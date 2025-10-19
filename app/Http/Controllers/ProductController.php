<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category\Principal;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // Create function create principal 
    public function createPrincipal(){
        return view('setting.setting-select.create-principal');
    }

    public function create()
    {
        $brand = Principal::all();

        return view('setting.setting-select.create-produk',compact('brand'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    // Make function for store principal 
    public function storePrincipal(Request $request){
        $request->validate([
            'nama_brand' => 'required',
        ]);

        $principal = new Principal();
        $principal->name = $request->nama_brand;
        $principal->save();

        return redirect()->route('setting.select')->with('success', 'Data Brand Berhasil Di Tambahkan !!');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nama_brand' => 'required',
            'nama_produk' => 'required',
        ]);

        $produk = new Product();
        $produk->principal_id = $request->nama_brand;
        $produk->nama_produk = $request->nama_produk;
        $produk->deskripsi = $request->deskripsi;

        $produk->save();

        return redirect()->route('setting.select')->with('success', 'Data Produk Berhasil Di Tambahkan !!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */

    // Make function for edit principal
    public function editPrincipal($id){
        $principal = Principal::find($id);

        return view('setting.setting-select.create-principal',compact('principal'));
    }
    
    
    public function edit($id)
    {
        $produk = Product::find($id);
        $brand = Principal::all();

        return view('setting.setting-select.create-produk',compact('brand','produk'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */

    // update for principal
    public function updatePrincipal(Request $request,$id){
        $request->validate([
            'nama_brand' => 'required',
        ]);

        $principal = Principal::find($id); 
        $principal->name = $request->nama_brand;

        $principal->save();

        return redirect()->route('setting.select')->with('success', 'Data Produk Berhasil Di Tambahkan !!');
    }



    public function update(Request $request,$id)
    {
        $request->validate([
            'nama_brand' => 'required',
            'nama_produk' => 'required',
        ]);

        $produk = Product::find($id);
        $produk->principal_id = $request->nama_brand;
        $produk->nama_produk = $request->nama_produk;
        $produk->deskripsi = $request->deskripsi;

        $produk->save();

        return redirect()->route('setting.select')->with('success', 'Data Produk Berhasil Di Update !!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */

    // make function for destroy princcipal
    public function destroyPrincipal($id)
    {
        $principal = Principal::find($id);
        $principal->delete();

        return redirect()->route('setting.select')->with('success', 'Data Brand Berhasil Di Hapus !!');
    }

     
    public function destroy($id)
    {
        $produk = Product::find($id);
        $produk->delete();

        // return view('setting.setting-select.create-produk');

        return redirect()->route('setting.select')->with('success', 'Data Produk Berhasil Di Hapus !!');
    }
}
