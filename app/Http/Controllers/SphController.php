<?php

namespace App\Http\Controllers;

use App\Models\Category\Principal;
use App\Models\Sph;
use App\Models\SphProduct;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Category\Status;
use App\Models\Category\Time_Line;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\Category\Sumber_Anggaran;
use App\Models\Category\Metode_Pembelian;
use App\Models\Category\Metode_Pembayaran;

class SphController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sph = Sph::all();
        $sph_sales = Sph::where('user_id', Auth::user()->id)->get();

        return view('sph.index', compact('sph', 'sph_sales'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sumber_anggaran = Sumber_Anggaran::all();
        $customer = Customer::all();
        $metode_pembelian = Metode_Pembelian::all();
        $metode_pembayaran = Metode_Pembayaran::all();
        $time_line = Time_Line::all();

        return view('sph.create', compact('customer', 'sumber_anggaran', 'metode_pembelian', 'metode_pembayaran', 'time_line'));
    }

    public function history($id)
    {
        $sph = Sph::find($id);
        $history = $sph->revisionHistory;

        return view('sph.history', compact('history', 'sph'));
    }



    public function store(Request $request)
    {
        $request->validate([
            // 'sumber_anggaran' => 'required',
            // // 'nilai_pagu' => 'required',
            // 'brand' => 'required',
            // 'products' => 'required',
            // 'metode_pembelian' => 'required',
            // 'status' =>  'required',
            // 'time_line' => 'required',
            // 'winrate' => 'required',
            // 'pdf_file' => 'required|mimes:pdf|max:10000'
        ]);
        //upload pdf
        if ($request->hasFile('pdf_file')) {

            $pdfName = time() . '.' . $request->pdf_file->extension();
            $uploadedPdf = $request->pdf_file->move(public_path('assets/pdf'), $pdfName);
            $pdfPath = $pdfName;

            $sph = new Sph;
            $sph->user_id =  Auth::user()->id;
            $sph->customer_id = $request->customer;
            $sph->kegiatan = "Qoutation";
            // $sph->brand = $request->brand;
            // $sph->produk = json_encode(["produk" => $request->products]); // Keep for backward compatibility
            $sph->sumber_anggaran = $request->sumber_anggaran;
            $sph->nilai_pagu = intval(str_replace(["Rp.", ".00", ","], "", $request->nilai_pagu));
            $sph->metode_pembelian = $request->metode_pembelian;
            $sph->status = $request->status;
            $sph->time_line = $request->time_line;
            $sph->winrate = $request->winrate;
            $sph->note = $request->note;
            $sph->pdf_file = $pdfPath;

            $sph->save();

            // Simpan produk ke tabel sph_products (multiple brand dan produk)
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
                        SphProduct::firstOrCreate(
                            [
                                'sph_id' => $sph->id,
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

            return redirect()->route('customer.sph', $request->customer)->with('success', 'Data Qoutation Berhasil Di Tambahkan !!');
        } else {

            $sph = new Sph;
            $sph->user_id =  Auth::user()->id;
            $sph->customer_id = $request->customer;
            $sph->kegiatan = "Qoutation";
            // $sph->brand = $request->brand;
            // $sph->produk = json_encode(["produk" => $request->products]); // Keep for backward compatibility
            $sph->sumber_anggaran = $request->sumber_anggaran;
            $sph->nilai_pagu = intval(str_replace(["Rp.", ".00", ","], "", $request->nilai_pagu));
            $sph->metode_pembelian = $request->metode_pembelian;
            $sph->status = $request->status;
            $sph->time_line = $request->time_line;
            $sph->winrate = $request->winrate;
            $sph->note = $request->note;

            $sph->save();

            // Simpan produk ke tabel sph_products (multiple brand dan produk)
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

                        SphProduct::firstOrCreate(
                            [
                                'sph_id' => $sph->id,
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

            return redirect()->route('customer.sph', $request->customer)->with('success', 'Data Qoutation Berhasil Di Tambahkan !!');
        }
    }

    /**
     * Display the specified resource.
     *\
     * @param  \App\Models\Sph  $sph
     * @return \Illuminate\Http\Response
     */
    public function show(Sph $sph)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sph  $sph
     * @return \Illuminate\Http\Response
     */
    public function edit($customer_id, $id)
    {
        $title = 'Edit History Qoutation';
        $sph = Sph::where('customer_id', $customer_id)->get();
        $sph_edit = Sph::find($id);
        $brand = Principal::all();
        $status = Status::all();
        $sumber_anggaran = Sumber_Anggaran::all();
        $customer = Customer::all();
        $metode_pembelian = Metode_Pembelian::all();
        $time_line = Time_Line::all();

        return view('kegiatan.sph.index', compact('sph', 'title', 'customer', 'brand', 'status', 'sph_edit', 'sumber_anggaran', 'metode_pembelian', 'time_line'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sph  $sph
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        // Retrieve the sph record by its ID
        $sph = Sph::findOrFail($id);

        // Handle file upload
        if ($request->hasFile('pdf_file')) {
            $pdfName = time() . '.' . $request->pdf_file->extension();
            $uploadedPdf = $request->pdf_file->move(public_path('assets/pdf'), $pdfName);
            $pdfPath = $pdfName;

            // Delete the previous file if it exists
            if (file_exists(public_path('assets/pdf/' . $sph->pdf_file))) {
                unlink(public_path('assets/pdf/' . $sph->pdf_file));
            }

            // Update the pdf_file field
            $sph->pdf_file = $pdfPath;
        }

        // Update the other fields
        $sph->user_id = Auth::user()->id;
        $sph->customer_id = $request->customer;
        $sph->kegiatan = "Qoutation";
        $sph->brand = $request->brand;
        $sph->produk = json_encode(["produk" => $request->products]); // Keep for backward compatibility
        $sph->sumber_anggaran = $request->sumber_anggaran;
        $sph->nilai_pagu = intval(str_replace(["Rp.", ".00", ","], "", $request->nilai_pagu));
        $sph->metode_pembelian = $request->metode_pembelian;
        $sph->status = $request->status;
        $sph->time_line = $request->time_line;
        $sph->winrate = $request->winrate;
        $sph->note = $request->note;

        // Save the updated record
        $sph->save();

        // Update products in sph_products table
        // First, delete existing products for this SPH
        SphProduct::where('sph_id', $sph->id)->delete();

        // Then, add new products (multiple brand dan produk) dengan validasi brand-product seperti Visit
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

                    SphProduct::firstOrCreate(
                        [
                            'sph_id' => $sph->id,
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

        return redirect()->route('customer.sph', $request->customer)->with('success', 'Data Qoutation Berhasil Diubah !!');
    }

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'customer' => 'required',
    //         // 'sumber_anggaran' => 'required',
    //         // 'nilai_pagu' => 'required',
    //         // 'metode_pembelian' => 'required',
    //         // 'metode_pembayaran' => 'required',
    //         // 'time_line' => 'required',
    //         // 'tanggal_pengiriman' => 'required',
    //         // 'tanggal_instalasi' => 'required'
    //     ]);

    //     $sph = Sph::find($id);
    //     $sph->user_id =  Auth::user()->id;
    //     $sph->customer_id = $request->customer;
    //     $sph->sumber_anggaran = $request->sumber_anggaran;
    //     $sph->nilai_pagu = $request->nilai_pagu;
    //     $sph->metode_pembelian = $request->metode_pembelian;
    //     $sph->metode_pembayaran = $request->metode_pembayaran;
    //     $sph->time_line = $request->time_line;
    //     $sph->tanggal_pengiriman = $request->tanggal_pengiriman;
    //     $sph->tanggal_instalasi = $request->tanggal_instalasi;

    //     $sph->save();

    //     return redirect()->route('sph.index')->with('success', 'Data Qoutation Berhasil Di Update !!');
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sph  $sph
     * @return \Illuminate\Http\Response
     */
    public function destroy($customer_id, $id)
    {
        $sph = Sph::find($id);

        // Delete related products first
        SphProduct::where('sph_id', $id)->delete();

        // Delete PDF file if exists
        if ($sph->pdf_file && file_exists(public_path('assets/pdf/' . $sph->pdf_file))) {
            File::delete(public_path('assets/pdf/' . $sph->pdf_file));
        }

        $sph->delete();

        return redirect()->route('customer.sph', $customer_id)->with('delete', 'Data Qoutation Berhasi Di Hapus !!');
    }
}
