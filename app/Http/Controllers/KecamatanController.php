<?php

namespace App\Http\Controllers;

use App\Kecamatan;
use App\Kota;
use Illuminate\Http\Request;

class KecamatanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $s = request()->s ?? "";
        $datas = Kecamatan::where(function($w)use($s){
            $w->where('nama_kecamatan','LIKE','%'.$s.'%');
        })->orderBy('created_at','DESC')->paginate(10);
        return view('admin.kecamatan.index',compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        return view('admin.kecamatan.create');
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
            'nama_kecamatan'=>'required|string|max:100',
            'kota_id'=>'required'
        ]);

        $kecamatan = Kecamatan::create([
            'nama_kecamatan'=>$request->nama_kecamatan,
            'kota_id'=>$request->kota_id
        ]);

        return redirect(route('admin.kecamatan.index'))->with(['success'=>'Menambah Data Kecamatan Baru Dengan Nama : '.$kecamatan->nama_kecamatan]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Kecamatan  $kecamatan
     * @return \Illuminate\Http\Response
     */
    public function show(Kecamatan $kecamatan)
    {
        return view('admin.kecamatan.show',compact('kecamatan'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Kecamatan  $kecamatan
     * @return \Illuminate\Http\Response
     */
    public function edit(Kecamatan $kecamatan)
    {
        return view('admin.kecamatan.edit',compact('kecamatan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Kecamatan  $kecamatan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kecamatan $kecamatan)
    {
        $request->validate([
            'nama_kecamatan'=>'required|string|max:100',
            'kota_id'=>'required'
        ]);

        $kecamatan->update([
            'nama_kecamatan'=>$request->nama_kecamatan,
            'kota_id'=>$request->kota_id
        ]);

        return redirect(route('admin.kecamatan.index'))->with(['success'=>'Mengupdate Data Kecamatan Dengan Nama : '.$kecamatan->nama_kecamatan]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Kecamatan  $kecamatan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kecamatan $kecamatan)
    {   

        if($kecamatan->wargas()->count() > 0){
            return redirect(route('admin.kecamatan.index'))->with(['warning'=>'Data Kecamatan Dengan Nama : '.$kecamatan->nama_kecamatan.' Masih Memiliki Data Warga']); 
        }

        if($kecamatan->kelurahans()->count() > 0){
            return redirect(route('admin.kecamatan.index'))->with(['warning'=>'Data Kecamatan Dengan Nama : '.$kecamatan->nama_kecamatan.' Masih Memiliki Data Kelurahan']); 
        }

        $kecamatan->delete();
        return redirect(route('admin.kecamatan.index'))->with(['success'=>'Menghapus Data Kecamatan Dengan Nama : '.$kecamatan->nama_kecamatan]);
    }

    public function searchSelect2(Request $request,$kota_id)
    {   
        if ($request->ajax()) {
            $page = $request->page;
            $resultCount = 5;
    
            $offset = ($page - 1) * $resultCount;
    
            $locations = Kecamatan::where('nama_kecamatan', 'LIKE', '%' . $request->term. '%')
                                    ->where('kota_id',$kota_id)
                                    ->orderBy('nama_kecamatan')
                                    ->skip($offset)
                                    ->take($resultCount)
                                    ->selectRaw('id, nama_kecamatan as text')
                                    ->get();
    
            $count = Count(Kecamatan::where('nama_kecamatan', 'LIKE', '%' . $request->term. '%')
                                    ->where('kota_id',$kota_id)
                                    ->orderBy('nama_kecamatan')
                                    ->selectRaw('id, nama_kecamatan as text')
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
