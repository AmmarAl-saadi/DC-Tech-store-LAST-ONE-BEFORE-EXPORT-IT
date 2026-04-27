<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Maatwebsite\Excel\Facades\Excel;

$file = 'products-2026-04-26-11_26_32.xlsx';
$data = Excel::toArray(new stdClass(), $file);
$rows = $data[0];

$fp = fopen('scratch/excel_dump.txt', 'w');
foreach ($rows as $i => $row) {
    if ($i > 50) break;
    fwrite($fp, "Row $i: " . implode(' | ', $row) . "\n");
}
fclose($fp);
echo "Dumped " . min(50, count($rows)) . " rows to scratch/excel_dump.txt\n";
