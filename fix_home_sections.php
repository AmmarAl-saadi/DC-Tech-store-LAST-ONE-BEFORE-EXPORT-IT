<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\ProductCollection;
use App\Models\ProductCollectionLang;

// 1. Setup TOP SELLING (ID 9)
$topSellingId = 9;
ProductCollection::where('id', $topSellingId)->update(['status' => 1]); // Set to PUBLIC
ProductCollectionLang::updateOrCreate(
    ['product_collection_id' => $topSellingId, 'lang' => 'en'],
    ['title' => 'TOP SELLING']
);

// 2. Setup RECOMMENDED FOR YOU (ID 4)
$recommendedId = 4;
ProductCollection::where('id', $recommendedId)->update(['status' => 1]); // Set to PUBLIC
ProductCollectionLang::updateOrCreate(
    ['product_collection_id' => $recommendedId, 'lang' => 'en'],
    ['title' => 'RECOMMENDED FOR YOU']
);

// 3. Add products to both
$products = [98394135, 64444140, 67153138, 67623165, 71721150, 71984114, 72060141, 75653111];

foreach ([$topSellingId, $recommendedId] as $collId) {
    foreach ($products as $pid) {
        $exists = DB::table('collection_with_products')
            ->where('product_collection_id', $collId)
            ->where('product_id', $pid)
            ->exists();
        
        if (!$exists) {
            DB::table('collection_with_products')->insert([
                'product_collection_id' => $collId,
                'product_id' => $pid,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}

echo "Home page sections updated and made public.\n";
