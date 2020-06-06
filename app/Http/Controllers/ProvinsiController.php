<?php

namespace App\Http\Controllers;

use App\Provinsi;
use Illuminate\Http\Request;

class ProvinsiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $s = request()->s ?? "";
        $datas = Provinsi::where(function($w)use($s){
            $w->where('nama_provinsi','LIKE','%'.$s.'%');
        })->orderBy('created_at','DESC')->paginate(10);
        return view('admin.provinsi.index',compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.provinsi.create');
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
            'nama_provinsi'=>'required|string|max:100'
        ]);

        $provinsi = Provinsi::create([
            'nama_provinsi'=>$request->nama_provinsi
        ]);

        return redirect(route('admin.provinsi.index'))->with(['success'=>'Menambah Data Provinsi Baru Dengan Nama : '.$provinsi->nama_provinsi]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Provinsi  $provinsi
     * @return \Illuminate\Http\Response
     */
    public function show(Provinsi $provinsi)
    {
        return view('admin.provinsi.show',compact('provinsi'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Provinsi  $provinsi
     * @return \Illuminate\Http\Response
     */
    public function edit(Provinsi $provinsi)
    {
        return view('admin.provinsi.edit',compact('provinsi'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Provinsi  $provinsi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Provinsi $provinsi)
    {
        $request->validate([
            'nama_provinsi'=>'required|string|max:100'
        ]);

        $provinsi->update([
            'nama_provinsi'=>$request->nama_provinsi
        ]);

        return redirect(route('admin.provinsi.index'))->with(['success'=>'Mengupdate Data Provinsi Dengan Nama : '.$provinsi->nama_provinsi]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provinsi  $provinsi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Provinsi $provinsi)
    {   

        if($provinsi->wargas()->count() > 0){
            return redirect(route('admin.provinsi.index'))->with(['warning'=>'Data Provinsi Dengan Nama : '.$provinsi->nama_provinsi.' Masih Memiliki Data Warga']); 
        }

        if($provinsi->kotas()->count() > 0){
            return redirect(route('admin.provinsi.index'))->with(['warning'=>'Data Provinsi Dengan Nama : '.$provinsi->nama_provinsi.' Masih Memiliki Data Kota']); 
        }

        $provinsi->delete();
        return redirect(route('admin.provinsi.index'))->with(['success'=>'Menghapus Data Provinsi Dengan Nama : '.$provinsi->nama_provinsi]);

        
    }
}
