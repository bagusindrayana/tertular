<?php

namespace App\Http\Controllers;

use App\Kelurahan;
use Illuminate\Http\Request;

class KelurahanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $s = request()->s ?? "";
        $datas = Kelurahan::where(function($w)use($s){
            $w->where('nama_kelurahan','LIKE','%'.$s.'%');
        })->orderBy('created_at','DESC')->paginate(10);
        return view('admin.kelurahan.index',compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        return view('admin.kelurahan.create');
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
            'nama_kelurahan'=>'required|string|max:100',
            'kecamatan_id'=>'required'
        ]);

        $kelurahan = Kelurahan::create([
            'nama_kelurahan'=>$request->nama_kelurahan,
            'kecamatan_id'=>$request->kecamatan_id
        ]);

        return redirect(route('admin.kelurahan.index'))->with(['success'=>'Menambah Data Kelurahan Baru Dengan Nama : '.$kelurahan->nama_kelurahan]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Kelurahan  $kelurahan
     * @return \Illuminate\Http\Response
     */
    public function show(Kelurahan $kelurahan)
    {
        return view('admin.kelurahan.show',compact('kelurahan'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Kelurahan  $kelurahan
     * @return \Illuminate\Http\Response
     */
    public function edit(Kelurahan $kelurahan)
    {
        return view('admin.kelurahan.edit',compact('kelurahan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Kelurahan  $kelurahan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kelurahan $kelurahan)
    {
        $request->validate([
            'nama_kelurahan'=>'required|string|max:100',
            'kecamatan_id'=>'required'
        ]);

        $kelurahan->update([
            'nama_kelurahan'=>$request->nama_kelurahan,
            'kecamatan_id'=>$request->kecamatan_id
        ]);

        return redirect(route('admin.kelurahan.index'))->with(['success'=>'Mengupdate Data Kelurahan Dengan Nama : '.$kelurahan->nama_kelurahan]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Kelurahan  $kelurahan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kelurahan $kelurahan)
    {   

        if($kelurahan->wargas()->count() > 0){
            return redirect(route('admin.kelurahan.index'))->with(['warning'=>'Data Kelurahan Dengan Nama : '.$kelurahan->nama_kelurahan.' Masih Memiliki Data Warga']); 
        }

        $kelurahan->delete();
        return redirect(route('admin.kelurahan.index'))->with(['success'=>'Menghapus Data Kelurahan Dengan Nama : '.$kelurahan->nama_kelurahan]);
    }

    public function searchSelect2(Request $request,$kecamatan_id)
    {   
        if ($request->ajax()) {
            $page = $request->page;
            $resultCount = 5;
    
            $offset = ($page - 1) * $resultCount;
    
            $locations = Kelurahan::where('nama_kelurahan', 'LIKE', '%' . $request->term. '%')
                                    ->where('kecamatan_id',$kecamatan_id)
                                    ->orderBy('nama_kelurahan')
                                    ->skip($offset)
                                    ->take($resultCount)
                                    ->selectRaw('id, nama_kelurahan as text')
                                    ->get();
    
            $count = Count(Kelurahan::where('nama_kelurahan', 'LIKE', '%' . $request->term. '%')
                                    ->where('kecamatan_id',$kecamatan_id)
                                    ->orderBy('nama_kelurahan')
                                    ->selectRaw('id, nama_kelurahan as text')
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
