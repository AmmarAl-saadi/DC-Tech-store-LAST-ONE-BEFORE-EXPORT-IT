<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use App\Models\ProductLang;
use App\Models\ProductCategory;
use App\Models\UpdatedInventory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

$cat = DB::table('categories')->where('slug', 'computer-systems')->first();
$tax = DB::table('tax_rules')->first();
$ship = DB::table('shipping_rules')->first();
$admin = DB::table('admins')->first();

if(!$cat) {
    die("Category not found\n");
}

$title = "CyberPowerPC Gamer Xtreme VR Gaming PC";
$slug = Str::slug($title) . '-' . time();

$product = Product::create([
    'title' => '',
    'selling' => 1299.99,
    'purchased' => 1299.99,
    'offered' => 1199.99,
    'image' => 'product_cyber_pc_1.png',
    'status' => 1,
    'admin_id' => $admin->id,
    'category_id' => $cat->id,
    'tax_rule_id' => $tax->id ?? 1,
    'shipping_rule_id' => $ship->id ?? 1,
    'slug' => $slug
]);

ProductLang::create([
    'product_id' => $product->id,
    'lang' => 'en',
    'title' => $title,
    'description' => '<p>Experience true gaming performance with the CyberPowerPC Gamer Xtreme VR series. Features latest generation high performance Intel Core processors and ultra-quick DDR RAM to easily handle system-intensive tasks, such as high definition video playback and gaming.</p>',
    'overview' => '<ul><li>Intel Core i7 Processor</li><li>NVIDIA GeForce RTX 4060</li><li>16GB DDR5 RAM</li><li>1TB NVMe SSD</li><li>RGB Tempered Glass Case</li></ul>',
    'meta_title' => $title,
    'meta_description' => 'Buy the ultimate gaming PC with RTX 4060 and Core i7.'
]);

ProductCategory::create([
    'product_id' => $product->id,
    'category_id' => $cat->id,
    'primary' => 1
]);

UpdatedInventory::create([
    'product_id' => $product->id,
    'quantity' => 15,
    'price' => 1299.99,
    'sku' => 'CYBER-PC-' . time()
]);

echo "Product created successfully with ID: " . $product->id . "\n";
