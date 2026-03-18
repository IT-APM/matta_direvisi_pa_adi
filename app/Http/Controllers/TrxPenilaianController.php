<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Perusahaan;
use App\Models\Karyawan;
use App\Models\TrxPenilaian;
use App\Models\TrxPenilaianLog;
use App\Models\TrxPenilaianLogHistory;
use App\Models\TrxPenilaianSkor;
use App\Models\MtrPenilaianTipe;
use App\Models\MtrPenilaianStatus;
use App\Models\MtrPenilaianPerpanjang;
use App\Models\MtrPenilaian1;
use App\Models\MtrPenilaian2;
use App\Models\MtrPenilaian3;
use App\Models\MtrPenilaianOutsource;
use Illuminate\Support\Facades\Log;
// 
use App\Models\ToDoList;
use App\Models\ToDoListLog;
use App\Models\MtrUtility;
use App\Mail\MattaRebornMail;
use Illuminate\Support\Facades\Mail;
// 
use DB;
use Elibyy\TCPDF\Facades\TCPDF;
// 
class TrxPenilaianController extends Controller
{
    public function kontrak(Request $request, $id)
    {
        // dd($request->all(), $id);$pdf::AddPage('P', 'A4');
        $waktu = date('YmdHis');
        $filename = 'Kontrak PKWT '.$waktu.'.pdf';
        $pdf = new TCPDF;
        $pdf::SetTitle('Kontrak PKWT');
        $pdf::SetFont('times', '', 10, '', true);
        $pdf::AddPage();
        $pdf::SetAutoPageBreak(true, 0);
        $bgImagePath = public_path('assets/images/matta/bg_alita_a4_header.png');
        $x = 7;
        $y = 23;
        $pdf::Image($bgImagePath, 0, 0, 210, $y, '', '', '', false, 300, '', false, false, 0);
        $bgImagePath = public_path('assets/images/matta/bg_alita_a4_footer.png');
        $pdf::Image($bgImagePath, 0, 275, 210, 16, '', '', '', false, 300, '', false, false, 0);
        $html = '<div style="text-align:center;"><b><u>PERJANJIAN KERJA WAKTU TERTENTU</u></b><br/>
        No.041-PKWT/ADM-APM/PC/III/2026<br/>
        ANTARA<br/>
        <b><u>PT. ALITA PRAYA MITRA</u></b><br/>
        DENGAN<br/>
        <b><u>ANDREA WULANDARI IRAWAN</u></b></div>';
        $pdf::writeHTMLCell(0, 0, $x, $y+=0, $html, 0, 1, 0, true, '', true);
        $html = '<div style="text-align:justify;">Pada hari ini Senin Dua Maret Dua Ribu Dua Puluh Enam di Kantor PT. ALITA PRAYA MITRA Jl. Muara Dalam No.108 Kelurahan Tanjung Barat Kecamatan Jagakarsa, Jakarta Selatan, telah tercapai kesepakatan antara pihak-pihak :</div>';
        $pdf::writeHTMLCell(0, 0, $x, $y+=30, $html, 0, 1, 0, true, '', true);
        $html = '<div style="text-align:justify;"><table>
        <tr>
            <td width="4%">I.</td>
            <td width="96%"><b>PT. ALITA PRAYA MITRA</b> berkedudukan di Jl. Muara Dalam No.108 Kelurahan Tanjung Barat Kecamatan Jagakarsa, Jakarta 12530, yang dalam perbuatan hukum ini diwakili secara sah oleh <b>Khatrin Rismasany</b> dalam kapasitasnya mewakili Direksi sebagai People & Culture Manager, dan untuk selanjutnya disebut sebagai <b>Pihak Pertama</b></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>II.</td>
            <td><table>
                    <tr>
                        <td width="20%">Nama</td>
                        <td width="2%">:</td>
                        <td width="78%">Andrea Wulandari Irawan</td>
                    </tr>
                    <tr>
                        <td>Tempat & tgl lahir</td>
                        <td>:</td>
                        <td>Jakarta / 7 Agustus 1995</td>
                    </tr>
                    <tr>
                        <td>NIK/KTP</td>
                        <td>:</td>
                        <td>3273184708950005</td>
                    </tr>
                    <tr>
                        <td>Jenis Kelamin</td>
                        <td>:</td>
                        <td>Perempuan</td>
                    </tr>
                    <tr>
                        <td>Agama</td>
                        <td>:</td>
                        <td>Islam</td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>:</td>
                        <td>Belum Menikah</td>
                    </tr>
                    <tr>
                        <td>Pendidikan terakhir</td>
                        <td>:</td>
                        <td>S2 (Magister Manajemen Internasional)</td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>:</td>
                        <td>Jl. Bona Indah A VI/7, RT 006 RW 006, Lebak Bulus, Cilandak, Jakarta Selatan, DKI Jakarta</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>Untuk selanjutnya disebut <b>Pihak Kedua</b></td>
        </tr>
        </table></div>';
        $pdf::writeHTMLCell(0, 0, $x, $y+=15, $html, 0, 1, 0, true, '', true);
        $html = '<div style="text-align:justify;">Dan secara bersamaan dalam Perjanjian Kerja Waktu Tertentu (<b>“Perjanjian”</b>) ini disebut sebagai <b>Para Pihak</b></div>';
        $pdf::writeHTMLCell(0, 0, $x, $y+=65, $html, 0, 1, 0, true, '', true);
        $html = '<div style="text-align:center;"><b>PASAL 1<br/>HUBUNGAN KERJA DAN MASA KERJA</b></div>';
        $pdf::writeHTMLCell(0, 0, $x, $y+=10, $html, 0, 1, 0, true, '', true);
        $html = '<div style="text-align:justify;"><table>
        <tr>
            <td width="4%">1)</td>
            <td width="96%">Bahwa Pihak Pertama dengan ini sepakat untuk mempekerjakan Pihak Kedua secara tidak tetap dan Pihak Kedua dengan ini sepakat untuk bekerja bagi dan untuk kepentingan Pihak Pertama secara tidak tetap dengan hubungan kerja berdasarkan Perjanjian Kerja Waktu Tertentu (PKWT) sebagaimana ditentukan dalam Perjanjian ini</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>2)</td>
            <td>Yang dimaksud dengan Waktu Tertentu adalah bahwa Pihak Pertama mempekerjakan Pihak Kedua di bawah Divisi <b>Corporate Secretary</b> selama 10 (sepuluh) bulan terhitung mulai tanggal 2 Maret 2026 sampai dengan 31 Desember 2026 dan dapat diperpanjang dan/atau diperbaharui atas persetujuan kedua belah pihak</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>3)</td>
            <td>Perjanjian ini adalah berbasis proyek dan bisa diakhiri jika proyek berakhir sebelum masa kontrak yang tercantum dalam Pasal 1 ayat (2)</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>4)</td>
            <td>Dengan berakhirnya Perjanjian sebagaimana yang dimaksud pada ayat (2) di atas, maka Karyawan berhak mendapatkan uang kompensasi sebagaimana diatur pada PP No. 35 tahun 2021 atau Peraturan Perundang-undangan yang berlaku, maka Pihak Kedua berhak atas uang kompensasi. Pemberian uang kompensasi diberikan oleh Pihak Pertama pada saat bersamaan dengan tanggal pembayaran upah bulanan</td>
        </tr>
        </table></div>';
        $pdf::writeHTMLCell(0, 0, $x, $y+=15, $html, 0, 1, 0, true, '', true);
        // page 2
        $pdf::AddPage();
        $pdf::SetAutoPageBreak(true, 0);
        $bgImagePath = public_path('assets/images/matta/bg_alita_a4_header_p2.png');
        $x = 7;
        $y = 23;
        $pdf::Image($bgImagePath, 0, 0, 210, $y, '', '', '', false, 300, '', false, false, 0);
        $bgImagePath = public_path('assets/images/matta/bg_alita_a4_footer.png');
        $pdf::Image($bgImagePath, 0, 275, 210, 16, '', '', '', false, 300, '', false, false, 0);
        $html = '<div style="text-align:center;"><b>PASAL 2<br/>PEKERJAAN DAN PENEMPATAN</b></div>';
        $pdf::writeHTMLCell(0, 0, $x, $y, $html, 0, 1, 0, true, '', true);
        $html = '<div style="text-align:justify;"><table>
        <tr>
            <td width="4%">1)</td>
            <td width="96%">Pekerjaan sebagaimana dimaksud dalam Perjanjian ini adalah sebagai berikut:<br/>
                <table>
                        <tr>
                            <td width="2%">a.</td>
                            <td width="20%">Posisi / Jabatan</td>
                            <td width="2%">:</td>
                            <td width="76%">Corporate Secretary Officer</td>
                        </tr>
                        <tr>
                            <td>b.</td>
                            <td>Level</td>
                            <td>:</td>
                            <td>Supervisor</td>
                        </tr>
                        <tr>
                            <td>c.</td>
                            <td>Unit Kerja</td>
                            <td>:</td>
                            <td>Corporate Secretary</td>
                        </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>2)</td>
            <td>Selain melakukan pekerjaan sebagaimana dimaksud dalam ayat (1) Pasal ini, Pihak Kedua sepakat untuk melakukan pekerjaan tambahan diluar ruang lingkup Pekerjaan tersebut yang ditugaskan oleh Pihak Pertama sepanjang untuk kepentingan ruang lingkup pekerjaan tersebut</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>3)</td>
            <td>Pihak Kedua bertempat kerja di Kantor Pihak Pertama, Jl. Muara Dalam No.108 Kelurahan Tanjung Barat Kecamatan Jagakarsa, Jakarta Selatan dan/atau di tempat yang telah ditunjuk oleh Pihak Pertama</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>4)</td>
            <td>Bahwa yang bersangkutan bersedia untuk ditugaskan dan/atau ditempatkan di Perusahaan lain dalam lingkungan Pihak Pertama dan/atau anak perusahaannya</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>5)</td>
            <td>Pihak Kedua dapat melakukan perjalanan dinas selama diperintahkan oleh Atasan Langsung pada Divisi terkait dan/atau Direktorat lainnya. Untuk itu Pihak Kedua mendapatkan Biaya Perjalanan Dinas sesuai dengan ketentuan yang berlaku (<b>“Biaya Perjalanan Dinas”</b>)</td>
        </tr>
        </table></div>';
        $pdf::writeHTMLCell(0, 0, $x, $y+=15, $html, 0, 1, 0, true, '', true);
        $pdf::Output(public_path($filename), 'I');
        return response()->download(public_path($filename));
        
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        $no = 0;
        $trx = TrxPenilaian::orderBy( 'id', 'desc' )->get();
        if(Gate::allows('hc_nkp')){
            $trx = TrxPenilaian::whereIn('nik_karyawan',$arr_nik_nkp)->orderBy( 'id', 'desc' )->get();
        }
        if(auth()->user()->userrole == 'nhc'){
            // nik_karyawan
            $where = ['nik_karyawan' => auth()->user()->nik];
            $trx = TrxPenilaian::where($where)->orderBy( 'id', 'desc' )->get();
            // nik_penilai_1
            $where = ['nik_penilai_1' => auth()->user()->nik];
            $trx = $trx->concat(TrxPenilaian::where($where)->orderBy( 'id', 'desc' )->get());
            // nik_penilai_2
            $where = ['nik_penilai_2' => auth()->user()->nik];
            $trx = $trx->concat(TrxPenilaian::where($where)->orderBy( 'id', 'desc' )->get());
            // nik_penilai_3
            $where = ['nik_penilai_3' => auth()->user()->nik];
            $trx = $trx->concat(TrxPenilaian::where($where)->orderBy( 'id', 'desc' )->get());
        }
        // foreach ($trx as $value) {
        //     $trxM = TrxPenilaian::find( $value->id );
        //     $trxM->karyawan_property = $this->getNikProperty($value->nik_karyawan);
        //     $trxM->penilai1_property = $this->getNikProperty($value->nik_penilai_1);
        //     $trxM->penilai2_property = $this->getNikProperty($value->nik_penilai_2);
        //     $trxM->penilai3_property = $this->getNikProperty($value->nik_penilai_3);
        //     $trxM->update();
        // }
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
        $arr_id_status = array();
        $arr_is_perpanjang = array();
        $arr_id_status_lengkap = array();
        $arr_perusahaan = Perusahaan::select('id','nama')->whereNotIn('id', [0,3])->orderBy('nama', 'asc')->get();
        $perusahaan = $arr_perusahaan;
        foreach ($trx as $data){
            array_push($arr_id_status, $data->id_status);
            array_push($arr_is_perpanjang, $data->is_perpanjang);
        }
        $id_status = MtrPenilaianStatus::whereIn('id', $arr_id_status)->orderBy('id', 'asc')->get();
        $is_perpanjang = MtrPenilaianPerpanjang::whereIn('id', $arr_is_perpanjang)->orderBy('id', 'asc')->get();
        $id_status_lengkap = [23];
        // start filter
        if($request->filter == true){
            $_id_status = $request->input('id_status', []);
            $_is_perpanjang = $request->input('is_perpanjang', []);
            $_id_status_lengkap = $request->input('id_status_lengkap', []);
            $_perusahaan = $request->input('perusahaan', []);
            if(count($_id_status) > 0){
                $arr_id_status = $_id_status;
            }
            else{
                $arr_id_status = array();
            }
            if(count($_is_perpanjang) > 0){
                $arr_is_perpanjang = $_is_perpanjang;
            }
            else{
                $arr_is_perpanjang = array();
            }
            if(count($_id_status_lengkap) > 0){
                $arr_id_status_lengkap = $_id_status_lengkap;
            }
            else{
                $arr_id_status_lengkap = array();
            }
            if(count($_perusahaan) > 0){
                $arr_perusahaan = $_perusahaan;
            }
            else{
                $arr_perusahaan = array();
            }
            $nik_karyawan = Karyawan::select('nik')->whereIn('kode_perusahaan', $arr_perusahaan)->get()->toArray();
            $trx = TrxPenilaian::whereIn('nik_karyawan', $nik_karyawan)->whereIn('id_status', $arr_id_status)->whereIn('is_perpanjang', $arr_is_perpanjang)->orderBy('id', 'desc')->get();
            if(Gate::allows('hc_nkp')){
                $trx = TrxPenilaian::whereIn('nik_karyawan',$arr_nik_nkp)->whereIn('nik_karyawan', $nik_karyawan)->whereIn('id_status', $arr_id_status)->whereIn('is_perpanjang', $arr_is_perpanjang)->orderBy('id', 'desc')->get();
            }
            if(count($_id_status_lengkap) > 0){
                $trx = TrxPenilaian::whereIn('nik_karyawan', $nik_karyawan)->whereIn('id_status', [6, 7])->whereIn('is_perpanjang', $arr_is_perpanjang)->orderBy('id', 'desc')->get();
                if(Gate::allows('hc_nkp')){
                    $trx = TrxPenilaian::whereIn('nik_karyawan',$arr_nik_nkp)->whereIn('nik_karyawan', $nik_karyawan)->whereIn('id_status', [6, 7])->whereIn('is_perpanjang', $arr_is_perpanjang)->orderBy('id', 'desc')->get();
                }
                foreach ($trx as $data){
                    // Done By Reviewer 2
                    $where = ['id_status' => 6, 'id_trx' => $data->id];
                    $trx_2 = TrxPenilaianLog::where($where)->exists();
                    // Done By Reviewer 3
                    $where = ['id_status' => 7, 'id_trx' => $data->id];
                    $trx_3 = TrxPenilaianLog::where($where)->exists();
                    if($trx_2 && $trx_3){
                        // 
                    }else{
                        $trx = forgetById($trx, $data->id);
                    }
                }
            }
            if(auth()->user()->userrole == 'nhc'){
                // nik_karyawan
                $where = ['nik_karyawan' => auth()->user()->nik];
                $trx = TrxPenilaian::whereIn('nik_karyawan', $nik_karyawan)->whereIn('id_status', $arr_id_status)->whereIn('is_perpanjang', $arr_is_perpanjang)->where($where)->orderBy('id', 'desc')->get();
                // nik_penilai_1
                $where = ['nik_penilai_1' => auth()->user()->nik];
                $trx = $trx->concat(TrxPenilaian::whereIn('nik_karyawan', $nik_karyawan)->whereIn('id_status', $arr_id_status)->whereIn('is_perpanjang', $arr_is_perpanjang)->where($where)->orderBy('id', 'desc')->get());
                // nik_penilai_2
                $where = ['nik_penilai_2' => auth()->user()->nik];
                $trx = $trx->concat(TrxPenilaian::whereIn('nik_karyawan', $nik_karyawan)->whereIn('id_status', $arr_id_status)->whereIn('is_perpanjang', $arr_is_perpanjang)->where($where)->orderBy('id', 'desc')->get());
                // nik_penilai_3
                $where = ['nik_penilai_3' => auth()->user()->nik];
                $trx = $trx->concat(TrxPenilaian::whereIn('nik_karyawan', $nik_karyawan)->whereIn('id_status', $arr_id_status)->whereIn('is_perpanjang', $arr_is_perpanjang)->where($where)->orderBy('id', 'desc')->get());
            }
        }else{
            $arr_id_status = array();
            $arr_is_perpanjang = array();
            $arr_id_status_lengkap = array();
            $arr_perusahaan = array();
        }
        // return response()->json(['id_status_lengkap' => $id_status_lengkap, 'arr_id_status_lengkap' => $arr_id_status_lengkap]);
        // end filter
        return view( 'formulir.penilaian.index', compact( 'trx', 'no', 'id_status', 'is_perpanjang', 'id_status_lengkap', 'perusahaan', 'arr_id_status', 'arr_is_perpanjang', 'arr_id_status_lengkap', 'arr_perusahaan'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('hc');
        //passing parameter ke formulir page
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        $where = ['is_aktif' => 1];
        $karyawan = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
        $tipe = MtrPenilaianTipe::orderBy('tipe_penilaian', 'asc')->get();
        if(Gate::allows('hc_nkp')){
            $karyawan = Karyawan::select('nik', 'nama')->whereIn('nik',$arr_nik_nkp)->where($where)->orderBy('nama', 'asc')->get();
            $tipe = MtrPenilaianTipe::whereIn('id',[6])->orderBy('tipe_penilaian', 'asc')->get();
        }
        $status = 'create';
        return view( 'formulir.penilaian.insert', compact( 'karyawan', 'tipe', 'status' ) );
    }

    public function layer($id=0)
    {
        // ambil parameter karyawan dan semua layer
        $where = ['is_aktif' => 1, 'nik'=> $id];
        $layer = Karyawan::select('layer1', 'layer2', 'layer3')->where($where)->first();
        $where = ['is_aktif' => 1];
        $karyawan = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
        return response()->json(['layer' => $layer, 'karyawan' => $karyawan]);
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
        $trx = New TrxPenilaian;
        $trx->nik_karyawan = $request->nik_karyawan;
        $trx->nik_penilai_1 = $request->penilai_1;
        $trx->karyawan_property = $this->getNikProperty($request->nik_karyawan);
        $trx->penilai1_property = $this->getNikProperty($request->penilai_1);
        if($request->has('penilai_2')){
            $trx->nik_penilai_2 = $request->penilai_2;
            $trx->penilai2_property = $this->getNikProperty($request->penilai_2);
        }
        if($request->has('penilai_3')){
            $trx->nik_penilai_3 = $request->penilai_3;
            $trx->penilai3_property = $this->getNikProperty($request->penilai_3);
        }
        $trx->id_tipe = $request->id_tipe;
        $trx->id_status = 1;
        // 6 = Penilaian Outsourcing
        if($trx->id_tipe == 6){
            $trx->id_status = 16;
        }
        $trx->save();
        $this->insertLog($trx->id, $trx->id_status, auth()->user()->nik);
        // simpan history perubahan
        $data = New TrxPenilaianLogHistory;
        $data->id_trx = $trx->id;
        $data->id_status = $trx->id_status;
        $data->nik_pic = auth()->user()->nik;
        $data->revised_by = auth()->user()->nik;
        $data->save();
        // 
        $array = json_decode($this->getPertanyaanPenilaian(), true);
        $id_level = json_decode($trx->karyawan_property, true)['id_level'];
        // 6 = Penilaian Outsourcing
        if($trx->id_tipe == 6){
            $array = json_decode($this->getPertanyaanPenilaianOutsourcing(0), true);
            // 3 = Supervisor (SPV)
            // untuk level spv keatas
            if($id_level > 2){
                $array = json_decode($this->getPertanyaanPenilaianOutsourcing(1), true);
            }
            foreach($array as $values){
                $this->insertPenilaianSkorOutsourcing($trx->id, $values);
            }
        }
        // selain Outsourcing
        else{
            foreach($array as $values) {
                // hanya level spv ke atas (>2) yang diberikan pertanyaan ini
                if($values['is_spv']==1){
                    if($id_level > 2){
                        $this->insertPenilaianSkor($trx->id,$values['id_nilai3'],$values['penilaian_1'],$values['penilaian_2'],$values['penilaian_2_ket'],$values['penilaian_3'],$values['is_spv']);
                    }
                }
                // semua level mendapatkan pertanyaan ini
                else{
                    $this->insertPenilaianSkor($trx->id,$values['id_nilai3'],$values['penilaian_1'],$values['penilaian_2'],$values['penilaian_2_ket'],$values['penilaian_3'],$values['is_spv']);
                }
            }
        }
        // parameter yang dipassing ke sendemail
        $karyawan = json_decode($trx->karyawan_property, true);
        $atasan = json_decode($trx->penilai1_property, true);
        $nik = $request->nik_karyawan;
        $pic = 'nik_karyawan';
        $id_status = 1;
        $mailData['title'] = 'Formulir Penilaian Karyawan';
        $mailData['filename'] = 'emails.penilaian0';
        $mailData['to'] = $karyawan['email'];
        if($trx->id_tipe == 2 || $trx->id_tipe == 4){
            $mailData['title'] .= '- Reguler';
        }else if($trx->id_tipe == 3 || $trx->id_tipe == 5){
            $mailData['title'] .= '- Assesment';
        }
        // 6 = Penilaian Outsourcing
        else if($trx->id_tipe == 6){
            $mailData['title'] .= ' - Outsourcing';
            $mailData['to'] = $atasan['email'];
            $mailData['filename'] = 'emails.penilaian0_outsourcing';
            $nik = $request->penilai_1;
            $pic = 'nik_penilai_1';
            $id_status = 16;
        }
        $mailData['data'] = ['nama'=>$karyawan['nama'].'/'.$karyawan['nik'], 'jabatan'=>$karyawan['jabatan'], 'unit_kerja'=>$karyawan['divisi'].'/'.$karyawan['dep'].'/'.$karyawan['sub_dep'], 'atasan'=>$atasan['nama'], 'link'=> route('penilaianskor.show', [(new OpenDataController)->encryptWithKey($trx->id), 'nik' => (new OpenDataController)->encryptWithKey($nik), 'id_status' => (new OpenDataController)->encryptWithKey($id_status), 'pic' => (new OpenDataController)->encryptWithKey($pic)]), 'id_trx' => $trx->id ];
        $todolist = ['id_trx' => $trx->id, 'nik' => $nik, 'id_status' => $id_status, 'pic' => $pic];
        $this->insertToDoList($todolist, $mailData);
        $this->sendEmail($mailData);
        // 
        return redirect( 'penilaian' )->with( 'msg', 'Data is saved successfully.' );
    }

    public function sendEmail($mailData)
    {
        $ccEmails = explode(',', MtrUtility::find(11)->informasi);
        Mail::to($mailData['to'])->cc($ccEmails)->send(new MattaRebornMail($mailData));
    }

    public function insertLog($id_trx, $id_status, $nik_pic)
    {
        $data = New TrxPenilaianLog;
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

    public function insertPenilaianSkor($id_trx,$id_nilai3,$penilaian_1,$penilaian_2,$penilaian_2_ket,$penilaian_3,$is_spv)
    {
        $data = New TrxPenilaianSkor;
        $data->id_trx = $id_trx;
        $data->id_nilai3 = $id_nilai3;
        $data->penilaian_1 = $penilaian_1;
        $data->penilaian_2 = $penilaian_2;
        $data->penilaian_2_ket = $penilaian_2_ket;
        $data->penilaian_3 = $penilaian_3;
        $data->is_spv = $is_spv;
        $data->save();
    }

    public function insertPenilaianSkorOutsourcing($id_trx, $array)
    {
        $data = New TrxPenilaianSkor;
        $data->id_trx = $id_trx;
        $data->id_nilai3 = $array['id'];
        $data->penilaian_1 = $array['obyek'];
        $data->save();
    }

    public function getNikProperty($nik)
    {
        $where = [ 'nik' => $nik ];
        $data = Karyawan::select('mtr_tbl_karyawan.nik','mtr_tbl_karyawan.nama','mtr_tbl_karyawan.email','mtr_tbl_perusahaan.nama as perusahaan','mtr_tbl_divisi.nama as divisi','mtr_tbl_departemen.nama as dep','mtr_tbl_sub_departemen.nama as sub_dep','mtr_tbl_level.level as level','mtr_tbl_jabatan.jabatan as jabatan','mtr_tbl_level.id as id_level','mtr_tbl_karyawan.tanggal_bergabung','mtr_tbl_karyawan.tanggal_berakhir','mtr_tbl_karyawan.tanggal_karyawan_tetap')
        ->leftJoin('mtr_tbl_perusahaan', 'mtr_tbl_perusahaan.id', '=', 'mtr_tbl_karyawan.kode_perusahaan')
        ->leftJoin('mtr_tbl_divisi', 'mtr_tbl_divisi.id', '=', 'mtr_tbl_karyawan.kode_divisi')
        ->leftJoin('mtr_tbl_departemen', 'mtr_tbl_departemen.id', '=', 'mtr_tbl_karyawan.kode_departemen')
        ->leftJoin('mtr_tbl_sub_departemen', 'mtr_tbl_sub_departemen.id', '=', 'mtr_tbl_karyawan.kode_sub_departemen')
        ->leftJoin('mtr_tbl_level', 'mtr_tbl_level.id', '=', 'mtr_tbl_karyawan.kode_level')
        ->leftJoin('mtr_tbl_jabatan', 'mtr_tbl_jabatan.id', '=', 'mtr_tbl_karyawan.kode_jabatan')
        ->where($where)->first();
        return json_encode( $data );
    }

    public function getPertanyaanPenilaian()
    {
        $data = MtrPenilaian3::select('mtr_tbl_penilaian_3.id as id_nilai3','mtr_tbl_penilaian_3.obyek as penilaian_3','mtr_tbl_penilaian_2.obyek as penilaian_2','mtr_tbl_penilaian_2.keterangan as penilaian_2_ket','mtr_tbl_penilaian_1.obyek as penilaian_1','mtr_tbl_penilaian_1.is_spv as is_spv')
        ->leftJoin('mtr_tbl_penilaian_2', 'mtr_tbl_penilaian_3.id_nilai2', '=', 'mtr_tbl_penilaian_2.id')
        ->leftJoin('mtr_tbl_penilaian_1', 'mtr_tbl_penilaian_2.id_nilai1', '=', 'mtr_tbl_penilaian_1.id')
        ->orderBy('mtr_tbl_penilaian_3.id', 'asc')->get();
        return json_encode( $data );
    }

    public function getPertanyaanPenilaianOutsourcing($is_spv)
    {
        $data = MtrPenilaianOutsource::orderBy('id', 'asc')->get();
        if($is_spv==0){
            $data = MtrPenilaianOutsource::where(['is_spv'=>0])->orderBy('id', 'asc')->get();
        }
        return json_encode( $data );
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
        $id = (new OpenDataController)->decryptWithKey($id);
        $penilaian = TrxPenilaian::find( $id );
        // ambil property para pihak
        $karyawan = json_decode($penilaian->karyawan_property, true);
        $penilai1 = json_decode($penilaian->penilai1_property, true);
        $penilai2 = json_decode($penilaian->penilai2_property, true);
        $penilai3 = json_decode($penilaian->penilai3_property, true);
        // 
        $view = $request->view;
        $view = (new OpenDataController)->decryptWithKey($view);
        switch ($view) {
            case 'detail':
                // 
                $where = [ 'id_trx' => $id ];
                $history = TrxPenilaianLog::where($where)->orderBy('created_at','desc')->get();
                // 
                $txt_perpanjang1 = '';
                $where = [ 'id_trx' => $id, 'nik_pic'=> $penilai1['nik'] ];
                $trx = TrxPenilaianLog::where($where)->whereNotNull('is_perpanjang')->orderBy('created_at','desc')->first();
                if(!empty($trx)){
                    if($trx->is_perpanjang){
                        $txt_perpanjang1 = 'Status Perpanjangan: Diperpanjang '.$trx->bulan.' bulan';
                    }else{
                        $txt_perpanjang1 = 'Status Perpanjangan: Tidak Diperpanjang';
                    }
                }
                // 
                $txt_perpanjang2 = '';
                if(!empty($penilaian->nik_penilai_2)){
                    $where = [ 'id_trx' => $id, 'nik_pic'=> $penilai2['nik'] ];
                    $trx = TrxPenilaianLog::where($where)->whereNotNull('is_perpanjang')->orderBy('created_at','desc')->first();
                    if(!empty($trx)){
                        if($trx->is_perpanjang){
                            $txt_perpanjang2 = 'Status Perpanjangan: Diperpanjang '.$trx->bulan.' bulan';
                        }else{
                            $txt_perpanjang2 = 'Status Perpanjangan: Tidak Diperpanjang';
                        }
                    }
                }
                // 
                $txt_perpanjang3 = '';
                if(!empty($penilaian->nik_penilai_3)){
                    $where = [ 'id_trx' => $id, 'nik_pic'=> $penilai3['nik'] ];
                    $trx = TrxPenilaianLog::where($where)->whereNotNull('is_perpanjang')->orderBy('created_at','desc')->first();
                    if(!empty($trx)){
                        if($trx->is_perpanjang){
                            $txt_perpanjang3 = 'Status Perpanjangan: Diperpanjang '.$trx->bulan.' bulan';
                        }else{
                            $txt_perpanjang3 = 'Status Perpanjangan: Tidak Diperpanjang';
                        }
                    }
                }
                return view( 'formulir.penilaian.view', compact( 'history', 'penilaian', 'karyawan', 'penilai1', 'penilai2', 'penilai3', 'txt_perpanjang1', 'txt_perpanjang2', 'txt_perpanjang3' ) );
                break;
            case 'result':
                // ambil angka rata-rata berdasarkan penilaian_2
                $where = ['id_trx' => $id];
                $trxSkor2 = DB::table('trx_tbl_penilaian_skor')
                ->selectRaw('penilaian_2, CAST(AVG(skor_karyawan) AS DECIMAL(6,2)) AS avg_kar, CAST(AVG(skor_penilai_1) AS DECIMAL(6,2)) AS avg_nilai1')
                ->where($where)->groupBy('penilaian_2')
                ->orderBy('id_nilai3', 'asc')->get();
                // 
                $angka_skor = '';
                $no = 1;
                foreach ($trxSkor2 as $value2) {
                    $strNilai2 = $value2->penilaian_2;
                    $angka_skor .= '<tr class="bodycopy"><td><strong>'.$strNilai2.'</strong></td>
                    <td><strong><div align="center">'.$value2->avg_kar.'</div></strong></td>
                    <td><strong><div align="center">'.$value2->avg_nilai1.'</div></strong></td></tr>';
                    // 
                    $where = ['id_trx' => $id];
                    $trxSkor3 = TrxPenilaianSkor::select('id_nilai3','penilaian_3','skor_karyawan','skor_penilai_1')
                    ->where('penilaian_2', '=', $strNilai2)->where($where)->orderBy('id_nilai3', 'asc')->get();
                    foreach ($trxSkor3 as $value3) {
                        $strNilai3 = $value3['penilaian_3'];
                        $angka_skor .= '<tr class="bodycopy"><td>'.$no.'. '.$strNilai3.'</td><td><div align="center">'.$value3['skor_karyawan'].'</div></td><td><div align="center">'.$value3['skor_penilai_1'].'</div></td></tr>';
                        $no++;
                    }
                }
                // ambil angka rata-rata semua
                $where = ['id_trx' => $id];
                $trxSkor2 = DB::table('trx_tbl_penilaian_skor')
                ->selectRaw('CAST(AVG(skor_karyawan) AS DECIMAL(6,2)) AS avg_kar, CAST(AVG(skor_penilai_1) AS DECIMAL(6,2)) AS avg_nilai1')
                ->where($where)->first();
                $trxSkorTotal = DB::table('trx_tbl_penilaian_skor')->selectRaw('SUM(skor_karyawan) AS skor_karyawan, SUM(skor_penilai_1) AS skor_penilai_1')->where($where)->first();
                // 
                $angka_rata = '<tr class="bodycopy"><td><strong><div align="right">RATA-RATA</div></strong></td>
                <td><strong><div align="center">'.$trxSkor2->avg_kar.'</div></strong></td>
                <td><strong><div align="center">'.$trxSkor2->avg_nilai1.'</div></strong></td>
                </tr>';
                $angka_total = '<tr class="bodycopy"><td><strong><div align="right">NILAI TOTAL</div></strong></td>
                <td><strong><div align="center">'.$trxSkorTotal->skor_karyawan.'</div></strong></td>
                <td><strong><div align="center">'.$trxSkorTotal->skor_penilai_1.'</div></strong></td>
                </tr>';
                // ambil isian kesimpulan dan rekomendasi
                $txt_perpanjang = '';
                $where = [ 'id_trx' => $id, 'nik_pic'=> $penilai1['nik'] ];
                $trx = TrxPenilaianLog::where($where)->whereNotNull('is_perpanjang')->orderBy('created_at','desc')->first();
                if(!empty($trx)){
                    if($trx->is_perpanjang){
                        $txt_perpanjang = '<br/>Status Perpanjangan: Diperpanjang '.$trx->bulan.' bulan';
                    }else{
                        $txt_perpanjang = '<br/>Status Perpanjangan: Tidak Diperpanjang';
                    }
                }
                $isian_kes_rek = '<tr><td class="innerpadding bodycopy"><b>Kesimpulan dan Rekomendasi oleh '.$penilai1['nama'].':<br/>'.$penilaian->kes_rek_1.$txt_perpanjang;
                if(!empty($penilaian->nik_penilai_2)){
                    $txt_perpanjang = '';
                    $where = [ 'id_trx' => $id, 'nik_pic'=> $penilai2['nik'] ];
                    $trx = TrxPenilaianLog::where($where)->whereNotNull('is_perpanjang')->orderBy('created_at','desc')->first();
                    if(!empty($trx)){
                        if($trx->is_perpanjang){
                            $txt_perpanjang = '<br/>Status Perpanjangan: Diperpanjang '.$trx->bulan.' bulan';
                        }else{
                            $txt_perpanjang = '<br/>Status Perpanjangan: Tidak Diperpanjang';
                        }
                    }
                    $isian_kes_rek .= '<br/><br/>Kesimpulan dan Rekomendasi oleh '.$penilai2['nama'].':<br/>'.$penilaian->kes_rek_2.$txt_perpanjang;
                }
                if(!empty($penilaian->nik_penilai_3)){
                    $txt_perpanjang = '';
                    $where = [ 'id_trx' => $id, 'nik_pic'=> $penilai3['nik'] ];
                    $trx = TrxPenilaianLog::where($where)->whereNotNull('is_perpanjang')->orderBy('created_at','desc')->first();
                    if(!empty($trx)){
                        if($trx->is_perpanjang){
                            $txt_perpanjang = '<br/>Status Perpanjangan: Diperpanjang '.$trx->bulan.' bulan';
                        }else{
                            $txt_perpanjang = '<br/>Status Perpanjangan: Tidak Diperpanjang';
                        }
                    }
                    $isian_kes_rek .= '<br/><br/>Kesimpulan dan Rekomendasi oleh '.$penilai3['nama'].':<br/>'.$penilaian->kes_rek_3.$txt_perpanjang;
                }
                $isian_kes_rek .= '</b></td></tr>';
                // nilai outsourcing
                if($penilaian->id_tipe == 6){
                    $where = [ 'id_trx' => $id ];
                    $angka_skor = TrxPenilaianSkor::where($where)->orderBy('id_nilai3', 'asc')->get();
                    $angka_rata = TrxPenilaianSkor::where($where)->sum('skor_penilai_1');
                }
                // 
                return view( 'formulir.penilaian.result', compact( 'penilaian', 'karyawan', 'penilai1', 'angka_skor', 'angka_rata', 'isian_kes_rek', 'angka_total' ) );
                break;
            case 'pdf':
                // ambil angka rata-rata berdasarkan penilaian_2
                $where = ['id_trx' => $id];
                $trxSkor2 = DB::table('trx_tbl_penilaian_skor')
                ->selectRaw('penilaian_2, CAST(AVG(skor_karyawan) AS DECIMAL(6,2)) AS avg_kar, CAST(AVG(skor_penilai_1) AS DECIMAL(6,2)) AS avg_nilai1')
                ->where($where)->groupBy('penilaian_2')
                ->orderBy('id_nilai3', 'asc')->get();
                // 
                $angka_skor = '';
                $no = 1;
                foreach ($trxSkor2 as $value2) {
                    $strNilai2 = $value2->penilaian_2;
                    $angka_skor .= '<tr><td><strong>'.$strNilai2.'</strong></td>
                    <td align="center"><strong>'.$value2->avg_kar.'</strong></td>
                    <td align="center"><strong>'.$value2->avg_nilai1.'</strong></td></tr>';
                    // 
                    $where = ['id_trx' => $id];
                    $trxSkor3 = TrxPenilaianSkor::select('id_nilai3','penilaian_3','skor_karyawan','skor_penilai_1')
                    ->where('penilaian_2', '=', $strNilai2)->where($where)->orderBy('id_nilai3', 'asc')->get();
                    foreach ($trxSkor3 as $value3) {
                        $strNilai3 = $value3['penilaian_3'];
                        $angka_skor .= '<tr><td>'.$no.'. '.$strNilai3.'</td>
                        <td><div align="center">'.$value3['skor_karyawan'].'</div></td>
                        <td><div align="center">'.$value3['skor_penilai_1'].'</div></td></tr>';
                        $no++;
                    }
                }
                // ambil angka rata-rata semua
                $where = ['id_trx' => $id];
                $trxSkor2 = DB::table('trx_tbl_penilaian_skor')
                ->selectRaw('CAST(AVG(skor_karyawan) AS DECIMAL(6,2)) AS avg_kar, CAST(AVG(skor_penilai_1) AS DECIMAL(6,2)) AS avg_nilai1')
                ->where($where)->first();
                $trxSkorTotal = DB::table('trx_tbl_penilaian_skor')->selectRaw('SUM(skor_karyawan) AS skor_karyawan, SUM(skor_penilai_1) AS skor_penilai_1')->where($where)->first();
                // 
                $angka_rata = '<tr><td width="70%" align="right"><strong>RATA-RATA</strong></td>
                <td width="15%" align="center"><strong>'.$trxSkor2->avg_kar.'</strong></td>
                <td width="15%" align="center"><strong>'.$trxSkor2->avg_nilai1.'</strong></td></tr>';
                $angka_total = '<tr><td width="70%" align="right"><strong>NILAI TOTAL</strong></td>
                <td width="15%" align="center"><strong>'.$trxSkorTotal->skor_karyawan.'</strong></td>
                <td width="15%" align="center"><strong>'.$trxSkorTotal->skor_penilai_1.'</strong></td></tr>';
                // ambil property para penilai
                $para_penilai = '<tr><td width="30%">Nama Penilai 1<br/>NIP<br/>Jabatan<br/>Direktorat / Divisi / Departemen</td>
                <td width="70%">: '.$penilai1['nama'].'<br/>: '.$penilai1['nik'].'<br/>: '.$penilai1['jabatan'].'<br/>: '.$penilai1['divisi'].' / '.$penilai1['dep'].' / '.$penilai1['sub_dep'].'</td></tr>';
                // ambil isian kesimpulan dan rekomendasi
                $txt_perpanjang = '';
                $where = [ 'id_trx' => $id, 'nik_pic'=> $penilai1['nik'] ];
                $trx = TrxPenilaianLog::where($where)->whereNotNull('is_perpanjang')->orderBy('created_at','desc')->first();
                // return response()->json( [ 'trx' => $trx ], 200 );
                if(!empty($trx)){
                    if($trx->is_perpanjang){
                        $txt_perpanjang = '<br/>Status Perpanjangan: Diperpanjang '.$trx->bulan.' bulan';
                    }else{
                        $txt_perpanjang = '<br/>Status Perpanjangan: Tidak Diperpanjang';
                    }
                }
                $isian_kes_rek = '<table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr><td>Kesimpulan dan Rekomendasi Penilai 1:<br/>'.$penilaian->kes_rek_1.$txt_perpanjang.'</td></tr>
                    </table><hr/>';
                // ambil timestamp terakhir para pihak
                $karyawan_timestamp = '';
                $penilai1_timestamp = '';
                $penilai2_timestamp = '';
                $penilai3_timestamp = '';
                $where = [ 'id_trx' => $id, 'nik_pic'=> $karyawan['nik'] ];
                $trx = TrxPenilaianLog::where($where)->orderBy('created_at','desc')->first();
                if(!empty($trx)){
                    $karyawan_timestamp = '<img src="'.public_path("assets/images/matta/assessment_1.png").'" width="85" height="85" /><br/>
                    '.$karyawan['nama'].'<br/>'.$karyawan['jabatan'].'<br/>'.$trx->created_at;
                }
                // 
                $where = [ 'id_trx' => $id, 'nik_pic'=> $penilai1['nik'] ];
                $trx = TrxPenilaianLog::where($where)->orderBy('created_at','desc')->first();
                if(!empty($trx)){
                    $penilai1_timestamp = '<img src="'.public_path("assets/images/matta/assessment_1.png").'" width="85" height="85" /><br/>
                    '.$penilai1['nama'].'<br/>'.$penilai1['jabatan'].'<br/>'.$trx->created_at;
                }
                // ambil timestamp semua para pihak
                $timestamp = '<td>Karyawan:<br/>'.$karyawan_timestamp.'</td>';
                if($penilaian->id_tipe == 6){
                    $timestamp = '';
                }
                // 
                $timestamp .= '<td>Penilai 1:<br/>'.$penilai1_timestamp.'</td>';
                // 
                if(!empty($penilaian->nik_penilai_2)){
                    $para_penilai .= '<tr><td>Nama Penilai 2<br/>NIP<br/>Jabatan<br/>Direktorat / Divisi / Departemen</td>
                    <td>: '.$penilai2['nama'].'<br/>: '.$penilai2['nik'].'<br/>: '.$penilai2['jabatan'].'<br/>: '.$penilai2['divisi'].' / '.$penilai2['dep'].' / '.$penilai2['sub_dep'].'</td></tr>';
                    // ambil isian kesimpulan dan rekomendasi
                    $txt_perpanjang = '';
                    $where = [ 'id_trx' => $id, 'nik_pic'=> $penilai2['nik'] ];
                    $trx = TrxPenilaianLog::where($where)->whereNotNull('is_perpanjang')->orderBy('created_at','desc')->first();
                    if(!empty($trx)){
                        if($trx->is_perpanjang){
                            $txt_perpanjang = '<br/>Status Perpanjangan: Diperpanjang '.$trx->bulan.' bulan';
                        }else{
                            $txt_perpanjang = '<br/>Status Perpanjangan: Tidak Diperpanjang';
                        }
                    }
                    $isian_kes_rek .= '<table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr><td>Kesimpulan dan Rekomendasi Penilai 2:<br/>'.$penilaian->kes_rek_2.$txt_perpanjang.'</td></tr>
                    </table><hr/>';
                    // 
                    $where = [ 'id_trx' => $id, 'nik_pic'=> $penilai2['nik'] ];
                    $trx = TrxPenilaianLog::where($where)->orderBy('created_at','desc')->first();
                    if(!empty($trx)){
                        $penilai2_timestamp = '<img src="'.public_path("assets/images/matta/assessment_1.png").'" width="85" height="85" /><br/>
                        '.$penilai2['nama'].'<br/>
                        '.$penilai2['jabatan'].'<br/>'.$trx->created_at;
                    }
                    // 
                    $timestamp .= '<td>Penilai 2:<br/>
                    '.$penilai2_timestamp.'</td>';
                }
                if(!empty($penilaian->nik_penilai_3)){
                    $para_penilai .= '<tr><td>Nama Penilai 3<br/>NIP<br/>Jabatan<br/>Direktorat / Divisi / Departemen</td>
                    <td>: '.$penilai3['nama'].'<br/>: '.$penilai3['nik'].'<br/>: '.$penilai3['jabatan'].'<br/>: '.$penilai3['divisi'].' / '.$penilai3['dep'].' / '.$penilai3['sub_dep'].'</td></tr>';
                    // ambil isian kesimpulan dan rekomendasi
                    $txt_perpanjang = '';
                    $where = [ 'id_trx' => $id, 'nik_pic'=> $penilai3['nik'] ];
                    $trx = TrxPenilaianLog::where($where)->whereNotNull('is_perpanjang')->orderBy('created_at','desc')->first();
                    if(!empty($trx)){
                        if($trx->is_perpanjang){
                            $txt_perpanjang = '<br/>Status Perpanjangan: Diperpanjang '.$trx->bulan.' bulan';
                        }else{
                            $txt_perpanjang = '<br/>Status Perpanjangan: Tidak Diperpanjang';
                        }
                    }
                    $isian_kes_rek .= '<table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr><td>Kesimpulan dan Rekomendasi Penilai 3:<br/>'.$penilaian->kes_rek_3.$txt_perpanjang.'</td></tr>
                    </table><hr/>';
                    // 
                    $where = [ 'id_trx' => $id, 'nik_pic'=> $penilai3['nik'] ];
                    $trx = TrxPenilaianLog::where($where)->orderBy('created_at','desc')->first();
                    if(!empty($trx)){
                        $penilai3_timestamp = '<img src="'.public_path("assets/images/matta/assessment_1.png").'" width="85" height="85" /><br/>
                        '.$penilai3['nama'].'<br/>
                        '.$penilai3['jabatan'].'<br/>'.$trx->created_at;
                    }
                    // 
                    $timestamp .= '<td>Penilai 3:<br/>
                    '.$penilai3_timestamp.'</td>';
                }
                if($penilaian->id_status == 4 || $penilaian->id_status == 8){
                    $where = [ 'id_trx' => $id ];
                    $trx = TrxPenilaianLog::where($where)->orderBy('created_at','desc')->first();
                    $where = [ 'nik' => $trx->nik_pic ];
                    $hco_pic = Karyawan::where($where)->first();
                    if(!empty($trx)){
                        $hco_timestamp = '<img src="'.public_path("assets/images/matta/assessment_1.png").'" width="85" height="85" /><br/>
                        '.$hco_pic->nama.'<br/>
                        '.$hco_pic->_jabatan->jabatan.'<br/>'.$trx->created_at;
                    }
                    $timestamp .= '<td>Mengetahui:<br/>
                    '.$hco_timestamp.'</td>';
                }
                // ambil isian kesimpulan dan rekomendasi
                $isian_kes_rek .= '<table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td>Kesimpulan dan Rekomendasi HCO:<br/>'.$penilaian->kes_rek_final.'</td></tr></table>';
                // nilai outsourcing
                if($penilaian->id_tipe == 6){
                    $where = [ 'id_trx' => $id ];
                    $angka_skor = TrxPenilaianSkor::where($where)->orderBy('id_nilai3', 'asc')->get();
                    $angka_rata = TrxPenilaianSkor::where($where)->sum('skor_penilai_1');
                }
                // print to pdf
                $waktu = date('YmdHis');
                $filename = $waktu.'_formulir_penilaian.pdf';
                if($penilaian->is_perpanjang == 1){
                    $_perpanjang = $penilaian->_perpanjang->informasi.' '.$penilaian->bulan.' bulan';
                }else{
                    $_perpanjang = $penilaian->_perpanjang->informasi;
                }
                $data = ['id_data'=>$penilaian->id, 'id_tipe'=>$penilaian->id_tipe, 'tipe'=>$penilaian->_tipe->tipe_penilaian, 'perpanjang'=>$_perpanjang ];
                $view = \View::make( 'formulir.penilaian.pdf', compact( 'data', 'karyawan', 'penilai1', 'para_penilai', 'angka_skor', 'angka_rata', 'isian_kes_rek', 'timestamp', 'angka_total' ) );
                $html = $view->render();
                $pdf = new TCPDF;
                $pdf::SetTitle('Formulir Penilaian Karyawan');
                $pdf::SetFont('times', '', 10, '', true);
                $pdf::AddPage();
                $pdf::writeHTML($html, true, false, true, false, '');
                $pdf::Output(public_path($filename), 'I');
                return response()->download(public_path($filename));
                // 
                break;
            default:
            //   return response()->json( [ 'success' => false, 'message' => 'No Data is viewed', 'data' => [] ], 200 );
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
        $this->authorize('hc');
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        $id = (new OpenDataController)->decryptWithKey($id);
        // dd($id);
        $where = [ 'id_trx' => $id ];
        $history = TrxPenilaianLog::where($where)->orderBy('created_at','desc')->get();
        $penilaian = TrxPenilaian::find( $id );
        $where = ['is_aktif' => 1];
        $karyawan = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
        if(Gate::allows('hc_nkp')){
            $karyawan = Karyawan::select('nik', 'nama')->whereIn('nik',$arr_nik_nkp)->where($where)->orderBy('nama', 'asc')->get();
        }
        $_karyawan = json_decode($penilaian->karyawan_property, true);
        $penilai1 = json_decode($penilaian->penilai1_property, true);
        $penilai2 = '';
        $penilai3 = '';
        if(!empty($penilaian->nik_penilai_2)){
            $penilai2 = json_decode($penilaian->penilai2_property, true);
        }
        if(!empty($penilaian->nik_penilai_3)){
            $penilai3 = json_decode($penilaian->penilai3_property, true);
        }
        $tipe = MtrPenilaianTipe::whereNotIn('id', [6])->orderBy('tipe_penilaian', 'asc')->get();
        if($penilaian->id_tipe == 6){
            $tipe = MtrPenilaianTipe::whereIn('id', [6])->orderBy('tipe_penilaian', 'asc')->get();
        }
        $status = MtrPenilaianStatus::whereIn('id', [4,5,8,9,10,11,12,13,14,15,$penilaian->id_status])->orderBy('id', 'asc')->get();
        if($penilaian->id_tipe == 6){
            $status = MtrPenilaianStatus::whereIn('id', [8,15,$penilaian->id_status])->orderBy('id', 'asc')->get();
        }
        $perpanjang = MtrPenilaianPerpanjang::orderBy('id', 'asc')->get();// ambil angka rata-rata berdasarkan penilaian_2
        $where = ['id_trx' => $id];
        $trxSkor2 = DB::table('trx_tbl_penilaian_skor')
        ->selectRaw('penilaian_2, CAST(AVG(skor_karyawan) AS DECIMAL(6,2)) AS avg_kar, CAST(AVG(skor_penilai_1) AS DECIMAL(6,2)) AS avg_nilai1')
        ->where($where)->groupBy('penilaian_2')
        ->orderBy('id_nilai3', 'asc')->get();
        // 
        $angka_skor = '';
        $no = 1;
        foreach ($trxSkor2 as $value2) {
            $strNilai2 = $value2->penilaian_2;
            $angka_skor .= '<tr><td><strong>'.$strNilai2.'</strong></td>
            <td><div align="center"><strong>'.$value2->avg_kar.'</strong></div></td>
            <td><div align="center"><strong>'.$value2->avg_nilai1.'</strong></div></td></tr>';
            // 
            $where = ['id_trx' => $id];
            $trxSkor3 = TrxPenilaianSkor::select('id_nilai3','penilaian_3','skor_karyawan','skor_penilai_1')
            ->where('penilaian_2', '=', $strNilai2)->where($where)->orderBy('id_nilai3', 'asc')->get();
            foreach ($trxSkor3 as $value3) {
                $strNilai3 = $value3['penilaian_3'];
                $angka_skor .= '<tr><td>'.$no.'. '.$strNilai3.'</td><td><div align="center">'.$value3['skor_karyawan'].'</div></td><td><div align="center">'.$value3['skor_penilai_1'].'</div></td></tr>';
                $no++;
            }
        }
        // ambil angka rata-rata semua
        $where = ['id_trx' => $id];
        $trxSkor2 = DB::table('trx_tbl_penilaian_skor')
        ->selectRaw('CAST(AVG(skor_karyawan) AS DECIMAL(6,2)) AS avg_kar, CAST(AVG(skor_penilai_1) AS DECIMAL(6,2)) AS avg_nilai1')
        ->where($where)->first();
        $trxSkorTotal = DB::table('trx_tbl_penilaian_skor')->selectRaw('SUM(skor_karyawan) AS skor_karyawan, SUM(skor_penilai_1) AS skor_penilai_1')->where($where)->first();
        // 
        $angka_rata = '<tr><td><div align="right"><strong>RATA-RATA</strong></div></td>
        <td><div align="center"><strong>'.$trxSkor2->avg_kar.'</strong></div></td>
        <td><div align="center"><strong>'.$trxSkor2->avg_nilai1.'</strong></div></td>
        </tr>';
        $angka_total = '<tr><td><div align="right"><strong>NILAI TOTAL</strong></div></td>
        <td><div align="center"><strong>'.$trxSkorTotal->skor_karyawan.'</strong></div></td>
        <td><div align="center"><strong>'.$trxSkorTotal->skor_penilai_1.'</strong></div></td>
        </tr>';
        // ambil isian kesimpulan dan rekomendasi
        $txt_perpanjang = '';
        $where = [ 'id_trx' => $id, 'nik_pic'=> $penilai1['nik'] ];
        $trx = TrxPenilaianLog::where($where)->whereNotNull('is_perpanjang')->orderBy('created_at','desc')->first();
        if(!empty($trx)){
            if($trx->is_perpanjang){
                $txt_perpanjang = '<br/>Status Perpanjangan: Diperpanjang '.$trx->bulan.' bulan';
            }else{
                $txt_perpanjang = '<br/>Status Perpanjangan: Tidak Diperpanjang';
            }
        }
        $isian_kes_rek = '<tr><td><b>Kesimpulan dan Rekomendasi oleh '.$penilai1['nama'].':<br/>'.$penilaian->kes_rek_1.$txt_perpanjang;
        if(!empty($penilaian->nik_penilai_2)){
            $txt_perpanjang = '';
            $where = [ 'id_trx' => $id, 'nik_pic'=> $penilai2['nik'] ];
            $trx = TrxPenilaianLog::where($where)->whereNotNull('is_perpanjang')->orderBy('created_at','desc')->first();
            if(!empty($trx)){
                if($trx->is_perpanjang){
                    $txt_perpanjang = '<br/>Status Perpanjangan: Diperpanjang '.$trx->bulan.' bulan';
                }else{
                    $txt_perpanjang = '<br/>Status Perpanjangan: Tidak Diperpanjang';
                }
            }
            $isian_kes_rek .= '<br/><br/>Kesimpulan dan Rekomendasi oleh '.$penilai2['nama'].':<br/>'.$penilaian->kes_rek_2.$txt_perpanjang;
        }
        if(!empty($penilaian->nik_penilai_3)){
            $txt_perpanjang = '';
            $where = [ 'id_trx' => $id, 'nik_pic'=> $penilai3['nik'] ];
            $trx = TrxPenilaianLog::where($where)->whereNotNull('is_perpanjang')->orderBy('created_at','desc')->first();
            if(!empty($trx)){
                if($trx->is_perpanjang){
                    $txt_perpanjang = '<br/>Status Perpanjangan: Diperpanjang '.$trx->bulan.' bulan';
                }else{
                    $txt_perpanjang = '<br/>Status Perpanjangan: Tidak Diperpanjang';
                }
            }
            $isian_kes_rek .= '<br/><br/>Kesimpulan dan Rekomendasi oleh '.$penilai3['nama'].':<br/>'.$penilaian->kes_rek_3.$txt_perpanjang;
        }
        $isian_kes_rek .= '</b></td></tr>';
        // nilai outsourcing
        if($penilaian->id_tipe == 6){
            $where = [ 'id_trx' => $id ];
            $angka_skor = TrxPenilaianSkor::where($where)->orderBy('id_nilai3', 'asc')->get();
            $angka_rata = TrxPenilaianSkor::where($where)->sum('skor_penilai_1');
        }
        // 
        return view( 'formulir.penilaian.update', compact( 'history', 'penilaian', 'karyawan', '_karyawan', 'penilai1', 'penilai2', 'penilai3', 'status', 'tipe', 'perpanjang', 'angka_skor', 'angka_rata', 'isian_kes_rek', 'angka_total' ) );
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
        // dd($request->all(), $id);
        $id = (new OpenDataController)->decryptWithKey($id);
        $id_status = $request->id_status;
        $id_tipe = $request->id_tipe;
        if($id_status==14 || $id_status==15){
            $id_status = 5;
            if($id_tipe == 6){
                $id_status = 15;
            }
        }
        $trx = TrxPenilaian::find( $id );
        if($request->has('penilai_2')){
            $trx->nik_penilai_2 = $request->penilai_2;
            $trx->penilai2_property = $this->getNikProperty($request->penilai_2);
        }
        if($request->has('penilai_3')){
            $trx->nik_penilai_3 = $request->penilai_3;
            $trx->penilai3_property = $this->getNikProperty($request->penilai_3);
        }
        if($request->has('is_perpanjang')){
            $trx->is_perpanjang = $request->is_perpanjang;
        }
        if($request->has('bulan')){
            $trx->bulan = $request->bulan;
        }
        if($request->has('kes_rek_final')){
            $trx->kes_rek_final = $request->kes_rek_final;
        }
        $trx->id_tipe = $id_tipe;
        $trx->id_status = $id_status;
        $trx->update();
        // 6=Penilaian Outsourcing
        if($id_tipe == 6){
            // tidak digunakan karena pic_hco menjadi 2 nik sedangkan ini nik sudah ditentukan dari 'nik'=>auth()->user()->nik
            // $this->deleteToDoList(['id_trx'=>$id, 'nik'=>auth()->user()->nik, 'pic'=>'hco', 'filename'=>'emails.penilaian0_outsourcing']);
            // ini yang digunakan tidak menggunaka nik
            $this->deleteToDoList(['id_trx'=>$id, 'pic'=>'hco', 'filename'=>'emails.penilaian0_outsourcing']);
        }
        // selain Penilaian Outsourcing
        else{
            $this->deleteToDoList(['id_trx'=>$id, 'nik'=>auth()->user()->nik, 'pic'=>'hco', 'filename'=>'emails.penilaian0']);
        }
        $this->insertLog($trx->id, $trx->id_status, auth()->user()->nik);
        // simpan history perubahan
        if($id_status==4 || $id_status==8){
            $data = New TrxPenilaianLogHistory;
            $data->id_trx = $id;
            $data->id_status = $id_status;
            $data->nik_pic = auth()->user()->nik;
            $data->revised_by = auth()->user()->nik;
            if($request->has('is_perpanjang')){
                $data->is_perpanjang = $request->is_perpanjang;
            }
            if($request->has('bulan')){
                $data->bulan = $request->bulan;
            }
            if($request->has('kes_rek_final')){
                $data->keterangan = $request->kes_rek_final;
            }
            $data->save();
        }
        // parameter yang dipassing ke sendemail
        $karyawan = json_decode($trx->karyawan_property, true);
        $atasan = json_decode($trx->penilai1_property, true);
        $penilai2 = json_decode($trx->penilai2_property, true);
        $penilai3 = json_decode($trx->penilai3_property, true);
        $mailData['title'] = 'Formulir Penilaian Karyawan';
        $mailData['filename'] = 'emails.penilaian0';
        if($trx->id_tipe == 2 || $trx->id_tipe == 4){
            $mailData['title'] .= '- Reguler';
        }else if($trx->id_tipe == 3 || $trx->id_tipe == 5){
            $mailData['title'] .= '- Assesment';
        }
        // 6 = Penilaian Outsourcing
        else if($trx->id_tipe == 6){
            $mailData['title'] .= ' - Outsourcing';
            $mailData['filename'] = 'emails.penilaian0_outsourcing';
        }
        if($request->has('penilai_2')){
            $mailData['data'] = ['nama'=>$karyawan['nama'].'/'.$karyawan['nik'], 'jabatan'=>$karyawan['jabatan'], 'unit_kerja'=>$karyawan['divisi'].'/'.$karyawan['dep'].'/'.$karyawan['sub_dep'], 'atasan'=>$atasan['nama'], 'link'=> route('penilaianskor.show', [(new OpenDataController)->encryptWithKey($trx->id), 'nik' => (new OpenDataController)->encryptWithKey($trx->nik_penilai_2), 'id_status' => (new OpenDataController)->encryptWithKey($id_status), 'pic' => (new OpenDataController)->encryptWithKey('nik_penilai_2')]), 'id_trx' => $trx->id ];
            $mailData['to'] = $penilai2['email'];
            $todolist = ['id_trx' => $trx->id, 'nik' => $trx->nik_penilai_2, 'id_status' => $id_status, 'pic' => 'nik_penilai_2'];
            $this->insertToDoList($todolist, $mailData);
            $this->sendEmail($mailData);
        }
        if($request->has('penilai_3')){
            $mailData['data'] = ['nama'=>$karyawan['nama'].'/'.$karyawan['nik'], 'jabatan'=>$karyawan['jabatan'], 'unit_kerja'=>$karyawan['divisi'].'/'.$karyawan['dep'].'/'.$karyawan['sub_dep'], 'atasan'=>$atasan['nama'], 'link'=> route('penilaianskor.show', [(new OpenDataController)->encryptWithKey($trx->id), 'nik' => (new OpenDataController)->encryptWithKey($trx->nik_penilai_3), 'id_status' => (new OpenDataController)->encryptWithKey($id_status), 'pic' => (new OpenDataController)->encryptWithKey('nik_penilai_3')]), 'id_trx' => $trx->id ];
            $mailData['to'] = $penilai3['email'];
            $todolist = ['id_trx' => $trx->id, 'nik' => $trx->nik_penilai_3, 'id_status' => $id_status, 'pic' => 'nik_penilai_3'];
            $this->insertToDoList($todolist, $mailData);
            $this->sendEmail($mailData);
        }
        return redirect( 'penilaian' )->with( 'msg', 'Data is saved successfully.' );
    }
    
    public function revisi(Request $request, $id)
    {
        // dd($request->all(), $id);
        $pic = $request->pic;
        $id = (new OpenDataController)->decryptWithKey($id);
        $penilaian = TrxPenilaian::find( $id );
        $where = ['is_aktif' => 1];
        $karyawan = Karyawan::select('nik', 'nama')->where($where)->orderBy('nama', 'asc')->get();
        $_karyawan = json_decode($penilaian->karyawan_property, true);
        $penilai1 = json_decode($penilaian->penilai1_property, true);
        $penilai2 = '';
        $penilai3 = '';
        if(!empty($penilaian->nik_penilai_2)){
            $penilai2 = json_decode($penilaian->penilai2_property, true);
        }
        if(!empty($penilaian->nik_penilai_3)){
            $penilai3 = json_decode($penilaian->penilai3_property, true);
        }
        $tipe = MtrPenilaianTipe::whereNotIn('id', [6])->orderBy('tipe_penilaian', 'asc')->get();
        if($penilaian->id_tipe == 6){
            $tipe = MtrPenilaianTipe::whereIn('id', [6])->orderBy('tipe_penilaian', 'asc')->get();
        }
        $status = MtrPenilaianStatus::whereIn('id', [4,5,8,9,10,11,12,13,14,15,$penilaian->id_status])->orderBy('id', 'asc')->get();
        if($penilaian->id_tipe == 6){
            $status = MtrPenilaianStatus::whereIn('id', [8,15,$penilaian->id_status])->orderBy('id', 'asc')->get();
        }
        $perpanjang = MtrPenilaianPerpanjang::orderBy('id', 'asc')->get();// ambil angka rata-rata berdasarkan penilaian_2
        $where = ['id_trx' => $id];
        $trxSkor2 = DB::table('trx_tbl_penilaian_skor')
        ->selectRaw('penilaian_2, CAST(AVG(skor_karyawan) AS DECIMAL(6,2)) AS avg_kar, CAST(AVG(skor_penilai_1) AS DECIMAL(6,2)) AS avg_nilai1')
        ->where($where)->groupBy('penilaian_2')
        ->orderBy('id_nilai3', 'asc')->get();
        // 
        $angka_skor = '';
        $no = 1;
        foreach ($trxSkor2 as $value2) {
            $strNilai2 = $value2->penilaian_2;
            $angka_skor .= '<tr><td><strong>'.$strNilai2.'</strong></td>
            <td><div align="center"><strong>'.$value2->avg_kar.'</strong></div></td>
            <td><div align="center"><strong>'.$value2->avg_nilai1.'</strong></div></td></tr>';
            // 
            $where = ['id_trx' => $id];
            $trxSkor3 = TrxPenilaianSkor::select('id_nilai3','penilaian_3','skor_karyawan','skor_penilai_1')
            ->where('penilaian_2', '=', $strNilai2)->where($where)->orderBy('id_nilai3', 'asc')->get();
            foreach ($trxSkor3 as $value3) {
                $strNilai3 = $value3['penilaian_3'];
                $angka_skor .= '<tr><td>'.$no.'. '.$strNilai3.'</td><td><div align="center">'.$value3['skor_karyawan'].'</div></td><td><div align="center">'.$value3['skor_penilai_1'].'</div></td></tr>';
                $no++;
            }
        }
        // ambil angka rata-rata semua
        $where = ['id_trx' => $id];
        $trxSkor2 = DB::table('trx_tbl_penilaian_skor')
        ->selectRaw('CAST(AVG(skor_karyawan) AS DECIMAL(6,2)) AS avg_kar, CAST(AVG(skor_penilai_1) AS DECIMAL(6,2)) AS avg_nilai1')
        ->where($where)->first();
        $trxSkorTotal = DB::table('trx_tbl_penilaian_skor')->selectRaw('SUM(skor_karyawan) AS skor_karyawan, SUM(skor_penilai_1) AS skor_penilai_1')->where($where)->first();
        // 
        $angka_rata = '<tr><td><div align="right"><strong>RATA-RATA</strong></div></td>
        <td><div align="center"><strong>'.$trxSkor2->avg_kar.'</strong></div></td>
        <td><div align="center"><strong>'.$trxSkor2->avg_nilai1.'</strong></div></td>
        </tr>';
        $angka_total = '<tr><td><div align="right"><strong>NILAI TOTAL</strong></div></td>
        <td><div align="center"><strong>'.$trxSkorTotal->skor_karyawan.'</strong></div></td>
        <td><div align="center"><strong>'.$trxSkorTotal->skor_penilai_1.'</strong></div></td>
        </tr>';
        // ambil isian kesimpulan dan rekomendasi
        $txt_perpanjang = '';
        $where = [ 'id_trx' => $id, 'nik_pic'=> $penilai1['nik'] ];
        $trx = TrxPenilaianLog::where($where)->whereNotNull('is_perpanjang')->orderBy('created_at','desc')->first();
        if(!empty($trx)){
            if($trx->is_perpanjang){
                $txt_perpanjang = '<br/>Status Perpanjangan: Diperpanjang '.$trx->bulan.' bulan';
            }else{
                $txt_perpanjang = '<br/>Status Perpanjangan: Tidak Diperpanjang';
            }
        }
        $isian_kes_rek = '<tr><td><b>Kesimpulan dan Rekomendasi oleh '.$penilai1['nama'].':<br/>'.$penilaian->kes_rek_1.$txt_perpanjang;
        if(!empty($penilaian->nik_penilai_2)){
            $txt_perpanjang = '';
            $where = [ 'id_trx' => $id, 'nik_pic'=> $penilai2['nik'] ];
            $trx = TrxPenilaianLog::where($where)->whereNotNull('is_perpanjang')->orderBy('created_at','desc')->first();
            if(!empty($trx)){
                if($trx->is_perpanjang){
                    $txt_perpanjang = '<br/>Status Perpanjangan: Diperpanjang '.$trx->bulan.' bulan';
                }else{
                    $txt_perpanjang = '<br/>Status Perpanjangan: Tidak Diperpanjang';
                }
            }
            $isian_kes_rek .= '<br/><br/>Kesimpulan dan Rekomendasi oleh '.$penilai2['nama'].':<br/>'.$penilaian->kes_rek_2.$txt_perpanjang;
        }
        if(!empty($penilaian->nik_penilai_3)){
            $txt_perpanjang = '';
            $where = [ 'id_trx' => $id, 'nik_pic'=> $penilai3['nik'] ];
            $trx = TrxPenilaianLog::where($where)->whereNotNull('is_perpanjang')->orderBy('created_at','desc')->first();
            if(!empty($trx)){
                if($trx->is_perpanjang){
                    $txt_perpanjang = '<br/>Status Perpanjangan: Diperpanjang '.$trx->bulan.' bulan';
                }else{
                    $txt_perpanjang = '<br/>Status Perpanjangan: Tidak Diperpanjang';
                }
            }
            $isian_kes_rek .= '<br/><br/>Kesimpulan dan Rekomendasi oleh '.$penilai3['nama'].':<br/>'.$penilaian->kes_rek_3.$txt_perpanjang;
        }
        $isian_kes_rek .= '</b></td></tr>';
        // nilai outsourcing
        if($penilaian->id_tipe == 6){
            $where = [ 'id_trx' => $id ];
            $angka_skor = TrxPenilaianSkor::where($where)->orderBy('id_nilai3', 'asc')->get();
            $angka_rata = TrxPenilaianSkor::where($where)->sum('skor_penilai_1');
        }
        // 
        return view( 'formulir.penilaian.revisi', compact( 'penilaian', 'karyawan', '_karyawan', 'penilai1', 'penilai2', 'penilai3', 'status', 'tipe', 'perpanjang', 'angka_skor', 'angka_rata', 'isian_kes_rek', 'pic', 'angka_total' ) );
    }

    public function revisi_save(Request $request)
    {
        // dd($request->all());
        $trx = TrxPenilaian::find( $request->id );
        // nik_penilai_1
        if(!empty($trx->nik_penilai_1)){
            $trx->kes_rek_1 = $request->kesrek_1;
            $trx->update();
            $log = TrxPenilaianLog::find( $request->logid_1 );
            if(!empty($log)){
                $log->keterangan = $request->kesrek_1;
                $log->is_perpanjang = $request->isperpanjang_1;
                $log->bulan = $request->bulan_1;
                $log->update();
                // simpan history perubahan
                $data = New TrxPenilaianLogHistory;
                $data->id_trx = $log->id_trx;
                $data->id_status = $log->id_status;
                $data->nik_pic = $log->nik_pic;
                $data->keterangan = $request->kesrek_1;
                $data->is_perpanjang = $request->isperpanjang_1;
                $data->bulan = $request->bulan_1;
                $data->revised_by = auth()->user()->nik;
                $data->save();
            }
        }
        // nik_penilai_2
        if(!empty($trx->nik_penilai_2)){
            $trx->kes_rek_2 = $request->kesrek_2;
            $trx->update();
            $log = TrxPenilaianLog::find( $request->logid_2 );
            if(!empty($log)){
                $log->keterangan = $request->kesrek_2;
                $log->is_perpanjang = $request->isperpanjang_2;
                $log->bulan = $request->bulan_2;
                $log->update();
                // simpan history perubahan
                $data = New TrxPenilaianLogHistory;
                $data->id_trx = $log->id_trx;
                $data->id_status = $log->id_status;
                $data->nik_pic = $log->nik_pic;
                $data->keterangan = $request->kesrek_2;
                $data->is_perpanjang = $request->isperpanjang_2;
                $data->bulan = $request->bulan_2;
                $data->revised_by = auth()->user()->nik;
                $data->save();
            }
        }
        // nik_penilai_3
        if(!empty($trx->nik_penilai_3)){
            $trx->kes_rek_3 = $request->kesrek_3;
            $trx->update();
            $log = TrxPenilaianLog::find( $request->logid_3 );
            if(!empty($log)){
                $log->keterangan = $request->kesrek_3;
                $log->is_perpanjang = $request->isperpanjang_3;
                $log->bulan = $request->bulan_3;
                $log->update();
                // simpan history perubahan
                $data = New TrxPenilaianLogHistory;
                $data->id_trx = $log->id_trx;
                $data->id_status = $log->id_status;
                $data->nik_pic = $log->nik_pic;
                $data->keterangan = $request->kesrek_3;
                $data->is_perpanjang = $request->isperpanjang_3;
                $data->bulan = $request->bulan_3;
                $data->revised_by = auth()->user()->nik;
                $data->save();
            }
        }
        // hco
        if($trx->id_status==4 || $trx->id_status==8){
            $trx->kes_rek_final = $request->kesrek_hco;
            $trx->is_perpanjang = $request->isperpanjang_hco;
            if($request->isperpanjang_hco == 0){
                $trx->is_perpanjang = 2;
            }
            $trx->bulan = $request->bulan_hco;
            $trx->update();
            $log = TrxPenilaianLog::find( $request->logid_hco );
            if(!empty($log)){
                $log->keterangan = $request->kesrek_hco;
                $log->is_perpanjang = $request->isperpanjang_hco;
                $log->bulan = $request->bulan_hco;
                $log->update();
                // simpan history perubahan
                $data = New TrxPenilaianLogHistory;
                $data->id_trx = $log->id_trx;
                $data->id_status = $log->id_status;
                $data->nik_pic = $log->nik_pic;
                $data->keterangan = $request->kesrek_hco;
                $data->is_perpanjang = $request->isperpanjang_hco;
                $data->bulan = $request->bulan_hco;
                $data->revised_by = auth()->user()->nik;
                $data->save();
            }
        }
        return redirect( 'penilaian' )->with( 'msg', 'Data is saved successfully.' );
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

    public function deleteToDoList($where)
    {
        // $trx = ToDoList::where($where)->first();
        // if (!empty($trx)) {
        //     $trx->delete();
        // }
        $trx = ToDoList::where($where)->delete();
    }
}
