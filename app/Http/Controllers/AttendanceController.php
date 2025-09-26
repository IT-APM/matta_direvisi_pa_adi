<?php

namespace App\Http\Controllers;
use App\Models\Attendance;
use App\Models\Karyawan;
use App\Models\Perusahaan;
use App\Models\WorkingGroup;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceExport;
use App\Exports\AttendanceMultiSheetExport;
use App\Models\TrxDebug;
use Illuminate\Support\Facades\Route;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        set_time_limit(3600);
        $limit = 25000;
        $tahun = Carbon::parse(now())->format('Y');
        $bulan = Carbon::parse(now())->format('m');
        $duration = array();
        // start default hc
        $att = array();
        // $att = Attendance::select('att_date','att_timestamp','att_nik','att_type','att_long','att_lat','att_address','att_status','att_data_src','wg_id')->whereMonth('att_timestamp', $bulan)->whereYear('att_timestamp', $tahun)->orderBy( 'att_timestamp', 'desc' )->limit($limit)->get();
        // end default hc
        if(auth()->user()->userrole == 'nhc'){
            // $where = ['att_nik' => auth()->user()->nik];
            $where = ['layer1' => auth()->user()->nik];
            $bawahan = Karyawan::select('nik')->where($where)->get();
            $att_nik = array();
            array_push($att_nik, auth()->user()->nik);
            foreach($bawahan as $data){
                array_push($att_nik, $data->nik);
            }
            $att = Attendance::select('att_date','att_timestamp','att_nik','att_type','att_long','att_lat','att_address','att_status','att_data_src','wg_id')->whereMonth('att_timestamp', $bulan)->whereYear('att_timestamp', $tahun)->whereIn('att_nik', $att_nik)->orderBy( 'att_timestamp', 'desc' )->limit($limit)->get();
            // start hitung durasi
            $duration = array();
            $_att_data_src = 'Regular';
            $raw = Attendance::select('att_date','att_timestamp','att_nik','att_type','att_long','att_lat','att_address','att_status','att_data_src','wg_id')->whereMonth('att_timestamp', $bulan)->whereYear('att_timestamp', $tahun)->whereIn('att_nik', $att_nik)->where('att_type', '=', 'Clock-in')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'asc' )->limit($limit)->get();
            foreach($raw as $_raw){
                $result = Attendance::select('att_date','att_timestamp','att_nik','att_type','att_long','att_lat','att_address','att_status','att_data_src','wg_id')->where('att_nik', '=', $_raw->att_nik)->where('att_date', '=', $_raw->att_date)->where('att_type', '=', 'Clock-out')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'desc' )->first();
                // jika non reguler
                $where = ['nik' => $_raw->att_nik, 'wg_id' => 1];
                if(!Karyawan::where($where)->exists()){
                    $result = Attendance::select('att_date','att_timestamp','att_nik','att_type','att_long','att_lat','att_address','att_status','att_data_src','wg_id')->where('att_nik', '=', $_raw->att_nik)->where('att_wd_ot_id', '=', $_raw->att_wd_ot_id)->where('att_type', '=', 'Clock-out')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'desc' )->first();
                }
                // 
                if(!empty($result)){
                    array_push($duration, ['att_date' => $_raw->att_date, 'att_nik' => $_raw->att_nik, 'att_data_src' => $_att_data_src, 'Clock-in' => $_raw->att_timestamp, 'Clock-out' => $result->att_timestamp]);
                }
            }
            $_att_data_src = 'Overtime';
            $raw = Attendance::select('att_date','att_timestamp','att_nik','att_type','att_long','att_lat','att_address','att_status','att_data_src','wg_id')->whereMonth('att_timestamp', $bulan)->whereYear('att_timestamp', $tahun)->whereIn('att_nik', $att_nik)->where('att_type', '=', 'Clock-in')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'asc' )->limit($limit)->get();
            foreach($raw as $_raw){
                $result = Attendance::select('att_date','att_timestamp','att_nik','att_type','att_long','att_lat','att_address','att_status','att_data_src','wg_id')->where('att_nik', '=', $_raw->att_nik)->where('att_date', '=', $_raw->att_date)->where('att_type', '=', 'Clock-out')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'desc' )->first();
                // jika non reguler
                $where = ['nik' => $_raw->att_nik, 'wg_id' => 1];
                if(!Karyawan::where($where)->exists()){
                    $result = Attendance::select('att_date','att_timestamp','att_nik','att_type','att_long','att_lat','att_address','att_status','att_data_src','wg_id')->where('att_nik', '=', $_raw->att_nik)->where('att_wd_ot_id', '=', $_raw->att_wd_ot_id)->where('att_type', '=', 'Clock-out')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'desc' )->first();
                }
                // 
                if(!empty($result)){
                    array_push($duration, ['att_date' => $_raw->att_date, 'att_nik' => $_raw->att_nik, 'att_data_src' => $_att_data_src, 'Clock-in' => $_raw->att_timestamp, 'Clock-out' => $result->att_timestamp]);
                }
            }
            // end hitung durasi
        }
        // filter parameter
        $_arr_att_type = Attendance::distinct()->orderBy( 'att_type', 'asc' )->get(['att_type']);
        $_arr_att_status = Attendance::distinct()->orderBy( 'att_status', 'asc' )->get(['att_status']);
        $_arr_att_data_src = Attendance::distinct()->orderBy( 'att_data_src', 'asc' )->get(['att_data_src']);
        // 0=NA
        // 3=SMS Project
        $perusahaan = Perusahaan::whereNotIn('id', [0,3])->orderBy( 'nama', 'asc' )->get();
        $wg_id = WorkingGroup::orderBy( 'id', 'asc' )->get();
        $arr_att_type = array();
        $arr_att_status = array();
        $arr_att_data_src = array();
        $arr_perusahaan = array();
        $arr_wg_id = array();
        foreach ($_arr_att_type as $data){
            if(!in_array($data->att_type, $arr_att_type)){
                array_push($arr_att_type, $data->att_type);
            }
        }
        foreach ($_arr_att_status as $data){
            if(!in_array($data->att_status, $arr_att_status)){
                array_push($arr_att_status, $data->att_status);
            }
        }
        foreach ($_arr_att_data_src as $data){
            if(!in_array($data->att_data_src, $arr_att_data_src)){
                array_push($arr_att_data_src, $data->att_data_src);
            }
        }
        foreach ($perusahaan as $data){
            array_push($arr_perusahaan, $data->id);
        }
        foreach ($wg_id as $data){
            array_push($arr_wg_id, $data->id);
        }
        $att_type = $arr_att_type;
        $att_status = $arr_att_status;
        $att_data_src = $arr_att_data_src;
        $date_from = '';
        $date_to = '';
        // start filter
        if($request->filter == true || $request->download == true){
            // $this->into_debug(['nomor'=>1, 'modul'=>Route::getCurrentRoute()->getActionName(), 'url'=>$request->fullUrl()]);
            $date_from = $request->date_from;
            $date_to = $request->date_to;
            $_att_type = $request->input('att_type', []);
            $_att_status = $request->input('att_status', []);
            $_att_data_src = $request->input('att_data_src', []);
            $_perusahaan = $request->input('perusahaan', []);
            $_wg_id = $request->input('wg_id', []);
            if(count($_att_type) > 0){
                $arr_att_type = $_att_type;
            }
            else{
                $arr_att_type = array();
            }
            if(count($_att_status) > 0){
                $arr_att_status = $_att_status;
            }
            else{
                $arr_att_status = array();
            }
            if(count($_att_data_src) > 0){
                $arr_att_data_src = $_att_data_src;
            }
            else{
                $arr_att_data_src = array();
            }
            if(count($_perusahaan) > 0){
                $arr_perusahaan = $_perusahaan;
            }
            else{
                $arr_perusahaan = array();
            }
            if(count($_wg_id) > 0){
                $arr_wg_id = $_wg_id;
            }
            else{
                $arr_wg_id = array();
            }
            $att_nik = Karyawan::select('nik')->whereIn('kode_perusahaan', $arr_perusahaan)->get();
            $nik_wg_id = Karyawan::select('nik')->whereIn('wg_id', $arr_wg_id)->get();
            $att = Attendance::select('att_date','att_timestamp','att_nik','att_type','att_long','att_lat','att_address','att_status','att_data_src','wg_id')->whereIn('att_type', $arr_att_type)->whereIn('att_status', $arr_att_status)->whereIn('att_data_src', $arr_att_data_src)->whereIn('att_nik', $att_nik)->whereIn('att_nik', $nik_wg_id)->whereBetween('att_date', [$date_from, $date_to])->orderBy( 'att_timestamp', 'desc' )->limit($limit)->get();
            // $att = Attendance::select('att_date','att_timestamp','att_nik','att_type','att_long','att_lat','att_address','att_status','att_data_src','wg_id')->whereIn('att_type', $arr_att_type)->whereIn('att_status', $arr_att_status)->whereIn('att_data_src', $arr_att_data_src)->whereIn('att_nik', $nik_wg_id)->whereBetween('att_date', [$date_from, $date_to])->orderBy( 'att_timestamp', 'desc' )->limit($limit)->get();
            // start hitung durasi
            $duration = array();
            $_att_data_src = 'Regular';
            $raw = Attendance::select('att_date','att_timestamp','att_nik','att_type','att_long','att_lat','att_address','att_status','att_data_src','wg_id')->whereIn('att_type', $arr_att_type)->whereIn('att_status', $arr_att_status)->whereIn('att_data_src', $arr_att_data_src)->whereIn('att_nik', $att_nik)->whereIn('att_nik', $nik_wg_id)->whereBetween('att_date', [$date_from, $date_to])->where('att_type', '=', 'Clock-in')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'asc' )->limit($limit)->get();
            // $raw = Attendance::select('att_date','att_timestamp','att_nik','att_type','att_long','att_lat','att_address','att_status','att_data_src','wg_id')->whereIn('att_type', $arr_att_type)->whereIn('att_status', $arr_att_status)->whereIn('att_data_src', $arr_att_data_src)->whereIn('att_nik', $nik_wg_id)->whereBetween('att_date', [$date_from, $date_to])->where('att_type', '=', 'Clock-in')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'asc' )->limit($limit)->get();
            foreach($raw as $_raw){
                // $this->into_debug(['nomor'=>2, 'modul'=>Route::getCurrentRoute()->getActionName(), 'url'=>$request->fullUrl()]);
                $result = Attendance::select('att_date','att_timestamp','att_nik','att_type','att_long','att_lat','att_address','att_status','att_data_src','wg_id')->where('att_nik', '=', $_raw->att_nik)->where('att_date', '=', $_raw->att_date)->where('att_type', '=', 'Clock-out')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'desc' )->first();
                // jika non reguler
                $where = ['nik' => $_raw->att_nik, 'wg_id' => 1];
                if(!Karyawan::where($where)->exists()){
                    $result = Attendance::select('att_date','att_timestamp','att_nik','att_type','att_long','att_lat','att_address','att_status','att_data_src','wg_id')->where('att_nik', '=', $_raw->att_nik)->where('att_wd_ot_id', '=', $_raw->att_wd_ot_id)->where('att_type', '=', 'Clock-out')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'desc' )->first();
                }
                // 
                if(!empty($result)){
                    array_push($duration, ['att_date' => $_raw->att_date, 'att_nik' => $_raw->att_nik, 'att_data_src' => $_att_data_src, 'Clock-in' => $_raw->att_timestamp, 'Clock-out' => $result->att_timestamp]);
                }
            }
            $_att_data_src = 'Overtime';
            $raw = Attendance::select('att_date','att_timestamp','att_nik','att_type','att_long','att_lat','att_address','att_status','att_data_src','wg_id')->whereIn('att_type', $arr_att_type)->whereIn('att_status', $arr_att_status)->whereIn('att_data_src', $arr_att_data_src)->whereIn('att_nik', $att_nik)->whereIn('att_nik', $nik_wg_id)->whereBetween('att_date', [$date_from, $date_to])->where('att_type', '=', 'Clock-in')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'asc' )->limit($limit)->get();
            // $raw = Attendance::select('att_date','att_timestamp','att_nik','att_type','att_long','att_lat','att_address','att_status','att_data_src','wg_id')->whereIn('att_type', $arr_att_type)->whereIn('att_status', $arr_att_status)->whereIn('att_data_src', $arr_att_data_src)->whereIn('att_nik', $nik_wg_id)->whereBetween('att_date', [$date_from, $date_to])->where('att_type', '=', 'Clock-in')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'asc' )->limit($limit)->get();
            foreach($raw as $_raw){
                // $this->into_debug(['nomor'=>3, 'modul'=>Route::getCurrentRoute()->getActionName(), 'url'=>$request->fullUrl()]);
                $result = Attendance::select('att_date','att_timestamp','att_nik','att_type','att_long','att_lat','att_address','att_status','att_data_src','wg_id')->where('att_nik', '=', $_raw->att_nik)->where('att_date', '=', $_raw->att_date)->where('att_type', '=', 'Clock-out')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'desc' )->first();
                // jika non reguler
                $where = ['nik' => $_raw->att_nik, 'wg_id' => 1];
                if(!Karyawan::where($where)->exists()){
                    $result = Attendance::select('att_date','att_timestamp','att_nik','att_type','att_long','att_lat','att_address','att_status','att_data_src','wg_id')->where('att_nik', '=', $_raw->att_nik)->where('att_wd_ot_id', '=', $_raw->att_wd_ot_id)->where('att_type', '=', 'Clock-out')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'desc' )->first();
                }
                // 
                if(!empty($result)){
                    array_push($duration, ['att_date' => $_raw->att_date, 'att_nik' => $_raw->att_nik, 'att_data_src' => $_att_data_src, 'Clock-in' => $_raw->att_timestamp, 'Clock-out' => $result->att_timestamp]);
                }
            }
            // end hitung durasi
            if(auth()->user()->userrole == 'nhc'){
                // $this->into_debug(['nomor'=>4, 'modul'=>Route::getCurrentRoute()->getActionName(), 'url'=>$request->fullUrl()]);
                // $where = ['att_nik' => auth()->user()->nik];
                $where = ['layer1' => auth()->user()->nik];
                $bawahan = Karyawan::select('nik')->where($where)->get();
                $att_nik = array();
                array_push($att_nik, auth()->user()->nik);
                foreach($bawahan as $data){
                    array_push($att_nik, $data->nik);
                }
                $att = Attendance::select('att_date','att_timestamp','att_nik','att_type','att_long','att_lat','att_address','att_status','att_data_src','wg_id')->whereIn('att_type', $arr_att_type)->whereIn('att_status', $arr_att_status)->whereIn('att_data_src', $arr_att_data_src)->whereIn('att_nik', $att_nik)->whereIn('att_nik', $nik_wg_id)->whereBetween('att_date', [$date_from, $date_to])->orderBy( 'att_timestamp', 'desc' )->limit($limit)->get();
                // start hitung durasi
                $duration = array();
                $_att_data_src = 'Regular';
                $raw = Attendance::select('att_date','att_timestamp','att_nik','att_type','att_long','att_lat','att_address','att_status','att_data_src','wg_id')->whereIn('att_type', $arr_att_type)->whereIn('att_status', $arr_att_status)->whereIn('att_data_src', $arr_att_data_src)->whereIn('att_nik', $att_nik)->whereIn('att_nik', $nik_wg_id)->whereBetween('att_date', [$date_from, $date_to])->where('att_type', '=', 'Clock-in')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'asc' )->limit($limit)->get();
                foreach($raw as $_raw){
                    // $this->into_debug(['nomor'=>5, 'modul'=>Route::getCurrentRoute()->getActionName(), 'url'=>$request->fullUrl()]);
                    $result = Attendance::select('att_date','att_timestamp','att_nik','att_type','att_long','att_lat','att_address','att_status','att_data_src','wg_id')->where('att_nik', '=', $_raw->att_nik)->where('att_date', '=', $_raw->att_date)->where('att_type', '=', 'Clock-out')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'desc' )->first();
                    // jika non reguler
                    $where = ['nik' => $_raw->att_nik, 'wg_id' => 1];
                    if(!Karyawan::where($where)->exists()){
                        $result = Attendance::select('att_date','att_timestamp','att_nik','att_type','att_long','att_lat','att_address','att_status','att_data_src','wg_id')->where('att_nik', '=', $_raw->att_nik)->where('att_wd_ot_id', '=', $_raw->att_wd_ot_id)->where('att_type', '=', 'Clock-out')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'desc' )->first();
                    }
                if(!empty($result)){
                        array_push($duration, ['att_date' => $_raw->att_date, 'att_nik' => $_raw->att_nik, 'att_data_src' => $_att_data_src, 'Clock-in' => $_raw->att_timestamp, 'Clock-out' => $result->att_timestamp]);
                    }
                }
                $_att_data_src = 'Overtime';
                $raw = Attendance::select('att_date','att_timestamp','att_nik','att_type','att_long','att_lat','att_address','att_status','att_data_src','wg_id')->whereIn('att_type', $arr_att_type)->whereIn('att_status', $arr_att_status)->whereIn('att_data_src', $arr_att_data_src)->whereIn('att_nik', $att_nik)->whereIn('att_nik', $nik_wg_id)->whereBetween('att_date', [$date_from, $date_to])->where('att_type', '=', 'Clock-in')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'asc' )->limit($limit)->get();
                foreach($raw as $_raw){
                    // $this->into_debug(['nomor'=>6, 'modul'=>Route::getCurrentRoute()->getActionName(), 'url'=>$request->fullUrl()]);
                    $result = Attendance::select('att_date','att_timestamp','att_nik','att_type','att_long','att_lat','att_address','att_status','att_data_src','wg_id')->where('att_nik', '=', $_raw->att_nik)->where('att_date', '=', $_raw->att_date)->where('att_type', '=', 'Clock-out')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'desc' )->first();
                    // jika non reguler
                    $where = ['nik' => $_raw->att_nik, 'wg_id' => 1];
                    if(!Karyawan::where($where)->exists()){
                        $result = Attendance::select('att_date','att_timestamp','att_nik','att_type','att_long','att_lat','att_address','att_status','att_data_src','wg_id')->where('att_nik', '=', $_raw->att_nik)->where('att_wd_ot_id', '=', $_raw->att_wd_ot_id)->where('att_type', '=', 'Clock-out')->where('att_data_src', '=', $_att_data_src)->orderBy( 'id', 'desc' )->first();    
                    }
                    if(!empty($result)){
                        array_push($duration, ['att_date' => $_raw->att_date, 'att_nik' => $_raw->att_nik, 'att_data_src' => $_att_data_src, 'Clock-in' => $_raw->att_timestamp, 'Clock-out' => $result->att_timestamp]);
                    }
                }
                // end hitung durasi
            }
            // dd($att);
            if($request->download == true){
                // dd($request->all());
                return Excel::download(new AttendanceMultiSheetExport($att, $duration), 'Raw Data dan Duration '.Carbon::parse($date_from)->format('d-m-Y').' sd '.Carbon::parse($date_to)->format('d-m-Y').'.xlsx', \Maatwebsite\Excel\Excel::XLSX);
                // return response()->json( [ 'att' => $att ], 200 );
            }
        }else{
            $arr_att_type = array();
            $arr_att_status = array();
            $arr_att_data_src = array();
            $arr_perusahaan = array();
            $arr_wg_id = array();
        }
        // return response()->json( [ 'att' => $att ], 200 );
        // $this->into_debug(['nomor'=>7, 'modul'=>Route::getCurrentRoute()->getActionName(), 'url'=>$request->fullUrl()]);
        // dd($att);
        // end filter
        return view( 'attendance.index', compact( 'att', 'duration', 'att_type', 'att_status', 'att_data_src', 'perusahaan', 'wg_id', 'arr_att_type', 'arr_att_status', 'arr_att_data_src', 'date_from', 'date_to', 'arr_perusahaan', 'arr_wg_id' ) );
        // return view( 'attendance.index', compact( 'att', 'duration', 'att_type', 'att_status', 'att_data_src', 'wg_id', 'arr_att_type', 'arr_att_status', 'arr_att_data_src', 'date_from', 'date_to', 'arr_wg_id' ) );
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
    public function destroy($id)
    {
        //
    }
    
    public function into_debug(array $arr)
    {
        $data = New TrxDebug;
        $data->nomor = $arr['nomor'];
        $data->modul = $arr['modul'];
        $data->url = $arr['url'];
        $data->save();
    }
}
