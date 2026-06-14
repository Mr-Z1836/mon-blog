<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['nom', 'email', 'message', 'lu_le'])]
class ContactMessage extends Model
{
    protected function casts(): array
    {
        return [
            'lu_le' => 'datetime',
        ];
    }

    public function markAsRead(): void
    {
        if ($this->lu_le === null) {
            $this->update(['lu_le' => now()]);
        }
    }
}
