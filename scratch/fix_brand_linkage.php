<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$allBrands = App\Models\Brand::all();
$publicBrands = [];
$privateBrands = [];

foreach ($allBrands as $b) {
    $title = $b->title;
    if (empty($title)) {
        $lang = App\Models\BrandLang::where('brand_id', $b->id)->where('lang', 'en')->first();
        $title = $lang ? $lang->title : '';
    }
    
    if (empty($title)) continue;
    
    $brandData = [
        'id' => $b->id,
        'title' => strtoupper($title),
        'status' => $b->status
    ];
    
    if ($b->status == 1) {
        $publicBrands[strtoupper($title)] = $b->id;
    } else {
        $privateBrands[] = $brandData;
    }
}

echo "=== MAPPING PRIVATE TO PUBLIC BRANDS ===\n";
foreach ($privateBrands as $pb) {
    if (isset($publicBrands[$pb['title']])) {
        $publicId = $publicBrands[$pb['title']];
        echo "Private ID:{$pb['id']} -> Public ID:{$publicId} ({$pb['title']})\n";
        
        // Update products
        $count = App\Models\Product::where('brand_id', $pb['id'])->count();
        if ($count > 0) {
            App\Models\Product::where('brand_id', $pb['id'])->update(['brand_id' => $publicId]);
            echo "  Updated $count products\n";
        }
    } else {
        echo "Private ID:{$pb['id']} ({$pb['title']}) - NO PUBLIC MATCH FOUND. Making public...\n";
        App\Models\Brand::where('id', $pb['id'])->update(['status' => 1, 'title' => $pb['title']]);
    }
}
