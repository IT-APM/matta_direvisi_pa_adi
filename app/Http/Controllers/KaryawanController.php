<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\KaryawanLog;
use App\Models\KaryawanPendidikan;
use App\Models\KaryawanPelatihan;
use App\Models\KaryawanSertifikasi;
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
use App\Models\MtrFindimProject;
use App\Models\MtrFindimDivisi;
use App\Models\MtrFindimDepartemen;
use App\Models\MtrFindimSubdepartemen;
use App\Models\KaryawanGrade;
use App\Models\TrxPenilaian;
// 
use App\Models\MtrUtility;
use App\Mail\MattaRebornMail;
use Illuminate\Support\Facades\Mail;
// 
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
// 
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class KaryawanController extends Controller
{
    public function __construct(Gate $gate)
    {
        $this->middleware('can:hc', ['except' => ['show', 'get_divisi', 'get_departemen', 'get_sub_departemen', 'update_profile']]);
    }
    
    public function upload_data($id)
    {
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
            if($jenis == 'data_findim'){
                $path = $request->file('template')->getPathname();
                $trx = Excel::toArray([], $path);
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
                    // 
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
            }
        }
        return redirect('karyawan/upload_data/0')->with('msg', 'Data is saved successfully.');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd($request->all());
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
        $no = 0;
        $karyawan = Karyawan::where('is_aktif', 1)->orderBy( 'nama', 'asc' )->get();
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

    public function get_divisi( $id ) {
        $data = Divisi::where(['kode_perusahaan'=>$id, 'is_active'=>1])->orderBy( 'nama', 'asc' )->get();
        return json_decode($data, true);
    }

    public function get_departemen( $id ) {
        $data = Departemen::where(['kode_divisi'=>$id, 'is_active'=>1])->orderBy( 'nama', 'asc' )->get();
        return json_decode($data, true);
    }

    public function get_sub_departemen( $id ) {
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
        $perusahaan = perusahaan::where('is_active', 1)->get();
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
        // dd($id);
        $status = 'edit';
        $karyawan = Karyawan::find( $id );
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
        $perusahaan = perusahaan::where('is_active', 1)->get();
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
        $pelatihan = KaryawanPelatihan::where($where)->orderBy( 'nama', 'asc' )->get();
        $no_pelatihan = 0;
        $sertifikasi = KaryawanSertifikasi::where($where)->orderBy( 'nama', 'asc' )->get();
        $no_sertifikasi = 0;
        $anak = KaryawanAnak::where($where)->orderBy( 'id_anakke', 'asc' )->get();
        $no_anak = 0;
        $history = KaryawanLog::where($where)->orderBy( 'id', 'desc' )->get();
        $no_history = 0;
        // $lbl_history = ["tanggal_lahir"=>"Tanggal Lahir","tempat_lahir"=>"Tempat Lahir","alamat_domisili"=>"Alamat Domisili","alamat_ktp"=>"Alamat KTP","alamat_npwp"=>"Alamat NPWP","telepon1"=>"Telepon 1","telepon2"=>"Telepon 2","telepon3"=>"Telepon 3","no_ktp"=>"NIK KTP","no_npwp"=>"NPWP","no_bpjs"=>"No BPJS Kesehatan","no_bpjstk"=>"No BPJS Ketenagakerjaan","nama_pasangan"=>"Nama Pasangan","kode_kelamin"=>"Jenis Kelamin","kode_agama"=>"Agama","kode_kawin"=>"Status Kawin","kode_warga"=>"Kewarganegaraan"];
        $lbl_history = ["tanggal_lahir"=>"Tanggal Lahir","tempat_lahir"=>"Tempat Lahir","alamat_domisili"=>"Alamat Domisili","alamat_ktp"=>"Alamat KTP","alamat_npwp"=>"Alamat NPWP","telepon1"=>"Telepon 1","telepon2"=>"Telepon 2","telepon3"=>"Telepon 3","no_ktp"=>"NIK KTP","no_npwp"=>"NPWP","no_bpjs"=>"No BPJS Kesehatan","no_bpjstk"=>"No BPJS Ketenagakerjaan","nama_pasangan"=>"Nama Pasangan","kode_kelamin"=>"Jenis Kelamin","kode_agama"=>"Agama","kode_kawin"=>"Status Kawin","kode_warga"=>"Kewarganegaraan","nama"=>"Nama","telepon_darurat"=>"Telepon Darurat","nama_anak"=>"Nama Anak","email"=>"Email","kode_perusahaan"=>"Perusahaan","alamat_kantor"=>"Alamat Kantor","kode_divisi"=>"Direktorat","kode_departemen"=>"Divisi","kode_sub_departemen"=>"Departemen","layer1"=>"Layer 1","layer2"=>"Layer 2","layer3"=>"Layer 3","kode_status"=>"Status Karyawan","wg_id"=>"Grup Kerja","is_aktif"=>"Aktif","gaji_pokok"=>"Gaji Pokok","kode_level"=>"Level","kode_jabatan"=>"Jabatan","tanggal_mulai"=>"Tanggal Mulai Bekerja","tanggal_bergabung"=>"Tanggal Bergabung","tanggal_berakhir"=>"Tanggal Berakhir","tanggal_karyawan_tetap"=>"Tanggal Karyawan Tetap","tanggal_tidak_aktif"=>"Tanggal Tidak Aktif","no_rek"=>"Nomor Rekening","nama_bank"=>"Nama Bank","nama_rek"=>"Rekening Atas Nama","findim_project"=>"Findim Project","findim_divisi"=>"Findim Divisi","findim_departemen"=>"Findim Departemen","findim_subdepartemen"=>"Findim Sub Departemen","id_grade"=>"Grade"];
        // $folder = 'matta\karyawan'.$karyawan->nik;
        // return response()->json( [ 'data' => public_path($folder) ], 200 );
        $penilaian = TrxPenilaian::where(['nik_karyawan' => $karyawan->nik])->orderBy( 'id', 'desc' )->get();
        return view( 'master.karyawan.profile-tab', compact( 'status', 'karyawan', 'layer', 'kelamin','agama', 'warga', 'kawin', 'perusahaan', 'divisi', 'dep', 'sub_dep', 'kar_status', 'wg', 'is_aktif', 'level', 'jabatan', 'alamat_kantor', 'pendidikan', 'no_pendidikan', 'pelatihan', 'no_pelatihan', 'sertifikasi', 'no_sertifikasi', 'anak', 'no_anak', 'history', 'no_history', 'lbl_history', 'findim_project', 'findim_divisi', 'findim_departemen', 'findim_subdepartemen', 'id_grade', 'penilaian' ) );
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
        $karyawan = Karyawan::find( $id );
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
        // $karyawan = Karyawan::find( $id );
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
        //
        $karyawan = Karyawan::find( $id );
        try {
            $karyawan->delete();
            return redirect( 'karyawan' )->with( 'msg', 'Data is deleted successfully.' );
         } catch (\Exception $e) {
            //   var_dump( $e->errorInfo );
            Log::error($e->errorInfo);
            Log::error($karyawan);
            return redirect()->route('error_db');
         }
    }
}
