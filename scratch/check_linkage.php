<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$totalProducts = DB::table('products')->count();
$productsWithValidCat = DB::table('product_categories')
    ->join('categories', 'product_categories.category_id', '=', 'categories.id')
    ->where('categories.title', '!=', '')
    ->distinct('product_id')
    ->count();

echo "Total Products: $totalProducts\n";
echo "Products linked to valid categories: $productsWithValidCat\n";

if ($totalProducts > 0) {
    $sample = DB::table('products')->where('id', '64444140')->first();
    echo "\nSample Product: {$sample->title}\n";
    echo "Tags: {$sample->tags}\n";
    
    $linkedCats = DB::table('product_categories')
        ->join('categories', 'product_categories.category_id', '=', 'categories.id')
        ->where('product_id', $sample->id)
        ->select('categories.id', 'categories.title', 'categories.parent')
        ->get();
    
    echo "Current linked categories:\n";
    foreach ($linkedCats as $cat) {
        echo " - ID:{$cat->id} Title:\"{$cat->title}\" Parent:{$cat->parent}\n";
    }
}
