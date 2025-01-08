<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// 
use App\Models\Karyawan;
use App\Models\TrxSakit;
use App\Models\TrxSakitLog;
use App\Models\SakitStatus;
use App\Models\Holiday;
use App\Models\WorkingDay;
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

class TrxSakitController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['show','update']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // 
        $where = ['is_aktif' => 1];
        $karyawan = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
        $no = 0;
        $trx = TrxSakit::orderBy('id', 'desc')->get();
        if(auth()->user()->userrole == 'nhc'){
            $layer1 = auth()->user()->nik;
            $where = ['layer1' => $layer1];
            $_nik = Karyawan::select('nik')->where($where)->orderBy('nama', 'asc')->get();
            $arr_nik = [$layer1];
            foreach ($_nik as $data){
                array_push($arr_nik, $data->nik);
            }
            $trx = TrxSakit::whereIn('nik_karyawan', $arr_nik)->orderBy('id', 'desc')->get();
        }
        // filter parameter
        function forgetById($collection,$id){
            foreach($collection as $key => $item){
                if($item->id == $id){
                    $collection->forget($key);
                    break;
                }
            }
            return $collection;
        }
        // 
        $arr_status_sakit = array();
        $nama = '';
        $date_from = '';
        $date_to = '';
        foreach ($trx as $data){
            array_push($arr_status_sakit, $data->status_sakit);
        }
        $status_sakit = SakitStatus::whereIn('id', $arr_status_sakit)->orderBy('id', 'asc')->get();
        // start filter
        if($request->filter == true){
            $_status_sakit = $request->input('status_sakit', []);
            $_tipe_sakit = $request->input('tipe_sakit', []);
            $nama = $request->nama;
            // $_nik_karyawan = $request->input('nik_karyawan', []);
            $date_from = $request->date_from;
            $date_to = $request->date_to;
            // 
            $arr_tanggal = array($date_from);
            $start = $date_from;
            $i=1;
            if (strtotime($date_from) < strtotime($date_to))
            {
            while (strtotime($start) < strtotime($date_to))
                {
                    $start = date('Y-m-d', strtotime($date_from.'+'.$i.' days'));
                    $arr_tanggal[] = $start;
                    $i++;
                }
            }
            // return response()->json( [ 'arr_tanggal' => $arr_tanggal ], 200 );
            // 
            if(count($_status_sakit) > 0){
                $arr_status_sakit = $_status_sakit;
            }
            else{
                $arr_status_sakit = array();
            }
            $_nama = Karyawan::select('nik')->where('nama','LIKE','%'.$nama.'%')->get();
            $trx = TrxSakit::whereIn('nik_karyawan', $_nama)->whereIn('status_sakit', $arr_status_sakit)->orderBy('id', 'desc')->get();
            if(auth()->user()->userrole == 'nhc'){
                $layer1 = auth()->user()->nik;
                $where = ['layer1' => $layer1];
                $_nik = Karyawan::select('nik')->where($where)->orderBy('nama', 'asc')->get();
                $arr_nik = [$layer1];
                foreach ($_nik as $data){
                    array_push($arr_nik, $data->nik);
                }
                $trx = TrxSakit::whereIn('nik_karyawan', $_nama)->whereIn('status_sakit', $arr_status_sakit)->whereIn('nik_karyawan', $arr_nik)->orderBy('id', 'desc')->get();
            }
            foreach ($trx as $data){
                $_arr_tanggal = explode(';', $data->tanggal_sakit);
                if(count(array_intersect($_arr_tanggal, $arr_tanggal)) == 0){
                    $trx = forgetById($trx, $data->id);
                }
            }
        }else{
            $arr_status_sakit = array();
        }
        // end filter
        return view( 'formulir.sakit.index', compact('trx', 'no', 'status_sakit', 'arr_status_sakit', 'date_from', 'date_to', 'nama', 'karyawan'));
    }

    // fungsi untuk cek apakah masuk window
    public function checkIfExist($from, $input)
    {
        $fromDateTime = Carbon::createFromFormat('Y-m-d', $from);
        $inputDateTime = Carbon::createFromFormat('Y-m-d', $input);
        $cek = $fromDateTime > $inputDateTime;
        return ['status' => $cek, 'message' => 'Tanggal Mulai tidak CURRENTDATE atau BACKDATE'];
    }

    // cek apakah hari ini sabtu minggu
    public function isWeekend($date)
    {
        $weekDay = date('w', strtotime($date));
        $cek = $weekDay == 0 || $weekDay == 6;
        return ['status' => !$cek, 'message' => 'Tanggal Mulai tidak di HARI SABTU atau MINGGU'];
    }

    // cek apakah hari ini adalah hari libur nasional
    public function isHoliday($date)
    {
        $cek = false;
        $where = ['tanggal' => $date];
        $data = Holiday::where($where)->first();
        if (empty($data)) {
            $cek = true;
        }
        return ['status' => $cek, 'message' => 'Tanggal Mulai tidak di HARI LIBUR NASIONAL'];
    }

    // cek apakah jumlah sakit tanggungan perusahaan sudah pernah diambil
    public function isPerusahaanOver($periode_sakit, $nik_karyawan, $tipe_sakit)
    {
        $cek = true;
        $where = ['periode_sakit' => $periode_sakit, 'nik_karyawan' => $nik_karyawan, 'tipe_sakit' => $tipe_sakit];
        $jumlah = TrxSakit::where($where)->whereNotIn('status_sakit', [5,7])->count();
        if ($jumlah > 0) {
            $cek = false;
        }
        return ['status' => $cek, 'message' => 'Izin Sakit Tanggungan Perusahaan Belum Pernah diambil'];
    }

    // cek apakah jumlah sakit sudah lebih dari 12
    public function isSakitTahunanOver($periode_sakit, $nik_karyawan, $jumlah_hari)
    {
        $cek = false;
        $where = ['periode_sakit' => $periode_sakit, 'nik_karyawan' => $nik_karyawan, 'tipe_sakit' => 0];
        $jumlah = TrxSakit::where($where)->whereNotIn('status_sakit', [5,7])->sum('jumlah_hari');
        if ($jumlah + $jumlah_hari < 13) {
            $cek = true;
        }
        return ['status' => $cek, 'message' => 'Izin Sakit Tahunan tidak melebihi BATAS'];
    }

    // ambil tanggal tanggal sakit reguler
    public function getTanggalSakit($str)
    {
        if (!$this->isWeekend($str)['status'] || !$this->isHoliday($str)['status']) {
            $str = $this->getTanggalSakit(date("Y-m-d", strtotime($str . "+1 day")));
        } else {
            // $str = $this->getTanggalSakit(date("Y-m-d", strtotime($str ."+1 day")));
        }
        return $str;
    }

    // ambil tanggal tanggal sakit non reguler
    public function getTanggalSakitNonReguler($str, $nik)
    {
        $where = [ 'nik' => $nik, 'working_date' => $str ];
        $check = WorkingDay::where($where)->exists();
        if (!$check) {
            $str = $this->getTanggalSakitNonReguler(date("Y-m-d", strtotime($str . "+1 day")), $nik);
        } else {
            // $str = $this->getTanggalSakit(date("Y-m-d", strtotime($str ."+1 day")));
        }
        return $str;
    }

    // fungsi ambil sakit yang ada sesuai NIK dan waktu pengajuan
    public function isSakitExisting($nik_karyawan, $from, $to, $periode_sakit)
    {
        $cek = true;
        $where = ['periode_sakit' => $periode_sakit, 'nik_karyawan' => $nik_karyawan];
        $data = TrxSakit::where($where)->where('tanggal_mulai', '<=', $from)->where('tanggal_akhir', '>=', $from)->whereNotIn('status_sakit', [5,7])->count();
        $data += TrxSakit::where($where)->where('tanggal_mulai', '<=', $to)->where('tanggal_akhir', '>=', $to)->whereNotIn('status_sakit', [5,7])->count();
        if ($data > 0) {
            $cek = false;
        }
        return ['status' => $cek, 'message' => 'Pengajuan Izin Sakit tidak beririsan dengan Izin Sakit lainnya'];
    }

    // fungsi cek boleh sakit yang sudah setahun dari tanggal_mulai untuk kode_status KT=1 dan KKWT=2
    public function isSetahun($nik_karyawan, $waktu)
    {
        $cek = true;
        $where = ['nik' => $nik_karyawan, 'is_aktif' => 1];
        $tanggal_mulai = Karyawan::where($where)->whereIn('kode_status', [1,2])->first()->tanggal_mulai;
        if (!empty($tanggal_mulai)) {
            $fromDate = Carbon::createFromFormat('Y-m-d', date("Y-m-d", strtotime("+1 years", strtotime($tanggal_mulai))));
            $inputDate = Carbon::createFromFormat('Y-m-d', $waktu);
            $cek = $fromDate <= $inputDate;
            // $cek = false;
        }
        return ['status' => $cek, 'message' => 'Mulai bekerja lebih dari setahun'];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        //passing parameter ke formulir page
        $where = ['is_aktif' => 1];
        $karyawan = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
        $status = 'create';
        $nik_karyawan = $request->nik_karyawan;
        $where = ['nik' => $nik_karyawan];
        $pengaju = Karyawan::select('nik', 'nama')->where($where)->first();
        $pengaju = $pengaju->nama.' - '.$pengaju->nik;
        return view('formulir.sakit.insert', compact('karyawan', 'status', 'nik_karyawan', 'pengaju'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // start
        $nik_karyawan = $request->nik_karyawan;
        // $nik_karyawan = auth()->user()->nik;
        $waktu = date('Y-m-d');
        $tanggal_mulai = $request->tanggal_mulai;
        $tanggal_akhir = $request->tanggal_akhir;
        $periode_sakit = date('Y', strtotime($tanggal_mulai));
        // $is_reguler = Karyawan::where('nik', $nik_karyawan)->first()->_wg->is_reguler;
        // // echo 'is_reguler='.$is_reguler;die;
        // // cek semua kemungkinan pengajuan sakit
        // $arr = array();
        // $data1 = $this->isWeekend($tanggal_mulai);
        // // jika karyawan non reguler maka bisa sakit di hari sabtu minggu
        // if ($is_reguler == 0) {
        //     $data1 = ['status' => true, 'message' => 'Tanggal Mulai boleh di HARI SABTU atau MINGGU'];
        // }
        // $data2 = $this->checkIfExist($tanggal_mulai, $waktu);
        // // sakit tanggungan perusahaan boleh current date / backdate
        // if ($tipe_sakit > 0) {
        //     $data2 = ['status' => true, 'message' => 'Tanggal Mulai boleh CURRENTDATE atau BACKDATE'];
        // }
        // $data3 = $this->isHoliday($tanggal_mulai);
        // // jika karyawan non reguler maka bisa sakit di hari libur
        // if ($is_reguler == 0) {
        //     $data3 = ['status' => true, 'message' => 'Tanggal Mulai boleh di HARI LIBUR NASIONAL'];
        // }
        // $data4 = $this->isSakitTahunanOver($periode_sakit, $nik_karyawan, $jumlah_hari);
        // // cek apakah sakit tanggungan perusahaan sudah pernah diambil atau belum
        // if ($tipe_sakit > 0) {
        //     $data4 = $this->isPerusahaanOver($periode_sakit, $nik_karyawan, $tipe_sakit);
        // }
        // // 
        // $arr1 = array();
        // $str = $tanggal_mulai;
        // for ($x = 0; $x < $jumlah_hari; $x++) {
        //     // 8 = Melahirkan-Karyawati (90 hari)
        //     // 12 = Ibadah Umroh (7 hari)
        //     // 13 = Ibadah Haji (30 hari)
        //     // untuk sakit tersebut Hari Sabtu Minggu dan libur nasional tetap dihitung
        //     if($tipe_sakit == 8 || $tipe_sakit == 12 || $tipe_sakit == 13){
        //         array_push($arr1, $str);
        //         $str = date("Y-m-d", strtotime($str . "+1 day"));
        //     }
        //     else{
        //         // jika karyawan reguler maka Hari Sabtu dan Minggu dan hari libur tidak dihitung
        //         if ($is_reguler == 1) {
        //             $str = $this->getTanggalSakit($str);
        //         }else{
        //             $str = $this->getTanggalSakitNonReguler($str, $nik_karyawan);
        //         }
        //         array_push($arr1, $str);
        //         $str = date("Y-m-d", strtotime($str . "+1 day"));
        //     }
        // }
        // $tanggal_akhir = $arr1[$jumlah_hari - 1];
        // $tanggal_sakit = implode(";", $arr1);
        $tanggal_sakit = implode(";", $this->getDatesFromRange($tanggal_mulai, $tanggal_akhir));
        // echo $tanggal_sakit;die;
        // 
        // $data5 = $this->isSakitExisting($nik_karyawan, $tanggal_mulai, $tanggal_akhir, $periode_sakit);
        // $data6 = $this->isSetahun($nik_karyawan, $tanggal_mulai);
        // array_push($arr, $data1, $data2, $data3, $data4, $data5, $data6);
        // $cek = $data1['status'] && $data2['status'] && $data3['status'] && $data4['status'] && $data5['status'] && $data6['status'];
        // finish
        // 
        $trx = new TrxSakit;
        // $trx->tipe_sakit = $tipe_sakit;
        $trx->periode_sakit = $periode_sakit;
        $trx->nik_karyawan = $nik_karyawan;
        $where = ['nik' => $nik_karyawan, 'is_aktif' => 1];
        $dataKaryawan = Karyawan::where($where)->first();
        $nik_atasan = $dataKaryawan->layer1;
        $trx->nik_atasan = $nik_atasan;
        $trx->pic_pengganti = implode(';', $request->pic_pengganti);
        $trx->karyawan_property = $this->getNikProperty($nik_karyawan);
        $trx->atasan_property = $this->getNikProperty($nik_atasan);
        $pic_property = array();
        foreach ($request->pic_pengganti as $value) {
            array_push($pic_property, $this->getNikProperty($value));
        }
        $trx->pic_property = implode(';', $pic_property);
        $trx->tanggal_mulai = $tanggal_mulai;
        $trx->tanggal_akhir = $tanggal_akhir;
        // $trx->jumlah_hari = $jumlah_hari;
        $trx->jumlah_hari = 0;
        $trx->tanggal_sakit = $tanggal_sakit;
        $trx->alasan = $request->alasan;
        $trx->alamat = $request->alamat;
        $trx->telepon = $request->telepon;
        // $trx->telepon = $dataKaryawan->telepon1;
        $trx->keterangan = $request->keterangan;
        $trx->status_sakit = 1;
        $lampiran = $request->file('lampiran');
        $request->validate([
            'lampiran' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);
        $extension = $lampiran->getClientOriginalExtension();
        $namafile = 'sakit_'.$nik_karyawan.'_'.now()->getTimestampMs().'.'.$extension;
        $lampiran->move('public/assets/sakit/', $namafile);
        $trx->lampiran = $namafile;
        // 
        // if ($cek) {
            $trx->save();
            $this->insertLog($trx->id, $trx->status_sakit, $nik_karyawan);
            // start
            // parameter yang dipassing ke sendemail
            $karyawan = json_decode($trx->karyawan_property, true);
            $atasan = json_decode($trx->atasan_property, true);
            $mailData['title'] = 'Formulir Permohonan Izin Sakit';
            $mailData['filename'] = 'emails.sakit0';
            foreach (explode(';', $trx->pic_property) as $value) {
                $pic_property = json_decode($value, true);
                $mailData['data'] = ['nama' => $karyawan['nama'].'/'.$karyawan['nik'], 'jabatan' => $karyawan['jabatan'], 'unit_kerja' => $karyawan['divisi'] . '/' . $karyawan['dep'] . '/' . $karyawan['sub_dep'], 'atasan' => $atasan['nama'], 'link' => route('sakit.show', [(new OpenDataController)->encryptWithKey($trx->id), 'view' => (new OpenDataController)->encryptWithKey('approval'), 'nik' => (new OpenDataController)->encryptWithKey($pic_property['nik']), 'pic' => (new OpenDataController)->encryptWithKey('pic_pengganti')])];
                $mailData['to'] = $pic_property['email'];
                $todolist = ['id_trx' => $trx->id, 'nik' => $pic_property['nik'], 'id_status' => $trx->status_sakit, 'pic' => 'pic_pengganti'];
                $this->insertToDoList($todolist, $mailData);
                $this->sendEmail($mailData);
            }
            // finish
            // 
            return redirect('sakit')->with('msg', 'Data is saved successfully.');
        // } else {
        //     // return view('formulir.sakit.error', compact(['arr']));
        //     return redirect()->back()->with('error', $arr);
        // }
    }

    public function getDatesFromRange($start, $end)
    {
        $dates = array($start);
        while(end($dates) < $end){
            $dates[] = date('Y-m-d', strtotime(end($dates).' +1 day'));
        }
        return $dates;
    }

    public function getNikProperty($nik)
    {
        $where = ['nik' => $nik, 'is_aktif' => 1];
        $data = Karyawan::select('mtr_tbl_karyawan.nik', 'mtr_tbl_karyawan.nama', 'mtr_tbl_karyawan.email', 'mtr_tbl_karyawan.telepon1', 'mtr_tbl_perusahaan.nama as perusahaan', 'mtr_tbl_divisi.nama as divisi', 'mtr_tbl_departemen.nama as dep', 'mtr_tbl_sub_departemen.nama as sub_dep', 'mtr_tbl_level.level as level', 'mtr_tbl_jabatan.jabatan as jabatan', 'mtr_tbl_level.id as id_level')
            ->leftJoin('mtr_tbl_perusahaan', 'mtr_tbl_perusahaan.id', '=', 'mtr_tbl_karyawan.kode_perusahaan')
            ->leftJoin('mtr_tbl_divisi', 'mtr_tbl_divisi.id', '=', 'mtr_tbl_karyawan.kode_divisi')
            ->leftJoin('mtr_tbl_departemen', 'mtr_tbl_departemen.id', '=', 'mtr_tbl_karyawan.kode_departemen')
            ->leftJoin('mtr_tbl_sub_departemen', 'mtr_tbl_sub_departemen.id', '=', 'mtr_tbl_karyawan.kode_sub_departemen')
            ->leftJoin('mtr_tbl_level', 'mtr_tbl_level.id', '=', 'mtr_tbl_karyawan.kode_level')
            ->leftJoin('mtr_tbl_jabatan', 'mtr_tbl_jabatan.id', '=', 'mtr_tbl_karyawan.kode_jabatan')
            ->where($where)->first();
        return json_encode($data);
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

    public function insertLog($id_trx, $id_status, $nik_pic)
    {
        $data = new TrxSakitLog;
        $data->id_trx = $id_trx;
        $data->id_status = $id_status;
        $data->nik_pic = $nik_pic;
        $data->save();
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
        $linkAction = route('sakit.update', $id);
        $id = (new OpenDataController)->decryptWithKey($id);
        // dd($request->all(), $id);
        $where = ['id_trx' => $id];
        $history = TrxSakitLog::where($where)->orderBy('created_at', 'desc')->get();
        // 
        $trxSakit = TrxSakit::find($id);
        $karyawan = json_decode($trxSakit->karyawan_property, true);
        $atasan = json_decode($trxSakit->atasan_property, true);
        // print_r($atasan);die;
        $tanggal_mulai = $this->getTanggalIndonesia($trxSakit->tanggal_mulai);
        $tanggal_akhir = $this->getTanggalIndonesia($trxSakit->tanggal_akhir);
        $tanggal_sakit = explode(';', $trxSakit->tanggal_sakit);
        $arrTanggalSakit = array();
        foreach ($tanggal_sakit as $value) {
            array_push($arrTanggalSakit, $this->getTanggalIndonesia($value));
        }
        $is_reguler = Karyawan::where('nik', $trxSakit->nik_karyawan)->first()->_wg->is_reguler;
        $tanggal_masuk = $this->getTanggalMasuk($trxSakit->tanggal_akhir, $is_reguler, $trxSakit->nik_karyawan);
        $arrTanggal = ['tanggal_mulai' => $tanggal_mulai, 'tanggal_akhir' => $tanggal_akhir, 'arrTanggalSakit' => $arrTanggalSakit, 'tanggal_masuk' => $tanggal_masuk];
        $pic_property = explode(';', $trxSakit->pic_property);
        $arrPicProperty = array();
        foreach ($pic_property as $value) {
            array_push($arrPicProperty, json_decode($value, true));
        }
        $data = ['nik' => $karyawan['nik'], 'nama' => $karyawan['nama'], 'jabatan' => $karyawan['jabatan'], 'unit_kerja' => $karyawan['divisi'] . '/' . $karyawan['dep'] . '/' . $karyawan['sub_dep']];
        $view = $request->view;
        $pic = $request->pic;
        $nik = $request->nik;
        $view = (new OpenDataController)->decryptWithKey($view);
        if(!empty($pic)){
            $pic = (new OpenDataController)->decryptWithKey($pic);
        }
        if(!empty($nik)){
            $nik = (new OpenDataController)->decryptWithKey($nik);
        }
        // 
        $where = ['id_trx' => $id, 'nik_pic' => $nik, 'id_status' => 2];
        if($pic == 'nik_atasan'){
            $where = ['id_trx' => $id, 'nik_pic' => $nik, 'id_status' => 4];
        }
        $nPic = TrxSakitLog::where($where)->count();
        $pic_pengganti = explode(';', $trxSakit->pic_pengganti);
        // 4 Disetujui oleh Layer 1
        // 5 Ditolak oleh Layer 1
        // 7 Ditolak oleh PIC Pengganti
        $where = ['id_trx' => $id];
        $isTolak = TrxSakitLog::where($where)->whereIn('id_status', [4,5,7])->exists();
        // 
        switch ($view) {
            // PIC pengganti dapat mereject 
            // dan salah satu pic pengganti reject berlaku untuk pic pengganti yg lain otomatis reject.
            // Ini berlaku jika atasan pengaju belum melakukan approve/reject.
            // Jika atasan pengaju sudah melakukan approve/reject maka pic pengganti yang belum melakukan approve/reject tidak bisa melakukan approve/reject.
            case 'approval':
                if ($pic == 'pic_pengganti' && in_array($nik, $pic_pengganti) && $nPic == 0) {
                    if($isTolak){
                        return view('emails.sakit_ok');
                    }
                    return view('emails.sakit', compact('trxSakit', 'data', 'arrTanggal', 'arrPicProperty', 'linkAction', 'pic', 'nik', 'history'));
                } else if ($pic == 'nik_atasan' && $nik == $trxSakit->nik_atasan && $nPic == 0) {
                    if($isTolak){
                        return view('emails.sakit_ok');
                    }
                    return view('emails.sakit', compact('trxSakit', 'data', 'arrTanggal', 'arrPicProperty', 'linkAction', 'pic', 'nik', 'history'));
                } else {
                    return view('emails.sakit_ok');
                }
                break;
            case 'detail':
                $this->authorize('auth');
                // 
                $where = ['id_trx' => $id];
                $history = TrxSakitLog::where($where)->orderBy('created_at', 'desc')->get();
                // 
                return view('formulir.sakit.view', compact('history', 'trxSakit', 'karyawan', 'atasan', 'arrTanggal', 'arrPicProperty'));
                break;
            case 'pdf':
                $this->authorize('auth');
                // print to pdf
                $waktu = date('YmdHis');
                // start
                $htmlSakitLog = '<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0"><tr>';
                // karyawan
                $where = ['id_trx' => $id, 'nik_pic' => $trxSakit->nik_karyawan, 'id_status' => 1];
                $sakitLog = TrxSakitLog::where($where)->orderBy('id', 'desc')->first();
                if (!empty($sakitLog)) {
                    $htmlSakitLog .= '<td>Mengajukan<br/><img src="' . public_path('assets/images/matta/requested_new.png') . '" height="30" /><br/>' . $karyawan['nama'] . '<br/>' . $karyawan['jabatan'] . '<br/>' . $sakitLog->created_at . '</td>';
                }
                // pic pengganti
                // 2 Disetujui oleh PIC Pengganti
                $where = ['id_trx' => $id, 'id_status' => 2];
                $sakitLog = TrxSakitLog::where($where)->groupBy('nik_pic')->orderBy('id', 'desc')->get();
                if (!empty($sakitLog) && count($sakitLog) > 0) {
                    $htmlSakitLog .= '<td>Menyetujui<br/><img src="' . public_path('assets/images/matta/approved_new.png') . '" height="30" /><br/>';
                    foreach ($sakitLog as $value) {
                        $_pic = $arrPicProperty[array_search($value->nik_pic, $pic_pengganti)];
                        $htmlSakitLog .= $_pic['nama'] . '<br/>' . $_pic['jabatan'] . '<br/>' . $value->created_at . '<br/>';
                    }
                    $htmlSakitLog .= '</td>';
                }
                // 7 Ditolak oleh PIC Pengganti
                $where = ['id_trx' => $id, 'id_status' => 7];
                $sakitLog = TrxSakitLog::where($where)->groupBy('nik_pic')->orderBy('id', 'desc')->get();
                if (!empty($sakitLog) && count($sakitLog) > 0) {
                    $htmlSakitLog .= '<td>Menolak<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>';
                    foreach ($sakitLog as $value) {
                        $_pic = $arrPicProperty[array_search($value->nik_pic, $pic_pengganti)];
                        $htmlSakitLog .= $_pic['nama'] . '<br/>' . $_pic['jabatan'] . '<br/>' . $value->created_at . '<br/>';
                    }
                    $htmlSakitLog .= '</td>';
                }
                // atasan
                // 4 Disetujui oleh Layer 1
                $where = ['id_trx' => $id, 'nik_pic' => $trxSakit->nik_atasan, 'id_status' => 4];
                $sakitLog = TrxSakitLog::where($where)->orderBy('id', 'desc')->first();
                if (!empty($sakitLog)) {
                    $htmlSakitLog .= '<td>Menyetujui<br/><img src="' . public_path('assets/images/matta/approved_new.png') . '" height="30" /><br/>' . $atasan['nama'] . '<br/>' . $atasan['jabatan'] . '<br/>' . $sakitLog->created_at . '</td>';
                }
                // 5 Ditolak oleh Layer 1
                $where = ['id_trx' => $id, 'nik_pic' => $trxSakit->nik_atasan, 'id_status' => 5];
                $sakitLog = TrxSakitLog::where($where)->orderBy('id', 'desc')->first();
                if (!empty($sakitLog)) {
                    $htmlSakitLog .= '<td>Menolak<br/><img src="' . public_path('assets/images/matta/rejected_new.png') . '" height="30" /><br/>' . $atasan['nama'] . '<br/>' . $atasan['jabatan'] . '<br/>' . $sakitLog->created_at . '</td>';
                }
                // hco
                $where = ['id_trx' => $id, 'id_status' => 6];
                $sakitLog = TrxSakitLog::where($where)->orderBy('id', 'desc')->first();
                if (!empty($sakitLog)) {
                    $htmlSakitLog .= '<td>Mengetahui<br/><img src="' . public_path('assets/images/matta/informed_new.png') . '" height="30" /><br/>' . $sakitLog->_pic->nama . '<br/>' . $sakitLog->_pic->_jabatan->jabatan . '<br/>' . $sakitLog->created_at . '</td>';
                }
                // finish
                $htmlSakitLog .= '</tr></table>';
                // die;
                // 
                $filename = $waktu . '_formulir_permohonan_sakit.pdf';
                $view = \View::make('formulir.sakit.pdf', compact('trxSakit', 'karyawan', 'arrTanggal', 'arrPicProperty', 'htmlSakitLog'));
                $html = $view->render();
                $pdf = new TCPDF;
                $pdf::SetTitle('Formulir Permohonan Izin Sakit');
                $pdf::SetFont('times', '', 10, '', true);
                $pdf::AddPage();
                $pdf::writeHTML($html, true, false, true, false, '');
                $pdf::Output(public_path($filename), 'I');
                return response()->download(public_path($filename));
                break;
            default:
                return view('emails.sakit_ok');
        }
    }

    public function getTanggalIndonesia($tgl)
    {
        $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $strTgl = 'Hari ' . $hari[Carbon::createFromFormat('Y-m-d', $tgl)->dayOfWeek] . ', ';
        $strTgl .= Carbon::createFromFormat('Y-m-d', $tgl)->format('d-m-Y');
        return $strTgl;
    }

    public function getTanggalMasuk($tgl, $is_reguler, $nik)
    {
        if ($is_reguler == 1) {
            $tgl = date("Y-m-d", strtotime($tgl . "+1 day"));
            while (!$this->isWeekend($tgl)['status'] || !$this->isHoliday($tgl)['status']) {
                $tgl = date("Y-m-d", strtotime($tgl . "+1 day"));
            }
        } else {
            $tgl = date("Y-m-d", strtotime($tgl . "+1 day"));
            $tgl = $this->getTanggalSakitNonReguler($tgl, $nik);
        }
        return $this->getTanggalIndonesia($tgl);
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
        // print_r($request->all());die;
        $id = (new OpenDataController)->decryptWithKey($id);
        $trx = TrxSakit::find($id);
        $pic = $request->pic;
        $nik = $request->nik;
        $status_sakit = $request->status_sakit;
        // $status_sakit = 0;
        // if($pic == 'pic_pengganti'){
        //     $status_sakit = 2;
        //     $trx->status_sakit = $status_sakit;
        // }
        $trx->status_sakit = $status_sakit;
        $trx->update();
        $this->deleteToDoList(['id_trx'=>$id, 'nik'=>$nik, 'pic'=>$pic, 'filename'=>'emails.sakit0']);
        $this->insertLog($id, $status_sakit, $nik);
        // jika sudah ada 1 pic pengganti yang approve
        $where = ['id_trx' => $id, 'id_status' => 2];
        $nPic = TrxSakitLog::where($where)->count();
        if ($nPic == 1 && $pic == 'pic_pengganti') {
            // 7 Ditolak oleh PIC Pengganti
            $where = ['id_trx' => $id, 'id_status' => 7];
            $isTolak = TrxSakitLog::where($where)->exists();
            if($isTolak){
                return view('emails.sakit_ok');
            }
            // start
            // parameter yang dipassing ke sendemail
            $karyawan = json_decode($trx->karyawan_property, true);
            $atasan = json_decode($trx->atasan_property, true);
            $mailData['title'] = 'Formulir Permohonan Izin Sakit';
            $mailData['filename'] = 'emails.sakit0';
            // $atasan = json_decode($trx['atasan_property'], true);
            $mailData['data'] = ['nama' => $karyawan['nama'].'/'.$karyawan['nik'], 'jabatan' => $karyawan['jabatan'], 'unit_kerja' => $karyawan['divisi'] . '/' . $karyawan['dep'] . '/' . $karyawan['sub_dep'], 'atasan' => $atasan['nama'], 'link' => route('sakit.show', [(new OpenDataController)->encryptWithKey($id), 'view' => (new OpenDataController)->encryptWithKey('approval'), 'nik' => (new OpenDataController)->encryptWithKey($atasan['nik']), 'pic' => (new OpenDataController)->encryptWithKey('nik_atasan')])];
            $mailData['to'] = $atasan['email'];
            $todolist = ['id_trx' => $id, 'nik' => $atasan['nik'], 'id_status' => $status_sakit, 'pic' => 'nik_atasan'];
            $this->insertToDoList($todolist, $mailData);
            $this->sendEmail($mailData);
            // finish
        }
        // hco
        else if ($status_sakit == 4 && $pic == 'nik_atasan') {
            // start
            $karyawan = json_decode($trx->karyawan_property, true);
            $atasan = json_decode($trx->atasan_property, true);
            $mailData['title'] = 'Formulir Permohonan Izin Sakit';
            $mailData['filename'] = 'emails.sakit1';
            $atasan = json_decode($trx['atasan_property'], true);
            $mailData['data'] = ['nama' => $karyawan['nama'], 'jabatan' => $karyawan['jabatan'], 'unit_kerja' => $karyawan['divisi'] . '/' . $karyawan['dep'] . '/' . $karyawan['sub_dep'], 'atasan' => $atasan['nama']];
            $mailData['to'] = explode(',', MtrUtility::find(1)->informasi);
            $this->sendEmail($mailData);
            // finish
        }
        // 
        return view('emails.sakit_ok');
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