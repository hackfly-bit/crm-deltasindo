<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Call;
use App\Models\Category\Pertemuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CallController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            // 'tanggal' => 'required',
            // 'pertemuan' => 'required',
            // 'note' => 'required'
        ]);

        $call = new Call;
        $call->user_id = Auth::user()->id;
        $call->customer_id = $request->customer;
        $call->kegiatan = "Call";
        $call->tanggal = $request->tanggal;
        $call->pertemuan = $request->pertemuan;
        $call->note = $request->note;

        $call->save();

        return redirect()->route('customer.call', $request->customer)->with('success', 'Data Call Berhasil Di Tambah');
    }

    public function edit($customer_id, $id)
    {
        $title = "Edit History Call";
        $customer = Customer::all();
        $call_edit = Call::find($id);
        $call = Call::where('customer_id', $customer_id)->get();
        $call_by_sales = Customer::where('user_id', Auth::user()->id)->get();
        $pertemuan = Pertemuan::all();

        return view('kegiatan.call.index', compact('title', 'call_edit', 'call', 'call_by_sales', 'customer', 'pertemuan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            // 'tanggal' => 'required',
            // 'pertemuan' => 'required',
            // 'note' => 'required'
        ]);

        $call = Call::find($id);
        $call->user_id = Auth::user()->id;
        $call->customer_id = $request->customer;
        $call->kegiatan = "Call";
        $call->tanggal = $request->tanggal;
        $call->pertemuan = $request->pertemuan;
        $call->note = $request->note;

        // return $call;

        $call->save();

        return redirect()->route('customer.call', $request->customer)->with('success', 'Data Call Berhasil Di Update');
    }

    public function destroy($customer_id, $id)
    {
        $call = Call::find($id);
        $call->delete();

        return redirect()->route('customer.call', $customer_id)->with('delete', 'Data Call Berhasil Di Hapus');
    }
}
