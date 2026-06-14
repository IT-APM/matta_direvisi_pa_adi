<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\KaryawanLog;
use App\Models\KaryawanPendidikan;
use App\Models\KaryawanPelatihan;
use App\Models\KaryawanSertifikasi;
use App\Models\TrxPelatihanSertifikat;
use App\Models\KaryawanAnak;
use App\Models\Kelamin;
use App\Models\Agama;
use App\Models\Warga;
use App\Models\Kawin;
use App\Models\Perusahaan;
use App\Models\Divisi;
use App\Models\Departemen;
use App\Models\SubDepartemen;
use App\Models\KaryawanStatus;
use App\Models\WorkingGroup;
use App\Models\Level;
use App\Models\Jabatan;
use App\Models\User;
use App\Models\UsersPwd;
use App\Models\MtrFindimProject;
use App\Models\MtrFindimDivisi;
use App\Models\MtrFindimDepartemen;
use App\Models\MtrFindimSubdepartemen;
use App\Models\KaryawanGrade;
use App\Models\TrxPenilaian;
use App\Models\PendidikanJenjang;
// 
use App\Models\MtrUtility;
use App\Mail\MattaRebornMail;
use Illuminate\Support\Facades\Mail;
// 
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
// 
use Illuminate\Http\Request;
use DB;
use DateTime;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class KaryawanController extends Controller
{
    // public function __construct(Gate $gate)
    public function __construct()
    {
        $this->middleware('can:hc', ['except' => ['show', 'get_divisi', 'get_departemen', 'get_sub_departemen', 'update_profile']]);
    }
    
    public function download_allphoto($id)
    {
        $karyawans     = Karyawan::where(['is_aktif' => 1])->get();
        $karyawans_non = Karyawan::where(['is_aktif' => 0])->get();

        // Setelah semua file di-copy, buat zip
        $zipFileName = 'foto_karyawan_' . date('Ymd_His') . '.zip';
        $tempDir     = public_path('temp');
        $zipPath     = $tempDir . DIRECTORY_SEPARATOR . $zipFileName;

        if (!is_dir($tempDir)) {
            shell_exec('md "' . $tempDir . '"');
        }

        $zip    = new \ZipArchive();
        $result = $zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        if ($result !== true) {
            return back()->with('error', 'Gagal membuat zip, error code: ' . $result);
        }

        // Buat folder dalam zip
        $zip->addEmptyDir('foto_karyawan');
        $zip->addEmptyDir('foto_karyawan/aktif');
        $zip->addEmptyDir('foto_karyawan/non aktif');

        $totalAdded = 0;

        // Karyawan aktif
        foreach ($karyawans as $karyawan) {
            $illegalChars = ['\\', '/', ':', '*', '?', '"', '<', '>', '|', '.', ','];
            $nama         = str_replace($illegalChars, '', $karyawan->nama);
            $nama         = trim(preg_replace('/\s+/', ' ', $nama));

            $sourcePath = public_path('assets/images/matta/karyawan/' . $karyawan->nik . '/' . $karyawan->nik . '.jpg');

            if (!file_exists($sourcePath)) {
                \Log::warning("File sumber tidak ditemukan: {$sourcePath}");
                continue;
            }

            $fileName = $karyawan->nik . ' ' . $nama . '.jpg';
            $zip->addFile($sourcePath, 'foto_karyawan/aktif/' . $fileName);
            $totalAdded++;
        }

        // Karyawan non aktif
        foreach ($karyawans_non as $karyawan) {
            $illegalChars = ['\\', '/', ':', '*', '?', '"', '<', '>', '|', '.', ','];
            $nama         = str_replace($illegalChars, '', $karyawan->nama);
            $nama         = trim(preg_replace('/\s+/', ' ', $nama));

            $sourcePath = public_path('assets/images/matta/karyawan/' . $karyawan->nik . '/' . $karyawan->nik . '.jpg');

            if (!file_exists($sourcePath)) {
                \Log::warning("File sumber tidak ditemukan: {$sourcePath}");
                continue;
            }

            $fileName = $karyawan->nik . ' ' . $nama . '.jpg';
            $zip->addFile($sourcePath, 'foto_karyawan/non aktif/' . $fileName);
            $totalAdded++;
        }

        $zip->close();

        if (!file_exists($zipPath)) {
            return back()->with('error', "File zip gagal dibuat. Total file: {$totalAdded}");
        }

        return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
    }
    
    public function upload_data($id)
    {
        if(Gate::allows('hc_nkp')){
            return redirect()->route('error-403');
        }
        return view('master.karyawan.upload_data');
    }
    
    public function upload_data_xlsx(Request $request, $id)
    {
        $jenis = $request->jenis;
        if($request->hasFile('template')){
            $request->validate([
                'template'=>'required|file|mimes:xls,xlsx|max:2048',
            ]);
            // upload data
            if($jenis == 'data_master_karyawan'){
                $path = $request->file('template')->getPathname();
                $trx = Excel::toArray([], $path);
                // data findim
                $data0 = $trx[0];
                // 100=NIP
                // 101=NO SUB DEPARTEMENT
                // 102=SUB DEPARTEMENT DESCRIPTION
                // 103=NO DEPARTEMENT
                // 104=DEPARTEMENT DESCRIPTION
                // 105=NO DIVISION
                // 106=DIVISION DESCRIPTION
                // 107=PROJECT ID
                // 108=PROJECT DESCRIPTION
                $_100 = array_search(100, $data0[0]);
                $_101 = array_search(101, $data0[0]);
                $_102 = array_search(102, $data0[0]);
                $_103 = array_search(103, $data0[0]);
                $_104 = array_search(104, $data0[0]);
                $_105 = array_search(105, $data0[0]);
                $_106 = array_search(106, $data0[0]);
                $_107 = array_search(107, $data0[0]);
                $_108 = array_search(108, $data0[0]);
                // dimulai dari baris ke-3
                for ($x = 2; $x < count($data0); $x++) {
                    // get karyawan
                    $where = ['nik' => $data0[$x][$_100]];
                    $karyawan = Karyawan::where($where)->first();
                    // get NO SUB DEPARTEMENT
                    $where = ['subdepartemen_id' => $data0[$x][$_101]];
                    $f_sub_dep = MtrFindimSubdepartemen::where($where)->first();
                    // check jika tidak ada maka insert
                    if(empty($f_sub_dep)){
                        $data = New MtrFindimSubdepartemen;
                        $data->subdepartemen_id = $data0[$x][$_101];
                        $data->subdepartemen_desc = $data0[$x][$_102];
                        $data->save();
                    }
                    // get NO SUB DEPARTEMENT
                    $where = ['subdepartemen_id' => $data0[$x][$_101]];
                    $f_sub_dep = MtrFindimSubdepartemen::where($where)->first();
                    // 
                    // get NO DEPARTEMENT
                    $where = ['departemen_id' => $data0[$x][$_103]];
                    $f_dep = MtrFindimDepartemen::where($where)->first();
                    // check jika tidak ada maka insert
                    if(empty($f_dep)){
                        $data = New MtrFindimDepartemen;
                        $data->departemen_id = $data0[$x][$_103];
                        $data->departemen_desc = $data0[$x][$_104];
                        $data->save();
                    }
                    // get NO DEPARTEMENT
                    $where = ['departemen_id' => $data0[$x][$_103]];
                    $f_dep = MtrFindimDepartemen::where($where)->first();
                    // 
                    // get NO DIVISION
                    $where = ['divisi_id' => $data0[$x][$_105]];
                    $f_divisi = MtrFindimDivisi::where($where)->first();
                    // check jika tidak ada maka insert
                    if(empty($f_divisi)){
                        $data = New MtrFindimDivisi;
                        $data->divisi_id = $data0[$x][$_105];
                        $data->divisi_desc = $data0[$x][$_106];
                        $data->save();
                    }
                    // get NO DIVISION
                    $where = ['divisi_id' => $data0[$x][$_105]];
                    $f_divisi = MtrFindimDivisi::where($where)->first();
                    // 
                    // get PROJECT ID
                    $where = ['project_id' => $data0[$x][$_107]];
                    $f_project = MtrFindimProject::where($where)->first();
                    // check jika tidak ada maka insert
                    if(empty($f_project)){
                        $data = New MtrFindimProject;
                        $data->project_id = $data0[$x][$_107];
                        $data->project_desc = $data0[$x][$_108];
                        $data->save();
                    }
                    // get PROJECT ID
                    $where = ['project_id' => $data0[$x][$_107]];
                    $f_project = MtrFindimProject::where($where)->first();
                    // update karyawan
                    if ($karyawan) {
                        $karyawan->findim_project = $f_project->id;
                        $karyawan->findim_divisi = $f_divisi->id;
                        $karyawan->findim_departemen = $f_dep->id;
                        $karyawan->findim_subdepartemen = $f_sub_dep->id;
                        $karyawan->update();
                    }
                }
                // data karyawan
                $data0 = $trx[1];
                // 1=NIP=nik
                // 2=Nomor Rekening=no_rek
                // 3=Nama Bank=nama_bank
                // 4=Rekening Atas Nama=nama_rek
                // 5=Nomor BPJS KS=no_bpjs
                // 6=Nomor BPJS TK=no_bpjstk
                // 7=Nomor Telepon Darurat=telepon_darurat
                // 8=Nomor NPWP=no_npwp
                // 9=Nomor KTP=no_ktp
                // 10=Alamat KTP=alamat_ktp
                // 11=Nama Pasangan=nama_pasangan
                // 12=Status Sipil=kode_kawin
                $nik = array_search(1, $data0[0]);
                $no_rek = array_search(2, $data0[0]);
                $nama_bank = array_search(3, $data0[0]);
                $nama_rek = array_search(4, $data0[0]);
                $no_bpjs = array_search(5, $data0[0]);
                $no_bpjstk = array_search(6, $data0[0]);
                $telepon_darurat = array_search(7, $data0[0]);
                $no_npwp = array_search(8, $data0[0]);
                $no_ktp = array_search(9, $data0[0]);
                $alamat_ktp = array_search(10, $data0[0]);
                $nama_pasangan = array_search(11, $data0[0]);
                $kode_kawin = array_search(12, $data0[0]);
                // dimulai dari baris ke-3
                for ($x = 2; $x < count($data0); $x++) {
                    $where = ['nik' => $data0[$x][$nik]];
                    $karyawan = Karyawan::where($where)->first();
                    if($karyawan){
                        if(!empty($data0[$x][$no_rek])){
                            $karyawan->no_rek = $data0[$x][$no_rek];
                        }
                        if(!empty($data0[$x][$nama_bank])){
                            $karyawan->nama_bank = $data0[$x][$nama_bank];
                        }
                        if(!empty($data0[$x][$nama_rek])){
                            $karyawan->nama_rek = $data0[$x][$nama_rek];
                        }
                        if(!empty($data0[$x][$no_bpjs])){
                            $karyawan->no_bpjs = $data0[$x][$no_bpjs];
                        }
                        if(!empty($data0[$x][$no_bpjstk])){
                            $karyawan->no_bpjstk = $data0[$x][$no_bpjstk];
                        }
                        if(!empty($data0[$x][$telepon_darurat])){
                            $karyawan->telepon_darurat = $data0[$x][$telepon_darurat];
                        }
                        if(!empty($data0[$x][$no_npwp])){
                            $karyawan->no_npwp = $data0[$x][$no_npwp];
                        }
                        if(!empty($data0[$x][$no_ktp])){
                            $karyawan->no_ktp = $data0[$x][$no_ktp];
                        }
                        if(!empty($data0[$x][$alamat_ktp])){
                            $karyawan->alamat_ktp = $data0[$x][$alamat_ktp];
                        }
                        if(!empty($data0[$x][$nama_pasangan])){
                            $karyawan->nama_pasangan = $data0[$x][$nama_pasangan];
                        }
                        if(!empty($data0[$x][$kode_kawin])){
                            $karyawan->kode_kawin = Kawin::where('status_kawin', $kode_kawin)->value('id');
                        }
                        $karyawan->update();
                    }
                }
            }
        }
        return redirect('karyawan/upload_data/0')->with('msg', 'Data is saved successfully.');
    }

    public function dashboard(Request $request, $id)
    {
        // initial
        $trx = [];
        // histori
        $jumlah_karyawan = [];
        $jumlah_karyawan_kelamin = [];
        $jumlah_karyawan_status = [];
        $jumlah_karyawan_level = [];
        $jumlah_karyawan_perusahaan = [];
        $jumlah_karyawan_divisi = [];
        $jumlah_karyawan_dep = [];
        $jumlah_karyawan_subdep = [];
        // now
        $jumlah_karyawan_kelamin_now = [];
        $jumlah_karyawan_status_now = [];
        $jumlah_karyawan_level_now = [];
        $jumlah_karyawan_pendidikan_now = [];
        $jumlah_karyawan_kelahiran_now = [];
        $jumlah_karyawan_perusahaan_now = [];
        // nkp
        if(Gate::allows('hc_nkp')){
            return redirect()->route('error-403');
        }
        $lable = ['Total Karyawan Aktif', 'Pria', 'Wanita', 'Karyawan Aktif (Jenis Kelamin)', 'Karyawan Aktif (Status)', 'Karyawan Aktif (Pendidikan)', 'Karyawan Aktif (Generasi)', 'Karyawan Aktif (Perusahaan)', 'Karyawan Aktif (Level)', 'Karyawan (Aktif/Join/Non Aktif)', 'Karyawan Aktif (Jenis Kelamin)', 'Karyawan Aktif (Status)', 'Karyawan Aktif (Level)', 'Karyawan Aktif (Perusahaan)', 'Karyawan Aktif (Direktorat)', 'Karyawan Aktif (Divisi)', 'Karyawan Aktif (Departemen)'];
        // filter parameter
        $tahun_dari = '0';
        $tahun_sampai = '0';
        $select_periode = '0';
        // 
        $bulan_tahun_dari = '';
        $bulan_tahun_sampai = '';
        $arr_select_aktif = array();
        $select_aktif = [
            ['id'=>1, 'data'=>'Aktif'],
            ['id'=>0, 'data'=>'Non Aktif'],
        ];
        $arr_select_kelamin = array();
        $arr_select_status = array();
        $arr_perusahaan = array();
        $arr_divisi = array();
        $arr_departemen = array();
        $arr_sub_departemen = array();
        $arr_select_level = array();
        $arr_select_pendidikan = array();
        $arr_select_generasi = array();
        $select_kelamin = Kelamin::whereIn('id', [2,3])->orderBy('id', 'asc')->get();
        $select_status = KaryawanStatus::orderBy('status_karyawan', 'asc')->get();
        $arr_not_in_nik = "'Hco.all','IT Infras','IT.BM'";
        // 0=NA
        // 3=SMS Project
        // 4=PT. Binayasa Karya Pratama
        $arr_not_in_perusahaan = "0,3,4";
        $perusahaan = Perusahaan::whereNotIn('id', explode(',', $arr_not_in_perusahaan))->orderBy('nama', 'asc')->get();
        $divisi = array();
        $departemen = array();
        $sub_departemen = array();
        $select_level = Level::orderBy('id', 'asc')->get();
        $select_pendidikan = PendidikanJenjang::whereNotIn('id', [11])->orderBy('id', 'asc')->get();
        $select_generasi = [
            ['id'=>1, 'min'=>1990, 'max'=>1946, 'data'=>'The Builders (<=1946)'],
            ['id'=>2, 'min'=>1946, 'max'=>1964, 'data'=>'Baby Boomers (1946< X <=1964)'],
            ['id'=>3, 'min'=>1964, 'max'=>1979, 'data'=>'Gen X (1964< X <=1979)'],
            ['id'=>4, 'min'=>1979, 'max'=>1994, 'data'=>'Gen Y (1979< X <=1994)'],
            ['id'=>5, 'min'=>1994, 'max'=>2009, 'data'=>'Gen Z (1994< X <=2009)'],
            ['id'=>6, 'min'=>2009, 'max'=>2024, 'data'=>'Gen Alpha (2009< X <=2024)'],
            ['id'=>7, 'min'=>2024, 'max'=>2050, 'data'=>'Gen Beta (2024< X)'],
        ];
        // start sync id_jenjang
        $trx = Karyawan::where(['is_aktif' => 1])->get();
        foreach($trx as $_trx){
            $data = Karyawan::find($_trx->id);
            // 11=Lainnya
            $check = KaryawanPendidikan::where(['nik' => $data->nik])->whereNotIn('id_jenjang', [11]);
            if($check->exists()){
                $data->id_jenjang = $check->orderBy( 'id_jenjang', 'desc' )->first()->id_jenjang;
                $data->update();
            }
        }
        // end
        // start filter
        if($request->filter == true && $id == 1){
            $bulan_tahun_dari = $request->bulan_tahun_dari;
            $bulan_tahun_sampai = $request->bulan_tahun_sampai;
            if(!empty($request->select_periode)){
                $tahun_dari = $request->tahun_dari;
                $tahun_sampai = $request->tahun_sampai;
                $select_periode = $request->select_periode;
            }
            $_select_kelamin = $request->input('select_kelamin', []);
            $_select_status = $request->input('select_status', []);
            $_perusahaan = $request->input('kode_perusahaan', []);
            $_divisi = $request->input('kode_divisi', []);
            $_departemen = $request->input('kode_departemen', []);
            $_sub_departemen = $request->input('kode_sub_departemen', []);
            $_select_level = $request->input('select_level', []);
            if(count($_select_kelamin) > 0){
                $arr_select_kelamin = $_select_kelamin;
            }
            else{
                $arr_select_kelamin = array();
            }
            if(count($_select_status) > 0){
                $arr_select_status = $_select_status;
            }
            else{
                $arr_select_status = array();
            }
            if(count($_perusahaan) > 0){
                $arr_perusahaan = $_perusahaan;
            }
            else{
                $arr_perusahaan = array();
            }
            if(count($_divisi) > 0){
                $arr_divisi = $_divisi;
            }
            else{
                $arr_divisi = array();
            }
            if(count($_departemen) > 0){
                $arr_departemen = $_departemen;
            }
            else{
                $arr_departemen = array();
            }
            if(count($_sub_departemen) > 0){
                $arr_sub_departemen = $_sub_departemen;
            }
            else{
                $arr_sub_departemen = array();
            }
            if(count($_select_level) > 0){
                $arr_select_level = $_select_level;
            }
            else{
                $arr_select_level = array();
            }
            $divisi = Divisi::whereIn('kode_perusahaan', $arr_perusahaan)->orderBy('nama', 'asc')->get();
            $departemen = Departemen::whereIn('kode_divisi', $arr_divisi)->orderBy('nama', 'asc')->get();
            $sub_departemen = SubDepartemen::whereIn('kode_departemen', $arr_departemen)->orderBy('nama', 'asc')->get();
        }else{
            // 
        }
        // end filter
        // all
        $where0 = "`nik` NOT IN (".$arr_not_in_nik.") AND `kode_perusahaan` NOT IN (".$arr_not_in_perusahaan.")";
        $where1 = "AND kode_kelamin IN (".implode(',', $arr_select_kelamin).") 
                AND kode_status IN (".implode(',', $arr_select_status).") 
                AND kode_perusahaan IN (".implode(',', $arr_perusahaan).") 
                AND kode_divisi IN (".implode(',', $arr_divisi).") 
                AND kode_departemen IN (".implode(',', $arr_departemen).") 
                AND kode_sub_departemen IN (".implode(',', $arr_sub_departemen).") 
                AND kode_level IN (".implode(',', $arr_select_level).")";
        // start
        if($id == 1){
            $trx = DB::select("SELECT 
                MIN(DATE(created_at)) AS tanggal_min,
                MAX(DATE(created_at)) AS tanggal_max,
                DATE_FORMAT(created_at, '%b %Y') AS label
                FROM mtr_tbl_karyawan_history
                WHERE ".$where0."
                AND YEAR(created_at) = YEAR(CURDATE())
                GROUP BY YEAR(created_at), MONTH(created_at)
                ORDER BY YEAR(created_at), MONTH(created_at)
            ");
            if($request->filter == true && empty($request->select_periode)){
                $trx = DB::select("SELECT 
                    MIN(DATE(created_at)) AS tanggal_min,
                    MAX(DATE(created_at)) AS tanggal_max,
                    DATE_FORMAT(created_at, '%b %Y') AS label
                    FROM mtr_tbl_karyawan_history
                    WHERE ".$where0."
                    AND DATE(`created_at`) BETWEEN DATE(CONCAT('".$bulan_tahun_dari."', '-01')) AND LAST_DAY(STR_TO_DATE('".$bulan_tahun_sampai."', '%Y-%m')) 
                    ".$where1."
                    GROUP BY YEAR(created_at), MONTH(created_at)
                    ORDER BY YEAR(created_at), MONTH(created_at)
                ");
            }elseif($request->filter == true && !empty($request->select_periode)){
                $trx = DB::select("SELECT 
                    MIN(DATE(created_at)) AS tanggal_min,
                    MAX(DATE(created_at)) AS tanggal_max,
                    DATE_FORMAT(created_at, '%b %Y') AS label
                    FROM mtr_tbl_karyawan_history
                    WHERE ".$where0."
                    AND YEAR(`created_at`) BETWEEN '".$tahun_dari."' AND '".$tahun_sampai."' AND MONTH(`created_at`) IN (".$select_periode.")
                    ".$where1."
                    GROUP BY YEAR(created_at), MONTH(created_at)
                    ORDER BY YEAR(created_at), MONTH(created_at)
                ");
            }
            // jumlah_karyawan
            foreach ($trx as $_trx) {
                $awal = $_trx->tanggal_min;
                $akhir = $_trx->tanggal_max;
                // aktif
                $aktif = DB::select("SELECT COUNT(*) AS jumlah FROM `mtr_tbl_karyawan_history` 
                WHERE ".$where0."
                AND DATE(`created_at`)='".$akhir."'")[0]->jumlah;
                $aktif_detail = DB::select("SELECT `nik`,`nama` FROM `mtr_tbl_karyawan_history` 
                WHERE ".$where0."
                AND DATE(`created_at`)='".$akhir."'");
                // non_aktif
                $non_aktif = DB::select("SELECT COUNT(*) AS jumlah FROM `mtr_tbl_karyawan` 
                WHERE ".$where0." 
                AND `tanggal_tidak_aktif` BETWEEN '".$awal."' AND '".$akhir."'")[0]->jumlah;
                $non_aktif_detail = DB::select("SELECT `nik`,`nama` FROM `mtr_tbl_karyawan` 
                WHERE ".$where0."
                AND `tanggal_tidak_aktif` BETWEEN '".$awal."' AND '".$akhir."'");
                // join
                $join = DB::select("SELECT COUNT(*) AS jumlah FROM `mtr_tbl_karyawan` 
                WHERE ".$where0." 
                AND `tanggal_bergabung` BETWEEN '".$awal."' AND '".$akhir."'")[0]->jumlah;
                $join_detail = DB::select("SELECT `nik`,`nama` FROM `mtr_tbl_karyawan` 
                WHERE ".$where0."
                AND `tanggal_bergabung` BETWEEN '".$awal."' AND '".$akhir."'");
                $jumlah_karyawan[$_trx->label] = [
                    'Aktif' => [
                        "jumlah" => $aktif,
                        "detail" => $aktif_detail,
                    ],
                    'Non Aktif' => [
                        "jumlah" => (int) $non_aktif,
                        "detail" => $non_aktif_detail,
                    ],
                    'Join' => [
                        "jumlah" => (int) $join,
                        "detail" => $join_detail,
                    ],
                ];
            }
            // jumlah_karyawan_kelamin
            foreach ($trx as $_trx) {
                $awal = $_trx->tanggal_min;
                $akhir = $_trx->tanggal_max;
                // 
                $data = DB::select("SELECT COUNT(*) AS jumlah,IFNULL(`kode_kelamin`, 1) AS `kode_kelamin` FROM `mtr_tbl_karyawan_history` 
                WHERE ".$where0." 
                AND DATE(`created_at`)='".$akhir."' 
                GROUP BY `kode_kelamin`");
                $labels = [
                    "1" => "NA",
                    "2" => "Pria",
                    "3" => "Wanita"
                ];
                foreach ($labels as $label) {
                    $jumlah_karyawan_kelamin[$_trx->label][$label] = [
                        "jumlah" => 0,
                        "detail" => []
                    ];
                }
                foreach ($data as $item) {
                    $key = $labels[$item->kode_kelamin];
                    $detail = DB::select("SELECT `nik`,`nama` FROM `mtr_tbl_karyawan_history` 
                    WHERE ".$where0." 
                    AND `kode_kelamin`=".$item->kode_kelamin."
                    AND DATE(`created_at`)='".$akhir."'");
                    $jumlah_karyawan_kelamin[$_trx->label][$key] = [
                        "jumlah" => (int) $item->jumlah,
                        "detail" => $detail
                    ];
                }
            }
            // jumlah_karyawan_status
            foreach ($trx as $_trx) {
                $awal = $_trx->tanggal_min;
                $akhir = $_trx->tanggal_max;
                // 
                $data = DB::select("SELECT COUNT(*) AS jumlah,`kode_status` FROM `mtr_tbl_karyawan_history` 
                WHERE ".$where0." 
                AND DATE(`created_at`)='".$akhir."' 
                GROUP BY `kode_status`");
                $_labels = DB::select("SELECT `id`,`status_karyawan` AS nama FROM `mtr_tbl_status_karyawan` ORDER BY `status_karyawan` ASC");
                $labels = [];
                foreach ($_labels as $item) {
                    $labels[$item->id] = $item->nama;
                }
                foreach ($labels as $label) {
                    $jumlah_karyawan_status[$_trx->label][$label] = [
                        "jumlah" => 0,
                        "detail" => []
                    ];
                }
                foreach ($data as $item) {
                    $key = $labels[$item->kode_status];
                    $detail = DB::select("SELECT `nik`,`nama` FROM `mtr_tbl_karyawan_history` 
                    WHERE ".$where0." 
                    AND `kode_status`=".$item->kode_status."
                    AND DATE(`created_at`)='".$akhir."'");
                    $jumlah_karyawan_status[$_trx->label][$key] = [
                        "jumlah" => (int) $item->jumlah,
                        "detail" => $detail
                    ];
                }
            }
            // jumlah_karyawan_level
            foreach ($trx as $_trx) {
                $awal = $_trx->tanggal_min;
                $akhir = $_trx->tanggal_max;
                // 
                $data = DB::select("SELECT COUNT(*) AS jumlah,`kode_level` FROM `mtr_tbl_karyawan_history` 
                WHERE ".$where0." 
                AND DATE(`created_at`)='".$akhir."' 
                GROUP BY `kode_level`");
                $_labels = DB::select("SELECT `id`,`level` AS nama FROM `mtr_tbl_level` WHERE `id` NOT IN (0) ORDER BY `id` ASC");
                $labels = [];
                foreach ($_labels as $item) {
                    $labels[$item->id] = $item->nama;
                }
                foreach ($labels as $label) {
                    $jumlah_karyawan_level[$_trx->label][$label] = [
                        "jumlah" => 0,
                        "detail" => []
                    ];
                }
                foreach ($data as $item) {
                    $key = $labels[$item->kode_level];
                    $detail = DB::select("SELECT `nik`,`nama` FROM `mtr_tbl_karyawan_history` 
                    WHERE ".$where0." 
                    AND `kode_level`=".$item->kode_level."
                    AND DATE(`created_at`)='".$akhir."'");
                    $jumlah_karyawan_level[$_trx->label][$key] = [
                        "jumlah" => (int) $item->jumlah,
                        "detail" => $detail
                    ];
                }
            }
            // jumlah_karyawan_perusahaan
            foreach ($trx as $_trx) {
                $awal = $_trx->tanggal_min;
                $akhir = $_trx->tanggal_max;
                // 
                $data = DB::select("SELECT COUNT(*) AS jumlah,`kode_perusahaan` FROM `mtr_tbl_karyawan_history` 
                WHERE ".$where0." 
                AND DATE(`created_at`)='".$akhir."' 
                GROUP BY `kode_perusahaan`");
                $_labels = DB::select("SELECT `id`,`nama` FROM `mtr_tbl_perusahaan` WHERE `id` NOT IN (".$arr_not_in_perusahaan.") ORDER BY `nama` ASC");
                $labels = [];
                foreach ($_labels as $item) {
                    $labels[$item->id] = $item->nama;
                }
                foreach ($labels as $label) {
                    $jumlah_karyawan_perusahaan[$_trx->label][$label] = [
                        "jumlah" => 0,
                        "detail" => []
                    ];
                }
                foreach ($data as $item) {
                    $key = $labels[$item->kode_perusahaan];
                    $detail = DB::select("SELECT `nik`,`nama` FROM `mtr_tbl_karyawan_history` 
                    WHERE ".$where0." 
                    AND `kode_perusahaan`=".$item->kode_perusahaan."
                    AND DATE(`created_at`)='".$akhir."'");
                    $jumlah_karyawan_perusahaan[$_trx->label][$key] = [
                        "jumlah" => (int) $item->jumlah,
                        "detail" => $detail
                    ];
                }
            }
            // jumlah_karyawan_divisi
            // foreach ($trx as $_trx) {
            //     $awal = $_trx->tanggal_min;
            //     $akhir = $_trx->tanggal_max;
            //     // 
            //     $data = DB::select("SELECT COUNT(*) AS jumlah,`kode_divisi` FROM `mtr_tbl_karyawan_history` 
            //     WHERE ".$where0." 
            //     AND DATE(`created_at`)='".$akhir."' 
            //     GROUP BY `kode_divisi`");
            //     $_labels = DB::select("SELECT a.`id`,a.`nama`,b.`nama_singkat` FROM `mtr_tbl_divisi` AS a LEFT JOIN `mtr_tbl_perusahaan` AS b ON a.`kode_perusahaan`=b.`id` WHERE a.`id` IN (".implode(',', array_column($data, 'kode_divisi')).") ORDER BY a.`nama` ASC");
            //     $labels = [];
            //     foreach ($_labels as $item) {
            //         $labels[$item->id] = $item->nama_singkat.'-'.$item->nama;
            //     }
            //     foreach ($labels as $label) {
            //         $jumlah_karyawan_divisi[$_trx->label][$label] = [
            //             "jumlah" => 0,
            //             "detail" => []
            //         ];
            //     }
            //     foreach ($data as $item) {
            //         $key = $labels[$item->kode_divisi];
            //         $detail = DB::select("SELECT `nik`,`nama` FROM `mtr_tbl_karyawan_history` 
            //         WHERE ".$where0." 
            //         AND `kode_divisi`=".$item->kode_divisi."
            //         AND DATE(`created_at`)='".$akhir."'");
            //         $jumlah_karyawan_divisi[$_trx->label][$key] = [
            //             "jumlah" => (int) $item->jumlah,
            //             "detail" => $detail
            //         ];
            //     }
            //     // return response()->json( [ 'jumlah_karyawan_divisi' => $jumlah_karyawan_divisi ], 200 );
            // }
        // jumlah_karyawan_dep
        // start
        // if($id == 1){
            // $trx = DB::select("SELECT COUNT(*) AS jumlah, DATE(a.`created_at`) AS tanggal, b.`nama` FROM `mtr_tbl_karyawan_history` AS a LEFT JOIN `mtr_tbl_departemen` AS b ON a.`kode_departemen`=b.`id` WHERE a.`nik` NOT IN (".$arr_not_in_nik.") AND a.kode_perusahaan NOT IN (".$arr_not_in_perusahaan.") GROUP BY DATE(a.`created_at`),a.`kode_departemen` ORDER BY tanggal ASC,b.`nama` ASC");
            // if($request->filter == true && $id == 1 && empty($request->select_periode)){
            //     $trx = DB::select("SELECT COUNT(*) AS jumlah, DATE(a.`created_at`) AS tanggal, b.`nama` FROM `mtr_tbl_karyawan_history` AS a LEFT JOIN `mtr_tbl_departemen` AS b ON a.`kode_departemen`=b.`id` WHERE a.`nik` NOT IN (".$arr_not_in_nik.") 
            //     AND DATE(a.`created_at`) BETWEEN DATE(CONCAT('".$bulan_tahun_dari."', '-01')) AND LAST_DAY(STR_TO_DATE('".$bulan_tahun_sampai."', '%Y-%m')) 
            //     AND a.kode_kelamin IN (".implode(',', $arr_select_kelamin).") 
            //     AND a.kode_status IN (".implode(',', $arr_select_status).") 
            //     AND a.kode_perusahaan IN (".implode(',', $arr_perusahaan).") 
            //     AND a.kode_divisi IN (".implode(',', $arr_divisi).") 
            //     AND a.kode_departemen IN (".implode(',', $arr_departemen).") 
            //     AND a.kode_sub_departemen IN (".implode(',', $arr_sub_departemen).") 
            //     AND a.kode_level IN (".implode(',', $arr_select_level).") 
            //     GROUP BY DATE(a.`created_at`),a.`kode_departemen` ORDER BY tanggal ASC,b.`nama` ASC");
            // }elseif($request->filter == true && $id == 1 && !empty($request->select_periode)){
            //     $trx = DB::select("SELECT COUNT(*) AS jumlah, DATE(a.`created_at`) AS tanggal, b.`nama` FROM `mtr_tbl_karyawan_history` AS a LEFT JOIN `mtr_tbl_departemen` AS b ON a.`kode_departemen`=b.`id` WHERE a.`nik` NOT IN (".$arr_not_in_nik.") 
            //     AND YEAR(a.`created_at`) BETWEEN '".$tahun_dari."' AND '".$tahun_sampai."' AND MONTH(a.`created_at`) IN (".$select_periode.")
            //     AND a.kode_kelamin IN (".implode(',', $arr_select_kelamin).") 
            //     AND a.kode_status IN (".implode(',', $arr_select_status).") 
            //     AND a.kode_perusahaan IN (".implode(',', $arr_perusahaan).") 
            //     AND a.kode_divisi IN (".implode(',', $arr_divisi).") 
            //     AND a.kode_departemen IN (".implode(',', $arr_departemen).") 
            //     AND a.kode_sub_departemen IN (".implode(',', $arr_sub_departemen).") 
            //     AND a.kode_level IN (".implode(',', $arr_select_level).") 
            //     GROUP BY DATE(a.`created_at`),a.`kode_departemen` ORDER BY tanggal ASC,b.`nama` ASC");
            // }
            // $data = collect($trx)->map(function($item){
            //     return (array) $item;
            // });
            // $jumlah_karyawan_dep = collect($data)->groupBy(function ($item) {
            //     return Carbon::parse($item['tanggal'])->format('M Y');
            // })->map(function ($bulanGroup) {
            //     return $bulanGroup
            //         ->groupBy('nama')
            //         ->mapWithKeys(function ($namaGroup, $nama) {
            //         return [
            //             $nama => (int) ceil($namaGroup->avg('jumlah'))
            //         ];
            //     });
            // });
            // // end
            // // jumlah_karyawan_subdep
            // // start
            // $trx = DB::select("SELECT COUNT(*) AS jumlah, DATE(a.`created_at`) AS tanggal, b.`nama` FROM `mtr_tbl_karyawan_history` AS a LEFT JOIN `mtr_tbl_sub_departemen` AS b ON a.`kode_sub_departemen`=b.`id` WHERE a.`nik` NOT IN (".$arr_not_in_nik.") AND a.kode_perusahaan NOT IN (".$arr_not_in_perusahaan.") GROUP BY DATE(a.`created_at`),a.`kode_sub_departemen` ORDER BY tanggal ASC,b.`nama` ASC");
            // if($request->filter == true && $id == 1 && empty($request->select_periode)){
            //     $trx = DB::select("SELECT COUNT(*) AS jumlah, DATE(a.`created_at`) AS tanggal, b.`nama` FROM `mtr_tbl_karyawan_history` AS a LEFT JOIN `mtr_tbl_sub_departemen` AS b ON a.`kode_sub_departemen`=b.`id` WHERE a.`nik` NOT IN (".$arr_not_in_nik.") 
            //     AND DATE(a.`created_at`) BETWEEN DATE(CONCAT('".$bulan_tahun_dari."', '-01')) AND LAST_DAY(STR_TO_DATE('".$bulan_tahun_sampai."', '%Y-%m')) 
            //     AND a.kode_kelamin IN (".implode(',', $arr_select_kelamin).") 
            //     AND a.kode_status IN (".implode(',', $arr_select_status).") 
            //     AND a.kode_perusahaan IN (".implode(',', $arr_perusahaan).") 
            //     AND a.kode_divisi IN (".implode(',', $arr_divisi).") 
            //     AND a.kode_departemen IN (".implode(',', $arr_departemen).") 
            //     AND a.kode_sub_departemen IN (".implode(',', $arr_sub_departemen).") 
            //     AND a.kode_level IN (".implode(',', $arr_select_level).") 
            //     GROUP BY DATE(a.`created_at`),a.`kode_sub_departemen` ORDER BY tanggal ASC,b.`nama` ASC");
            // }elseif($request->filter == true && $id == 1 && !empty($request->select_periode)){
            //     $trx = DB::select("SELECT COUNT(*) AS jumlah, DATE(a.`created_at`) AS tanggal, b.`nama` FROM `mtr_tbl_karyawan_history` AS a LEFT JOIN `mtr_tbl_sub_departemen` AS b ON a.`kode_sub_departemen`=b.`id` WHERE a.`nik` NOT IN (".$arr_not_in_nik.") 
            //     AND YEAR(a.`created_at`) BETWEEN '".$tahun_dari."' AND '".$tahun_sampai."' AND MONTH(a.`created_at`) IN (".$select_periode.")
            //     AND a.kode_kelamin IN (".implode(',', $arr_select_kelamin).") 
            //     AND a.kode_status IN (".implode(',', $arr_select_status).") 
            //     AND a.kode_perusahaan IN (".implode(',', $arr_perusahaan).") 
            //     AND a.kode_divisi IN (".implode(',', $arr_divisi).") 
            //     AND a.kode_departemen IN (".implode(',', $arr_departemen).") 
            //     AND a.kode_sub_departemen IN (".implode(',', $arr_sub_departemen).") 
            //     AND a.kode_level IN (".implode(',', $arr_select_level).") 
            //     GROUP BY DATE(a.`created_at`),a.`kode_sub_departemen` ORDER BY tanggal ASC,b.`nama` ASC");
            // }
            // $data = collect($trx)->map(function($item){
            //     return (array) $item;
            // });
            // $jumlah_karyawan_subdep = collect($data)->groupBy(function ($item) {
            //     return Carbon::parse($item['tanggal'])->format('M Y');
            // })->map(function ($bulanGroup) {
            //     return $bulanGroup
            //         ->groupBy('nama')
            //         ->mapWithKeys(function ($namaGroup, $nama) {
            //         return [
            //             $nama => (int) ceil($namaGroup->avg('jumlah'))
            //         ];
            //     });
            // });
        // }
        }
        // return response()->json( [ 'jumlah_karyawan_perusahaan' => $jumlah_karyawan_perusahaan, 'jumlah_karyawan_status' => $jumlah_karyawan_status, 'jumlah_karyawan_level' => $jumlah_karyawan_level ], 200 );
        // end
        // start now
        if($id == 0){
            // jumlah_karyawan_kelamin_now
            $trx = DB::select("SELECT COUNT(*) AS jumlah,IFNULL(`kode_kelamin`, 1) AS `kode_kelamin` FROM `mtr_tbl_karyawan` WHERE `is_aktif`=1 AND `nik` NOT IN (".$arr_not_in_nik.") AND kode_perusahaan NOT IN (".$arr_not_in_perusahaan.") GROUP BY `kode_kelamin`");
            $data = collect($trx)->map(function($item){
                return (array) $item;
            });
            $labels = [
                "1" => "NA",
                "2" => "Pria",
                "3" => "Wanita"
            ];
            foreach ($data as $item) {
                $detail = Karyawan::select('nik','nama')
                ->where(['is_aktif'=>1, 'kode_kelamin'=>$item['kode_kelamin']])
                ->whereNotIn('nik', explode(',', str_replace("'", "", $arr_not_in_nik)))
                ->whereNotIn('kode_perusahaan', explode(',', $arr_not_in_perusahaan))
                ->orderBy( 'nama', 'asc' )->get([
                    'nik as NIP',
                    'nama as Nama'
                ])->toArray();
                $key = $labels[$item['kode_kelamin']];
                $jumlah_karyawan_kelamin_now[$key] = [
                    "jumlah" => $item['jumlah'],
                    "detail" => $detail,
                ];
            }
            // jumlah_karyawan_status_now
            $trx = DB::select("SELECT COUNT(*) AS jumlah,b.`status_karyawan`,a.`kode_status` FROM `mtr_tbl_karyawan` AS a LEFT JOIN `mtr_tbl_status_karyawan` AS b ON a.`kode_status`=b.`id` WHERE a.`is_aktif`=1 AND a.`nik` NOT IN (".$arr_not_in_nik.") AND a.kode_perusahaan NOT IN (".$arr_not_in_perusahaan.") GROUP BY a.`kode_status` ORDER BY b.`status_karyawan`");
            $data = collect($trx)->map(function($item){
                return (array) $item;
            });
            foreach ($data as $item) {
                $detail = Karyawan::select('nik','nama')
                ->where(['is_aktif'=>1, 'kode_status'=>$item['kode_status']])
                ->whereNotIn('nik', explode(',', str_replace("'", "", $arr_not_in_nik)))
                ->whereNotIn('kode_perusahaan', explode(',', $arr_not_in_perusahaan))
                ->orderBy( 'nama', 'asc' )->get([
                    'nik as NIP',
                    'nama as Nama'
                ])->toArray();
                $jumlah_karyawan_status_now[$item['status_karyawan']] = [
                    "jumlah" => $item['jumlah'],
                    "detail" => $detail,
                ];
            }
            // jumlah_karyawan_level_now
            $trx = DB::select("SELECT COUNT(*) AS jumlah,b.`level`,a.`kode_level` FROM `mtr_tbl_karyawan` AS a LEFT JOIN `mtr_tbl_level` AS b ON a.`kode_level`=b.`id` WHERE a.`is_aktif`=1 AND a.`nik` NOT IN (".$arr_not_in_nik.") AND a.kode_perusahaan NOT IN (".$arr_not_in_perusahaan.") GROUP BY a.`kode_level` ORDER BY b.`level`");
            $data = collect($trx)->map(function($item){
                return (array) $item;
            });
            foreach ($data as $item) {
                $detail = Karyawan::select('nik','nama')
                ->where(['is_aktif'=>1, 'kode_level'=>$item['kode_level']])
                ->whereNotIn('nik', explode(',', str_replace("'", "", $arr_not_in_nik)))
                ->whereNotIn('kode_perusahaan', explode(',', $arr_not_in_perusahaan))
                ->orderBy( 'nama', 'asc' )->get([
                    'nik as NIP',
                    'nama as Nama'
                ])->toArray();
                $jumlah_karyawan_level_now[$item['level']] = [
                    "jumlah" => $item['jumlah'],
                    "detail" => $detail,
                ];
            }
            // jumlah_karyawan_perusahaan_now
            $trx = DB::select("SELECT COUNT(*) AS jumlah,b.`nama`,a.`kode_perusahaan` FROM `mtr_tbl_karyawan` AS a LEFT JOIN `mtr_tbl_perusahaan` AS b ON a.`kode_perusahaan`=b.`id` WHERE a.`is_aktif`=1 AND a.`nik` NOT IN (".$arr_not_in_nik.") AND a.kode_perusahaan NOT IN (".$arr_not_in_perusahaan.") GROUP BY a.`kode_perusahaan` ORDER BY b.`nama`");
            $data = collect($trx)->map(function($item){
                return (array) $item;
            });
            foreach ($data as $item) {
                $detail = Karyawan::select('nik','nama')
                ->where(['is_aktif'=>1, 'kode_perusahaan'=>$item['kode_perusahaan']])
                ->whereNotIn('nik', explode(',', str_replace("'", "", $arr_not_in_nik)))
                ->whereNotIn('kode_perusahaan', explode(',', $arr_not_in_perusahaan))
                ->orderBy( 'nama', 'asc' )->get([
                    'nik as NIP',
                    'nama as Nama'
                ])->toArray();
                $jumlah_karyawan_perusahaan_now[$item['nama']] = [
                    "jumlah" => $item['jumlah'],
                    "detail" => $detail,
                ];
            }
            // jumlah_karyawan_pendidikan_now
            $trx = DB::select("SELECT COUNT(*) AS jumlah,b.`jenjang`,a.`id_jenjang` FROM `mtr_tbl_karyawan` AS a LEFT JOIN `mtr_tbl_pendidikan_jenjang` AS b ON a.`id_jenjang`=b.`id` WHERE a.`is_aktif`=1 AND a.`nik` NOT IN (".$arr_not_in_nik.") AND a.kode_perusahaan NOT IN (".$arr_not_in_perusahaan.") GROUP BY a.`id_jenjang` ORDER BY a.`id_jenjang`");
            $data = collect($trx)->map(function($item){
                return (array) $item;
            });
            foreach ($data as $item) {
                $detail = Karyawan::select('nik','nama')
                ->where(['is_aktif'=>1, 'id_jenjang'=>$item['id_jenjang']])
                ->whereNotIn('nik', explode(',', str_replace("'", "", $arr_not_in_nik)))
                ->whereNotIn('kode_perusahaan', explode(',', $arr_not_in_perusahaan))
                ->orderBy( 'nama', 'asc' )->get([
                    'nik as NIP',
                    'nama as Nama'
                ])->toArray();
                $jumlah_karyawan_pendidikan_now[$item['jenjang']] = [
                    "jumlah" => $item['jumlah'],
                    "detail" => $detail,
                ];
            }
            // jumlah_karyawan_kelahiran_now
            $trx = DB::select("SELECT 
                CASE
                    WHEN YEAR(`tanggal_lahir`) > 1900 AND YEAR(`tanggal_lahir`) <= 1946 THEN 'The Builders'
                    WHEN YEAR(`tanggal_lahir`) > 1946 AND YEAR(`tanggal_lahir`) <= 1964 THEN 'Baby Boomers'
                    WHEN YEAR(`tanggal_lahir`) > 1964 AND YEAR(`tanggal_lahir`) <= 1979 THEN 'Gen X'
                    WHEN YEAR(`tanggal_lahir`) > 1979 AND YEAR(`tanggal_lahir`) <= 1994 THEN 'Gen Y'
                    WHEN YEAR(`tanggal_lahir`) > 1994 AND YEAR(`tanggal_lahir`) <= 2009 THEN 'Gen Z'
                    WHEN YEAR(`tanggal_lahir`) > 2009 AND YEAR(`tanggal_lahir`) <= 2024 THEN 'Gen Alpha'
                    WHEN YEAR(`tanggal_lahir`) > 2024 AND YEAR(`tanggal_lahir`) <= 2050 THEN 'Gen Beta'
                    -- ELSE 'Gen Beta'
                END AS kategori_kelahiran,
                CASE
                    WHEN YEAR(`tanggal_lahir`) > 1900 AND YEAR(`tanggal_lahir`) <= 1946 THEN 1900
                    WHEN YEAR(`tanggal_lahir`) > 1946 AND YEAR(`tanggal_lahir`) <= 1964 THEN 1946
                    WHEN YEAR(`tanggal_lahir`) > 1964 AND YEAR(`tanggal_lahir`) <= 1979 THEN 1964
                    WHEN YEAR(`tanggal_lahir`) > 1979 AND YEAR(`tanggal_lahir`) <= 1994 THEN 1979
                    WHEN YEAR(`tanggal_lahir`) > 1994 AND YEAR(`tanggal_lahir`) <= 2009 THEN 1994
                    WHEN YEAR(`tanggal_lahir`) > 2009 AND YEAR(`tanggal_lahir`) <= 2024 THEN 2009
                    WHEN YEAR(`tanggal_lahir`) > 2024 AND YEAR(`tanggal_lahir`) <= 2050 THEN 2024
                    -- ELSE 'Gen Beta'
                END AS tahun_min,
                CASE
                    WHEN YEAR(`tanggal_lahir`) > 1900 AND YEAR(`tanggal_lahir`) <= 1946 THEN 1946
                    WHEN YEAR(`tanggal_lahir`) > 1946 AND YEAR(`tanggal_lahir`) <= 1964 THEN 1964
                    WHEN YEAR(`tanggal_lahir`) > 1964 AND YEAR(`tanggal_lahir`) <= 1979 THEN 1979
                    WHEN YEAR(`tanggal_lahir`) > 1979 AND YEAR(`tanggal_lahir`) <= 1994 THEN 1994
                    WHEN YEAR(`tanggal_lahir`) > 1994 AND YEAR(`tanggal_lahir`) <= 2009 THEN 2009
                    WHEN YEAR(`tanggal_lahir`) > 2009 AND YEAR(`tanggal_lahir`) <= 2024 THEN 2024
                    WHEN YEAR(`tanggal_lahir`) > 2024 AND YEAR(`tanggal_lahir`) <= 2050 THEN 2050
                    -- ELSE 'Gen Beta'
                END AS tahun_max,
                COUNT(*) AS jumlah
            FROM `mtr_tbl_karyawan`
            WHERE `is_aktif` = 1
            AND `nik` NOT IN (".$arr_not_in_nik.")
            AND kode_perusahaan NOT IN (".$arr_not_in_perusahaan.") 
            AND `tanggal_lahir` IS NOT NULL
            -- AND YEAR(`tanggal_lahir`) <= YEAR(NOW())
            GROUP BY kategori_kelahiran ORDER BY YEAR(`tanggal_lahir`) ASC");
            $data = collect($trx)->map(function($item){
                return (array) $item;
            });
            foreach ($data as $item) {
                $detail = Karyawan::select('nik','nama')
                ->where(['is_aktif'=>1])
                ->whereYear('tanggal_lahir', '>', $item['tahun_min'])
                ->whereYear('tanggal_lahir', '<=', $item['tahun_max'])
                ->whereNotIn('nik', explode(',', str_replace("'", "", $arr_not_in_nik)))
                ->whereNotIn('kode_perusahaan', explode(',', $arr_not_in_perusahaan))
                ->orderBy( 'nama', 'asc' )->get([
                    'nik as NIP',
                    'nama as Nama'
                ])->toArray();
                $jumlah_karyawan_kelahiran_now[$item['kategori_kelahiran']] = [
                    "jumlah" => $item['jumlah'],
                    "detail" => $detail,
                ];
            }
            // start filter
            if($request->filter == true){
                // reset
                $jumlah_karyawan_kelamin_now = [];
                $jumlah_karyawan_status_now = [];
                $jumlah_karyawan_level_now = [];
                $jumlah_karyawan_pendidikan_now = [];
                $jumlah_karyawan_kelahiran_now = [];
                $jumlah_karyawan_perusahaan_now = [];
                // 
                $_select_kelamin = $request->input('select_kelamin', []);
                $_select_status = $request->input('select_status', []);
                $_perusahaan = $request->input('kode_perusahaan', []);
                $_divisi = $request->input('kode_divisi', []);
                $_departemen = $request->input('kode_departemen', []);
                $_sub_departemen = $request->input('kode_sub_departemen', []);
                $_select_level = $request->input('select_level', []);
                $_select_pendidikan = $request->input('select_pendidikan', []);
                $_select_generasi = $request->input('select_generasi', []);
                if(count($_select_kelamin) > 0){
                    $arr_select_kelamin = $_select_kelamin;
                }
                else{
                    $arr_select_kelamin = array();
                }
                if(count($_select_status) > 0){
                    $arr_select_status = $_select_status;
                }
                else{
                    $arr_select_status = array();
                }
                if(count($_perusahaan) > 0){
                    $arr_perusahaan = $_perusahaan;
                }
                else{
                    $arr_perusahaan = array();
                }
                if(count($_divisi) > 0){
                    $arr_divisi = $_divisi;
                }
                else{
                    $arr_divisi = array();
                }
                if(count($_departemen) > 0){
                    $arr_departemen = $_departemen;
                }
                else{
                    $arr_departemen = array();
                }
                if(count($_sub_departemen) > 0){
                    $arr_sub_departemen = $_sub_departemen;
                }
                else{
                    $arr_sub_departemen = array();
                }
                if(count($_select_level) > 0){
                    $arr_select_level = $_select_level;
                }
                else{
                    $arr_select_level = array();
                }
                if(count($_select_pendidikan) > 0){
                    $arr_select_pendidikan = $_select_pendidikan;
                }
                else{
                    $arr_select_pendidikan = array();
                }
                if(count($_select_generasi) > 0){
                    $arr_select_generasi = $_select_generasi;
                }
                else{
                    $arr_select_generasi = array();
                }
                $divisi = Divisi::whereIn('kode_perusahaan', $arr_perusahaan)->orderBy('nama', 'asc')->get();
                $departemen = Departemen::whereIn('kode_divisi', $arr_divisi)->orderBy('nama', 'asc')->get();
                $sub_departemen = SubDepartemen::whereIn('kode_departemen', $arr_departemen)->orderBy('nama', 'asc')->get();
                // $arr_select_generasi
                $arr_generasi = array_values(array_filter($select_generasi, function($item) use ($arr_select_generasi) {
                    return in_array($item['id'], $arr_select_generasi);
                }));
                // arr_generasi
                $where = [];
                $ranges  = [];
                foreach ($arr_generasi as $item) {
                    $min = $item['min'];
                    $max = $item['max'];
                    $ranges[] = ['min' => $min, 'max' => $max];
                    if (!empty($max)) {
                        $where[] = "(YEAR(tanggal_lahir) > $min AND YEAR(tanggal_lahir) <= $max)";
                    } else {
                        $where[] = "(YEAR(tanggal_lahir) > $min)";
                    }
                }
                $where_sql = implode(" OR ", $where);
                // jumlah_karyawan_kelamin_now
                $trx = DB::select("SELECT COUNT(*) AS jumlah,IFNULL(`kode_kelamin`, 1) AS `kode_kelamin` 
                FROM `mtr_tbl_karyawan` WHERE `is_aktif`=1 AND `nik` NOT IN (".$arr_not_in_nik.") 
                AND (".$where_sql.") 
                AND kode_kelamin IN (".implode(',', $arr_select_kelamin).") 
                AND kode_status IN (".implode(',', $arr_select_status).") 
                AND kode_level IN (".implode(',', $arr_select_level).") 
                AND kode_perusahaan IN (".implode(',', $arr_perusahaan).") 
                AND kode_divisi IN (".implode(',', $arr_divisi).") 
                AND kode_departemen IN (".implode(',', $arr_departemen).") 
                AND kode_sub_departemen IN (".implode(',', $arr_sub_departemen).") 
                GROUP BY `kode_kelamin`");
                $data = collect($trx)->map(function($item){
                    return (array) $item;
                });
                $labels = [
                    "1" => "NA",
                    "2" => "Pria",
                    "3" => "Wanita"
                ];
                foreach ($data as $item) {
                    $detail = Karyawan::select('nik','nama')
                    ->where(['is_aktif'=>1, 'kode_kelamin'=>$item['kode_kelamin']])
                    ->whereNotIn('nik', explode(',', str_replace("'", "", $arr_not_in_nik)))
                    ->whereNotIn('kode_perusahaan', explode(',', $arr_not_in_perusahaan))
                    ->whereIn('kode_kelamin', $arr_select_kelamin)
                    ->whereIn('kode_status', $arr_select_status)
                    ->whereIn('kode_level', $arr_select_level)
                    ->whereIn('kode_perusahaan', $arr_perusahaan)
                    ->whereIn('kode_divisi', $arr_divisi)
                    ->whereIn('kode_departemen', $arr_departemen)
                    ->whereIn('kode_sub_departemen', $arr_sub_departemen)
                    ->where(function($query) use ($ranges) {
                        foreach ($ranges as $range) {
                            $query->orWhere(function($q) use ($range) {
                                $q->whereYear('tanggal_lahir', '>', $range['min'])
                                ->whereYear('tanggal_lahir', '<=', $range['max']);
                            });
                        }
                    })
                    ->orderBy( 'nama', 'asc' )->get([
                        'nik as NIP',
                        'nama as Nama'
                    ])->toArray();
                    $key = $labels[$item['kode_kelamin']];
                    $jumlah_karyawan_kelamin_now[$key] = [
                        "jumlah" => $item['jumlah'],
                        "detail" => $detail,
                    ];
                }
                // jumlah_karyawan_status_now
                $trx = DB::select("SELECT COUNT(*) AS jumlah,b.`status_karyawan`,a.`kode_status` 
                FROM `mtr_tbl_karyawan` AS a LEFT JOIN `mtr_tbl_status_karyawan` AS b ON a.`kode_status`=b.`id` 
                WHERE a.`is_aktif`=1 AND a.`nik` NOT IN (".$arr_not_in_nik.") 
                AND (".$where_sql.") AND a.kode_kelamin IN (".implode(',', $arr_select_kelamin).") 
                AND a.kode_status IN (".implode(',', $arr_select_status).") 
                AND a.kode_level IN (".implode(',', $arr_select_level).") 
                AND a.kode_perusahaan IN (".implode(',', $arr_perusahaan).") 
                AND a.kode_divisi IN (".implode(',', $arr_divisi).") 
                AND a.kode_departemen IN (".implode(',', $arr_departemen).") 
                AND a.kode_sub_departemen IN (".implode(',', $arr_sub_departemen).") 
                GROUP BY a.`kode_status` ORDER BY b.`status_karyawan`");
                $data = collect($trx)->map(function($item){
                    return (array) $item;
                });
                foreach ($data as $item) {
                    $detail = Karyawan::select('nik','nama')
                    ->where(['is_aktif'=>1, 'kode_status'=>$item['kode_status']])
                    ->whereNotIn('nik', explode(',', str_replace("'", "", $arr_not_in_nik)))
                    ->whereNotIn('kode_perusahaan', explode(',', $arr_not_in_perusahaan))
                    ->whereIn('kode_kelamin', $arr_select_kelamin)
                    ->whereIn('kode_status', $arr_select_status)
                    ->whereIn('kode_level', $arr_select_level)
                    ->whereIn('kode_perusahaan', $arr_perusahaan)
                    ->whereIn('kode_divisi', $arr_divisi)
                    ->whereIn('kode_departemen', $arr_departemen)
                    ->whereIn('kode_sub_departemen', $arr_sub_departemen)
                    ->where(function($query) use ($ranges) {
                        foreach ($ranges as $range) {
                            $query->orWhere(function($q) use ($range) {
                                $q->whereYear('tanggal_lahir', '>', $range['min'])
                                ->whereYear('tanggal_lahir', '<=', $range['max']);
                            });
                        }
                    })
                    ->orderBy( 'nama', 'asc' )->get([
                        'nik as NIP',
                        'nama as Nama'
                    ])->toArray();
                    $jumlah_karyawan_status_now[$item['status_karyawan']] = [
                        "jumlah" => $item['jumlah'],
                        "detail" => $detail,
                    ];
                }
                // jumlah_karyawan_level_now
                $trx = DB::select("SELECT COUNT(*) AS jumlah,b.`level`,a.`kode_level` 
                FROM `mtr_tbl_karyawan` AS a LEFT JOIN `mtr_tbl_level` AS b ON a.`kode_level`=b.`id` 
                WHERE a.`is_aktif`=1 AND a.`nik` NOT IN (".$arr_not_in_nik.") 
                AND (".$where_sql.") AND a.kode_kelamin IN (".implode(',', $arr_select_kelamin).") 
                AND a.kode_status IN (".implode(',', $arr_select_status).") 
                AND a.kode_level IN (".implode(',', $arr_select_level).") 
                AND a.kode_perusahaan IN (".implode(',', $arr_perusahaan).") 
                AND a.kode_divisi IN (".implode(',', $arr_divisi).") 
                AND a.kode_departemen IN (".implode(',', $arr_departemen).") 
                AND a.kode_sub_departemen IN (".implode(',', $arr_sub_departemen).") 
                GROUP BY a.`kode_level` ORDER BY b.`level`");
                $data = collect($trx)->map(function($item){
                    return (array) $item;
                });
                foreach ($data as $item) {
                    $detail = Karyawan::select('nik','nama')
                    ->where(['is_aktif'=>1, 'kode_level'=>$item['kode_level']])
                    ->whereNotIn('nik', explode(',', str_replace("'", "", $arr_not_in_nik)))
                    ->whereNotIn('kode_perusahaan', explode(',', $arr_not_in_perusahaan))
                    ->whereIn('kode_kelamin', $arr_select_kelamin)
                    ->whereIn('kode_status', $arr_select_status)
                    ->whereIn('kode_level', $arr_select_level)
                    ->whereIn('kode_perusahaan', $arr_perusahaan)
                    ->whereIn('kode_divisi', $arr_divisi)
                    ->whereIn('kode_departemen', $arr_departemen)
                    ->whereIn('kode_sub_departemen', $arr_sub_departemen)
                    ->where(function($query) use ($ranges) {
                        foreach ($ranges as $range) {
                            $query->orWhere(function($q) use ($range) {
                                $q->whereYear('tanggal_lahir', '>', $range['min'])
                                ->whereYear('tanggal_lahir', '<=', $range['max']);
                            });
                        }
                    })
                    ->orderBy( 'nama', 'asc' )->get([
                        'nik as NIP',
                        'nama as Nama'
                    ])->toArray();
                    $jumlah_karyawan_level_now[$item['level']] = [
                        "jumlah" => $item['jumlah'],
                        "detail" => $detail,
                    ];
                }
                // jumlah_karyawan_perusahaan_now
                $trx = DB::select("SELECT COUNT(*) AS jumlah,b.`nama`,a.`kode_perusahaan` 
                FROM `mtr_tbl_karyawan` AS a LEFT JOIN `mtr_tbl_perusahaan` AS b ON a.`kode_perusahaan`=b.`id` 
                WHERE a.`is_aktif`=1 AND a.`nik` NOT IN (".$arr_not_in_nik.") 
                AND (".$where_sql.") AND a.kode_kelamin IN (".implode(',', $arr_select_kelamin).") 
                AND a.kode_status IN (".implode(',', $arr_select_status).") 
                AND a.kode_level IN (".implode(',', $arr_select_level).") 
                AND a.kode_perusahaan IN (".implode(',', $arr_perusahaan).") 
                AND a.kode_divisi IN (".implode(',', $arr_divisi).") 
                AND a.kode_departemen IN (".implode(',', $arr_departemen).") 
                AND a.kode_sub_departemen IN (".implode(',', $arr_sub_departemen).") 
                GROUP BY a.`kode_perusahaan` ORDER BY b.`nama`");
                $data = collect($trx)->map(function($item){
                    return (array) $item;
                });
                foreach ($data as $item) {
                    $detail = Karyawan::select('nik','nama')
                    ->where(['is_aktif'=>1, 'kode_perusahaan'=>$item['kode_perusahaan']])
                    ->whereNotIn('nik', explode(',', str_replace("'", "", $arr_not_in_nik)))
                    ->whereNotIn('kode_perusahaan', explode(',', $arr_not_in_perusahaan))
                    ->whereIn('kode_kelamin', $arr_select_kelamin)
                    ->whereIn('kode_status', $arr_select_status)
                    ->whereIn('kode_level', $arr_select_level)
                    ->whereIn('kode_perusahaan', $arr_perusahaan)
                    ->whereIn('kode_divisi', $arr_divisi)
                    ->whereIn('kode_departemen', $arr_departemen)
                    ->whereIn('kode_sub_departemen', $arr_sub_departemen)
                    ->where(function($query) use ($ranges) {
                        foreach ($ranges as $range) {
                            $query->orWhere(function($q) use ($range) {
                                $q->whereYear('tanggal_lahir', '>', $range['min'])
                                ->whereYear('tanggal_lahir', '<=', $range['max']);
                            });
                        }
                    })
                    ->orderBy( 'nama', 'asc' )->get([
                        'nik as NIP',
                        'nama as Nama'
                    ])->toArray();
                    $jumlah_karyawan_perusahaan_now[$item['nama']] = [
                        "jumlah" => $item['jumlah'],
                        "detail" => $detail,
                    ];
                }
                // jumlah_karyawan_pendidikan_now
                $trx = DB::select("SELECT COUNT(*) AS jumlah,b.`jenjang`,a.`id_jenjang` 
                FROM `mtr_tbl_karyawan` AS a LEFT JOIN `mtr_tbl_pendidikan_jenjang` AS b ON a.`id_jenjang`=b.`id` 
                WHERE a.`is_aktif`=1 AND a.`nik` NOT IN (".$arr_not_in_nik.") 
                AND a.kode_perusahaan NOT IN (".$arr_not_in_perusahaan.") 
                AND (".$where_sql.") AND a.kode_kelamin IN (".implode(',', $arr_select_kelamin).") 
                AND a.kode_status IN (".implode(',', $arr_select_status).") 
                AND a.kode_level IN (".implode(',', $arr_select_level).") 
                AND a.kode_perusahaan IN (".implode(',', $arr_perusahaan).") 
                AND a.kode_divisi IN (".implode(',', $arr_divisi).") 
                AND a.kode_departemen IN (".implode(',', $arr_departemen).") 
                AND a.kode_sub_departemen IN (".implode(',', $arr_sub_departemen).") 
                AND a.id_jenjang IN (".implode(',', $arr_select_pendidikan).") 
                GROUP BY a.`id_jenjang` ORDER BY a.`id_jenjang`");
                $data = collect($trx)->map(function($item){
                    return (array) $item;
                });
                foreach ($data as $item) {
                    $detail = Karyawan::select('nik','nama')
                    ->where(['is_aktif'=>1, 'id_jenjang'=>$item['id_jenjang']])
                    ->whereNotIn('nik', explode(',', str_replace("'", "", $arr_not_in_nik)))
                    ->whereNotIn('kode_perusahaan', explode(',', $arr_not_in_perusahaan))
                    ->whereIn('kode_kelamin', $arr_select_kelamin)
                    ->whereIn('kode_status', $arr_select_status)
                    ->whereIn('kode_level', $arr_select_level)
                    ->whereIn('kode_perusahaan', $arr_perusahaan)
                    ->whereIn('kode_divisi', $arr_divisi)
                    ->whereIn('kode_departemen', $arr_departemen)
                    ->whereIn('kode_sub_departemen', $arr_sub_departemen)
                    ->whereIn('id_jenjang', $arr_select_pendidikan)
                    ->where(function($query) use ($ranges) {
                        foreach ($ranges as $range) {
                            $query->orWhere(function($q) use ($range) {
                                $q->whereYear('tanggal_lahir', '>', $range['min'])
                                ->whereYear('tanggal_lahir', '<=', $range['max']);
                            });
                        }
                    })
                    ->orderBy( 'nama', 'asc' )->get([
                        'nik as NIP',
                        'nama as Nama'
                    ])->toArray();
                    $jumlah_karyawan_pendidikan_now[$item['jenjang']] = [
                        "jumlah" => $item['jumlah'],
                        "detail" => $detail,
                    ];
                }
                // jumlah_karyawan_kelahiran_now
                $trx = DB::select("SELECT 
                    CASE
                        WHEN YEAR(`tanggal_lahir`) > 1900 AND YEAR(`tanggal_lahir`) <= 1946 THEN 'The Builders'
                        WHEN YEAR(`tanggal_lahir`) > 1946 AND YEAR(`tanggal_lahir`) <= 1964 THEN 'Baby Boomers'
                        WHEN YEAR(`tanggal_lahir`) > 1964 AND YEAR(`tanggal_lahir`) <= 1979 THEN 'Gen X'
                        WHEN YEAR(`tanggal_lahir`) > 1979 AND YEAR(`tanggal_lahir`) <= 1994 THEN 'Gen Y'
                        WHEN YEAR(`tanggal_lahir`) > 1994 AND YEAR(`tanggal_lahir`) <= 2009 THEN 'Gen Z'
                        WHEN YEAR(`tanggal_lahir`) > 2009 AND YEAR(`tanggal_lahir`) <= 2024 THEN 'Gen Alpha'
                        WHEN YEAR(`tanggal_lahir`) > 2024 AND YEAR(`tanggal_lahir`) <= 2050 THEN 'Gen Beta'
                        -- ELSE 'Gen Beta'
                    END AS kategori_kelahiran,
                    CASE
                        WHEN YEAR(`tanggal_lahir`) > 1900 AND YEAR(`tanggal_lahir`) <= 1946 THEN 1900
                        WHEN YEAR(`tanggal_lahir`) > 1946 AND YEAR(`tanggal_lahir`) <= 1964 THEN 1946
                        WHEN YEAR(`tanggal_lahir`) > 1964 AND YEAR(`tanggal_lahir`) <= 1979 THEN 1964
                        WHEN YEAR(`tanggal_lahir`) > 1979 AND YEAR(`tanggal_lahir`) <= 1994 THEN 1979
                        WHEN YEAR(`tanggal_lahir`) > 1994 AND YEAR(`tanggal_lahir`) <= 2009 THEN 1994
                        WHEN YEAR(`tanggal_lahir`) > 2009 AND YEAR(`tanggal_lahir`) <= 2024 THEN 2009
                        WHEN YEAR(`tanggal_lahir`) > 2024 AND YEAR(`tanggal_lahir`) <= 2050 THEN 2024
                        -- ELSE 'Gen Beta'
                    END AS tahun_min,
                    CASE
                        WHEN YEAR(`tanggal_lahir`) > 1900 AND YEAR(`tanggal_lahir`) <= 1946 THEN 1946
                        WHEN YEAR(`tanggal_lahir`) > 1946 AND YEAR(`tanggal_lahir`) <= 1964 THEN 1964
                        WHEN YEAR(`tanggal_lahir`) > 1964 AND YEAR(`tanggal_lahir`) <= 1979 THEN 1979
                        WHEN YEAR(`tanggal_lahir`) > 1979 AND YEAR(`tanggal_lahir`) <= 1994 THEN 1994
                        WHEN YEAR(`tanggal_lahir`) > 1994 AND YEAR(`tanggal_lahir`) <= 2009 THEN 2009
                        WHEN YEAR(`tanggal_lahir`) > 2009 AND YEAR(`tanggal_lahir`) <= 2024 THEN 2024
                        WHEN YEAR(`tanggal_lahir`) > 2024 AND YEAR(`tanggal_lahir`) <= 2050 THEN 2050
                        -- ELSE 'Gen Beta'
                    END AS tahun_max,
                    COUNT(*) AS jumlah
                FROM `mtr_tbl_karyawan`
                WHERE `is_aktif` = 1
                AND `nik` NOT IN (".$arr_not_in_nik.")
                AND `tanggal_lahir` IS NOT NULL
                -- AND YEAR(`tanggal_lahir`) <= YEAR(NOW())
                AND (".$where_sql.")
                AND kode_kelamin IN (".implode(',', $arr_select_kelamin).") 
                AND kode_status IN (".implode(',', $arr_select_status).") 
                AND kode_level IN (".implode(',', $arr_select_level).") 
                AND kode_perusahaan IN (".implode(',', $arr_perusahaan).") 
                AND kode_divisi IN (".implode(',', $arr_divisi).") 
                AND kode_departemen IN (".implode(',', $arr_departemen).") 
                AND kode_sub_departemen IN (".implode(',', $arr_sub_departemen).") 
                GROUP BY kategori_kelahiran ORDER BY YEAR(`tanggal_lahir`) ASC");
                $data = collect($trx)->map(function($item){
                    return (array) $item;
                });
                $jumlah_karyawan_kelahiran_now = [];
                foreach ($data as $item) {
                    $detail = Karyawan::select('nik','nama')
                    ->where(['is_aktif'=>1])
                    ->whereYear('tanggal_lahir', '>', $item['tahun_min'])
                    ->whereYear('tanggal_lahir', '<=', $item['tahun_max'])
                    ->whereNotIn('nik', explode(',', str_replace("'", "", $arr_not_in_nik)))
                    ->whereNotIn('kode_perusahaan', explode(',', $arr_not_in_perusahaan))
                    ->whereIn('kode_kelamin', $arr_select_kelamin)
                    ->whereIn('kode_status', $arr_select_status)
                    ->whereIn('kode_level', $arr_select_level)
                    ->whereIn('kode_perusahaan', $arr_perusahaan)
                    ->whereIn('kode_divisi', $arr_divisi)
                    ->whereIn('kode_departemen', $arr_departemen)
                    ->whereIn('kode_sub_departemen', $arr_sub_departemen)
                    ->where(function($query) use ($ranges) {
                        foreach ($ranges as $range) {
                            $query->orWhere(function($q) use ($range) {
                                $q->whereYear('tanggal_lahir', '>', $range['min'])
                                ->whereYear('tanggal_lahir', '<=', $range['max']);
                            });
                        }
                    })
                    ->orderBy( 'nama', 'asc' )->get([
                        'nik as NIP',
                        'nama as Nama'
                    ])->toArray();
                    $jumlah_karyawan_kelahiran_now[$item['kategori_kelahiran']] = [
                        "jumlah" => $item['jumlah'],
                        "detail" => $detail,
                    ];
                }
            }else{
                // 
            }
            // end filter
        }
        return view('master.karyawan.dashboard', compact('lable', 'jumlah_karyawan', 'jumlah_karyawan_kelamin', 'jumlah_karyawan_perusahaan', 'jumlah_karyawan_divisi', 'jumlah_karyawan_dep', 'jumlah_karyawan_subdep', 'jumlah_karyawan_status', 'jumlah_karyawan_level', 'jumlah_karyawan_kelamin_now', 'jumlah_karyawan_status_now', 'jumlah_karyawan_level_now', 'jumlah_karyawan_pendidikan_now', 'jumlah_karyawan_kelahiran_now', 'jumlah_karyawan_perusahaan_now', 'select_kelamin', 'arr_select_kelamin', 'select_status', 'arr_select_status', 'perusahaan', 'divisi', 'departemen', 'sub_departemen', 'arr_perusahaan', 'arr_divisi', 'arr_departemen', 'arr_sub_departemen', 'select_level', 'arr_select_level', 'select_pendidikan', 'arr_select_pendidikan', 'select_generasi', 'arr_select_generasi', 'bulan_tahun_dari', 'bulan_tahun_sampai', 'id', 'select_aktif', 'arr_select_aktif', 'tahun_dari', 'tahun_sampai', 'select_periode'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->str == 'proper_karyawan'){
            $trx = Karyawan::all();
            foreach($trx as $_trx){
                $data = Karyawan::find($_trx->id);
                $data->nama = Str::title($_trx->nama);
                $data->tempat_lahir = Str::title($_trx->tempat_lahir);
                $data->alamat_domisili = Str::title($_trx->alamat_domisili);
                $data->alamat_ktp = Str::title($_trx->alamat_ktp);
                $data->alamat_npwp = Str::title($_trx->alamat_npwp);
                $data->nama_pasangan = Str::title($_trx->nama_pasangan);
                $data->update();
            }
        }
        elseif($request->str == 'proper_anak'){
            $trx = KaryawanAnak::all();
            foreach($trx as $_trx){
                $data = KaryawanAnak::find($_trx->id);
                $data->nama = Str::title($_trx->nama);
                $data->update();
            }
        }
        // $karyawan = Karyawan::all();
        $arr_nik_nkp = new Karyawan();
        $arr_nik_nkp = $arr_nik_nkp->getNikNkp();
        $no = 0;
        $karyawan = Karyawan::where('is_aktif', 1)->orderBy( 'nama', 'asc' )->get();
        if(Gate::allows('hc_nkp')){
            $karyawan = Karyawan::whereIn('nik',$arr_nik_nkp)->where('is_aktif', 1)->orderBy( 'nama', 'asc' )->get();
        }
        // $karyawan = Karyawan::orderBy( 'nama', 'asc' )->limit(100)->get();
        // $karyawan = Karyawan::orderBy( 'nama', 'asc' )->pluck('is_aktif','nik','nama','email');
        // $karyawan = DB::select('SELECT * FROM mtr_tbl_karyawan ORDER BY nama');
        // filter parameter
        $arr_is_aktif = [ ['id'=>1,'is_aktif'=>'Aktif'], ['id'=>0,'is_aktif'=>'Non Aktif'] ];
        // return response()->json( [ 'success' => true, 'arr_is_aktif' => $arr_is_aktif ], 200 );
        $arr_kode_status = array();
        $arr_wg_id = array();
        $arr_kode_perusahaan = array();
        $arr_kode_level = array();
        // foreach ($karyawan as $data){
        //     array_push($arr_wg_id, $data->wg_id);
        //     array_push($arr_kode_perusahaan, $data->kode_perusahaan);
        // }
        $is_aktif = $arr_is_aktif;
        $kode_status = KaryawanStatus::orderBy('id', 'asc')->get();
        $kode_level = Level::orderBy('id', 'asc')->get();
        $wg_id = WorkingGroup::orderBy('id', 'asc')->get();
        $kode_perusahaan = Perusahaan::orderBy('id', 'asc')->get();
        // $wg_id = WorkingGroup::whereIn('id', $arr_wg_id)->orderBy('id', 'asc')->get();
        // $kode_perusahaan = Perusahaan::whereIn('id', $arr_kode_perusahaan)->orderBy('id', 'asc')->get();
        $nama = '';
        $layer1 = '';
        $layer2 = '';
        $layer3 = '';
        // start filter
        if($request->filter == true){
            $nama = $request->nama;
            $layer1 = $request->layer1;
            $layer2 = $request->layer2;
            $layer3 = $request->layer3;
            $_is_aktif = $request->input('is_aktif', []);
            $_kode_status = $request->input('kode_status', []);
            $_kode_level = $request->input('kode_level', []);
            $_wg_id = $request->input('wg_id', []);
            $_kode_perusahaan = $request->input('kode_perusahaan', []);
            if(count($_is_aktif) > 0){
                $arr_is_aktif = $_is_aktif;
            }
            else{
                $arr_is_aktif = array();
            }
            if(count($_kode_status) > 0){
                $arr_kode_status = $_kode_status;
            }
            else{
                $arr_kode_status = array();
            }
            if(count($_kode_level) > 0){
                $arr_kode_level = $_kode_level;
            }
            else{
                $arr_kode_level = array();
            }
            if(count($_wg_id) > 0){
                $arr_wg_id = $_wg_id;
            }
            else{
                $arr_wg_id = array();
            }
            if(count($_kode_perusahaan) > 0){
                $arr_kode_perusahaan = $_kode_perusahaan;
            }
            else{
                $arr_kode_perusahaan = array();
            }
            $_nama = Karyawan::select('nik')->where('nama','LIKE','%'.$nama.'%')->get();
            $_layer1 = Karyawan::select('nik')->where('nama','LIKE','%'.$layer1.'%')->get();
            $_layer2 = Karyawan::select('nik')->where('nama','LIKE','%'.$layer2.'%')->get();
            $_layer3 = Karyawan::select('nik')->where('nama','LIKE','%'.$layer3.'%')->get();
            $karyawan = Karyawan::whereIn('nik', $_nama)->whereIn('layer1', $_layer1)->whereIn('layer2', $_layer2)->whereIn('layer3', $_layer3)->whereIn('is_aktif', $arr_is_aktif)->whereIn('kode_status', $arr_kode_status)->whereIn('wg_id', $arr_wg_id)->whereIn('kode_perusahaan', $arr_kode_perusahaan)->whereIn('kode_level', $arr_kode_level)->orderBy( 'nama', 'asc' )->get();
            if(Gate::allows('hc_nkp')){
                $karyawan = Karyawan::whereIn('nik',$arr_nik_nkp)->whereIn('nik', $_nama)->whereIn('layer1', $_layer1)->whereIn('layer2', $_layer2)->whereIn('layer3', $_layer3)->whereIn('is_aktif', $arr_is_aktif)->whereIn('kode_status', $arr_kode_status)->whereIn('wg_id', $arr_wg_id)->whereIn('kode_perusahaan', $arr_kode_perusahaan)->whereIn('kode_level', $arr_kode_level)->orderBy( 'nama', 'asc' )->get();
            }
        }else{
            $arr_is_aktif = array();
            $arr_kode_status = array();
            $arr_wg_id = array();
            $arr_kode_perusahaan = array();
            $arr_kode_level = array();
        }
        // end filter
        return view('master.karyawan.index', compact('karyawan', 'no', 'is_aktif', 'kode_status', 'wg_id', 'kode_perusahaan', 'kode_level', 'arr_is_aktif', 'arr_kode_status', 'arr_wg_id', 'arr_kode_perusahaan', 'arr_kode_level', 'nama', 'layer1', 'layer2', 'layer3'));
        // return view( 'master.karyawan.index', compact( 'karyawan', 'no' ) );
    }

    public static function getLayer1( $nik ) {
        // $layer1 = Karyawan::where('nik', $nik)->first();
        // $layer1 = Karyawan::select("nama")->where('nik', $nik)->get();
        $layer1 = Karyawan::select("nama")->where('nik', $nik)->first();
        $nama = json_decode($layer1, true);
        if(isset($nama)){
            // info($nama['nama']);
            return $nama['nama'];
        }else{
            // info($nama);
            return "-";
        }
    }

    public static function getLayer2( $nik ) {
        $layer1 = Karyawan::select("nama")->where('nik', $nik)->first();
        $nama = json_decode($layer1, true);
        if(isset($nama)){
            // info($nama['nama']);
            return $nama['nama'];
        }else{
            // info($nama);
            return "-";
        }
    }

    public static function getLayer3( $nik ) {
        $layer1 = Karyawan::select("nama")->where('nik', $nik)->first();
        $nama = json_decode($layer1, true);
        if(isset($nama)){
            // info($nama['nama']);
            return $nama['nama'];
        }else{
            // info($nama);
            return "-";
        }
    }

    public function get_divisi($id) {
        $data = Divisi::where(['kode_perusahaan'=>$id, 'is_active'=>1])->orderBy( 'nama', 'asc' )->get();
        return json_decode($data, true);
    }

    public function get_departemen($id) {
        $data = Departemen::where(['kode_divisi'=>$id, 'is_active'=>1])->orderBy( 'nama', 'asc' )->get();
        return json_decode($data, true);
    }

    public function get_sub_departemen($id) {
        $data = SubDepartemen::where(['kode_departemen'=>$id, 'is_active'=>1])->orderBy( 'nama', 'asc' )->get();
        return json_decode($data, true);
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
        $karyawan = Karyawan::find( 0 );
        $layer = Karyawan::select('nik','nama')->where('is_aktif', 1)->get();
        // $kelamin = Kelamin::all();
        $kelamin = Kelamin::whereIn('id', [2,3])->get();
        $agama = Agama::all();
        $warga = Warga::all();
        $kawin = Kawin::all();
        // $perusahaan = Perusahaan::all();
        // $divisi = Divisi::all();
        // $dep = Departemen::all();
        // $sub_dep = SubDepartemen::all();
        // $perusahaan = perusahaan::where('is_active', 1)->get();
        $perusahaan = perusahaan::where('is_active', 1)->get();
        if(Gate::allows('hc_nkp')){
            $perusahaan = perusahaan::whereIn('id', [5])->where('is_active', 1)->get();
        }
        $divisi = Divisi::where('is_active', 1)->get();
        $dep = Departemen::where('is_active', 1)->get();
        $sub_dep = SubDepartemen::where('is_active', 1)->get();
        $kar_status = KaryawanStatus::all();
        $wg = WorkingGroup::all();
        $is_aktif = [['id'=>1, 'status'=>'Aktif'], ['id'=>0, 'status'=>'Non Aktif']];
        $alamat_kantor = [['key'=>'HQ', 'value'=>'HQ'], ['key'=>'Region', 'value'=>'Region']];
        $level = Level::all();
        $jabatan = Jabatan::orderBy( 'jabatan', 'asc' )->get();
        $findim_project = MtrFindimProject::orderBy( 'project_id', 'asc' )->get();
        $findim_divisi = MtrFindimDivisi::orderBy( 'divisi_id', 'asc' )->get();
        $findim_departemen = MtrFindimDepartemen::orderBy( 'departemen_id', 'asc' )->get();
        $findim_subdepartemen = MtrFindimSubdepartemen::orderBy( 'subdepartemen_id', 'asc' )->get();
        // $findim_project = MtrFindimProject::all();
        // $findim_divisi = MtrFindimDivisi::all();
        // $findim_departemen = MtrFindimDepartemen::all();
        // $findim_subdepartemen = MtrFindimSubdepartemen::all();
        $id_grade = KaryawanGrade::all();
        return view( 'master.karyawan.profile-tab', compact( 'status', 'karyawan', 'layer', 'kelamin','agama', 'warga', 'kawin', 'perusahaan', 'divisi', 'dep', 'sub_dep', 'kar_status', 'wg', 'is_aktif', 'level', 'jabatan', 'alamat_kantor', 'findim_project', 'findim_divisi', 'findim_departemen', 'findim_subdepartemen', 'id_grade' ) );
    }

    public function ImageUploadStore(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg|max:2048',
        ]);
        $nik = $request->nik;
        $imageName = $nik.'.'.$request->image->extension();
        $request->image->move(public_path('assets/images/matta/karyawan/'.$nik), $imageName);
        // return response()->json( [ 'data' => public_path('images'), 'imageName' => $imageName, 'imageName' => $imageName ], 200 );
        return back()->with('success','You have successfully upload image.')->with('image',$imageName); 
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
        // return response()->json( [ 'success' => true, 'message' => 'Data successfully viewed', 'data' => $request->all() ], 200 );
        $karyawan = New Karyawan;
        $password = Hash::make($request->nik);
        $karyawan->nik = $request->nik;
        $karyawan->password = $password;
        $karyawan->nama = $request->nama;
        $karyawan->tempat_lahir = Str::title($request->tempat_lahir);
        $karyawan->tanggal_lahir = $request->tanggal_lahir;
        $karyawan->no_ktp = $request->no_ktp;
        $karyawan->no_npwp = $request->no_npwp;
        $karyawan->no_bpjs = $request->no_bpjs;
        $karyawan->no_bpjstk = $request->no_bpjstk;
        $karyawan->telepon_darurat = $request->telepon_darurat;
        $karyawan->alamat_domisili = Str::title($request->alamat_domisili);
        $karyawan->alamat_ktp = Str::title($request->alamat_ktp);
        $karyawan->alamat_npwp = Str::title($request->alamat_npwp);
        $karyawan->telepon1 = $request->telepon1;
        $karyawan->telepon2 = $request->telepon2;
        $karyawan->telepon3 = $request->telepon3;
        $karyawan->nama_pasangan = $request->nama_pasangan;
        $karyawan->nama_anak = $request->nama_anak;
        $karyawan->email = $request->email;
        $karyawan->kode_perusahaan = $request->kode_perusahaan;
        $karyawan->alamat_kantor = $request->alamat_kantor;
        $karyawan->kode_divisi = $request->kode_divisi;
        $karyawan->kode_departemen = $request->kode_departemen;
        $karyawan->kode_sub_departemen = $request->kode_sub_departemen;
        $karyawan->layer1 = $request->layer1;
        $karyawan->layer2 = $request->layer2;
        $karyawan->layer3 = $request->layer3;
        $karyawan->kode_status = $request->kode_status;
        $karyawan->wg_id = $request->wg_id;
        $karyawan->is_aktif = $request->is_aktif;
        $karyawan->gaji_pokok = $request->gaji_pokok;
        $karyawan->kode_level = $request->kode_level;
        $karyawan->kode_jabatan = $request->kode_jabatan;
        $karyawan->tanggal_mulai = $request->tanggal_mulai;
        $karyawan->tanggal_bergabung = $request->tanggal_bergabung;
        $karyawan->tanggal_berakhir = $request->tanggal_berakhir;
        $karyawan->tanggal_karyawan_tetap = $request->tanggal_karyawan_tetap;
        $karyawan->tanggal_tidak_aktif = $request->tanggal_tidak_aktif;
        $karyawan->kode_agama = $request->kode_agama;
        $karyawan->kode_kelamin = $request->kode_kelamin;
        $karyawan->kode_warga = $request->kode_warga;
        $karyawan->kode_kawin = $request->kode_kawin;
        $karyawan->no_rek = $request->no_rek;
        $karyawan->nama_bank = $request->nama_bank;
        $karyawan->nama_rek = $request->nama_rek;
        $karyawan->findim_project = $request->findim_project;
        $karyawan->findim_divisi = $request->findim_divisi;
        $karyawan->findim_departemen = $request->findim_departemen;
        $karyawan->findim_subdepartemen = $request->findim_subdepartemen;
        $karyawan->id_grade = $request->id_grade;
        $karyawan->created_by = auth()->user()->nik;
        $karyawan->save();
        // 
        $user = New User;
        $user->name = $karyawan->nama;
        $user->nik = $karyawan->nik;
        $user->email = $karyawan->email;
        $user->userrole = 'nhc';
        $user->password = $password;
        $user->save();
        // 
        $user = New UsersPwd;
        $user->nik = $karyawan->nik;
        $user->password = $password;
        $user->save();
        // 
        return redirect( 'karyawan' )->with( 'msg', 'Data is saved successfully.' );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // dd($id);
        $id = (new OpenDataController)->decryptWithKey($id);
        if($id != auth()->user()->nik){
            return redirect()->route('error-403');
        }
        $karyawan = Karyawan::where('nik', $id)->first();
        $kelamin = Kelamin::whereIn('id', [2,3])->get();
        $agama = Agama::all();
        $warga = Warga::all();
        $kawin = Kawin::all();
        // 
        $where = ['nik' => $id];
        $pendidikan = KaryawanPendidikan::where($where)->orderBy( 'id_jenjang', 'asc' )->get();
        $no_pendidikan = 0;
        $pelatihan = KaryawanPelatihan::where($where)->orderBy( 'nama', 'asc' )->get();
        $no_pelatihan = 0;
        $sertifikasi = KaryawanSertifikasi::where($where)->orderBy( 'nama', 'asc' )->get();
        $no_sertifikasi = 0;
        $anak = KaryawanAnak::where($where)->orderBy( 'id_anakke', 'asc' )->get();
        $no_anak = 0;
        // return response()->json( [ 'karyawan' => $karyawan ] );
        return view( 'master.karyawan.profile', compact( 'karyawan', 'kelamin', 'agama', 'warga', 'kawin', 'pendidikan', 'no_pendidikan', 'pelatihan', 'no_pelatihan', 'sertifikasi', 'no_sertifikasi', 'anak', 'no_anak' ) );
    }

    public static function showKaryawan($id)
    {
        //
        $karyawan = Karyawan::where('nik', $id)->first();
        return $karyawan;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $status = 'edit';
        $karyawan = Karyawan::find($id);
        $layer = Karyawan::all();
        // $kelamin = Kelamin::all();
        $kelamin = Kelamin::whereIn('id', [2,3])->get();
        $agama = Agama::all();
        $warga = Warga::all();
        $kawin = Kawin::all();
        $findim_project = MtrFindimProject::orderBy( 'project_id', 'asc' )->get();
        $findim_divisi = MtrFindimDivisi::orderBy( 'divisi_id', 'asc' )->get();
        $findim_departemen = MtrFindimDepartemen::orderBy( 'departemen_id', 'asc' )->get();
        $findim_subdepartemen = MtrFindimSubdepartemen::orderBy( 'subdepartemen_id', 'asc' )->get();
        // $findim_project = MtrFindimProject::all();
        // $findim_divisi = MtrFindimDivisi::all();
        // $findim_departemen = MtrFindimDepartemen::all();
        // $findim_subdepartemen = MtrFindimSubdepartemen::all();
        $id_grade = KaryawanGrade::all();
        // $perusahaan = Perusahaan::all();
        // $divisi = Divisi::where('kode_perusahaan', $karyawan->kode_perusahaan)->get();
        // $dep = Departemen::where('kode_divisi', $karyawan->kode_divisi)->get();
        // $sub_dep = SubDepartemen::where('kode_departemen', $karyawan->kode_departemen)->get();
        // $perusahaan = perusahaan::where('is_active', 1)->get();
        $perusahaan = perusahaan::where('is_active', 1)->get();
        if(Gate::allows('hc_nkp')){
            $perusahaan = perusahaan::whereIn('id', [5])->where('is_active', 1)->get();
        }
        $divisi = Divisi::where(['kode_perusahaan'=>$karyawan->kode_perusahaan, 'is_active'=>1])->get();
        $dep = Departemen::where(['kode_divisi'=>$karyawan->kode_divisi, 'is_active'=>1])->get();
        $sub_dep = SubDepartemen::where(['kode_departemen'=>$karyawan->kode_departemen, 'is_active'=>1])->get();
        $kar_status = KaryawanStatus::all();
        $wg = WorkingGroup::all();
        $is_aktif = [['id'=>1, 'status'=>'Aktif'], ['id'=>0, 'status'=>'Non Aktif']];
        $alamat_kantor = [['key'=>'HQ', 'value'=>'HQ'], ['key'=>'Region', 'value'=>'Region']];
        $level = Level::all();
        $jabatan = Jabatan::orderBy( 'jabatan', 'asc' )->get();
        $where = ['nik' => $karyawan->nik];
        $pendidikan = KaryawanPendidikan::where($where)->orderBy( 'id_jenjang', 'asc' )->get();
        $no_pendidikan = 0;
        // $pelatihan = KaryawanPelatihan::where($where)->orderBy( 'nama', 'asc' )->get();
        // $no_pelatihan = 0;
        // $sertifikasi = KaryawanSertifikasi::where($where)->orderBy( 'nama', 'asc' )->get();
        // $no_sertifikasi = 0;
        $pelatihan = TrxPelatihanSertifikat::where(['nik'=>$karyawan->nik, 'jenis'=>'Pelatihan / Training'])->orderBy('judul', 'asc')->get();
        $sertifikasi = TrxPelatihanSertifikat::where(['nik'=>$karyawan->nik, 'jenis'=>'Sertifikasi'])->orderBy('judul', 'asc')->get();
        $seminar = TrxPelatihanSertifikat::where(['nik'=>$karyawan->nik, 'jenis'=>'Seminar / Workshop'])->orderBy('judul', 'asc')->get();
        // 
        $anak = KaryawanAnak::where($where)->orderBy( 'id_anakke', 'asc' )->get();
        $no_anak = 0;
        $history = KaryawanLog::where($where)->orderBy( 'id', 'desc' )->get();
        $no_history = 0;
        // $lbl_history = ["tanggal_lahir"=>"Tanggal Lahir","tempat_lahir"=>"Tempat Lahir","alamat_domisili"=>"Alamat Domisili","alamat_ktp"=>"Alamat KTP","alamat_npwp"=>"Alamat NPWP","telepon1"=>"Telepon 1","telepon2"=>"Telepon 2","telepon3"=>"Telepon 3","no_ktp"=>"NIK KTP","no_npwp"=>"NPWP","no_bpjs"=>"No BPJS Kesehatan","no_bpjstk"=>"No BPJS Ketenagakerjaan","nama_pasangan"=>"Nama Pasangan","kode_kelamin"=>"Jenis Kelamin","kode_agama"=>"Agama","kode_kawin"=>"Status Kawin","kode_warga"=>"Kewarganegaraan"];
        $lbl_history = ["tanggal_lahir"=>"Tanggal Lahir","tempat_lahir"=>"Tempat Lahir","alamat_domisili"=>"Alamat Domisili","alamat_ktp"=>"Alamat KTP","alamat_npwp"=>"Alamat NPWP","telepon1"=>"Telepon 1","telepon2"=>"Telepon 2","telepon3"=>"Telepon 3","no_ktp"=>"NIK KTP","no_npwp"=>"NPWP","no_bpjs"=>"No BPJS Kesehatan","no_bpjstk"=>"No BPJS Ketenagakerjaan","nama_pasangan"=>"Nama Pasangan","kode_kelamin"=>"Jenis Kelamin","kode_agama"=>"Agama","kode_kawin"=>"Status Kawin","kode_warga"=>"Kewarganegaraan","nama"=>"Nama","telepon_darurat"=>"Telepon Darurat","nama_anak"=>"Nama Anak","email"=>"Email","kode_perusahaan"=>"Perusahaan","alamat_kantor"=>"Alamat Kantor","kode_divisi"=>"Direktorat","kode_departemen"=>"Divisi","kode_sub_departemen"=>"Departemen","layer1"=>"Layer 1","layer2"=>"Layer 2","layer3"=>"Layer 3","kode_status"=>"Status Karyawan","wg_id"=>"Grup Kerja","is_aktif"=>"Aktif","gaji_pokok"=>"Gaji Pokok","kode_level"=>"Level","kode_jabatan"=>"Jabatan","tanggal_mulai"=>"Tanggal Mulai Bekerja","tanggal_bergabung"=>"Tanggal Bergabung","tanggal_berakhir"=>"Tanggal Berakhir","tanggal_karyawan_tetap"=>"Tanggal Karyawan Tetap","tanggal_tidak_aktif"=>"Tanggal Tidak Aktif","no_rek"=>"Nomor Rekening","nama_bank"=>"Nama Bank","nama_rek"=>"Rekening Atas Nama","findim_project"=>"Findim Project","findim_divisi"=>"Findim Divisi","findim_departemen"=>"Findim Departemen","findim_subdepartemen"=>"Findim Sub Departemen","id_grade"=>"Grade"];
        // $folder = 'matta\karyawan'.$karyawan->nik;
        // return response()->json( [ 'data' => public_path($folder) ], 200 );
        $penilaian = TrxPenilaian::where(['nik_karyawan' => $karyawan->nik])->orderBy( 'id', 'desc' )->get();
        // return view( 'master.karyawan.profile-tab', compact( 'status', 'karyawan', 'layer', 'kelamin','agama', 'warga', 'kawin', 'perusahaan', 'divisi', 'dep', 'sub_dep', 'kar_status', 'wg', 'is_aktif', 'level', 'jabatan', 'alamat_kantor', 'pendidikan', 'no_pendidikan', 'pelatihan', 'no_pelatihan', 'sertifikasi', 'no_sertifikasi', 'anak', 'no_anak', 'history', 'no_history', 'lbl_history', 'findim_project', 'findim_divisi', 'findim_departemen', 'findim_subdepartemen', 'id_grade', 'penilaian' ) );
        return view( 'master.karyawan.profile-tab', compact( 'status', 'karyawan', 'layer', 'kelamin','agama', 'warga', 'kawin', 'perusahaan', 'divisi', 'dep', 'sub_dep', 'kar_status', 'wg', 'is_aktif', 'level', 'jabatan', 'alamat_kantor', 'pendidikan', 'no_pendidikan', 'pelatihan', 'sertifikasi', 'seminar', 'anak', 'no_anak', 'history', 'no_history', 'lbl_history', 'findim_project', 'findim_divisi', 'findim_departemen', 'findim_subdepartemen', 'id_grade', 'penilaian' ) );
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
        // dd($request->all(), $id);
        $karyawan = Karyawan::find($id);
        $karyawan->nama = $request->nama;
        $karyawan->tempat_lahir = Str::title($request->tempat_lahir);
        $karyawan->tanggal_lahir = $request->tanggal_lahir;
        $karyawan->no_ktp = $request->no_ktp;
        $karyawan->no_npwp = $request->no_npwp;
        $karyawan->no_bpjs = $request->no_bpjs;
        $karyawan->no_bpjstk = $request->no_bpjstk;
        $karyawan->telepon_darurat = $request->telepon_darurat;
        $karyawan->alamat_domisili = Str::title($request->alamat_domisili);
        $karyawan->alamat_ktp = Str::title($request->alamat_ktp);
        $karyawan->alamat_npwp = Str::title($request->alamat_npwp);
        $karyawan->telepon1 = $request->telepon1;
        $karyawan->telepon2 = $request->telepon2;
        $karyawan->telepon3 = $request->telepon3;
        $karyawan->nama_pasangan = $request->nama_pasangan;
        $karyawan->nama_anak = $request->nama_anak;
        $karyawan->email = $request->email;
        $karyawan->kode_perusahaan = $request->kode_perusahaan;
        $karyawan->alamat_kantor = $request->alamat_kantor;
        $karyawan->kode_divisi = $request->kode_divisi;
        $karyawan->kode_departemen = $request->kode_departemen;
        $karyawan->kode_sub_departemen = $request->kode_sub_departemen;
        $karyawan->layer1 = $request->layer1;
        $karyawan->layer2 = $request->layer2;
        $karyawan->layer3 = $request->layer3;
        $karyawan->kode_status = $request->kode_status;
        $karyawan->wg_id = $request->wg_id;
        $karyawan->is_aktif = $request->is_aktif;
        $karyawan->gaji_pokok = $request->gaji_pokok;
        $karyawan->kode_level = $request->kode_level;
        $karyawan->kode_jabatan = $request->kode_jabatan;
        $karyawan->tanggal_mulai = $request->tanggal_mulai;
        $karyawan->tanggal_bergabung = $request->tanggal_bergabung;
        $karyawan->tanggal_berakhir = $request->tanggal_berakhir;
        $karyawan->tanggal_karyawan_tetap = $request->tanggal_karyawan_tetap;
        $karyawan->tanggal_tidak_aktif = $request->tanggal_tidak_aktif;
        $karyawan->kode_agama = $request->kode_agama;
        $karyawan->kode_kelamin = $request->kode_kelamin;
        $karyawan->kode_warga = $request->kode_warga;
        $karyawan->kode_kawin = $request->kode_kawin;
        $karyawan->no_rek = $request->no_rek;
        $karyawan->nama_bank = $request->nama_bank;
        $karyawan->nama_rek = $request->nama_rek;
        $karyawan->findim_project = $request->findim_project;
        $karyawan->findim_divisi = $request->findim_divisi;
        $karyawan->findim_departemen = $request->findim_departemen;
        $karyawan->findim_subdepartemen = $request->findim_subdepartemen;
        $karyawan->id_grade = $request->id_grade;
        $karyawan->updated_by = auth()->user()->nik;
        $getOriginal = $karyawan->getOriginal();
        $karyawan->update();
        // simpan jika ada perubahan
        $getChanges = $karyawan->getChanges();
        $_arr = ["tanggal_lahir","tempat_lahir","alamat_domisili","alamat_ktp","alamat_npwp","telepon1","telepon2","telepon3","no_ktp","no_npwp","no_bpjs","no_bpjstk","nama_pasangan","nama","telepon_darurat","nama_anak","email","alamat_kantor","layer1","layer2","layer3","is_aktif","gaji_pokok","tanggal_mulai","tanggal_bergabung","tanggal_berakhir","tanggal_karyawan_tetap","tanggal_tidak_aktif","no_rek","nama_bank","nama_rek","findim_project","findim_divisi","findim_departemen","findim_subdepartemen","id_grade"];
        $arr = array();
        foreach ($_arr as $value){
            if ($karyawan->wasChanged($value)){
                $arr[$value] = ['before'=>$getOriginal[$value], 'after'=>$getChanges[$value]];
            }
        }
        // ,"kode_kelamin","kode_agama","kode_kawin","kode_warga"
        if ($karyawan->wasChanged('kode_kelamin')){
            $arr['kode_kelamin'] = ['before'=>Kelamin::find($getOriginal['kode_kelamin'])->kelamin, 'after'=>Kelamin::find($getChanges['kode_kelamin'])->kelamin];
        }
        if ($karyawan->wasChanged('kode_agama')){
            $arr['kode_agama'] = ['before'=>Agama::find($getOriginal['kode_agama'])->agama, 'after'=>Agama::find($getChanges['kode_agama'])->agama];
        }
        if ($karyawan->wasChanged('kode_kawin')){
            $arr['kode_kawin'] = ['before'=>Kawin::find($getOriginal['kode_kawin'])->status_kawin, 'after'=>Kawin::find($getChanges['kode_kawin'])->status_kawin];
        }
        if ($karyawan->wasChanged('kode_warga')){
            $arr['kode_warga'] = ['before'=>Warga::find($getOriginal['kode_warga'])->kewarganegaraan, 'after'=>Warga::find($getChanges['kode_warga'])->kewarganegaraan];
        }
        // kode_perusahaan,kode_divisi,kode_departemen,kode_sub_departemen,kode_status,wg_id,kode_level,kode_jabatan
        if ($karyawan->wasChanged('kode_perusahaan')){
            $arr['kode_perusahaan'] = ['before'=>Perusahaan::find($getOriginal['kode_perusahaan'])->nama, 'after'=>Perusahaan::find($getChanges['kode_perusahaan'])->nama];
        }
        if ($karyawan->wasChanged('kode_divisi')){
            $arr['kode_divisi'] = ['before'=>Divisi::find($getOriginal['kode_divisi'])->nama, 'after'=>Divisi::find($getChanges['kode_divisi'])->nama];
        }
        if ($karyawan->wasChanged('kode_departemen')){
            $arr['kode_departemen'] = ['before'=>Departemen::find($getOriginal['kode_departemen'])->nama, 'after'=>Departemen::find($getChanges['kode_departemen'])->nama];
        }
        if ($karyawan->wasChanged('kode_sub_departemen')){
            $arr['kode_sub_departemen'] = ['before'=>SubDepartemen::find($getOriginal['kode_sub_departemen'])->nama, 'after'=>SubDepartemen::find($getChanges['kode_sub_departemen'])->nama];
        }
        if ($karyawan->wasChanged('kode_status')){
            $arr['kode_status'] = ['before'=>KaryawanStatus::find($getOriginal['kode_status'])->status_karyawan, 'after'=>KaryawanStatus::find($getChanges['kode_status'])->status_karyawan];
        }
        if ($karyawan->wasChanged('wg_id')){
            $arr['wg_id'] = ['before'=>WorkingGroup::find($getOriginal['wg_id'])->wg_name, 'after'=>WorkingGroup::find($getChanges['wg_id'])->wg_name];
        }
        if ($karyawan->wasChanged('kode_level')){
            $arr['kode_level'] = ['before'=>Level::find($getOriginal['kode_level'])->level, 'after'=>Level::find($getChanges['kode_level'])->level];
        }
        if ($karyawan->wasChanged('kode_jabatan')){
            $arr['kode_jabatan'] = ['before'=>Jabatan::find($getOriginal['kode_jabatan'])->jabatan, 'after'=>Jabatan::find($getChanges['kode_jabatan'])->jabatan];
        }
        // findim_project,findim_divisi,findim_departemen,findim_subdepartemen
        if ($karyawan->wasChanged('findim_project')){
            $arr['findim_project'] = ['before'=>MtrFindimProject::find($getOriginal['findim_project'])->project_desc, 'after'=>MtrFindimProject::find($getChanges['findim_project'])->project_desc];
        }
        if ($karyawan->wasChanged('findim_divisi')){
            $arr['findim_divisi'] = ['before'=>MtrFindimDivisi::find($getOriginal['findim_divisi'])->divisi_desc, 'after'=>MtrFindimDivisi::find($getChanges['findim_divisi'])->divisi_desc];
        }
        if ($karyawan->wasChanged('findim_departemen')){
            $arr['findim_departemen'] = ['before'=>MtrFindimDepartemen::find($getOriginal['findim_departemen'])->departemen_desc, 'after'=>MtrFindimDepartemen::find($getChanges['findim_departemen'])->departemen_desc];
        }
        if ($karyawan->wasChanged('findim_subdepartemen')){
            $arr['findim_subdepartemen'] = ['before'=>MtrFindimSubdepartemen::find($getOriginal['findim_subdepartemen'])->subdepartemen_desc, 'after'=>MtrFindimSubdepartemen::find($getChanges['findim_subdepartemen'])->subdepartemen_desc];
        }
        // id_grade
        // akan diaktifkan jika sudah terisikan semua
        if ($karyawan->wasChanged('id_grade')){
            if(!empty(KaryawanGrade::find($getOriginal['id_grade']))){
                $arr['id_grade'] = ['before'=>KaryawanGrade::find($getOriginal['id_grade'])->grade, 'after'=>KaryawanGrade::find($getChanges['id_grade'])->grade];
            }else{
                $arr['id_grade'] = ['before'=>'', 'after'=>KaryawanGrade::find($getChanges['id_grade'])->grade];
            }
        }
        if(!empty($getChanges)){
            $log = New KaryawanLog;
            $log->nik = $karyawan->nik;
            $log->perubahan = json_encode($arr);
            $log->insert_by = auth()->user()->nik;
            $log->save();
            // start sendEmail
            $mailData['title'] = 'Perubahan Data Karyawan';
            $mailData['filename'] = 'emails.data0';
            $lbl_history = ["tanggal_lahir"=>"Tanggal Lahir","tempat_lahir"=>"Tempat Lahir","alamat_domisili"=>"Alamat Domisili","alamat_ktp"=>"Alamat KTP","alamat_npwp"=>"Alamat NPWP","telepon1"=>"Telepon 1","telepon2"=>"Telepon 2","telepon3"=>"Telepon 3","no_ktp"=>"NIK KTP","no_npwp"=>"NPWP","no_bpjs"=>"No BPJS Kesehatan","no_bpjstk"=>"No BPJS Ketenagakerjaan","nama_pasangan"=>"Nama Pasangan","kode_kelamin"=>"Jenis Kelamin","kode_agama"=>"Agama","kode_kawin"=>"Status Kawin","kode_warga"=>"Kewarganegaraan","nama"=>"Nama","telepon_darurat"=>"Telepon Darurat","nama_anak"=>"Nama Anak","email"=>"Email","kode_perusahaan"=>"Perusahaan","alamat_kantor"=>"Alamat Kantor","kode_divisi"=>"Direktorat","kode_departemen"=>"Divisi","kode_sub_departemen"=>"Departemen","layer1"=>"Layer 1","layer2"=>"Layer 2","layer3"=>"Layer 3","kode_status"=>"Status Karyawan","wg_id"=>"Grup Kerja","is_aktif"=>"Aktif","gaji_pokok"=>"Gaji Pokok","kode_level"=>"Level","kode_jabatan"=>"Jabatan","tanggal_mulai"=>"Tanggal Mulai Bekerja","tanggal_bergabung"=>"Tanggal Bergabung","tanggal_berakhir"=>"Tanggal Berakhir","tanggal_karyawan_tetap"=>"Tanggal Karyawan Tetap","tanggal_tidak_aktif"=>"Tanggal Tidak Aktif","no_rek"=>"Nomor Rekening","nama_bank"=>"Nama Bank","nama_rek"=>"Rekening Atas Nama","findim_project"=>"Findim Project","findim_divisi"=>"Findim Divisi","findim_departemen"=>"Findim Departemen","findim_subdepartemen"=>"Findim Sub Departemen","id_grade"=>"Grade"];
            $mailData['data'] = ['insert_by' => $log->_insert_by->nama.' / '.$log->insert_by, 'nik' => $log->nik, 'nama' => $log->_nik->nama, 'perubahan' => $log->perubahan, 'lbl_history' => $lbl_history, 'created_at' => $log->created_at];
            $mailData['to'] = explode(',', MtrUtility::find(1)->informasi);
            $this->sendEmail($mailData);
            // end sendEmail
        }
        return redirect( 'karyawan' )->with( 'msg', 'Data is saved successfully.' );
    }
    
    public function update_profile(Request $request, $id){
        // dd($request->all(), $id);
        $id = (new OpenDataController)->decryptWithKey($id);
        // $karyawan = Karyawan::find($id);
        if($id != auth()->user()->nik){
            return redirect()->route('error-403');
        }
        $karyawan = Karyawan::where('nik', $id)->first();
        // $karyawan->nama = $request->nama;
        $karyawan->tempat_lahir = Str::title($request->tempat_lahir);
        $karyawan->tanggal_lahir = $request->tanggal_lahir;
        $karyawan->no_ktp = $request->no_ktp;
        $karyawan->no_npwp = $request->no_npwp;
        $karyawan->no_bpjs = $request->no_bpjs;
        $karyawan->no_bpjstk = $request->no_bpjstk;
        // $karyawan->telepon_darurat = $request->telepon_darurat;
        $karyawan->alamat_domisili = Str::title($request->alamat_domisili);
        $karyawan->alamat_ktp = Str::title($request->alamat_ktp);
        $karyawan->alamat_npwp = Str::title($request->alamat_npwp);
        $karyawan->telepon1 = $request->telepon1;
        $karyawan->telepon2 = $request->telepon2;
        $karyawan->telepon3 = $request->telepon3;
        $karyawan->nama_pasangan = $request->nama_pasangan;
        // $karyawan->nama_anak = $request->nama_anak;
        // $karyawan->email = $request->email;
        // $karyawan->kode_perusahaan = $request->kode_perusahaan;
        // $karyawan->alamat_kantor = $request->alamat_kantor;
        // $karyawan->kode_divisi = $request->kode_divisi;
        // $karyawan->kode_departemen = $request->kode_departemen;
        // $karyawan->kode_sub_departemen = $request->kode_sub_departemen;
        // $karyawan->layer1 = $request->layer1;
        // $karyawan->layer2 = $request->layer2;
        // $karyawan->layer3 = $request->layer3;
        // $karyawan->kode_status = $request->kode_status;
        // $karyawan->wg_id = $request->wg_id;
        // $karyawan->is_aktif = $request->is_aktif;
        // $karyawan->gaji_pokok = $request->gaji_pokok;
        // $karyawan->kode_level = $request->kode_level;
        // $karyawan->kode_jabatan = $request->kode_jabatan;
        // $karyawan->tanggal_mulai = $request->tanggal_mulai;
        // $karyawan->tanggal_bergabung = $request->tanggal_bergabung;
        // $karyawan->tanggal_berakhir = $request->tanggal_berakhir;
        // $karyawan->tanggal_karyawan_tetap = $request->tanggal_karyawan_tetap;
        // $karyawan->tanggal_tidak_aktif = $request->tanggal_tidak_aktif;
        $karyawan->kode_agama = $request->kode_agama;
        $karyawan->kode_kelamin = $request->kode_kelamin;
        $karyawan->kode_warga = $request->kode_warga;
        $karyawan->kode_kawin = $request->kode_kawin;
        $karyawan->updated_by = auth()->user()->nik;
        $getOriginal = $karyawan->getOriginal();
        $karyawan->update();
        // simpan jika ada perubahan
        $getChanges = $karyawan->getChanges();
        $_arr = ["tanggal_lahir","tempat_lahir","alamat_domisili","alamat_ktp","alamat_npwp","telepon1","telepon2","telepon3","no_ktp","no_npwp","no_bpjs","no_bpjstk","nama_pasangan"];
        $arr = array();
        foreach ($_arr as $value){
            if ($karyawan->wasChanged($value)){
                $arr[$value] = ['before'=>$getOriginal[$value], 'after'=>$getChanges[$value]];
            }
        }
        // ,"kode_kelamin","kode_agama","kode_kawin","kode_warga"
        if ($karyawan->wasChanged('kode_kelamin')){
            $arr['kode_kelamin'] = ['before'=>Kelamin::find($getOriginal['kode_kelamin'])->kelamin, 'after'=>Kelamin::find($getChanges['kode_kelamin'])->kelamin];
        }
        if ($karyawan->wasChanged('kode_agama')){
            $arr['kode_agama'] = ['before'=>Agama::find($getOriginal['kode_agama'])->agama, 'after'=>Agama::find($getChanges['kode_agama'])->agama];
        }
        if ($karyawan->wasChanged('kode_kawin')){
            $arr['kode_kawin'] = ['before'=>Kawin::find($getOriginal['kode_kawin'])->status_kawin, 'after'=>Kawin::find($getChanges['kode_kawin'])->status_kawin];
        }
        if ($karyawan->wasChanged('kode_warga')){
            $arr['kode_warga'] = ['before'=>Warga::find($getOriginal['kode_warga'])->kewarganegaraan, 'after'=>Warga::find($getChanges['kode_warga'])->kewarganegaraan];
        }
        if(!empty($getChanges)){
            $log = New KaryawanLog;
            $log->nik = auth()->user()->nik;
            $log->perubahan = json_encode($arr);
            $log->insert_by = auth()->user()->nik;
            $log->save();
            // start sendEmail
            $mailData['title'] = 'Perubahan Data Karyawan';
            $mailData['filename'] = 'emails.data0';
            $lbl_history = ["tanggal_lahir"=>"Tanggal Lahir","tempat_lahir"=>"Tempat Lahir","alamat_domisili"=>"Alamat Domisili","alamat_ktp"=>"Alamat KTP","alamat_npwp"=>"Alamat NPWP","telepon1"=>"Telepon 1","telepon2"=>"Telepon 2","telepon3"=>"Telepon 3","no_ktp"=>"NIK KTP","no_npwp"=>"NPWP","no_bpjs"=>"No BPJS Kesehatan","no_bpjstk"=>"No BPJS Ketenagakerjaan","nama_pasangan"=>"Nama Pasangan","kode_kelamin"=>"Jenis Kelamin","kode_agama"=>"Agama","kode_kawin"=>"Status Kawin","kode_warga"=>"Kewarganegaraan"];
            // $mailData['data'] = ['nik' => $log->nik, 'nama' => $log->_nik->nama, 'perubahan' => $log->perubahan, 'lbl_history' => $lbl_history, 'created_at' => $log->created_at];
            $mailData['data'] = ['insert_by' => $log->_insert_by->nama.' / '.$log->insert_by, 'nik' => $log->nik, 'nama' => $log->_nik->nama, 'perubahan' => $log->perubahan, 'lbl_history' => $lbl_history, 'created_at' => $log->created_at];
            $mailData['to'] = explode(',', MtrUtility::find(1)->informasi);
            $this->sendEmail($mailData);
            // end sendEmail
        }
        // return response()->json( [ 'arr' => $arr ], 200 );
        return redirect()->route('karyawan.show', (new OpenDataController)->encryptWithKey($id))->with('success','Data is saved successfully.');
    }

    public function sendEmail($mailData)
    {
        $ccEmails = explode(',', MtrUtility::find(11)->informasi);
        Mail::to($mailData['to'])->cc($ccEmails)->send(new MattaRebornMail($mailData));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = Crypt::decrypt($id);
        $karyawan = Karyawan::find($id);
        try {
            $karyawan->delete();
            return redirect( 'karyawan' )->with( 'msg', 'Data is deleted successfully.' );
         } catch (\Exception $e) {
            //   var_dump( $e->errorInfo );
            // Log::error($e->errorInfo);
            // Log::error($karyawan);
            return redirect()->route('error_db');
         }
    }
}
