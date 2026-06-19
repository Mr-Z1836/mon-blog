<?php

namespace App\Support;

class ForbiddenUsernames
{
    /**
     * Pseudos interdits (réservés ou offensants) — comparaison exacte, insensible à la casse.
     *
     * @return list<string>
     */
    public static function all(): array
    {
        return [
            'admin',
            'administrateur',
            'superuser',
            'superadmin',
            'root',
            'system',
            'systeme',
            'moderateur',
            'moderator',
            'modo',
            'support',
            'helpdesk',
            'webmaster',
            'harrydedji',
            'starboy',
            'built_in_benin',
            'builtinbenin',
            'pute',
            'putain',
            'merde',
            'connard',
            'connasse',
            'salope',
            'encule',
            'enculer',
            'nique',
            'niquer',
            'foutre',
            'batard',
            'batarde',
            'pd',
            'negro',
            'negre',
            'bicot',
            'pedophile',
            'pedo',
            'nazi',
            'hitler',
            'terroriste',
        ];
    }

    public static function isForbidden(string $username): bool
    {
        return in_array(strtolower(trim($username)), self::all(), true);
    }
}
