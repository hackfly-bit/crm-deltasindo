<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Kegiatan_other;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KegiatanOtherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kegiatan_other = Kegiatan_other::all();
        $kegiatan_other_sales = Kegiatan_other::where('user_id', Auth::user()->id)->get();

        return view('kegiatan.other.index', compact('kegiatan_other','kegiatan_other_sales'));
    }

    public function history($id){
        $other = Kegiatan_other::find($id);
        $history = $other->revisionHistory;

        return view('kegiatan.other.history', compact('history','other'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customer = Customer::all();
        $customer_by_sales = Customer::where('user_id', Auth::user()->id)->get();

        return view('kegiatan.other.create',compact('customer','customer_by_sales'));
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
            'customer' => 'required',
            'nama_kegiatan' => 'required',
            'tanggal' => 'required',
            'deskripsi' => 'required'
        ]);
        

        $other = new Kegiatan_other;
        $other->user_id  = Auth::user()->id;
        $other->customer_id = $request->customer;
        $other->nama_kegiatan = $request->nama_kegiatan;
        $other->tanggal_other = $request->tanggal;
        $other->deskripsi = $request->deskripsi;

        $kegiatan_other = Kegiatan_other::where('customer_id', $request->customer)->get()->count();

        if($kegiatan_other > 0){
            
            return redirect()->route('other.create')->with('success', 'Data Customer Sudah Ada');
        }

        $other->save();

        return redirect()->route('other.index')->with('success','Data Other Berhasil Di Tambah !!');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Kegiatan_other  $kegiatan_other
     * @return \Illuminate\Http\Response
     */
    public function show(Kegiatan_other $kegiatan_other)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Kegiatan_other  $kegiatan_other
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $kegiatan_other = Kegiatan_other::find($id);
        $customer = Customer::all();
        $customer_by_sales = Customer::where('user_id', Auth::user()->id)->get();
        return view('kegiatan.other.edit',compact('kegiatan_other','customer','customer_by_sales'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Kegiatan_other  $kegiatan_other
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'customer' => 'required',
            'nama_kegiatan' => 'required',
            'tanggal' => 'required',
            'deskripsi' => 'required'
        ]);
        

        $other = Kegiatan_other::find($id);
        $other->user_id  = Auth::user()->id;
        $other->customer_id = $request->customer;
        $other->nama_kegiatan = $request->nama_kegiatan;
        $other->tanggal_other = $request->tanggal;
        $other->deskripsi = $request->deskripsi;

        $other->save();


        return redirect()->route('other.index')->with('success','Data Other Berhasil Di Update !!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Kegiatan_other  $kegiatan_other
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $kegiatan_other = Kegiatan_other::find($id);
        $kegiatan_other->delete();
        return redirect()->route('other.index')->with('success','Data Berhasil Di Hapus !!');
    }
}
