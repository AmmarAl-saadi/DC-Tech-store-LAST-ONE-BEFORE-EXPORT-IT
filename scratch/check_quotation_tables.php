<?php
include 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$tables = DB::select('SHOW TABLES');
foreach ($tables as $table) {
    $name = array_values((array)$table)[0];
    if (stripos($name, 'quote') !== false || stripos($name, 'quotation') !== false) {
        echo "FOUND: $name\n";
    }
}
