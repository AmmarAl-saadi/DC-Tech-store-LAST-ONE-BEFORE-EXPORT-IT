<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$category_ids = [99585400, 99585401, 99585402];
$categories = DB::table('categories')->whereIn('id', $category_ids)->get();

foreach ($categories as $cat) {
    echo "ID:{$cat->id} | parent:{$cat->parent} | slug:{$cat->slug} | title:{$cat->title}\n";
}
