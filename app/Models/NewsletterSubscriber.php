<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['email', 'source', 'abonne_le', 'desabonne_le'])]
class NewsletterSubscriber extends Model
{
    protected function casts(): array
    {
        return [
            'abonne_le' => 'datetime',
            'desabonne_le' => 'datetime',
        ];
    }

    #[Scope]
    protected function active(Builder $query): void
    {
        $query->whereNull('desabonne_le');
    }

    public function isActive(): bool
    {
        return $this->desabonne_le === null;
    }

    public function resubscribe(?string $source = null): void
    {
        $this->update([
            'abonne_le' => now(),
            'desabonne_le' => null,
            'source' => $source ?? $this->source,
        ]);
    }
}
