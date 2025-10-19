<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Preorder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class PreorderController extends Controller
{
    // Make function for store data
    public function store(Request $request)
    {

        $request->validate([
            // 'due_date' => 'required',
            // 'npwp' => 'required',
            // 'alamat_pengiriman' => 'required',

        ]);

        

        $preorder = new Preorder();
        $preorder->user_id = Auth::user()->id;
        $preorder->customer_id = $request->customer;
        $preorder->kegiatan = "Purchase Order";
        $preorder->nominal = intval(str_replace(["Rp.", ".00", ","], "", $request->nominal));
        $preorder->npwp = $request->npwp;
        $preorder->due_date = $request->due_date;
        $preorder->alamat = $request->alamat_pengiriman;
        
        if ($request->hasFile('file')) {
            $file = time() . '.' . $request->file->extension();
            $uploadedPdf = $request->file->move(public_path('assets/pdf'), $file);
            $pdfPath = $file;
            $preorder->file =  $pdfPath;

        }

        $preorder->save();

        return redirect()->route('customer.preorder', $request->customer)->with('success', 'Data Purchase Order Berhasil Di Tambah');
    }

    public function edit($customer_id, $id)
    {
        $title = "Daftar History Purchace Order";
        $customer = Customer::all();
        $preorder_edit = Preorder::find($id);
        $preorder = Preorder::where('customer_id', $customer_id)->get();
        $preorder_by_sales = Customer::where('user_id', Auth::user()->id)->get();

        return view('kegiatan.preorder.index', compact('title', 'preorder', 'preorder_edit', 'preorder_by_sales', 'customer'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            // 'due_date' => 'required',
            // 'npwp' => 'required',
            // 'alamat_pengiriman' => 'required',

        ]);


        $preorder = Preorder::find($id);

        if ($request->hasFile('file')) {
            $file = time() . '.' . $request->file->extension();
            $request->file->move(public_path('assets/pdf'), $file);
            $filePath = $file;
    
            if (file_exists(public_path('assets/pdf/' . $preorder->file))) {
                unlink(public_path('assets/pdf/' . $preorder->file));
            }
    
            $preorder->file = $filePath;
        }

        $preorder->user_id = Auth::user()->id;
        $preorder->customer_id = $request->customer;
        $preorder->kegiatan = "Purchase Order";
        $preorder->nominal = intval(str_replace(["Rp.", ".00", ","], "", $request->nominal));
        $preorder->npwp = $request->npwp;
        $preorder->due_date = $request->due_date;
        $preorder->alamat = $request->alamat_pengiriman;


        $preorder->save();

        return redirect()->route('customer.preorder', $request->customer)->with('success', 'Data Purchase Order Berhasil Di Update');
    }

    public function destroy($customer_id, $id)
    {
        $preorder = Preorder::find($id);
        $pdfFilePath = public_path('assets/pdf/' . $preorder->pdf_file);
        File::delete($pdfFilePath);
        $preorder->delete();
        return redirect()->route('customer.preorder', $customer_id)->with('delete', 'Data Purchase Order Berhasil Di Hapus');
    }
}
