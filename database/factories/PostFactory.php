<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    private const TITLES = [
        'Bien démarrer un projet Laravel proprement',
        'Organiser ses routes pour un blog maintenable',
        'Créer des formulaires robustes avec Form Request',
        'Optimiser les requêtes Eloquent sans complexité',
        'Structurer un dashboard admin simple et efficace',
        'Mettre en place une modération de commentaires',
        'Améliorer la qualité visuelle avec Blade et Tailwind',
        'Éviter les erreurs courantes de validation utilisateur',
        'Gérer les catégories et les tags intelligemment',
        'Concevoir un système de notation lisible et utile',
        'Ajouter des statistiques de vues par article',
        'Préparer son blog pour l indexation Google',
        'Rendre son application plus rapide en production',
        'Tester les routes essentielles d une application Laravel',
        'Créer des seeders réalistes pour développer plus vite',
        'Sécuriser l accès admin avec un middleware dédié',
        'Écrire un CRUD propre en Resource Controller',
        'Mettre en place un layout réutilisable pour le front',
        'Simplifier la navigation entre public et admin',
        'Déployer un projet Laravel sous Windows avec XAMPP',
        'Construire une page article claire et engageante',
        'Passer du prototype au produit utilisable',
        'Réduire la dette technique dès le début du projet',
        'Créer une expérience utilisateur fluide sur mobile',
        'Garder une base de code lisible en équipe',
        'Mettre en place des indexes SQL pertinents',
        'Mieux structurer les données d un blog personnel',
        'Écrire du contenu utile pour les développeurs débutants',
        'Choisir les bonnes conventions pour aller plus vite',
        'Industrialiser un blog Laravel pour la production',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->randomElement(self::TITLES);

        $excerpt = fake()->randomElement([
            'Un guide concret pour avancer étape par étape avec des choix simples et solides.',
            'Retour d expérience sur une implémentation réelle avec des conseils directement applicables.',
            'Résumé des bonnes pratiques pour gagner du temps sans sacrifier la qualité.',
        ]);

        $paragraphs = [
            'Quand on démarre un projet, la clarté de structure fait toute la différence au quotidien.',
            'Le plus important est de garder des conventions simples afin de faciliter la maintenance.',
            'En séparant bien les responsabilités, on réduit les erreurs et on accélère les évolutions.',
            'Les validations doivent être explicites pour offrir un retour utilisateur compréhensible.',
            'Un bon tableau de bord admin permet de piloter le contenu sans complexité technique.',
            'Enfin, quelques optimisations ciblées suffisent souvent à améliorer sensiblement les performances.',
        ];

        $content = "## Introduction\n\n".fake()->randomElement($paragraphs)."\n\n## Mise en pratique\n\n".implode("\n\n", fake()->randomElements($paragraphs, 2))."\n\n```php\n// Exemple Laravel\nRoute::get('/articles', [HomeController::class, 'index']);\n```\n\n## Conclusion\n\n".fake()->randomElement($paragraphs);

        return [
            'user_id' => User::factory(),
            'category_id' => Category::query()->inRandomOrder()->value('id') ?? Category::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'excerpt' => $excerpt,
            'image_path' => 'posts/images/default-cover.svg',
            'video_path' => null,
            'content' => $content,
            'youtube_url' => fake()->optional(0.2)->passthrough('https://www.youtube.com/watch?v=dQw4w9WgXcQ'),
            'is_published' => true,
            'is_featured' => fake()->boolean(15),
            'published_at' => fake()->dateTimeBetween('-3 months', 'now'),
        ];
    }
}
