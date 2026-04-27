<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$products = DB::table('products')->select('id', 'title', 'category_id', 'subcategory_id')->get();

foreach ($products as $p) {
    echo "ID:{$p->id} | Title:{$p->title} | Cat:{$p->category_id} | Sub:{$p->subcategory_id}\n";
}
