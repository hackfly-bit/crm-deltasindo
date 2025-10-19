<?php

namespace App\Console\Commands;

use App\Models\Kegiatan_visit;
use App\Models\VisitProduct;
use App\Models\Product;
use App\Models\Category\Principal as Brand;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MigrateVisitProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate-product:visit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'migrate product visit';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {


        $visits = $this->getVisitProduct();

        foreach ($visits as $visit) {
            $kegiatan_visit_id = $visit->id;
            $brand_id = $visit->brand;
            $produk_data = $visit->produk;

            // Handle null or empty brand
            if (empty($brand_id)) {
                Log::warning('MigrateVisitProduct: Skipping visit due to empty brand_id', ['kegiatan_visit_id' => $kegiatan_visit_id]);
                continue;
            }

            // Check if brand exists
            $brandExists = Brand::find($brand_id);
            if (!$brandExists) {
                Log::warning('MigrateVisitProduct: Skipping visit, brand not found', ['brand_id' => $brand_id, 'kegiatan_visit_id' => $kegiatan_visit_id]);
                continue;
            }

            $product_names = [];

            // Try to decode JSON
            $decoded_produk = json_decode($produk_data, true);

            if (json_last_error() === JSON_ERROR_NONE && isset($decoded_produk['produk'])) {
                // Handle JSON array of products
                if (is_array($decoded_produk['produk'])) {
                    $product_names = $decoded_produk['produk'];
                } else if (is_string($decoded_produk['produk'])) {
                    // Handle JSON string of comma-separated products
                    $product_names = array_map('trim', explode(',', $decoded_produk['produk']));
                }
            } else {
                // Handle comma-separated string directly
                $product_names = array_map('trim', explode(',', $produk_data));
            }

            // Filter out empty product names
            $product_names = array_filter($product_names);

            if (empty($product_names)) {
                Log::warning('MigrateVisitProduct: Skipping visit due to empty product names', ['kegiatan_visit_id' => $kegiatan_visit_id, 'produk_data' => $produk_data]);
                continue;
            }

            foreach ($product_names as $product_name) {
                // Find product by name
                $product = Product::where('nama_produk', $product_name)->first();

                if ($product) {
                    $this->insertIntoProductVisit($kegiatan_visit_id, (int) $brand_id, $product->id);
                    // Log::info('Data Insert For kegiatan_visit_id :' . $kegiatan_visit_id, [
                    //     'brand_id' => (int) $brand_id,
                    //     'product_id' => $product->id,
                    // ]);
                } else {
                    Log::warning('MigrateVisitProduct: Product not found, skipping insertion', ['product_name' => $product_name, 'kegiatan_visit_id' => $kegiatan_visit_id]);
                }
            }
        }

        Log::info('MigrateVisitProduct: Migration completed successfully.');
        return Command::SUCCESS;
    }


    public function getVisitProduct(){
        return Kegiatan_visit::select('id','brand','produk')->get();
    }


    public function insertIntoProductVisit($id , $brand_id , $product_id){
        return VisitProduct::create([
            'kegiatan_visit_id' => $id,
            'brand_id' => $brand_id,
            'product_id' => $product_id,
            'quantity' => 1,
            'notes' => null,
        ]);
    }



}
