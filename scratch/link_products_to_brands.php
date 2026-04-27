<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$brands = App\Models\Brand::all();
$updatedCount = 0;

foreach ($brands as $b) {
    if (empty($b->title)) continue;
    
    $title = trim($b->title);
    $noSpaceTitle = str_replace(' ', '', $title);
    $hyphenTitle = str_replace(' ', '-', $title);
    
    $variations = array_unique([$title, $noSpaceTitle, $hyphenTitle]);
    
    foreach ($variations as $var) {
        if (strlen($var) < 3) continue; // Avoid matching too many things
        
        $products = App\Models\Product::where(function($q) use ($var) {
            $q->where('title', 'like', '%' . $var . '%')
              ->orWhere('tags', 'like', '%' . $var . '%');
        })->get();
        
        foreach ($products as $p) {
            if ($p->brand_id != $b->id) {
                $p->brand_id = $b->id;
                $p->save();
                $updatedCount++;
                echo "Linked product '{$p->title}' to brand '{$title}' (Matched variation: '{$var}')\n";
            }
        }
    }
}

echo "Total products updated: $updatedCount\n";
