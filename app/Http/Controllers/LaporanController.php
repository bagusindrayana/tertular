<?php

namespace App\Http\Controllers;

use App\Klaster;
use App\Kota;
use App\Pasien;
use App\Provinsi;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index($menu)
    {
       switch ($menu) {
            case 'klaster':
                $datas = Klaster::all();
                return view('admin.laporan.klaster.index',compact('datas'));
                break;
            case 'provinsi':
                $datas = Provinsi::all();
                return view('admin.laporan.provinsi.index',compact('datas'));
                break;
            case 'pasien':
                $datas = Pasien::paginate(10);
                $provinsis = Provinsi::pluck('nama_provinsi','id');
                return view('admin.laporan.pasien.index',compact('datas','provinsis'));
                break;
            default:
                return "menu laporan tidak di temukan";
                break;
       }
    }

    public function export($menu)
    {
       switch ($menu) {
            case 'klaster':
                $datas = Klaster::all();
                return view('admin.laporan.klaster.excel',compact('datas'));
                break;
            case 'provinsi':
                $datas = Provinsi::all();
                return view('admin.laporan.provinsi.excel',compact('datas'));
                break;
            case 'pasien':
                $datas = Pasien::orderBy('no','ASC');
                if(isset(request()->provinsi_id)){
                    $datas = $datas->where('provinsi_id',request()->provinsi_id);
                }
                if(isset(request()->kota_id)){
                    $datas = $datas->where('kota_id',request()->kota_id);
                }
                $datas = $datas->get();
                return view('admin.laporan.pasien.excel',compact('datas'));
                break;
            default:
                return "menu laporan tidak di temukan";
                break;
       }
    }
}
