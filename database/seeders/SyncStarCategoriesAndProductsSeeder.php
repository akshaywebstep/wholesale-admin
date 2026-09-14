<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\ProductPriceTier;
use App\Models\Stock;
use App\Models\Unit;
use App\Models\Warehouse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SyncStarCategoriesAndProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info("=== Starting Categories & Products Sync (Star Importers Target) ===");

        // 1. Setup storage directories
        $storageDir1 = storage_path('app/public/products');
        $storageDir2 = public_path('storage/products');
        $catDir1 = storage_path('app/public/categories');
        $catDir2 = public_path('storage/categories');

        File::ensureDirectoryExists($storageDir1);
        File::ensureDirectoryExists($storageDir2);
        File::ensureDirectoryExists($catDir1);
        File::ensureDirectoryExists($catDir2);

        // 2. Warehouses & Units lookup
        $warehouse = Warehouse::where('status', 'ACTIVE')->first() ?? Warehouse::first();
        if (!$warehouse) {
            $warehouse = Warehouse::create([
                'name'     => 'Central Distribution Hub',
                'code'     => 'MAIN',
                'location' => 'Primary Distribution Facility',
                'status'   => 'ACTIVE',
            ]);
        }
        $warehouseId = $warehouse->id;

        $boxUnit = Unit::where('name', 'Box')->orWhere('short_code', 'box')->first() ?? Unit::first();
        $packUnit = Unit::where('name', 'Pack')->orWhere('short_code', 'pack')->first() ?? Unit::first();
        $cartonUnit = Unit::where('name', 'Carton')->orWhere('short_code', 'ctn')->first() ?? Unit::first();
        $bottleUnit = Unit::where('name', 'Bottle')->orWhere('short_code', 'btl')->first() ?? Unit::first();
        $pieceUnit = Unit::where('name', 'Piece')->orWhere('short_code', 'pc')->first() ?? Unit::first();

        // 3. DELETE UNWANTED CATEGORIES & PRODUCTS (Food, Groceries, Beverages, OPMS, Smoke Shop)
        $this->command->info("Step 1: Removing excluded categories, products and orphan images...");

        $excludedPatterns = [
            '%food%',
            '%grocer%',
            '%beverage%',
            '%opms%',
            '%smoke shop%',
            '%energy drink%',
            '%snack%',
            '%candy%',
            '%chocolate%',
            '%noodle%',
            '%frozen%',
            '%canned%',
        ];

        // Find unwanted categories
        $unwantedCategoryIds = Category::where(function ($query) use ($excludedPatterns) {
            foreach ($excludedPatterns as $pat) {
                $query->orWhere('name', 'like', $pat);
            }
        })->pluck('id')->toArray();

        // Also find all subcategories of unwanted categories
        $allUnwantedCategoryIds = $unwantedCategoryIds;
        if (!empty($unwantedCategoryIds)) {
            $childIds = Category::whereIn('parent_id', $unwantedCategoryIds)->pluck('id')->toArray();
            $allUnwantedCategoryIds = array_unique(array_merge($allUnwantedCategoryIds, $childIds));
        }

        $this->command->info("Found " . count($allUnwantedCategoryIds) . " unwanted category IDs to delete.");

        // Find products under unwanted categories
        $unwantedProducts = Product::whereIn('category_id', $allUnwantedCategoryIds)->get();
        foreach ($unwantedProducts as $prod) {
            $this->command->line("Deleting product: {$prod->name} (ID: {$prod->id})");

            // Delete images from disk
            foreach ($prod->images as $img) {
                $p1 = storage_path('app/public/' . $img->image_path);
                $p2 = public_path('storage/' . $img->image_path);
                if (File::exists($p1)) File::delete($p1);
                if (File::exists($p2)) File::delete($p2);
                $img->delete();
            }

            // Remove foreign relations
            $variantIds = $prod->variants()->pluck('id')->toArray();
            if (!empty($variantIds)) {
                // Delete order items if referencing
                DB::table('order_items')->whereIn('product_variant_id', $variantIds)->delete();
                DB::table('stock')->whereIn('product_variant_id', $variantIds)->delete();
                $prod->variants()->delete();
            }

            $prod->priceTiers()->delete();
            $prod->delete();
        }

        // Delete unwanted categories
        foreach ($allUnwantedCategoryIds as $catId) {
            $cat = Category::find($catId);
            if ($cat) {
                if ($cat->image) {
                    $c1 = storage_path('app/public/' . $cat->image);
                    $c2 = public_path('storage/' . $cat->image);
                    if (File::exists($c1)) File::delete($c1);
                    if (File::exists($c2)) File::delete($c2);
                }
                $cat->delete();
            }
        }

        // 4. SYNC TARGET CATEGORIES (Matching Star Importers, clean structure)
        $this->command->info("Step 2: Syncing target Star Importers categories...");

        $categoriesHierarchy = [
            [
                'name' => 'Medicine',
                'slug' => 'medicine',
                'subs' => [
                    ['name' => 'Pain & Fever Relief', 'slug' => 'pain-fever-relief'],
                    ['name' => 'Cold, Cough & Flu', 'slug' => 'cold-cough-flu'],
                    ['name' => 'Stomach & Digestion', 'slug' => 'stomach-digestion'],
                    ['name' => 'Allergy & Sinus', 'slug' => 'allergy-sinus'],
                    ['name' => 'Eye & Ear Care', 'slug' => 'eye-ear-care'],
                    ['name' => 'First Aid & Antiseptics', 'slug' => 'first-aid-antiseptics'],
                ]
            ],
            [
                'name' => 'Health & Beauty',
                'slug' => 'health-beauty',
                'subs' => [
                    ['name' => 'Body & Skin Care', 'slug' => 'body-skin-care'],
                    ['name' => 'Hair Care', 'slug' => 'hair-care'],
                    ['name' => 'Shaving', 'slug' => 'shaving'],
                    ['name' => 'Body Wash & Soap', 'slug' => 'body-wash-soap'],
                    ['name' => 'Lip Care', 'slug' => 'lip-care'],
                    ['name' => 'Oral Care', 'slug' => 'oral-care'],
                    ['name' => 'Deodorant', 'slug' => 'deodorant'],
                    ['name' => 'Baby Care', 'slug' => 'baby-care'],
                    ['name' => 'Feminine Care', 'slug' => 'feminine-care'],
                ]
            ],
            [
                'name' => 'Sexual Wellness',
                'slug' => 'sexual-wellness',
                'subs' => [
                    ['name' => 'Condom', 'slug' => 'condom'],
                    ['name' => 'Lube', 'slug' => 'lube'],
                    ['name' => 'Pleasure Kit', 'slug' => 'pleasure-kit'],
                    ['name' => 'Adult Toys', 'slug' => 'adult-toys'],
                ]
            ],
            [
                'name' => 'Automotive & Accessories',
                'slug' => 'automotive-accessories',
                'subs' => [
                    ['name' => 'Auto Care', 'slug' => 'auto-care'],
                    ['name' => 'Gas Cans', 'slug' => 'gas-cans'],
                    ['name' => 'Auto Accessories', 'slug' => 'auto-accessories'],
                    ['name' => 'Anti-Freeze', 'slug' => 'anti-freeze'],
                    ['name' => 'Motor Oil', 'slug' => 'motor-oil'],
                ]
            ],
            [
                'name' => 'General Store',
                'slug' => 'general-store',
                'subs' => [
                    ['name' => 'Household Items', 'slug' => 'household-items'],
                    ['name' => 'Battery', 'slug' => 'battery'],
                ]
            ],
            [
                'name' => 'Herbal Supplements',
                'slug' => 'herbal-supplements',
                'subs' => [
                    ['name' => 'Stacker-2', 'slug' => 'stacker-2'],
                    ['name' => 'Tweaker', 'slug' => 'tweaker'],
                    ['name' => 'K-Chill', 'slug' => 'k-chill'],
                    ['name' => 'Vivazen', 'slug' => 'vivazen'],
                    ['name' => 'Oral Strips', 'slug' => 'oral-strips'],
                    ['name' => 'Wellness Gummies', 'slug' => 'wellness-gummies'],
                    ['name' => 'Shots', 'slug' => 'shots'],
                    ['name' => 'Pills', 'slug' => 'pills'],
                    ['name' => 'Honey', 'slug' => 'honey'],
                    ['name' => 'Detox', 'slug' => 'detox'],
                ]
            ],
            [
                'name' => 'Lighter & Butanes',
                'slug' => 'lighter-butanes',
                'subs' => [
                    ['name' => 'Lighters', 'slug' => 'lighters'],
                    ['name' => 'Butane', 'slug' => 'butane'],
                ]
            ],
            [
                'name' => 'Airfreshners & Incense',
                'slug' => 'airfreshners-incense',
                'subs' => [
                    ['name' => 'Incense', 'slug' => 'incense'],
                    ['name' => 'Airfreshener', 'slug' => 'airfreshener'],
                ]
            ],
            [
                'name' => 'Novelty',
                'slug' => 'novelty',
                'subs' => [
                    ['name' => 'Sunglasses', 'slug' => 'sunglasses'],
                    ['name' => 'Playing Card', 'slug' => 'playing-card'],
                    ['name' => 'Knives', 'slug' => 'knives'],
                    ['name' => 'Empty Displays', 'slug' => 'empty-displays'],
                    ['name' => 'Phone Accessories', 'slug' => 'phone-accessories'],
                ]
            ],
            [
                'name' => 'Cigars, Cigarillos & Wraps',
                'slug' => 'cigars-cigarillos-wraps',
                'subs' => [
                    ['name' => 'Cigarillos & Foil Pouches', 'slug' => 'cigarillos-foil-pouches'],
                    ['name' => 'Natural Leaf Cigars', 'slug' => 'natural-leaf-cigars'],
                    ['name' => 'Whole Leaf & Blunt Wraps', 'slug' => 'whole-leaf-blunt-wraps'],
                    ['name' => 'Pipe & Shag Tobacco', 'slug' => 'pipe-shag-tobacco'],
                    ['name' => 'Little Cigars & Cigarettes', 'slug' => 'little-cigars-cigarettes'],
                ]
            ],
            [
                'name' => 'Nicotine Pouches',
                'slug' => 'nicotine-pouches',
                'subs' => []
            ],
            [
                'name' => 'Wraps N Rolling Papers',
                'slug' => 'wraps-n-rolling-papers',
                'subs' => [
                    ['name' => 'Hemp Rolling Papers', 'slug' => 'hemp-rolling-papers'],
                    ['name' => 'Hemp Wraps', 'slug' => 'hemp-wraps'],
                    ['name' => 'Filtered Tips/Tubes', 'slug' => 'filtered-tips-tubes'],
                    ['name' => 'Cones', 'slug' => 'cones'],
                ]
            ],
            [
                'name' => 'Stationery',
                'slug' => 'stationery',
                'subs' => [
                    ['name' => 'Register Rolls', 'slug' => 'register-rolls'],
                    ['name' => 'Gloves', 'slug' => 'gloves'],
                ]
            ],
        ];

        $categoryMap = []; // slug => model

        foreach ($categoriesHierarchy as $parentDef) {
            $parent = Category::where('slug', $parentDef['slug'])
                ->orWhere('name', $parentDef['name'])
                ->first();

            if (!$parent) {
                $parent = Category::create([
                    'name'      => $parentDef['name'],
                    'slug'      => $parentDef['slug'],
                    'parent_id' => null,
                    'status'    => 'ACTIVE',
                ]);
            } else {
                $parent->update([
                    'name'      => $parentDef['name'],
                    'slug'      => $parentDef['slug'],
                    'parent_id' => null,
                    'status'    => 'ACTIVE',
                ]);
            }

            $categoryMap[$parentDef['slug']] = $parent;
            $categoryMap[$parentDef['name']] = $parent;

            foreach ($parentDef['subs'] as $subDef) {
                $sub = Category::where('slug', $subDef['slug'])
                    ->orWhere('name', $subDef['name'])
                    ->first();

                if (!$sub) {
                    $sub = Category::create([
                        'name'      => $subDef['name'],
                        'slug'      => $subDef['slug'],
                        'parent_id' => $parent->id,
                        'status'    => 'ACTIVE',
                    ]);
                } else {
                    $sub->update([
                        'name'      => $subDef['name'],
                        'slug'      => $subDef['slug'],
                        'parent_id' => $parent->id,
                        'status'    => 'ACTIVE',
                    ]);
                }

                $categoryMap[$subDef['slug']] = $sub;
                $categoryMap[$subDef['name']] = $sub;
            }
        }

        // 5. PRODUCTS SPECIFICATION (Focus on Medicine & Core C-Store Wholesale items)
        $this->command->info("Step 3: Creating wholesale products with realistic images & inventory...");

        $productsData = [
            // ====== MEDICINE CATEGORY ======
            [
                'name'         => 'Tylenol Extra Strength 500mg Caplets (50 Pouches of 2 Display Box)',
                'sku'          => 'MED-TYL-500',
                'category_slug'=> 'pain-fever-relief',
                'parent_slug'  => 'medicine',
                'base_price'   => 34.50,
                'unit_id'      => $boxUnit->id,
                'weight'       => 0.45,
                'description'  => 'Official C-store countertop dispenser box containing 50 individual tamper-evident packets of 2 Tylenol Extra Strength 500mg acetaminophen caplets. Fast relief for headaches, backaches, and fever.',
                'image_slug'   => 'tylenol-extra-strength-500mg-caplets-50ct',
                'brand'        => 'TYLENOL',
                'badge'        => '50 POUCHES (2 CAPLETS EA)',
                'sub'          => 'Acetaminophen 500mg | Pain & Fever Relief',
                'bg1'          => [245, 247, 250],
                'bg2'          => [220, 230, 242],
                'accent'       => [220, 38, 38], // Red
                'tiers'        => [
                    ['min_qty' => 1, 'max_qty' => 4, 'price' => 34.50],
                    ['min_qty' => 5, 'max_qty' => 19, 'price' => 31.99],
                    ['min_qty' => 20, 'max_qty' => null, 'price' => 29.50],
                ],
                'stock'        => 150,
            ],
            [
                'name'         => 'Advil Pain Reliever & Fever Reducer 200mg (50 Packets of 2 Display Box)',
                'sku'          => 'MED-ADV-200',
                'category_slug'=> 'pain-fever-relief',
                'parent_slug'  => 'medicine',
                'base_price'   => 33.75,
                'unit_id'      => $boxUnit->id,
                'weight'       => 0.40,
                'description'  => 'Convenient counter dispenser box with 50 individual 2-tablet packets of Advil Ibuprofen 200mg. The #1 doctor-recommended pain reliever for joint pain, headache, and muscle aches.',
                'image_slug'   => 'advil-ibuprofen-200mg-display-box-50ct',
                'brand'        => 'ADVIL',
                'badge'        => '50 PACKETS (2 TABLETS EA)',
                'sub'          => 'Ibuprofen 200mg NSAID | Fast Pain Relief',
                'bg1'          => [254, 252, 232],
                'bg2'          => [254, 240, 138],
                'accent'       => [202, 138, 4], // Amber/Gold
                'tiers'        => [
                    ['min_qty' => 1, 'max_qty' => 4, 'price' => 33.75],
                    ['min_qty' => 5, 'max_qty' => 19, 'price' => 30.99],
                    ['min_qty' => 20, 'max_qty' => null, 'price' => 28.50],
                ],
                'stock'        => 180,
            ],
            [
                'name'         => 'Vicks DayQuil & NyQuil Severe Cold & Flu LiquidCaps (24-Count Display)',
                'sku'          => 'MED-VIC-SEV',
                'category_slug'=> 'cold-cough-flu',
                'parent_slug'  => 'medicine',
                'base_price'   => 48.90,
                'unit_id'      => $boxUnit->id,
                'weight'       => 0.60,
                'description'  => 'Wholesale combo counter pack featuring 12 DayQuil Severe and 12 NyQuil Severe co-pack units. Maximum strength relief for coughing, congestion, sore throat, and sinus pressure.',
                'image_slug'   => 'vicks-dayquil-nyquil-severe-liquidcaps-24ct',
                'brand'        => 'VICKS',
                'badge'        => '24 COMBO LIQUIDCAP PACKS',
                'sub'          => 'Maximum Strength Cold & Flu Dual Relief',
                'bg1'          => [236, 253, 245],
                'bg2'          => [167, 243, 208],
                'accent'       => [5, 150, 105], // Green
                'tiers'        => [
                    ['min_qty' => 1, 'max_qty' => 4, 'price' => 48.90],
                    ['min_qty' => 5, 'max_qty' => 19, 'price' => 45.00],
                    ['min_qty' => 20, 'max_qty' => null, 'price' => 41.50],
                ],
                'stock'        => 120,
            ],
            [
                'name'         => 'Pepto Bismol Chewable Tablets 5-Symptom Relief (30 Packets Box)',
                'sku'          => 'MED-PEP-CHEW',
                'category_slug'=> 'stomach-digestion',
                'parent_slug'  => 'medicine',
                'base_price'   => 26.80,
                'unit_id'      => $boxUnit->id,
                'weight'       => 0.35,
                'description'  => '30 pocket packets each with 2 chewable bismuth subsalicylate tablets. Fast soothing relief for nausea, heartburn, indigestion, upset stomach, and diarrhea.',
                'image_slug'   => 'pepto-bismol-chewable-tablets-30ct-box',
                'brand'        => 'PEPTO BISMOL',
                'badge'        => '30 POCKET PACKETS (2 TABS)',
                'sub'          => '5-Symptom Stomach & Digestion Relief',
                'bg1'          => [253, 242, 248],
                'bg2'          => [251, 207, 232],
                'accent'       => [219, 39, 119], // Pink
                'tiers'        => [
                    ['min_qty' => 1, 'max_qty' => 4, 'price' => 26.80],
                    ['min_qty' => 5, 'max_qty' => 19, 'price' => 24.50],
                    ['min_qty' => 20, 'max_qty' => null, 'price' => 22.00],
                ],
                'stock'        => 140,
            ],
            [
                'name'         => 'Bayer Genuine Aspirin 325mg Pain Reliever (50 Pouches of 2 Box)',
                'sku'          => 'MED-BAY-325',
                'category_slug'=> 'pain-fever-relief',
                'parent_slug'  => 'medicine',
                'base_price'   => 24.50,
                'unit_id'      => $boxUnit->id,
                'weight'       => 0.38,
                'description'  => '50 pouches of 2 genuine Bayer 325mg coated aspirin tablets. Effective temporary pain relief and heart-health support for gas station and C-store customers.',
                'image_slug'   => 'bayer-aspirin-325mg-pain-reliever-50ct',
                'brand'        => 'BAYER',
                'badge'        => '50 POUCHES (2 TABLETS EA)',
                'sub'          => 'Genuine Aspirin 325mg | Proven Pain Relief',
                'bg1'          => [240, 249, 255],
                'bg2'          => [224, 242, 254],
                'accent'       => [2, 132, 199], // Light Blue
                'tiers'        => [
                    ['min_qty' => 1, 'max_qty' => 4, 'price' => 24.50],
                    ['min_qty' => 5, 'max_qty' => 19, 'price' => 22.25],
                    ['min_qty' => 20, 'max_qty' => null, 'price' => 19.90],
                ],
                'stock'        => 100,
            ],
            [
                'name'         => 'Alka-Seltzer Original Fast Relief Effervescent (36 Foil Packs of 2)',
                'sku'          => 'MED-ALK-ORIG',
                'category_slug'=> 'stomach-digestion',
                'parent_slug'  => 'medicine',
                'base_price'   => 22.95,
                'unit_id'      => $boxUnit->id,
                'weight'       => 0.42,
                'description'  => 'Original bubbly effervescent antacid and pain relief tablets in foil pouches. Speedy relief of acid indigestion, sour stomach, heartburn, and headache.',
                'image_slug'   => 'alka-seltzer-original-effervescent-36ct',
                'brand'        => 'ALKA-SELTZER',
                'badge'        => '36 FOIL PACKS (2 TABS EA)',
                'sub'          => 'Effervescent Antacid & Pain Reliever',
                'bg1'          => [248, 250, 252],
                'bg2'          => [226, 232, 240],
                'accent'       => [15, 23, 42], // Deep Navy
                'tiers'        => [
                    ['min_qty' => 1, 'max_qty' => 4, 'price' => 22.95],
                    ['min_qty' => 5, 'max_qty' => 19, 'price' => 20.50],
                    ['min_qty' => 20, 'max_qty' => null, 'price' => 18.75],
                ],
                'stock'        => 110,
            ],
            [
                'name'         => 'Benadryl Allergy Ultratabs Antihistamine 25mg (24 Pouches of 2 Display)',
                'sku'          => 'MED-BEN-25',
                'category_slug'=> 'allergy-sinus',
                'parent_slug'  => 'medicine',
                'base_price'   => 28.50,
                'unit_id'      => $boxUnit->id,
                'weight'       => 0.30,
                'description'  => '24 two-tablet pouches of Benadryl 25mg diphenhydramine HCl allergy tablets. Effective relief from hay fever, allergy symptoms, itchy watery eyes, and runny nose.',
                'image_slug'   => 'benadryl-allergy-ultratabs-25mg-24ct',
                'brand'        => 'BENADRYL',
                'badge'        => '24 POUCHES (2 TABLETS EA)',
                'sub'          => 'Diphenhydramine HCl 25mg | Allergy Relief',
                'bg1'          => [254, 242, 242],
                'bg2'          => [254, 205, 211],
                'accent'       => [225, 29, 72], // Rose
                'tiers'        => [
                    ['min_qty' => 1, 'max_qty' => 4, 'price' => 28.50],
                    ['min_qty' => 5, 'max_qty' => 19, 'price' => 25.90],
                    ['min_qty' => 20, 'max_qty' => null, 'price' => 23.50],
                ],
                'stock'        => 95,
            ],
            [
                'name'         => 'Visine Red Eye Comfort Lubricant Eye Drops 0.5oz (12 Bottles Display)',
                'sku'          => 'MED-VIS-RED',
                'category_slug'=> 'eye-ear-care',
                'parent_slug'  => 'medicine',
                'base_price'   => 39.99,
                'unit_id'      => $boxUnit->id,
                'weight'       => 0.55,
                'description'  => '12 individual 0.5 fl oz sterile squeeze bottles with red eye comfort formula. Gets the red out fast and relieves dryness from smoke and lack of sleep.',
                'image_slug'   => 'visine-red-eye-comfort-eye-drops-12ct',
                'brand'        => 'VISINE',
                'badge'        => '12 STERILE BOTTLES (0.5 FL OZ)',
                'sub'          => 'Redness Reliever & Lubricant Eye Drops',
                'bg1'          => [240, 253, 250],
                'bg2'          => [204, 251, 241],
                'accent'       => [13, 148, 136], // Teal
                'tiers'        => [
                    ['min_qty' => 1, 'max_qty' => 4, 'price' => 39.99],
                    ['min_qty' => 5, 'max_qty' => 19, 'price' => 36.50],
                    ['min_qty' => 20, 'max_qty' => null, 'price' => 33.00],
                ],
                'stock'        => 85,
            ],
            [
                'name'         => 'Excedrin Extra Strength Caplets (50 Packets of 2 Display Box)',
                'sku'          => 'MED-EXC-STR',
                'category_slug'=> 'pain-fever-relief',
                'parent_slug'  => 'medicine',
                'base_price'   => 32.90,
                'unit_id'      => $boxUnit->id,
                'weight'       => 0.40,
                'description'  => 'Fast acting headache and migraine formula with acetaminophen, aspirin, and caffeine. Countertop gravity feed dispenser box of 50 packets.',
                'image_slug'   => 'excedrin-extra-strength-caplets-50ct',
                'brand'        => 'EXCEDRIN',
                'badge'        => '50 PACKETS (2 CAPLETS EA)',
                'sub'          => 'Triple Action Pain & Headache Formula',
                'bg1'          => [254, 243, 199],
                'bg2'          => [253, 230, 138],
                'accent'       => [180, 83, 9], // Brown Amber
                'tiers'        => [
                    ['min_qty' => 1, 'max_qty' => 4, 'price' => 32.90],
                    ['min_qty' => 5, 'max_qty' => 19, 'price' => 29.90],
                    ['min_qty' => 20, 'max_qty' => null, 'price' => 27.00],
                ],
                'stock'        => 130,
            ],
            [
                'name'         => 'Dramamine Original Motion Sickness Relief (24 Travel Packs Box)',
                'sku'          => 'MED-DRA-MOT',
                'category_slug'=> 'stomach-digestion',
                'parent_slug'  => 'medicine',
                'base_price'   => 29.50,
                'unit_id'      => $boxUnit->id,
                'weight'       => 0.32,
                'description'  => '24 travel pouches containing 2 dimenhydrinate 50mg tablets each. Prevents and relieves motion sickness, nausea, and dizziness for road trips and commuting.',
                'image_slug'   => 'dramamine-motion-sickness-relief-24ct',
                'brand'        => 'DRAMAMINE',
                'badge'        => '24 TRAVEL PACKS (2 TABS EA)',
                'sub'          => 'Motion Sickness & Nausea Prevention',
                'bg1'          => [255, 237, 213],
                'bg2'          => [254, 215, 170],
                'accent'       => [194, 65, 12], // Orange
                'tiers'        => [
                    ['min_qty' => 1, 'max_qty' => 4, 'price' => 29.50],
                    ['min_qty' => 5, 'max_qty' => 19, 'price' => 26.80],
                    ['min_qty' => 20, 'max_qty' => null, 'price' => 24.00],
                ],
                'stock'        => 75,
            ],

            // ====== HEALTH & BEAUTY CATEGORY ======
            [
                'name'         => 'ChapStick Classic Original Lip Balm (Countertop Gravity Display 48 Tubes)',
                'sku'          => 'HEA-CHA-LIP',
                'category_slug'=> 'lip-care',
                'parent_slug'  => 'health-beauty',
                'base_price'   => 44.90,
                'unit_id'      => $boxUnit->id,
                'weight'       => 0.65,
                'description'  => 'Display cylinder with 48 tubes of ChapStick Classic Original 0.15oz lip balm. High rotation impulse item for checkout counters and register lanes.',
                'image_slug'   => 'chapstick-classic-original-lip-balm-48ct',
                'brand'        => 'CHAPSTICK',
                'badge'        => '48 TUBES COUNTERTOP DISPLAY',
                'sub'          => 'Classic Original Skin Protectant Lip Balm',
                'bg1'          => [241, 245, 249],
                'bg2'          => [203, 213, 225],
                'accent'       => [30, 41, 59], // Slate
                'tiers'        => [
                    ['min_qty' => 1, 'max_qty' => 4, 'price' => 44.90],
                    ['min_qty' => 5, 'max_qty' => 19, 'price' => 41.50],
                    ['min_qty' => 20, 'max_qty' => null, 'price' => 38.00],
                ],
                'stock'        => 160,
            ],
            [
                'name'         => 'Colgate Total Whitening Toothpaste Travel Size 0.85oz (24 Pack Display)',
                'sku'          => 'HEA-COL-TP',
                'category_slug'=> 'oral-care',
                'parent_slug'  => 'health-beauty',
                'base_price'   => 21.50,
                'unit_id'      => $boxUnit->id,
                'weight'       => 0.70,
                'description'  => '24 pack travel size 0.85oz tubes of Colgate Total Whitening fluoride toothpaste. Perfect for convenience stores, hotels, and travel centers.',
                'image_slug'   => 'colgate-total-whitening-travel-24ct',
                'brand'        => 'COLGATE',
                'badge'        => '24 TUBES (0.85 OZ TRAVEL SIZE)',
                'sub'          => 'Whole Mouth Health Fluoride Toothpaste',
                'bg1'          => [254, 242, 242],
                'bg2'          => [254, 202, 202],
                'accent'       => [239, 68, 68], // Red
                'tiers'        => [
                    ['min_qty' => 1, 'max_qty' => 4, 'price' => 21.50],
                    ['min_qty' => 5, 'max_qty' => 19, 'price' => 19.50],
                    ['min_qty' => 20, 'max_qty' => null, 'price' => 17.80],
                ],
                'stock'        => 140,
            ],
            [
                'name'         => 'Old Spice High Endurance Pure Sport Deodorant 2.25oz (12 Sticks Pack)',
                'sku'          => 'HEA-OLD-DEO',
                'category_slug'=> 'deodorant',
                'parent_slug'  => 'health-beauty',
                'base_price'   => 34.80,
                'unit_id'      => $packUnit->id,
                'weight'       => 0.95,
                'description'  => 'Wholesale shrink-wrapped 12-pack of Old Spice Pure Sport High Endurance aluminum-free deodorant sticks. Long lasting 48-hour odor protection.',
                'image_slug'   => 'old-spice-pure-sport-deodorant-12pk',
                'brand'        => 'OLD SPICE',
                'badge'        => '12 PACK (2.25 OZ STICKS)',
                'sub'          => 'High Endurance Pure Sport 48Hr Odor Defense',
                'bg1'          => [255, 241, 242],
                'bg2'          => [254, 205, 211],
                'accent'       => [190, 18, 60], // Dark Red
                'tiers'        => [
                    ['min_qty' => 1, 'max_qty' => 4, 'price' => 34.80],
                    ['min_qty' => 5, 'max_qty' => 19, 'price' => 31.50],
                    ['min_qty' => 20, 'max_qty' => null, 'price' => 28.90],
                ],
                'stock'        => 115,
            ],
            [
                'name'         => 'Gillette Foamy Regular Shaving Cream 11oz Can (Case of 12 Cans)',
                'sku'          => 'HEA-GIL-SHV',
                'category_slug'=> 'shaving',
                'parent_slug'  => 'health-beauty',
                'base_price'   => 28.50,
                'unit_id'      => $cartonUnit->id,
                'weight'       => 4.20,
                'description'  => 'Full master case of 12 cans of Gillette Foamy Regular shaving cream with Comfort Glide formula. Rich, thick lather for an effortless close shave.',
                'image_slug'   => 'gillette-foamy-regular-shave-cream-12ct',
                'brand'        => 'GILLETTE',
                'badge'        => 'CASE OF 12 CANS (11 OZ EA)',
                'sub'          => 'Foamy Comfort Glide Rich Shaving Foam',
                'bg1'          => [239, 246, 255],
                'bg2'          => [191, 219, 254],
                'accent'       => [29, 78, 216], // Blue
                'tiers'        => [
                    ['min_qty' => 1, 'max_qty' => 4, 'price' => 28.50],
                    ['min_qty' => 5, 'max_qty' => 19, 'price' => 26.00],
                    ['min_qty' => 20, 'max_qty' => null, 'price' => 23.50],
                ],
                'stock'        => 90,
            ],

            // ====== SEXUAL WELLNESS CATEGORY ======
            [
                'name'         => 'Trojan Magnum Lubricated Large Condoms (24 Packs of 3 Display Box)',
                'sku'          => 'SEX-TRO-MAG',
                'category_slug'=> 'condom',
                'parent_slug'  => 'sexual-wellness',
                'base_price'   => 54.00,
                'unit_id'      => $boxUnit->id,
                'weight'       => 0.50,
                'description'  => '24 retail boxes with 3 premium Trojan Magnum lubricated latex condoms each. Larger than standard condoms for extra comfort with silky smooth lubricant.',
                'image_slug'   => 'trojan-magnum-lubricated-condoms-24ct',
                'brand'        => 'TROJAN',
                'badge'        => '24 BOXES (3 CONDOMS EA)',
                'sub'          => 'Magnum Gold Standard Large Size Latex',
                'bg1'          => [254, 243, 199],
                'bg2'          => [253, 224, 71],
                'accent'       => [161, 98, 7], // Gold
                'tiers'        => [
                    ['min_qty' => 1, 'max_qty' => 4, 'price' => 54.00],
                    ['min_qty' => 5, 'max_qty' => 19, 'price' => 49.50],
                    ['min_qty' => 20, 'max_qty' => null, 'price' => 45.00],
                ],
                'stock'        => 130,
            ],
            [
                'name'         => 'Durex Extra Sensitive Ultra Thin Lubricated Condoms (18 Packs of 3 Box)',
                'sku'          => 'SEX-DUR-EXT',
                'category_slug'=> 'condom',
                'parent_slug'  => 'sexual-wellness',
                'base_price'   => 42.50,
                'unit_id'      => $boxUnit->id,
                'weight'       => 0.45,
                'description'  => '18 retail 3-packs of Durex Extra Sensitive ultra thin lubricated condoms. Designed to enhance sensitivity and connection with trusted strength.',
                'image_slug'   => 'durex-extra-sensitive-condoms-18ct',
                'brand'        => 'DUREX',
                'badge'        => '18 BOXES (3 CONDOMS EA)',
                'sub'          => 'Extra Sensitive Ultra Thin Feel',
                'bg1'          => [245, 243, 255],
                'bg2'          => [221, 214, 254],
                'accent'       => [109, 40, 217], // Purple
                'tiers'        => [
                    ['min_qty' => 1, 'max_qty' => 4, 'price' => 42.50],
                    ['min_qty' => 5, 'max_qty' => 19, 'price' => 38.90],
                    ['min_qty' => 20, 'max_qty' => null, 'price' => 35.50],
                ],
                'stock'        => 110,
            ],
            [
                'name'         => 'K-Y Jelly Water Based Personal Lubricant 4oz Tube (Pack of 12)',
                'sku'          => 'SEX-KY-LUBE',
                'category_slug'=> 'lube',
                'parent_slug'  => 'sexual-wellness',
                'base_price'   => 46.80,
                'unit_id'      => $packUnit->id,
                'weight'       => 1.60,
                'description'  => 'Wholesale box of 12 tubes of genuine K-Y Jelly 4oz water-based lubricant. Safe, natural feeling formula compatible with latex condoms.',
                'image_slug'   => 'ky-jelly-water-based-lubricant-4oz-12pk',
                'brand'        => 'K-Y JELLY',
                'badge'        => 'PACK OF 12 TUBES (4 OZ EA)',
                'sub'          => 'Water Based Non-Greasy Body Lubricant',
                'bg1'          => [240, 253, 250],
                'bg2'          => [204, 251, 241],
                'accent'       => [15, 118, 110], // Cyan/Teal
                'tiers'        => [
                    ['min_qty' => 1, 'max_qty' => 4, 'price' => 46.80],
                    ['min_qty' => 5, 'max_qty' => 19, 'price' => 42.50],
                    ['min_qty' => 20, 'max_qty' => null, 'price' => 39.00],
                ],
                'stock'        => 85,
            ],

            // ====== GENERAL STORE / BATTERIES ======
            [
                'name'         => 'Duracell Coppertop AA Alkaline Batteries (Contractor 24-Pack Box)',
                'sku'          => 'GEN-DUR-AA',
                'category_slug'=> 'battery',
                'parent_slug'  => 'general-store',
                'base_price'   => 22.50,
                'unit_id'      => $boxUnit->id,
                'weight'       => 0.60,
                'description'  => '24 AA long-life alkaline batteries in bulk contractor box packaging. Engineered for everyday household electronics and emergency equipment.',
                'image_slug'   => 'duracell-coppertop-aa-batteries-24pk',
                'brand'        => 'DURACELL',
                'badge'        => 'CONTRACTOR BOX OF 24 AA',
                'sub'          => 'Coppertop Long-Lasting Power Guarantee',
                'bg1'          => [254, 243, 199],
                'bg2'          => [245, 158, 11],
                'accent'       => [180, 83, 9], // Copper
                'tiers'        => [
                    ['min_qty' => 1, 'max_qty' => 4, 'price' => 22.50],
                    ['min_qty' => 5, 'max_qty' => 19, 'price' => 20.25],
                    ['min_qty' => 20, 'max_qty' => null, 'price' => 18.50],
                ],
                'stock'        => 200,
            ],
            [
                'name'         => 'Energizer Max AAA Alkaline Long-Lasting Batteries (24-Pack Box)',
                'sku'          => 'GEN-ENR-AAA',
                'category_slug'=> 'battery',
                'parent_slug'  => 'general-store',
                'base_price'   => 21.90,
                'unit_id'      => $boxUnit->id,
                'weight'       => 0.35,
                'description'  => 'Pack of 24 AAA Energizer Max high performance alkaline batteries. PowerSeal Technology holds power up to 10 years in storage.',
                'image_slug'   => 'energizer-max-aaa-batteries-24pk',
                'brand'        => 'ENERGIZER',
                'badge'        => 'BOX OF 24 AAA BATTERIES',
                'sub'          => 'Max PowerSeal 10-Year Shelf Life Power',
                'bg1'          => [248, 250, 252],
                'bg2'          => [203, 213, 225],
                'accent'       => [234, 88, 12], // Energizer Orange
                'tiers'        => [
                    ['min_qty' => 1, 'max_qty' => 4, 'price' => 21.90],
                    ['min_qty' => 5, 'max_qty' => 19, 'price' => 19.80],
                    ['min_qty' => 20, 'max_qty' => null, 'price' => 17.90],
                ],
                'stock'        => 175,
            ],

            // ====== AUTOMOTIVE & ACCESSORIES ======
            [
                'name'         => 'Pennzoil Platinum Full Synthetic Motor Oil 5W-30 (Case of 6 x 1-Quart)',
                'sku'          => 'AUT-PEN-5W30',
                'category_slug'=> 'motor-oil',
                'parent_slug'  => 'automotive-accessories',
                'base_price'   => 42.00,
                'unit_id'      => $cartonUnit->id,
                'weight'       => 5.80,
                'description'  => 'Case of 6 individual 1-quart bottles of Pennzoil Platinum Full Synthetic 5W-30 made from natural gas. Superior wear protection and fuel economy.',
                'image_slug'   => 'pennzoil-platinum-5w30-motor-oil-case-6',
                'brand'        => 'PENNZOIL',
                'badge'        => 'CASE OF 6 (1-QUART BOTTLES)',
                'sub'          => 'Platinum Full Synthetic 5W-30 PurePlus',
                'bg1'          => [254, 252, 232],
                'bg2'          => [254, 240, 138],
                'accent'       => [234, 179, 8], // Yellow
                'tiers'        => [
                    ['min_qty' => 1, 'max_qty' => 4, 'price' => 42.00],
                    ['min_qty' => 5, 'max_qty' => 19, 'price' => 38.50],
                    ['min_qty' => 20, 'max_qty' => null, 'price' => 35.00],
                ],
                'stock'        => 90,
            ],
            [
                'name'         => 'Peak Original Antifreeze & Coolant 50/50 Prediluted (Case of 6 x 1-Gallon)',
                'sku'          => 'AUT-PEA-AF',
                'category_slug'=> 'anti-freeze',
                'parent_slug'  => 'automotive-accessories',
                'base_price'   => 49.50,
                'unit_id'      => $cartonUnit->id,
                'weight'       => 24.00,
                'description'  => 'Case of six 1-gallon jugs of Peak 50/50 prediluted all-makes all-models antifreeze coolant. Guaranteed protection for up to 5 years or 150,000 miles.',
                'image_slug'   => 'peak-antifreeze-50-50-prediluted-case-6',
                'brand'        => 'PEAK',
                'badge'        => 'CASE OF 6 (1-GALLON JUGS)',
                'sub'          => 'Original All Vehicles 50/50 Antifreeze',
                'bg1'          => [236, 253, 245],
                'bg2'          => [167, 243, 208],
                'accent'       => [16, 185, 129], // Green
                'tiers'        => [
                    ['min_qty' => 1, 'max_qty' => 4, 'price' => 49.50],
                    ['min_qty' => 5, 'max_qty' => 19, 'price' => 46.00],
                    ['min_qty' => 20, 'max_qty' => null, 'price' => 42.50],
                ],
                'stock'        => 60,
            ],

            // ====== AIRFRESHENERS & INCENSE ======
            [
                'name'         => 'Little Trees Air Fresheners Assorted Top Scents (72-Piece Counter Display)',
                'sku'          => 'AIR-LIT-TRE',
                'category_slug'=> 'airfreshener',
                'parent_slug'  => 'airfreshners-incense',
                'base_price'   => 39.50,
                'unit_id'      => $boxUnit->id,
                'weight'       => 0.85,
                'description'  => '72-piece assorted display featuring Black Ice, Vanillaroma, Strawberry, Caribbean Colada, and New Car Scent. America’s favorite car freshener.',
                'image_slug'   => 'little-trees-air-fresheners-assorted-72ct',
                'brand'        => 'LITTLE TREES',
                'badge'        => '72 TREE DISPLAY HANGER',
                'sub'          => 'Black Ice, Vanillaroma, New Car & More',
                'bg1'          => [240, 253, 244],
                'bg2'          => [187, 247, 208],
                'accent'       => [22, 163, 74], // Forest Green
                'tiers'        => [
                    ['min_qty' => 1, 'max_qty' => 4, 'price' => 39.50],
                    ['min_qty' => 5, 'max_qty' => 19, 'price' => 36.00],
                    ['min_qty' => 20, 'max_qty' => null, 'price' => 33.00],
                ],
                'stock'        => 140,
            ],

            // ====== LIGHTER & BUTANES ======
            [
                'name'         => 'BIC Classic Maxi Full-Size Pocket Lighters (50-Count Counter Display Tray)',
                'sku'          => 'LGT-BIC-MAX',
                'category_slug'=> 'lighters',
                'parent_slug'  => 'lighter-butanes',
                'base_price'   => 47.50,
                'unit_id'      => $boxUnit->id,
                'weight'       => 1.10,
                'description'  => 'Authentic BIC Classic Maxi disposable lighter 50-count retail counter display tray. Assorted vibrant colors with child-resistant safety guard.',
                'image_slug'   => 'bic-classic-maxi-pocket-lighters-50-tray',
                'brand'        => 'BIC',
                'badge'        => '50 COUNT TRAY DISPLAY',
                'sub'          => 'Maxi Full-Size Classic Lighters Assorted',
                'bg1'          => [254, 242, 242],
                'bg2'          => [254, 215, 170],
                'accent'       => [234, 88, 12], // Orange
                'tiers'        => [
                    ['min_qty' => 1, 'max_qty' => 4, 'price' => 47.50],
                    ['min_qty' => 5, 'max_qty' => 19, 'price' => 43.50],
                    ['min_qty' => 20, 'max_qty' => null, 'price' => 39.90],
                ],
                'stock'        => 220,
            ],
            [
                'name'         => 'Newport Zero Ultra-Refined Butane Gas 300ml Can (Master Pack of 12 Cans)',
                'sku'          => 'LGT-NEW-BUT',
                'category_slug'=> 'butane',
                'parent_slug'  => 'lighter-butanes',
                'base_price'   => 36.00,
                'unit_id'      => $packUnit->id,
                'weight'       => 3.20,
                'description'  => 'Master carton of 12 cans of Newport Zero 300ml refined lighter fuel with near zero impurities. Universal nozzle adapters included in every cap.',
                'image_slug'   => 'newport-zero-refined-butane-300ml-12pk',
                'brand'        => 'NEWPORT ZERO',
                'badge'        => 'PACK OF 12 CANS (300ML EA)',
                'sub'          => 'Extra Purified Near-Zero Impurity Butane',
                'bg1'          => [241, 245, 249],
                'bg2'          => [203, 213, 225],
                'accent'       => [15, 23, 42], // Slate/Navy
                'tiers'        => [
                    ['min_qty' => 1, 'max_qty' => 4, 'price' => 36.00],
                    ['min_qty' => 5, 'max_qty' => 19, 'price' => 32.50],
                    ['min_qty' => 20, 'max_qty' => null, 'price' => 29.00],
                ],
                'stock'        => 130,
            ],
        ];

        foreach ($productsData as $pData) {
            $cat = $categoryMap[$pData['category_slug']] 
                ?? $categoryMap[$pData['parent_slug']] 
                ?? Category::where('slug', $pData['category_slug'])->first()
                ?? Category::where('slug', $pData['parent_slug'])->first();

            if (!$cat) {
                $cat = Category::first();
            }

            // Create or update product
            $product = Product::updateOrCreate(
                ['sku' => $pData['sku']],
                [
                    'name'        => $pData['name'],
                    'category_id' => $cat->id,
                    'base_price'  => $pData['base_price'],
                    'unit_id'     => $pData['unit_id'],
                    'weight'      => $pData['weight'],
                    'description' => $pData['description'],
                    'is_active'   => true,
                ]
            );

            // Generate crisp professional product packaging image using GD
            $imgRelative = 'products/' . $pData['image_slug'] . '.jpg';
            $dest1 = $storageDir1 . '/' . $pData['image_slug'] . '.jpg';
            $dest2 = $storageDir2 . '/' . $pData['image_slug'] . '.jpg';

            $this->generateProductPackagingImage(
                $dest1,
                $pData['brand'],
                $pData['name'],
                $pData['sub'],
                $pData['badge'],
                $pData['bg1'],
                $pData['bg2'],
                $pData['accent']
            );

            // Copy to public/storage as well
            File::copy($dest1, $dest2);

            // Link image in product_images
            ProductImage::updateOrCreate(
                ['product_id' => $product->id, 'image_path' => $imgRelative],
                ['image_path' => $imgRelative]
            );

            // Variant & Stock
            $variant = ProductVariant::updateOrCreate(
                ['product_id' => $product->id, 'variant_sku' => $pData['sku'] . '-STD'],
                [
                    'size'  => 'Standard Wholesale Display',
                    'color' => null,
                ]
            );

            Stock::updateOrCreate(
                ['product_variant_id' => $variant->id, 'warehouse_id' => $warehouseId],
                [
                    'quantity'  => $pData['stock'],
                    'threshold' => 10,
                ]
            );

            // Price Tiers
            $product->priceTiers()->delete();
            foreach ($pData['tiers'] as $tier) {
                ProductPriceTier::create([
                    'product_id' => $product->id,
                    'min_qty'    => $tier['min_qty'],
                    'max_qty'    => $tier['max_qty'],
                    'price'      => $tier['price'],
                ]);
            }

            $this->command->line("Synced: {$product->name} (SKU: {$product->sku}, Stock: {$pData['stock']}, Image: {$imgRelative})");
        }

        $this->command->info("=== Categories & Products Sync Completed Successfully ===");
    }

    /**
     * Generate a crisp, beautiful 600x600 wholesale packaging image with GD
     */
    private function generateProductPackagingImage($filename, $brand, $title, $sub, $badge, $bgColor1, $bgColor2, $accentColor)
    {
        $width = 600;
        $height = 600;
        $im = imagecreatetruecolor($width, $height);

        // Smooth gradient background
        for ($y = 0; $y < $height; $y++) {
            $ratio = $y / $height;
            $r = (int)($bgColor1[0] + ($bgColor2[0] - $bgColor1[0]) * $ratio);
            $g = (int)($bgColor1[1] + ($bgColor2[1] - $bgColor1[1]) * $ratio);
            $b = (int)($bgColor1[2] + ($bgColor2[2] - $bgColor1[2]) * $ratio);
            $col = imagecolorallocate($im, $r, $g, $b);
            imageline($im, 0, $y, $width, $y, $col);
        }

        // Color palette
        $white = imagecolorallocate($im, 255, 255, 255);
        $dark = imagecolorallocate($im, 15, 23, 42); // slate-900
        $gray = imagecolorallocate($im, 100, 116, 139); // slate-500
        $accent = imagecolorallocate($im, $accentColor[0], $accentColor[1], $accentColor[2]);
        $cardBg = imagecolorallocate($im, 255, 255, 255);
        $border = imagecolorallocate($im, 226, 232, 240);
        $shadow = imagecolorallocate($im, 203, 213, 225);

        // Subtle shadow box
        imagefilledrectangle($im, 44, 34, 556, 566, $shadow);

        // Central Card
        imagefilledrectangle($im, 40, 30, 560, 560, $cardBg);
        imagerectangle($im, 40, 30, 560, 560, $border);

        // Top Brand Header Banner
        imagefilledrectangle($im, 40, 30, 560, 130, $accent);
        imagestring($im, 5, 60, 55, strtoupper($brand), $white);
        imagestring($im, 3, 60, 85, "WHOLESALE MASTER DISPENSER | ORIGINAL FACTORY SEALED", $white);

        // Badge pill
        $badgeBg = imagecolorallocate($im, 241, 245, 249);
        imagefilledrectangle($im, 60, 150, 540, 190, $badgeBg);
        imagerectangle($im, 60, 150, 540, 190, $border);
        imagestring($im, 4, 75, 162, ">> " . $badge, $accent);

        // Main Title (two lines if needed)
        $cleanTitle = trim($title);
        $line1 = substr($cleanTitle, 0, 42);
        $line2 = strlen($cleanTitle) > 42 ? substr($cleanTitle, 42, 42) : '';

        imagestring($im, 5, 60, 215, $line1, $dark);
        if ($line2) {
            imagestring($im, 5, 60, 240, $line2, $dark);
        }

        // Subtitle / Specs
        imagestring($im, 4, 60, 280, $sub, $gray);

        // Graphic Illustration Box
        $graphicBox = imagecolorallocate($im, 248, 250, 252);
        imagefilledrectangle($im, 80, 320, 520, 480, $graphicBox);
        imagerectangle($im, 80, 320, 520, 480, $border);

        // Render clean graphic pill/bottle icon inside graphic box
        imagefilledellipse($im, 300, 400, 160, 70, $accent);
        imagefilledellipse($im, 250, 400, 80, 70, $white);
        imagestring($im, 5, 280, 392, "OTC PACK", $dark);

        // Bottom Footer details
        imagestring($im, 3, 60, 510, "TAMPER-EVIDENT POUCHES | BARCODE SCANNABLE | FULL CASE LOT", $gray);
        imagestring($im, 2, 60, 532, "DISTRIBUTED EXCLUSIVELY FOR CERTIFIED B2B RETAIL RE-SALE", $gray);

        imagejpeg($im, $filename, 94);
        imagedestroy($im);
    }
}
