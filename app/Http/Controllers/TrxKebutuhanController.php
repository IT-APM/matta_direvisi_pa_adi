<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
// 
use App\Models\Karyawan;
use App\Models\Jabatan;
use App\Models\Level;
use App\Models\Perusahaan;
use App\Models\Divisi;
use App\Models\Departemen;
use App\Models\SubDepartemen;
// 
use App\Models\MtrFindimProject;
use App\Models\MtrFindimDivisi;
use App\Models\MtrFindimDepartemen;
use App\Models\MtrFindimSubdepartemen;
// 
use App\Models\Kelamin;
use App\Models\Propinsi;
use App\Models\PropinsiKota;
use App\Models\JenisPermintaan;
use App\Models\PendidikanJenjang;
use App\Models\PendidikanJurusan;
use App\Models\PelatihanSub;
use App\Models\SertifikasiSub;
use App\Models\KeterampilanSub;
use App\Models\KepribadianSub;
use App\Models\StatusKerja;
use App\Models\PekerjaanSub;
use App\Models\Pengalaman;
use App\Models\KebutuhanStatus;
use App\Models\KebutuhanNikKecuali;
use App\Models\TrxKebutuhanMaster;
use App\Models\TrxKebutuhanMasterEdit;
use App\Models\TrxKebutuhanDetail;
use App\Models\TrxKebutuhanLog;
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
use Illuminate\Support\Facades\Crypt;
// 

class TrxKebutuhanController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:asm', ['except' => ['show','update','kota']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $no = 0;
        $karyawan = Karyawan::select('nik', 'nama')->where(['is_aktif' => 1])->where('kode_level', '>=', 4)->whereNotIn('nik', ['Hco.all','IT.BM','IT Infras'])->orderBy('nama', 'asc')->get();
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        $trx = TrxKebutuhanMaster::orderBy('id', 'desc')->get();
        if(Gate::allows('hc_nkp')){
            $trx = TrxKebutuhanMaster::whereIn('pengaju',$arr_nik_nkp)->orderBy('id', 'desc')->get();
        }
        // foreach ($trx as $value) {
        //     $trxM = TrxKebutuhanMaster::find($value->id);
        //     // $trxM->pengaju_property = $this->getNikProperty($value->pengaju);
        //     // $trxM->layer1_property = $this->getNikProperty($value->layer1);
        //     // $trxM->layer2_property = $this->getNikProperty($value->layer2);
        //     $pengaju_property = json_decode($trxM->pengaju_property, true);
        //     $id_sub_departemen = $pengaju_property['id_sub_departemen'];
        //     $id_dep_kandidat = SubDepartemen::find($id_sub_departemen)->kode_departemen;
        //     $id_divisi_kandidat = Departemen::find($id_dep_kandidat)->kode_divisi;
        //     $trxM->id_sub_dep_kandidat = $id_sub_departemen;
        //     $trxM->id_dep_kandidat = $id_dep_kandidat;
        //     $trxM->id_divisi_kandidat = $id_divisi_kandidat;
        //     // dd($id_sub_departemen);
        //     $trxM->update();
        // }
        if(auth()->user()->userrole == 'nhc'){
            $where = ['pengaju' => auth()->user()->nik];
            $trx = TrxKebutuhanMaster::where($where)->orderBy('id', 'desc')->get();
            // 
            $arr_id = [];
            foreach($trx as $_trx){
                array_push($arr_id, $_trx->id);
            }
            $where = ['nik_pic' => auth()->user()->nik];
            $trx = TrxKebutuhanLog::where($where)->orderBy('id_trx', 'desc')->get();
            foreach($trx as $_trx){
                array_push($arr_id, $_trx->id_trx);
            }
            // 
            $trx = TrxKebutuhanMaster::whereIn('id', $arr_id)->orderBy('id', 'desc')->get();
        }
        // filter parameter
        $arr_status_pengajuan = array();
        $arr_id_area = array();
        $arr_id_jenis_permintaan = array();
        foreach ($trx as $data){
            array_push($arr_status_pengajuan, $data->status_pengajuan);
            array_push($arr_id_area, $data->id_area);
            array_push($arr_id_jenis_permintaan, $data->id_jenis_permintaan);
        }
        $status_pengajuan = KebutuhanStatus::whereIn('id', $arr_status_pengajuan)->orderBy('id', 'asc')->get();
        $id_area = Propinsi::whereIn('id', $arr_id_area)->orderBy('order_by', 'asc')->get();
        $id_jenis_permintaan = JenisPermintaan::whereIn('id', $arr_id_jenis_permintaan)->orderBy('id', 'asc')->get();
        // start filter
        if($request->filter == true){
            $_status_pengajuan = $request->input('status_pengajuan', []);
            $_id_area = $request->input('id_area', []);
            $_id_jenis_permintaan = $request->input('id_jenis_permintaan', []);
            if(count($_status_pengajuan) > 0){
                $arr_status_pengajuan = $_status_pengajuan;
            }
            else{
                $arr_status_pengajuan = array();
            }
            if(count($_id_area) > 0){
                $arr_id_area = $_id_area;
            }
            else{
                $arr_id_area = array();
            }
            if(count($_id_jenis_permintaan) > 0){
                $arr_id_jenis_permintaan = $_id_jenis_permintaan;
            }
            else{
                $arr_id_jenis_permintaan = array();
            }
            // return response()->json(['trx' => $request->input('status_pengajuan', [])]);
            $trx = TrxKebutuhanMaster::whereIn('status_pengajuan', $arr_status_pengajuan)->whereIn('id_area', $arr_id_area)->whereIn('id_jenis_permintaan', $arr_id_jenis_permintaan)->orderBy('id', 'desc')->get();
            if(Gate::allows('hc_nkp')){
                $trx = TrxKebutuhanMaster::whereIn('pengaju',$arr_nik_nkp)->whereIn('status_pengajuan', $arr_status_pengajuan)->whereIn('id_area', $arr_id_area)->whereIn('id_jenis_permintaan', $arr_id_jenis_permintaan)->orderBy('id', 'desc')->get();
            }
            if(auth()->user()->userrole == 'nhc'){
                $where = ['pengaju' => auth()->user()->nik];
                $trx = TrxKebutuhanMaster::whereIn('status_pengajuan', $arr_status_pengajuan)->whereIn('id_area', $arr_id_area)->whereIn('id_jenis_permintaan', $arr_id_jenis_permintaan)->where($where)->orderBy('id', 'desc')->get();
                // 
                $arr_id = [];
                foreach($trx as $_trx){
                    array_push($arr_id, $_trx->id);
                }
                $where = ['nik_pic' => auth()->user()->nik];
                $trx = TrxKebutuhanLog::where($where)->orderBy('id_trx', 'desc')->get();
                foreach($trx as $_trx){
                    array_push($arr_id, $_trx->id_trx);
                }
                // 
                $trx = TrxKebutuhanMaster::whereIn('id', $arr_id)->whereIn('status_pengajuan', $arr_status_pengajuan)->whereIn('id_area', $arr_id_area)->whereIn('id_jenis_permintaan', $arr_id_jenis_permintaan)->orderBy('id', 'desc')->get();
            }
        }else{
            $arr_status_pengajuan = array();
            $arr_id_area = array();
            $arr_id_jenis_permintaan = array();
        }
        // end filter
        return view('formulir.kebutuhan.index', compact('trx', 'no', 'status_pengajuan', 'id_area', 'id_jenis_permintaan', 'arr_status_pengajuan', 'arr_id_area', 'arr_id_jenis_permintaan', 'karyawan'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $nik_pengaju = Crypt::decrypt($request->nik_pengaju);
        $pengaju = Karyawan::where(['nik' => $nik_pengaju])->first();
        // $pengaju = $pengaju->nama.' - '.$pengaju->nik;
        // 
        $pekerjaan = PekerjaanSub::orderBy('pekerjaan', 'asc')->get();
        $level = Level::orderBy('id', 'asc')->get();
        $status_kerja = StatusKerja::orderBy('status_kerja', 'asc')->get();
        $kelamin = Kelamin::orderBy('id', 'asc')->get();
        $area = Propinsi::orderBy('order_by', 'asc')->get();
        $kota = PropinsiKota::orderBy('kota', 'asc')->get();
        $jenis_permintaan = JenisPermintaan::orderBy('jenis', 'asc')->get();
        $jenjang_pendidikan = PendidikanJenjang::orderBy('id', 'asc')->get();
        $jurusan_pendidikan = PendidikanJurusan::orderBy('jurusan', 'asc')->get();
        $pengalaman_kerja = Pengalaman::orderBy('id', 'asc')->get();
        $pelatihan = PelatihanSub::orderBy('sub_pelatihan', 'asc')->get();
        $sertifikasi = SertifikasiSub::orderBy('sub_sertifikasi', 'asc')->get();
        $keterampilan = KeterampilanSub::orderBy('sub_keterampilan', 'asc')->get();
        $kepribadian = KepribadianSub::orderBy('sub_kepribadian', 'asc')->get();
        $where = ['is_aktif' => 1];
        $karyawan = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
        $findim_project = MtrFindimProject::all();
        $findim_divisi = MtrFindimDivisi::all();
        $findim_departemen = MtrFindimDepartemen::all();
        $findim_subdepartemen = MtrFindimSubdepartemen::all();
        // atasan langsung dengan level minimal 3 = Supervisor (SPV)
        $atasan = Karyawan::select('nik', 'nama')->where(['is_aktif' => 1])->where('kode_level','>=',3)->orderBy('nama', 'asc')->get();
        return view('formulir.kebutuhan.insert', compact('karyawan', 'pekerjaan', 'level', 'status_kerja', 'kelamin', 'area', 'kota', 'jenis_permintaan', 'jenjang_pendidikan', 'jurusan_pendidikan', 'pengalaman_kerja', 'pelatihan', 'sertifikasi', 'keterampilan', 'kepribadian', 'findim_project', 'findim_divisi', 'findim_departemen', 'findim_subdepartemen', 'atasan', 'nik_pengaju', 'pengaju'));
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
        // karyawan masih di hardcode
        // Andika Cahya Fajar. R
        // $pengaju = '01031406353';
        // $pengaju = auth()->user()->nik;
        $pengaju = $request->nik_pengaju;
        $layer1 = $request->layer1;
        $layer2 = $request->layer2;
        $atasan = $request->atasan;
        // 
        $pengaju_property = $this->getNikProperty($pengaju);
        $layer1_property = $this->getNikProperty($layer1);
        $layer2_property = $this->getNikProperty($layer2);
        $atasan_property = $this->getNikProperty($atasan);
        // 
        $trxM = new TrxKebutuhanMaster;
        $trxM->pengaju = $pengaju;
        $trxM->layer1 = $layer1;
        $trxM->layer2 = $layer2;
        $trxM->atasan = $atasan;
        $trxM->id_divisi_kandidat = $request->kode_divisi;
        $trxM->id_dep_kandidat = $request->kode_departemen;
        $trxM->id_sub_dep_kandidat = $request->kode_sub_departemen;
        $trxM->pengaju_property = $pengaju_property;
        $trxM->layer1_property = $layer1_property;
        $trxM->layer2_property = $layer2_property;
        $trxM->atasan_property = $atasan_property;
        // 
        $id_pekerjaan = $request->id_pekerjaan;
        $trxM->id_pekerjaan = $id_pekerjaan;
        $trxM->tugas = $request->tugas;
        $trxM->wewenang = $request->wewenang;
        $trxM->tanggung_jawab = $request->tanggung_jawab;
        // 
        $trxM->id_level = $request->id_level;
        $trxM->id_status_kerja = $request->id_status_kerja;
        $trxM->waktu_kontrak = $request->waktu_kontrak;
        // 
        $jumlah = $request->jumlah;
        $trxM->jumlah = $jumlah;
        // 
        $trxM->id_kelamin = $request->id_kelamin;
        $trxM->id_area = $request->id_area;
        $trxM->id_kota = $request->id_kota;
        $trxM->id_jenis_permintaan = $request->id_jenis_permintaan;
        // $_nik_karyawan = $request->input('nik_karyawan', []);
        $nik_karyawan = explode(',', $request->_nik_karyawan_);
        $trxM->nik_karyawan = implode(';', $nik_karyawan);
        $nik_karyawan_property = array();
        foreach ($nik_karyawan as $value) {
            array_push($nik_karyawan_property, $this->getNikProperty($value));
        }
        $trxM->nik_karyawan_property = implode(';', $nik_karyawan_property);
        $trxM->penjelasan = $request->penjelasan;
        $trxM->id_rencana = $request->id_rencana;
        // 
        $id_jenjang_pendidikan = $request->id_jenjang_pendidikan;
        $trxM->id_jenjang_pendidikan = $id_jenjang_pendidikan;
        // 
        $trxM->id_jurusan_pendidikan = $request->id_jurusan_pendidikan;
        $trxM->nilai_ipk = $request->nilai_ipk;
        $trxM->umur_minimum = $request->umur_minimum;
        $trxM->umur_maksimum = $request->umur_maksimum;
        $trxM->id_pengalaman = $request->id_pengalaman;
        // 
        if($request->has('id_pelatihan')){
            $trxM->id_pelatihan = implode(',', $request->id_pelatihan);
        }
        if($request->has('id_sertifikasi')){
            $trxM->id_sertifikasi = implode(',', $request->id_sertifikasi);
        }
        if($request->has('id_keterampilan')){
            $trxM->id_keterampilan = implode(',', $request->id_keterampilan);
        }
        if($request->has('id_kepribadian')){
            $trxM->id_kepribadian = implode(',', $request->id_kepribadian);
        }
        $trxM->tanggal = $request->tanggal;
        $trxM->keterangan_lain = $request->keterangan_lain;
        $trxM->id_penambahan = $request->id_penambahan;
        $status_pengajuan = 1;
        $trxM->status_pengajuan = $status_pengajuan;
        $trxM->findim_project = $request->findim_project;
        $trxM->findim_divisi = $request->findim_divisi;
        $trxM->findim_departemen = $request->findim_departemen;
        $trxM->findim_subdepartemen = $request->findim_subdepartemen;
        $trxM->save();
        $this->insertLog($trxM->id, $status_pengajuan, $pengaju);
        // 
        $_pengaju = json_decode($pengaju_property, true);
        $_layer1 = json_decode($layer1_property, true);
        // 
        for ($x = 0; $x < $jumlah; $x++) {
            $trxD = new TrxKebutuhanDetail;
            $trxD->id_kebutuhan = $trxM->id;
            // $trxD->id_pendidikan = $id_jenjang_pendidikan;
            $trxD->id_posisi = $id_pekerjaan;
            $trxD->id_divisi = $_pengaju['id_sub_departemen'];
            $trxD->save();
        }
        // 
        // parameter yang dipassing ke sendemail
        $mailData['title'] = 'Formulir Kebutuhan Karyawan';
        $mailData['data'] = ['nama'=>$_pengaju['nama'].'/'.$_pengaju['nik'], 'jabatan'=>$_pengaju['jabatan'], 'unit_kerja'=>$_pengaju['divisi'].'/'.$_pengaju['dep'].'/'.$_pengaju['sub_dep'], 'link'=> route('kebutuhan.show', [(new OpenDataController)->encryptWithKey($trxM->id), 'view' => (new OpenDataController)->encryptWithKey('approval'), 'nik' => (new OpenDataController)->encryptWithKey($_layer1['nik']), 'id_status' => (new OpenDataController)->encryptWithKey($status_pengajuan), 'pic' => (new OpenDataController)->encryptWithKey('layer1')]), 'id_trx' => $trxM->id ];
        $mailData['filename'] = 'emails.kebutuhan0';
        $mailData['to'] = $_layer1['email'];
        $todolist = ['id_trx' => $trxM->id, 'nik' => $_layer1['nik'], 'id_status' => $status_pengajuan, 'pic' => 'layer1'];
        $this->insertToDoList($todolist, $mailData);
        $this->sendEmail($mailData);
        // 
        return redirect('kebutuhan')->with('msg', 'Data is saved successfully.');
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

    public function insertLog($id_trx, $id_status, $nik_pic)
    {
        $data = new TrxKebutuhanLog;
        $data->id_trx = $id_trx;
        $data->id_status = $id_status;
        $data->nik_pic = $nik_pic;
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
        // $trx = ToDoList::where($where)->first();
        // if (!empty($trx)) {
        //     $trx->delete();
        // }
        $trx = ToDoList::where($where)->delete();
    }

    public function sendEmail($mailData)
    {
        $ccEmails = explode(',', MtrUtility::find(11)->informasi);
        Mail::to($mailData['to'])->cc($ccEmails)->send(new MattaRebornMail($mailData));
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
        $linkAction = route('kebutuhan.update', $id);
        $id = (new OpenDataController)->decryptWithKey($id);
        $where = ['id_trx' => $id];
        $history = TrxKebutuhanLog::where($where)->orderBy('created_at', 'desc')->get();
        $trxM = TrxKebutuhanMaster::find($id);
        // 
        // $where = ['id_trx' => $id];
        // $trxD = TrxKebutuhanDetail::where($where)->get();
        // ambil property para pihak
        $pengaju = json_decode($trxM->pengaju_property, true);
        $layer1 = json_decode($trxM->layer1_property, true);
        $layer2 = json_decode($trxM->layer2_property, true);
        // 
        $arr_nik_karyawan = array();
        if(!empty($trxM->nik_karyawan)){
            $arr = explode(";", $trxM->nik_karyawan_property);
            foreach ($arr as $value){
                array_push($arr_nik_karyawan, json_decode($value, true));
            }
        }
        // return response()->json(['arr_nik_karyawan' => $arr_nik_karyawan]);
        // 
        $pelatihan = '';
        if(!empty($trxM->id_pelatihan)){
            $arr = explode(",", $trxM->id_pelatihan);
            $pelatihan = PelatihanSub::whereIn('id', $arr)->orderBy('sub_pelatihan', 'asc')->get();
        }
        $sertifikasi = '';
        if(!empty($trxM->id_sertifikasi)){
            $arr = explode(",", $trxM->id_sertifikasi);
            $sertifikasi = SertifikasiSub::whereIn('id', $arr)->orderBy('sub_sertifikasi', 'asc')->get();
        }
        $keterampilan = '';
        if(!empty($trxM->id_keterampilan)){
            $arr = explode(",", $trxM->id_keterampilan);
            $keterampilan = KeterampilanSub::whereIn('id', $arr)->orderBy('sub_keterampilan', 'asc')->get();
        }
        $kepribadian = '';
        if(!empty($trxM->id_kepribadian)){
            $arr = explode(",", $trxM->id_kepribadian);
            $kepribadian = KepribadianSub::whereIn('id', $arr)->orderBy('sub_kepribadian', 'asc')->get();
        }
        // 
        $view = $request->view;
        $pic = $request->pic;
        $nik = $request->nik;
        $id_status = $request->id_status;
        $view = (new OpenDataController)->decryptWithKey($view);
        if(!empty($pic)){
            $pic = (new OpenDataController)->decryptWithKey($pic);
        }
        if(!empty($nik)){
            $nik = (new OpenDataController)->decryptWithKey($nik);
        }
        if(!empty($id_status)){
            $id_status = (new OpenDataController)->decryptWithKey($id_status);
        }
        $where = ['id_trx' => $id, 'nik_pic' => $nik];
        // 2 = Approved by L1
        // 5 = Rejected by L1
        $nLayer1 = TrxKebutuhanLog::where($where)->whereIn('id_status', [2, 5])->count();
        // 7 = Rejected by L2
        // 9 = Approved by L2 to HC
        $nLayer2 = TrxKebutuhanLog::where($where)->whereIn('id_status', [7, 9])->count();
        switch ($view) {
            case 'approval':
                // layer1
                if ($pic == 'layer1' && $nik == $trxM->layer1 && $nLayer1 == 0 && $id_status == 1) {
                    $approve = 2;
                    $reject = 5;
                    return view('emails.kebutuhan', compact('trxM', 'arr_nik_karyawan', 'nik', 'pic', 'approve', 'reject', 'pengaju', 'linkAction', 'pelatihan', 'sertifikasi', 'keterampilan', 'kepribadian', 'history'));
                } 
                // layer2
                else if ($pic == 'layer2' && $nik == $trxM->layer2 && $nLayer2 == 0 && $id_status == 2) {
                    $approve = 9;
                    $reject = 7;
                    return view('emails.kebutuhan', compact('trxM', 'arr_nik_karyawan', 'nik', 'pic', 'approve', 'reject', 'pengaju', 'linkAction', 'pelatihan', 'sertifikasi', 'keterampilan', 'kepribadian', 'history'));
                } 
                // selainnya
                else {
                    return view('emails.kebutuhan_ok');
                }
                break;
            case 'detail':
                $this->authorize('auth');
                $where = ['id_trx' => $id];
                $history = TrxKebutuhanLog::where($where)->orderBy('created_at', 'desc')->get();
                return view('formulir.kebutuhan.view', compact('trxM', 'arr_nik_karyawan', 'pengaju', 'layer1', 'layer2', 'pelatihan', 'sertifikasi', 'keterampilan', 'kepribadian', 'history'));
                break;
            case 'doc':
                $this->authorize('hc');
                $no = 0;
                $where = ['id_kebutuhan' => $id];
                $trx = TrxKebutuhanDetail::where($where)->orderBy('id', 'desc')->get();
                return view('formulir.kebutuhan.doc_index', compact('trx', 'no'));
                break;
            case 'doc_file':
                echo 'ok';
                // $no = 0;
                // $where = ['id_kebutuhan' => $id];
                // $trx = TrxKebutuhanDetail::where($where)->orderBy('id', 'desc')->get();
                // return view('formulir.kebutuhan.doc_index', compact('trx', 'no'));
                break;
            case 'pdf':
                $this->authorize('auth');
                // print to pdf
                $waktu = date('YmdHis');
                $no = 0;
                // 
                // start
                $htmlLog = '<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0"><tr>';
                // pengaju
                $where = ['id_trx' => $id, 'nik_pic' => $trxM->pengaju, 'id_status' => 1];
                $kebutuhanLog = TrxKebutuhanLog::where($where)->orderBy('id', 'desc')->first();
                $htmlLog .= '<td>Mengajukan<br/><img src="' . public_path('assets/images/matta/requested_new.png') . '" height="30" /><br/>' . $pengaju['nama'] . '<br/>' . $pengaju['jabatan'] . '<br/>' . $kebutuhanLog->created_at . '</td>';
                // layer1
                // 2 = Approved by L1
                // 5 = Rejected by L1
                $where = ['id_trx' => $id, 'nik_pic' => $trxM->layer1];
                $kebutuhanLog = TrxKebutuhanLog::where($where)->whereIn('id_status', [2, 5])->orderBy('id', 'desc')->first();
                if (!empty($kebutuhanLog)) {
                    if($kebutuhanLog->id_status == 2){
                        $htmlLog .= '<td>Menyetujui<br/><img src="' . public_path('assets/images/matta/approved_new.png') . '" height="30" /><br/>' . $layer1['nama'] . '<br/>' . $layer1['jabatan'] . '<br/>' . $kebutuhanLog->created_at . '</td>';
                    }else{
                        $htmlLog .= '<td>Menolak<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $layer1['nama'] . '<br/>' . $layer1['jabatan'] . '<br/>' . $kebutuhanLog->created_at . '</td>';
                    }
                }
                // layer2
                // 7 = Rejected by L2
                // 9 = Approved by L2 to HC
                $where = ['id_trx' => $id, 'nik_pic' => $trxM->layer2];
                $kebutuhanLog = TrxKebutuhanLog::where($where)->whereIn('id_status', [7, 9])->orderBy('id', 'desc')->first();
                if (!empty($kebutuhanLog)) {
                    if($kebutuhanLog->id_status == 9){
                        $htmlLog .= '<td>Menyetujui<br/><img src="' . public_path('assets/images/matta/approved_new.png') . '" height="30" /><br/>' . $layer2['nama'] . '<br/>' . $layer2['jabatan'] . '<br/>' . $kebutuhanLog->created_at . '</td>';
                    }else{
                        $htmlLog .= '<td>Menolak<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $layer2['nama'] . '<br/>' . $layer2['jabatan'] . '<br/>' . $kebutuhanLog->created_at . '</td>';
                    }
                }
                // hco
                // 3 = Reject Checked by HC
                // 10 = Done by HC
                // 28 = Terminate Cancel by HC
                $where = ['id_trx' => $id];
                $kebutuhanLog = TrxKebutuhanLog::where($where)->whereIn('id_status', [3, 10, 28])->orderBy('id', 'desc')->first();
                if (!empty($kebutuhanLog)) {
                    $htmlLog .= '<td>Mengetahui<br/><img src="' . public_path('assets/images/matta/informed_new.png') . '" height="30" /><br/>' . $kebutuhanLog->_pic->nama . '<br/>' . $kebutuhanLog->_pic->_jabatan->jabatan . '<br/>' . $kebutuhanLog->created_at . '</td>';
                }
                // finish
                $htmlLog .= '</tr></table>';
                // 
                $filename = $waktu . '_formulir_kebutuhan_karyawan.pdf';
                $view = \View::make('formulir.kebutuhan.pdf', compact('trxM', 'arr_nik_karyawan', 'pengaju', 'pelatihan', 'sertifikasi', 'keterampilan', 'kepribadian', 'htmlLog'));
                $html = $view->render();
                $pdf = new TCPDF;
                $pdf::SetTitle('Formulir Kebutuhan Karyawan');
                $pdf::SetFont('times', '', 10, '', true);
                $pdf::AddPage();
                $pdf::writeHTML($html, true, false, true, false, '');
                $pdf::Output(public_path($filename), 'I');
                return response()->download(public_path($filename));
                break;
            case 'antrian':
                // print to pdf
                // $filename = 'nomor_antrian.pdf';
                // $pdf = new TCPDF;
                // $pdf::SetTitle('nomor_antrian');
                // $pdf::SetFont('times', '', 11, '', true);
                // $no = 1;
                // $jumlah = 250;
                // for($i = 1; $i<=$jumlah;$i++){
                //     $view = \View::make('formulir.kebutuhan.antrian', compact('no'));
                //     $html = $view->render();
                //     $pdf::AddPage('L', 'A4');
                //     $pdf::writeHTML($html, true, false, true, false, '');
                //     $no = ($i*6)+1;
                // }
                // // 
                // $pdf::Output(public_path($filename), 'I');
                // return response()->download(public_path($filename));
                // break;
            default:
                return view('emails.kebutuhan_ok');
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
        $id = (new OpenDataController)->decryptWithKey($id);
        $trxM = TrxKebutuhanMaster::find($id);
        // 
        $status_pengajuan = KebutuhanStatus::whereIn('id', [3,10,28,$trxM->status_pengajuan])->orderBy('id', 'asc')->get();
        // 
        // ambil property para pihak
        $pengaju = json_decode($trxM->pengaju_property, true);
        $layer1 = json_decode($trxM->layer1_property, true);
        $layer2 = json_decode($trxM->layer2_property, true);
        return view('formulir.kebutuhan.update', compact('trxM', 'pengaju', 'layer1', 'layer2', 'status_pengajuan'));
        // } else {
        //     abort('404');
        // }
    }
    
    public function edit_kolom(Request $request, $id)
    {
        $id = (new OpenDataController)->decryptWithKey($id);
        $field = $request->field;
        // dd($request->all(), $id);
        $trxM = TrxKebutuhanMaster::find($id);
        $where = ['id_kebutuhan' => $id];
        $history = TrxKebutuhanMasterEdit::where($where)->orderBy('created_at', 'desc')->get();
        return view('formulir.kebutuhan.edit_kolom', compact('trxM', 'field', 'history') );
        // 
    }
    
    public function post_kolom(Request $request, $id)
    {
        $id = (new OpenDataController)->decryptWithKey($id);
        // dd($request->all(), $id);
        $trxM = TrxKebutuhanMaster::find($id);
        // check jumlah data TrxKebutuhanDetail yang dapat disesuaikan
        $check = false;
        $count = TrxKebutuhanDetail::where(['id_kebutuhan'=>$id])->whereNull('nama_karyawan')->count();
        $sebelum = $trxM->jumlah;
        $sesudah = $request->jumlah;
        $selisih = abs($sebelum - $sesudah);
        // data TrxKebutuhanDetail bertambah
        if($sesudah > $sebelum){
            $check = true;
            $acuan = TrxKebutuhanDetail::where(['id_kebutuhan'=>$id])->first();
            for ($x = 0; $x < $selisih; $x++) {
                $trxD = new TrxKebutuhanDetail;
                $trxD->id_kebutuhan = $acuan->id_kebutuhan;
                $trxD->id_posisi = $acuan->id_posisi;
                $trxD->id_divisi = $acuan->id_divisi;
                $trxD->save();
            }
        }
        // data TrxKebutuhanDetail berkurang
        elseif($sebelum > $sesudah){
            // check apakah jumlah pengurangan tersedia
            if($count >= $selisih){
                $check = true;
                $acuan = TrxKebutuhanDetail::where(['id_kebutuhan'=>$id])->whereNull('nama_karyawan')->orderBy('id', 'asc')->get();
                for ($x = 0; $x < $selisih; $x++) {
                    TrxKebutuhanDetail::find($acuan[$x]->id)->delete();
                }
            }
        }
        // 
        if($check){
            $data = new TrxKebutuhanMasterEdit;
            $data->id_kebutuhan = $id;
            $data->kolom = $request->field;
            $data->sebelum = $sebelum;
            $data->sesudah = $sesudah;
            $data->keterangan = $request->keterangan;
            $data->pic = auth()->user()->nik;
            $data->save();
            // 
            $trxM->jumlah = $sesudah;
            $trxM->update();
            // 
            return redirect('kebutuhan')->with('msg', 'Data is saved successfully.');
        }else{
            return redirect('kebutuhan')->with('error', 'No data is saved successfully.');
        }
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
		// MtrUtility::find(11)->informasi
		// $hco = 01031808735 = Sofyan Farid Ma'Arif
        $id = (new OpenDataController)->decryptWithKey($id);
		$hco = MtrUtility::find(20)->informasi;
        $trxM = TrxKebutuhanMaster::find($id);
        $pengaju = json_decode($trxM->pengaju_property, true);
        $layer1 = json_decode($trxM->layer1_property, true);
        $layer2 = json_decode($trxM->layer2_property, true);
        // 
        $pic = $request->pic;
        $nik = $request->nik;
        $status_pengajuan = $request->status_pengajuan;
        $catatan = $request->catatan;
        // 2 = Approved by L1
        // 5 = Rejected by L1
        if (($status_pengajuan == 2 || $status_pengajuan == 5) && $pic == 'layer1' && $nik == $trxM->layer1) {
            $trxM->status_pengajuan = $status_pengajuan;
            $trxM->catatan_layer1 = $catatan;
            $trxM->update();
            $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$nik, 'pic'=>$pic, 'filename'=>'emails.kebutuhan0']);
            $this->insertLog($id, $status_pengajuan, $nik);
            // start
            // parameter yang dipassing ke sendemail
            $mailData['title'] = 'Formulir Kebutuhan Karyawan';
            // 5 = Rejected by L1
            if($status_pengajuan == 5){
                $mailData['filename'] = 'emails.kebutuhan1';
                $mailData['data'] = ['nama' => $pengaju['nama'], 'jabatan' => $pengaju['jabatan'], 'unit_kerja' => $pengaju['divisi'] . '/' . $pengaju['dep'] . '/' . $pengaju['sub_dep'], 'id_trx' => $id];
                $mailData['to'] = explode(',', MtrUtility::find(1)->informasi);
                $this->sendEmail($mailData);
            }
            // 2 = Approved by L1
            else if($status_pengajuan == 2){
                $mailData['data'] = ['nama'=>$pengaju['nama'].'/'.$pengaju['nik'], 'jabatan'=>$pengaju['jabatan'], 'unit_kerja'=>$pengaju['divisi'].'/'.$pengaju['dep'].'/'.$pengaju['sub_dep'], 'link'=> route('kebutuhan.show', [(new OpenDataController)->encryptWithKey($trxM->id), 'view' => (new OpenDataController)->encryptWithKey('approval'), 'nik' => (new OpenDataController)->encryptWithKey($layer2['nik']), 'id_status' => (new OpenDataController)->encryptWithKey($status_pengajuan), 'pic' => (new OpenDataController)->encryptWithKey('layer2')]), 'id_trx' => $trxM->id ];
                $mailData['filename'] = 'emails.kebutuhan0';
                $mailData['to'] = $layer2['email'];
                $todolist = ['id_trx' => $trxM->id, 'nik' => $layer2['nik'], 'id_status' => $status_pengajuan, 'pic' => 'layer2'];
                $this->insertToDoList($todolist, $mailData);
                $this->sendEmail($mailData);
            }
            return view('emails.kebutuhan_ok');
            // finish
        }
        // 7 = Rejected by L2
        // 9 = Approved by L2 to HC
        else if (($status_pengajuan == 7 || $status_pengajuan == 9) && $pic == 'layer2' && $nik == $trxM->layer2) {
            $trxM->status_pengajuan = $status_pengajuan;
            $trxM->catatan_layer2 = $catatan;
            $trxM->update();
            $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$nik, 'pic'=>$pic, 'filename'=>'emails.kebutuhan0']);
            $this->insertLog($id, $status_pengajuan, $nik);
            // start
            // parameter yang dipassing ke sendemail
            $mailData['title'] = 'Formulir Kebutuhan Karyawan';
            $mailData['filename'] = 'emails.kebutuhan1';
            $mailData['data'] = ['nama' => $pengaju['nama'], 'jabatan' => $pengaju['jabatan'], 'unit_kerja' => $pengaju['divisi'] . '/' . $pengaju['dep'] . '/' . $pengaju['sub_dep'], 'id_trx' => $id];
            $mailData['to'] = explode(',', MtrUtility::find(1)->informasi);
            $this->sendEmail($mailData);
            return view('emails.kebutuhan_ok');
            // finish
        }
        // 3 = Reject Checked by HC
        // 10 = Done by HC
        // 28 = Terminate Cancel by HC
        else if ($status_pengajuan == 3 || $status_pengajuan == 10 || $status_pengajuan == 28) {
            $trxM->status_pengajuan = $status_pengajuan;
            $trxM->keterangan_lain = $request->keterangan_lain;
            // 
            $trxM->update();
            $this->insertLog($id, $status_pengajuan, $hco);
            // 
            return redirect('kebutuhan')->with('msg', 'Data is saved successfully.');
        }
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

    public function kota($id=0)
    {
        // ambil kota sesuai id_propinsi
        $where = ['id_propinsi' => $id];
        $kota = PropinsiKota::where($where)->orderBy('kota', 'asc')->get();
        return response()->json(['kota' => $kota]);
    }

    public function layer($id=0)
    {
        // ambil parameter karyawan dan semua layer
        $where = ['is_aktif' => 1, 'nik'=> $id];
        $layer = Karyawan::select('layer1', 'layer2', 'layer3')->where($where)->first();
        // approval dengan level minimal 4 = Assistant Manager
        $where = ['is_aktif' => 1];
        $karyawan = Karyawan::select('nik', 'nama')->where($where)->where('kode_level','>=',4)->orderBy('nama', 'asc')->get();
        return response()->json(['layer' => $layer, 'karyawan' => $karyawan]);
    }
}
