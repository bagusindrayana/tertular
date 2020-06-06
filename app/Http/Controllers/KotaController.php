<?php

namespace App\Http\Controllers;

use App\Kota;
use App\Provinsi;
use Illuminate\Http\Request;

class KotaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $s = request()->s ?? "";
        $datas = Kota::where(function($w)use($s){
            $w->where('nama_kota','LIKE','%'.$s.'%');
        })->orderBy('created_at','DESC')->paginate(10);
        return view('admin.kota.index',compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $provinsis = Provinsi::pluck('nama_provinsi','id');
        return view('admin.kota.create',compact('provinsis'));
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
            'nama_kota'=>'required|string|max:100',
            'provinsi_id'=>'required'
        ]);

        $kota = Kota::create([
            'nama_kota'=>$request->nama_kota,
            'provinsi_id'=>$request->provinsi_id
        ]);

        return redirect(route('admin.kota.index'))->with(['success'=>'Menambah Data Kota Baru Dengan Nama : '.$kota->nama_kota]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Kota  $kota
     * @return \Illuminate\Http\Response
     */
    public function show(Kota $kota)
    {
        return view('admin.kota.show',compact('kota'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Kota  $kota
     * @return \Illuminate\Http\Response
     */
    public function edit(Kota $kota)
    {   
        $provinsis = Provinsi::pluck('nama_provinsi','id');
       
        return view('admin.kota.edit',compact('kota','provinsis'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Kota  $kota
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kota $kota)
    {
        $request->validate([
            'nama_kota'=>'required|string|max:100',
            'provinsi_id'=>'required'
        ]);

        $kota->update([
            'nama_kota'=>$request->nama_kota,
            'provinsi_id'=>$request->provinsi_id
        ]);

        return redirect(route('admin.kota.index'))->with(['success'=>'Mengupdate Data Kota Dengan Nama : '.$kota->nama_kota]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Kota  $kota
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kota $kota)
    {   

        if($kota->wargas()->count() > 0){
            return redirect(route('admin.kota.index'))->with(['warning'=>'Data Kota Dengan Nama : '.$kota->nama_kota.' Masih Memiliki Data Warga']); 
        }

        if($kota->kecamatans()->count() > 0){
            return redirect(route('admin.kota.index'))->with(['warning'=>'Data Kota Dengan Nama : '.$kota->nama_kota.' Masih Memiliki Data Kecamatan']); 
        }

        $kota->delete();
        return redirect(route('admin.kota.index'))->with(['success'=>'Menghapus Data Kota Dengan Nama : '.$kota->nama_kota]);

        
    }

    public function searchSelect2(Request $request,$provinsi_id)
    {   
        if ($request->ajax()) {
            $page = $request->page;
            $resultCount = 5;
    
            $offset = ($page - 1) * $resultCount;
    
            $locations = Kota::where('nama_kota', 'LIKE', '%' . $request->term. '%')
                                    ->where('provinsi_id',$provinsi_id)
                                    ->orderBy('nama_kota')
                                    ->skip($offset)
                                    ->take($resultCount)
                                    ->selectRaw('id, nama_kota as text')
                                    ->get();
    
            $count = Count(Kota::where('nama_kota', 'LIKE', '%' . $request->term. '%')
                                    ->where('provinsi_id',$provinsi_id)
                                    ->orderBy('nama_kota')
                                    ->selectRaw('id, nama_kota as text')
                                    ->get());
    
            $endCount = $offset + $resultCount;
            $morePages = $count > $endCount;
    
            $results = array(
                  "results" => $locations,
                  "pagination" => array(
                      "more" => $morePages
                  )
              );
    
            return response()->json($results);
        }
        return response()->json('oops');
    }
}
