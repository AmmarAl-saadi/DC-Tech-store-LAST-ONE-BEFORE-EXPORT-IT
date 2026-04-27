<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$cat = DB::table('categories')->where('slug', 'computer-systems')->first();
echo "Category ID: " . $cat->id . "\n";
$subs = DB::table('categories')->where('parent', $cat->id)->get();
print_r($subs);
