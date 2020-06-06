<?php

namespace App\Http\Controllers;

use App\Interaksi;
use App\Klaster;
use App\Provinsi;
use App\Pasien;
use Illuminate\Http\Request;

class PasienController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $s = request()->s ?? "";
        $datas = Pasien::where(function($w)use($s){
            $w->where('nama_lengkap','LIKE','%'.$s.'%')->orWhere("status",'LIKE','%'.$s.'%')->orWhere('alamat','LIKE','%'.$s.'%')->orWhereHas('provinsi',function($q)use($s){
                $q->where('nama_provinsi','LIKE','%'.$s.'%');
            })->orWhereHas('kota',function($q)use($s){
                $q->where('nama_kota','LIKE','%'.$s.'%');
            });
        })->orderBy('created_at','DESC')->paginate(10);
        return view('admin.pasien.index',compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $provinsis = Provinsi::pluck('nama_provinsi','id');
        $klasters = Klaster::pluck('nama_klaster','id');

        
      
        return view('admin.pasien.create',compact('provinsis','klasters'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        // $val = 1;
        // $cek = str_pad($val,4,"0",STR_PAD_LEFT); 
        // dd(++$cek);
        //dd($request->all());
        $request->validate([
            'nama_lengkap'=>'required|string|max:100',
            'jenis_kelamin'=>'required',
            'alamat'=>'requried'
        ]);
        
        $no = '00000001';
        $cek = Pasien::orderBy('no','DESC')->first();
        if($cek){
            $val = $cek->no;
            $no = str_pad(++$val,8,"0",STR_PAD_LEFT);
        }

        $pasien = Pasien::create([
            'no'=>$no,
            'nama_lengkap'=>$request->nama_lengkap,
            'jenis_kelamin'=>$request->jenis_kelamin,
            'alamat'=>$request->alamat,
            'tanggal_lahir'=>$request->tanggal_lahir,
            'provinsi_id'=>$request->provinsi_id,
            'kota_id'=>$request->kota_id,
            'kecamatan_id'=>$request->kecamatan_id,
            'kelurahan_id'=>$request->kelurahan_id,
            'lokasi'=>$request->lokasi,
            'kordinat_lokasi'=>$request->kordinat_lokasi,
            'klaster_id'=>$request->klaster_id,
            'status'=>$request->status
        ]);

        for ($i=0; $i < count($request->interaksi_keterangan); $i++) { 
            $keterangan = $request->interaksi_keterangan[$i];
            $tanggal = $request->interaksi_tanggal[$i];
            $lokasi = $request->interaksi_lokasi[$i];
            $kordinat = $request->interaksi_kordinat_lokasi[$i];
            $provinsi = $request->interaksi_provinsi_id[$i];
            $kota = $request->interaksi_kota_id[$i];
            $kecamatan = $request->interaksi_kecamatan_id[$i];
            $kelurahan = $request->interaksi_kelurahan_id[$i];

          
            Interaksi::create([
                'pasien_id'=>$pasien->id,
                'keterangan'=>$keterangan,
                'tanggal_interaksi'=>$tanggal,
                'lokasi'=>$lokasi,
                'kordinat_lokasi'=>$kordinat,
                'provinsi_id'=>$provinsi,
                'kota_id'=>$kota,
                'kecamatan_id'=>$kecamatan,
                'kelurahan_id'=>$kelurahan,
            ]);

        }

        return redirect(route('admin.pasien.index'))->with(['success'=>'Menambah Data Pasien Baru Dengan Nama : '.$pasien->nama_lengkap]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Pasien  $pasien
     * @return \Illuminate\Http\Response
     */
    public function show(Pasien $pasien)
    {
        return view('admin.pasien.show',compact('pasien'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Pasien  $pasien
     * @return \Illuminate\Http\Response
     */
    public function edit(Pasien $pasien)
    {   
      
        $provinsis = Provinsi::pluck('nama_provinsi','id');
        $klasters = Klaster::pluck('nama_klaster','id');
      
        return view('admin.pasien.edit',compact('provinsis','klasters','pasien'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Pasien  $pasien
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pasien $pasien)
    {
        $request->validate([
            'nama_lengkap'=>'required|string|max:100',
            'jenis_kelamin'=>'required',
            'alamat'=>'requried'
        ]);

        $pasien->update([
            'nama_lengkap'=>$request->nama_lengkap,
            'jenis_kelamin'=>$request->jenis_kelamin,
            'alamat'=>$request->alamat,
            'tanggal_lahir'=>$request->tanggal_lahir,
            'provinsi_id'=>$request->provinsi_id,
            'kota_id'=>$request->kota_id,
            'kecamatan_id'=>$request->kecamatan_id,
            'kelurahan_id'=>$request->kelurahan_id,
            'lokasi'=>$request->lokasi,
            'kordinat_lokasi'=>$request->kordinat_lokasi,
            'klaster_id'=>$request->klaster_id,
            'status'=>$request->status
        ]);
        
        Interaksi::where('pasien_id',$pasien->id)->delete();
        for ($i=0; $i < count($request->interaksi_keterangan); $i++) { 
            $keterangan = $request->interaksi_keterangan[$i];
            $lokasi = $request->interaksi_lokasi[$i];
            $kordinat = $request->interaksi_kordinat_lokasi[$i];
            $provinsi = $request->interaksi_provinsi_id[$i];
            $kota = $request->interaksi_kota_id[$i];
            $kecamatan = $request->interaksi_kecamatan_id[$i];
            $kelurahan = $request->interaksi_kelurahan_id[$i];

          
            Interaksi::create([
                'pasien_id'=>$pasien->id,
                'keterangan'=>$keterangan,
                'lokasi'=>$lokasi,
                'kordinat_lokasi'=>$kordinat,
                'provinsi_id'=>$provinsi,
                'kota_id'=>$kota,
                'kecamatan_id'=>$kecamatan,
                'kelurahan_id'=>$kelurahan,
            ]);

        }

        return redirect(route('admin.pasien.index'))->with(['success'=>'Mengupdate Data Pasien Dengan Nama : '.$pasien->nama_lengkap]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Pasien  $pasien
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pasien $pasien)
    {   

        if($pasien->pasiens()->count() > 0){
            return redirect(route('admin.pasien.index'))->with(['warning'=>'Data Pasien Dengan Nama : '.$pasien->nama_lengkap.' Masih Memiliki Data Pasien']); 
        }

        if($pasien->kotas()->count() > 0){
            return redirect(route('admin.pasien.index'))->with(['warning'=>'Data Pasien Dengan Nama : '.$pasien->nama_lengkap.' Masih Memiliki Data Kota']); 
        }

        $pasien->delete();
        return redirect(route('admin.pasien.index'))->with(['success'=>'Menghapus Data Pasien Dengan Nama : '.$pasien->nama_lengkap]);

        
    }


    public function searchSelect2(Request $request)
    {   
        if ($request->ajax()) {
            $page = $request->page;
            $resultCount = 5;
    
            $offset = ($page - 1) * $resultCount;
    
            $locations = Pasien::where('nama_lengkap', 'LIKE', '%' . $request->term. '%')
                                    ->orderBy('nama_lengkap')
                                    ->skip($offset)
                                    ->take($resultCount)
                                    ->selectRaw('id, nama_lengkap as text')
                                    ->get();
    
            $count = Count(Pasien::where('nama_lengkap', 'LIKE', '%' . $request->term. '%')
                                    ->orderBy('nama_lengkap')
                                    ->selectRaw('id, nama_lengkap as text')
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
