<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use DB;
use App\Models\Perusahaan;
use App\Models\Divisi;
use App\Models\Departemen;
use App\Models\SubDepartemen;
use App\Models\ToDoList;
use App\Models\TrxPayrollLog;
use App\Models\KaryawanStatus;

class DashboardController extends Controller
{
    public function biaya(Request $request)
    {
        // dd($request->all());
        $tahun = TrxPayrollLog::distinct()->orderBy('tahun', 'asc')->get(['tahun'])->toArray();
        $bulan = [['bulan'=>'Januari','angka'=>1],['bulan'=>'Februari','angka'=>2],['bulan'=>'Maret','angka'=>3],['bulan'=>'April','angka'=>4],['bulan'=>'Mei','angka'=>5],['bulan'=>'Juni','angka'=>6],['bulan'=>'Juli','angka'=>7],['bulan'=>'Agustus','angka'=>8],['bulan'=>'September','angka'=>9],['bulan'=>'Oktober','angka'=>10],['bulan'=>'November','angka'=>11],['bulan'=>'Desember','angka'=>12]];
        // dd($tahun);
        $status_karyawan = KaryawanStatus::orderBy('status_karyawan', 'asc')->get();
        $perusahaan = Perusahaan::whereNotIn('id', [0,3])->orderBy('nama', 'asc')->get();
        $divisi = [];
        $departemen = [];
        $sub_departemen = [];
        //
        $arr_perusahaan = [];
        $arr_divisi = [];
        $arr_departemen = [];
        $arr_sub_departemen = [];
        $arr_tahun = [];
        $arr_bulan = [];
        $arr_status_karyawan = [];
        // 
        $payroll = [];
        // dd($payroll);
        // start filter
        if ($request->filter == true) {
            $_tahun = $request->input('tahun', []);
            $_bulan = $request->input('bulan', []);
            $_status_karyawan = $request->input('status_karyawan', []);
            $_perusahaan = $request->input('kode_perusahaan', []);
            $_divisi = $request->input('kode_divisi', []);
            $_departemen = $request->input('kode_departemen', []);
            $_sub_departemen = $request->input('kode_sub_departemen', []);
            if (count($_perusahaan) > 0) {
                $arr_perusahaan = $_perusahaan;
            }
            if (count($_divisi) > 0) {
                $arr_divisi = $_divisi;
            }
            if (count($_departemen) > 0) {
                $arr_departemen = $_departemen;
            }
            if (count($_sub_departemen) > 0) {
                $arr_sub_departemen = $_sub_departemen;
            }
            if (count($_tahun) > 0) {
                $arr_tahun = $_tahun;
            }
            if (count($_bulan) > 0) {
                $arr_bulan = $_bulan;
            }
            if (count($_status_karyawan) > 0) {
                $arr_status_karyawan = $_status_karyawan;
            }
            $divisi = Divisi::whereIn('kode_perusahaan', $arr_perusahaan)->get();
            $departemen = Departemen::whereIn('kode_divisi', $arr_divisi)->get();
            $sub_departemen = SubDepartemen::whereIn('kode_departemen', $arr_departemen)->get();
            // 
            $payroll = TrxPayrollLog::whereIn('tahun', $arr_tahun)
            ->whereIn('bulan', $arr_bulan)
            ->whereIn('status_karyawan', KaryawanStatus::whereIn('id', $arr_status_karyawan)->get('status_karyawan')->toArray())
            ->whereIn('perusahaan', Perusahaan::whereIn('id', $arr_perusahaan)->get('nama')->toArray())
            ->whereIn('divisi', Divisi::whereIn('id', $arr_divisi)->get('nama')->toArray())
            ->whereIn('departemen', Departemen::whereIn('id', $arr_departemen)->get('nama')->toArray())
            ->whereIn('subdepartemen', SubDepartemen::whereIn('id', $arr_sub_departemen)->get('nama')->toArray())
            ->get();
            // dd($payroll);
        }
        return view( 'dashboard.biaya', compact('payroll', 'perusahaan', 'divisi', 'departemen', 'sub_departemen', 'tahun', 'bulan', 'status_karyawan', 'arr_perusahaan', 'arr_divisi', 'arr_departemen', 'arr_sub_departemen', 'arr_tahun', 'arr_bulan', 'arr_status_karyawan'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perusahaan = DB::select("SELECT b.id AS id,b.nama AS nama FROM mtr_tbl_karyawan AS a LEFT JOIN mtr_tbl_perusahaan AS b ON b.id=a.kode_perusahaan GROUP BY b.id ORDER BY b.nama");;
        //
        // $data_status = DB::select("SELECT b.status_karyawan AS _status,COUNT(a.kode_status) AS jumlah,SUM(COUNT(a.kode_status)) OVER() AS total FROM mtr_tbl_karyawan AS a LEFT JOIN mtr_tbl_status_karyawan AS b ON b.id=a.kode_status WHERE a.is_aktif=1 AND a.nik NOT IN ('IT.BM','Hco.all') GROUP BY a.kode_status");
        // $status = array();
        // $jumlah_status = array();
        // foreach($data_status as $_data){
        //     array_push($status, $_data->_status);
        //     array_push($jumlah_status, round($_data->jumlah / $_data->total * 100, 2));
        // }
        // //
        // $data_perusahaan = DB::select("SELECT b.nama AS perusahaan,COUNT(a.kode_perusahaan) AS jumlah,SUM(COUNT(a.kode_perusahaan)) OVER() AS total FROM mtr_tbl_karyawan AS a LEFT JOIN mtr_tbl_perusahaan AS b ON b.id=a.kode_perusahaan WHERE a.is_aktif=1 AND a.nik NOT IN ('IT.BM','Hco.all') GROUP BY a.kode_perusahaan");
        // $perusahaan = array();
        // $jumlah_perusahaan = array();
        // foreach($data_perusahaan as $_data){
        //     array_push($perusahaan, $_data->perusahaan);
        //     array_push($jumlah_perusahaan, round($_data->jumlah / $_data->total * 100, 2));
        // }
        //
        $data_kelamin = DB::select("SELECT b.kelamin AS kelamin,COUNT(a.kode_kelamin) AS jumlah,SUM(COUNT(a.kode_kelamin)) OVER() AS total FROM mtr_tbl_karyawan AS a LEFT JOIN mtr_tbl_kelamin AS b ON b.id=a.kode_kelamin WHERE a.is_aktif=1 AND a.nik NOT IN ('IT.BM','Hco.all') GROUP BY a.kode_kelamin");
        // dd($data_kelamin);
        $kelamin = array();
        $jumlah_kelamin = array();
        $total_kelamin = 0;
        foreach($data_kelamin as $_data){
            if(!empty($_data->kelamin)){
                array_push($kelamin, $_data->kelamin);
                array_push($jumlah_kelamin, $_data->jumlah);
                $total_kelamin += $_data->jumlah;
            }
        }
        // //
        // $data_level = DB::select("SELECT b.level AS level,COUNT(a.kode_level) AS jumlah,SUM(COUNT(a.kode_level)) OVER() AS total FROM mtr_tbl_karyawan AS a LEFT JOIN mtr_tbl_level AS b ON b.id=a.kode_level WHERE a.is_aktif=1 AND a.nik NOT IN ('IT.BM','Hco.all')GROUP BY a.kode_level");
        // $level = array();
        // $jumlah_level = array();
        // foreach($data_level as $_data){
        //     array_push($level, $_data->level);
        //     array_push($jumlah_level, round($_data->jumlah / $_data->total * 100, 2));
        // }
        // return view( 'dashboard.index', compact( 'status', 'jumlah_status','perusahaan', 'jumlah_perusahaan','kelamin', 'jumlah_kelamin','level', 'jumlah_level' ) );
        $data = DB::select("SELECT COUNT(a.nik) AS jumlah, b.nama_singkat AS nama FROM mtr_tbl_karyawan AS a LEFT JOIN mtr_tbl_perusahaan AS b ON a.kode_perusahaan=b.id WHERE a.is_aktif=1 AND a.nik NOT IN ('IT.BM','Hco.all') GROUP BY a.kode_perusahaan");
        $perusahaan_aktif = array();
        $karyawan_aktif = array();
        $total_aktif = 0;
        foreach($data as $_data){
            array_push($perusahaan_aktif, $_data->nama);
            array_push($karyawan_aktif, $_data->jumlah);
            $total_aktif += $_data->jumlah;
        }
        // 
        $data = DB::select("SELECT COUNT(a.nik) AS jumlah, b.nama_singkat AS nama FROM mtr_tbl_karyawan AS a LEFT JOIN mtr_tbl_perusahaan AS b ON a.kode_perusahaan=b.id WHERE a.is_aktif=0 AND a.nik NOT IN ('IT.BM','Hco.all') GROUP BY a.kode_perusahaan");
        $perusahaan_nonaktif = array();
        $karyawan_nonaktif = array();
        $total_nonaktif = 0;
        foreach($data as $_data){
            array_push($perusahaan_nonaktif, $_data->nama);
            array_push($karyawan_nonaktif, $_data->jumlah);
            $total_nonaktif += $_data->jumlah;
        }
        // 
        $data = DB::select("SELECT COUNT(a.jenis) AS jumlah, a.tahun AS tahun FROM trx_tbl_pelatihan AS a GROUP BY a.tahun");
        $tahun_pelatihan = array();
        $jumlah_pelatihan = array();
        $total_pelatihan = 0;
        foreach($data as $_data){
            array_push($tahun_pelatihan, $_data->tahun);
            array_push($jumlah_pelatihan, $_data->jumlah);
            $total_pelatihan += $_data->jumlah;
        }
        // 
        $data = DB::select("SELECT COUNT(tanggal) AS jumlah, MONTHNAME(tanggal) AS bulan FROM trx_tbl_nonaktif_master WHERE YEAR(tanggal)=YEAR(NOW()) GROUP BY MONTH(tanggal)");
        $bulan_karyawannonaktif = array();
        $jumlah_karyawannonaktif = array();
        $total_karyawannonaktif = 0;
        foreach($data as $_data){
            array_push($bulan_karyawannonaktif, $_data->bulan);
            array_push($jumlah_karyawannonaktif, $_data->jumlah);
            $total_karyawannonaktif += $_data->jumlah;
        }
        // 
        $data = DB::select("SELECT COUNT(tanggal) AS jumlah, MONTHNAME(tanggal) AS bulan FROM trx_tbl_nonaktif_master WHERE YEAR(tanggal)=YEAR(NOW())-1 GROUP BY MONTH(tanggal)");
        $bulan_karyawannonaktif_1 = array();
        $jumlah_karyawannonaktif_1 = array();
        $total_karyawannonaktif_1 = 0;
        foreach($data as $_data){
            array_push($bulan_karyawannonaktif_1, $_data->bulan);
            array_push($jumlah_karyawannonaktif_1, $_data->jumlah);
            $total_karyawannonaktif_1 += $_data->jumlah;
        }
        return view( 'dashboard.index', compact( 'perusahaan', 'perusahaan_aktif', 'karyawan_aktif', 'total_aktif', 'perusahaan_nonaktif', 'karyawan_nonaktif', 'total_nonaktif', 'tahun_pelatihan', 'jumlah_pelatihan', 'total_pelatihan', 'kelamin', 'jumlah_kelamin', 'total_kelamin', 'bulan_karyawannonaktif', 'jumlah_karyawannonaktif','total_karyawannonaktif', 'bulan_karyawannonaktif_1', 'jumlah_karyawannonaktif_1','total_karyawannonaktif_1' ) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    }
}
