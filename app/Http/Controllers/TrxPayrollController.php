<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use App\Mail\MattaRebornMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use URL;
// 
use App\Models\MtrUtility;
use App\Models\Karyawan;
use App\Models\MtrPayroll;
use App\Models\MtrPayrollAddsub;
use App\Models\MtrPayrollBpjstk;
use App\Models\MtrPayrollBpjskes;
use App\Models\MtrPayrollBpjsMax;
use App\Models\TrxPayroll;
use App\Models\TrxPayrollLog;
use App\Models\TrxPayrollAddsub;
use App\Models\TrxPayrollAddsubLog;
use App\Models\TrxPayrollBpjstk;
use App\Models\TrxPayrollBpjstkLog;
use App\Models\TrxPayrollBpjskes;
use App\Models\TrxPayrollBpjskesLog;
class TrxPayrollController extends Controller
{
    public function upsert_master(Request $request)
    {
        // dd($request->all(), $request->input('id_data'));
        // add dan sub
        if(in_array($request->_id, [1, 2])){
            if($request->tipe == 'edit'){
                $trx = MtrPayrollAddsub::find($request->input('id_data'));
                $trx->nama = $request->nama;
                if($request->bulanan == 'on'){
                    $trx->bulanan = 1;
                }else{
                    $trx->bulanan = 0;
                }
                $trx->update();
                return redirect('payroll/master/'.$request->_id)->with('msg', 'Data is saved successfully.');
            }
            $trx = New MtrPayrollAddsub;
            $trx->nama = $request->nama;
            if($request->_id == 1){
                $trx->tipe = 'add';
            }elseif($request->_id == 2){
                $trx->tipe = 'sub';
            }
            if($request->bulanan == 'on'){
                $trx->bulanan = 1;
            }else{
                $trx->bulanan = 0;
            }
            $trx->save();
            return redirect('payroll/master/'.$request->_id)->with('msg', 'Data is saved successfully.');
        }
        // bpjs
        elseif(in_array($request->_id, [3, 4, 5])){
            if(in_array($request->tipe, ['edit_bpjstk', 'edit_bpjsks', 'edit_bpjsmx'])){
                if(in_array($request->tipe, ['edit_bpjstk'])){
                    $trx = MtrPayrollBpjstk::find($request->input('id_data'));
                    $trx->nama = $request->nama;
                    $trx->persen = $request->persen;
                }elseif(in_array($request->tipe, ['edit_bpjsks'])){
                    $trx = MtrPayrollBpjskes::find($request->input('id_data'));
                    $trx->nama = $request->nama;
                    $trx->persen = $request->persen;
                }elseif(in_array($request->tipe, ['edit_bpjsmx'])){
                    $trx = MtrPayrollBpjsMax::find($request->input('id_data'));
                    $trx->nama = $request->nama;
                    $trx->angka = str_replace(',', '', $request->angka);
                }
                $trx->update();
                return redirect('payroll/master/'.$request->_id)->with('msg', 'Data is saved successfully.');
            }
            elseif(in_array($request->tipe, ['bpjstk', 'bpjsks', 'bpjsmx'])){
                if(in_array($request->tipe, ['bpjstk'])){
                    $trx = New MtrPayrollBpjstk;
                    $trx->nama = $request->nama;
                    $trx->persen = $request->persen;
                }elseif(in_array($request->tipe, ['bpjsks'])){
                    $trx = New MtrPayrollBpjskes;
                    $trx->nama = $request->nama;
                    $trx->persen = $request->persen;
                }elseif(in_array($request->tipe, ['bpjsmx'])){
                    $trx = New MtrPayrollBpjsMax;
                    $trx->nama = $request->nama;
                    $trx->angka = $request->angka;
                }
                $trx->save();
                return redirect('payroll/master/'.$request->_id)->with('msg', 'Data is saved successfully.');
            }
        }
    }

    public function upload_payroll(Request $request)
    {
        // dd($request->all());
        $jenis = $request->jenis;
        if($request->hasFile('template')){
            $request->validate([
                'template'=>'required|file|mimes:xls,xlsx|max:2048',
            ]);
            // upload transaksi
            if($jenis == 'trx'){
                $path = $request->file('template')->getPathname();
                $trx = Excel::toArray([], $path);
                // data payroll dan penambahan
                $data0 = $trx[0];
                // data potongan
                $data1 = $trx[1];
                // 101=NIP
                // 102=Bulan
                // 103=Tahun
                // 104=Gaji Pokok
                $_101 = array_search(101, $data0[0]);
                $_102 = array_search(102, $data0[0]);
                $_103 = array_search(103, $data0[0]);
                $_104 = array_search(104, $data0[0]);
                // dimulai dari baris ke-3
                // data payroll
                for ($x = 2; $x < count($data0); $x++) {
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103]];
                    TrxPayroll::where($where)->delete();
                    $trx = New TrxPayroll;
                    $trx->nik = $data0[$x][$_101];
                    $trx->nik_property = $this->getNikProperty($data0[$x][$_101]);
                    $trx->bulan = $data0[$x][$_102];
                    $trx->tahun = $data0[$x][$_103];
                    $trx->gapok = $data0[$x][$_104];
                    $trx->created_by = auth()->user()->nik;
                    $trx->updated_by = auth()->user()->nik;
                    $trx->save();
                }
                // data tambahan
                $addsub = MtrPayrollAddsub::whereIn('id', $data0[0])->orderBy('id', 'asc')->get();
                $arr = [];
                foreach($addsub as $_addsub){
                    if(in_array($_addsub->id, $data0[0])){
                        $_arr = [];
                        array_push($_arr, array_search($_addsub->id, $data0[0]), $_addsub->id, $_addsub->tipe);
                    }
                    array_push($arr, $_arr);
                }
                for ($x = 2; $x < count($data0); $x++) {
                    foreach($arr as $_arr){
                        $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_addsub'=>$_arr[1]];
                        TrxPayrollAddsub::where($where)->delete();
                        $trx = New TrxPayrollAddsub;
                        $trx->nik = $data0[$x][$_101];
                        $trx->bulan = $data0[$x][$_102];
                        $trx->tahun = $data0[$x][$_103];
                        // 0=index data
                        $trx->angka = $data0[$x][$_arr[0]];
                        // 1=id_addsub
                        $trx->id_addsub = $_arr[1];
                        // 2=tipe
                        $trx->tipe = $_arr[2];
                        $trx->created_by = auth()->user()->nik;
                        $trx->updated_by = auth()->user()->nik;
                        $trx->save();
                        // 
                        $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103]];
                        $payroll = TrxPayroll::where($where)->orderBy('id', 'asc')->get();
                        $this->insertBpjs($payroll);
                    }
                }
                // data potongan
                $addsub = MtrPayrollAddsub::whereIn('id', $data1[0])->orderBy('id', 'asc')->get();
                $arr = [];
                foreach($addsub as $_addsub){
                    if(in_array($_addsub->id, $data1[0])){
                        $_arr = [];
                        array_push($_arr, array_search($_addsub->id, $data1[0]), $_addsub->id, $_addsub->tipe);
                    }
                    array_push($arr, $_arr);
                }
                for ($x = 2; $x < count($data1); $x++) {
                    foreach($arr as $_arr){
                        $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_addsub'=>$_arr[1]];
                        TrxPayrollAddsub::where($where)->delete();
                        $trx = New TrxPayrollAddsub;
                        $trx->nik = $data1[$x][$_101];
                        $trx->bulan = $data1[$x][$_102];
                        $trx->tahun = $data1[$x][$_103];
                        // 0=index data
                        $trx->angka = $data1[$x][$_arr[0]];
                        // 1=id_addsub
                        $trx->id_addsub = $_arr[1];
                        // 2=tipe
                        $trx->tipe = $_arr[2];
                        $trx->created_by = auth()->user()->nik;
                        $trx->updated_by = auth()->user()->nik;
                        $trx->save();
                    }
                }
                return redirect('payroll')->with('msg', 'Data is saved successfully.');
            }
            // upload master
            elseif($jenis == 'mtr'){
                $path = $request->file('template')->getPathname();
                $trx = Excel::toArray([], $path);
                $data0 = $trx[0];
                // 101=NIP
                // 102=Bulan
                // 103=Tahun
                // 104=Gaji Pokok
                // 105=Pasangan
                // 106=Anak
                // 107=Tambahan
                // 108=No KTP
                // 109=No KPJ
                // 110=No JKN
                // 111=Rekening Atas Nama
                // 112=No. Rekening
                // 113=Nama Bank
                $_101 = array_search(101, $data0[0]);
                $_102 = array_search(102, $data0[0]);
                $_103 = array_search(103, $data0[0]);
                $_104 = array_search(104, $data0[0]);
                $_105 = array_search(105, $data0[0]);
                $_106 = array_search(106, $data0[0]);
                $_107 = array_search(107, $data0[0]);
                $_108 = array_search(108, $data0[0]);
                $_109 = array_search(109, $data0[0]);
                $_110 = array_search(110, $data0[0]);
                $_111 = array_search(111, $data0[0]);
                $_112 = array_search(112, $data0[0]);
                $_113 = array_search(113, $data0[0]);
                // dimulai dari baris ke-3
                // data payroll
                for ($x = 2; $x < count($data0); $x++) {
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103]];
                    MtrPayroll::where($where)->delete();
                    $trx = New MtrPayroll;
                    $trx->nik = $data0[$x][$_101];
                    $trx->bulan = $data0[$x][$_102];
                    $trx->tahun = $data0[$x][$_103];
                    $trx->gapok = $data0[$x][$_104];
                    $trx->pasangan = $data0[$x][$_105];
                    $trx->anak = $data0[$x][$_106];
                    $trx->tambahan = $data0[$x][$_107];
                    $trx->no_ktp = $data0[$x][$_108];
                    $trx->no_kpj = $data0[$x][$_109];
                    $trx->no_jkn = $data0[$x][$_110];
                    $trx->nama_rek = $data0[$x][$_111];
                    $trx->nama_bank = $data0[$x][$_112];
                    $trx->no_rek = $data0[$x][$_113];
                    $trx->save();
                }
                return redirect('/payroll/master/0')->with('msg', 'Data is saved successfully.');
            }
        }
    }
    
    public function master($id, Request $request)
    {
        // dd($id, $request->input('tipe'));
        $tipe = $request->input('tipe');
        $payroll = MtrPayroll::orderBy('id', 'asc')->get();
        $where = ['tipe'=>'add'];
        $add = MtrPayrollAddsub::where($where)->orderBy('id', 'asc')->get();
        $where = ['tipe'=>'sub'];
        $sub = MtrPayrollAddsub::where($where)->orderBy('id', 'asc')->get();
        $bpjstk = MtrPayrollBpjstk::orderBy('id', 'asc')->get();
        $bpjskes = MtrPayrollBpjskes::orderBy('id', 'asc')->get();
        $bpjsmax = MtrPayrollBpjsMax::orderBy('id', 'asc')->get();
        if(in_array($tipe, ['add', 'sub', 'edit', 'bpjstk', 'bpjsks', 'bpjsmx', 'edit_bpjstk', 'edit_bpjsks', 'edit_bpjsmx'])){
            $status = 'new';
            if($tipe == 'edit'){
                $status = 'edit';
                $addsub = MtrPayrollAddsub::find($request->input('id_data'));
                return view('payroll.upsert_addsub', compact('addsub','status','id','tipe'));
            }elseif(in_array($tipe, ['add', 'sub'])){
                return view('payroll.upsert_addsub', compact('status','id','tipe'));
            }elseif(in_array($tipe, ['bpjstk', 'bpjsks', 'bpjsmx', 'edit_bpjstk', 'edit_bpjsks', 'edit_bpjsmx'])){$status = 'new';
                if(in_array($tipe, ['edit_bpjstk', 'edit_bpjsks', 'edit_bpjsmx'])){
                    $status = 'edit';
                    if(in_array($tipe, ['edit_bpjstk'])){
                        $bpjs = MtrPayrollBpjstk::find($request->input('id_data'));
                    }elseif(in_array($tipe, ['edit_bpjsks'])){
                        $bpjs = MtrPayrollBpjskes::find($request->input('id_data'));
                    }elseif(in_array($tipe, ['edit_bpjsmx'])){
                        $bpjs = MtrPayrollBpjsMax::find($request->input('id_data'));
                    }
                    return view('payroll.upsert_bpjs', compact('bpjs','status','id','tipe'));
                }else{
                    return view('payroll.upsert_bpjs', compact('status','id','tipe'));
                }
            }
        }else{
            return view('payroll.index_mtr', compact('payroll','add','sub','bpjstk','bpjskes','bpjsmax','id'));
        }
    }
    
    public function insertBpjs($payroll)
    {
        // dd($payroll);
        $where = ['nik'=>$payroll[0]->nik, 'bulan'=>$payroll[0]->bulan, 'tahun'=>$payroll[0]->tahun];
        TrxPayrollBpjstk::where($where)->delete();
        TrxPayrollBpjskes::where($where)->delete();
        // 
        $bpjstk = MtrPayrollBpjstk::orderBy('id', 'asc')->get();
        $bpjskes = MtrPayrollBpjskes::orderBy('id', 'asc')->get();
        // 1=Upah Max BPJS TK
        // 2=Upah Max BPJS KS
        // 3=Anggota Keluarga BPJS KS
        $bpjsmax = MtrPayrollBpjsMax::orderBy('id', 'asc')->get();
        foreach ($payroll as $data){
            // TK
            foreach ($bpjstk as $_data){
                $trx = new TrxPayrollBpjstk;
                $trx->nik = $data->nik;
                $trx->bulan = $data->bulan;
                $trx->tahun = $data->tahun;
                // 
                $where = ['nik'=>$data->nik, 'tipe'=>'add'];
                $arr_id = [1,2,3,4,5];
                $add = TrxPayrollAddsub::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                $total_tunjangan = $add->sum('angka');
                // 
                $upah = $data->gapok + $total_tunjangan;
                $trx->upah = $upah;
                // 
                $trx->maksimal = $bpjsmax[0]->angka;
                $trx->angka = $upah;
                if($upah > $bpjsmax[0]->angka){
                    $trx->angka = $bpjsmax[0]->angka;
                }
                // 
                $trx->persen = $_data->persen;
                $trx->id_bpjstk = $_data->id;
                $trx->created_by = auth()->user()->nik;
                $trx->updated_by = auth()->user()->nik;
                $trx->save();
            }
            // KS
            foreach ($bpjskes as $_data){
                $trx = new TrxPayrollBpjskes;
                $trx->nik = $data->nik;
                $trx->bulan = $data->bulan;
                $trx->tahun = $data->tahun;
                // 
                $where = ['nik'=>$data->nik, 'bulan'=>$data->bulan, 'tahun'=>$data->tahun];
                $mtr = MtrPayroll::where($where)->orderBy('id', 'asc')->first();
                $trx->pasangan = $mtr->pasangan;
                $trx->anak = $mtr->anak;
                $trx->tambahan = $mtr->tambahan;
                // 
                $where = ['nik'=>$data->nik, 'tipe'=>'add'];
                $arr_id = [1,2,3,4,5];
                $add = TrxPayrollAddsub::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                $total_tunjangan = $add->sum('angka');
                // 
                $upah = $data->gapok + $total_tunjangan;
                $trx->upah = $upah;
                // 
                $trx->maksimal = $bpjsmax[1]->angka;
                $trx->angka = $upah;
                if($upah > $bpjsmax[1]->angka){
                    $trx->angka = $bpjsmax[1]->angka;
                }
                // 
                $trx->persen = $_data->persen;
                $trx->id_bpjskes = $_data->id;
                $trx->created_by = auth()->user()->nik;
                $trx->updated_by = auth()->user()->nik;
                $trx->save();
            }

        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $month = '11';
        // $year = '2024';
        // dd(Carbon::createFromFormat('Y-m-d', "{$year}-{$month}-01")->subMonth()->startOfMonth()->format('Y-m-d'));
        // dd(Carbon::createFromFormat('Y-m-d', "{$year}-{$month}-01")->subMonth()->endOfMonth()->format('Y-m-d'));
        $payroll = TrxPayroll::orderBy('id', 'asc')->get();
        // $where = ['tipe'=>'add'];
        // $add = TrxPayrollAddsub::where($where)->orderBy('id', 'asc')->get();
        // $where = ['tipe'=>'sub'];
        // $sub = TrxPayrollAddsub::where($where)->orderBy('id', 'asc')->get();
        // $bpjstk = TrxPayrollBpjstk::orderBy('id', 'asc')->get();
        // $bpjskes = TrxPayrollBpjskes::orderBy('id', 'asc')->get();
        // return view('payroll.index', compact('payroll','add','sub','bpjstk','bpjskes'));
        return view('payroll.index', compact('payroll'));
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
    public function destroy(Request $request, $id)
    {
        // dd($request->all(), $id);
        try {
            // add dan sub
            if(in_array($id, [1, 2])){
                $trx = MtrPayrollAddsub::find($request->id);
                $trx->delete();
            }
            // bpjstk
            elseif(in_array($id, [3])){
                $trx = MtrPayrollBpjstk::find($request->id);
                $trx->delete();
            }
            // bpjsks
            elseif(in_array($id, [4])){
                $trx = MtrPayrollBpjskes::find($request->id);
                $trx->delete();
            }
            // bpjsmx
            elseif(in_array($id, [5])){
                $trx = MtrPayrollBpjsMax::find($request->id);
                $trx->delete();
            }
            return redirect( 'payroll/master/'.$id )->with( 'msg', 'Data is deleted successfully.' );
         } catch (\Exception $e) {
            // Log::error($e->errorInfo);
            return redirect()->route('error_db');
         }
    }

    public function getNikProperty($nik)
    {
        $where = ['nik'=>$nik];
        $data = Karyawan::select('mtr_tbl_karyawan.nik', 'mtr_tbl_karyawan.nama', 'mtr_tbl_karyawan.email', 'mtr_tbl_karyawan.telepon1', 'mtr_tbl_perusahaan.nama as perusahaan', 'mtr_tbl_divisi.nama as divisi', 'mtr_tbl_departemen.nama as dep', 'mtr_tbl_sub_departemen.nama as sub_dep', 'mtr_tbl_level.level as level', 'mtr_tbl_jabatan.jabatan as jabatan', 'mtr_tbl_level.id as id_level', 'mtr_tbl_status_karyawan.status_karyawan as status_karyawan')
            ->leftJoin('mtr_tbl_perusahaan', 'mtr_tbl_perusahaan.id', '=', 'mtr_tbl_karyawan.kode_perusahaan')
            ->leftJoin('mtr_tbl_divisi', 'mtr_tbl_divisi.id', '=', 'mtr_tbl_karyawan.kode_divisi')
            ->leftJoin('mtr_tbl_departemen', 'mtr_tbl_departemen.id', '=', 'mtr_tbl_karyawan.kode_departemen')
            ->leftJoin('mtr_tbl_sub_departemen', 'mtr_tbl_sub_departemen.id', '=', 'mtr_tbl_karyawan.kode_sub_departemen')
            ->leftJoin('mtr_tbl_level', 'mtr_tbl_level.id', '=', 'mtr_tbl_karyawan.kode_level')
            ->leftJoin('mtr_tbl_jabatan', 'mtr_tbl_jabatan.id', '=', 'mtr_tbl_karyawan.kode_jabatan')
            ->leftJoin('mtr_tbl_status_karyawan', 'mtr_tbl_status_karyawan.id', '=', 'mtr_tbl_karyawan.kode_status')
            ->where($where)->first();
        return json_encode($data);
    }
}
