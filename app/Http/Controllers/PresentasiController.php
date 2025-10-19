<?php

namespace App\Http\Controllers;

use App\Models\Category\Status;
use App\Models\Customer;
use App\Models\Presentasi;
use Illuminate\Http\Request;
use App\Models\Category\Pertemuan;
use Illuminate\Support\Facades\Auth;

class PresentasiController extends Controller
{
    //create store function 
    public function store(Request $request)
    {

        $request->validate([
            // 'tanggal' => 'required',
            // 'pertemuan' => 'required',
            // 'note' => 'required'
        ]);

        $presentasi = new Presentasi();
        $presentasi->user_id = Auth::user()->id;
        $presentasi->customer_id = $request->customer;
        $presentasi->kegiatan = "Presentasi";
        $presentasi->tanggal = $request->tanggal;
        $presentasi->pertemuan = $request->pertemuan;
        $presentasi->note = $request->note;

        $presentasi->save();

        return redirect()->route('customer.presentasi', $request->customer)->with('success', 'Data Presentasi Berhasil Di Tambah');
    }

    public function edit($customer_id, $id)
    {
        $title = "Daftar History Presentasi";
        $customer = Customer::all();
        $presentasi_edit = Presentasi::find($id);
        $presentasi = Presentasi::where('customer_id', $customer_id)->get();
        $presentasi_by_sales = Customer::where('user_id', Auth::user()->id)->get();
        $pertemuan = Pertemuan::all();
        $status = Status::all();

        return view('kegiatan.presentasi.index', compact('title', 'presentasi_edit', 'presentasi', 'presentasi_by_sales', 'customer', 'pertemuan', 'status'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            // 'tanggal' => 'required',
            // 'pertemuan' => 'required',
            // 'note' => 'required'
        ]);

        $presentasi = Presentasi::find($id);
        $presentasi->user_id = Auth::user()->id;
        $presentasi->customer_id = $request->customer;
        $presentasi->kegiatan = "Presentasi";
        $presentasi->tanggal = $request->tanggal;
        $presentasi->pertemuan = $request->pertemuan;
        $presentasi->note = $request->note;

        $presentasi->save();

        return redirect()->route('customer.presentasi', $request->customer)->with('success', 'Data Presentasi Berhasil Di Update');
    }

    // create destroy Function for presentasi
    public function destroy($customer_id, $id)
    {
        $presentasi = Presentasi::find($id);
        $presentasi->delete();
        return redirect()->route('customer.presentasi', $customer_id)->with('delete', 'Data Presentasi Berhasil Di Hapus');
    }
}
