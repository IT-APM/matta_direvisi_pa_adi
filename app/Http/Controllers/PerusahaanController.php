<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perusahaan;
use Illuminate\Support\Facades\Log;

class PerusahaanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $no = 0;
        $perusahaan = Perusahaan::orderBy( 'nama', 'asc' )->get();
        return view( 'master.perusahaan.index', compact( 'perusahaan', 'no' ) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $status = 'create';
        return view( 'master.perusahaan.upsert', compact( 'status' ) );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $perusahaan = New Perusahaan;
        $perusahaan->nama = $request->nama;
        $perusahaan->nama_singkat = '';
        if($request->is_active == 'on'){
            $perusahaan->is_active = 1;
        }else{
            $perusahaan->is_active = 0;
        }
        $perusahaan->save();
        return redirect( 'perusahaan' )->with( 'msg', 'Data is saved successfully.' );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $status = 'edit';
        $perusahaan = Perusahaan::find( $id );
        return view( 'master.perusahaan.upsert', compact( 'perusahaan', 'status' ) );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $perusahaan = Perusahaan::find( $id );
        $perusahaan->nama = $request->nama;
        if($request->is_active == 'on'){
            $perusahaan->is_active = 1;
        }else{
            $perusahaan->is_active = 0;
        }
        $perusahaan->update();
        return redirect( 'perusahaan' )->with( 'msg', 'Data is saved successfully.' );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $perusahaan = Perusahaan::find( $id );
        try {
            $perusahaan->delete();
            return redirect( 'perusahaan' )->with( 'msg', 'Data is deleted successfully.' );
         } catch (\Exception $e) {
            //   var_dump( $e->errorInfo );
            Log::error($e->errorInfo);
            // Log::error($perusahaan);
            return redirect()->route('error_db');
         }
    }
}
