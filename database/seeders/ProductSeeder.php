<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Seeds a curated catalog of real, well-known perfumes. Embeddings are generated
     * automatically per product by ProductObserver as each row is created.
     */
    public function run(): void
    {
        foreach ($this->perfumes() as $perfume) {
            Product::create([
                ...$perfume,
                'image_url' => 'https://picsum.photos/seed/'.Str::slug($perfume['name']).'/600/600',
            ]);
        }
    }

    /**
     * @return array<int, array{name: string, description: string, notes: array<int, string>, accords: array<int, string>, price: float}>
     */
    protected function perfumes(): array
    {
        return [
            [
                'name' => 'Chanel No. 5',
                'description' => 'The archetypal aldehydic floral, built around a bouquet of rose and jasmine over a soft, powdery base. Timeless, elegant, and unmistakably classic - a signature scent rather than a seasonal one.',
                'notes' => ['aldehydes', 'ylang-ylang', 'rose', 'jasmine', 'sandalwood', 'vanilla', 'vetiver'],
                'accords' => ['floral', 'powdery', 'aldehydic'],
                'price' => 145.00,
            ],
            [
                'name' => 'Chanel Coco Mademoiselle',
                'description' => 'A modern chypre built on orange and bergamot leading into rose and jasmine, finished with warm patchouli and vanilla. Polished and confident - equally at home in the office or at a wedding.',
                'notes' => ['orange', 'bergamot', 'rose', 'jasmine', 'patchouli', 'vanilla', 'vetiver'],
                'accords' => ['floral', 'oriental', 'woody'],
                'price' => 138.00,
            ],
            [
                'name' => 'Chanel Bleu de Chanel',
                'description' => 'A woody aromatic that opens crisp and citrusy before settling into cedar, sandalwood, and a whisper of incense. Clean and versatile, built for daily wear from desk to evening.',
                'notes' => ['grapefruit', 'lemon', 'mint', 'ginger', 'cedar', 'sandalwood', 'incense'],
                'accords' => ['woody', 'aromatic', 'citrus'],
                'price' => 108.00,
            ],
            [
                'name' => 'Dior Sauvage',
                'description' => 'A fresh, peppery opening of Calabrian bergamot over an ambroxan-heavy base gives this scent huge projection and a distinctly modern, clean-masculine character.',
                'notes' => ['bergamot', 'pepper', 'lavender', 'ambroxan', 'cedar'],
                'accords' => ['fresh', 'spicy', 'aromatic'],
                'price' => 98.00,
            ],
            [
                'name' => "Dior J'adore",
                'description' => 'A luminous bouquet of ylang-ylang, damask rose, and jasmine sambac over creamy sandalwood. Bright, feminine, and generous - a go-to for weddings and formal daytime occasions.',
                'notes' => ['ylang-ylang', 'damask rose', 'jasmine sambac', 'sandalwood', 'orchid'],
                'accords' => ['floral', 'fruity', 'sweet'],
                'price' => 132.00,
            ],
            [
                'name' => 'Tom Ford Black Orchid',
                'description' => 'A dark, opulent floral built on black truffle and orchid over a rich chocolate-patchouli base. Sensual and heavy - best suited to evenings and cooler weather.',
                'notes' => ['black truffle', 'orchid', 'ylang-ylang', 'patchouli', 'dark chocolate', 'incense'],
                'accords' => ['oriental', 'woody', 'sweet'],
                'price' => 158.00,
            ],
            [
                'name' => 'Creed Aventus',
                'description' => 'A fruity chypre that opens with blackcurrant and crisp apple, moves through birch and patchouli, and dries down smoky and smooth. Confident, sporty, and widely imitated.',
                'notes' => ['blackcurrant', 'apple', 'bergamot', 'birch', 'patchouli', 'musk', 'oakmoss'],
                'accords' => ['fruity', 'woody', 'smoky'],
                'price' => 375.00,
            ],
            [
                'name' => 'Yves Saint Laurent Black Opium',
                'description' => 'A gourmand oriental built on coffee and black coffee accord layered with white flowers and vanilla. Sweet, addictive, and unmistakably nocturnal.',
                'notes' => ['coffee', 'pear', 'pink pepper', 'jasmine', 'vanilla', 'patchouli', 'cedar'],
                'accords' => ['oriental', 'gourmand', 'sweet'],
                'price' => 128.00,
            ],
            [
                'name' => 'Jo Malone Wood Sage & Sea Salt',
                'description' => 'A minimalist aromatic-aquatic built on ambrette seed and sea salt with a driftwood-like sage heart. Airy and understated, evoking a walk along the coast.',
                'notes' => ['ambrette seed', 'sea salt', 'sage', 'driftwood'],
                'accords' => ['aquatic', 'aromatic', 'fresh'],
                'price' => 78.00,
            ],
            [
                'name' => 'Jo Malone Peony & Blush Suede',
                'description' => 'A soft, romantic floral pairing peony with rhubarb and a suede-like base. Delicate and pretty without being cloying - a favourite for spring weddings and daytime celebrations.',
                'notes' => ['peony', 'rhubarb', 'red apple', 'rose', 'suede', 'musk'],
                'accords' => ['floral', 'fruity', 'powdery'],
                'price' => 78.00,
            ],
            [
                'name' => 'Guerlain Shalimar',
                'description' => 'One of the original oriental perfumes: bergamot and lemon over a smoky vanilla-incense base. Bold, smoky-sweet, and deeply nostalgic.',
                'notes' => ['bergamot', 'lemon', 'iris', 'jasmine', 'vanilla', 'incense', 'opoponax'],
                'accords' => ['oriental', 'sweet', 'smoky'],
                'price' => 118.00,
            ],
            [
                'name' => 'Versace Eros',
                'description' => 'A loud, fresh-oriental fragrance opening with mint and green apple, warming into tonka bean and vanilla. High projection, made for nights out.',
                'notes' => ['mint', 'green apple', 'lemon', 'tonka bean', 'vanilla', 'cedar'],
                'accords' => ['fresh', 'oriental', 'sweet'],
                'price' => 92.00,
            ],
            [
                'name' => 'Paco Rabanne 1 Million',
                'description' => 'A spicy-leather fragrance built on grapefruit and cinnamon over a leathery amber base. Bold, sweet, and unapologetically maximalist.',
                'notes' => ['grapefruit', 'mint', 'cinnamon', 'leather', 'amber', 'patchouli'],
                'accords' => ['spicy', 'leather', 'sweet'],
                'price' => 88.00,
            ],
            [
                'name' => 'Maison Margiela Replica Jazz Club',
                'description' => 'A warm, smoky-spicy scent meant to evoke a late-night jazz bar: rum and tobacco leaf over a base of vanilla and oakmoss. Cosy and intimate.',
                'notes' => ['pink pepper', 'rum', 'tobacco leaf', 'vanilla', 'oakmoss'],
                'accords' => ['spicy', 'smoky', 'sweet'],
                'price' => 132.00,
            ],
            [
                'name' => 'Byredo Gypsy Water',
                'description' => 'A woody-citrus scent blending bergamot and juniper berries with pine needle and a soft vanilla-amber base. Free-spirited and airy, popular in both niche and mainstream circles.',
                'notes' => ['bergamot', 'lemon', 'juniper berries', 'pine needle', 'incense', 'vanilla', 'amber'],
                'accords' => ['woody', 'citrus', 'fresh'],
                'price' => 195.00,
            ],
            [
                'name' => 'Le Labo Santal 33',
                'description' => 'A cult woody-musk fragrance centred on creamy sandalwood, cardamom, and leather. Unisex, smoky-sweet, and instantly recognisable in a crowd.',
                'notes' => ['sandalwood', 'cardamom', 'iris', 'leather', 'violet', 'cedar'],
                'accords' => ['woody', 'musky', 'spicy'],
                'price' => 215.00,
            ],
            [
                'name' => 'Viktor & Rolf Flowerbomb',
                'description' => 'An explosive floral gourmand of jasmine and orchid over a sugary patchouli base. Sweet, feminine, and long-lasting - a bouquet in a bottle.',
                'notes' => ['jasmine', 'orchid', 'freesia', 'patchouli', 'vanilla', 'musk'],
                'accords' => ['floral', 'gourmand', 'sweet'],
                'price' => 128.00,
            ],
            [
                'name' => 'Giorgio Armani Acqua di Gio',
                'description' => 'A benchmark aquatic-fresh scent built on a marine accord with bergamot and jasmine over woody-musky base notes. Clean, breezy, made for summer days.',
                'notes' => ['bergamot', 'jasmine', 'sea notes', 'rosemary', 'cedar', 'musk'],
                'accords' => ['aquatic', 'fresh', 'aromatic'],
                'price' => 88.00,
            ],
            [
                'name' => 'Thierry Mugler Angel',
                'description' => 'The original modern gourmand: a striking contrast of patchouli against cotton candy and chocolate. Sweet, dense, and polarising in the best way.',
                'notes' => ['bergamot', 'red berries', 'honey', 'patchouli', 'chocolate', 'vanilla', 'caramel'],
                'accords' => ['gourmand', 'sweet', 'woody'],
                'price' => 112.00,
            ],
            [
                'name' => 'Chloé Eau de Parfum',
                'description' => 'A soft, powdery floral centred on peony and rose with a musky base. Romantic and understated - a quiet, grown-up femininity.',
                'notes' => ['peony', 'freesia', 'rose', 'lychee', 'musk', 'cedar'],
                'accords' => ['floral', 'powdery', 'musky'],
                'price' => 118.00,
            ],
            [
                'name' => 'Prada Luna Rossa',
                'description' => 'A crisp aromatic-fresh fragrance opening with lavender and spearmint over a woody-ambery base. Sporty and clean, well suited to office wear.',
                'notes' => ['lavender', 'spearmint', 'lemon', 'ambroxan', 'cedar'],
                'accords' => ['aromatic', 'fresh', 'woody'],
                'price' => 95.00,
            ],
            [
                'name' => 'Narciso Rodriguez For Her',
                'description' => 'A soft musky floral built on musk, amber, and a peach-tinted heart. Skin-close and understated, designed to feel like a second skin rather than a statement.',
                'notes' => ['peach', 'musk', 'amber', 'vanilla', 'rose', 'orange blossom'],
                'accords' => ['musky', 'floral', 'sweet'],
                'price' => 105.00,
            ],
            [
                'name' => "Hermès Terre d'Hermès",
                'description' => 'A woody-mineral fragrance built around a grapefruit-and-pepper opening over flint, vetiver, and cedar. Earthy and restrained, evoking soil and stone rather than flowers.',
                'notes' => ['grapefruit', 'pepper', 'flint', 'vetiver', 'cedar', 'benzoin'],
                'accords' => ['woody', 'fresh', 'aromatic'],
                'price' => 112.00,
            ],
            [
                'name' => 'Marc Jacobs Daisy',
                'description' => 'A light, fresh floral built on wild strawberry and violet leaves with a soft jasmine heart. Youthful and casual, best for daytime and warmer weather.',
                'notes' => ['wild strawberry', 'violet leaves', 'jasmine', 'white violet', 'musk'],
                'accords' => ['floral', 'fresh', 'fruity'],
                'price' => 98.00,
            ],
            [
                'name' => 'Dolce & Gabbana Light Blue',
                'description' => 'A bright citrus-fresh scent led by Sicilian lemon and green apple over a cedar-musk base. Simple, sunny, and universally easy to wear.',
                'notes' => ['sicilian lemon', 'green apple', 'bellflower', 'cedar', 'musk', 'amber'],
                'accords' => ['citrus', 'fresh', 'fruity'],
                'price' => 82.00,
            ],
            [
                'name' => 'Xerjoff Naxos',
                'description' => 'A honeyed gourmand-tobacco fragrance blending lavender and bergamot with honey, tobacco leaf, and vanilla. Rich and indulgent, made for cooler evenings.',
                'notes' => ['lavender', 'bergamot', 'honey', 'tobacco leaf', 'vanilla', 'tonka bean'],
                'accords' => ['gourmand', 'spicy', 'sweet'],
                'price' => 285.00,
            ],
            [
                'name' => 'Amouage Interlude Man',
                'description' => 'A dense, smoky-spicy fragrance built on oregano and pepper over frankincense and leather. Intense and unconventional, for cold weather and confident wearers.',
                'notes' => ['oregano', 'pepper', 'frankincense', 'leather', 'amber', 'oud'],
                'accords' => ['smoky', 'spicy', 'woody'],
                'price' => 325.00,
            ],
            [
                'name' => 'Diptyque Philosykos',
                'description' => 'A green fig fragrance capturing the whole tree: coconut-like fig leaf, milky fig flesh, and woody fig branch. Fresh, quiet, and distinctly summery.',
                'notes' => ['fig leaf', 'fig', 'coconut', 'cedar', 'white woods'],
                'accords' => ['green', 'woody', 'fresh'],
                'price' => 145.00,
            ],
        ];
    }
}
