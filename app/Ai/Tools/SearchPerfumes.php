<?php

namespace App\Ai\Tools;

use App\Models\Product;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Str;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class SearchPerfumes implements Tool
{
    /**
     * The number of matching perfumes to return.
     */
    protected int $limit = 5;

    /**
     * The minimum cosine similarity a perfume must have to be considered a match.
     */
    protected float $minSimilarity = 0.5;

    /**
     * Get the tool's name, as seen by the LLM and the frontend's tool-part rendering.
     */
    public function name(): string
    {
        return 'search_perfumes';
    }

    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Search the perfume catalog for perfumes matching a natural-language description of what '
            .'the customer wants (occasion, mood, scent notes, accords, budget, etc). Only recommend '
            .'perfumes returned by this tool - never invent products that are not in the results.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $vector = Str::of($request->string('query'))->toEmbeddings(
            provider: 'gemini',
            model: 'gemini-embedding-001',
            dimensions: 768,
        );

        $maxPrice = $request->filled('max_price') ? $request->float('max_price') : null;

        $matches = Product::query()
            ->whereVectorSimilarTo('embedding', $vector, minSimilarity: $this->minSimilarity)
            ->orderByVectorDistance('embedding', $vector)
            ->when($maxPrice, fn ($query) => $query->where('price', '<=', $maxPrice))
            ->limit($this->limit)
            ->get(['id', 'name', 'description', 'notes', 'accords', 'price', 'image_url']);

        if ($matches->isEmpty()) {
            return 'No perfumes in the catalog matched that description.';
        }

        return $matches->toJson();
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'query' => $schema->string()
                ->description('A natural-language description of what the customer is looking for, e.g. "a fresh floral perfume for a summer wedding".')
                ->required(),
            'max_price' => $schema->number()
                ->description('Optional maximum price the customer is willing to pay.'),
        ];
    }
}
