<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$categories = DB::table('categories')
    ->where('title', '!=', '')
    ->whereNotNull('title')
    ->select('id', 'title', 'slug', 'parent')
    ->get();

echo "Count: " . count($categories) . "\n";
foreach ($categories as $cat) {
    echo "ID:{$cat->id} | parent:{$cat->parent} | slug:{$cat->slug} | title:{$cat->title}\n";
}
