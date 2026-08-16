<?php

namespace Tests\Feature;

use App\Ai\Tools\SearchPerfumes;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Ai\Embeddings;
use Laravel\Ai\Prompts\EmbeddingsPrompt;
use Laravel\Ai\Tools\Request;
use Tests\TestCase;

class SearchPerfumesToolTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Fake embeddings deterministically: identical text always produces the
        // identical vector, so we can control which product is the closest match.
        Embeddings::fake(
            fn (EmbeddingsPrompt $prompt) => [$this->deterministicVector($prompt->inputs[0], $prompt->dimensions)]
        );
    }

    public function test_it_returns_the_closest_matching_perfume_first()
    {
        $target = Product::factory()->create();
        Product::factory()->count(3)->create();

        $result = (new SearchPerfumes)->handle(new Request(['query' => $target->embeddingText()]));

        $matches = json_decode((string) $result, true);

        $this->assertSame($target->id, $matches[0]['id']);
    }

    public function test_it_filters_by_max_price()
    {
        $affordable = Product::factory()->create(['price' => 40]);
        $expensive = Product::factory()->create(['price' => 300]);

        $result = (new SearchPerfumes)->handle(new Request([
            'query' => $affordable->embeddingText(),
            'max_price' => 100,
        ]));

        $matches = json_decode((string) $result, true);
        $ids = array_column($matches, 'id');

        $this->assertContains($affordable->id, $ids);
        $this->assertNotContains($expensive->id, $ids);
    }

    public function test_it_reports_when_nothing_matches()
    {
        $result = (new SearchPerfumes)->handle(new Request(['query' => 'anything']));

        $this->assertSame('No perfumes in the catalog matched that description.', (string) $result);
    }

    /**
     * Deterministically derive a fake embedding vector from the given text, so the
     * same text always produces the same vector (and different text a different one).
     *
     * @return array<int, float>
     */
    protected function deterministicVector(string $text, int $dimensions): array
    {
        mt_srand(crc32($text));

        $vector = array_map(fn () => mt_rand() / mt_getrandmax(), range(1, $dimensions));

        mt_srand();

        return $vector;
    }
}
