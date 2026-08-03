#!/usr/bin/env bash
# ╔══════════════════════════════════════════════════════════╗
# ║  GÜMRÜK PLUS — local dev quick-start                   ║
# ║  Run from the repo root: bash gp-start.sh              ║
# ╚══════════════════════════════════════════════════════════╝
set -Eeuo pipefail

on_error() {
  local exit_code=$?
  echo ""
  echo "✗  gp-start.sh failed at line ${BASH_LINENO[0]} (exit code $exit_code)"
  echo "   Command: ${BASH_COMMAND}"
  exit "$exit_code"
}
trap on_error ERR

GP_THEME="gumruk-plus"
WP_URL="http://localhost:8082"
WP_ADMIN="admin"
WP_PASS="admin123"
WP_EMAIL="admin@gumrukplus.local"
WP_TITLE="Gümrük Plus"
OWNER_USER="owner"
OWNER_PASS="owner123"

MYSQL_WAIT_TIMEOUT=60
MYSQL_WAIT_INTERVAL=2

echo ""
echo "▶  Step 1 — copy .env if needed"
if [ ! -f .env ]; then
  cp .env.example .env
fi
set -a
source .env
set +a
echo "    .env ready"

echo ""
echo "▶  Step 2 — spin up Docker"
docker compose up -d

echo "    Waiting for MySQL to accept connections (timeout ${MYSQL_WAIT_TIMEOUT}s)..."
elapsed=0
until docker compose exec -T db mysqladmin ping -h 127.0.0.1 \
  -u root -p"${DB_ROOT_PASSWORD:-rootpass}" --silent >/dev/null 2>&1; do
  if [ "$elapsed" -ge "$MYSQL_WAIT_TIMEOUT" ]; then
    echo "    ✗  MySQL did not become ready within ${MYSQL_WAIT_TIMEOUT}s"
    echo "       Check logs with: docker compose logs db"
    exit 1
  fi
  sleep "$MYSQL_WAIT_INTERVAL"
  elapsed=$((elapsed + MYSQL_WAIT_INTERVAL))
done
echo "    ✓  MySQL ready after ${elapsed}s"

echo ""
echo "▶  Step 2b — ensure WP-CLI is available in the container"
if ! docker compose exec -T wordpress test -x /usr/local/bin/wp; then
  echo "    Installing WP-CLI..."
  docker compose exec -T wordpress curl -fsSL -o /usr/local/bin/wp \
    https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
  docker compose exec -T wordpress chmod +x /usr/local/bin/wp
fi
echo "    ✓  WP-CLI ready"

echo ""
echo "▶  Step 3 — install WordPress via WP-CLI"
docker compose exec wordpress wp core install \
  --url="$WP_URL" \
  --title="$WP_TITLE" \
  --admin_user="$WP_ADMIN" \
  --admin_password="$WP_PASS" \
  --admin_email="$WP_EMAIL" \
  --skip-email \
  --allow-root

echo ""
echo "▶  Step 4 — install & activate WooCommerce"
docker compose exec wordpress wp plugin install woocommerce --activate --allow-root
docker compose exec wordpress wp wc --user=admin tool run install_pages --allow-root

echo ""
echo "▶  Step 5 — install & activate WooCommerce plugin (gp-quick-edit)"
docker compose exec wordpress wp plugin activate gp-quick-edit --allow-root || \
  echo "    (gp-quick-edit not found — skipping, activate manually)"

echo ""
echo "▶  Step 6 — activate theme"
WORDPRESS_CID="$(docker compose ps -q wordpress)"
if [ -z "$WORDPRESS_CID" ]; then
  echo "    ✗  wordpress container is not running — aborting"
  exit 1
fi
docker compose exec wordpress wp theme install storefront --allow-root
docker compose exec wordpress wp theme activate $GP_THEME --allow-root
echo "    Theme activated: $GP_THEME"

echo ""
echo "▶  Step 7 — create owner Quick-Edit user"
if ! docker compose exec wordpress wp user create \
  "$OWNER_USER" owner@gumrukplus.local \
  --role=gp_quick_edit \
  --user_pass="$OWNER_PASS" \
  --allow-root; then
  echo "    (owner user creation skipped — user may already exist, or 'gp_quick_edit' role isn't registered yet; check Step 5)"
fi

echo ""
echo "▶  Step 8 — seed theme mods for homepage"
seed_theme_mod() {
  local key="$1" value="$2"
  if ! docker compose exec -T wordpress wp option patch insert theme_mods_$GP_THEME \
    "$key" "$value" --allow-root >/dev/null 2>&1; then
    if ! docker compose exec wordpress wp option patch update theme_mods_$GP_THEME \
      "$key" "$value" --allow-root; then
      echo "    ($key seeding failed — check theme is activated and option name is correct)"
    fi
  fi
}
seed_theme_mod gp_whatsapp_number "905000000000"
seed_theme_mod gp_deals_marquee "Ücretsiz kargo 500₺ ve üzeri,Free shipping over 500₺,Yeni ürünler eklendi,New arrivals in stock"

echo ""
echo "▶  Step 9 — PHP lint"
LINT_OUTPUT="$(docker compose exec wordpress find /var/www/html/wp-content/themes/$GP_THEME \
  -name '*.php' -exec php -l {} \; 2>&1 | grep -v "No syntax errors" || true)"
if [ -n "$LINT_OUTPUT" ]; then
  echo "$LINT_OUTPUT"
  echo "    ✗  PHP syntax errors found — see above"
  exit 1
fi
echo "    ✓  No PHP syntax errors"

echo ""
echo "╔══════════════════════════════════════╗"
echo "║  ✓  ALL DONE                        ║"
echo "╠══════════════════════════════════════╣"
echo "║  Site:      $WP_URL"
echo "║  Admin:     $WP_URL/wp-admin"
echo "║  User:      $WP_ADMIN / $WP_PASS"
echo "║  Owner:     $OWNER_USER / $OWNER_PASS"
echo "╚══════════════════════════════════════╝"
echo ""
