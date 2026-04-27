<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// 1. Get all valid categories and build a map
$allCats = DB::table('categories')
    ->where('title', '!=', '')
    ->whereNotNull('title')
    ->get();

$catMap = []; // title (lowercase) => category object
foreach ($allCats as $cat) {
    $catMap[strtolower(trim($cat->title))] = $cat;
}

// Helper to get top-level parent
function getTopParent($catId, $allCatsById) {
    $currentId = $catId;
    while (isset($allCatsById[$currentId]) && $allCatsById[$currentId]->parent != 0) {
        $currentId = $allCatsById[$currentId]->parent;
    }
    return $currentId;
}

$allCatsById = [];
foreach ($allCats as $cat) {
    $allCatsById[$cat->id] = $cat;
}

echo "Found " . count($catMap) . " valid categories.\n";

// 2. Process products
$products = DB::table('products')->get();
$fixedCount = 0;

foreach ($products as $product) {
    echo "Processing: {$product->title}\n";
    $matchedCatIds = [];
    
    // Check tags
    $tags = explode(',', $product->tags);
    $tags[] = $product->title; // Also check title
    
    foreach ($tags as $tag) {
        $cleanTag = strtolower(trim($tag));
        if (empty($cleanTag)) continue;
        
        // Try exact match
        if (isset($catMap[$cleanTag])) {
            $matchedCatIds[] = $catMap[$cleanTag]->id;
        }
        
        // Try partial match if no exact match (e.g. "Gaming Speakers" matches "Speakers")
        foreach ($catMap as $catTitle => $cat) {
            if ($catTitle == $cleanTag || (strlen($catTitle) > 3 && strpos($cleanTag, $catTitle) !== false)) {
                $matchedCatIds[] = $cat->id;
            }
        }
    }
    
    $matchedCatIds = array_unique($matchedCatIds);
    
    if (!empty($matchedCatIds)) {
        // Clear old bad links
        DB::table('product_categories')->where('product_id', $product->id)->delete();
        
        $primaryCatId = 0;
        $subCatId = 0;
        
        foreach ($matchedCatIds as $cid) {
            DB::table('product_categories')->insert([
                'product_id' => $product->id,
                'category_id' => $cid,
                'primary' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            // Try to determine primary and sub
            $cat = $allCatsById[$cid];
            if ($cat->parent == 0) {
                if (!$primaryCatId) $primaryCatId = $cid;
            } else {
                if (!$subCatId) $subCatId = $cid;
                if (!$primaryCatId) $primaryCatId = getTopParent($cid, $allCatsById);
            }
        }
        
        // Update product table
        DB::table('products')->where('id', $product->id)->update([
            'category_id' => $primaryCatId,
            'subcategory_id' => $subCatId
        ]);
        
        echo " -> Linked to " . count($matchedCatIds) . " categories. Top: $primaryCatId, Sub: $subCatId\n";
        $fixedCount++;
    } else {
        echo " -> No match found for tags/title.\n";
    }
}

echo "\nFixed $fixedCount products.\n";

// 3. Clean up empty categories that have no products
$emptyCatIds = DB::table('categories')
    ->where(function($q) {
        $q->where('title', '')->orWhereNull('title');
    })
    ->pluck('id');

$deletedCount = DB::table('categories')->whereIn('id', $emptyCatIds)->delete();
echo "Deleted $deletedCount empty junk categories.\n";
