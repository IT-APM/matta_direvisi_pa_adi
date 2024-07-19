<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReimbursePlafon;
use App\Models\TrxReimburseMaster;
use App\Models\Karyawan;

class ReimbursePlafonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $no = 0;
        $plafon = ReimbursePlafon::orderBy( 'periode', 'desc' )->get();
        // filter parameter
        $arr_tahun = array();
        $_arr_tahun = ReimbursePlafon::select('periode')->distinct()->get();
        foreach ($_arr_tahun as $value){
            array_push($arr_tahun, $value->periode);
        }
        // return response()->json( [ 'arr_tahun' => $arr_tahun ], 200 );
        $tahun = $arr_tahun;
        // start filter
        if($request->filter == true){
            $_tahun = $request->input('tahun', []);
            if(count($_tahun) > 0){
                $arr_tahun = $_tahun;
            }
            else{
                $arr_tahun = array();
            }
            $plafon = ReimbursePlafon::whereIn('periode', $arr_tahun)->orderBy( 'periode', 'desc' )->get();
        }else{
            $arr_tahun = array();
        }
        // end filter
        return view( 'master.reimburse.index', compact( 'plafon', 'no', 'tahun', 'arr_tahun' ) );
    }

    // ambil sisa plafon kesehatan
    public static function getSisaPlafon($nik, $periode)
    {
        $where = ['nik_karyawan' => $nik, 'periode' => $periode];
        // SELECT SUM(`klaim_kesehatan`) FROM `trx_tbl_reimburse_master` WHERE `status_pengajuan`!=3 AND `nik_karyawan`='' AND `periode`=
        $trx = TrxReimburseMaster::where($where)->whereNotIn('status_pengajuan', [3])->sum('klaim_kesehatan');
        return $trx;
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
        $where = ['is_aktif' => 1];
        $karyawan = Karyawan::where($where)->orderBy('nama', 'asc')->get();
        return view( 'master.reimburse.upsert', compact( 'karyawan', 'status' ) );
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
        // return response()->json( [ 'data' => $request->all() ], 200 );
        $nik_karyawan = $request->nik_karyawan;
        $periode = $request->periode;
        $kesehatan = $request->kesehatan;
        $lensa = $request->lensa;
        $frame = $request->frame;
        $kehamilan = $request->kehamilan;
        $persalinan_normal = $request->persalinan_normal;
        $persalinan_caesar = $request->persalinan_caesar;
        $where = ['nik_karyawan'=>$nik_karyawan, 'periode'=>$periode];
        $check = ReimbursePlafon::where($where)->exists();
        if($check){
            $error = 'Data is already exist!';
            return redirect()->back()->with('error', $error);
        }else{
            $trx = new ReimbursePlafon;
            $trx->nik_karyawan = $nik_karyawan;
            $trx->periode = $periode;
            $trx->kesehatan = $kesehatan;
            $trx->lensa = $lensa;
            $trx->frame = $frame;
            $trx->kehamilan = $kehamilan;
            $trx->persalinan_normal = $persalinan_normal;
            $trx->persalinan_caesar = $persalinan_caesar;
            $trx->save();
            return redirect('plafon')->with('msg', 'Data is saved successfully.');
        }
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
        $plafon = ReimbursePlafon::find( $id );
        $where = ['is_aktif' => 1];
        $karyawan = Karyawan::where($where)->orderBy('nama', 'asc')->get();
        return view( 'master.reimburse.upsert', compact( 'plafon', 'karyawan','status' ) );
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
        $trx = ReimbursePlafon::find( $id );
        $trx->kesehatan = $request->kesehatan;
        $trx->lensa = $request->lensa;
        $trx->frame = $request->frame;
        $trx->kehamilan = $request->kehamilan;
        $trx->persalinan_normal = $request->persalinan_normal;
        $trx->persalinan_caesar = $request->persalinan_caesar;
        $trx->update();
        return redirect('plafon')->with('msg', 'Data is saved successfully.');
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
        ReimbursePlafon::find( $id )->delete();
        return redirect( 'plafon' )->with( 'msg', 'Data is deleted successfully.' );
    }
}
