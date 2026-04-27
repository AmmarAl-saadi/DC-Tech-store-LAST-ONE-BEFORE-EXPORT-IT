<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "=== TABLES ===\n";
$tables = DB::select('SHOW TABLES');
foreach($tables as $table) {
    echo current((array)$table) . "\n";
}

echo "\n=== PRODUCT TABLE SCHEMA ===\n";
$columns = Schema::getColumnListing('products');
print_r($columns);

echo "\n=== PRODUCT_CATEGORIES TABLE SCHEMA ===\n";
if (Schema::hasTable('product_categories')) {
    $columns = Schema::getColumnListing('product_categories');
    print_r($columns);
} else {
    echo "product_categories table does NOT exist.\n";
}

echo "\n=== SAMPLE PRODUCT DATA ===\n";
$product = DB::table('products')->first();
print_r($product);

echo "\n=== SAMPLE PRODUCT_CATEGORIES DATA ===\n";
if (Schema::hasTable('product_categories')) {
    $pc = DB::table('product_categories')->limit(5)->get();
    print_r($pc);
}
