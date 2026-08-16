<?php

namespace App\Models;

use App\Casts\AsVector;
use App\Observers\ProductObserver;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([ProductObserver::class])]
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
        'notes',
        'accords',
        'price',
        'image_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'notes' => 'array',
            'accords' => 'array',
            'price' => 'decimal:2',
            'embedding' => AsVector::class,
        ];
    }

    /**
     * Build the text representation of the product used to generate its embedding.
     */
    public function embeddingText(): string
    {
        return implode("\n", [
            "Name: {$this->name}",
            'Notes: '.implode(', ', $this->notes ?? []),
            'Accords: '.implode(', ', $this->accords ?? []),
            "Description: {$this->description}",
        ]);
    }
}
