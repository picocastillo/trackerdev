# SEO local — TrackerDev

Pasos **fuera del código** para que Google indexe y muestre TrackerDev en búsquedas locales. El sitio ya tiene titles, description, keywords, canonical, Open Graph, JSON-LD (sede Santo Tomé) y `sitemap.xml`.

Sede canónica: **Santo Tomé**. Zona de servicio: **Santa Fe capital** y Gran Santa Fe.

---

## 1. Producción

En el VPS, confirmá:

```
APP_URL=https://trackerdev.com.ar
```

Los canonicals del landing usan ese host (si `APP_URL` es localhost, el código cae a `https://trackerdev.com.ar`).

---

## 2. Google Search Console

La propiedad ya está verificada:

- Meta: `google-site-verification` en el landing
- Archivo: `public/google938d7358fa09d136.html`

En [Search Console](https://search.google.com/search-console):

1. Abrí la propiedad `https://trackerdev.com.ar`.
2. **Sitemaps** → enviar `https://trackerdev.com.ar/sitemap.xml`.
3. **Inspección de URLs** → solicitar indexación de:
   - `https://trackerdev.com.ar/`
   - `https://trackerdev.com.ar/methodology`
   - `https://trackerdev.com.ar/projects`
   - `https://trackerdev.com.ar/contact`

Repetí la inspección después de cada deploy relevante.

---

## 3. Google Business Profile

Esto es lo que más mueve el pack local (“cerca de mí”, Maps).

1. Creá o reclamá el perfil: [Google Business Profile](https://business.google.com/).
2. Nombre: **TrackerDev**.
3. Ubicación: **Santo Tomé, Santa Fe, Argentina**.
4. Categorías sugeridas: Software company / Web designer / Mobile app developer (o equivalentes en español).
5. NAP (tiene que coincidir con el sitio y las redes):
   - Nombre: TrackerDev
   - Teléfono / WhatsApp: `+54 342 528-7592`
   - Sitio: `https://trackerdev.com.ar`
6. Áreas de servicio: Santo Tomé, Santa Fe capital, Gran Santa Fe.
7. Completá descripción, horarios, fotos y pedí reseñas reales.

Si no hay calle pública, usá perfil “atiende en la zona del cliente” con Santo Tomé como base.

---

## 4. NAP consistente

El mismo nombre, teléfono y URL en:

- Sitio (footer / schema)
- Google Business Profile
- Facebook: `https://www.facebook.com/trackerdev`
- Instagram: `https://www.instagram.com/trackerdev/`
- LinkedIn: `https://www.linkedin.com/in/trackerdev-solutions`
- WhatsApp Business

Cualquier diferencia (otro nombre, otro número) diluye el SEO local.

---

## 5. Google Analytics

GA4 `G-TY9Z038WBJ` ya está en el landing. En Analytics: **Admin → Product links → Search Console** y vinculá la propiedad.

---

## 6. Opcional

- [Bing Webmaster Tools](https://www.bing.com/webmasters): importar desde Search Console o verificar el mismo dominio y enviar el sitemap.
- Embed de Google Maps en el sitio cuando tengas dirección de calle pública.
- Rich Results Test: [https://search.google.com/test/rich-results](https://search.google.com/test/rich-results) sobre la home.

---

## 7. Después del deploy

1. Inspeccionar la home en Search Console.
2. Validar JSON-LD en Rich Results Test.
3. Esperar días o semanas para queries locales (`desarrollo de software santo tomé`, `desarrollo web santa fe`, etc.). El código no acelera esa cola.
