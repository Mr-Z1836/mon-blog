# Built in Benin

Blog **Tech & Entrepreneuriat africain** — Laravel 13 + Breeze + MySQL, déployé sur Railway.

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

Compte admin (après seed) : `harrydedji@gmail.com` / `password` — Harry DEDJI (`@starboy`)

Le seeder crée **6 catégories**, **15 tags** et **6 articles** contextualisés Bénin/Afrique. Aucun utilisateur fictif ni abonné newsletter inventé.

Bases MySQL : exécuter `scripts/create-databases.sql` si besoin.

## Déploiement Railway

1. Crée un projet Railway avec **deux services** : l'app (GitHub `mon-blog`) + **MySQL**.
2. Lie le service MySQL au service web (Variables → référence du plugin MySQL).
3. Variables obligatoires sur le service web :
   - `APP_KEY` → `php artisan key:generate --show` en local
   - `APP_URL` → URL Railway du service web
   - `APP_ENV=production`, `APP_DEBUG=false`
   - `BLOG_CONTACT_EMAIL`, `BLOG_TAGLINE`, etc.
4. **Ne fixe pas** `DB_HOST=127.0.0.1` sur Railway — l'app lit `MYSQLHOST` du plugin MySQL.
5. Premier déploiement : `RUN_DB_SEED=true` (puis repasse à `false`).
6. Start command (déjà dans `railway.toml`) : `bash scripts/railway-start.sh`

Erreur `Connection refused ... 127.0.0.1:3306` → MySQL non lié ou `DB_HOST` forcé en local sur Railway.

## Déploiement production (VPS / mutualisé)

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
