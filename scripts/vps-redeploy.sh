#!/usr/bin/env bash
# Redeploy Trackerdev on the VPS (git pull, composer, assets, migrate, caches).
#
# Usage (as root):
#   sudo -E /var/www/trackerdev/scripts/vps-redeploy.sh
#   sudo -E ./scripts/vps-redeploy.sh
#
# Optional env:
#   APP_DIR=/var/www/trackerdev
#   DEPLOY_USER=deploy
#   APP_NAME=trackerdev
#   SKIP_NPM=1
#   RESTART_QUEUE=1     # restart trackerdev-queue if that unit exists
#   GIT_BRANCH=master

set -euo pipefail

APP_NAME="${APP_NAME:-trackerdev}"
APP_DIR="${APP_DIR:-/var/www/trackerdev}"
DEPLOY_USER="${DEPLOY_USER:-deploy}"
SKIP_NPM="${SKIP_NPM:-0}"
RESTART_QUEUE="${RESTART_QUEUE:-1}"
GIT_BRANCH="${GIT_BRANCH:-master}"

log()  { printf '\n\033[1;32m==>\033[0m %s\n' "$*"; }
warn() { printf '\033[1;33mWARN:\033[0m %s\n' "$*" >&2; }
die()  { printf '\033[1;31mERROR:\033[0m %s\n' "$*" >&2; exit 1; }

[[ "$(id -u)" -eq 0 ]] || die "Run as root: sudo -E $0"

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
REPO_ROOT="$(cd "${SCRIPT_DIR}/.." && pwd)"
if [[ -f "${REPO_ROOT}/artisan" ]]; then
  APP_DIR="$REPO_ROOT"
fi

[[ -f "${APP_DIR}/artisan" ]] || die "App not found at ${APP_DIR}"
id "$DEPLOY_USER" >/dev/null 2>&1 || die "User ${DEPLOY_USER} does not exist"

log "Redeploying Trackerdev in ${APP_DIR}"

if [[ -d "${APP_DIR}/.git" ]]; then
  log "git pull"
  sudo -u "$DEPLOY_USER" git -C "$APP_DIR" fetch --all --prune || true
  sudo -u "$DEPLOY_USER" git -C "$APP_DIR" checkout "$GIT_BRANCH" || true
  sudo -u "$DEPLOY_USER" git -C "$APP_DIR" pull --ff-only
else
  warn "No .git in ${APP_DIR} — deploying tree as-is"
fi

sudo -u "$DEPLOY_USER" bash -lc "cd '${APP_DIR}' && php artisan down --retry=60 || true"

log "composer install"
sudo -u "$DEPLOY_USER" bash -lc "cd '${APP_DIR}' && composer install --no-dev --optimize-autoloader --no-interaction"

if [[ "$SKIP_NPM" == "1" ]]; then
  warn "SKIP_NPM=1 — skipping frontend build"
  [[ -d "${APP_DIR}/public/build" ]] || warn "public/build missing"
else
  log "npm ci && npm run build"
  if ! command -v npm >/dev/null 2>&1; then
    die "npm not found. Install Node 20 or set SKIP_NPM=1 with public/build present"
  fi
  sudo -u "$DEPLOY_USER" bash -lc "cd '${APP_DIR}' && npm ci && npm run build"
fi

log "migrate + caches"
sudo -u "$DEPLOY_USER" bash -lc "cd '${APP_DIR}' && php artisan migrate --force"
sudo -u "$DEPLOY_USER" bash -lc "cd '${APP_DIR}' && php artisan storage:link || true"
sudo -u "$DEPLOY_USER" bash -lc "cd '${APP_DIR}' && php artisan config:cache && php artisan route:cache && php artisan view:cache"
sudo -u "$DEPLOY_USER" bash -lc "cd '${APP_DIR}' && php artisan queue:restart || true"

if [[ "$RESTART_QUEUE" == "1" ]] && systemctl list-unit-files "${APP_NAME}-queue.service" 2>/dev/null | grep -q "${APP_NAME}-queue"; then
  log "restart ${APP_NAME}-queue"
  systemctl restart "${APP_NAME}-queue" || warn "Could not restart ${APP_NAME}-queue"
fi

chown -R "$DEPLOY_USER:www-data" "${APP_DIR}/storage" "${APP_DIR}/bootstrap/cache"
chmod -R ug+rwx "${APP_DIR}/storage" "${APP_DIR}/bootstrap/cache"

sudo -u "$DEPLOY_USER" bash -lc "cd '${APP_DIR}' && php artisan up"

log "Trackerdev redeploy OK"
