<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$brands = App\Models\Brand::all();
foreach ($brands as $b) {
    if (empty($b->title)) continue;
    $count = App\Models\Product::where('title', 'like', '%' . $b->title . '%')->count();
    echo $b->title . ': ' . $count . " products found\n";
}
