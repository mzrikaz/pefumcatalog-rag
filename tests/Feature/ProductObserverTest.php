<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Ai\Embeddings;
use Tests\TestCase;

class ProductObserverTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_a_product_generates_its_embedding()
    {
        Embeddings::fake();

        $product = Product::factory()->create(['name' => 'Midnight Rose']);

        Embeddings::assertGenerated(fn ($prompt) => $prompt->contains('Midnight Rose'));

        $this->assertIsArray($product->embedding);
        $this->assertCount(768, $product->embedding);
    }

    public function test_updating_an_embeddable_field_regenerates_the_embedding()
    {
        Embeddings::fake();

        $product = Product::factory()->create();
        $originalEmbedding = $product->embedding;

        $product->update(['description' => 'A completely different scent story.']);

        $this->assertNotSame($originalEmbedding, $product->fresh()->embedding);
    }

    public function test_updating_an_unrelated_field_does_not_regenerate_the_embedding()
    {
        Embeddings::fake();

        $product = Product::factory()->create();
        $originalEmbedding = $product->embedding;

        $product->update(['price' => 199.99]);

        // pgvector stores single-precision floats, so compare with a tolerance
        // rather than exact equality once the value has round-tripped through the DB.
        $this->assertEqualsWithDelta($originalEmbedding, $product->fresh()->embedding, 0.0001);
    }
}
