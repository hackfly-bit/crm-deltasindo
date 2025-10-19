<?php

namespace App\Console\Commands;

use App\Models\Sph;
use App\Models\SphProduct;
use App\Models\Product;
use App\Models\Category\Principal as Brand;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MigrateSphProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate-product:sph';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate sph product to product visit';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $sphs = $this->getSphProduct();

        Log::info('MigrateSphProduct: Starting migration', ['total_sphs' => count($sphs)]);

        foreach ($sphs as $sph) {
            $sph_id = $sph->id;
            $brand_id = $sph->brand;
            $produk_data = $sph->produk;

            // Handle null or empty brand
            if (empty($brand_id)) {
                Log::warning('MigrateSphProduct: Skipping sph due to empty brand_id', ['sph_id' => $sph_id]);
                continue;
            }

            // Check if brand exists
            $brandExists = Brand::find($brand_id);
            if (!$brandExists) {
                Log::warning('MigrateSphProduct: Skipping sph, brand not found', ['brand_id' => $brand_id, 'sph_id' => $sph_id]);
                continue;
            }

            // Handle null or empty produk
            if (empty($produk_data)) {
                Log::warning('MigrateSphProduct: Skipping sph due to empty produk_data', ['sph_id' => $sph_id]);
                continue;
            }

            $product_names = [];

            // Handle comma-separated string directly (based on log data structure)
            if (is_string($produk_data)) {
                $product_names = array_map('trim', explode(',', $produk_data));
            }

            // Filter out empty product names
            $product_names = array_filter($product_names, function($name) {
                return !empty($name);
            });

            if (empty($product_names)) {
                Log::warning('MigrateSphProduct: Skipping sph due to empty product names after filtering', ['sph_id' => $sph_id, 'produk_data' => $produk_data]);
                continue;
            }

            foreach ($product_names as $product_name) {
                // Find product by name
                $product = Product::where('nama_produk', $product_name)->first();

                if ($product) {
                    $this->insertIntoSphProduct($sph_id, (int) $brand_id, $product->id);
                    // Log::info('MigrateSphProduct: Successfully inserted', ['sph_id' => $sph_id, 'brand_id' => $brand_id, 'product_id' => $product->id, 'product_name' => $product_name]);
                } else {
                    Log::warning('MigrateSphProduct: Product not found, skipping insertion', ['product_name' => $product_name, 'sph_id' => $sph_id]);
                }
            }
        }

        Log::info('MigrateSphProduct: Migration completed successfully.');
        return Command::SUCCESS;
    }

    public function getSphProduct(){
        return Sph::select('id','brand','produk')->get();
    }

    public function insertIntoSphProduct($id, $brand_id, $product_id){
        return SphProduct::create([
            'sph_id' => $id,
            'brand_id' => $brand_id,
            'product_id' => $product_id,
            'quantity' => 1,
            'notes' => null,
        ]);
    }
}
