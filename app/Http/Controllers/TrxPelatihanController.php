<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// 
use App\Models\Karyawan;
use App\Models\Jabatan;
use App\Models\Level;
// 
use App\Models\PelatihanJenis;
use App\Models\PelatihanAnggaran;
use App\Models\PelatihanBenefit;
use App\Models\PelatihanStatus;
use App\Models\TrxPelatihan;
use App\Models\TrxPelatihanLog;
// 
use App\Models\ToDoList;
use App\Models\ToDoListLog;
use App\Models\MtrUtility;
use App\Mail\MattaRebornMail;
use Illuminate\Support\Facades\Mail;
// 
use Carbon\Carbon;
use DB;
use Elibyy\TCPDF\Facades\TCPDF;
// 
use Illuminate\Support\Facades\Gate;

class TrxPelatihanController extends Controller
{
    public function __construct(Gate $gate)
    {
        $this->middleware('can:spv', ['except' => ['show','update']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $no = 0;
        $trx = TrxPelatihan::orderBy('id', 'desc')->get();
        // foreach ($trx as $value) {
        //     $trxM = TrxPelatihan::find( $value->id );
        //     $trxM->pengaju_property = $this->getNikProperty($value->pengaju);
        //     $trxM->atasan_pengaju_property = $this->getNikProperty($value->atasan_pengaju);
        //     $trxM->reviewer1_property = $this->getNikProperty($value->reviewer1);
        //     $trxM->reviewer2_property = $this->getNikProperty($value->reviewer2);
        //     // 
        //     $peserta_property = array();
        //     $_peserta = explode(';', $value->peserta);
        //     foreach ($_peserta as $_value) {
        //         array_push($peserta_property, $this->getNikProperty($_value));
        //     }
        //     $trxM->peserta_property = implode(';', $peserta_property);
        //     // 
        //     $atasan_peserta_property = array();
        //     $_atasan_peserta = explode(';', $value->atasan_peserta);
        //     foreach ($_atasan_peserta as $_value) {
        //         array_push($atasan_peserta_property, $this->getNikProperty($_value));
        //     }
        //     $trxM->atasan_peserta_property = implode(';', $atasan_peserta_property);
        //     // 
        //     $trxM->update();
        // }
        if(auth()->user()->userrole == 'nhc'){
            $where = ['pengaju' => auth()->user()->nik];
            $trx = TrxPelatihan::where($where)->orderBy('id', 'desc')->get();
        }
        // filter parameter
        $arr_status_pengajuan = array();
        $arr_pengaju = array();
        $arr_atasan_pengaju = array();
        foreach ($trx as $data){
            array_push($arr_status_pengajuan, $data->status_pengajuan);
            array_push($arr_pengaju, $data->pengaju);
            array_push($arr_atasan_pengaju, $data->atasan_pengaju);
        }
        $status_pengajuan = PelatihanStatus::whereIn('id', $arr_status_pengajuan)->orderBy('id', 'asc')->get();
        $pengaju = Karyawan::whereIn('nik', $arr_pengaju)->orderBy('nama', 'asc')->get();
        $atasan_pengaju = Karyawan::whereIn('nik', $arr_atasan_pengaju)->orderBy('nama', 'asc')->get();
        // start filter
        if($request->filter == true){
            $_status_pengajuan = $request->input('status_pengajuan', []);
            $_pengaju = $request->input('pengaju', []);
            $_atasan_pengaju = $request->input('atasan_pengaju', []);
            if(count($_status_pengajuan) > 0){
                $arr_status_pengajuan = $_status_pengajuan;
            }
            else{
                $arr_status_pengajuan = array();
            }
            if(count($_pengaju) > 0){
                $arr_pengaju = $_pengaju;
            }
            // else{
            //     $arr_pengaju = array();
            // }
            if(count($_atasan_pengaju) > 0){
                $arr_atasan_pengaju = $_atasan_pengaju;
            }
            // else{
            //     $arr_atasan_pengaju = array();
            // }
            $trx = TrxPelatihan::whereIn('status_pengajuan', $arr_status_pengajuan)->whereIn('pengaju', $arr_pengaju)->whereIn('atasan_pengaju', $arr_atasan_pengaju)->orderBy('id', 'desc')->get();
            if(auth()->user()->userrole == 'nhc'){
				$where = ['pengaju' => auth()->user()->nik];
                $trx = TrxPelatihan::whereIn('status_pengajuan', $arr_status_pengajuan)->whereIn('pengaju', $arr_pengaju)->whereIn('atasan_pengaju', $arr_atasan_pengaju)->where($where)->orderBy('id', 'desc')->get();
            }
        }else{
            $arr_status_pengajuan = array();
            $arr_pengaju = array();
            $arr_atasan_pengaju = array();
        }
        // end filter
        return view('formulir.pelatihan.index', compact('trx', 'no', 'status_pengajuan', 'pengaju', 'atasan_pengaju','arr_status_pengajuan', 'arr_pengaju', 'arr_atasan_pengaju'));
        // return view('formulir.pelatihan.index', compact('trx', 'no'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $where = ['is_aktif' => 1];
        $karyawan = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
        $jenis = PelatihanJenis::orderBy('jenis', 'asc')->get();
        $anggaran = PelatihanAnggaran::orderBy('anggaran', 'asc')->get();
        $benefit = PelatihanBenefit::orderBy('benefit', 'asc')->get();
        // reviewer level minimal 3 = spv
        $where = [ 'is_aktif' => 1];
        $reviewer = Karyawan::where($where)->where('kode_level','>=',3)->orderBy('nama', 'asc')->get();
        return view('formulir.pelatihan.insert', compact('karyawan', 'jenis', 'anggaran', 'benefit', 'reviewer'));
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
        // return response()->json(['request' => $request->all()]);
        // nik pengaju di hardcode 01031504369
        // Abi Ibadallah
        // Minimal level pengaju adalah spv
        // $pengaju = '01031504369';
        $pengaju = auth()->user()->nik;
        $where = ['nik' => $pengaju, 'is_aktif' => 1];
        $karyawan = Karyawan::where($where)->first();
        $atasan_pengaju = $karyawan->layer1;
        // 
        $trx = new TrxPelatihan;
        $status_pengajuan = 1;
        $trx->pengaju = $pengaju;
        $trx->atasan_pengaju = $atasan_pengaju;
        $trx->peserta = implode(';', $request->peserta);
        $atasan_peserta = Karyawan::select('layer1')->whereIn('nik', $request->peserta)->get();
        $nik_atasan_peserta = array();
        foreach ($atasan_peserta as $value) {
            array_push($nik_atasan_peserta, $value->layer1);
        }
        $trx->atasan_peserta = implode(';', $nik_atasan_peserta);
        if($request->has('reviewer1')){
            $trx->reviewer1 = $request->reviewer1;
            $trx->reviewer1_property = $this->getNikProperty($request->reviewer1);
        }
        if($request->has('reviewer2')){
            $trx->reviewer2 = $request->reviewer2;
            $trx->reviewer2_property = $this->getNikProperty($request->reviewer2);
        }
        $_pengaju = $this->getNikProperty($pengaju);
        $trx->pengaju_property = $_pengaju;
        $_atasan_pengaju = $this->getNikProperty($atasan_pengaju);
        $trx->atasan_pengaju_property = $_atasan_pengaju;
        $peserta_property = array();
        foreach ($request->peserta as $value) {
            array_push($peserta_property, $this->getNikProperty($value));
        }
        $trx->peserta_property = implode(';', $peserta_property);
        $atasan_peserta_property = array();
        foreach ($atasan_peserta as $value) {
            array_push($atasan_peserta_property, $this->getNikProperty($value->layer1));
        }
        $trx->atasan_peserta_property = implode(';', $atasan_peserta_property);
        // return response()->json(['response' => $trx->atasan_peserta_property]);die;
        $where = ['pengaju' => $pengaju, 'tahun' => $request->tahun];
        $ke = TrxPelatihan::where($where)->count() + 1;
        $trx->ke = $ke;
        $trx->tahun = $request->tahun;
        $trx->jenis = implode(';', $request->jenis);
        $trx->judul = $request->judul;
        $trx->penyelenggara = $request->penyelenggara;
        $trx->tanggal_mulai = $request->tanggal_mulai;
        $trx->tanggal_akhir = $request->tanggal_akhir;
        $trx->tempat = $request->tempat;
        $trx->biaya = str_replace(',', '', $request->biaya);
        $trx->benefit = implode(';', $request->benefit);
        $trx->anggaran = $request->anggaran;
        $trx->keterangan = $request->keterangan;
        $trx->status_pengajuan = $status_pengajuan;
        $trx->save();
        $this->insertLog($trx->id, $status_pengajuan, $pengaju, 'new insert');
        // 
        $_pengaju_ = json_decode($_pengaju, true);
        $_atasan_pengaju_ = json_decode($_atasan_pengaju, true);
        // parameter yang dipassing ke sendemail
        $mailData['title'] = 'Formulir Pengajuan Pelatihan';
        $mailData['data'] = ['nama'=>$_pengaju_['nama'].'/'.$_pengaju_['nik'], 'jabatan'=>$_pengaju_['jabatan'], 'unit_kerja'=>$_pengaju_['divisi'].'/'.$_pengaju_['dep'].'/'.$_pengaju_['sub_dep'], 'link'=> route('pelatihan.show', [$trx->id, 'view' => 'approval', 'nik' => $_atasan_pengaju_['nik'], 'id_status' => $status_pengajuan, 'pic' => 'atasan_pengaju']), 'trx'=>$trx ];
        $mailData['filename'] = 'emails.pelatihan0';
        $mailData['to'] = $_atasan_pengaju_['email'];
        $todolist = ['id_trx' => $trx->id, 'nik' => $_atasan_pengaju_['nik'], 'id_status' => $status_pengajuan, 'pic' => 'atasan_pengaju'];
        $this->insertToDoList($todolist, $mailData);
        $this->sendEmail($mailData);
        // 
        return redirect('pelatihan')->with('msg', 'Data is saved successfully.');
    }

    public function getNikProperty($nik)
    {
        $where = ['nik' => $nik, 'is_aktif' => 1];
        $data = Karyawan::select('mtr_tbl_karyawan.nik', 'mtr_tbl_karyawan.nama', 'mtr_tbl_karyawan.email', 'mtr_tbl_karyawan.telepon1', 'mtr_tbl_perusahaan.nama as perusahaan', 'mtr_tbl_divisi.nama as divisi', 'mtr_tbl_departemen.nama as dep', 'mtr_tbl_sub_departemen.nama as sub_dep', 'mtr_tbl_level.level as level', 'mtr_tbl_jabatan.jabatan as jabatan', 'mtr_tbl_level.id as id_level', 'mtr_tbl_sub_departemen.id as id_sub_departemen')
            ->leftJoin('mtr_tbl_perusahaan', 'mtr_tbl_perusahaan.id', '=', 'mtr_tbl_karyawan.kode_perusahaan')
            ->leftJoin('mtr_tbl_divisi', 'mtr_tbl_divisi.id', '=', 'mtr_tbl_karyawan.kode_divisi')
            ->leftJoin('mtr_tbl_departemen', 'mtr_tbl_departemen.id', '=', 'mtr_tbl_karyawan.kode_departemen')
            ->leftJoin('mtr_tbl_sub_departemen', 'mtr_tbl_sub_departemen.id', '=', 'mtr_tbl_karyawan.kode_sub_departemen')
            ->leftJoin('mtr_tbl_level', 'mtr_tbl_level.id', '=', 'mtr_tbl_karyawan.kode_level')
            ->leftJoin('mtr_tbl_jabatan', 'mtr_tbl_jabatan.id', '=', 'mtr_tbl_karyawan.kode_jabatan')
            ->where($where)->first();
        return json_encode($data);
    }

    public function insertLog($id_trx, $id_status, $nik_pic, $keterangan)
    {
        $data = new TrxPelatihanLog;
        $data->id_trx = $id_trx;
        $data->id_status = $id_status;
        $data->nik_pic = $nik_pic;
        $data->keterangan = $keterangan;
        $data->save();
    }

    public function insertToDoList($todolist, $mailData)
    {
        $data = New ToDoList;
        $data->id_trx = $todolist['id_trx'];
        $data->id_status = $todolist['id_status'];
        $data->nik = $todolist['nik'];
        $data->pic = $todolist['pic'];
        $data->title = $mailData['title'];
        $data->nama = $mailData['data']['nama'];
        $data->jabatan = $mailData['data']['jabatan'];
        $data->unit_kerja = $mailData['data']['unit_kerja'];
        $data->link = $mailData['data']['link'];
        $data->filename = $mailData['filename'];
        $data->email = $mailData['to'];
        $data->save();
        $this->insertToDoListLog($todolist, $mailData);
    }

    public function insertToDoListLog($todolist, $mailData)
    {
        $data = New ToDoListLog;
        $data->id_trx = $todolist['id_trx'];
        $data->id_status = $todolist['id_status'];
        $data->nik = $todolist['nik'];
        $data->pic = $todolist['pic'];
        $data->title = $mailData['title'];
        $data->nama = $mailData['data']['nama'];
        $data->jabatan = $mailData['data']['jabatan'];
        $data->unit_kerja = $mailData['data']['unit_kerja'];
        $data->link = $mailData['data']['link'];
        $data->filename = $mailData['filename'];
        $data->email = $mailData['to'];
        $data->save();
    }

    public function deleteToDoList($where)
    {
        $trx = ToDoList::where($where)->first();
        if (!empty($trx)) {
            $trx->delete();
        }
    }

    public function sendEmail($mailData)
    {
        $ccEmails = explode(',', MtrUtility::find(11)->informasi);
        Mail::to($mailData['to'])->cc($ccEmails)->send(new MattaRebornMail($mailData));
    }

    public function checkApprovalLine($id)
    {
        // check para approver, jika sudah semua kirimkan email ke pengaju
        // start
        $trx = TrxPelatihan::find( $id );
        $check = true;
        $approver = array();
        $approver['atasan_pengaju'] = $trx->atasan_pengaju;
        $approver['atasan_peserta'] = $trx->atasan_peserta;
        if(!empty($trx->reviewer1)){
            $approver['reviewer1'] = $trx->reviewer1;
        }
        if(!empty($trx->reviewer2)){
            $approver['reviewer2'] = $trx->reviewer2;
        }
        // atasan_pengaju
        // 2 = Approve by L1 Requestor
        $where = ['id_trx' => $id, 'nik_pic' => $approver['atasan_pengaju'], 'id_status' => 2];
        if(TrxPelatihanLog::where($where)->exists()){
            $check = $check && true;
        }else{
            $check = $check && false;
        }
        // atasan_peserta
        // 5 = Approve by L1 Participants
        foreach(explode(';', $approver['atasan_peserta']) as $value){
            $where = ['id_trx' => $id, 'nik_pic' => $value, 'id_status' => 5];
            if(TrxPelatihanLog::where($where)->exists()){
                $check = $check && true;
            }else{
                $check = $check && false;
            }
        }
        // reviewer1
        // 10 = Approve by Reviewer 2
        if(!empty($trx->reviewer1)){
            $where = ['id_trx' => $id, 'nik_pic' => $approver['reviewer1'], 'id_status' => 10];
            if(TrxPelatihanLog::where($where)->exists()){
                $check = $check && true;
            }else{
                $check = $check && false;
            }
        }
        // reviewer2
        // 14 = Approve by Reviewer 3
        if(!empty($trx->reviewer2)){
            $where = ['id_trx' => $id, 'nik_pic' => $approver['reviewer2'], 'id_status' => 14];
            if(TrxPelatihanLog::where($where)->exists()){
                $check = $check && true;
            }else{
                $check = $check && false;
            }
        }
        if($check){
            // parameter yang dipassing ke sendemail
            $pengaju = json_decode($trx->pengaju_property, true);
            $mailData['title'] = 'Formulir Pengajuan Pelatihan';
            $mailData['filename'] = 'emails.pelatihan0';
            $mailData['data'] = ['nama'=>$pengaju['nama'], 'jabatan'=>$pengaju['jabatan'], 'unit_kerja'=>$pengaju['divisi'].'/'.$pengaju['dep'].'/'.$pengaju['sub_dep'], 'link'=> route('pelatihan.edit', [$id]), 'trx'=>$trx];
            $hco = explode('/', MtrUtility::find(29)->informasi);
            $mailData['to'] = $hco[1];
            $todolist = ['id_trx' => $trx->id, 'nik' => $hco[0], 'id_status' => $trx->status_pengajuan, 'pic' => 'hco'];
            $this->insertToDoList($todolist, $mailData);
            $this->sendEmail($mailData);
        }
        // finish
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
        $linkAction = route('pelatihan.update', $id);
        $where = ['id_trx' => $id];
        $history = TrxPelatihanLog::where($where)->orderBy('created_at', 'desc')->get();
        $trx = TrxPelatihan::find( $id );
        // ambil property para pihak
        $pengaju = json_decode($trx->pengaju_property, true);
        $atasan_pengaju = json_decode($trx->atasan_pengaju_property, true);
        // return response()->json(['atasan_pengaju' => $atasan_pengaju]);die;
        // 
        $arr_peserta = explode(';', $trx->peserta_property);
        $arrPeserta = array();
        foreach ($arr_peserta as $value) {
            array_push($arrPeserta, json_decode($value, true));
        }
        // 
        $arr_atasan_peserta = explode(';', $trx->atasan_peserta_property);
        $arrAtasanPeserta = array();
        foreach ($arr_atasan_peserta as $value) {
            array_push($arrAtasanPeserta, json_decode($value, true));
        }
        // 
        $arr_jenis = explode(';', $trx->jenis);
        $arrJenis = array();
        foreach ($arr_jenis as $value) {
            array_push($arrJenis, PelatihanJenis::select('jenis')->find( $value ));
        }
        // 
        $arr_benefit = explode(';', $trx->benefit);
        $arrBenefit = array();
        foreach ($arr_benefit as $value) {
            array_push($arrBenefit, PelatihanBenefit::select('benefit')->find( $value ));
        }
        // 
        $reviewer1 = '';
        $reviewer2 = '';
        if(!empty($trx->reviewer1)){
            $reviewer1 = json_decode($trx->reviewer1_property, true);
        }
        if(!empty($trx->reviewer2)){
            $reviewer2 = json_decode($trx->reviewer2_property, true);
        }
        // 
        $view = $request->view;
        $pic = $request->pic;
        $nik = $request->nik;
        $id_status = $request->id_status;
        $where = ['id_trx' => $id, 'nik_pic' => $nik];
        // 2 = Approve by L1 Requestor
        // 3 = Reject by L1 Requestor
        $nAtasanPengaju = TrxPelatihanLog::where($where)->whereIn('id_status', [2, 3])->count();
        // 5 = Approve by L1 Participants
        // 6 = Reject by L1 Participants
        $nAtasanPeserta = TrxPelatihanLog::where($where)->whereIn('id_status', [5, 6])->count();
        // Reviewer1 & Reviewer2 adalah paralel
        // 10 = Approve by Reviewer 2
        // 8 = Reject by Reviewer 2
        $nReviewer1 = TrxPelatihanLog::where($where)->whereIn('id_status', [10, 8])->count();
        // 14 = Approve by Reviewer 3
        // 12 = Reject by Reviewer 3
        $nReviewer2 = TrxPelatihanLog::where($where)->whereIn('id_status', [14, 12])->count();
        // 
        switch ($view) {
            case 'approval':
                // atasan_pengaju - 1 = Submit by Requestor
                if ($pic == 'atasan_pengaju' && $nik == $trx->atasan_pengaju && $nAtasanPengaju == 0 && $id_status == 1) {
                    // 2 = Approve by L1 Requestor
                    // 3 = Reject by L1 Requestor
                    $approve = 2;
                    $reject = 3;
                    return view('emails.pelatihan', compact('trx', 'nik', 'pic', 'approve', 'reject', 'pengaju', 'arrPeserta', 'arrJenis', 'arrBenefit', 'linkAction', 'history'));
                } 
                // atasan_peserta - 2 = Approve by L1 Requestor
                else if ($pic == 'atasan_peserta' && in_array($nik, explode(';', $trx->atasan_peserta)) && $nAtasanPeserta == 0 && $id_status == 2) {
                    // 5 = Approve by L1 Participants
                    // 6 = Reject by L1 Participants
                    $approve = 5;
                    $reject = 6;
                    return view('emails.pelatihan', compact('trx', 'nik', 'pic', 'approve', 'reject', 'pengaju', 'arrPeserta', 'arrJenis', 'arrBenefit', 'linkAction', 'history'));
                } 
                // reviewer1 - 5 = Approve by L1 Participants
                else if ($pic == 'reviewer1' && $nik == $trx->reviewer1 && $nReviewer1 == 0 && $id_status == 5) {
                    // 10 = Approve by Reviewer 2
                    // 8 = Reject by Reviewer 2
                    $approve = 10;
                    $reject = 8;
                    return view('emails.pelatihan', compact('trx', 'nik', 'pic', 'approve', 'reject', 'pengaju', 'arrPeserta', 'arrJenis', 'arrBenefit', 'linkAction', 'history'));
                } 
                // reviewer2 - 5 = Approve by L1 Participants
                else if ($pic == 'reviewer2' && $nik == $trx->reviewer2 && $nReviewer2 == 0 && $id_status == 5) {
                    // 14 = Approve by Reviewer 3
                    // 12 = Reject by Reviewer 3
                    $approve = 14;
                    $reject = 12;
                    return view('emails.pelatihan', compact('trx', 'nik', 'pic', 'approve', 'reject', 'pengaju', 'arrPeserta', 'arrJenis', 'arrBenefit', 'linkAction', 'history'));
                } 
                // selainnya
                else {
                    return view('emails.pelatihan_ok');
                }
                break;
            case 'detail':
                $this->authorize('auth');
                $where = ['id_trx' => $id];
                $history = TrxPelatihanLog::where($where)->orderBy('created_at', 'desc')->get();
                $jenis = PelatihanJenis::orderBy('jenis', 'asc')->get();
                $anggaran = PelatihanAnggaran::orderBy('anggaran', 'asc')->get();
                $benefit = PelatihanBenefit::orderBy('benefit', 'asc')->get();
                return view('formulir.pelatihan.view', compact('trx', 'pengaju', 'arrPeserta', 'arrAtasanPeserta', 'history', 'arrJenis', 'arrBenefit', 'jenis', 'anggaran', 'benefit'));
                break;
            case 'pdf':
                $this->authorize('auth');
                // print to pdf
                $waktu = date('YmdHis');
                $no = 0;
                // start
                $arr_htmlLog = array();
                $htmlLog = '<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">';
                // pengaju
                $where = ['id_trx' => $id, 'nik_pic' => $trx->pengaju, 'id_status' => 1];
                $pelatihanLog = TrxPelatihanLog::where($where)->orderBy('id', 'desc')->first();
                $_htmlLog = '<td>Mengajukan<br/><img src="' . public_path('assets/images/matta/requested_new.png') . '" height="30" /><br/>' . $pengaju['nama'] . '<br/>' . $pengaju['jabatan'] . '<br/>' . $pelatihanLog->created_at . '</td>';
                array_push($arr_htmlLog, $_htmlLog);
                // atasan_pengaju
                $where = ['id_trx' => $id, 'nik_pic' => $trx->atasan_pengaju];
                $pelatihanLog = TrxPelatihanLog::where($where)->whereIn('id_status', [2, 3])->orderBy('id', 'desc')->first();
                // 2 = Menyetujui
                // 3 = Menolak
                if (!empty($pelatihanLog)) {
                    if($pelatihanLog->id_status == 2){
                        $_htmlLog = '<td>Menyetujui<br/><img src="' . public_path('assets/images/matta/approved_new.png') . '" height="30" /><br/>' . $atasan_pengaju['nama'] . '<br/>' . $atasan_pengaju['jabatan'] . '<br/>' . $pelatihanLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }else{
                        $_htmlLog = '<td>Menolak<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $atasan_pengaju['nama'] . '<br/>' . $atasan_pengaju['jabatan'] . '<br/>' . $pelatihanLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }
                }
                // atasan_peserta
                $atasan_peserta = array();
                foreach (explode(';', $trx->atasan_peserta_property) as $value){
                    if(!in_array($value, $atasan_peserta)){
                        array_push($atasan_peserta, $value);
                    }
                }
                foreach ($atasan_peserta as $value){
                    $_atasan_peserta = json_decode($value, true);
                    $where = ['id_trx' => $id, 'nik_pic' => $_atasan_peserta['nik']];
                    $pelatihanLog = TrxPelatihanLog::where($where)->whereIn('id_status', [5,6])->orderBy('id', 'desc')->first();
                    // 5 = Menyetujui
                    // 6 = Menolak
                    if (!empty($pelatihanLog)) {
                        if($pelatihanLog->id_status == 5){
                            $_htmlLog = '<td>Menyetujui<br/><img src="' . public_path('assets/images/matta/approved_new.png') . '" height="30" /><br/>' . $_atasan_peserta['nama'] . '<br/>' . $_atasan_peserta['jabatan'] . '<br/>' . $pelatihanLog->created_at . '</td>';
                            array_push($arr_htmlLog, $_htmlLog);
                        }else{
                            $_htmlLog = '<td>Menolak<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $_atasan_peserta['nama'] . '<br/>' . $_atasan_peserta['jabatan'] . '<br/>' . $pelatihanLog->created_at . '</td>';
                            array_push($arr_htmlLog, $_htmlLog);
                        }
                    }
                }
                // reviewer1
                $where = ['id_trx' => $id, 'nik_pic' => $trx->reviewer1];
                $pelatihanLog = TrxPelatihanLog::where($where)->whereIn('id_status', [10, 8])->orderBy('id', 'desc')->first();
                // 10 = Menyetujui
                // 8 = Menolak
                if (!empty($pelatihanLog)) {
                    if($pelatihanLog->id_status == 10){
                        $_htmlLog = '<td>Menyetujui<br/><img src="' . public_path('assets/images/matta/approved_new.png') . '" height="30" /><br/>' . $reviewer1['nama'] . '<br/>' . $reviewer1['jabatan'] . '<br/>' . $pelatihanLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }else{
                        $_htmlLog = '<td>Menolak<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $reviewer1['nama'] . '<br/>' . $reviewer1['jabatan'] . '<br/>' . $pelatihanLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }
                }
                // reviewer2
                $where = ['id_trx' => $id, 'nik_pic' => $trx->reviewer2];
                $pelatihanLog = TrxPelatihanLog::where($where)->whereIn('id_status', [14, 12])->orderBy('id', 'desc')->first();
                // 14 = Menyetujui
                // 12 = Menolak
                if (!empty($pelatihanLog)) {
                    if($pelatihanLog->id_status == 14){
                        $_htmlLog = '<td>Menyetujui<br/><img src="' . public_path('assets/images/matta/approved_new.png') . '" height="30" /><br/>' . $reviewer2['nama'] . '<br/>' . $reviewer2['jabatan'] . '<br/>' . $pelatihanLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }else{
                        $_htmlLog = '<td>Menolak<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $reviewer2['nama'] . '<br/>' . $reviewer2['jabatan'] . '<br/>' . $pelatihanLog->created_at . '</td>';
                        array_push($arr_htmlLog, $_htmlLog);
                    }
                }
                // hco
                $where = ['id_trx' => $id];
                $pelatihanLog = TrxPelatihanLog::where($where)->whereIn('id_status', [11])->orderBy('id', 'desc')->first();
                if (!empty($pelatihanLog)) {
                    $_htmlLog = '<td>Mengetahui<br/><img src="' . public_path('assets/images/matta/informed_new.png') . '" height="30" /><br/>' . $pelatihanLog->_pic->nama . '<br/>' . $pelatihanLog->_pic->_jabatan->jabatan . '<br/>' . $pelatihanLog->created_at . '</td>';
                    array_push($arr_htmlLog, $_htmlLog);
                }
                // finish
                // start looping satu baris terdiri dari $pembagi
                $pembagi = 4;
                $jumlah = count($arr_htmlLog);
                $sisa = $jumlah % $pembagi;
                $hasil = ($jumlah - $sisa) / $pembagi;
                if($hasil > 0){
                    for ($y = 0; $y < $hasil; $y++) {
                        $htmlLog .= '<tr>';
                        for ($x = $y*$pembagi; $x < ($y+1)*$pembagi; $x++) {
                            $htmlLog .= $arr_htmlLog[$x];
                        }
                        $htmlLog .= '</tr>';
                    }
                    if($sisa>0){
                        $htmlLog .= '<tr>';
                        for ($x = $hasil*$pembagi; $x < $jumlah; $x++) {
                            $htmlLog .= $arr_htmlLog[$x];
                        }
                        $htmlLog .= '</tr>';
                    }
                }else{
                    $htmlLog .= '<tr>';
                    foreach($arr_htmlLog as $value){
                        $htmlLog .= $value;
                    }
                    $htmlLog .= '</tr>';
                }
                $htmlLog .= '</table>';
                // end looping satu baris terdiri dari $pembagi
                // 
                $filename = $waktu . '_formulir_pelatihan_karyawan.pdf';
                $view = \View::make('formulir.pelatihan.pdf', compact('trx', 'pengaju', 'arrPeserta', 'arrJenis', 'arrBenefit', 'htmlLog'));
                $html = $view->render();
                $pdf = new TCPDF;
                $pdf::SetTitle('Formulir Pengajuan Pelatihan');
                $pdf::SetFont('times', '', 10, '', true);
                $pdf::AddPage();
                $pdf::writeHTML($html, true, false, true, false, '');
                $pdf::Output(public_path($filename), 'I');
                return response()->download(public_path($filename));
                break;
            default:
                return view('emails.pelatihan_ok');
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
        $this->authorize('hc');
        $where = ['id_trx' => $id];
        $history = TrxPelatihanLog::where($where)->orderBy('created_at', 'desc')->get();
        $trx = TrxPelatihan::find( $id );
        // 
        $status_pengajuan = PelatihanStatus::whereIn('id', [11,$trx->status_pengajuan])->orderBy('id', 'asc')->get();
        // return response()->json(['status_pengajuan' => $status_pengajuan]);die;
        // 
        // ambil property para pihak
        $pengaju = json_decode($trx->pengaju_property, true);
        $atasan_pengaju = json_decode($trx->atasan_pengaju_property, true);
        // 
        $arr_peserta = explode(';', $trx->peserta_property);
        $arrPeserta = array();
        foreach ($arr_peserta as $value) {
            array_push($arrPeserta, json_decode($value, true));
        }
        // 
        $arr_atasan_peserta = explode(';', $trx->atasan_peserta_property);
        $arrAtasanPeserta = array();
        foreach ($arr_atasan_peserta as $value) {
            array_push($arrAtasanPeserta, json_decode($value, true));
        }
        // 
        $reviewer1 = '';
        $reviewer2 = '';
        if(!empty($trx->reviewer1)){
            $reviewer1 = json_decode($trx->reviewer1_property, true);
        }
        if(!empty($trx->reviewer2)){
            $reviewer2 = json_decode($trx->reviewer2_property, true);
        }
        return view( 'formulir.pelatihan.update', compact( 'trx', 'pengaju', 'atasan_pengaju', 'arrPeserta', 'arrAtasanPeserta', 'reviewer1', 'reviewer2', 'status_pengajuan', 'history' ) );
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
        // masih di hardcode pic hco -> Khatrin Rismasany
        // $hco = '01031908799';
        // $hco = auth()->user()->nik;
		$hco = explode('/', MtrUtility::find(29)->informasi);
        $trx = TrxPelatihan::find( $id );
        $pengaju = json_decode($trx->pengaju_property, true);
        // 
        $pic = $request->pic;
        $nik = $request->nik;
        $status_pengajuan = $request->status_pengajuan;
        // 2 = Approve by L1 Requestor
        // next to atasan_peserta
        if ($status_pengajuan == 2 && $pic == 'atasan_pengaju' && $nik == $trx->atasan_pengaju) {
            $trx->status_pengajuan = $status_pengajuan;
            $trx->update();
            $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$nik, 'pic'=>$pic, 'filename'=>'emails.pelatihan0']);
            $this->insertLog($id, $status_pengajuan, $nik, $request->keterangan);
            $atasan_peserta = array();
            foreach (explode(';', $trx->atasan_peserta_property) as $value){
                if(!in_array($value, $atasan_peserta)){
                    array_push($atasan_peserta, $value);
                }
            }
            // start
            // parameter yang dipassing ke sendemail
            $mailData['title'] = 'Formulir Pengajuan Pelatihan';
            $mailData['filename'] = 'emails.pelatihan0';
            foreach ($atasan_peserta as $value) {
                $_atasan_peserta = json_decode($value, true);
                $mailData['data'] = ['nama'=>$pengaju['nama'].'/'.$pengaju['nik'], 'jabatan'=>$pengaju['jabatan'], 'unit_kerja'=>$pengaju['divisi'].'/'.$pengaju['dep'].'/'.$pengaju['sub_dep'], 'link'=> route('pelatihan.show', [$trx->id, 'view' => 'approval', 'nik' => $_atasan_peserta['nik'], 'id_status' => $status_pengajuan, 'pic' => 'atasan_peserta']), 'trx'=>$trx ];
                $mailData['to'] = $_atasan_peserta['email'];
                $todolist = ['id_trx' => $trx->id, 'nik' => $_atasan_peserta['nik'], 'id_status' => $status_pengajuan, 'pic' => 'atasan_peserta'];
                $this->insertToDoList($todolist, $mailData);
                $this->sendEmail($mailData);
            }
            // check para approver, jika sudah semua kirimkan email ke pengaju
            $this->checkApprovalLine($id);
            // finish
            return view('emails.pelatihan_ok');
        }
        // 3 = Reject by L1 Requestor
        // next to hco
        else if ($status_pengajuan == 3 && $pic == 'atasan_pengaju' && $nik == $trx->atasan_pengaju) {
            $trx->status_pengajuan = $status_pengajuan;
            $trx->update();
            $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$nik, 'pic'=>$pic, 'filename'=>'emails.pelatihan0']);
            $this->insertLog($id, $status_pengajuan, $nik, $request->keterangan);
            // start
            // parameter yang dipassing ke sendemail
            $mailData['title'] = 'Formulir Pengajuan Pelatihan';
            $mailData['filename'] = 'emails.pelatihan1';
            $mailData['data'] = ['nama'=>$pengaju['nama'], 'jabatan'=>$pengaju['jabatan'], 'unit_kerja'=>$pengaju['divisi'].'/'.$pengaju['dep'].'/'.$pengaju['sub_dep'], 'trx'=>$trx];
            $mailData['to'] = explode(',', MtrUtility::find(1)->informasi);
            $this->sendEmail($mailData);
            // check para approver, jika sudah semua kirimkan email ke pengaju
            $this->checkApprovalLine($id);
            // finish
            return view('emails.pelatihan_ok');
        }
        // 6 = Reject by L1 Participants
        // next to hco
        else if ($status_pengajuan == 6 && $pic == 'atasan_peserta' && in_array($nik, explode(';', $trx->atasan_peserta))) {
            $trx->status_pengajuan = $status_pengajuan;
            $trx->update();
            $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$nik, 'pic'=>$pic, 'filename'=>'emails.pelatihan0']);
            $this->insertLog($id, $status_pengajuan, $nik, $request->keterangan);
            // start
            // parameter yang dipassing ke sendemail
            $mailData['title'] = 'Formulir Pengajuan Pelatihan';
            $mailData['filename'] = 'emails.pelatihan1';
            $mailData['data'] = ['nama'=>$pengaju['nama'], 'jabatan'=>$pengaju['jabatan'], 'unit_kerja'=>$pengaju['divisi'].'/'.$pengaju['dep'].'/'.$pengaju['sub_dep'], 'trx'=>$trx];
            $mailData['to'] = explode(',', MtrUtility::find(1)->informasi);
            $this->sendEmail($mailData);
            // check para approver, jika sudah semua kirimkan email ke pengaju
            $this->checkApprovalLine($id);
            // finish
            return view('emails.pelatihan_ok');
        }
        // 8 = Reject by Reviewer 2
        // 10 = Approve by Reviewer 2
        // matta lama masih berlabel Reviewer 2 di matta reborn menjadi reviewer1
        // next to hco
        else if (($status_pengajuan == 8 || $status_pengajuan == 10) && $pic == 'reviewer1' && $nik == $trx->reviewer1) {
            $trx->status_pengajuan = $status_pengajuan;
            $trx->update();
            $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$nik, 'pic'=>$pic, 'filename'=>'emails.pelatihan0']);
            $this->insertLog($id, $status_pengajuan, $nik, $request->keterangan);
            // start
            // parameter yang dipassing ke sendemail
            $mailData['title'] = 'Formulir Pengajuan Pelatihan';
            $mailData['filename'] = 'emails.pelatihan1';
            $mailData['data'] = ['nama'=>$pengaju['nama'], 'jabatan'=>$pengaju['jabatan'], 'unit_kerja'=>$pengaju['divisi'].'/'.$pengaju['dep'].'/'.$pengaju['sub_dep'], 'trx'=>$trx];
            $mailData['to'] = explode(',', MtrUtility::find(1)->informasi);
            $this->sendEmail($mailData);
            // check para approver, jika sudah semua kirimkan email ke pengaju
            $this->checkApprovalLine($id);
            // finish
            return view('emails.pelatihan_ok');
        }
        // 12 = Reject by Reviewer 3
        // 14 = Approve by Reviewer 3
        // matta lama masih berlabel Reviewer 3 di matta reborn menjadi reviewer2
        // next to hco
        else if (($status_pengajuan == 12 || $status_pengajuan == 14) && $pic == 'reviewer2' && $nik == $trx->reviewer2) {
            $trx->status_pengajuan = $status_pengajuan;
            $trx->update();
            $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$nik, 'pic'=>$pic, 'filename'=>'emails.pelatihan0']);
            $this->insertLog($id, $status_pengajuan, $nik, $request->keterangan);
            // start
            // parameter yang dipassing ke sendemail
            $mailData['title'] = 'Formulir Pengajuan Pelatihan';
            $mailData['filename'] = 'emails.pelatihan1';
            $mailData['data'] = ['nama'=>$pengaju['nama'], 'jabatan'=>$pengaju['jabatan'], 'unit_kerja'=>$pengaju['divisi'].'/'.$pengaju['dep'].'/'.$pengaju['sub_dep'], 'trx'=>$trx];
            $mailData['to'] = explode(',', MtrUtility::find(1)->informasi);
            $this->sendEmail($mailData);
            // check para approver, jika sudah semua kirimkan email ke pengaju
            $this->checkApprovalLine($id);
            // finish
            return view('emails.pelatihan_ok');
        }
        // 5 = Approve by L1 Participants
        else if ($status_pengajuan == 5 && $pic == 'atasan_peserta' && in_array($nik, explode(';', $trx->atasan_peserta))) {
            $trx->status_pengajuan = $status_pengajuan;
            $trx->update();
            $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$nik, 'pic'=>$pic, 'filename'=>'emails.pelatihan0']);
            $this->insertLog($id, $status_pengajuan, $nik, $request->keterangan);
            $where = ['id_trx' => $id];
            $nAtasanPeserta = TrxPelatihanLog::where($where)->whereIn('id_status', [5])->count();
            if($nAtasanPeserta == 1){
                // reviewer1 || reviewer2
                if(!empty($trx->reviewer1) || !empty($trx->reviewer2)){
                    if(!empty($trx->reviewer1)){
                        $reviewer1 = json_decode($trx->reviewer1_property, true);
                        // start
                        // parameter yang dipassing ke sendemail
                        $mailData['title'] = 'Formulir Pengajuan Pelatihan';
                        $mailData['filename'] = 'emails.pelatihan0';
                        $mailData['data'] = ['nama'=>$pengaju['nama'].'/'.$pengaju['nik'], 'jabatan'=>$pengaju['jabatan'], 'unit_kerja'=>$pengaju['divisi'].'/'.$pengaju['dep'].'/'.$pengaju['sub_dep'], 'link'=> route('pelatihan.show', [$trx->id, 'view' => 'approval', 'nik' => $reviewer1['nik'], 'id_status' => $status_pengajuan, 'pic' => 'reviewer1']), 'trx'=>$trx ];
                        $mailData['to'] = $reviewer1['email'];
                        $todolist = ['id_trx' => $trx->id, 'nik' => $reviewer1['nik'], 'id_status' => $status_pengajuan, 'pic' => 'reviewer1'];
                        $this->insertToDoList($todolist, $mailData);
                        $this->sendEmail($mailData);
                        // check para approver, jika sudah semua kirimkan email ke pengaju
                        $this->checkApprovalLine($id);
                        // finish
                    }
                    if(!empty($trx->reviewer2)){
                        $reviewer2 = json_decode($trx->reviewer2_property, true);
                        // start
                        // parameter yang dipassing ke sendemail
                        $mailData['title'] = 'Formulir Pengajuan Pelatihan';
                        $mailData['filename'] = 'emails.pelatihan0';
                        $mailData['data'] = ['nama'=>$pengaju['nama'].'/'.$pengaju['nik'], 'jabatan'=>$pengaju['jabatan'], 'unit_kerja'=>$pengaju['divisi'].'/'.$pengaju['dep'].'/'.$pengaju['sub_dep'], 'link'=> route('pelatihan.show', [$trx->id, 'view' => 'approval', 'nik' => $reviewer2['nik'], 'id_status' => $status_pengajuan, 'pic' => 'reviewer2']), 'trx'=>$trx ];
                        $mailData['to'] = $reviewer2['email'];
                        $todolist = ['id_trx' => $trx->id, 'nik' => $reviewer2['nik'], 'id_status' => $status_pengajuan, 'pic' => 'reviewer2'];
                        $this->insertToDoList($todolist, $mailData);
                        $this->sendEmail($mailData);
                        // check para approver, jika sudah semua kirimkan email ke pengaju
                        $this->checkApprovalLine($id);
                        // finish
                    }
                }else{
                    // start
                    // parameter yang dipassing ke sendemail
                    $mailData['title'] = 'Formulir Pengajuan Pelatihan';
                    $mailData['filename'] = 'emails.pelatihan1';
                    $mailData['data'] = ['nama'=>$pengaju['nama'], 'jabatan'=>$pengaju['jabatan'], 'unit_kerja'=>$pengaju['divisi'].'/'.$pengaju['dep'].'/'.$pengaju['sub_dep'], 'trx'=>$trx];
                    $mailData['to'] = explode(',', MtrUtility::find(1)->informasi);
                    $this->sendEmail($mailData);
                    // check para approver, jika sudah semua kirimkan email ke pengaju
                    $this->checkApprovalLine($id);
                    // finish
                }
            }else{
                // check para approver, jika sudah semua kirimkan email ke pengaju
                $this->checkApprovalLine($id);
                // finish
            }
        }
        // 11 = Done by HC
        else if ($status_pengajuan == 11) {
            $trx->status_pengajuan = $status_pengajuan;
            // 
            $trx->update();
            $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$hco[0], 'pic'=>'hco', 'filename'=>'emails.pelatihan0']);
            $this->insertLog($id, $status_pengajuan, $hco[0], 'Done by HC');
            // 
            return redirect('pelatihan')->with('msg', 'Data is saved successfully.');
        }
        // 
        return view('emails.pelatihan_ok');
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

    public function save_update(Request $request, $id)
    {
        // dd($request->all(), $id);
        $this->authorize('hc');
        $back_url = $request->back_url;
        $trx = TrxPelatihan::find( $id );
        $trx->tahun = $request->tahun;
        $trx->jenis = implode(';', $request->jenis);
        $trx->judul = $request->judul;
        $trx->penyelenggara = $request->penyelenggara;
        $trx->tanggal_mulai = $request->tanggal_mulai;
        $trx->tanggal_akhir = $request->tanggal_akhir;
        $trx->tempat = $request->tempat;
        $trx->biaya = str_replace(',', '', $request->biaya);
        $trx->benefit = implode(';', $request->benefit);
        $trx->anggaran = $request->anggaran;
        $trx->keterangan = $request->keterangan;
        $trx->update();
        return redirect()->to($back_url)->with( 'msg', 'Data is saved successfully.' );
    }
}
