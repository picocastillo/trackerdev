# Deploy en VPS — Trackerdev

Guía para publicar **Trackerdev** en el mismo VPS que RLujan (Nginx + PHP 8.3 + MariaDB, sin panel).

| Dominio | Proyecto | Path |
|---------|----------|------|
| `trackerdev.com.ar` (+ `www`) | **Este repo** | `/var/www/trackerdev` |
| `turemis.trackerdev.com.ar` | RLujan | `/var/www/rlujan` |

La instalación del stack del servidor (Nginx, PHP, MariaDB, swap, UFW, usuario `deploy`) la hace el script de RLujan. Doc completa del VPS: en el repo `rlujan` → `docs/DEPLOY-VPS.md`.

SSL: script `rlujan/scripts/vps-ssl.sh` (`landing` = este sitio, `turemis` = RLujan).

Redeploy de este sitio: [`scripts/vps-redeploy.sh`](../scripts/vps-redeploy.sh).

---

## Prerrequisitos

1. Ubuntu 24.04 LTS en el VPS.  
2. RLujan ya instalado con `scripts/vps-install.sh` (o al menos Nginx + PHP 8.3 + MariaDB + user `deploy`).  
3. DNS **A** de `trackerdev.com.ar` y `www.trackerdev.com.ar` → IP del VPS.

---

## 1. Base de datos (root)

Generá la clave y creá el user **en la misma sesión**:

```bash
DB_PASS="$(openssl rand -base64 32 | tr -d '\n+/=' | head -c 40)"
echo "Guardá esta clave: $DB_PASS"

sudo mysql <<EOF
CREATE DATABASE IF NOT EXISTS trackerdev CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
DROP USER IF EXISTS 'trackerdev'@'localhost';
CREATE USER 'trackerdev'@'localhost' IDENTIFIED BY '${DB_PASS}';
GRANT ALL PRIVILEGES ON trackerdev.* TO 'trackerdev'@'localhost';
FLUSH PRIVILEGES;
EOF
```

Si `php artisan migrate` da `Access denied for user 'trackerdev'@'localhost'`, el password del `.env` no coincide: regenerá con el bloque de arriba y actualizá `.env`.

---

## 2. Clonar e instalar (usuario `deploy`)

```bash
sudo -u deploy -i
cd /var/www
git clone git@github.com:picocastillo/trackerdev.git trackerdev
cd /var/www/trackerdev

composer install --no-dev --optimize-autoloader --no-interaction
cp .env.example .env
php artisan key:generate
```

---

## 3. Frontend (`npm run build`)

```bash
cd /var/www/trackerdev
npm ci
npm run build
```

Si Node no está instalado (root, una vez):

```bash
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt-get install -y nodejs
```

En VPS de **1 GB**, si falla por memoria:

```bash
NODE_OPTIONS='--max-old-space-size=512' npm run build
```

o buildeá en local/CI y subí `public/build`.

---

## 4. `.env` + migrate

```bash
nano /var/www/trackerdev/.env
```

```env
APP_NAME="Trackerdev"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://trackerdev.com.ar

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=trackerdev
DB_USERNAME=trackerdev
DB_PASSWORD=PEGAR_LA_MISMA_DB_PASS

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
```

No copies el `.env` de RLujan.

```bash
php artisan migrate --force
php artisan storage:link
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
exit

sudo chown -R deploy:www-data /var/www/trackerdev/storage /var/www/trackerdev/bootstrap/cache
sudo chmod -R ug+rwx /var/www/trackerdev/storage /var/www/trackerdev/bootstrap/cache
```

---

## 5. Nginx (root)

```bash
sudo tee /etc/nginx/sites-available/trackerdev >/dev/null <<'EOF'
server {
    listen 80;
    listen [::]:80;
    server_name trackerdev.com.ar www.trackerdev.com.ar;
    root /var/www/trackerdev/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;
    charset utf-8;
    client_max_body_size 20M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF

sudo ln -sfn /etc/nginx/sites-available/trackerdev /etc/nginx/sites-enabled/trackerdev
sudo nginx -t && sudo systemctl reload nginx
```

---

## 6. SSL (Let's Encrypt)

El script está en el repo RLujan:

```bash
cd /var/www/rlujan
export CERTBOT_EMAIL=admin@trackerdev.com.ar
sudo -E ./scripts/vps-ssl.sh landing
```

Eso certifica `trackerdev.com.ar` y `www.trackerdev.com.ar`.  
Renovación: automática con Certbot en Ubuntu (no hace falta cron manual).

Probá: `https://trackerdev.com.ar`

---

## 7. Cola / cron (opcional)

Si la app no usa queues ni scheduler, no configures nada.

Queue:

```bash
sudo tee /etc/systemd/system/trackerdev-queue.service >/dev/null <<'EOF'
[Unit]
Description=trackerdev Laravel queue worker
After=network.target mariadb.service

[Service]
User=deploy
Group=www-data
Restart=always
RestartSec=5
WorkingDirectory=/var/www/trackerdev
ExecStart=/usr/bin/php artisan queue:work database --sleep=1 --tries=3 --timeout=90
Nice=10

[Install]
WantedBy=multi-user.target
EOF

sudo systemctl daemon-reload
sudo systemctl enable --now trackerdev-queue
```

Scheduler (crontab de `deploy`, línea aparte de RLujan):

```cron
* * * * * cd /var/www/trackerdev && php artisan schedule:run >> /dev/null 2>&1
```

---

## 8. Redeploy

Script del repo:

```bash
cd /var/www/trackerdev
sudo -E ./scripts/vps-redeploy.sh
```

Opcional:

```bash
SKIP_NPM=1 sudo -E ./scripts/vps-redeploy.sh          # si public/build ya viene listo
RESTART_QUEUE=0 sudo -E ./scripts/vps-redeploy.sh     # no tocar systemd queue
GIT_BRANCH=otro-branch sudo -E ./scripts/vps-redeploy.sh   # override (default: master)
```

Hace: `git pull` → `composer install` → `npm ci && npm run build` → `migrate` → caches → `artisan up`.  
Si existe el unit `trackerdev-queue`, lo reinicia (salvo `RESTART_QUEUE=0`).

Para RLujan usá el script del otro repo: `/var/www/rlujan/scripts/vps-redeploy.sh`.

---

## Checklist

- [ ] DNS `trackerdev.com.ar` / `www` → VPS  
- [ ] DB + user `trackerdev` (password = `.env`)  
- [ ] `composer install` + `npm run build`  
- [ ] `APP_URL=https://trackerdev.com.ar`  
- [ ] Nginx vhost + `nginx -t`  
- [ ] `vps-ssl.sh landing`  
- [ ] `https://trackerdev.com.ar` responde  

No pegues passwords reales en este archivo.
