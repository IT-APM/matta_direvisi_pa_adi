<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use App\Mail\MattaRebornMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use DB;
use Elibyy\TCPDF\Facades\TCPDF;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use URL;
// 
use App\Models\Overtime;
use App\Models\MtrUtility;
use App\Models\Karyawan;
use App\Models\MtrPayroll;
use App\Models\MtrPayrollLog;
use App\Models\MtrPayrollAddsub;
use App\Models\MtrPayrollBpjstk;
use App\Models\MtrPayrollBpjskes;
use App\Models\MtrPayrollBpjsMax;
// 
use App\Models\TrxPayroll;
use App\Models\TrxPayrollLog;
use App\Models\TrxPayrollAddsub;
use App\Models\TrxPayrollAddsubLog;
use App\Models\TrxPayrollBpjstk;
use App\Models\TrxPayrollBpjstkLog;
use App\Models\TrxPayrollBpjskes;
use App\Models\TrxPayrollBpjskesLog;
use App\Models\TrxPayrollEmail;
use App\Models\TrxPayrollEmailLog;
// 
use App\Models\MtrFindimSubdepartemen;
use App\Models\MtrFindimDepartemen;
use App\Models\MtrFindimDivisi;
use App\Models\MtrFindimProject;
use App\Models\Divisi;
use App\Models\Departemen;
use App\Models\SubDepartemen;
use App\Models\KaryawanStatus;
use App\Models\Jabatan;
use App\Models\Level;
// 
class TrxPayrollController extends Controller
{

    // http://localhost/matta/payroll/set_paid_date/0
    // https://matta-reborn.alita.id/matta/payroll/set_paid_date/0
    public function set_paid_date($id)
    {
        // cek tanggal
        $today = Carbon::today();
        // setiap tanggal 1 memindahkan trx_tbl_payroll ke trx_tbl_payroll_log
        if($today->day == 1) {
            $this->bkp(0);
        }
        $check = TrxPayroll::whereNull('paid_at')->exists();
        $arr = [];
        if($check){
            $trx = TrxPayroll::whereNull('paid_at')->get();
            foreach($trx as $_trx){
                $tgl = Carbon::parse("{$_trx->tahun}-{$_trx->bulan}-25")->format('Y-m-d');
                while(!(new TrxCutiController)->isWeekend($tgl)['status'] || !(new TrxCutiController)->isHoliday($tgl)['status']) {
                    $tgl = date("Y-m-d", strtotime($tgl . "-1 day"));
                }
                TrxPayroll::whereNull('paid_at')->where(['id'=>$_trx->id])->update(['paid_at' => $tgl]);
                array_push($arr, $tgl);
            }
        }
        // $tgl = Carbon::parse($today)->format('Y-m-d');
        // while(!(new TrxCutiController)->isWeekend($tgl)['status'] || !(new TrxCutiController)->isHoliday($tgl)['status']) {
        //     $tgl = date("Y-m-d", strtotime($tgl . "-1 day"));
        // }
        // dd($arr);
        // TrxPayroll::whereNull('paid_at')->where(['bulan'=>$today->month, 'tahun'=>$today->year])->update(['paid_at' => $tgl]);
        // DB::update(DB::raw("UPDATE trx_tbl_payroll SET paid_at='".Carbon::parse($tgl)->format('Y-m-d')."' WHERE bulan=".$today->month." AND tahun=".$today->year));
    }

    public function sendEmail($mailData)
    {
        $ccEmails = explode(',', $mailData['cc']);
        Mail::to($mailData['to'])->cc($ccEmails)->send(new MattaRebornMail($mailData));
    }
    
    public function send_email_exe(Request $request)
    {
        // dd($request->all());
        if($request->submit == 'execute'){
            $id = $request->input('id_current', []);
            if(count($id)==0){
                return redirect( 'payroll/send_email/0' )->with( 'msg', 'No Payroll Email is executed.' );
            }else{
                foreach($id as $_id){
                    $_trx = TrxPayrollEmail::find($_id);
                    $email = json_decode($_trx->email, true);
                    // parameter yang dipassing ke sendemail
                    $mailData['title'] = $email['subject'];
                    $mailData['data'] = ['msg' => $email['body']];
                    $mailData['filename'] = 'emails.payroll0';
                    $mailData['to'] = $email['to'];
                    $mailData['cc'] = $email['cc'];
                    $this->sendEmail($mailData);
                    // insert into trx_tbl_payroll_email_log
                    $trx = New TrxPayrollEmailLog;
                    $trx->nik = $_trx->nik;
                    $trx->nik_property = $_trx->nik_property;
                    $trx->bulan = $_trx->bulan;
                    $trx->tahun = $_trx->tahun;
                    $trx->email = $_trx->email;
                    $trx->id_addsub = $_trx->id_addsub;
                    $trx->save();
                    // delete
                    TrxPayrollEmail::find($_id)->delete();
                }
                return redirect( 'payroll/send_email/0' )->with( 'msg', 'Payroll Email is executed successfully.' );
            }
        }
        elseif($request->submit == 'delete'){
            $id = $request->input('id_current', []);
            if(count($id)==0){
                return redirect( 'payroll/send_email/0' )->with( 'msg', 'No Payroll Email is deleted.' );
            }else{
                foreach($id as $_id){
                    TrxPayrollEmail::find($_id)->delete();
                }
                return redirect( 'payroll/send_email/0' )->with( 'msg', 'Payroll Email is deleted successfully.' );
            }
        }
    }

    public function send_email(Request $request, $id)
    {
        $trx_current = TrxPayrollEmail::orderBy('id', 'desc')->get();
        $trx_history = TrxPayrollEmailLog::orderBy('id', 'desc')->get();
        return view('payroll.send_email', compact('trx_current', 'trx_history'));
    }
    
    public function employee_gap(Request $request, $id)
    {
        $result = array();
        // membandingkan data 1 bulan sebelumnya untuk mengecek apakah ada perbedaan karyawan
        // present
        $periode = DB::select(DB::raw("SELECT bulan,tahun FROM trx_tbl_payroll GROUP BY bulan,tahun ORDER BY tahun DESC,bulan DESC"));
        foreach($periode as $data){
            array_push($result, $this->get_employee_gap($data->bulan, $data->tahun, 'present'));
        }
        // past
        $periode = DB::select(DB::raw("SELECT bulan,tahun FROM trx_tbl_payroll_log GROUP BY bulan,tahun ORDER BY tahun DESC,bulan DESC"));
        foreach($periode as $data){
            array_push($result, $this->get_employee_gap($data->bulan, $data->tahun, 'past'));
        }
        // 
        $bulan_tahun_dari = '';
        $bulan_tahun_sampai = '';// start filter
        if($request->filter == true){
            $result = array();
            $bulan_tahun_dari = $request->bulan_tahun_dari;
            $bulan_tahun_sampai = $request->bulan_tahun_sampai;
            // present
            $periode = DB::select(DB::raw("SELECT bulan,tahun FROM trx_tbl_payroll WHERE DATE(CONCAT(tahun, '-', bulan, '-01')) BETWEEN '".$request->bulan_tahun_dari."-01' AND '".$request->bulan_tahun_sampai."-01' GROUP BY bulan,tahun ORDER BY tahun DESC,bulan DESC"));
            foreach($periode as $data){
                array_push($result, $this->get_employee_gap($data->bulan, $data->tahun, 'present'));
            }
            // past
            $periode = DB::select(DB::raw("SELECT bulan,tahun FROM trx_tbl_payroll_log WHERE DATE(CONCAT(tahun, '-', bulan, '-01')) BETWEEN '".$request->bulan_tahun_dari."-01' AND '".$request->bulan_tahun_sampai."-01' GROUP BY bulan,tahun ORDER BY tahun DESC,bulan DESC"));
            foreach($periode as $data){
                array_push($result, $this->get_employee_gap($data->bulan, $data->tahun, 'past'));
            }
        }
        return view('payroll.employee_gap', compact('result', 'bulan_tahun_dari', 'bulan_tahun_sampai'));
        // $where = ['bulan'=>Carbon::now()->month, 'tahun'=>Carbon::now()->year];
        // $payroll = TrxPayroll::where(['status'=>'apm'])->where($where)->get();
        // $where = ['bulan'=>Carbon::now()->subMonth(2)->month, 'tahun'=>Carbon::now()->subMonth(2)->year];
        // $payroll = TrxPayrollLog::where(['status'=>'apm'])->where($where)->get();
        // $where = ['bulan'=>Carbon::now()->subMonth(3)->month, 'tahun'=>Carbon::now()->subMonth(3)->year];
        // $payroll_h = TrxPayrollLog::where(['status'=>'apm'])->where($where)->get();
        // $arr_nik = [];
        // $arr_nik_h = [];
        // foreach(($payroll->toArray()) as $data){
        //     array_push($arr_nik, $data['nik']);
        // }
        // foreach(($payroll_h->toArray()) as $data){
        //     array_push($arr_nik_h, $data['nik']);
        // }
        // $collection1 = collect($arr_nik);
        // $collection2 = collect($arr_nik_h);
        // $diff = $collection2->diff($collection1);
        // if ($diff->isNotEmpty()) {
        //     dd('Ada anggota baru di array kedua yang tidak ada di array pertama:', $diff);
        // } else {
        //     dd('Tidak ada anggota baru di array kedua yang tidak ada di array pertama.');
        // }
        // dd($diff);
    }
    
    public function get_employee_gap($bulan, $tahun, $periode)
    {
        $submonth = Carbon::createFromFormat('Y-m-d', $tahun."-".$bulan."-01")->subMonth();
        $where = ['bulan'=>$bulan, 'tahun'=>$tahun];
        $payroll = TrxPayroll::where(['status'=>'apm'])->where($where)->orderByRaw('tahun desc, bulan desc')->get();
        // cek jika jumlah gapok + id_addsub IN (1,2,3,4,5) = 0 maka dikeluarkan
        $reject_nik = array();
        foreach($payroll as $data){
            $where = ['bulan'=>$bulan, 'tahun'=>$tahun, 'nik'=>$data->nik];
            $arr_id = [1,2,3,4,5];
            $add = TrxPayrollAddsub::where($where)->whereIn('id_addsub', $arr_id)->sum('angka');
            if($data->gapok + $add == 0){
                array_push($reject_nik, $data->nik);
            }
        }
        foreach($reject_nik as $data){
            $payroll = $payroll->reject(function ($item) use ($data) {
                return $item['nik'] == $data;
            });
        }
        if($periode == 'past'){
            $payroll = TrxPayrollLog::where(['status'=>'apm'])->where($where)->orderByRaw('tahun desc, bulan desc')->get();
            // cek jika jumlah gapok + id_addsub IN (1,2,3,4,5) = 0 maka dikeluarkan
            $reject_nik = array();
            foreach($payroll as $data){
                $where = ['bulan'=>$bulan, 'tahun'=>$tahun, 'nik'=>$data->nik];
                $arr_id = [1,2,3,4,5];
                $add = TrxPayrollAddsubLog::where($where)->whereIn('id_addsub', $arr_id)->sum('angka');
                if($data->gapok + $add == 0){
                    array_push($reject_nik, $data->nik);
                }
            }
            foreach($reject_nik as $data){
                $payroll = $payroll->reject(function ($item) use ($data) {
                    return $item['nik'] == $data;
                });
            }
        }
        $where = ['bulan'=>$submonth->month, 'tahun'=>$submonth->year];
        $payroll_h = TrxPayrollLog::where(['status'=>'apm'])->where($where)->orderByRaw('tahun desc, bulan desc')->get();
        // cek jika jumlah gapok + id_addsub IN (1,2,3,4,5) = 0 maka dikeluarkan
        $reject_nik = array();
        foreach($payroll_h as $data){
            $where = ['bulan'=>$bulan, 'tahun'=>$tahun, 'nik'=>$data->nik];
            $arr_id = [1,2,3,4,5];
            $add = TrxPayrollAddsubLog::where($where)->whereIn('id_addsub', $arr_id)->sum('angka');
            if($data->gapok + $add == 0){
                array_push($reject_nik, $data->nik);
            }
        }
        foreach($reject_nik as $data){
            $payroll_h = $payroll_h->reject(function ($item) use ($data) {
                return $item['nik'] == $data;
            });
        }
        $arr_nik = [];
        $arr_nik_h = [];
        foreach(($payroll->toArray()) as $data){
            array_push($arr_nik, $data['nik']);
        }
        foreach(($payroll_h->toArray()) as $data){
            array_push($arr_nik_h, $data['nik']);
        }
        $coll = collect($arr_nik);
        $coll_h = collect($arr_nik_h);
        $diff_after = $coll->diff($coll_h);
        $diff_before = $coll_h->diff($coll);
        // 
        $where = ['bulan'=>$bulan, 'tahun'=>$tahun];
        $payroll = TrxPayroll::where(['status'=>'apm'])->where($where)->whereIn('nik', $diff_after)->orderByRaw('tahun desc, bulan desc')->get();
        if($periode == 'past'){
            $payroll = TrxPayrollLog::where(['status'=>'apm'])->where($where)->whereIn('nik', $diff_after)->orderByRaw('tahun desc, bulan desc')->get();
        }
        $where = ['bulan'=>$submonth->month, 'tahun'=>$submonth->year];
        $payroll_h = TrxPayrollLog::where(['status'=>'apm'])->where($where)->whereIn('nik', $diff_before)->orderByRaw('tahun desc, bulan desc')->get();
        // 
        return ['bulan'=>$bulan, 'tahun'=>$tahun, 'after'=>$payroll, 'before'=>$payroll_h];
    }
    
    public function edit_payroll(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $back_url = $request->back_url;
        $type = $request->type;
        $trx = TrxPayrollLog::find($id);
        if($type=='overtime_present'){
            $trx = TrxPayroll::find($id);
        }
        return view('payroll.edit_payroll', compact('trx', 'back_url', 'type'));
    }
    
    public function save_edit_payroll(Request $request)
    {
        // dd($request->all());
        $back_url = $request->back_url;
        $type = $request->type;
        // $tab = $request->tab;
        $trx = TrxPayrollLog::find($request->id);
        if($type=='overtime_present'){
            $trx = TrxPayroll::find($request->id);
        }
        $trx->overtime = str_replace(',', '', $request->overtime);
        $trx->update();
        return redirect($back_url)->with( 'msg', 'Data is saved successfully.' );
        // $msg = 'Overtime Payroll Value Data is saved successfully.';
        // return redirect($back_url)->with(compact('msg','tab'));
        // return redirect($back_url, compact('msg','tab'));
        // return redirect($back_url)->with(['msg'=>$tab, 'tab'=>$tab]);
    }
    
    public function slip(Request $request, $id)
    {
        // fungsi untuk set paid_at default pada trx_tbl_payroll_log
        // $arr = array();
        // $startYear = 2022;
        // $endYear = 2024;
        // for ($year = $startYear; $year <= $endYear; $year++) {
        //     for ($month = 1; $month <= 12; $month++) {
        //         $tgl = Carbon::create($year, $month, 25);
        //         while (!(new TrxCutiController)->isWeekend($tgl)['status'] || !(new TrxCutiController)->isHoliday($tgl)['status']) {
        //             $tgl = date("Y-m-d", strtotime($tgl . "-1 day"));
        //         }
        //         array_push($arr, "UPDATE trx_tbl_payroll_log SET paid_at='".Carbon::parse($tgl)->format('Y-m-d')."' WHERE bulan=".$month." AND tahun=".$year.";");
        //         echo "UPDATE trx_tbl_payroll_log SET paid_at='".Carbon::parse($tgl)->format('Y-m-d')."' WHERE bulan=".$month." AND tahun=".$year.";<br/>";
        //         // array_push($arr, (new TrxCutiController)->getTanggalIndonesia(Carbon::parse($tgl)->format('Y-m-d')));
        //     }
        // }
        // dd('');
        $nik = (new OpenDataController)->decryptWithKey($id);
        if($nik != auth()->user()->nik){
            return redirect()->route('error-403');
        }
        $where = ['nik'=>$nik];
        $payroll = TrxPayroll::where(['status'=>'apm'])->where($where)->orderByRaw('tahun desc, bulan desc')->get();
        $payroll_h = TrxPayrollLog::where(['status'=>'apm'])->where($where)->orderByRaw('tahun desc, bulan desc')->get();
        // filter parameter
        $bulan_tahun_dari = '';
        $bulan_tahun_sampai = '';// start filter
        if($request->filter == true){
            $bulan_tahun_dari = $request->bulan_tahun_dari;
            $bulan_tahun_sampai = $request->bulan_tahun_sampai;
            $payroll = TrxPayroll::where(['status'=>'apm'])->where($where)->whereBetween(DB::raw('DATE(CONCAT(tahun, "-", bulan, "-01"))'), [$bulan_tahun_dari.'-01', $bulan_tahun_sampai.'-01'])->orderByRaw('tahun desc, bulan desc')->get();
            $payroll_h = TrxPayrollLog::where(['status'=>'apm'])->where($where)->whereBetween(DB::raw('DATE(CONCAT(tahun, "-", bulan, "-01"))'), [$bulan_tahun_dari.'-01', $bulan_tahun_sampai.'-01'])->orderByRaw('tahun desc, bulan desc')->get();
        }
        return view('payroll.self', compact('payroll', 'payroll_h', 'bulan_tahun_dari', 'bulan_tahun_sampai'));
    }
    
    public function slip_detail(Request $request, $id)
    {
        // dd($request->all(), $id);
        $bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        $id = (new OpenDataController)->decryptWithKey($id);
        $payroll = null;
        $_view = $request->view;
        if($_view == 'pdf_h'){
            $payroll = TrxPayrollLog::find($id);
            // $view = \View::make('payroll.pdf_h', compact('payroll'));
        }elseif($_view == 'pdf'){
            $payroll = TrxPayroll::find($id);
            // $view = \View::make('payroll.pdf', compact('payroll'));
        }
        $pic_paraf = Karyawan::where(['nik' => MtrUtility::find(35)->informasi])->first();
        $pic_paraf = $pic_paraf->nama;
        $view = \View::make('payroll.pdf', compact('payroll', '_view', 'pic_paraf'));
        $password = Carbon::parse(Karyawan::where(['nik' => $payroll->nik])->first()->tanggal_lahir)->format("dmY");
        $filename = (new OpenDataController)->encryptWithKey($payroll->nik).' Slip Gaji '.$bulan[$payroll->bulan - 1].' '.$payroll->tahun.'.pdf';
        $html = $view->render();
        $pdf = new TCPDF;
        $pdf::SetTitle($filename);
        // $pdf::SetFont('times', '', 8, '', true);
        $pdf::SetFont('helvetica', '', 8, '', true);
        $pdf::AddPage('L', 'A4');
        $pdf::writeHTML($html, true, false, true, false, '');
        // Set PDF protection settings
        // The first parameter: permissions, ['copy', 'print'] means the user can copy and print but cannot modify the PDF
        // The second parameter: User password (this can be empty)
        // The third parameter: Owner password (this is used to unlock for modification)
        // The fourth parameter: Encryption type (0 = no encryption, 256 = AES encryption)
        // $pdf::SetProtection(array('print', 'copy'), $password, $password);
        $pdf::SetProtection(array('print', 'copy'), $password, md5(123456), 0, null);
        // open browser
        // $pdf::Output(public_path($filename), 'I');
        // download pdf
        $pdf::Output(public_path($filename), 'D');
        return response()->download(public_path($filename));
    }
    
    public function terbilang($x)
    {
        $angka = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
        if ($x < 12)
            return " " . $angka[$x];
        elseif($x < 20)
            return $this->terbilang($x - 10) . " Belas";
        elseif($x < 100)
            return $this->terbilang($x / 10) . " Puluh" . $this->terbilang($x % 10);
        elseif($x < 200)
            return " Seratus" . $this->terbilang($x - 100);
        elseif($x < 1000)
            return $this->terbilang($x / 100) . " Ratus" . $this->terbilang($x % 100);
        elseif($x < 2000)
            return " Seribu" . $this->terbilang($x - 1000);
        elseif($x < 1000000)
            return $this->terbilang($x / 1000) . " Ribu" . $this->terbilang($x % 1000);
        elseif($x < 1000000000)
            return $this->terbilang($x / 1000000) . " Juta" . $this->terbilang($x % 1000000);
    }
    
    public function history($id, Request $request)
    {
        // dd($id, $request->all());
        // $payroll = TrxPayrollLog::where(['status'=>'apm'])->orderByRaw('tahun desc, bulan desc')->get();
        $payroll = TrxPayrollLog::orderByRaw('tahun desc, bulan desc')->get();
        if(in_array(auth()->user()->nik, explode(',', MtrUtility::find(36)->informasi))){
            $payroll = TrxPayrollLog::where(['status'=>'apm'])->orderByRaw('tahun desc, bulan desc')->get();
        }
        // 
        $arr_nik = [];
        $arr_findim_subdepartemen = [];
        $arr_findim_departemen = [];
        $arr_findim_divisi = [];
        $arr_findim_project = [];
        $arr_divisi = [];
        $arr_departemen = [];
        $arr_subdepartemen = [];
        $arr_status_karyawan = [];
        $arr_jabatan = [];
        $arr_level = [];
        foreach(($payroll->toArray()) as $data){
            array_push($arr_nik, $data['nik']);
            array_push($arr_findim_subdepartemen, explode('/', $data['findim_subdepartemen'])[0]);
            array_push($arr_findim_departemen, explode('/', $data['findim_departemen'])[0]);
            array_push($arr_findim_divisi, explode('/', $data['findim_divisi'])[0]);
            array_push($arr_findim_project, explode('/', $data['findim_project'])[0]);
            array_push($arr_divisi, $data['divisi']);
            array_push($arr_departemen, $data['departemen']);
            array_push($arr_subdepartemen, $data['subdepartemen']);
            array_push($arr_status_karyawan, $data['status_karyawan']);
            array_push($arr_jabatan, $data['jabatan']);
            array_push($arr_level, $data['level']);
        }
        // 
        $karyawan = Karyawan::select('nama','nik')->whereIn('nik', $arr_nik)->orderBy('nama', 'asc')->get();
        $findim_subdepartemen = MtrFindimSubdepartemen::whereIn('subdepartemen_id', $arr_findim_subdepartemen)->orderBy('subdepartemen_id', 'asc')->get();
        $findim_departemen = MtrFindimDepartemen::whereIn('departemen_id', $arr_findim_departemen)->orderBy('departemen_id', 'asc')->get();
        $findim_divisi = MtrFindimDivisi::whereIn('divisi_id', $arr_findim_divisi)->orderBy('divisi_id', 'asc')->get();
        $findim_project = MtrFindimProject::whereIn('project_id', $arr_findim_project)->orderBy('project_id', 'asc')->get();
        $divisi = Divisi::whereIn('nama', $arr_divisi)->orderBy('nama', 'asc')->groupBy('nama')->get();
        $departemen = Departemen::whereIn('nama', $arr_departemen)->orderBy('nama', 'asc')->groupBy('nama')->get();
        $subdepartemen = SubDepartemen::whereIn('nama', $arr_subdepartemen)->orderBy('nama', 'asc')->groupBy('nama')->get();
        $status_karyawan = KaryawanStatus::whereIn('status_karyawan', $arr_status_karyawan)->orderBy('status_karyawan', 'asc')->groupBy('status_karyawan')->get();
        $jabatan = Jabatan::whereIn('jabatan', $arr_jabatan)->orderBy('jabatan', 'asc')->groupBy('jabatan')->get();
        $level = Level::whereIn('level', $arr_level)->orderBy('id', 'asc')->groupBy('level')->get();
        // filter parameter
        $bulan_tahun_dari = '';
        $bulan_tahun_sampai = '';
        $_arr_nik = [];
        $_arr_findim_subdepartemen = [];
        $_arr_findim_departemen = [];
        $_arr_findim_divisi = [];
        $_arr_findim_project = [];
        $_arr_divisi = [];
        $_arr_departemen = [];
        $_arr_subdepartemen = [];
        $_arr_status_karyawan = [];
        $_arr_jabatan = [];
        $_arr_level = [];
        // default only one month before and year displayed
        $where = ['bulan'=>Carbon::now()->subMonth()->month, 'tahun'=>Carbon::now()->subMonth()->year];
        $payroll = TrxPayrollLog::where(['status'=>'apm'])->where($where)->orderByRaw('tahun desc, bulan desc')->get();
        // 
        $payroll_out = TrxPayrollLog::where(['status'=>'osc'])->where($where)->orderByRaw('tahun desc, bulan desc')->get();
        // start filter
        if($request->filter == true){
            $bulan_tahun_dari = $request->bulan_tahun_dari;
            $bulan_tahun_sampai = $request->bulan_tahun_sampai;
            $_arr_nik = [$request->nik];
            $_arr_findim_subdepartemen = $request->input('findim_subdepartemen', []);
            $_arr_findim_departemen = $request->input('findim_departemen', []);
            $_arr_findim_divisi = $request->input('findim_divisi', []);
            $_arr_findim_project = $request->input('findim_project', []);
            $_arr_divisi = $request->input('divisi', []);
            $_arr_departemen = $request->input('departemen', []);
            $_arr_subdepartemen = $request->input('subdepartemen', []);
            $_arr_status_karyawan = $request->input('status_karyawan', []);
            $_arr_jabatan = $request->input('jabatan', []);
            $_arr_level = $request->input('level', []);
            $payroll = TrxPayrollLog::where(['status'=>'apm'])->whereIn('nik', $_arr_nik)
            // ->whereIn('findim_subdepartemen', $_arr_findim_subdepartemen)
            // ->whereIn('findim_departemen', $_arr_findim_departemen)
            // ->whereIn('findim_divisi', $_arr_findim_divisi)
            // ->whereIn('findim_project', $_arr_findim_project)
            // ->whereIn('divisi', $_arr_divisi)
            // ->whereIn('departemen', $_arr_departemen)
            // ->whereIn('subdepartemen', $_arr_subdepartemen)
            ->whereIn('status_karyawan', $_arr_status_karyawan)
            // ->whereIn('jabatan', $_arr_jabatan)
            ->whereIn('level', $_arr_level)
            ->whereBetween(DB::raw('DATE(CONCAT(tahun, "-", bulan, "-01"))'), [$bulan_tahun_dari.'-01', $bulan_tahun_sampai.'-01'])
            ->orderByRaw('tahun desc, bulan desc')->get();
            // 
            $payroll_out = TrxPayrollLog::where(['status'=>'osc'])->whereIn('nik', $_arr_nik)
            ->whereIn('status_karyawan', $_arr_status_karyawan)
            ->whereIn('level', $_arr_level)
            ->whereBetween(DB::raw('DATE(CONCAT(tahun, "-", bulan, "-01"))'), [$bulan_tahun_dari.'-01', $bulan_tahun_sampai.'-01'])
            ->orderByRaw('tahun desc, bulan desc')->get();
            // 
            if(in_array('0', $_arr_nik)){
                // $payroll = TrxPayrollLog::where(['status'=>'apm'])->whereIn('findim_subdepartemen', $_arr_findim_subdepartemen)
                // ->whereIn('findim_departemen', $_arr_findim_departemen)
                // ->whereIn('findim_divisi', $_arr_findim_divisi)
                // ->whereIn('findim_project', $_arr_findim_project)
                // ->whereIn('divisi', $_arr_divisi)
                // ->whereIn('departemen', $_arr_departemen)
                // ->whereIn('subdepartemen', $_arr_subdepartemen)
                $payroll = TrxPayrollLog::where(['status'=>'apm'])->whereIn('status_karyawan', $_arr_status_karyawan)
                // ->whereIn('jabatan', $_arr_jabatan)
                ->whereIn('level', $_arr_level)
                ->whereBetween(DB::raw('DATE(CONCAT(tahun, "-", bulan, "-01"))'), [$bulan_tahun_dari.'-01', $bulan_tahun_sampai.'-01'])
                ->orderByRaw('tahun desc, bulan desc')->get();
                // 
                $payroll_out = TrxPayrollLog::where(['status'=>'osc'])->whereIn('status_karyawan', $_arr_status_karyawan)
                ->whereIn('level', $_arr_level)
                ->whereBetween(DB::raw('DATE(CONCAT(tahun, "-", bulan, "-01"))'), [$bulan_tahun_dari.'-01', $bulan_tahun_sampai.'-01'])
                ->orderByRaw('tahun desc, bulan desc')->get();
            }
        }
        // end filter
        return view('payroll.history', compact('payroll', 'payroll_out', 'karyawan', 'bulan_tahun_dari', 'bulan_tahun_sampai', 'findim_subdepartemen', 'findim_departemen', 'findim_divisi', 'findim_project', 'divisi', 'departemen', 'subdepartemen', 'status_karyawan', 'jabatan', 'level', '_arr_nik', '_arr_findim_subdepartemen', '_arr_findim_departemen', '_arr_findim_divisi', '_arr_findim_project', '_arr_divisi', '_arr_departemen', '_arr_subdepartemen', '_arr_status_karyawan', '_arr_jabatan', '_arr_level'));
        // return view('payroll.history', compact('payroll'));
    }

    // http://localhost/matta/payroll/bkp/0
    // https://matta-reborn.alita.id/matta/payroll/bkp/0
    public function bkp($id)
    {
        // // bkp MtrPayroll
        // $trx = MtrPayroll::all();
        // foreach($trx as $_trx){
        //     MtrPayrollLog::insert(array(
        //         'nik' => $_trx->nik,
        //         'nik' => $_trx->nik,
        //         'bulan' => $_trx->bulan,
        //         'tahun' => $_trx->tahun,
        //         'gapok' => $_trx->gapok,
        //         'status' => $_trx->status,
        //         'pasangan' => $_trx->pasangan,
        //         'anak' => $_trx->anak,
        //         'tambahan' => $_trx->tambahan,
        //         'no_ktp' => $_trx->no_ktp,
        //         'no_kpj' => $_trx->no_kpj,
        //         'no_jkn' => $_trx->no_jkn,
        //         'nama_rek' => $_trx->nama_rek,
        //         'nama_bank' => $_trx->nama_bank,
        //         'no_rek' => $_trx->no_rek,
        //         'created_at' => Carbon::parse($_trx->created_at) ->format('Y-m-d H:i:s'),
        //         'updated_at' => Carbon::parse($_trx->updated_at) ->format('Y-m-d H:i:s'),
        //     ));
        // }
        // bkp TrxPayroll
        $trx = TrxPayroll::all();
        foreach($trx as $_trx){
            // kesehatan data bulan berjalan
            $date_from = Carbon::createFromFormat('Y-m-d', "{$_trx->tahun}-{$_trx->bulan}-01")->startOfMonth()->format('Y-m-d');
            $date_to = Carbon::createFromFormat('Y-m-d', "{$_trx->tahun}-{$_trx->bulan}-01")->endOfMonth()->format('Y-m-d');
            // $total_kesehatan = 0;
            // 4 = Kehamilan
            $kehamilan = DB::select(DB::raw("SELECT CASE WHEN SUM(a.jumlah_klaim) IS NULL THEN 0 ELSE SUM(a.jumlah_klaim) END AS jumlah_klaim FROM trx_tbl_reimburse_detail AS a LEFT JOIN trx_tbl_reimburse_master AS b ON a.id_reimburse=b.id WHERE b.nik_karyawan='".$_trx->nik."' AND DATE(b.updated_at) BETWEEN '".$date_from."' AND '".$date_to."' AND b.status_pengajuan=2 AND a.id_layanan IN (4)"))[0]->jumlah_klaim;
            // $total_kesehatan += $kehamilan;
            // 5,6 = Persalinan-Normal dan Persalinan-Caesar
            $persalinan = DB::select(DB::raw("SELECT CASE WHEN SUM(a.jumlah_klaim) IS NULL THEN 0 ELSE SUM(a.jumlah_klaim) END AS jumlah_klaim FROM trx_tbl_reimburse_detail AS a LEFT JOIN trx_tbl_reimburse_master AS b ON a.id_reimburse=b.id WHERE b.nik_karyawan='".$_trx->nik."' AND DATE(b.updated_at) BETWEEN '".$date_from."' AND '".$date_to."' AND b.status_pengajuan=2 AND a.id_layanan IN (5,6)"))[0]->jumlah_klaim;
            // $total_kesehatan += $persalinan;
            // 2,3 = Kacamata-Frame dan Kacamata-Lensa
            $kacamata = DB::select(DB::raw("SELECT CASE WHEN SUM(a.jumlah_klaim) IS NULL THEN 0 ELSE SUM(a.jumlah_klaim) END AS jumlah_klaim FROM trx_tbl_reimburse_detail AS a LEFT JOIN trx_tbl_reimburse_master AS b ON a.id_reimburse=b.id WHERE b.nik_karyawan='".$_trx->nik."' AND DATE(b.updated_at) BETWEEN '".$date_from."' AND '".$date_to."' AND b.status_pengajuan=2 AND a.id_layanan IN (2,3)"))[0]->jumlah_klaim;
            // $total_kesehatan += $kacamata;
            // 1 = Kesehatan
            $kesehatan = DB::select(DB::raw("SELECT CASE WHEN SUM(a.jumlah_klaim) IS NULL THEN 0 ELSE SUM(a.jumlah_klaim) END AS jumlah_klaim FROM trx_tbl_reimburse_detail AS a LEFT JOIN trx_tbl_reimburse_master AS b ON a.id_reimburse=b.id WHERE b.nik_karyawan='".$_trx->nik."' AND DATE(b.updated_at) BETWEEN '".$date_from."' AND '".$date_to."' AND b.status_pengajuan=2 AND a.id_layanan IN (1)"))[0]->jumlah_klaim;
            // $total_kesehatan += $kesehatan;
            // 7 = Ekses
            $ekses = DB::select(DB::raw("SELECT CASE WHEN SUM(a.jumlah_klaim) IS NULL THEN 0 ELSE SUM(a.jumlah_klaim) END AS jumlah_klaim FROM trx_tbl_reimburse_detail AS a LEFT JOIN trx_tbl_reimburse_master AS b ON a.id_reimburse=b.id WHERE b.nik_karyawan='".$_trx->nik."' AND DATE(b.updated_at) BETWEEN '".$date_from."' AND '".$date_to."' AND b.status_pengajuan=2 AND a.id_layanan IN (7)"))[0]->jumlah_klaim;
            // $total_kesehatan += $ekses;
            // tidak digunakan lagi karena sudah diambil dari trx_tbl_payroll
            // overtime data bulan lalu
            // start
            // $date_from = Carbon::createFromFormat('Y-m-d', "{$_trx->tahun}-{$_trx->bulan}-01")->subMonth()->startOfMonth()->format('Y-m-d');
            // $date_to = Carbon::createFromFormat('Y-m-d', "{$_trx->tahun}-{$_trx->bulan}-01")->subMonth()->endOfMonth()->format('Y-m-d');
            // $where = ['ot_maker_by'=>$_trx->nik, 'ot_approver_status'=>'Approve'];
            // $overtime = Overtime::select(DB::raw('SUM(ROUND(ot_length_rp_adj,-3)) as ot_length_rp_adj'))->where($where)->whereBetween(DB::raw('DATE(ot_start_at)'), [$date_from, $date_to])->orderBy( 'ot_start_at', 'desc' )->groupBy('ot_maker_by')->get();
            // $overtime = $overtime->sum('ot_length_rp_adj');
            // end
            // if($_trx->gapok == 0){
            //     $kehamilan = 0;
            //     $persalinan = 0;
            //     $kacamata = 0;
            //     $kesehatan = 0;
            //     $ekses = 0;
            //     $overtime = 0;
            // }
            TrxPayrollLog::insert(array(
                'nik' => $_trx->nik,
                'nik_property' => $_trx->nik_property,
                'bulan' => $_trx->bulan,
                'tahun' => $_trx->tahun,
                'gapok' => $_trx->gapok,
                'status' => $_trx->status,
                'kehamilan' => $kehamilan,
                'persalinan' => $persalinan,
                'kacamata' => $kacamata,
                'kesehatan' => $kesehatan,
                'ekses' => $ekses,
                'overtime' => $_trx->overtime,
                'overtime_max' => $_trx->overtime_max,
                'overtime_ori' => $_trx->overtime_ori,
                'created_by' => $_trx->created_by,
                'updated_by' => $_trx->updated_by,
                'created_at' => Carbon::parse($_trx->created_at) ->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::parse($_trx->updated_at) ->format('Y-m-d H:i:s'),
                'findim_subdepartemen' => $_trx->findim_subdepartemen,
                'findim_departemen' => $_trx->findim_departemen,
                'findim_divisi' => $_trx->findim_divisi,
                'findim_project' => $_trx->findim_project,
                'perusahaan' => $_trx->perusahaan,
                'divisi' => $_trx->divisi,
                'departemen' => $_trx->departemen,
                'subdepartemen' => $_trx->subdepartemen,
                'status_karyawan' => $_trx->status_karyawan,
                'status_aktif' => $_trx->status_aktif,
                'jabatan' => $_trx->jabatan,
                'level' => $_trx->level,
                'grade' => $_trx->grade,
                'paid_at' => $_trx->paid_at,
                'pajak' => $_trx->pajak,
                'mgt_fee' => $_trx->mgt_fee,
                'mgt_fee_kkwt' => $_trx->mgt_fee_kkwt,
            ));
        }
        // bkp TrxPayrollAddsub
        $trx = TrxPayrollAddsub::all();
        foreach($trx as $_trx){
            TrxPayrollAddsubLog::insert(array(
                'nik' => $_trx->nik,
                'bulan' => $_trx->bulan,
                'tahun' => $_trx->tahun,
                'angka' => $_trx->angka,
                'id_addsub' => $_trx->id_addsub,
                'tipe' => $_trx->tipe,
                'status' => $_trx->status,
                'created_by' => $_trx->created_by,
                'updated_by' => $_trx->updated_by,
                'created_at' => Carbon::parse($_trx->created_at) ->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::parse($_trx->updated_at) ->format('Y-m-d H:i:s'),
            ));
        }
        // bkp TrxPayrollBpjstk
        $trx = TrxPayrollBpjstk::all();
        foreach($trx as $_trx){
            TrxPayrollBpjstkLog::insert(array(
                'nik' => $_trx->nik,
                'bulan' => $_trx->bulan,
                'tahun' => $_trx->tahun,
                'upah' => $_trx->upah,
                'maksimal' => $_trx->maksimal,
                'angka' => $_trx->angka,
                'persen' => $_trx->persen,
                'id_bpjstk' => $_trx->id_bpjstk,
                'status' => $_trx->status,
                'created_by' => $_trx->created_by,
                'updated_by' => $_trx->updated_by,
                'created_at' => Carbon::parse($_trx->created_at) ->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::parse($_trx->updated_at) ->format('Y-m-d H:i:s'),
                'thp_dilaporkan' => $_trx->thp_dilaporkan,
            ));
        }
        // bkp TrxPayrollBpjskes
        $trx = TrxPayrollBpjskes::all();
        foreach($trx as $_trx){
            TrxPayrollBpjskesLog::insert(array(
                'nik' => $_trx->nik,
                'bulan' => $_trx->bulan,
                'tahun' => $_trx->tahun,
                'pasangan' => $_trx->pasangan,
                'anak' => $_trx->anak,
                'tambahan' => $_trx->tambahan,
                'upah' => $_trx->upah,
                'maksimal' => $_trx->maksimal,
                'angka' => $_trx->angka,
                'persen' => $_trx->persen,
                'id_bpjskes' => $_trx->id_bpjskes,
                'status' => $_trx->status,
                'created_by' => $_trx->created_by,
                'updated_by' => $_trx->updated_by,
                'created_at' => Carbon::parse($_trx->created_at) ->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::parse($_trx->updated_at) ->format('Y-m-d H:i:s'),
                'thp_dilaporkan' => $_trx->thp_dilaporkan,
            ));
        }
        // menjadi bulan dan tahun berjalan
        // MtrPayroll::whereNotNull('id')->update(['bulan' => Carbon::now()->month, 'tahun' => Carbon::now()->year]);
        TrxPayroll::truncate();
        TrxPayrollAddsub::truncate();
        TrxPayrollBpjstk::truncate();
        TrxPayrollBpjskes::truncate();
        // dd('done');
    }

    public function upsert_master(Request $request)
    {
        // dd($request->all());
        $id_data = $request->input('id_data');
        if(!empty($id_data)){
            $id_data = (new OpenDataController)->decryptWithKey($id_data);
        }
        // karyawan
        if(in_array($request->_id, [0])){
            if($request->tipe == 'edit_karyawan'){
                $trx = MtrPayroll::find($id_data);
                // $trx->nik = $request->nik;
                $trx->pasangan = $request->pasangan;
                $trx->anak = $request->anak;
                $trx->tambahan = $request->tambahan;
                $trx->no_ktp = $request->no_ktp;
                $trx->no_kpj = $request->no_kpj;
                $trx->no_jkn = $request->no_jkn;
                $trx->nama_rek = $request->nama_rek;
                $trx->nama_bank = $request->nama_bank;
                $trx->no_rek = $request->no_rek;
                $trx->update();
                return redirect('payroll/master/'.$request->_id)->with('msg', 'Data is saved successfully.');
            }
            $trx = New MtrPayroll;
                $trx->nik = $request->nik;
                $trx->pasangan = $request->pasangan;
                $trx->anak = $request->anak;
                $trx->tambahan = $request->tambahan;
                $trx->no_ktp = $request->no_ktp;
                $trx->no_kpj = $request->no_kpj;
                $trx->no_jkn = $request->no_jkn;
                $trx->nama_rek = $request->nama_rek;
                $trx->nama_bank = $request->nama_bank;
                $trx->no_rek = $request->no_rek;
            $trx->save();
            return redirect('payroll/master/'.$request->_id)->with('msg', 'Data is saved successfully.');
        }
        // add dan sub
        elseif(in_array($request->_id, [1, 2])){
            if($request->tipe == 'edit'){
                $trx = MtrPayrollAddsub::find($id_data);
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
                    $trx = MtrPayrollBpjstk::find($id_data);
                    $trx->nama = $request->nama;
                    $trx->persen = $request->persen;
                }elseif(in_array($request->tipe, ['edit_bpjsks'])){
                    $trx = MtrPayrollBpjskes::find($id_data);
                    $trx->nama = $request->nama;
                    $trx->persen = $request->persen;
                }elseif(in_array($request->tipe, ['edit_bpjsmx'])){
                    $trx = MtrPayrollBpjsMax::find($id_data);
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
        // trxpayroll
        // TrxPayrollAddsub
        elseif(in_array($request->_id, [6])){
            $data = $request->all();
            if($request->tipe == 'edit_trxpayroll'){
                $trx = TrxPayroll::find($id_data);
                $trx->gapok = str_replace(',', '', $request->gapok);
                $trx->update();
                foreach($data as $_id => $value){
                    if(str_starts_with($_id, 'add_angka_') || str_starts_with($_id, 'sub_angka_')){
                        $addsub = TrxPayrollAddsub::find(explode('_', $_id)[2]);
                        $addsub->angka = str_replace(',', '', $value);
                        $addsub->update();
                    }
                }
                return redirect('payroll')->with('msg', 'Data is saved successfully.');
            }
            $trx = New TrxPayroll;
            $trx->nik = $request->nik;
            $trx->nik_property = $this->getNikProperty($request->nik);
            $trx->bulan = $request->bulan;
            $trx->tahun = $request->tahun;
            $trx->gapok = str_replace(',', '', $request->gapok);
            $trx->save();
            foreach($data as $_id => $value){
                if(str_starts_with($_id, 'add_angka_') || str_starts_with($_id, 'sub_angka_')){
                    $addsub = New TrxPayrollAddsub;
                    $addsub->nik = $request->nik;
                    $addsub->bulan = $request->bulan;
                    $addsub->tahun = $request->tahun;
                    $addsub->id_addsub = explode('_', $_id)[2];
                    $addsub->tipe = explode('_', $_id)[0];
                    $addsub->angka = str_replace(',', '', $value);
                    $addsub->save();
                }
            }
            return redirect('payroll')->with('msg', 'Data is saved successfully.');
        }
    }

    public function upload_payroll(Request $request)
    {
        // dd($request->all());
        set_time_limit(3600);
        $jenis = $request->jenis;
        if($request->hasFile('template')){
            $request->validate([
                'template'=>'required|file|mimes:xls,xlsx|max:2048',
            ]);
            // upload transaksi present
            if($jenis == 'trx'){
                $path = $request->file('template')->getPathname();
                $trx = Excel::toArray([], $path);
                // data payroll dan penambahan
                $data0 = $trx[0];
                // data potongan
                // $data1 = $trx[1];
                // 100=Sumber
                // 101=NIP
                // 102=Bulan
                // 103=Tahun
                // 104=Gaji Pokok
                // 204=BPJS Kesehatan
                // 307=BPJS Ketenagakerjaan
                // 401=PPh21
                // 501=Management Fee (%)
                // 502=Management Fee Kompensasi KKWT(%)
                $_100 = array_search(100, $data0[0]);
                $_101 = array_search(101, $data0[0]);
                $_102 = array_search(102, $data0[0]);
                $_103 = array_search(103, $data0[0]);
                $_104 = array_search(104, $data0[0]);
                $_204 = array_search(204, $data0[0]);
                $_307 = array_search(307, $data0[0]);
                $_401 = array_search(401, $data0[0]);
                $_501 = array_search(501, $data0[0]);
                $_502 = array_search(502, $data0[0]);
                // dimulai dari baris ke-3
                // data payroll
                for ($x = 2; $x < count($data0); $x++) {
                    // delete data existing
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103]];
                    TrxPayroll::where($where)->delete();
                    TrxPayrollAddsub::where($where)->delete();
                    TrxPayrollBpjstk::where($where)->delete();
                    TrxPayrollBpjskes::where($where)->delete();
                    TrxPayrollEmail::where($where)->delete();
                    // 
                    $where = ['nik' => $data0[$x][$_101]];
                    $karyawan = Karyawan::where($where)->first();
                    // $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103]];
                    // TrxPayroll::where($where)->delete();
                    $trx = New TrxPayroll;
                    $trx->status = $data0[$x][$_100];
                    $trx->nik = $data0[$x][$_101];
                    $trx->nik_property = $this->getNikProperty($data0[$x][$_101]);
                    $trx->bulan = $data0[$x][$_102];
                    $trx->tahun = $data0[$x][$_103];
                    $trx->gapok = $data0[$x][$_104];
                    $trx->findim_subdepartemen = $karyawan->_findim_subdepartemen->subdepartemen_id.'/'.$karyawan->_findim_subdepartemen->subdepartemen_desc;
                    $trx->findim_departemen = $karyawan->_findim_departemen->departemen_id.'/'.$karyawan->_findim_departemen->departemen_desc;
                    $trx->findim_divisi = $karyawan->_findim_divisi->divisi_id.'/'.$karyawan->_findim_divisi->divisi_desc;
                    $trx->findim_project = $karyawan->_findim_project->project_id.'/'.$karyawan->_findim_project->project_desc;
                    $trx->perusahaan = $karyawan->_perusahaan->nama;
                    $trx->divisi = $karyawan->_divisi->nama;
                    $trx->departemen = $karyawan->_departemen->nama;
                    $trx->subdepartemen = $karyawan->_subdepartemen->nama;
                    $trx->status_karyawan = $karyawan->_status->status_karyawan;
                    $trx->status_aktif = 'Aktif';
                    $trx->jabatan = $karyawan->_jabatan->jabatan;
                    $trx->level = $karyawan->_level->level;
                    $trx->grade = $karyawan->_grade->grade;
                    $trx->created_by = auth()->user()->nik;
                    $trx->updated_by = auth()->user()->nik;
                    // overtime data bulan lalu
                    // start
                    $date_from = Carbon::createFromFormat('Y-m-d', "{$data0[$x][$_103]}-{$data0[$x][$_102]}-01")->subMonth()->startOfMonth()->format('Y-m-d');
                    $date_to = Carbon::createFromFormat('Y-m-d', "{$data0[$x][$_103]}-{$data0[$x][$_102]}-01")->subMonth()->endOfMonth()->format('Y-m-d');
                    $where = ['ot_maker_by'=>$data0[$x][$_101], 'ot_approver_status'=>'Approve'];
                    $overtime = Overtime::select(DB::raw('SUM(ROUND(ot_length_rp_adj,-3)) as ot_length_rp_adj'))->where($where)->whereBetween(DB::raw('DATE(ot_start_at)'), [$date_from, $date_to])->orderBy( 'ot_start_at', 'desc' )->groupBy('ot_maker_by')->get();
                    $overtime = $overtime->sum('ot_length_rp_adj');
                    // end
                    $trx->overtime = $overtime;
                    $trx->overtime_max = MtrUtility::find(33)->informasi;
                    // overtime_ori sama dengan overtime
                    $trx->overtime_ori = $overtime;
                    // hanya outsource saja
                    if($data0[$x][$_100] == 'osc'){
                        // 401=PPh21
                        $trx->pajak = $data0[$x][$_401];
                        // parameter tidak digunakan lagi
                        // 4=Management Fee(%)
                        // $trx->mgt_fee = MtrPayrollBpjsMax::find(4)->angka;
                        // 5=Management Fee Kompensasi KKWT(%)
                        // $trx->mgt_fee_kkwt = MtrPayrollBpjsMax::find(5)->angka;
                        // 
                        // 501=Management Fee (%)
                        $trx->mgt_fee = $data0[$x][$_501]*100;
                        // 502=Management Fee Kompensasi KKWT(%)
                        $trx->mgt_fee_kkwt = $data0[$x][$_502]*100;
                        // dd($data0[$x][$_501], $data0[$x][$_502]);
                    }
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
                        // $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_addsub'=>$_arr[1]];
                        // TrxPayrollAddsub::where($where)->delete();
                        $trx = New TrxPayrollAddsub;
                        $trx->status = $data0[$x][$_100];
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
                    }
                    // hanya alita saja
                    if($data0[$x][$_100] == 'apm'){
                        // insert into bpjs
                        $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103]];
                        $payroll = TrxPayroll::where($where)->orderBy('id', 'asc')->get();
                        $this->insertBpjs($payroll);
                        // membandingkan data 1 bulan sebelumnya untuk mengecek apakah ada penambahan Insentif dan atau Kompensasi KKWT
                        $is_insentif = false;
                        $is_kompensasi = false;
                        // 6=Insentif
                        $arr_id = [6];
                        $insentif = TrxPayrollAddsub::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get()->sum('angka');
                        // 9=Kompensasi KKWT
                        $arr_id = [9];
                        $kompensasi = TrxPayrollAddsub::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get()->sum('angka');
                        $submonth = Carbon::createFromFormat('Y-m-d', "{$data0[$x][$_103]}-{$data0[$x][$_102]}-01")->subMonth();
                        $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$submonth->month, 'tahun'=>$submonth->year];
                        $arr_id = [6];
                        $insentif_submonth = TrxPayrollAddsubLog::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get()->sum('angka');
                        $arr_id = [9];
                        $kompensasi_submonth = TrxPayrollAddsubLog::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get()->sum('angka');
                        if($insentif > $insentif_submonth){
                            $is_insentif = true;
                        }
                        if($kompensasi > $kompensasi_submonth){
                            $is_kompensasi = true;
                        }
                        $karyawan = Karyawan::where(['nik'=>$data0[$x][$_101]])->first();
                        $email_hco = array();
                        $hco = Karyawan::whereIn('nik', explode(',', MtrUtility::find(32)->informasi))->get();
                        foreach($hco as $data){
                            array_push($email_hco, $data->email);
                        }
                        // insert into payroll_email
                        if($is_insentif && $is_kompensasi){
                            // delete data existing
                            $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103]];
                            TrxPayrollEmail::where($where)->delete();
                            // insert new data
                            $trx = New TrxPayrollEmail;
                            $trx->nik = $data0[$x][$_101];
                            $trx->nik_property = $this->getNikProperty($data0[$x][$_101]);
                            $trx->bulan = $data0[$x][$_102];
                            $trx->tahun = $data0[$x][$_103];
                            $subject = 'Penambahan Insentif dan Kompensasi PKWT';
                            $trx->email = json_encode(['nik'=>$data0[$x][$_101], 'subject'=>$subject, 'body'=>$this->getBodyEmail($karyawan->nama, $data0[$x][$_102], $data0[$x][$_103], 69), 'to'=>$karyawan->email, 'cc'=>implode(',', $email_hco)]);
                            $trx->id_addsub = '6,9';
                            $trx->save();
                        }elseif($is_insentif){
                            // delete data existing
                            $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103]];
                            TrxPayrollEmail::where($where)->delete();
                            // insert new data
                            $trx = New TrxPayrollEmail;
                            $trx->nik = $data0[$x][$_101];
                            $trx->nik_property = $this->getNikProperty($data0[$x][$_101]);
                            $trx->bulan = $data0[$x][$_102];
                            $trx->tahun = $data0[$x][$_103];
                            $subject = 'Penambahan Insentif';
                            $trx->email = json_encode(['nik'=>$data0[$x][$_101], 'subject'=>$subject, 'body'=>$this->getBodyEmail($karyawan->nama, $data0[$x][$_102], $data0[$x][$_103], 6), 'to'=>$karyawan->email, 'cc'=>implode(',', $email_hco)]);
                            $trx->id_addsub = '6';
                            $trx->save();
                        }elseif($is_kompensasi){
                            // delete data existing
                            $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103]];
                            TrxPayrollEmail::where($where)->delete();
                            // insert new data
                            $trx = New TrxPayrollEmail;
                            $trx->nik = $data0[$x][$_101];
                            $trx->nik_property = $this->getNikProperty($data0[$x][$_101]);
                            $trx->bulan = $data0[$x][$_102];
                            $trx->tahun = $data0[$x][$_103];
                            $subject = 'Penambahan Kompensasi PKWT';
                            $trx->email = json_encode(['nik'=>$data0[$x][$_101], 'subject'=>$subject, 'body'=>$this->getBodyEmail($karyawan->nama, $data0[$x][$_102], $data0[$x][$_103], 9), 'to'=>$karyawan->email, 'cc'=>implode(',', $email_hco)]);
                            $trx->id_addsub = '9';
                            $trx->save();
                        }
                    }
                    // hanya outsource saja
                    elseif($data0[$x][$_100] == 'osc'){
                        // insert into bpjs
                        $data_bpjs = ['status'=>$data0[$x][$_100], 'nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'bpjsks'=>$data0[$x][$_204], 'bpjstk'=>$data0[$x][$_307]];
                        $this->insertBpjsOut($data_bpjs);
                    }
                }
                // // data potongan
                // $addsub = MtrPayrollAddsub::whereIn('id', $data1[0])->orderBy('id', 'asc')->get();
                // $arr = [];
                // foreach($addsub as $_addsub){
                //     if(in_array($_addsub->id, $data1[0])){
                //         $_arr = [];
                //         array_push($_arr, array_search($_addsub->id, $data1[0]), $_addsub->id, $_addsub->tipe);
                //     }
                //     array_push($arr, $_arr);
                // }
                // for ($x = 2; $x < count($data1); $x++) {
                //     foreach($arr as $_arr){
                //         $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_addsub'=>$_arr[1]];
                //         TrxPayrollAddsub::where($where)->delete();
                //         $trx = New TrxPayrollAddsub;
                //         $trx->nik = $data1[$x][$_101];
                //         $trx->bulan = $data1[$x][$_102];
                //         $trx->tahun = $data1[$x][$_103];
                //         // 0=index data
                //         $trx->angka = $data1[$x][$_arr[0]];
                //         // 1=id_addsub
                //         $trx->id_addsub = $_arr[1];
                //         // 2=tipe
                //         $trx->tipe = $_arr[2];
                //         $trx->created_by = auth()->user()->nik;
                //         $trx->updated_by = auth()->user()->nik;
                //         $trx->save();
                //     }
                // }
                return redirect('payroll')->with('msg', 'Data is saved successfully.');
            }
            // upload transaksi past
            elseif($jenis == 'trx_past'){
                $path = $request->file('template')->getPathname();
                $trx = Excel::toArray([], $path);
                // data payroll dan penambahan
                $data0 = $trx[0];
                // data potongan
                // $data1 = $trx[1];
                // 100=Sumber
                // 101=NIP
                // 102=Bulan
                // 103=Tahun
                // 104=Gaji Pokok
                $_100 = array_search(100, $data0[0]);
                $_101 = array_search(101, $data0[0]);
                $_102 = array_search(102, $data0[0]);
                $_103 = array_search(103, $data0[0]);
                $_104 = array_search(104, $data0[0]);
                // delete data existing
                $where = ['nik'=>$data0[2][$_101], 'bulan'=>$data0[2][$_102], 'tahun'=>$data0[2][$_103]];
                TrxPayrollLog::where($where)->delete();
                TrxPayrollAddsubLog::where($where)->delete();
                TrxPayrollBpjstkLog::where($where)->delete();
                TrxPayrollBpjskesLog::where($where)->delete();
                // dimulai dari baris ke-3
                // data payroll
                for ($x = 2; $x < count($data0); $x++) {
                    $where = ['nik' => $data0[$x][$_101]];
                    $karyawan = Karyawan::where($where)->first();
                    // $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103]];
                    // TrxPayroll::where($where)->delete();
                    $trx = New TrxPayrollLog;
                    $trx->status = $data0[$x][$_100];
                    $trx->nik = $data0[$x][$_101];
                    $trx->nik_property = $this->getNikProperty($data0[$x][$_101]);
                    $trx->bulan = $data0[$x][$_102];
                    $trx->tahun = $data0[$x][$_103];
                    $trx->gapok = $data0[$x][$_104];
                    $trx->findim_subdepartemen = $karyawan->_findim_subdepartemen->subdepartemen_id.'/'.$karyawan->_findim_subdepartemen->subdepartemen_desc;
                    $trx->findim_departemen = $karyawan->_findim_departemen->departemen_id.'/'.$karyawan->_findim_departemen->departemen_desc;
                    $trx->findim_divisi = $karyawan->_findim_divisi->divisi_id.'/'.$karyawan->_findim_divisi->divisi_desc;
                    $trx->findim_project = $karyawan->_findim_project->project_id.'/'.$karyawan->_findim_project->project_desc;
                    $trx->perusahaan = $karyawan->_perusahaan->nama;
                    $trx->divisi = $karyawan->_divisi->nama;
                    $trx->departemen = $karyawan->_departemen->nama;
                    $trx->subdepartemen = $karyawan->_subdepartemen->nama;
                    $trx->status_karyawan = $karyawan->_status->status_karyawan;
                    $trx->status_aktif = 'Aktif';
                    $trx->jabatan = $karyawan->_jabatan->jabatan;
                    $trx->level = $karyawan->_level->level;
                    $trx->grade = $karyawan->_grade->grade;
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
                        $trx = New TrxPayrollAddsubLog;
                        $trx->status = $data0[$x][$_100];
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
                    }
                    // insert into bpjs
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103]];
                    $payroll = TrxPayrollLog::where($where)->orderBy('id', 'asc')->get();
                    $this->insertBpjsPast($payroll);
                }
                return redirect('payroll/history/0')->with('msg', 'Data is saved successfully.');
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
            // upload thr present
            elseif($jenis == 'thr'){
                $path = $request->file('template')->getPathname();
                $trx = Excel::toArray([], $path);
                // data thr
                $data0 = $trx[0];
                // 101=NIP
                // 102=Bulan
                // 103=Tahun
                // 21=Tunjangan Hari Raya
                $_101 = array_search(101, $data0[0]);
                $_102 = array_search(102, $data0[0]);
                $_103 = array_search(103, $data0[0]);
                $_21 = array_search(21, $data0[0]);
                // dimulai dari baris ke-3
                // data thr
                for ($x = 2; $x < count($data0); $x++) {
                    // 21
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_addsub'=>21];
                    $trx = TrxPayrollAddsub::where($where)->first();
                    $trx->angka = $data0[$x][$_21];
                    $trx->update();
                }
                return redirect('payroll')->with('msg', 'Data is saved successfully.');
            }
            // upload thr past
            elseif($jenis == 'thr_past'){
                $path = $request->file('template')->getPathname();
                $trx = Excel::toArray([], $path);
                // data thr
                $data0 = $trx[0];
                // 101=NIP
                // 102=Bulan
                // 103=Tahun
                // 21=Tunjangan Hari Raya
                $_101 = array_search(101, $data0[0]);
                $_102 = array_search(102, $data0[0]);
                $_103 = array_search(103, $data0[0]);
                $_21 = array_search(21, $data0[0]);
                // dimulai dari baris ke-3
                // data thr
                for ($x = 2; $x < count($data0); $x++) {
                    // 21
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_addsub'=>21];
                    $trx = TrxPayrollAddsubLog::where($where)->first();
                    $trx->angka = $data0[$x][$_21];
                    $trx->update();
                }
                return redirect('payroll/history/0')->with('msg', 'Data is saved successfully.');
            }
            // upload bpjstk
            elseif($jenis == 'bpjstk'){
                $path = $request->file('template')->getPathname();
                $trx = Excel::toArray([], $path);
                // data bpjstk
                $data0 = $trx[0];
                // 101=NIP
                // 102=Bulan
                // 103=Tahun
                // 300=Upah Dilaporkan BPJS TK
                // 301=Iuran JKK
                // 302=Iuran JKM
                // 303=Iuran JP PRSHN
                // 304=Iuran JP TK
                // 305=Iuran JHT PRSHN
                // 306=Iuran JHT TK
                $_101 = array_search(101, $data0[0]);
                $_102 = array_search(102, $data0[0]);
                $_103 = array_search(103, $data0[0]);
                $_300 = array_search(300, $data0[0]);
                $_301 = array_search(301, $data0[0]);
                $_302 = array_search(302, $data0[0]);
                $_303 = array_search(303, $data0[0]);
                $_304 = array_search(304, $data0[0]);
                $_305 = array_search(305, $data0[0]);
                $_306 = array_search(306, $data0[0]);
                // dimulai dari baris ke-3
                // data bpjstk
                for ($x = 2; $x < count($data0); $x++) {
                    // 300
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103]];
                    TrxPayrollBpjstk::where($where)->update(['thp_dilaporkan' => $data0[$x][$_300]]);
                    // 1
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_bpjstk'=>1];
                    TrxPayrollBpjstk::where($where)->update(['angka' => $data0[$x][$_301]]);
                    // 2
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_bpjstk'=>2];
                    TrxPayrollBpjstk::where($where)->update(['angka' => $data0[$x][$_302]]);
                    // 3
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_bpjstk'=>3];
                    TrxPayrollBpjstk::where($where)->update(['angka' => $data0[$x][$_303]]);
                    // 4
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_bpjstk'=>4];
                    TrxPayrollBpjstk::where($where)->update(['angka' => $data0[$x][$_304]]);
                    // 5
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_bpjstk'=>5];
                    TrxPayrollBpjstk::where($where)->update(['angka' => $data0[$x][$_305]]);
                    // 6
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_bpjstk'=>6];
                    TrxPayrollBpjstk::where($where)->update(['angka' => $data0[$x][$_306]]);
                }
                return redirect('payroll')->with('msg', 'Data is saved successfully.');
            }
            // upload bpjsks
            elseif($jenis == 'bpjsks'){
                $path = $request->file('template')->getPathname();
                $trx = Excel::toArray([], $path);
                // data bpjsks
                $data0 = $trx[0];
                // 101=NIP
                // 102=Bulan
                // 103=Tahun
                // 200=Upah Dilaporkan BPJS KS
                // 201=Iuran Perusahaan
                // 202=Iuran Karyawan
                // 203=Iuran Karyawan Tambahan Anggota Keluarga
                $_101 = array_search(101, $data0[0]);
                $_102 = array_search(102, $data0[0]);
                $_103 = array_search(103, $data0[0]);
                $_200 = array_search(200, $data0[0]);
                $_201 = array_search(201, $data0[0]);
                $_202 = array_search(202, $data0[0]);
                $_203 = array_search(203, $data0[0]);
                // dimulai dari baris ke-3
                // data bpjsks
                for ($x = 2; $x < count($data0); $x++) {
                    // 200
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103]];
                    TrxPayrollBpjskes::where($where)->update(['thp_dilaporkan' => $data0[$x][$_200]]);
                    // 1
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_bpjskes'=>1];
                    TrxPayrollBpjskes::where($where)->update(['angka' => $data0[$x][$_201]]);
                    // 2
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_bpjskes'=>2];
                    TrxPayrollBpjskes::where($where)->update(['angka' => $data0[$x][$_202]]);
                    // 3
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_bpjskes'=>3];
                    TrxPayrollBpjskes::where($where)->update(['angka' => $data0[$x][$_203]]);
                }
                return redirect('payroll')->with('msg', 'Data is saved successfully.');
            }
            // upload bpjstk_past
            elseif($jenis == 'bpjstk_past'){
                $path = $request->file('template')->getPathname();
                $trx = Excel::toArray([], $path);
                // data bpjstk_past
                $data0 = $trx[0];
                // 101=NIP
                // 102=Bulan
                // 103=Tahun
                // 300=Upah Dilaporkan BPJS TK
                // 301=Iuran JKK
                // 302=Iuran JKM
                // 303=Iuran JP PRSHN
                // 304=Iuran JP TK
                // 305=Iuran JHT PRSHN
                // 306=Iuran JHT TK
                $_101 = array_search(101, $data0[0]);
                $_102 = array_search(102, $data0[0]);
                $_103 = array_search(103, $data0[0]);
                $_300 = array_search(300, $data0[0]);
                $_301 = array_search(301, $data0[0]);
                $_302 = array_search(302, $data0[0]);
                $_303 = array_search(303, $data0[0]);
                $_304 = array_search(304, $data0[0]);
                $_305 = array_search(305, $data0[0]);
                $_306 = array_search(306, $data0[0]);
                // dimulai dari baris ke-3
                // data bpjstk_past
                for ($x = 2; $x < count($data0); $x++) {
                    // 300
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103]];
                    TrxPayrollBpjstkLog::where($where)->update(['thp_dilaporkan' => $data0[$x][$_300]]);
                    // 1
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_bpjstk'=>1];
                    TrxPayrollBpjstkLog::where($where)->update(['angka' => $data0[$x][$_301]]);
                    // 2
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_bpjstk'=>2];
                    TrxPayrollBpjstkLog::where($where)->update(['angka' => $data0[$x][$_302]]);
                    // 3
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_bpjstk'=>3];
                    TrxPayrollBpjstkLog::where($where)->update(['angka' => $data0[$x][$_303]]);
                    // 4
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_bpjstk'=>4];
                    TrxPayrollBpjstkLog::where($where)->update(['angka' => $data0[$x][$_304]]);
                    // 5
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_bpjstk'=>5];
                    TrxPayrollBpjstkLog::where($where)->update(['angka' => $data0[$x][$_305]]);
                    // 6
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_bpjstk'=>6];
                    TrxPayrollBpjstkLog::where($where)->update(['angka' => $data0[$x][$_306]]);
                }
                return redirect('payroll/history/0')->with('msg', 'Data is saved successfully.');
            }
            // upload bpjsks_past
            elseif($jenis == 'bpjsks_past'){
                $path = $request->file('template')->getPathname();
                $trx = Excel::toArray([], $path);
                // data bpjsks_past
                $data0 = $trx[0];
                // 101=NIP
                // 102=Bulan
                // 103=Tahun
                // 200=Upah Dilaporkan BPJS KS
                // 201=Iuran Perusahaan
                // 202=Iuran Karyawan
                // 203=Iuran Karyawan Tambahan Anggota Keluarga
                $_101 = array_search(101, $data0[0]);
                $_102 = array_search(102, $data0[0]);
                $_103 = array_search(103, $data0[0]);
                $_200 = array_search(200, $data0[0]);
                $_201 = array_search(201, $data0[0]);
                $_202 = array_search(202, $data0[0]);
                $_203 = array_search(203, $data0[0]);
                // dimulai dari baris ke-3
                // data bpjsks_past
                for ($x = 2; $x < count($data0); $x++) {
                    // 200
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103]];
                    TrxPayrollBpjskesLog::where($where)->update(['thp_dilaporkan' => $data0[$x][$_200]]);
                    // 1
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_bpjskes'=>1];
                    TrxPayrollBpjskesLog::where($where)->update(['angka' => $data0[$x][$_201]]);
                    // 2
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_bpjskes'=>2];
                    TrxPayrollBpjskesLog::where($where)->update(['angka' => $data0[$x][$_202]]);
                    // 3
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103], 'id_bpjskes'=>3];
                    TrxPayrollBpjskesLog::where($where)->update(['angka' => $data0[$x][$_203]]);
                }
                return redirect('payroll/history/0')->with('msg', 'Data is saved successfully.');
            }
            // upload pajak
            elseif($jenis == 'pajak_present'){
                $path = $request->file('template')->getPathname();
                $trx = Excel::toArray([], $path);
                // data pajak
                $data0 = $trx[0];
                // 101=NIP
                // 102=Bulan
                // 103=Tahun
                // 401=PPh21
                $_101 = array_search(101, $data0[0]);
                $_102 = array_search(102, $data0[0]);
                $_103 = array_search(103, $data0[0]);
                $_401 = array_search(401, $data0[0]);
                // dimulai dari baris ke-3
                // data pajak
                for ($x = 2; $x < count($data0); $x++) {
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103]];
                    $trx = TrxPayroll::where($where)->first();
                    $trx->pajak = $data0[$x][$_401];
                    $trx->update();
                }
                return redirect('payroll')->with('msg', 'Data is saved successfully.');
            }
            // upload pajak
            elseif($jenis == 'pajak_past'){
                $path = $request->file('template')->getPathname();
                $trx = Excel::toArray([], $path);
                // data pajak
                $data0 = $trx[0];
                // 101=NIP
                // 102=Bulan
                // 103=Tahun
                // 401=PPh21
                $_101 = array_search(101, $data0[0]);
                $_102 = array_search(102, $data0[0]);
                $_103 = array_search(103, $data0[0]);
                $_401 = array_search(401, $data0[0]);
                // dimulai dari baris ke-3
                // data pajak
                for ($x = 2; $x < count($data0); $x++) {
                    $where = ['nik'=>$data0[$x][$_101], 'bulan'=>$data0[$x][$_102], 'tahun'=>$data0[$x][$_103]];
                    $trx = TrxPayrollLog::where($where)->first();
                    $trx->pajak = $data0[$x][$_401];
                    $trx->update();
                }
                return redirect('payroll/history/0')->with('msg', 'Data is saved successfully.');
            }
        }
        // 
        else{
            // update paid_at
            if($jenis == 'paid_at'){
                // dd('paid_at');
                // $paid_at = $request->paid_at;
                $trx = TrxPayroll::find($request->id);
                TrxPayroll::where(['bulan'=>$trx->bulan, 'tahun'=>$trx->tahun])->update(['paid_at' => $request->paid_at]);
                // dd($trx);
                return redirect('payroll')->with('msg', 'Data is saved successfully.');
            }
        }
    }
    
    public function master($id, Request $request)
    {
        // dd($id, $request->all());
        $id_data = $request->input('id_data');
        if(!empty($id_data)){
            $id_data = (new OpenDataController)->decryptWithKey($id_data);
        }
        $tipe = $request->input('tipe');
        $payroll = MtrPayroll::orderBy('id', 'asc')->get();
        $where = ['tipe'=>'add'];
        $add = MtrPayrollAddsub::where($where)->orderBy('id', 'asc')->get();
        $where = ['tipe'=>'sub'];
        $sub = MtrPayrollAddsub::where($where)->orderBy('id', 'asc')->get();
        $bpjstk = MtrPayrollBpjstk::orderBy('id', 'asc')->get();
        $bpjskes = MtrPayrollBpjskes::orderBy('id', 'asc')->get();
        $bpjsmax = MtrPayrollBpjsMax::orderBy('id', 'asc')->get();
        if(in_array($tipe, ['add', 'sub', 'edit', 'bpjstk', 'bpjsks', 'bpjsmx', 'edit_bpjstk', 'edit_bpjsks', 'edit_bpjsmx', 'karyawan', 'edit_karyawan', 'trxpayroll', 'edit_trxpayroll'])){
            $status = 'new';
            if($tipe == 'edit'){
                $status = 'edit';
                $addsub = MtrPayrollAddsub::find($id_data);
                return view('payroll.upsert_addsub', compact('addsub','status','id','tipe'));
            }
            elseif(in_array($tipe, ['add', 'sub'])){
                return view('payroll.upsert_addsub', compact('status','id','tipe'));
            }
            elseif(in_array($tipe, ['bpjstk', 'bpjsks', 'bpjsmx', 'edit_bpjstk', 'edit_bpjsks', 'edit_bpjsmx'])){$status = 'new';
                if(in_array($tipe, ['edit_bpjstk', 'edit_bpjsks', 'edit_bpjsmx'])){
                    $status = 'edit';
                    if(in_array($tipe, ['edit_bpjstk'])){
                        $bpjs = MtrPayrollBpjstk::find($id_data);
                    }elseif(in_array($tipe, ['edit_bpjsks'])){
                        $bpjs = MtrPayrollBpjskes::find($id_data);
                    }elseif(in_array($tipe, ['edit_bpjsmx'])){
                        $bpjs = MtrPayrollBpjsMax::find($id_data);
                    }
                    return view('payroll.upsert_bpjs', compact('bpjs','status','id','tipe'));
                }else{
                    return view('payroll.upsert_bpjs', compact('status','id','tipe'));
                }
            }
            elseif(in_array($tipe, ['karyawan', 'edit_karyawan'])){
                $karyawan = [];
                $where = ['is_aktif' => 1];
                $_karyawan = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
                if(in_array($tipe, ['edit_karyawan'])){
                    $status = 'edit';
                    $karyawan = MtrPayroll::find($id_data);
                }
                return view('payroll.upsert_karyawan', compact('_karyawan', 'karyawan', 'status','id','tipe'));
            }
            // trxpayroll
            elseif(in_array($tipe, ['trxpayroll', 'edit_trxpayroll'])){
                $trxpayroll = [];
                $add_trx = [];
                $sub_trx = [];
                $where = ['is_aktif' => 1];
                $_karyawan = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
                if(in_array($tipe, ['edit_trxpayroll'])){
                    $status = 'edit';
                    $trxpayroll = TrxPayroll::find($id_data);
                    $where = ['tipe'=>'add', 'nik'=>$trxpayroll->nik, 'bulan'=>$trxpayroll->bulan, 'tahun'=>$trxpayroll->tahun];
                    $add_trx = TrxPayrollAddsub::where($where)->orderBy('id', 'asc')->get();
                    $where = ['tipe'=>'sub', 'nik'=>$trxpayroll->nik, 'bulan'=>$trxpayroll->bulan, 'tahun'=>$trxpayroll->tahun];
                    $sub_trx = TrxPayrollAddsub::where($where)->orderBy('id', 'asc')->get();
                }
                return view('payroll.upsert_payroll', compact('_karyawan', 'trxpayroll', 'status','id','tipe', 'add', 'sub', 'add_trx', 'sub_trx'));
            }
        }else{
            return view('payroll.index_mtr', compact('payroll','add','sub','bpjstk','bpjskes','bpjsmax','id'));
        }
    }
    
    public function insertBpjs($payroll)
    {
        // dd($payroll);
        // $where = ['nik'=>$payroll[0]->nik, 'bulan'=>$payroll[0]->bulan, 'tahun'=>$payroll[0]->tahun];
        // TrxPayrollBpjstk::where($where)->delete();
        // TrxPayrollBpjskes::where($where)->delete();
        $arr_id = [1,2,3,4,5,6];
        $bpjstk = MtrPayrollBpjstk::whereIn('id', $arr_id)->orderBy('id', 'asc')->get();
        $arr_id = [1,2,3];
        $bpjskes = MtrPayrollBpjskes::whereIn('id', $arr_id)->orderBy('id', 'asc')->get();
        // 1=Upah Max BPJS TK
        // 2=Upah Max BPJS KS
        // 3=Anggota Keluarga BPJS KS
        $bpjsmax = MtrPayrollBpjsMax::orderBy('id', 'asc')->get();
        foreach ($payroll as $data){
            // TK
            foreach ($bpjstk as $_data){
                $trx = new TrxPayrollBpjstk;
                $trx->status = $data->status;
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
                // tidak digunakan, menjadi upload angka bpjstk
                // $trx->angka = $upah;
                // if($upah > $bpjsmax[0]->angka){
                //     $trx->angka = $bpjsmax[0]->angka;
                // }
                $trx->angka = 0;
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
                $trx->status = $data->status;
                $trx->nik = $data->nik;
                $trx->bulan = $data->bulan;
                $trx->tahun = $data->tahun;
                // 
                $where = ['nik'=>$data->nik, 'bulan'=>$data->bulan, 'tahun'=>$data->tahun];
                $mtr = MtrPayroll::where($where)->orderBy('id', 'asc')->first();
                if(!empty($mtr)){
                    $trx->pasangan = $mtr->pasangan;
                    $trx->anak = $mtr->anak;
                    $trx->tambahan = $mtr->tambahan;
                }
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
                // tidak digunakan, menjadi upload angka bpjstk
                // $trx->angka = $upah;
                // if($upah > $bpjsmax[1]->angka){
                //     $trx->angka = $bpjsmax[1]->angka;
                // }
                $trx->angka = 0;
                // 
                $trx->persen = $_data->persen;
                $trx->id_bpjskes = $_data->id;
                $trx->created_by = auth()->user()->nik;
                $trx->updated_by = auth()->user()->nik;
                $trx->save();
            }
        }
    }
    
    public function insertBpjsOut($data_bpjs)
    {
        // 7=BPJS Ketenagakerjaan
        $bpjstk = MtrPayrollBpjstk::find(7);
        // 4=BPJS Kesehatan
        $bpjskes = MtrPayrollBpjskes::find(4);
        // TK
        $trx = new TrxPayrollBpjstk;
        $trx->status = $data_bpjs['status'];
        $trx->nik = $data_bpjs['nik'];
        $trx->bulan = $data_bpjs['bulan'];
        $trx->tahun = $data_bpjs['tahun'];
        $trx->angka = $data_bpjs['bpjstk'];
        $trx->persen = $bpjstk->persen;
        $trx->id_bpjstk = $bpjstk->id;
        $trx->created_by = auth()->user()->nik;
        $trx->updated_by = auth()->user()->nik;
        $trx->save();
        // KS
        $trx = new TrxPayrollBpjskes;
        $trx->status = $data_bpjs['status'];
        $trx->nik = $data_bpjs['nik'];
        $trx->bulan = $data_bpjs['bulan'];
        $trx->tahun = $data_bpjs['tahun'];
        $trx->angka = $data_bpjs['bpjsks'];
        $trx->persen = $bpjskes->persen;
        $trx->id_bpjskes = $bpjskes->id;
        $trx->created_by = auth()->user()->nik;
        $trx->updated_by = auth()->user()->nik;
        $trx->save();
    }
    
    public function insertBpjsPast($payroll)
    {
        $bpjstk = MtrPayrollBpjstk::orderBy('id', 'asc')->get();
        $bpjskes = MtrPayrollBpjskes::orderBy('id', 'asc')->get();
        // 1=Upah Max BPJS TK
        // 2=Upah Max BPJS KS
        // 3=Anggota Keluarga BPJS KS
        $bpjsmax = MtrPayrollBpjsMax::orderBy('id', 'asc')->get();
        foreach ($payroll as $data){
            // TK
            foreach ($bpjstk as $_data){
                $trx = new TrxPayrollBpjstkLog;
                $trx->status = $data->status;
                $trx->nik = $data->nik;
                $trx->bulan = $data->bulan;
                $trx->tahun = $data->tahun;
                // 
                $where = ['nik'=>$data->nik, 'tipe'=>'add'];
                $arr_id = [1,2,3,4,5];
                $add = TrxPayrollAddsubLog::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                $total_tunjangan = $add->sum('angka');
                // 
                $upah = $data->gapok + $total_tunjangan;
                $trx->upah = $upah;
                // 
                $trx->maksimal = $bpjsmax[0]->angka;
                // tidak digunakan, menjadi upload angka bpjstk
                // $trx->angka = $upah;
                // if($upah > $bpjsmax[0]->angka){
                //     $trx->angka = $bpjsmax[0]->angka;
                // }
                $trx->angka = 0;
                // 
                $trx->persen = $_data->persen;
                $trx->id_bpjstk = $_data->id;
                $trx->created_by = auth()->user()->nik;
                $trx->updated_by = auth()->user()->nik;
                $trx->save();
            }
            // KS
            foreach ($bpjskes as $_data){
                $trx = new TrxPayrollBpjskesLog;
                $trx->status = $data->status;
                $trx->nik = $data->nik;
                $trx->bulan = $data->bulan;
                $trx->tahun = $data->tahun;
                // 
                $where = ['nik'=>$data->nik, 'bulan'=>$data->bulan, 'tahun'=>$data->tahun];
                $mtr = MtrPayroll::where($where)->orderBy('id', 'asc')->first();
                if(!empty($mtr)){
                    $trx->pasangan = $mtr->pasangan;
                    $trx->anak = $mtr->anak;
                    $trx->tambahan = $mtr->tambahan;
                }
                // 
                $where = ['nik'=>$data->nik, 'tipe'=>'add'];
                $arr_id = [1,2,3,4,5];
                $add = TrxPayrollAddsubLog::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                $total_tunjangan = $add->sum('angka');
                // 
                $upah = $data->gapok + $total_tunjangan;
                $trx->upah = $upah;
                // 
                $trx->maksimal = $bpjsmax[1]->angka;
                // tidak digunakan, menjadi upload angka bpjstk
                // $trx->angka = $upah;
                // if($upah > $bpjsmax[1]->angka){
                //     $trx->angka = $bpjsmax[1]->angka;
                // }
                $trx->angka = 0;
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
    public function index(Request $request)
    {
        // $trx = TrxPayroll::get();
        // foreach($trx as $_trx){
        //     $nik_property = json_decode($_trx->nik_property, true);
        //     $_trx_ = TrxPayroll::find($_trx->id);
        //     $_trx_->perusahaan = $nik_property['perusahaan'];
        //     $_trx_->update();
        // }
        // 
        // $trx = TrxPayrollLog::get();
        // foreach($trx as $_trx){
        //     $nik_property = json_decode($_trx->nik_property, true);
        //     $_trx_ = TrxPayrollLog::find($_trx->id);
        //     $_trx_->perusahaan = $nik_property['perusahaan'];
        //     $_trx_->update();
        // }
        // dd($trx);
        // dd($request->all());
        // $month = '11';
        // $year = '2024';
        // dd(Carbon::createFromFormat('Y-m-d', "{$year}-{$month}-01")->subMonth()->startOfMonth()->format('Y-m-d'));
        // dd(Carbon::createFromFormat('Y-m-d', "{$year}-{$month}-01")->subMonth()->endOfMonth()->format('Y-m-d'));
        // $payroll = TrxPayroll::where(['status'=>'apm'])->orderByRaw('tahun desc, bulan desc')->get();
        $payroll = TrxPayroll::orderByRaw('tahun desc, bulan desc')->get();
        if(in_array(auth()->user()->nik, explode(',', MtrUtility::find(36)->informasi))){
            $payroll = TrxPayroll::where(['status'=>'apm'])->orderByRaw('tahun desc, bulan desc')->get();
        }
        // 
        $arr_nik = [];
        $arr_findim_subdepartemen = [];
        $arr_findim_departemen = [];
        $arr_findim_divisi = [];
        $arr_findim_project = [];
        $arr_divisi = [];
        $arr_departemen = [];
        $arr_subdepartemen = [];
        $arr_status_karyawan = [];
        $arr_jabatan = [];
        $arr_level = [];
        foreach(($payroll->toArray()) as $data){
            array_push($arr_nik, $data['nik']);
            array_push($arr_findim_subdepartemen, explode('/', $data['findim_subdepartemen'])[0]);
            array_push($arr_findim_departemen, explode('/', $data['findim_departemen'])[0]);
            array_push($arr_findim_divisi, explode('/', $data['findim_divisi'])[0]);
            array_push($arr_findim_project, explode('/', $data['findim_project'])[0]);
            array_push($arr_divisi, $data['divisi']);
            array_push($arr_departemen, $data['departemen']);
            array_push($arr_subdepartemen, $data['subdepartemen']);
            array_push($arr_status_karyawan, $data['status_karyawan']);
            // array_push($arr_jabatan, $data['jabatan']);
            array_push($arr_level, $data['level']);
        }
        // 
        $karyawan = Karyawan::select('nama','nik')->whereIn('nik', $arr_nik)->orderBy('nama', 'asc')->get();
        $findim_subdepartemen = MtrFindimSubdepartemen::whereIn('subdepartemen_id', $arr_findim_subdepartemen)->orderBy('subdepartemen_id', 'asc')->get();
        $findim_departemen = MtrFindimDepartemen::whereIn('departemen_id', $arr_findim_departemen)->orderBy('departemen_id', 'asc')->get();
        $findim_divisi = MtrFindimDivisi::whereIn('divisi_id', $arr_findim_divisi)->orderBy('divisi_id', 'asc')->get();
        $findim_project = MtrFindimProject::whereIn('project_id', $arr_findim_project)->orderBy('project_id', 'asc')->get();
        $divisi = Divisi::whereIn('nama', $arr_divisi)->orderBy('nama', 'asc')->groupBy('nama')->get();
        $departemen = Departemen::whereIn('nama', $arr_departemen)->orderBy('nama', 'asc')->groupBy('nama')->get();
        $subdepartemen = SubDepartemen::whereIn('nama', $arr_subdepartemen)->orderBy('nama', 'asc')->groupBy('nama')->get();
        $status_karyawan = KaryawanStatus::whereIn('status_karyawan', $arr_status_karyawan)->orderBy('status_karyawan', 'asc')->groupBy('status_karyawan')->get();
        $jabatan = Jabatan::whereIn('jabatan', $arr_jabatan)->orderBy('jabatan', 'asc')->groupBy('jabatan')->get();
        $level = Level::whereIn('level', $arr_level)->orderBy('id', 'asc')->groupBy('level')->get();
        // filter parameter
        $bulan_tahun_dari = '';
        $bulan_tahun_sampai = '';
        $_arr_nik = [];
        $_arr_findim_subdepartemen = [];
        $_arr_findim_departemen = [];
        $_arr_findim_divisi = [];
        $_arr_findim_project = [];
        $_arr_divisi = [];
        $_arr_departemen = [];
        $_arr_subdepartemen = [];
        $_arr_status_karyawan = [];
        $_arr_jabatan = [];
        $_arr_level = [];
        // default only current month and year displayed
        $where = ['bulan'=>Carbon::now()->month, 'tahun'=>Carbon::now()->year];
        $payroll = TrxPayroll::where(['status'=>'apm'])->where($where)->orderByRaw('tahun desc, bulan desc')->get();
        $payroll_out = TrxPayroll::where(['status'=>'osc'])->where($where)->orderByRaw('tahun desc, bulan desc')->get();
        // start filter
        if($request->filter == true){
            $bulan_tahun_dari = $request->bulan_tahun_dari;
            $bulan_tahun_sampai = $request->bulan_tahun_sampai;
            $_arr_nik = [$request->nik];
            $_arr_findim_subdepartemen = $request->input('findim_subdepartemen', []);
            $_arr_findim_departemen = $request->input('findim_departemen', []);
            $_arr_findim_divisi = $request->input('findim_divisi', []);
            $_arr_findim_project = $request->input('findim_project', []);
            $_arr_divisi = $request->input('divisi', []);
            $_arr_departemen = $request->input('departemen', []);
            $_arr_subdepartemen = $request->input('subdepartemen', []);
            $_arr_status_karyawan = $request->input('status_karyawan', []);
            // $_arr_jabatan = $request->input('jabatan', []);
            $_arr_level = $request->input('level', []);
            $payroll = TrxPayroll::where(['status'=>'apm'])->whereIn('nik', $_arr_nik)
            // ->whereIn('findim_subdepartemen', $_arr_findim_subdepartemen)
            // ->whereIn('findim_departemen', $_arr_findim_departemen)
            // ->whereIn('findim_divisi', $_arr_findim_divisi)
            // ->whereIn('findim_project', $_arr_findim_project)
            // ->whereIn('divisi', $_arr_divisi)
            // ->whereIn('departemen', $_arr_departemen)
            // ->whereIn('subdepartemen', $_arr_subdepartemen)
            ->whereIn('status_karyawan', $_arr_status_karyawan)
            // ->whereIn('jabatan', $_arr_jabatan)
            ->whereIn('level', $_arr_level)
            ->whereBetween(DB::raw('DATE(CONCAT(tahun, "-", bulan, "-01"))'), [$bulan_tahun_dari.'-01', $bulan_tahun_sampai.'-01'])
            ->orderByRaw('tahun desc, bulan desc')->get();
            // 
            $payroll_out = TrxPayroll::where(['status'=>'osc'])->whereIn('nik', $_arr_nik)
            ->whereIn('status_karyawan', $_arr_status_karyawan)
            ->whereIn('level', $_arr_level)
            ->whereBetween(DB::raw('DATE(CONCAT(tahun, "-", bulan, "-01"))'), [$bulan_tahun_dari.'-01', $bulan_tahun_sampai.'-01'])
            ->orderByRaw('tahun desc, bulan desc')->get();
            if(in_array('0', $_arr_nik)){
                // $payroll = TrxPayroll::where(['status'=>'apm'])->whereIn('findim_subdepartemen', $_arr_findim_subdepartemen)
                // ->whereIn('findim_departemen', $_arr_findim_departemen)
                // ->whereIn('findim_divisi', $_arr_findim_divisi)
                // ->whereIn('findim_project', $_arr_findim_project)
                // ->whereIn('divisi', $_arr_divisi)
                // ->whereIn('departemen', $_arr_departemen)
                // ->whereIn('subdepartemen', $_arr_subdepartemen)
                $payroll = TrxPayroll::where(['status'=>'apm'])->whereIn('status_karyawan', $_arr_status_karyawan)
                // ->whereIn('jabatan', $_arr_jabatan)
                ->whereIn('level', $_arr_level)
                ->whereBetween(DB::raw('DATE(CONCAT(tahun, "-", bulan, "-01"))'), [$bulan_tahun_dari.'-01', $bulan_tahun_sampai.'-01'])
                ->orderByRaw('tahun desc, bulan desc')->get();
                // 
                $payroll_out = TrxPayroll::where(['status'=>'osc'])->whereIn('status_karyawan', $_arr_status_karyawan)
                ->whereIn('level', $_arr_level)
                ->whereBetween(DB::raw('DATE(CONCAT(tahun, "-", bulan, "-01"))'), [$bulan_tahun_dari.'-01', $bulan_tahun_sampai.'-01'])
                ->orderByRaw('tahun desc, bulan desc')->get();
            }
        }
        // end filter
        return view('payroll.index', compact('payroll', 'payroll_out', 'karyawan', 'bulan_tahun_dari', 'bulan_tahun_sampai', 'findim_subdepartemen', 'findim_departemen', 'findim_divisi', 'findim_project', 'divisi', 'departemen', 'subdepartemen', 'status_karyawan', 'jabatan', 'level', '_arr_nik', '_arr_findim_subdepartemen', '_arr_findim_departemen', '_arr_findim_divisi', '_arr_findim_project', '_arr_divisi', '_arr_departemen', '_arr_subdepartemen', '_arr_status_karyawan', '_arr_jabatan', '_arr_level'));
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
        // dd($request->all(), $id);
        $bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        $id = (new OpenDataController)->decryptWithKey($id);
        $payroll = null;
        $_view = $request->view;
        if($_view == 'pdf_h'){
            $payroll = TrxPayrollLog::find($id);
            // $view = \View::make('payroll.pdf_h', compact('payroll'));
        }elseif($_view == 'pdf'){
            $payroll = TrxPayroll::find($id);
            // $view = \View::make('payroll.pdf', compact('payroll'));
        }
        $pic_paraf = Karyawan::where(['nik' => MtrUtility::find(35)->informasi])->first();
        $pic_paraf = $pic_paraf->nama;
        $view = \View::make('payroll.pdf', compact('payroll', '_view', 'pic_paraf'));
        $password = Carbon::parse(Karyawan::where(['nik' => $payroll->nik])->first()->tanggal_lahir)->format("dmY");
        $filename = (new OpenDataController)->encryptWithKey($payroll->nik).' Slip Gaji '.$bulan[$payroll->bulan - 1].' '.$payroll->tahun.'.pdf';
        $html = $view->render();
        $pdf = new TCPDF;
        $pdf::SetTitle($filename);
        // $pdf::SetFont('times', '', 8, '', true);
        $pdf::SetFont('helvetica', '', 8, '', true);
        $pdf::AddPage('L', 'A4');
        $pdf::writeHTML($html, true, false, true, false, '');
        // $pdf::SetProtection(array('print', 'copy'), $password, $password);
        $pdf::SetProtection(array('print', 'copy'), $password, md5(123456), 0, null);
        // open browser
        // $pdf::Output(public_path($filename), 'I');
        // download pdf
        $pdf::Output(public_path($filename), 'D');
        return response()->download(public_path($filename));
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
        $_id = $request->id;
        if(!empty($_id)){
            $_id = (new OpenDataController)->decryptWithKey($_id);
        }
        // dd($_id, $id);
        try {
            // karyawan
            if(in_array($id, [0])){
                $trx = MtrPayroll::find($_id);
                $trx->delete();
            }
            // add dan sub
            elseif(in_array($id, [1, 2])){
                $trx = MtrPayrollAddsub::find($_id);
                $trx->delete();
            }
            // bpjstk
            elseif(in_array($id, [3])){
                $trx = MtrPayrollBpjstk::find($_id);
                $trx->delete();
            }
            // bpjsks
            elseif(in_array($id, [4])){
                $trx = MtrPayrollBpjskes::find($_id);
                $trx->delete();
            }
            // bpjsmx
            elseif(in_array($id, [5])){
                $trx = MtrPayrollBpjsMax::find($_id);
                $trx->delete();
            }
            // payroll
            elseif(in_array($id, [6])){
                $trx = TrxPayroll::find($_id);
                $nik = $trx->nik;
                $trx->delete();
                $where = ['nik' => $nik];
                TrxPayrollAddsub::where($where)->delete();
                TrxPayrollBpjstk::where($where)->delete();
                TrxPayrollBpjskes::where($where)->delete();
                return redirect( 'payroll' )->with( 'msg', 'Data is deleted successfully.' );
            }
            return redirect( 'payroll/master/'.$id )->with( 'msg', 'Data is deleted successfully.' );
         } catch (\Exception $e) {
            // Log::error($e->errorInfo);
            return redirect()->route('error_db');
         }
    }

    public function getBodyEmail($nama, $bulan, $tahun, $addsub)
    {
        $nama_bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        $body = '<p>Dear Bapak/Ibu '.$nama.'</p>';
        // 6=Insentif
        // 9=Kompensasi KKWT
        if($addsub == 69){
            $body .= '<p>Kami informasikan bahwa terdapat penambahan insentif dan penambahan kompensasi masa Perjanjian Kerja Karyawan (PKWT) yang telah berakhir pada akhir '.$nama_bulan[$bulan - 1].' '.$tahun.' dalam penggajian bulan '.$nama_bulan[$bulan - 1].' '.$tahun.'. Informasi lebih detail terdapat dalam slip gaji yang dapat diunduh melalui menu Payroll Slip dalam web Matta (https://matta-reborn.alita.id).</p>';
        }elseif($addsub == 6){
            $body .= '<p>Kami informasikan bahwa terdapat penambahan insentif dalam penggajian bulan '.$nama_bulan[$bulan - 1].' '.$tahun.'. Informasi lebih detail terdapat dalam slip gaji yang dapat diunduh melalui menu Payroll Slip dalam web Matta (https://matta-reborn.alita.id).</p>';
        }elseif($addsub == 9){
            $body .= '<p>Kami informasikan bahwa terdapat penambahan kompensasi masa Perjanjian Kerja Karyawan (PKWT) yang telah berakhir pada akhir '.$nama_bulan[$bulan - 1].' '.$tahun.' dalam penggajian bulan '.$nama_bulan[$bulan - 1].' '.$tahun.'. Informasi lebih detail terdapat dalam slip gaji yang dapat diunduh melalui menu Payroll Slip dalam web Matta (https://matta-reborn.alita.id).</p>';
        }
        $body .= '<p>Slip Gaji bersifat <strong style="color:red">PERSONAL & CONFIDENTIAL</strong>. Mohon untuk tidak diberitahukan kepada rekan kerja ataupun pihak lain dengan alasan apapun.</p>';
        $body .= '<p>Demikian kami sampaikan, terimakasih atas perhatian dan kerjasamanya.</p>';
        $body .= '<p>Best Regards,<br/>Human Capital & Organization<br/><a href="https://alita.id/" target="_blank">www.alita.id</a><br/><strong>"Passion - Integrity - Excellent"</strong></p>';
        return $body;
    }

    public function getNikProperty($nik)
    {
        $where = ['nik'=>$nik];
        // $data = Karyawan::select('mtr_tbl_karyawan.nik', 'mtr_tbl_karyawan.nama', 'mtr_tbl_karyawan.email', 'mtr_tbl_karyawan.telepon1', 'mtr_tbl_perusahaan.nama as perusahaan', 'mtr_tbl_divisi.nama as divisi', 'mtr_tbl_departemen.nama as dep', 'mtr_tbl_sub_departemen.nama as sub_dep', 'mtr_tbl_level.level as level', 'mtr_tbl_jabatan.jabatan as jabatan', 'mtr_tbl_level.id as id_level', 'mtr_tbl_status_karyawan.status_karyawan as status_karyawan')
        //     ->leftJoin('mtr_tbl_perusahaan', 'mtr_tbl_perusahaan.id', '=', 'mtr_tbl_karyawan.kode_perusahaan')
        //     ->leftJoin('mtr_tbl_divisi', 'mtr_tbl_divisi.id', '=', 'mtr_tbl_karyawan.kode_divisi')
        //     ->leftJoin('mtr_tbl_departemen', 'mtr_tbl_departemen.id', '=', 'mtr_tbl_karyawan.kode_departemen')
        //     ->leftJoin('mtr_tbl_sub_departemen', 'mtr_tbl_sub_departemen.id', '=', 'mtr_tbl_karyawan.kode_sub_departemen')
        //     ->leftJoin('mtr_tbl_level', 'mtr_tbl_level.id', '=', 'mtr_tbl_karyawan.kode_level')
        //     ->leftJoin('mtr_tbl_jabatan', 'mtr_tbl_jabatan.id', '=', 'mtr_tbl_karyawan.kode_jabatan')
        //     ->leftJoin('mtr_tbl_status_karyawan', 'mtr_tbl_status_karyawan.id', '=', 'mtr_tbl_karyawan.kode_status')
        //     ->where($where)->first();
        $data = DB::table('mtr_tbl_karyawan as a')->select('a.nik', 'a.nama', 'a.email', 'a.telepon1', 'b.nama as perusahaan', 'c.nama as divisi', 'd.nama as dep', 'e.nama as sub_dep', 'f.level as level', 'g.jabatan as jabatan', 'f.id as id_level', 'h.status_karyawan as status_karyawan', DB::Raw("CONCAT(i.subdepartemen_id, '/', i.subdepartemen_desc) as findim_subdepartemen"), DB::Raw("CONCAT(j.departemen_id, '/', j.departemen_desc) as findim_departemen"), DB::Raw("CONCAT(k.divisi_id, '/', k.divisi_desc) as findim_divisi"), DB::Raw("CONCAT(l.project_id, '/', l.project_desc) as findim_project"))
            ->leftJoin('mtr_tbl_perusahaan as b', 'b.id', '=', 'a.kode_perusahaan')
            ->leftJoin('mtr_tbl_divisi as c', 'c.id', '=', 'a.kode_divisi')
            ->leftJoin('mtr_tbl_departemen as d', 'd.id', '=', 'a.kode_departemen')
            ->leftJoin('mtr_tbl_sub_departemen as e', 'e.id', '=', 'a.kode_sub_departemen')
            ->leftJoin('mtr_tbl_level as f', 'f.id', '=', 'a.kode_level')
            ->leftJoin('mtr_tbl_jabatan as g', 'g.id', '=', 'a.kode_jabatan')
            ->leftJoin('mtr_tbl_status_karyawan as h', 'h.id', '=', 'a.kode_status')
            ->leftJoin('mtr_tbl_findim_subdepartemen as i', 'i.id', '=', 'a.findim_subdepartemen')
            ->leftJoin('mtr_tbl_findim_departemen as j', 'j.id', '=', 'a.findim_departemen')
            ->leftJoin('mtr_tbl_findim_divisi as k', 'k.id', '=', 'a.findim_divisi')
            ->leftJoin('mtr_tbl_findim_project as l', 'l.id', '=', 'a.findim_project')
            ->where($where)->first();
        return json_encode($data);
    }
}
