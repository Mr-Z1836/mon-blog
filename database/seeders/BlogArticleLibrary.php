<?php

namespace Database\Seeders;

/**
 * Contenus éditoriaux du blog — 6 articles (un par catégorie), contextualisés Bénin / Afrique.
 */
class BlogArticleLibrary
{
    /**
     * @return list<array<string, mixed>>
     */
    public static function definitions(): array
    {
        return [
            [
                'category' => 'dev-code',
                'slug' => 'deployer-laravel-railway-depuis-le-benin',
                'title' => 'Déployer un projet Laravel sur Railway depuis le Bénin',
                'excerpt' => 'Guide complet : GitHub, variables d\'environnement, MySQL managé et commandes de déploiement — même avec une connexion instable à Cotonou.',
                'tags' => ['laravel', 'railway', 'benin', 'php'],
                'image_path' => 'posts/images/deployer-laravel-railway.png',
                'is_pinned' => true,
                'is_featured' => true,
                'content' => self::md([
                    ['title' => 'Pourquoi sortir du localhost ?', 'paragraphs' => [
                        'Tant que ton application tourne uniquement sur ton PC, personne ne peut la tester, la partager ou la payer. Au Bénin comme ailleurs en Afrique, beaucoup de jeunes développeurs bloquent à cette étape : peur de la facturation, connexion coupée, ou jargon DevOps incompréhensible.',
                        'Railway est une option intéressante parce qu\'elle relie ton dépôt GitHub à un environnement de production en quelques clics. Tu n\'as pas besoin d\'un VPS à configurer manuellement ni d\'un expert sysadmin. Pour un blog, une landing page ou un MVP Laravel, c\'est largement suffisant.',
                    ]],
                    ['title' => 'Préparer le projet avant le déploiement', 'ordered' => [
                        'Vérifie que ton application fonctionne en local avec `php artisan serve` et que tes migrations passent.',
                        'Pousse ton code sur GitHub (branche `main`). Dans Railway, crée un nouveau projet à partir de ce dépôt.',
                        'Ajoute un service **MySQL** dans le même projet Railway.',
                        'Recopie les variables Railway (`MYSQLHOST`, `MYSQLPORT`, etc.) dans ton service Laravel : `DB_CONNECTION=mysql`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.',
                        'Configure aussi `APP_KEY`, `APP_ENV=production`, `APP_DEBUG=false` et `APP_URL` avec l\'URL Railway fournie.',
                    ]],
                    ['title' => 'Commandes essentielles en production', 'intro' => [
                        'Après le premier déploiement, connecte-toi au shell Railway ou configure une commande de release pour exécuter les migrations. Sans ça, ton site affichera des erreurs de tables manquantes.',
                    ], 'items' => [
                        'Exécuter `php artisan migrate --force` après chaque déploiement majeur.',
                        'Lancer `php artisan storage:link` si tu gères des uploads.',
                        'Builder les assets front avec `npm run build` avant le push, ou intégrer une étape de build dans le pipeline.',
                    ]],
                    ['title' => 'Gérer une connexion instable', 'items' => [
                        'Travaille par petits commits et pousse dès qu\'un bloc fonctionne.',
                        'Garde un fichier `.env.production.example` dans le repo pour ne pas oublier de variables.',
                        'En cas de coupure réseau pendant un déploiement, Railway reprendra au prochain push sans tout casser.',
                    ], 'outro' => [
                        'Sur Built in Benin, on utilise exactement cette stack. Si tu bloques sur une erreur précise (Composer, PHP 8.3, permissions), écris-moi via le formulaire contact — on pourra en faire un article dédié.',
                    ]],
                ], 'php', "composer install --no-dev --optimize-autoloader\nphp artisan migrate --force\nphp artisan config:cache\nphp artisan route:cache"),
            ],
            [
                'category' => 'entrepreneuriat',
                'slug' => 'valider-une-idee-startup-cotonou',
                'title' => 'Comment valider une idée de startup à Cotonou (méthode terrain)',
                'excerpt' => '15 interviews, problème réel, willingness to pay : la méthode simple avant de dépenser en dev ou en pub Facebook.',
                'tags' => ['startup', 'cotonou', 'benin', 'afrique'],
                'image_path' => 'posts/images/valider-startup-cotonou.png',
                'is_featured' => true,
                'content' => self::md([
                    ['title' => 'L\'idée ne suffit pas', 'paragraphs' => [
                        'Chaque semaine, on entend « j\'ai une idée d\'app qui va changer l\'Afrique ». Le problème : sans validation terrain, tu risques 3 mois de dev pour un produit que personne ne paie.',
                        'Valider, ce n\'est pas faire un sondage Instagram. C\'est parler à de vrais utilisateurs potentiels, comprendre leur problème actuel, et vérifier s\'ils paieraient pour une solution.',
                    ]],
                    ['title' => 'La méthode des 15 interviews', 'ordered' => [
                        'Identifie un segment précis : « commerçants de Dantokpa qui vendent sur WhatsApp », pas « tous les entrepreneurs ».',
                        'Contacte 15 personnes de ce segment.',
                        'Pose des questions ouvertes : comment ils gèrent le problème aujourd\'hui, combien ça leur coûte en temps ou argent, ce qu\'ils ont déjà essayé.',
                        'Écoute d\'abord — ne présente pas ton idée tout de suite.',
                        'Si 10 personnes sur 15 décrivent le même pain point, tu tiens peut-être quelque chose.',
                    ]],
                    ['title' => 'Signaux qu\'il faut avancer', 'items' => [
                        'Les gens demandent « c\'est dispo quand ? » sans que tu aies pitché agressivement.',
                        'Quelqu\'un accepte de payer un acompte ou un pilote.',
                        'Un commerçant te demande de tester une version manuelle (Google Sheet + WhatsApp) avant l\'app.',
                    ], 'outro' => [
                        'Ces signaux valent plus qu\'un logo et un pitch deck.',
                    ]],
                    ['title' => 'Signaux d\'alerte', 'items' => [
                        '« C\'est intéressant » sans engagement concret.',
                        'Personne ne veut tester, même gratuitement.',
                        'Tu dois convaincre pendant 30 minutes et ton interlocuteur décrochent.',
                    ], 'outro' => [
                        'Dans ce cas, pivote ou change de segment avant de coder.',
                    ]],
                ]),
            ],
            [
                'category' => 'crypto-finance',
                'slug' => 'mobile-money-fintech-afrique-ouest',
                'title' => 'Mobile Money en Afrique de l\'Ouest : guide pour développeurs et entrepreneurs',
                'excerpt' => 'MTN MoMo, Moov, Orange Money : comprendre l\'écosystème avant de coder une app de paiement ou une fintech.',
                'tags' => ['fintech', 'mobile-money', 'afrique'],
                'image_path' => 'posts/images/mobile-money-fintech-afrique-ouest.png',
                'is_featured' => true,
                'content' => self::md([
                    ['title' => 'Pourquoi c\'est prioritaire avant la crypto', 'intro' => [
                        'Des centaines de millions d\'Africains utilisent déjà Mobile Money pour payer courses, factures, salaires et transferts familiaux. Avant de parler Bitcoin, comprends cet infrastructure : c\'est souvent là que se trouvent tes vrais utilisateurs.',
                    ], 'items' => [
                        'E-commerce et marketplaces locales.',
                        'Dons et collectes communautaires.',
                        'Abonnements et paiements récurrents.',
                        'Intégration via agrégateurs ou APIs opérateurs selon les pays.',
                    ]],
                    ['title' => 'Comment ça marche (simplifié)', 'ordered' => [
                        'L\'utilisateur a un solde lié à son numéro de téléphone.',
                        'Il envoie de l\'argent via USSD ou application mobile.',
                        'Les agents cash-in/cash-out convertissent cash ↔ solde digital.',
                        'Les marchands peuvent recevoir des paiements marchands.',
                    ], 'outro' => [
                        'Chaque pays et opérateur a ses règles. Au Bénin, informe-toi sur les offres MTN et Moov actuelles et leurs APIs partenaires.',
                    ]],
                    ['title' => 'Opportunités pour les jeunes', 'items' => [
                        'Intégrateur freelance pour PME locales.',
                        'Outils de suivi de trésorerie pour commerçants.',
                        'Formation des utilisateurs peu à l\'aise avec le digital.',
                        'Partenariat avec un agrégateur certifié plutôt que de gérer la conformité seul.',
                    ]],
                ]),
            ],
            [
                'category' => 'vie-etudiant',
                'slug' => 'organisation-etudiant-informatique',
                'title' => 'S\'organiser entre cours, code et vie perso (sans burnout)',
                'excerpt' => 'Emploi du temps réaliste pour un étudiant en informatique à Cotonou qui code le soir et le week-end.',
                'tags' => ['etudes', 'jeunesse', 'afrique'],
                'image_path' => 'posts/images/organisation-etudiant-informatique.png',
                'content' => self::md([
                    ['title' => 'Le mythe du hustle 24/7', 'paragraphs' => [
                        'Coder jusqu\'à 3h chaque nuit avant les partiels mène à l\'épuisement, pas à un job Google. Les étudiants qui progressent vite ont un rythme **soutenable** : créneaux fixes, pauses, et une vie sociale minimale pour rester sane.',
                    ]],
                    ['title' => 'Template de semaine', 'items' => [
                        '**Lundi & jeudi** : 1h30 projet perso après les cours.',
                        '**Mardi** : révision cours + exercices.',
                        '**Mercredi** : off (récupération).',
                        '**Vendredi** : communauté (meetup, Discord, pair programming).',
                        '**Samedi matin** : deep work 3h.',
                        '**Dimanche** : planification de la semaine suivante.',
                    ], 'outro' => [
                        'Ajuste selon ton emploi du temps universitaire. L\'important : écrire le plan et le coller au mur.',
                    ]],
                    ['title' => 'Outils simples', 'items' => [
                        'Google Calendar ou un carnet papier pour bloquer tes créneaux.',
                        'Todo list limitée à 3 tâches par jour maximum.',
                        'Pas 15 outils de productivité — tu perdrais du temps à les configurer.',
                    ]],
                ]),
            ],
            [
                'category' => 'opportunites',
                'slug' => 'bourses-tech-jeunes-africains-2026',
                'title' => 'Bourses et programmes tech pour jeunes Africains (guide 2026)',
                'excerpt' => 'Mastercard Foundation, Google, programmes régionaux : comment préparer ton dossier et ne pas rater les dates.',
                'tags' => ['bourse', 'afrique', 'jeunesse', 'etudes'],
                'image_path' => 'posts/images/bourses-tech-jeunes-africains-2026.png',
                'is_pinned' => true,
                'is_featured' => true,
                'content' => self::md([
                    ['title' => 'Pourquoi tant de places restent vides', 'items' => [
                        'L\'information arrive souvent tard.',
                        'Beaucoup de programmes sont documentés uniquement en anglais.',
                        'Les dossiers semblent complexes sans méthode.',
                        'Des étudiants béninois qualifiés ne postulent pas par manque de préparation — pas par manque de talent.',
                    ]],
                    ['title' => 'Préparer un dossier solide (6 mois avant)', 'items' => [
                        'Relevés de notes à jour.',
                        'Lettre de motivation personnalisée (pas de copier-coller).',
                        'Projet perso documenté avec lien de démo.',
                        'Lettre de recommandation d\'un professeur ou mentor.',
                        'Certificats en ligne reconnus (Google, Cisco, freeCodeCamp) en complément.',
                    ], 'outro' => [
                        'Crée un dossier Google Drive centralisé : CV, lettres, scans et liens projets. Mets-le à jour dès qu\'un nouveau projet sort.',
                    ]],
                    ['title' => 'Où surveiller', 'items' => [
                        'Sites officiels des universités partenaires.',
                        'Pages LinkedIn des fondations et programmes.',
                        'Groupes Facebook « bourses étudiants Afrique ».',
                        'Newsletter Built in Benin.',
                        'Alertes Google sur « scholarship Africa tech 2026 ».',
                    ]],
                    ['title' => 'Calendrier type', 'intro' => [
                        'Beaucoup de programmes ouvrent entre janvier et mars pour la rentrée suivante.',
                    ], 'items' => [
                        'Note chaque deadline dans ton calendrier **3 semaines avant** la date limite.',
                        'Prépare les pièces manquantes la semaine précédente, pas la veille.',
                    ]],
                ]),
            ],
            [
                'category' => 'cybersecurite',
                'slug' => 'debuter-cybersecurite-benin-ctf-outils',
                'title' => 'Débuter en cybersécurité au Bénin : CTF, outils et bonnes habitudes',
                'excerpt' => 'Parcours réaliste pour apprendre la sécurité informatique : labs en ligne, premiers CTF, outils essentiels et erreurs à éviter quand on débute à Cotonou.',
                'tags' => ['benin', 'afrique', 'jeunesse', 'etudes'],
                'image_path' => 'posts/images/debuter-cybersecurite-benin.png',
                'is_featured' => true,
                'content' => self::md([
                    ['title' => 'Pourquoi la cybersécurité intéresse le continent', 'paragraphs' => [
                        'Les entreprises, banques, administrations et startups africaines recrutent de plus en plus des profils capables de sécuriser applications, réseaux et données. Au Bénin, la demande existe — mais les parcours d\'apprentissage structurés restent rares en français.',
                        'La bonne nouvelle : tu peux commencer avec un laptop classique, une connexion internet et de la curiosité. Pas besoin d\'un lab physique hors de prix pour comprendre les bases.',
                    ]],
                    ['title' => 'Par où commencer (ordre conseillé)', 'ordered' => [
                        '**Réseaux & Linux** : IP, ports, DNS, commandes de base (`ls`, `grep`, `chmod`).',
                        '**Web** : HTTP, cookies, sessions, injections SQL et XSS (en environnement légal uniquement).',
                        '**Cryptographie pratique** : hashing, HTTPS, mots de passe — pas la théorie pure d\'abord.',
                    ], 'intro' => [
                        'Plateformes utiles pour t\'entraîner :',
                    ], 'items' => [
                        'TryHackMe (parcours débutant).',
                        'PicoCTF et OverTheWire Bandit.',
                        '45 minutes par jour plutôt que 8h un samedi puis plus rien pendant deux semaines.',
                    ]],
                    ['title' => 'Les CTF : apprendre en jouant', 'intro' => [
                        'Un CTF (Capture The Flag) est un challenge où tu résous des énigmes techniques pour gagner des « flags ». C\'est l\'un des meilleurs moyens de progresser : tu cherches, tu rates, tu documentes, tu recommences.',
                    ], 'items' => [
                        'Forme un petit groupe Telegram ou Discord à Cotonou.',
                        'Fixez un créneau hebdomadaire d\'entraînement.',
                        'Participez à des CTF en ligne : picoCTF, Root-Me, CTFtime.',
                        'Même si vous finissez derniers, vous apprenez.',
                    ]],
                    ['title' => 'Outils à connaître (sans devenir script kiddie)', 'items' => [
                        '**Nmap** — découvrir les services ouverts (uniquement sur tes machines ou labs autorisés).',
                        '**Burp Suite Community** — analyser le trafic web.',
                        '**Wireshark** — comprendre ce qui circule sur le réseau.',
                        '**VirtualBox** + VM Kali ou Parrot pour isoler tes tests.',
                    ], 'outro' => [
                        'Règle d\'or : n\'attaque jamais un site, un Wi-Fi ou un compte qui ne t\'appartient pas et pour lequel tu n\'as pas d\'autorisation écrite. La curiosité mal canalisée peut te mettre dans de vrais problèmes juridiques.',
                    ]],
                    ['title' => 'Construire un profil crédible', 'items' => [
                        'Publie des **writeups** après chaque challenge résolu (GitHub ou ce blog).',
                        'Contribue à l\'open source sécurité (OWASP, outils documentés).',
                        'Vise des certifications accessibles (Security+, eJPT, PNPT) en complément de la pratique.',
                    ], 'outro' => [
                        'Un portfolio de labs et writeups vaut souvent plus qu\'un PDF sans mise en pratique.',
                    ]],
                ], 'bash', "nmap -sV localhost\nsqlmap -h  # toujours sur lab autorisé uniquement"),
            ],
        ];
    }

    /**
     * @param  list<array{title: string, intro?: list<string>, paragraphs?: list<string>, items?: list<string>, ordered?: list<string>, outro?: list<string>}>  $sections
     */
    public static function md(array $sections, ?string $codeLang = null, ?string $code = null): string
    {
        $markdown = '';

        foreach ($sections as $section) {
            $markdown .= "## {$section['title']}\n\n";

            foreach ($section['intro'] ?? $section['paragraphs'] ?? [] as $paragraph) {
                $markdown .= "{$paragraph}\n\n";
            }

            foreach ($section['ordered'] ?? [] as $index => $item) {
                $markdown .= ($index + 1).". {$item}\n";
            }

            if (! empty($section['ordered'])) {
                $markdown .= "\n";
            }

            foreach ($section['items'] ?? [] as $item) {
                $markdown .= "- {$item}\n";
            }

            if (! empty($section['items'])) {
                $markdown .= "\n";
            }

            foreach ($section['outro'] ?? [] as $paragraph) {
                $markdown .= "{$paragraph}\n\n";
            }
        }

        if ($codeLang && $code) {
            $markdown .= "```{$codeLang}\n{$code}\n```\n\n";
        }

        $markdown .= "## Pour aller plus loin\n\n";
        $markdown .= "Tu as une question sur ce sujet ou une expérience à partager depuis le Bénin ? ";
        $markdown .= "Laisse un commentaire sur cet article ou écris via la page [Contact](/contact). ";
        $markdown .= "Abonne-toi à la newsletter Built in Benin pour ne pas manquer les prochains guides.\n";

        return $markdown;
    }
}
