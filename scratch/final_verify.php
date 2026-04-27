<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$unlinked = DB::table('products')->where('category_id', 0)->count();
echo "Products still unlinked: $unlinked\n";

if ($unlinked > 0) {
    $list = DB::table('products')->where('category_id', 0)->select('title')->get();
    foreach ($list as $p) {
        echo " - {$p->title}\n";
    }
}
