<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$cats = DB::table('categories')->whereNull('parent')->orWhere('parent', 0)->get();
print_r($cats);
