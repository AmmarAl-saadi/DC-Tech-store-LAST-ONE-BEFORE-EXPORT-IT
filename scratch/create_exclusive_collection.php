<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ProductCollection;

$c = ProductCollection::where('slug', 'exclusive')->first();
if (!$c) {
    $c = new ProductCollection();
    $c->title = 'Exclusive';
    $c->status = 1;
    $c->slug = 'exclusive';
    $c->admin_id = 1;
    $c->save();
    echo "Created collection ID: " . $c->id;
} else {
    echo "Collection already exists ID: " . $c->id;
}
