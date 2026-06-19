<?php

return [

    'tagline' => env('BLOG_TAGLINE', 'by Starboy'),

    'theme' => 'Tech & Entrepreneuriat africain',

    'meta_description' => 'Built in Benin — Tech, code, entrepreneuriat, crypto et opportunités pour la jeunesse africaine.',

    'contact_email' => env('BLOG_CONTACT_EMAIL'),

    'google_analytics_id' => env('GOOGLE_ANALYTICS_ID'),

    'og_image' => env('BLOG_OG_IMAGE'),

    'comments_notify_author' => env('BLOG_COMMENTS_NOTIFY_AUTHOR', true),

    /*
    | Comptes à ne jamais supprimer lors du nettoyage des utilisateurs de démo.
    | Séparer plusieurs e-mails par des virgules dans BLOG_PRESERVED_USER_EMAILS.
    */
    'preserved_user_emails' => array_values(array_filter(array_map(
        static fn (string $email) => strtolower(trim($email)),
        explode(',', (string) env('BLOG_PRESERVED_USER_EMAILS', 'harrydedji@gmail.com,millenium@gmail.com,exau@gmail.com'))
    ))),

];
