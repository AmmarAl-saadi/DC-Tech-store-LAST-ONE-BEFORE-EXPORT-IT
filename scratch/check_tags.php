<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$products = App\Models\Product::whereNotNull('tags')->take(50)->get();
foreach ($products as $p) {
    echo $p->id . ': ' . $p->title . ' | Tags: ' . $p->tags . "\n";
}
