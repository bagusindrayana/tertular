<?php

namespace App\Http\Controllers;

use App\Klaster;
use Illuminate\Http\Request;

class KlasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $s = request()->s ?? "";
        $datas = Klaster::where(function($w)use($s){
            $w->where('nama_klaster','LIKE','%'.$s.'%');
        })->orderBy('created_at','DESC')->paginate(10);
        return view('admin.klaster.index',compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.klaster.create');
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
            'nama_klaster'=>'required|string|max:100'
        ]);

        $klaster = Klaster::create([
            'nama_klaster'=>$request->nama_klaster
        ]);

        return redirect(route('admin.klaster.index'))->with(['success'=>'Menambah Data Klaster Baru Dengan Nama : '.$klaster->nama_klaster]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Klaster  $klaster
     * @return \Illuminate\Http\Response
     */
    public function show(Klaster $klaster)
    {
        return view('admin.klaster.show',compact('klaster'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Klaster  $klaster
     * @return \Illuminate\Http\Response
     */
    public function edit(Klaster $klaster)
    {
        return view('admin.klaster.edit',compact('klaster'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Klaster  $klaster
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Klaster $klaster)
    {
        $request->validate([
            'nama_klaster'=>'required|string|max:100'
        ]);

        $klaster->update([
            'nama_klaster'=>$request->nama_klaster
        ]);

        return redirect(route('admin.klaster.index'))->with(['success'=>'Mengupdate Data Klaster Dengan Nama : '.$klaster->nama_klaster]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Klaster  $klaster
     * @return \Illuminate\Http\Response
     */
    public function destroy(Klaster $klaster)
    {   

        if($klaster->wargas()->count() > 0){
            return redirect(route('admin.klaster.index'))->with(['warning'=>'Data Klaster Dengan Nama : '.$klaster->nama_klaster.' Masih Memiliki Data Warga']); 
        }
        $klaster->delete();
        return redirect(route('admin.klaster.index'))->with(['success'=>'Menghapus Data Klaster Dengan Nama : '.$klaster->nama_klaster]);

        
    }
}
