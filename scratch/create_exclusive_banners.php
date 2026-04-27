<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Banner;

// Computer Systems Banner
Banner::updateOrCreate(
    ['slug' => 'home-exclusive-computer-systems'],
    [
        'title' => 'COMPUTER SYSTEMS',
        'image' => '/categories/cat_computer_systems_1777204862889.png',
        'url' => '/all/computer-systems',
        'status' => 1,
        'source_type' => 4,
        'type' => 10, // Exclusive type
        'admin_id' => 1,
        'tags' => 'Workstations, desktops, and customized PCs.'
    ]
);

// Gaming Section Banner
Banner::updateOrCreate(
    ['slug' => 'home-exclusive-gaming-section'],
    [
        'title' => 'GAMING SECTION',
        'image' => '/categories/cat_gaming_1777204920446.png',
        'url' => '/all/gaming',
        'status' => 1,
        'source_type' => 4,
        'type' => 10, // Exclusive type
        'admin_id' => 1,
        'tags' => 'Gaming Accessories, RGB Builds and Gears.'
    ]
);

echo "Banners created successfully.";
