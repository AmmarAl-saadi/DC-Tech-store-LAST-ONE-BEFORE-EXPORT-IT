<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$brands = App\Models\Brand::where('status', 1)->get();
foreach ($brands as $b) {
    echo "{ id: {$b->id}, title: '{$b->title}', slug: '{$b->slug}', image: '/uploads/{$b->image}' },\n";
}
