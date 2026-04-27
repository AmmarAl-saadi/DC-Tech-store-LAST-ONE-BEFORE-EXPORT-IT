<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// 1. Manually fix Playstation 1
$gamingCat = DB::table('categories')->where('slug', 'gaming')->first();
if ($gamingCat) {
    DB::table('products')->where('title', 'Playstation 1')->update([
        'category_id' => $gamingCat->id
    ]);
    
    // Add to product_categories
    DB::table('product_categories')->updateOrInsert(
        ['product_id' => 92133117, 'category_id' => $gamingCat->id],
        ['primary' => 1, 'updated_at' => now()]
    );
    echo "Fixed Playstation 1.\n";
}

// 2. Comprehensive cleanup of junk categories
$junkCatIds = DB::table('categories')
    ->where(function($q) {
        $q->where('title', '')->orWhereNull('title');
    })
    ->pluck('id')
    ->toArray();

if (!empty($junkCatIds)) {
    echo "Found " . count($junkCatIds) . " junk categories to clean.\n";
    
    // Delete related records to satisfy foreign keys
    DB::table('category_langs')->whereIn('category_id', $junkCatIds)->delete();
    DB::table('product_categories')->whereIn('category_id', $junkCatIds)->delete();
    
    // Now delete categories
    $deleted = DB::table('categories')->whereIn('id', $junkCatIds)->delete();
    echo "Deleted $deleted junk categories.\n";
} else {
    echo "No junk categories found.\n";
}
