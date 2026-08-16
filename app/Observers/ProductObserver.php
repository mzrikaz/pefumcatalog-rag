<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Str;

class ProductObserver
{
    /**
     * The attributes that, when changed, require the embedding to be regenerated.
     *
     * @var list<string>
     */
    protected array $embeddable = ['name', 'notes', 'accords', 'description'];

    /**
     * Handle the Product "saving" event.
     */
    public function saving(Product $product): void
    {
        if ($product->exists && ! $product->isDirty($this->embeddable)) {
            return;
        }

        $product->embedding = Str::of($product->embeddingText())->toEmbeddings(
            provider: 'gemini',
            model: 'gemini-embedding-001',
            dimensions: 768,
        );
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }
}
