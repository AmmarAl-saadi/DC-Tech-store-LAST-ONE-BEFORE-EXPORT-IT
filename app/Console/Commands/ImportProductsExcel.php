<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class ImportProductsExcel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:import {file} {lang=en}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import products from Excel file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $file = $this->argument('file');
        $lang = $this->argument('lang');

        if (!file_exists($file)) {
            $this->error("File not found: " . $file);
            return 1;
        }

        $this->info("Importing products from: " . $file . " with language: " . $lang);

        // Mock auth for the importer
        Auth::loginUsingId(1);

        try {
            Excel::import(new ProductsImport($lang), $file);
            $this->info("Import completed successfully!");
        } catch (\Exception $e) {
            $this->error("Import failed: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
