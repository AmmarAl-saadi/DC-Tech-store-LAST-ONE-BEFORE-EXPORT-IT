<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$file = 'products-2026-04-26-11_26_32.xlsx';
$spreadsheet = IOFactory::load($file);
$sheetNames = $spreadsheet->getSheetNames();
foreach ($sheetNames as $name) {
    echo "Sheet: $name\n";
}
