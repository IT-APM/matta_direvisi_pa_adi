<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ToDoList;
use App\Models\Karyawan;
use App\Models\Perusahaan;
use App\Models\Divisi;
use App\Models\Departemen;
use App\Models\SubDepartemen;
use DB;

class TrxToDoListController extends Controller
{

    public function detail(Request $request)
    {
        $nik = (new OpenDataController)->decryptWithKey($request->nik);
        $title = (new OpenDataController)->decryptWithKey($request->title);
        $where = ['nik' => $nik, 'title' => $title];
        $trx = ToDoList::where($where)->orderBy('id', 'desc')->get();
        return view('todolist.recap_detail', compact('trx'));
    }

    public function recap(Request $request)
    {
        // dd($request->all());
        $title = ToDoList::groupBy('title')->orderBy('title', 'asc')->get();
        // dd($title);
        // 0=NA
        // 3=SMS Project
        $perusahaan = Perusahaan::whereNotIn('id', [0,3])->orderBy('nama', 'asc')->get();
        $divisi = [];
        $departemen = [];
        $sub_departemen = [];
        //
        $arr_title = [];
        $arr_perusahaan = [];
        $arr_divisi = [];
        $arr_departemen = [];
        $arr_sub_departemen = [];
        // 
        $trx = ToDoList::select('*', DB::raw('COUNT(nik) AS jumlah'))->groupBy('nik', 'title')->orderBy('jumlah', 'desc')->get();
        // start filter
        if ($request->filter == true) {
            $_title = $request->input('title', []);
            $_perusahaan = $request->input('kode_perusahaan', []);
            $_divisi = $request->input('kode_divisi', []);
            $_departemen = $request->input('kode_departemen', []);
            $_sub_departemen = $request->input('kode_sub_departemen', []);
            if (count($_title) > 0) {
                $arr_title = $_title;
            }
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
            $divisi = Divisi::whereIn('kode_perusahaan', $arr_perusahaan)->get();
            $departemen = Departemen::whereIn('kode_divisi', $arr_divisi)->get();
            $sub_departemen = SubDepartemen::whereIn('kode_departemen', $arr_departemen)->get();
            $karyawan = Karyawan::whereIn('kode_sub_departemen', $arr_sub_departemen)->whereNotIn('nik', ['Hco.all', 'IT.BM'])->get();
            $_karyawan = [];
            foreach($karyawan as $data){
                array_push($_karyawan, $data->nik);
            }
            $trx = ToDoList::select('*', DB::raw('COUNT(nik) AS jumlah'))->whereIn('nik', $_karyawan)->whereIn('title', $arr_title)->groupBy('nik', 'title')->orderBy('jumlah', 'desc')->get();
        }
        return view( 'todolist.recap', compact('trx', 'perusahaan', 'divisi', 'departemen', 'sub_departemen', 'title', 'arr_perusahaan', 'arr_divisi', 'arr_departemen', 'arr_sub_departemen', 'arr_title'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $arr = [];
        // $trx = DB::select("SELECT COUNT(kode_sub_departemen) AS jumlah,kode_sub_departemen FROM mtr_tbl_karyawan WHERE is_aktif=1 AND wg_id=1 AND nik NOT IN ('Hco.all', 'IT.BM') GROUP BY kode_sub_departemen ORDER BY jumlah");
        // foreach ($trx as $data) {
        //     // $_arr = [];
        //     $where = ['kode_sub_departemen'=>$data->kode_sub_departemen, 'is_aktif'=>1, 'wg_id'=>1];
        //     $karyawan = Karyawan::where($where)->whereNotIn('nik', ['Hco.all', 'IT.BM'])->orderBy('nama', 'asc')->get();
        //     array_push($arr, ['jumlah'=>$data->jumlah, 'karyawan'=>$karyawan]);
        //     // array_push($arr, $_arr);
        // }
        // // dd($arr);
        // return view('todolist.karyawan_detail', compact('arr'));
        //
        // kebutuhan0
        $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.kebutuhan0'];
        $trxKebutuhan = ToDoList::where($where)->orderBy('id', 'desc')->get();
        // cuti0
        $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.cuti0'];
        $trxCuti = ToDoList::where($where)->orderBy('id', 'desc')->get();
        // izin0
        $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.izin0'];
        $trxIzin = ToDoList::where($where)->orderBy('id', 'desc')->get();
        // nonaktif0
        $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.nonaktif0'];
        $trxNonAktif = ToDoList::where($where)->orderBy('id', 'desc')->get();
        // pelatihan0
        $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.pelatihan0'];
        $trxPelatihan = ToDoList::where($where)->orderBy('id', 'desc')->get();
        // penilaian0 dan penilaian0_outsourcing
        // $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.penilaian0'];
        $where = ['nik' => auth()->user()->nik];
        $trxPenilaian = ToDoList::where($where)->whereIn('filename', ['emails.penilaian0', 'emails.penilaian0_outsourcing'])->orderBy('id', 'desc')->get();
        // reimburse0
        $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.reimburse0'];
        $trxReimburse = ToDoList::where($where)->orderBy('id', 'desc')->get();
        // rekomendasi0
        $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.rekomendasi0'];
        $trxRekomendasi = ToDoList::where($where)->orderBy('id', 'desc')->get();
        // lembur0
        $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.lembur0'];
        $trxOvertime = ToDoList::where($where)->orderBy('id', 'desc')->get();
        // bpd0
        // $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.bpd0'];
        $where = ['nik' => auth()->user()->nik];
        $trxBpd = ToDoList::where($where)->whereIn('filename', ['emails.bpd0', 'emails.bpd_done'])->orderBy('id', 'desc')->get();
        // sakit0
        $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.sakit0'];
        $trxSakit = ToDoList::where($where)->orderBy('id', 'desc')->get();
        // recruitment0
        $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.recruitment0'];
        $recruitment = ToDoList::where($where)->orderBy('id', 'desc')->get();
        // 
        return view('todolist.index', compact('trxKebutuhan','trxCuti','trxIzin','trxNonAktif','trxPelatihan','trxPenilaian','trxReimburse','trxRekomendasi','trxOvertime','trxBpd','trxSakit','recruitment'));
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
    public function show(Request $request, $id)
    {
        //
        // return response()->json(['trx' => $trx]);die;
        $no= 0;
        $filename = $request->filename;
        // kebutuhan0
        if($filename == 'emails.kebutuhan0'){
            $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.kebutuhan0'];
            $trx = ToDoList::where($where)->orderBy('id', 'desc')->get();
            return view('todolist.list', compact('no', 'trx'));
        }
        // penilaian0
        else if($filename == 'emails.penilaian0'){
            // $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.penilaian0'];
            $where = ['nik' => auth()->user()->nik];
            $trx = ToDoList::where($where)->whereIn('filename', ['emails.penilaian0', 'emails.penilaian0_outsourcing'])->orderBy('id', 'desc')->get();
            return view('todolist.list', compact('no', 'trx'));
        }
        // rekomendasi0
        else if($filename == 'emails.rekomendasi0'){
            $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.rekomendasi0'];
            $trx = ToDoList::where($where)->orderBy('id', 'desc')->get();
            return view('todolist.list', compact('no', 'trx'));
        }
        // nonaktif0
        else if($filename == 'emails.nonaktif0'){
            $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.nonaktif0'];
            $trx = ToDoList::where($where)->orderBy('id', 'desc')->get();
            return view('todolist.list', compact('no', 'trx'));
        }
        // reimburse0
        else if($filename == 'emails.reimburse0'){
            $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.reimburse0'];
            $trx = ToDoList::where($where)->orderBy('id', 'desc')->get();
            return view('todolist.list', compact('no', 'trx'));
        }
        // cuti0
        else if($filename == 'emails.cuti0'){
            $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.cuti0'];
            $trx = ToDoList::where($where)->orderBy('id', 'desc')->get();
            return view('todolist.list', compact('no', 'trx'));
        }
        // izin0
        else if($filename == 'emails.izin0'){
            $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.izin0'];
            $trx = ToDoList::where($where)->orderBy('id', 'desc')->get();
            return view('todolist.list', compact('no', 'trx'));
        }
        // pelatihan0
        else if($filename == 'emails.pelatihan0'){
            $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.pelatihan0'];
            $trx = ToDoList::where($where)->orderBy('id', 'desc')->get();
            return view('todolist.list', compact('no', 'trx'));
        }
        // lembur0
        else if($filename == 'emails.lembur0'){
            $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.lembur0'];
            $trx = ToDoList::where($where)->orderBy('id', 'desc')->get();
            return view('todolist.list', compact('no', 'trx'));
        }
        // bpd0
        else if($filename == 'emails.bpd0'){
            // $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.bpd0'];
            $where = ['nik' => auth()->user()->nik];
            $trx = ToDoList::where($where)->whereIn('filename', ['emails.bpd0', 'emails.bpd_done'])->orderBy('id', 'desc')->get();
            return view('todolist.list', compact('no', 'trx'));
        }
        // sakit0
        else if($filename == 'emails.sakit0'){
            $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.sakit0'];
            $trx = ToDoList::where($where)->orderBy('id', 'desc')->get();
            return view('todolist.list', compact('no', 'trx'));
        }
        // recruitment0
        else if($filename == 'emails.recruitment0'){
            $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.recruitment0'];
            $trx = ToDoList::where($where)->orderBy('id', 'desc')->get();
            return view('todolist.list', compact('no', 'trx'));
        }
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
