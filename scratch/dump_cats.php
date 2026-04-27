<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Just dump all categories with their parent info
$all = DB::table('categories')
    ->where('status', 2)
    ->where('title','!=','')
    ->orderBy('parent')
    ->orderBy('id')
    ->select('id','title','slug','image','parent')
    ->get();

foreach ($all as $cat) {
    echo "parent:{$cat->parent} | ID:{$cat->id} | slug:{$cat->slug} | title:{$cat->title}\n";
}
