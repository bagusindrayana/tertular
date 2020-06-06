<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $s = request()->s ?? "";
        $datas = User::where(function($w)use($s){
            $w->where('nama','LIKE','%'.$s.'%');
        })->orderBy('created_at','DESC')->paginate(10);
        return view('admin.user.index',compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.user.create');
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
            'nama'=>'required|string|max:100'
        ]);

        $user = User::create([
            'nama'=>$request->nama,
            'username'=>$request->username,
            'level'=>$request->level,
            'password'=>Hash::make($request->password)
        ]);

        return redirect(route('admin.user.index'))->with(['success'=>'Menambah Data User Baru Dengan Nama : '.$user->nama]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('admin.user.show',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('admin.user.edit',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'nama'=>'required|string|max:100'
        ]);

        $user->update([
            'nama'=>$request->nama
        ]);

        return redirect(route('admin.user.index'))->with(['success'=>'Mengupdate Data User Dengan Nama : '.$user->nama]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {   

        if($user->wargas()->count() > 0){
            return redirect(route('admin.user.index'))->with(['warning'=>'Data User Dengan Nama : '.$user->nama.' Masih Memiliki Data Warga']); 
        }

        if($user->kotas()->count() > 0){
            return redirect(route('admin.user.index'))->with(['warning'=>'Data User Dengan Nama : '.$user->nama.' Masih Memiliki Data Kota']); 
        }

        $user->delete();
        return redirect(route('admin.user.index'))->with(['success'=>'Menghapus Data User Dengan Nama : '.$user->nama]);

        
    }
}
