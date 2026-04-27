<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$productId = 87715120;
$parentCatId = 99585158;
$subCatId = 99585161;

// Update Product table just in case
$p = App\Models\Product::find($productId);
if ($p) {
    $p->category_id = $parentCatId;
    $p->subcategory_id = $subCatId;
    $p->save();
}

// Update ProductCategory mapping table
App\Models\ProductCategory::where('product_id', $productId)->delete();

App\Models\ProductCategory::create([
    'product_id' => $productId,
    'category_id' => $parentCatId,
]);

App\Models\ProductCategory::create([
    'product_id' => $productId,
    'category_id' => $subCatId,
]);

echo "Updated mapping for product $productId\n";
