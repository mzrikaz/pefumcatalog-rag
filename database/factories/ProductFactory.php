<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * A pool of realistic perfume notes to sample from.
     *
     * @var list<string>
     */
    protected array $notePool = [
        'bergamot', 'lemon', 'grapefruit', 'pink pepper', 'saffron',
        'rose', 'jasmine', 'lily of the valley', 'orange blossom', 'iris',
        'sandalwood', 'cedarwood', 'vetiver', 'patchouli', 'oakmoss',
        'vanilla', 'amber', 'musk', 'tonka bean', 'benzoin',
        'oud', 'incense', 'leather', 'tobacco', 'clove',
        'sea salt', 'green tea', 'fig', 'coconut', 'peach',
    ];

    /**
     * A pool of fragrance accords to sample from.
     *
     * @var list<string>
     */
    protected array $accordPool = [
        'fresh', 'floral', 'woody', 'oriental', 'citrus',
        'aquatic', 'gourmand', 'spicy', 'powdery', 'green',
        'aromatic', 'chypre', 'fruity', 'smoky', 'sweet',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => ucwords(fake()->words(random_int(2, 3), true)),
            'description' => fake()->paragraph(3),
            'notes' => fake()->randomElements($this->notePool, random_int(3, 6)),
            'accords' => fake()->randomElements($this->accordPool, random_int(2, 4)),
            'price' => fake()->randomFloat(2, 35, 250),
            'image_url' => 'https://picsum.photos/seed/'.fake()->unique()->slug(3).'/600/600',
        ];
    }
}
