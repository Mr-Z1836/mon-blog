# Harry's Blog

Blog personnel Laravel 13 + Breeze + MySQL (XAMPP en local).

## Fonctionnalités

- **Visiteur** : articles, filtres catégorie/tag, recherche, mode sombre, temps de lecture, sommaire, partage social, séries, Prism.js
- **Connecté** : réactions, commentaires imbriqués, signalements, notes, avis, favoris, profil enrichi
- **Admin** : dashboard stats, CRUD, Tiptap, SEO, séries, médiathèque, modération, utilisateurs
- **SEO** : `/sitemap.xml`, `/robots.txt`, Open Graph

## Installation locale

```powershell
cd c:\xampp\htdocs\mon-blog
# MySQL démarré dans XAMPP, puis :
powershell -ExecutionPolicy Bypass -File scripts\deploy.ps1
php artisan db:seed   # optionnel : données de démo
php artisan serve
```

Comptes démo (après seed) : `admin@blog.test` / `password` — `test@example.com` / `password`

Bases MySQL : exécuter `scripts/create-databases.sql` si besoin.

## Déploiement production

1. Uploader le projet (sans `vendor/`, `node_modules/`, `.env`).
2. Sur le serveur, copier `.env.production.example` → `.env` et renseigner `APP_URL`, `DB_*`, mail.
3. Document root = dossier `public/`.
4. Lancer :

```bash
chmod +x scripts/deploy.sh
./scripts/deploy.sh
```

Ou sous Windows / Composer :

```bash
composer run deploy   # après npm run build sur le serveur
```

**Production** : PHP **8.3.31+** (ou 8.4+), `APP_DEBUG=false`, HTTPS, `php artisan storage:link`, droits écriture sur `storage/` et `bootstrap/cache/`.

Le `composer.lock` est figé pour PHP 8.3 (`config.platform.php` dans `composer.json`). Ne pas régénérer le lock avec PHP 8.5+ sans cette contrainte, sinon le déploiement en 8.3 échouera.

Ne jamais versionner `.env`. Le build front (`public/build/`) doit exister sur le serveur (généré par `npm run build`).

## Routes utiles

| Zone | URLs |
|------|------|
| Public | `/`, `/articles`, `/articles/{slug}` |
| Auth | `/login`, `/register`, `/profile` |
| Admin | `/admin/dashboard`, `/admin/posts`, `/admin/media`, `/admin/reports` |
| SEO | `/sitemap.xml`, `/robots.txt` |
