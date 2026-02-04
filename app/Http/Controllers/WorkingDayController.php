<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use App\Models\Karyawan;
use App\Models\WorkingDay;
use App\Models\WorkingGroup;

class WorkingDayController extends Controller
{
    
    public function deleteData($nik, $tanggal_awal, $tanggal_akhir){
        WorkingDay::where('nik', $nik)->whereBetween('working_date', [$tanggal_awal, $tanggal_akhir])->delete();
    }
    
    public function insertData($wg_id, $shift, $working_date, $nik, $clockin, $clockout, $end_clockout, $start_clockin){
        $trx = New WorkingDay;
        $trx->wg_id = $wg_id;
        $trx->shift = $shift;
        $trx->nik = $nik;
        $trx->working_date = $working_date;
        $trx->clockin = $clockin;
        $trx->clockout = $clockout;
        $trx->start_clockin = $start_clockin;
        $trx->end_clockin = $clockout;
        $trx->start_clockout = $clockout;
        $trx->end_clockout = $end_clockout;
        $trx->save();
        return $trx;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 
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
        if(Gate::allows('hc_nkp')){
            return redirect()->route('error-403');
        }
        $req = $request->req;
        $id = $request->id;
        if($id == 21 && $req == 'add'){
            $wg = WorkingGroup::find($id);
            $where = ['wg_id' => $id, 'is_aktif' => 1];
            $nik = Karyawan::where($where)->pluck('nik')->toArray();
            if(count($nik) == 0){
                return redirect()->route('working.show', [$id, 'req'=>'view'])->with('error', 'No data is saved successfully.');
            }
            $start = Carbon::parse($request->tanggal_awal);
            $end = Carbon::parse($request->tanggal_akhir);
            foreach($nik as $_nik){
                $this->deleteData($_nik, $start, $end);
                while ($start->lte($end)) {
                    $tgl = $start->format('Y-m-d');
                    $this->insertData($wg->id, 1, $tgl, $_nik, $tgl.' '.$wg->clockin, $tgl.' '.$wg->clockout, $tgl.' '.$wg->end_clockout, $tgl.' '.$wg->start_clockin);
                    $start->addDay();
                }
                $start = Carbon::parse($request->start);
                $end = Carbon::parse($request->end);
            }
            return redirect()->route('working.show', [$id, 'req'=>'view'])->with('msg', 'Data is saved successfully.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if(Gate::allows('hc_nkp')){
            return redirect()->route('error-403');
        }
        $req = $request->req;
        // 21 = Reguler Freelance
        if($id == 21 && $req == 'view'){
            $tahun = Carbon::parse(now())->format('Y');
            $where = ['wg_id' => $id];
            $trx = WorkingDay::where($where)->whereYear('working_date', $tahun)->orderBy( 'working_date', 'desc' )->orderBy( 'clockin', 'asc' )->get();
            // filter parameter
            $_arr_nik_karyawan = WorkingDay::where($where)->groupBy('nik')->pluck('nik')->toArray();
            $arr_nik_karyawan = array();
            $date_from = '';
            $date_to = '';
            foreach ($_arr_nik_karyawan as $data){
                array_push($arr_nik_karyawan, $data);
            }
            $nik_karyawan = Karyawan::select('nik','nama')->whereIn('nik', $arr_nik_karyawan)->orderBy('nama', 'asc')->get();
            // start filter
            if($request->filter == true){
                $_nik_karyawan = $request->input('nik_karyawan', []);
                $date_from = $request->date_from;
                $date_to = $request->date_to;
                // 
                if(count($_nik_karyawan) > 0){
                    $arr_nik_karyawan = $_nik_karyawan;
                }
                else{
                    $arr_nik_karyawan = array();
                }
                $trx = WorkingDay::where($where)->whereIn('nik', $arr_nik_karyawan)->whereBetween('working_date', [$date_from, $date_to])->orderBy( 'working_date', 'desc' )->orderBy( 'clockin', 'asc' )->get();
            }else{
                $arr_nik_karyawan = array();
            }
            // end filter
            return view('master.jadwal.freelance.index', compact('trx', 'date_from', 'date_to', 'nik_karyawan', 'arr_nik_karyawan'));
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
