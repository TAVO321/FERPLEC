<?php

namespace App\Models;

class Guide extends Tip
{
    protected $table = 'tips';

    public const AREAS = [
        'Electricidad' => 'Electricidad',
        'Plomería' => 'Plomería',
        'Ferretería' => 'Ferretería',
        'Construcción' => 'Construcción',
    ];

    public function getRouteKeyName(): string
    {
        return 'id';
    }

    public function getAreaLabelAttribute(): string
    {
        return $this->category ?? ucfirst((string) $this->area);
    }
}
