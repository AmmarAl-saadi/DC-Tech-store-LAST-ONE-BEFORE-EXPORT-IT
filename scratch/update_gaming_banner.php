<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Banner;

$updated = Banner::where('slug', 'home-exclusive-gaming-section')->update(['image' => '/categories/gaming_white_bg_angled.png']);
echo "Rows updated: " . $updated;
