<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Maatwebsite\Excel\Facades\Excel;

$file = 'products-2026-04-26-11_26_32.xlsx';
$data = Excel::toArray(new stdClass(), $file);

echo "Total tabs: " . count($data) . "\n";
foreach ($data as $i => $tab) {
    echo "Tab $i: " . count($tab) . " rows\n";
}
