<?php

namespace App\Http\Controllers;

use App\Models\Category\Jenis_Kegiatan;
use App\Models\Category\Pertemuan;
use App\Models\Category\Principal;
use App\Models\Category\Status;
use App\Models\Customer;
use App\Models\Kegiatan_visit;
use App\Models\VisitProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KegiatanVisitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kegiatan_visit = Kegiatan_visit::all();
        $kegiatan_visit_sales = Kegiatan_visit::where('user_id', Auth::user()->id)->get();

        return view('kegiatan.visit.index', compact('kegiatan_visit', 'kegiatan_visit_sales'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customer = Customer::all();
        $jenis_kegiatan = Jenis_Kegiatan::all();
        $principal = Principal::all();
        $pertemuan = Pertemuan::all();
        $status = Status::all();
        $customer_by_sales = Customer::where('user_id', Auth::user()->id)->get();

        return view('kegiatan.visit.create', compact('jenis_kegiatan', 'principal', 'pertemuan', 'status', 'customer', 'customer_by_sales'));
    }

    public function history($id)
    {
        $visit = Kegiatan_visit::find($id);
        $history = $visit->revisionHistory;

        return view('kegiatan.other.history', compact('history', 'visit'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([

            // 'tanggal' => 'required',
            // 'products' => 'required',
            // 'brand' => 'required',
            // 'pertemuan' => 'required',
            // 'note' => 'required'

        ]);

        $visit = new Kegiatan_visit;
        $visit->user_id = Auth::user()->id;
        $visit->customer_id = $request->customer;
        $visit->kegiatan = "Visit";
        $visit->tanggal = $request->tanggal;
        $visit->pertemuan = $request->pertemuan;
        $visit->note = $request->note;

        // Checking Duplicate Data

        $visit->save();

        // Simpan produk ke tabel visit_products (multiple brand dan produk)
        $brandIds = is_array($request->brand) ? $request->brand : ($request->brand ? [$request->brand] : []);
        $productIds = is_array($request->products) ? $request->products : ($request->products ? [$request->products] : []);

        if (!empty($productIds)) {
            foreach ($productIds as $productId) {
                $productModel = Product::find($productId);
                if (!$productModel) {
                    continue;
                }

                // Jika brand dipilih, gunakan brand yang cocok dengan product principal_id
                // Jika tidak ada brand dipilih, gunakan principal_id dari product
                $candidateBrandIds = !empty($brandIds) ? $brandIds : [$productModel->principal_id];
                foreach ($candidateBrandIds as $brandId) {
                    // Hanya simpan pasangan brand-product yang valid
                    if ((int)$brandId !== (int)$productModel->principal_id) {
                        continue;
                    }

                    // Hindari duplikasi sesuai unique constraint
                    VisitProduct::firstOrCreate(
                        [
                            'kegiatan_visit_id' => $visit->id,
                            'product_id' => $productId,
                            'brand_id' => (int)$brandId,
                        ],
                        [
                            'quantity' => 1,
                            'notes' => null,
                        ]
                    );
                }
            }
        }


        return redirect()->route('customer.visit', $request->customer)->with('success', 'Data Visit Berhasil Di Tambah !!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Kegiatan_visit  $kegiatan_visit
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Kegiatan_visit  $kegiatan_visit
     * @return \Illuminate\Http\Response
     */
    public function edit($customer_id, $id)
    {
        $title = "Daftar History Visit";
        $customer = Customer::all();
        $visit_edit = Kegiatan_visit::find($id);
        $visit = Kegiatan_visit::where('customer_id', $customer_id)->get();
        $visit_by_sales = Customer::where('user_id', Auth::user()->id)->get();
        $brand = Principal::all();
        $pertemuan = Pertemuan::all();

        return view('kegiatan.visit.index', compact('title', 'visit_edit', 'visit', 'visit_by_sales', 'customer', 'pertemuan', 'brand'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Kegiatan_visit  $kegiatan_visit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([

            // 'customer' => 'required',
            // 'jenis_kegiatan' => 'required',
            // 'tanggal' => 'required',
            // 'produk' => 'required',
            // 'principal' => 'required',
            // 'pertemuan' => 'required',
            // 'status' => 'required',
            // 'deskripsi' => 'required'

        ]);


        $visit = Kegiatan_visit::find($id);
        $visit->user_id = Auth::user()->id;
        $visit->customer_id = $request->customer;
        $visit->kegiatan = "Visit";
        $visit->tanggal = $request->tanggal;
        // $visit->produk = json_encode(["produk" => $request->products]); // Keep for backward compatibility
        // // Simpan brand sebagai CSV untuk kompatibilitas lama
        // $visit->brand = is_array($request->brand) ? implode(',', $request->brand) : $request->brand;
        $visit->pertemuan = $request->pertemuan;
        $visit->note = $request->note;

        $visit->save();

        // Update products in visit_products table
        // First, delete existing products for this visit
        VisitProduct::where('kegiatan_visit_id', $visit->id)->delete();

        // Then, add new products (multiple brand dan produk)
        $brandIds = is_array($request->brand) ? $request->brand : ($request->brand ? [$request->brand] : []);
        $productIds = is_array($request->products) ? $request->products : ($request->products ? [$request->products] : []);

        if (!empty($productIds)) {
            foreach ($productIds as $productId) {
                $productModel = Product::find($productId);
                if (!$productModel) {
                    continue;
                }

                $candidateBrandIds = !empty($brandIds) ? $brandIds : [$productModel->principal_id];
                foreach ($candidateBrandIds as $brandId) {
                    if ((int)$brandId !== (int)$productModel->principal_id) {
                        continue;
                    }

                    VisitProduct::firstOrCreate(
                        [
                            'kegiatan_visit_id' => $visit->id,
                            'product_id' => $productId,
                            'brand_id' => (int)$brandId,
                        ],
                        [
                            'quantity' => 1,
                            'notes' => null,
                        ]
                    );
                }
            }
        }

        return redirect()->route('customer.visit', $request->customer)->with('success', 'Data Visit Berhasil Di Update !!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Kegiatan_visit  $kegiatan_visit
     * @return \Illuminate\Http\Response
     */
    public function destroy($customer_id, $id)
    {
        $visit = Kegiatan_visit::find($id);

        // Delete related products first
        VisitProduct::where('kegiatan_visit_id', $id)->delete();

        $visit->delete();

        return redirect()->route('customer.visit', $customer_id)->with('delete', 'Data Visit Berhasil Di Hapus !!');
    }
}
