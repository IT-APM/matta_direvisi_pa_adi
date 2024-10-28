<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\TrxKebutuhanMaster;
use App\Models\TrxRecruitment;
use App\Models\MtrRecruitmentStatus;
use App\Models\MtrRecruitmentSoal;
use App\Models\TrxRecruitmentIdentitas;
use App\Models\TrxRecruitmentAnak;
use App\Models\TrxRecruitmentSaudara;
use App\Models\TrxRecruitmentFormal;
use App\Models\TrxRecruitmentInformal;
use App\Models\TrxRecruitmentOrganisasi;
use App\Models\TrxRecruitmentPekerjaan;
use App\Models\TrxRecruitmentPsychotest;
use App\Models\TrxRecruitmentInterview;
use App\Models\TrxRecruitmentLog;
use App\Models\MtrRecruitmentBenefit;
use App\Models\MtrRecruitmentCatatPegawai;
use App\Models\MtrRecruitmentStatusPegawai;
use App\Models\TrxRecruitmentNegosiasi;
use App\Models\TrxRecruitmentNegosiasiLog;
use App\Models\MtrUtility;
use App\Models\Karyawan;
use Spatie\Browsershot\Browsershot;
use App\Mail\MattaRebornMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Encryption\DecryptException;
use Carbon\Carbon;
use App\Exports\TrxRecruitmentPsychotestExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TrxRecruitmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $where = ['status_pengajuan' => 10];
        $trx = TrxKebutuhanMaster::where($where)->orderBy('id', 'desc')->get();
        return view('recruitment.index', compact('trx'));
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
        // dd($request->all());
        if(empty($request->job_description)){
            return redirect()->back()->with('error', 'Content is empty.');
        }
        // 
        $id_kebutuhan = Crypt::decrypt($request->id_kebutuhan);
        $where = ['id_kebutuhan' => $id_kebutuhan];
        $trx = TrxRecruitment::where($where)->orderBy('id', 'desc')->first();
        if(!empty($trx)){
            $trx->job_description = $request->job_description;
            $trx->update();
        }else{
            $trx = new TrxRecruitment;
            $trx->id_kebutuhan = Crypt::decrypt($request->id_kebutuhan);
            $trx->id_status = 2;
            $trx->job_description = $request->job_description;
            $trx->save();
        }
        // $link = 'http://localhost/matta/recruitment/eyJpdiI6IlFEMEo0eFBZampzRGg3dURuYml6UkE9PSIsInZhbHVlIjoiVnlOUi9ZV2NVbnJWMVk1UXM2anVhQT09IiwibWFjIjoiZjdmYTI0NzZmMmZlN2NlZmI0YzQ5OGNlOTYwNjk1MGVlMjk4ZGQ4NjQ4NmIyYTQxM2FiZTVlYWRiZDNiOWJkMyIsInRhZyI6IiJ9?view=posting';
        // Browsershot::url($link)->setScreenshotType('jpeg', 100)->windowSize(1920, 1080)->save('example.jpg');
        return redirect('recruitment')->with('msg', 'Data is saved successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // dd(Crypt::decrypt($id));
        $id = Crypt::decrypt($id);
        $id_kebutuhan = $id[0];
        $trx = TrxKebutuhanMaster::find($id_kebutuhan);
        $where = ['id_kebutuhan' => $id_kebutuhan];
        $_trx = TrxRecruitment::where($where)->orderBy('id', 'desc')->first();
        $default = '<div class="col-12 text-center"><span style="font-size:30px;"><strong>Urgently Needed!</strong></span></div>';
        $default .= '<div class="container">
                        <div class="row">
                            <div class="col-12 text-center text-uppercase"><span style="font-size:20px;color:orange;"><strong>'.$trx->_pekerjaan->pekerjaan.'</strong></span></div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-1">&nbsp;</div>
                            <div class="col-5">
                                <h5 class="text-start">Qualification</h5>
                                <table class="table table-borderless text-start" style="font-size:16px">
                                    <tbody>
                                        <tr>
                                            <td>'.$trx->_kelamin->kelamin.'<br/>'
                                            .$trx->_jenjang_pendidikan->jenjang.'<br/>Usia minimum '
                                            .$trx->umur_minimum.' tahun<br/>Usia maksimum '
                                            .$trx->umur_maksimum.' tahun<br/>'
                                            .$trx->_pengalaman->pengalaman.
                                            '</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-5">
                                <h5 class="text-start">Job Description</h5>
                                <table class="table table-borderless text-start" style="font-size:16px">
                                    <tbody>
                                        <tr>
                                            <td>'.$trx->tugas.'<br/>'
                                            .$trx->wewenang.'<br/>'
                                            .$trx->tanggung_jawab.
                                            '</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-1">&nbsp;</div>
                        </div>
                    </div>';
        if($id[1] == 2){
            return view('recruitment.posting', compact('trx', '_trx', 'default'));
        }elseif($id[1] == 1){
            return view('recruitment.lowongan', compact('trx', '_trx', 'default'));
        }
    }

    public function applicant_process($id)
    {
        $id = Crypt::decrypt($id);
        // dd($id);
        $where = ['id_identitas' => $id];
        $history = TrxRecruitmentLog::where($where)->orderBy('id', 'desc')->get();
        $trx = TrxRecruitmentIdentitas::find($id);
        $trxM = TrxKebutuhanMaster::find($trx->id_kebutuhan);
        $benefit = MtrRecruitmentBenefit::orderBy('benefit', 'asc')->get();
        $catat_pegawai = MtrRecruitmentCatatPegawai::orderBy('pencatatan', 'asc')->get();
        $status_pegawai = MtrRecruitmentStatusPegawai::orderBy('status', 'asc')->get();
        // 4=Psychotest Send
        // 6=Interview User_HCO Send
        // 8=Interview User Send
        // 10=Interview HCO Send
        $id_status = [4,6,8,10];
        $log_status = TrxRecruitmentLog::where(['id_identitas'=>$id])->pluck('id_status')->toArray();
        // 5=Psychotest Done
        if(in_array(5, $log_status)){
            $id_status = array_values(array_diff($id_status, [4]));
            // 16=Interpretation
            array_push($id_status, 16);
        }
        // negosiasi dilakukan setelah interpretasi dan done any interview
        // 16=Interpretation
        // 7=Interview User and HCO Done
        // 9=Interview User Done
        // 11=Interview HCO Done
        if(in_array(16, $log_status) && (in_array(7, $log_status)||in_array(9, $log_status)||in_array(11, $log_status))){
            // 12=Negotiation Process
            array_push($id_status, 12);
        }
        // 12=Negotiation Process
        if(in_array(12, $log_status)){
            $id_status = array_values(array_diff($id_status, [6,8,10,16]));
            // 13=Negotiation Deal
            // 14=Negotiation Failed
            $id_status = [13,14];
        }
        // 13=Negotiation Deal
        // 14=Negotiation Failed
        if(in_array(13, $log_status) || in_array(14, $log_status)){
            // 15=Recruitment Done
            $id_status = [15];
        }
        // 15=Recruitment Done
        if(in_array(15, $log_status)){
            $id_status = [];
        }
        // dd($log_status);
        $id_status = MtrRecruitmentStatus::whereIn('id', $id_status)->whereNotIn('id', $log_status)->orderBy('id', 'asc')->get();
        // kode_level=3=Supervisor (SPV)
        $where = ['is_aktif' => 1];
        $karyawan = Karyawan::select('nik', 'nama')->where('kode_level', '>=', 3)->where($where)->whereNotIn('kode_sub_departemen', [263])->orderBy('nama', 'asc')->get();
        $karyawan_user_hco = Karyawan::select('nik', 'nama')->where('kode_level', '>=', 3)->where($where)->orderBy('nama', 'asc')->get();
        $where = ['is_aktif' => 1, 'kode_sub_departemen' => 263];
        $karyawan_hco = Karyawan::select('nik', 'nama')->where('kode_level', '>=', 3)->where($where)->orderBy('nama', 'asc')->get();
        return view('recruitment.applicant_process', compact('trx', 'id_status', 'karyawan', 'karyawan_hco', 'karyawan_user_hco', 'history', 'trxM', 'benefit', 'catat_pegawai', 'status_pegawai'));
        
    }

    // public function generateCustomQRCode()
    // {
    //     $image = QrCode::format('png')
    //                     ->size(300)
    //                     ->color(255, 0, 0)
    //                     ->backgroundColor(255, 255, 255)
    //                     ->margin(1)
    //                     ->merge('/public/assets/images/matta/alita_50.png', .3)
    //                     ->generate('Customize your QR code content here');
    //     return view('customQrCode', ['image' => $image]);
    // }

    public function apply($id)
    {
        try {
            $id_recruitment = Crypt::decrypt($id)[0];
            // dd(Crypt::decrypt($id));
            $trx = TrxKebutuhanMaster::find(TrxRecruitment::find($id_recruitment)->id_kebutuhan);
            // $posisi = $trx->_pekerjaan->pekerjaan;
            // 1=Process
            // 2=Posting
            // 3=Apply
            $where = ['id'=>$id_recruitment, 'id_status'=>2];
            if(TrxRecruitment::where($where)->exists()){
                return view('recruitment.application', compact('id'));
            }else{
                return view('recruitment.no_vacancy', compact('id'));
            }
        } catch (DecryptException $e) {
            return view('errors.403');
        }
    }

    public function applicant_list($id)
    {
        // dd($id);
        $trx = TrxRecruitmentIdentitas::orderBy('id', 'desc')->get();
        $trx1 = TrxRecruitmentInterview::orderBy('id_identitas', 'asc')->orderBy('id', 'desc')->get();
        return view('recruitment.applicant_list', compact('trx', 'trx1'));
    }

    public function applicant_item($id)
    {
        $id = Crypt::decrypt($id);
        // dd($id);
        $trx = TrxRecruitmentIdentitas::find($id);
        $_trx = TrxKebutuhanMaster::find($trx->id_kebutuhan);
        // $posisi = $_trx->_pekerjaan->pekerjaan;
        return view('recruitment.applicant_item', compact('trx'));
    }

    public function interview_item($id)
    {
        $id = Crypt::decrypt($id);
        // dd($id);
        $trx = TrxRecruitmentInterview::find($id);
        $_trx = TrxRecruitmentIdentitas::find($trx->id_identitas);
        $where = ['is_aktif' => 1];
        $karyawan = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
        return view('recruitment.interview_item', compact('trx', '_trx', 'karyawan'));
    }

    public function negotiation_save(Request $request)
    {
        // dd($request->all());
        $id = Crypt::decrypt($request->id);
        $id_identitas = Crypt::decrypt($request->id_identitas);
        $id_status = $request->id_status;
        // dd($id, $id_identitas, $request->all());
        $trx = TrxRecruitmentNegosiasi::find($id);
        $trx->id_status = $id_status;
        // 
        $_trx = TrxRecruitmentIdentitas::find($id_identitas);
        $_trx->id_status = $id_status;
        // start
        // id_identitas,id_status,pic,pic_property,aktifitas,keterangan
        $arr = [$_trx->id,$_trx->id_status,$_trx->nama,'','negosiasi',$request->catatan];
        if($request->hasFile('lampiran')){
            $lampiran = $request->file('lampiran');
            $request->validate([
                'lampiran' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
            ]);
            $extension = $lampiran->getClientOriginalExtension();
            $namafile = $id.'_'.now()->getTimestampMs().'.'.$extension;
            $lampiran->move('public/assets/recruitment/upload/', $namafile);
            array_push($arr, $namafile);
        }
        $trx->update();
        // 
        $_trx->update();
        // 
        $this->insertNegotiationLog($trx);
        $this->insertLog($arr);
        // end
        // kirim email
        $info = 'Proses Negosiasi Disetujui Kandidat';
        if($id_status == 14){
            $info = 'Proses Negosiasi Tidak Disetujui Kandidat';
        }
        $msg = '<p>Hai '.$_trx->nama.',<br/>'.$info.' sudah disampaikan.
        <br/>Kami akan menggunakan email anda ('.$_trx->email.') sebagai alamat email untuk korespondensi.<br/>Terima kasih,</p>
        <p>Best Regards,<br/>Human Capital & Organization<br/><a href="https://alita.id/" target="_blank">www.alita.id</a><br/><strong>"Passion - Integrity - Excellent"</strong></p>';
        // parameter yang dipassing ke sendemail
        $mailData['title'] = $info;
        $mailData['data'] = ['msg'=>$msg];
        $mailData['filename'] = 'emails.recruitment0';
        $mailData['to'] = $_trx->email;
        $this->sendEmail($mailData);
        return view('recruitment.thank', compact('msg'));
    }

    public function negotiation_process($id)
    {
        $id = Crypt::decrypt($id);
        // dd($id);
        $trx = TrxRecruitmentNegosiasi::where(['id_identitas'=>$id])->first();
        return view('recruitment.negotiation_process', compact('trx'));
    }

    public function insertNegotiationLog($_trx)
    {
        $trx = new TrxRecruitmentNegosiasiLog;
        $trx->id_identitas = $_trx->id_identitas;
        $trx->id_status = $_trx->id_status;
        $trx->posisi = $_trx->posisi;
        $trx->area_kerja = $_trx->area_kerja;
        $trx->divisi = $_trx->divisi;
        $trx->thp = $_trx->thp;
        $trx->benefit_utama = $_trx->benefit_utama;
        $trx->benefit_lainnya = $_trx->benefit_lainnya;
        $trx->tgl_bergabung = $_trx->tgl_bergabung;
        $trx->id_catat_pegawai = $_trx->id_catat_pegawai;
        $trx->id_status_pegawai = $_trx->id_status_pegawai;
        $trx->masa_kerja = $_trx->masa_kerja;
        $trx->save();
    }

    public function negotiation_send(Request $request)
    {
        // dd($request->all());
        $id = Crypt::decrypt($request->id);
        // dd($id);
        $id_status = $request->id_status;
        $trx = new TrxRecruitmentNegosiasi;
        $trx->id_identitas = $id;
        $trx->id_status = $id_status;
        $trx->posisi = $request->posisi;
        $trx->area_kerja = $request->area_kerja;
        $trx->divisi = $request->divisi;
        $trx->thp = str_replace(',', '', $request->thp);
        $trx->benefit_utama = implode(',', $request->input('benefit_utama', []));
        $trx->benefit_lainnya = $request->benefit_lainnya;
        $trx->tgl_bergabung = $request->tgl_bergabung;
        $trx->id_catat_pegawai = $request->id_catat_pegawai;
        $trx->id_status_pegawai = $request->id_status_pegawai;
        $trx->masa_kerja = $request->masa_kerja;
        $trx->save();
        // 
        $this->insertNegotiationLog($trx);
        // 
        $trx = TrxRecruitmentIdentitas::find($id);
        $trx->id_status = $id_status;
        $trx->update();
        // parameter yang dipassing ke sendemail
        $mailData['title'] = 'Proses Negosiasi';
        $mailData['filename'] = 'emails.recruitment3';
        $mailData['data'] = ['trx' => $trx];
        $mailData['to'] = $trx->email;
        $this->sendEmail($mailData);
        // start
        // id_identitas,id_status,pic,pic_property,aktifitas,keterangan
        $nik = auth()->user()->nik;
        $nama = Karyawan::where(['nik'=>$nik])->first()->nama;
        $arr = [$trx->id,$id_status,$nama.'/'.$nik,'','negosiasi',''];
        $this->insertLog($arr);
        return redirect('recruitment/applicant_list/0')->with('msg', 'Data is saved successfully.');
    }

    public function interview_save(Request $request)
    {
        // dd($request->all());
        $id = Crypt::decrypt($request->id);
        // dd($id);
        $id_status = $request->id_status;
        // 6=Interview User_HCO Send
        // 8=Interview User Send
        // 10=Interview HCO Send
        if($id_status == 6 || $id_status == 8 || $id_status == 10){
            $_trx = TrxRecruitmentIdentitas::find($id);
            $metode = $request->metode;
            $trx = new TrxRecruitmentInterview;
            $trx->id_identitas = $id;
            $trx->id_status = $id_status;
            $trx->jenis = 'user';
            if($id_status == 10){
                $trx->jenis = 'hco';
            }elseif($id_status == 6){
                $trx->jenis = 'user_hco';
            }
            $trx->interviewer = implode(';', $request->interviewer);
            $trx->metode = $metode;
            $trx->check_in = $request->check_in;
            $trx->check_out = $request->check_out;
            $trx->save();
            // parameter yang dipassing ke sendemail
            $mailData['title'] = 'Undangan Interview '.$metode;
            $mailData['filename'] = 'emails.recruitment2';
            $mailData['data'] = ['trx' => $trx, 'metode' => $metode];
            $mailData['to'] = $_trx->email;
            $mailData['cc'] = Karyawan::whereIn('nik', $request->interviewer)->pluck('email')->toArray();
            $this->sendEmail($mailData);
            // return redirect()->to($request->back_url)->with('msg', 'Data is saved successfully.');
        }
        // 16=Interpretation
        // upload file Interpretation
        elseif($id_status == 16){
            $_trx = TrxRecruitmentIdentitas::find($id);
            $_trx->id_status = $id_status;
            if($request->hasFile('interpretasi')){
                $interpretasi = $request->file('interpretasi');
                $request->validate([
                    'interpretasi' => 'file|max:1024',
                ]);
                $extension = $interpretasi->getClientOriginalExtension();
                $namafile = $id.'_'.now()->getTimestampMs().'.'.$extension;
                $interpretasi->move('public/assets/recruitment/upload/', $namafile);
                $_trx->interpretasi = $namafile;
            }
            $_trx->update();
        }
        // 12=Negotiation Process
        elseif($id_status == 12){
            $_trx = TrxRecruitmentIdentitas::find($id);
            $_trx->id_status = $id_status;
            $_trx->update();
        }
        // 13=Negotiation Deal
        // 14=Negotiation Failed
        // upload file Negotiation
        elseif($id_status == 13 || $id_status == 14){
            $_trx = TrxRecruitmentIdentitas::find($id);
            $_trx->id_status = $id_status;
            if($request->hasFile('negosiasi')){
                $negosiasi = $request->file('negosiasi');
                $request->validate([
                    'negosiasi' => 'file|max:1024',
                ]);
                $extension = $negosiasi->getClientOriginalExtension();
                $namafile = $id.'_'.now()->getTimestampMs().'.'.$extension;
                $negosiasi->move('public/assets/recruitment/upload/', $namafile);
                $_trx->negosiasi = $namafile;
            }
            $_trx->update();
        }
        // 15=Recruitment Done
        elseif($id_status == 15){
            $_trx = TrxRecruitmentIdentitas::find($id);
            $_trx->id_status = $id_status;
            $_trx->update();
        }
        // $_trx = TrxRecruitmentIdentitas::find($trx->id_identitas);
        // $posisi = $_trx->_kebutuhan->_pekerjaan->pekerjaan;
        // $where = ['is_aktif' => 1];
        // $karyawan = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
        // $trx->interviewer = $request->interviewer;
        // $trx->check_in = $request->check_in;
        // $trx->check_out = $request->check_out;
        // 7=Interview Send
        // $id_status = 7;
        if($request->hasFile('file_interview')){
            $trx = TrxRecruitmentInterview::find($id);
            $_trx = TrxRecruitmentIdentitas::find($trx->id_identitas);
            $file_interview = $request->file('file_interview');
            $request->validate([
                'file_interview' => 'file|max:20480',
            ]);
            $extension = $file_interview->getClientOriginalExtension();
            $namafile = $trx->id_identitas.'_'.now()->getTimestampMs().'.'.$extension;
            $file_interview->move('public/assets/recruitment/upload/', $namafile);
            $trx->file_interview = $namafile;
            // 6=Interview User_HCO Send
            // 7=Interview User_HCO Done
            // 8=Interview User Send
            // 9=Interview User Done
            // 10=Interview HCO Send
            // 11=Interview HCO Done
            $id_status = $trx->id_status;
            if($id_status == 6){
                $trx->id_status = 7;
                $id_status = 7;
            }elseif($id_status == 8){
                $trx->id_status = 9;
                $id_status = 9;
            }elseif($id_status == 10){
                $trx->id_status = 11;
                $id_status = 11;
            }
            $trx->update();
            // end
            // return redirect()->to($request->back_url)->with('msg', 'Data is saved successfully.');
        }
        // start
        // id_identitas,id_status,pic,pic_property,aktifitas,keterangan
        $nik = auth()->user()->nik;
        $nama = Karyawan::where(['nik'=>$nik])->first()->nama;
        $arr = [$_trx->id,$id_status,$nama.'/'.$nik,'','internal',''];
        $this->insertLog($arr);
        return redirect('recruitment/applicant_list/0')->with('msg', 'Data is saved successfully.');
    }

    public function applicant_save(Request $request)
    {
        // dd($request->all());
        $trx = TrxRecruitmentIdentitas::find(Crypt::decrypt($request->id));
        $_trx = TrxKebutuhanMaster::find($trx->id_kebutuhan);
        // $posisi = $_trx->_pekerjaan->pekerjaan;
        // dd($trx);
        $trx->id_status = $request->id_status;
        $trx->check_in = $request->check_in;
        // default durasi pengerjaan adalah 150 menit (2.5 jam)
        $trx->check_out = Carbon::parse($request->check_in)->addMinutes(150);
        // dan jika belum terisi karena sudah dikerjakan
        if(empty($trx->remain)){
            $trx->remain = Carbon::parse($trx->check_out)->diffInSeconds(Carbon::parse($trx->check_in));
        }
        $trx->update();
        // start
        // id_identitas,id_status,pic,pic_property,aktifitas,keterangan
        $nik = auth()->user()->nik;
        $nama = Karyawan::where(['nik'=>$nik])->first()->nama;
        $arr = [$trx->id,$trx->id_status,$nama.'/'.$nik,'','internal',''];
        $this->insertLog($arr);
        // end
        // jika belum psychotest
        $where = ['id_identitas'=>$trx->id];
        if(!TrxRecruitmentPsychotest::where($where)->exists()){
            $this->insertPsychotest($trx);
        }
        // source: https://ziixon93.blogspot.com/2020/07/cara-memasang-imagemagick-di-xampp.html
        $qr = QrCode::format('png')->size(500)->generate(route('recruitment.psychotest', [Crypt::encrypt(array($trx->id, 'psychotest'))]));
        $qrImageName = base64_encode($trx->id) . '.png';
        // dd($qrImageName);
        // simpan ke local storage
        Storage::put('public/qr/' . $qrImageName, $qr);
        // Storage::disk('public')->put('qr/'.$qrImageName, File::get(public_path($qrImageName)));
        // 
        // parameter yang dipassing ke sendemail
        $mailData['title'] = 'Undangan Psikotes';
        $mailData['data'] = ['trx' => $trx];
        $mailData['filename'] = 'emails.recruitment1';
        $mailData['to'] = $trx->email;
        $this->sendEmail($mailData);
        // return redirect()->to($request->back_url)->with('msg', 'Data is saved successfully.');
        return redirect('recruitment/applicant_list/0')->with('msg', 'Data is saved successfully.');
    }

    public function insertLog($id)
    {
        $trx = new TrxRecruitmentLog;
        $trx->id_identitas = $id[0];
        $trx->id_status = $id[1];
        $trx->pic = $id[2];
        $trx->pic_property = $id[3];
        $trx->aktifitas = $id[4];
        $trx->keterangan = $id[5];
        if(!empty($id[6])){
            $trx->lampiran = $id[6];
        }
        $trx->save();
    }

    public function insertPsychotest($id)
    {
        // dd($id);
        // insert jenis=0
        // $where = ['jenis'=>0];
        $trx = MtrRecruitmentSoal::find(1);
        $_trx = new TrxRecruitmentPsychotest;
        $_trx->id_identitas = $id->id;
        $_trx->jenis = $trx->jenis;
        $_trx->nomor = $trx->nomor;
        $_trx->soal1 = $trx->soal;
        $_trx->save();
        // insert jenis=1
        $jenis = 1;
        $where = ['jenis'=>$jenis, 'urutan'=>1];
        $soal1 = MtrRecruitmentSoal::where($where)->orderBy('id', 'asc')->pluck('soal')->toArray();
        $where = ['jenis'=>$jenis, 'urutan'=>2];
        $soal2 = MtrRecruitmentSoal::where($where)->orderBy('id', 'asc')->pluck('soal')->toArray();
        for($x = 0; $x < count($soal1); $x++){
            $_trx = new TrxRecruitmentPsychotest;
            $_trx->id_identitas = $id->id;
            $_trx->jenis = $jenis;
            $_trx->nomor = $x+1;
            $_trx->soal1 = $soal1[$x];
            $_trx->soal2 = $soal2[$x];
            $_trx->save();
        }
        // insert jenis=2
        $jenis = 2;
        $where = ['jenis'=>$jenis, 'urutan'=>1];
        $soal1 = MtrRecruitmentSoal::where($where)->orderBy('id', 'asc')->pluck('soal')->toArray();
        $where = ['jenis'=>$jenis, 'urutan'=>2];
        $soal2 = MtrRecruitmentSoal::where($where)->orderBy('id', 'asc')->pluck('soal')->toArray();
        for($x = 0; $x < count($soal1); $x++){
            $_trx = new TrxRecruitmentPsychotest;
            $_trx->id_identitas = $id->id;
            $_trx->jenis = $jenis;
            $_trx->nomor = $x+1;
            $_trx->soal1 = $soal1[$x];
            $_trx->soal2 = $soal2[$x];
            $_trx->save();
        }
        // insert jenis=3
        $jenis = 3;
        $where = ['jenis'=>$jenis, 'urutan'=>1];
        $soal1 = MtrRecruitmentSoal::where($where)->orderBy('id', 'asc')->pluck('soal')->toArray();
        $where = ['jenis'=>$jenis, 'urutan'=>2];
        $soal2 = MtrRecruitmentSoal::where($where)->orderBy('id', 'asc')->pluck('soal')->toArray();
        $where = ['jenis'=>$jenis, 'urutan'=>3];
        $soal3 = MtrRecruitmentSoal::where($where)->orderBy('id', 'asc')->pluck('soal')->toArray();
        $where = ['jenis'=>$jenis, 'urutan'=>4];
        $soal4 = MtrRecruitmentSoal::where($where)->orderBy('id', 'asc')->pluck('soal')->toArray();
        for($x = 0; $x < count($soal1); $x++){
            $_trx = new TrxRecruitmentPsychotest;
            $_trx->id_identitas = $id->id;
            $_trx->jenis = $jenis;
            $_trx->nomor = $x+1;
            $_trx->soal1 = $soal1[$x];
            $_trx->soal2 = $soal2[$x];
            $_trx->soal3 = $soal3[$x];
            $_trx->soal4 = $soal4[$x];
            $_trx->save();
        }
        // insert jenis=4
        $jenis = 4;
        $where = ['jenis'=>$jenis];
        $soal1 = MtrRecruitmentSoal::where($where)->orderBy('id', 'asc')->pluck('soal')->toArray();
        for($x = 0; $x < count($soal1); $x++){
            $_trx = new TrxRecruitmentPsychotest;
            $_trx->id_identitas = $id->id;
            $_trx->jenis = $jenis;
            $_trx->nomor = $x+1;
            $_trx->soal1 = $soal1[$x];
            $_trx->save();
        }
        // $where = ['jenis'=>$jenis, 'urutan'=>1];
        // $soal1 = MtrRecruitmentSoal::where($where)->orderBy('id', 'asc')->pluck('soal')->toArray();
        // $where = ['jenis'=>$jenis, 'urutan'=>2];
        // $soal2 = MtrRecruitmentSoal::where($where)->orderBy('id', 'asc')->pluck('soal')->toArray();
        // for($x = 0; $x < count($soal1); $x++){
        //     $_trx = new TrxRecruitmentPsychotest;
        //     $_trx->id_identitas = $id->id;
        //     $_trx->jenis = $jenis;
        //     $_trx->nomor = $x+1;
        //     $_trx->soal1 = $soal1[$x];
        //     $_trx->soal2 = $soal2[$x];
        //     $_trx->save();
        // }
    }

    public function psychotest(Request $request, $id)
    {
        try {
            // dd($request->all(), Crypt::decrypt($id));
            $remain = 0;
            $_now = Carbon::now();
            $_id = Crypt::decrypt($id);
            $id = $_id[0];
            // $kode = $_id[1];
            if(!empty($_id[2])){
                $soal = TrxRecruitmentPsychotest::find($_id[2]);
                $soal->check_out = $_now;
                $remain = $soal->remain - $_now->diffInSeconds(Carbon::parse($soal->check_in));
                // jika jenis=0 masih di pengantar belum dihitung mundur
                if($soal->jenis == 0){
                    $remain = $soal->remain;
                }
                $soal->jawaban1 = $request->a;
                if($soal->jenis == 3){
                    $soal->jawaban1 = $request->p;
                    $soal->jawaban2 = $request->k;
                }
                $soal->update();
            }
            $trx = TrxRecruitmentIdentitas::find($id);
            $where = ['id_identitas' => $id];
            $soal = TrxRecruitmentPsychotest::where($where)->whereNull('check_out')->orderBy('id', 'asc')->first();
            if(!empty($soal)){
                // dd(Carbon::now()->diffInSeconds(Carbon::parse($trx->check_in)));
                // dd(Carbon::now()->diffInSeconds(Carbon::parse($trx->check_out)));
                if((Carbon::now() > Carbon::parse($trx->check_in)) && (Carbon::now() < Carbon::parse($trx->check_out))){
                    $where = ['id_identitas' => $soal->id_identitas, 'jenis' => $soal->jenis];
                    $jumlah = TrxRecruitmentPsychotest::where($where)->count();
                    $soal->check_in = $_now;
                    if(empty($_id[2])){
                        $remain = $trx->remain;
                    }
                    $soal->remain = $remain;
                    $soal->update();
                    // 
                    $trx->remain = $remain;
                    $trx->update();
                    if($soal->jenis == 0){
                        return view('recruitment.psychotest_pre', compact('trx','soal', 'jumlah'));
                    }else{
                        return view('recruitment.psychotest_exe', compact('trx','soal', 'jumlah'));
                    }
                }else{
                    $msg = '';
                    $msg .= '<p>Saat ini Tahapan Psikotest tidak tersedia.
                    <br/>Agar kemudian dapat diperhatikan waktu yang sudah diagendakan.</p>';
                    $msg .= '<p>Best Regards,<br/>Human Capital & Organization<br/><a href="https://alita.id/" target="_blank">www.alita.id</a><br/><strong>"Passion - Integrity - Excellent"</strong></p>';
                    return view('recruitment.thank', compact('msg'));
                }
            }else{
                $msg = '';
                if(empty($trx->check_in)){
                    $msg .= '<p>Saat ini Tahapan Psikotest belum tersedia.
                    <br/>Agar kemudian dapat diperhatikan waktu yang sudah diagendakan.</p>';
                }else{
                    $msg .= '<p>Terima kasih atas partisipasinya pada Tahapan Psikotest - Seleksi Penerimaan Karyawan ini.
                    <br/>Semoga anda dapat bergabung bersama kami.</p>';
                }
                $msg .= '<p>Best Regards,<br/>Human Capital & Organization<br/><a href="https://alita.id/" target="_blank">www.alita.id</a><br/><strong>"Passion - Integrity - Excellent"</strong></p>';
                return view('recruitment.thank', compact('msg'));
            }
        } catch (DecryptException $e) {
            return view('errors.403');
        }
    }
    
    // digunakan jika gambar pohon dan manusia menjadi 2 halaman
    public function psychotest_file(Request $request)
    {
        try {
            // dd($request->all());
            $remain = 0;
            $_now = Carbon::now();
            $_id = Crypt::decrypt($request->id);
            $id = $_id[0];
            // $kode = $_id[1];
            if(!empty($_id[2])){
                $soal = TrxRecruitmentPsychotest::find($_id[2]);
                $soal->check_out = $_now;
                $remain = $soal->remain - $_now->diffInSeconds(Carbon::parse($soal->check_in));
                if($soal->jenis == 4){
                    if($soal->nomor == 1){
                        $gambar_pohon = $request->file('gambar_pohon');
                        $request->validate([
                            'gambar_pohon' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
                        ]);
                        $extension = $gambar_pohon->getClientOriginalExtension();
                        $namafile = $id.'_'.now()->getTimestampMs().'.'.$extension;
                        $gambar_pohon->move('public/assets/recruitment/upload/', $namafile);
                        $soal->jawaban1 = $namafile;
                        $soal->update();
                    }
                    elseif($soal->nomor == 2){
                        $gambar_manusia = $request->file('gambar_manusia');
                        $request->validate([
                            'gambar_manusia' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
                        ]);
                        $extension = $gambar_manusia->getClientOriginalExtension();
                        $namafile = $id.'_'.now()->getTimestampMs().'.'.$extension;
                        $gambar_manusia->move('public/assets/recruitment/upload/', $namafile);
                        $soal->jawaban1 = $namafile;
                        $soal->update();
                    }
                }
            }
            $trx = TrxRecruitmentIdentitas::find($id);
            $where = ['id_identitas' => $id];
            $soal = TrxRecruitmentPsychotest::where($where)->whereNull('check_out')->orderBy('id', 'asc')->first();
            if(!empty($soal)){
                $where = ['id_identitas' => $soal->id_identitas, 'jenis' => $soal->jenis];
                $jumlah = TrxRecruitmentPsychotest::where($where)->count();
                $soal->check_in = $_now;
                if(empty($_id[2])){
                    $remain = $trx->remain;
                }
                $soal->remain = $remain;
                $soal->update();
                // 
                $trx->remain = $remain;
                $trx->update();
                return view('recruitment.psychotest_exe', compact('trx','soal', 'jumlah'));
            }else{
                // update status 5=Psychotest Done
                $trx = TrxRecruitmentIdentitas::find($id);
                $trx->id_status = 5;
                $trx->update();
                // start
                // id_identitas,id_status,pic,pic_property,aktifitas,keterangan
                $arr = [$trx->id,$trx->id_status,$trx->nama,'','external',''];
                $this->insertLog($arr);
                // end
                // kirim email
                $msg = '<p>Hai '.$trx->nama.',<br/>Psikotes anda sudah selesai.
                <br/>Kami akan menggunakan email anda ('.$trx->email.') sebagai alamat email untuk korespondensi.<br/>Terima kasih,</p>
                <p>Best Regards,<br/>Human Capital & Organization<br/><a href="https://alita.id/" target="_blank">www.alita.id</a><br/><strong>"Passion - Integrity - Excellent"</strong></p>';
                // parameter yang dipassing ke sendemail
                $mailData['title'] = 'Psikotes Selesai';
                $mailData['data'] = ['msg'=>$msg];
                $mailData['filename'] = 'emails.recruitment0';
                $mailData['to'] = $trx->email;
                $this->sendEmail($mailData);
                return view('recruitment.thank', compact('msg'));
            }
        } catch (DecryptException $e) {
            return view('errors.403');
        }
    }
    
    // digunakan jika gambar pohon dan manusia menjadi 1 halaman
    // public function psychotest_file(Request $request)
    // {
    //     try {
    //         // dd($request->all());
    //         $_now = Carbon::now();
    //         $_id = Crypt::decrypt($request->id);
    //         $id = $_id[0];
    //         // $kode = $_id[1];
    //         if(!empty($_id[2])){
    //             $soal = TrxRecruitmentPsychotest::find($_id[2]);
    //             $soal->check_out = $_now;
    //             if($soal->jenis == 4){
    //                 $gambar_pohon = $request->file('gambar_pohon');
    //                 $request->validate([
    //                     'gambar_pohon' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
    //                 ]);
    //                 $gambar_manusia = $request->file('gambar_manusia');
    //                 $request->validate([
    //                     'gambar_manusia' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
    //                 ]);
    //                 $extension = $gambar_pohon->getClientOriginalExtension();
    //                 $namafile = $id.'_'.now()->getTimestampMs().'.'.$extension;
    //                 $gambar_pohon->move('public/assets/recruitment/upload/', $namafile);
    //                 $soal->jawaban1 = $namafile;
    //                 // 
    //                 $extension = $gambar_manusia->getClientOriginalExtension();
    //                 $namafile = $id.'_'.now()->getTimestampMs().'.'.$extension;
    //                 $gambar_manusia->move('public/assets/recruitment/upload/', $namafile);
    //                 $soal->jawaban2 = $namafile;
    //             }
    //             $soal->update();
    //             // update status 5=Psychotest Done
    //             $trx = TrxRecruitmentIdentitas::find($id);
    //             $trx->id_status = 5;
    //             $trx->update();
    //             // insert interview
    //             // $this->insertInterview($trx);
    //             // kirim email
    //             // $posisi = $trx->_kebutuhan->_pekerjaan->pekerjaan;
    //             $msg = '<p>Hai '.$trx->nama.',<br/>Psikotes anda sudah selesai.
    //             <br/>Kami akan menggunakan email anda ('.$trx->email.') sebagai alamat email untuk korespondensi.<br/>Terima kasih,</p>
    //             <p>Best Regards,<br/>Human Capital & Organization<br/><a href="https://alita.id/" target="_blank">www.alita.id</a><br/><strong>"Passion - Integrity - Excellent"</strong></p>';
    //             // parameter yang dipassing ke sendemail
    //             $mailData['title'] = 'Psikotes Selesai';
    //             $mailData['data'] = ['msg'=>$msg];
    //             $mailData['filename'] = 'emails.recruitment0';
    //             $mailData['to'] = $trx->email;
    //             $this->sendEmail($mailData);
    //         }
    //         return view('recruitment.thank', compact('msg'));
    //     } catch (DecryptException $e) {
    //         return view('errors.403');
    //     }
    // }

    public function insertInterview($id)
    {
        // jenis=hco
        $trx = new TrxRecruitmentInterview;
        $trx->id_identitas = $id->id;
        // 6=Interview Next
        $trx->id_status = 6;
        $trx->jenis = 'hco';
        $trx->save();
        // jenis=user
        $trx = new TrxRecruitmentInterview;
        $trx->id_identitas = $id->id;
        // 6=Interview Next
        $trx->id_status = 6;
        $trx->jenis = 'user';
        $trx->save();
    }

    public function apply_save(Request $request)
    {
        // dd($request->all());
        $id = Crypt::decrypt($request->id);
        $id_recruitment = $id[0];
        $trx = TrxRecruitment::find($id_recruitment);
        $id_kebutuhan = $trx->id_kebutuhan;
        $trx = TrxKebutuhanMaster::find($id_kebutuhan);
        // $posisi = $trx->_pekerjaan->pekerjaan;
        // dd($id);
        $trx = new TrxRecruitmentIdentitas;
        $trx->id_recruitment = $id_recruitment;
        $trx->id_kebutuhan = $id_kebutuhan;
        // 3=Apply
        $trx->id_status = 3;
        // 
        $cv = $request->file('cv');
        $request->validate([
            'cv' => 'required|file|mimes:pdf|max:5120',
        ]);
        $extension = $cv->getClientOriginalExtension();
        $namafile = 'cv_'.now()->getTimestampMs().'.'.$extension;
        $cv->move('public/assets/recruitment/upload/', $namafile);
        $trx->cv = $namafile;
        // 
        $trx->nama = $request->nama;
        $trx->tempat_lahir = $request->tempat_lahir;
        $trx->tanggal_lahir = $request->tanggal_lahir;
        $trx->kelamin = $request->kelamin;
        $trx->ktp = $request->ktp;
        $trx->alamat_ktp = $request->alamat_ktp;
        $trx->alamat_domisili = $request->alamat_domisili;
        $trx->agama = $request->agama;
        $trx->npwp = $request->npwp;
        $trx->telepon = $request->telepon;
        $trx->email = $request->email;
        $trx->status_hidup = $request->status_hidup;
        $trx->tanggal_status = $request->tanggal_status;
        $trx->nama_pasangan = $request->nama_pasangan;
        $trx->usia_pasangan = $request->usia_pasangan;
        $trx->kelamin_pasangan = $request->kelamin_pasangan;
        $trx->pendidikan_pasangan = $request->pendidikan_pasangan;
        $trx->pekerjaan_pasangan = $request->pekerjaan_pasangan;
        $trx->nama_bapak = $request->nama_bapak;
        $trx->usia_bapak = $request->usia_bapak;
        $trx->pendidikan_bapak = $request->pendidikan_bapak;
        $trx->pekerjaan_bapak = $request->pekerjaan_bapak;
        $trx->nama_ibu = $request->nama_ibu;
        $trx->usia_ibu = $request->usia_ibu;
        $trx->pendidikan_ibu = $request->pendidikan_ibu;
        $trx->pekerjaan_ibu = $request->pekerjaan_ibu;
        $trx->hobi = $request->hobi;
        $trx->kelebihan = $request->kelebihan;
        $trx->peningkatan = $request->peningkatan;
        $trx->is_sakit = $request->is_sakit;
        $trx->sakit_apa = $request->sakit_apa;
        $trx->sakit_tahun = $request->sakit_tahun;
        $trx->sakit_kondisi = $request->sakit_kondisi;
        $trx->alasan = $request->alasan;
        $trx->gaji = str_replace(',', '', $request->gaji);
        $trx->pendidikan = $request->pendidikan;
        $trx->pekerjaan = $request->pekerjaan;
        $trx->usia = Carbon::parse($request->tanggal_lahir)->age;
        $trx->save();
        // start
        // id_identitas,id_status,pic,pic_property,aktifitas,keterangan
        $arr = [$trx->id,$trx->id_status,$trx->nama,'','external',''];
        $this->insertLog($arr);
        // end
        // TrxRecruitmentAnak
        $nama_anak = $request->input('nama_anak', []);
        $usia_anak = $request->input('usia_anak', []);
        $kelamin_anak = $request->input('kelamin_anak', []);
        $pendidikan_anak = $request->input('pendidikan_anak', []);
        $pekerjaan_anak = $request->input('pekerjaan_anak', []);
        for($x = 0; $x < count($nama_anak); $x++){
            $_trx = new TrxRecruitmentAnak;
            $_trx->id_identitas = $trx->id;
            $_trx->nama = $nama_anak[$x];
            $_trx->usia = $usia_anak[$x];
            $_trx->kelamin = $kelamin_anak[$x];
            $_trx->pendidikan = $pendidikan_anak[$x];
            $_trx->pekerjaan = $pekerjaan_anak[$x];
            $_trx->save();
        }
        // TrxRecruitmentSaudara
        $kakak_adik = $request->input('kakak_adik', []);
        $nama_saudara = $request->input('nama_saudara', []);
        $usia_saudara = $request->input('usia_saudara', []);
        $kelamin_saudara = $request->input('kelamin_saudara', []);
        $pendidikan_saudara = $request->input('pendidikan_saudara', []);
        $pekerjaan_saudara = $request->input('pekerjaan_saudara', []);
        for($x = 0; $x < count($kakak_adik); $x++){
            $_trx = new TrxRecruitmentSaudara;
            $_trx->id_identitas = $trx->id;
            $_trx->kakak_adik = $kakak_adik[$x];
            $_trx->nama = $nama_saudara[$x];
            $_trx->usia = $usia_saudara[$x];
            $_trx->kelamin = $kelamin_saudara[$x];
            $_trx->pendidikan = $pendidikan_saudara[$x];
            $_trx->pekerjaan = $pekerjaan_saudara[$x];
            $_trx->save();
        }
        // TrxRecruitmentFormal
        $jenjang_sekolah = $request->input('jenjang_sekolah', []);
        $nama_formal = $request->input('nama_formal', []);
        $kota_sekolah = $request->input('kota_sekolah', []);
        $jurusan = $request->input('jurusan', []);
        $tahun_masuk_sekolah = $request->input('tahun_masuk_sekolah', []);
        $tahun_lulus_sekolah = $request->input('tahun_lulus_sekolah', []);
        $nilai = $request->input('nilai', []);
        for($x = 0; $x < count($jenjang_sekolah); $x++){
            $_trx = new TrxRecruitmentFormal;
            $_trx->id_identitas = $trx->id;
            $_trx->jenjang = $jenjang_sekolah[$x];
            $_trx->nama = $nama_formal[$x];
            $_trx->kota = $kota_sekolah[$x];
            $_trx->jurusan = $jurusan[$x];
            $_trx->tahun_masuk = $tahun_masuk_sekolah[$x];
            $_trx->tahun_lulus = $tahun_lulus_sekolah[$x];
            $_trx->nilai = $nilai[$x];
            $_trx->save();
        }
        // TrxRecruitmentInformal
        $bidang_informal = $request->input('bidang_informal', []);
        $tahun_informal = $request->input('tahun_informal', []);
        $lembaga = $request->input('lembaga', []);
        $sertifikat = $request->input('sertifikat', []);
        for($x = 0; $x < count($bidang_informal); $x++){
            $_trx = new TrxRecruitmentInformal;
            $_trx->id_identitas = $trx->id;
            $_trx->bidang = $bidang_informal[$x];
            $_trx->tahun = $tahun_informal[$x];
            $_trx->lembaga = $lembaga[$x];
            $_trx->sertifikat = $sertifikat[$x];
            $_trx->save();
        }
        // TrxRecruitmentOrganisasi
        $nama_organisasi = $request->input('nama_organisasi', []);
        $periode = $request->input('periode', []);
        $jabatan_organisasi = $request->input('jabatan_organisasi', []);
        $keterangan = $request->input('keterangan', []);
        for($x = 0; $x < count($nama_organisasi); $x++){
            $_trx = new TrxRecruitmentOrganisasi;
            $_trx->id_identitas = $trx->id;
            $_trx->nama = $nama_organisasi[$x];
            $_trx->periode = $periode[$x];
            $_trx->jabatan = $jabatan_organisasi[$x];
            $_trx->keterangan = $keterangan[$x];
            $_trx->save();
        }
        // TrxRecruitmentPekerjaan
        $nama_perusahaan = $request->input('nama_perusahaan', []);
        $kota_perusahaan = $request->input('kota_perusahaan', []);
        $tahun_masuk_perusahaan = $request->input('tahun_masuk_perusahaan', []);
        $tahun_keluar_perusahaan = $request->input('tahun_keluar_perusahaan', []);
        $jabatan_perusahaan = $request->input('jabatan_perusahaan', []);
        $upah = $request->input('upah', []);
        $alasan_keluar = $request->input('alasan_keluar', []);
        for($x = 0; $x < count($nama_perusahaan); $x++){
            $_trx = new TrxRecruitmentPekerjaan;
            $_trx->id_identitas = $trx->id;
            $_trx->nama = $nama_perusahaan[$x];
            $_trx->kota = $kota_perusahaan[$x];
            $_trx->tahun_masuk = $tahun_masuk_perusahaan[$x];
            $_trx->tahun_keluar = $tahun_keluar_perusahaan[$x];
            $_trx->jabatan = $jabatan_perusahaan[$x];
            $_trx->gaji = str_replace(',', '', $upah[$x]);
            $_trx->alasan = $alasan_keluar[$x];
            $_trx->save();
        }
        $nomor = $trx->id_kebutuhan.'/'.$trx->id;
        $msg = '<p>Hai '.$trx->nama.',<br/>Lamaran anda dengan nomor '.$nomor.' akan segera kami proses.
        <br/>Kami akan menggunakan email anda ('.$trx->email.') sebagai alamat email untuk korespondensi.<br/>Terima kasih,</p>
        <p>Best Regards,<br/>Human Capital & Organization<br/><a href="https://alita.id/" target="_blank">www.alita.id</a><br/><strong>"Passion - Integrity - Excellent"</strong></p>';
        // parameter yang dipassing ke sendemail
        $mailData['title'] = 'Lamaran Pekerjaan';
        $mailData['data'] = ['msg'=>$msg];
        $mailData['filename'] = 'emails.recruitment0';
        $mailData['to'] = $trx->email;
        $this->sendEmail($mailData);
        // 
        // return redirect()->view('recruitment.thank', compact('msg'))->withInput();
        // return back()->with(['message' => $msg]);
        return view('recruitment.thank', compact('msg'));
    }

    public function sendEmail($mailData)
    {
        $ccEmails = explode(',', MtrUtility::find(11)->informasi);
        if(!empty($mailData['cc'])){
            foreach($mailData['cc'] as $cc){
                array_push($ccEmails, $cc);
            }
        }
        // dd($ccEmails);
        Mail::to($mailData['to'])->cc($ccEmails)->send(new MattaRebornMail($mailData));
    }

    public static function getTanggalIndonesia($tgl)
    {
        $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $strTgl = $hari[Carbon::createFromFormat('Y-m-d', $tgl)->dayOfWeek] . ', ';
        $strTgl .= Carbon::createFromFormat('Y-m-d', $tgl)->format('d-m-Y');
        return $strTgl;
    }

    public function psychotestexcel(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        // dd($request->all(), $id);
        $filename = '';
        if($id[1] == 1){
            $filename = 'EPPS_';
        }elseif($id[1] == 2){
            $filename = 'PAPI Kostick_';
        }elseif($id[1] == 3){
            $filename = 'DISC_';
        }
        return Excel::download(new TrxRecruitmentPsychotestExport($request,$id), $filename.$id[0].'_'.now()->getTimestampMs().'.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        // if($id[1] == 1){
        //     return Excel::download(new TrxRecruitmentPsychotestExport($request,$id), 'psychotest_'.$id[0].'_'.now()->getTimestampMs().'.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        // }elseif($id == 2){
        //     return Excel::download(new TrxRecruitmentPsychotestExport($request,$id), 'psychotest_'.$id[0].'_'.now()->getTimestampMs().'.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        // }
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
