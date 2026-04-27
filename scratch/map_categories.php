<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Get all top-level categories (parent = 0 or NULL)
$topLevel = DB::table('categories')
    ->where(function($q){ $q->where('parent', 0)->orWhereNull('parent'); })
    ->where('title', '!=', '')
    ->where('status', 2)
    ->select('id','title','slug','image','parent')
    ->get();

echo "=== TOP-LEVEL CATEGORIES ===\n";
foreach ($topLevel as $cat) {
    echo "ID:{$cat->id} slug:{$cat->slug} title:{$cat->title}\n";
    
    $children = DB::table('categories')
        ->where('parent', $cat->id)
        ->where('status', 2)
        ->where('title','!=','')
        ->select('id','title','slug','image','parent')
        ->get();
    
    foreach ($children as $child) {
        echo "  -> ID:{$child->id} slug:{$child->slug} title:{$child->title}\n";
        
        // Also get grandchildren
        $grandchildren = DB::table('categories')
            ->where('parent', $child->id)
            ->where('status', 2)
            ->where('title','!=','')
            ->select('id','title','slug')
            ->get();
        foreach ($grandchildren as $gc) {
            echo "      --> ID:{$gc->id} slug:{$gc->slug} title:{$gc->title}\n";
        }
    }
}
