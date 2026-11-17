<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\Overtime;
use Carbon\Carbon;
use App\Http\Controllers\Api\OvertimeApi;
use App\Http\Controllers\Api\MattaApi;

class TrxCiCoOvertimeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $nik = auth()->user()->nik;
        $karyawan = Karyawan::where('nik', $nik)->first() ?: Karyawan::first();
        
        $otApi = new OvertimeApi();
        $apiReq = new Request(['id' => 'maker', 'src' => 'ot_maker_by', 'nik' => $nik]);
        $response = $otApi->show($apiReq);
        $resJson = json_decode($response->getContent(), true);
        $arr = $resJson['data'] ?? [];

        $data = array();
        foreach($arr as $value){
            $_arr = array();
            $cico = $this->getCICO($nik, $value['id']);
            $_arr['id'] = $value['id'];
            $_arr['ot_title'] = $value['ot_title'];
            $_arr['ot_desc'] = $value['ot_desc'];
            $_arr['ot_type'] = $value['ot_type'];
            $_arr['ot_start_at'] = $value['ot_start_at'];
            $_arr['ot_end_at'] = $value['ot_end_at'];
            $_arr['ot_length'] = $value['ot_length'];
            $_arr['ot_approver_status'] = $value['ot_approver_status'];
            
            $_arr['n_clockin'] = $cico[0]['n_clockin'] ?? 0;
            $_arr['n_clockout'] = $cico[0]['n_clockout'] ?? 0;
            $_arr['tgl'] = $cico[1]['tgl'] ?? date('Y-m-d');
            $_arr['jam'] = $cico[1]['jam'] ?? date('H:i:s');
            $_arr['isClockin'] = $cico[2]['isClockin'] ?? false;
            $_arr['isClockout'] = $cico[2]['isClockout'] ?? false;
            $_arr['dtClockin'] = $cico[3]['dtClockin'] ?? null;
            $_arr['dtClockout'] = $cico[3]['dtClockout'] ?? null;
            array_push($data, $_arr);
        }
        return view('cico.overtime', compact('data', 'karyawan'));
    }

    public function getCICO($nik, $wd_id)
    {
        $otApi = new OvertimeApi();
        $apiReq = new Request(['id' => 'absensi', 'nik' => $nik, 'wd_id' => $wd_id]);
        $response = $otApi->show($apiReq);
        $data = json_decode($response->getContent(), true)['data'] ?? [];
        return $data;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $nik = auth()->user()->nik;
        $karyawan = Karyawan::where('nik', $nik)->first() ?: Karyawan::first();
        $data = Overtime::find($request->id);
        $_att_status = '';
        $clockin = $data ? $data->ot_start_at : date('Y-m-d H:i:s');
        if(Carbon::now() > Carbon::parse($clockin)){
            $_att_status = 'Late-in';
        }else{
            $_att_status = 'Book-in';
        }
        
        $att_type = $request->att_type;
        $att_data_src = 'Overtime';
        $att_status = '';
        if($att_type == 'Clock-out'){
            $att_status = 'Book-in';
        }else if($att_type == 'Clock-in'){
            $att_status = $_att_status;
        }

        $mattaApi = new MattaApi();
        $apiReq = new Request([
            'att_nik' => auth()->user()->nik,
            'att_type' => $att_type,
            'att_data_src' => $att_data_src,
            'att_wd_ot_id' => $request->id,
            'att_status' => $att_status,
            'att_long' => $request->att_long ?? 106.8227,
            'att_lat' => $request->att_lat ?? -6.2088,
            'att_address' => $request->att_address ?? 'Office'
        ]);
        $response = $mattaApi->store($apiReq);
        $arr = json_decode($response->getContent(), true);
        if($arr['success'] ?? false){
            return redirect('cico_ot')->with('msg', $arr['message']);
        }
        return redirect('cico_ot')->with('msg', 'Overtime attendance saved successfully');
    }

    public function create() {}
    public function show($id) {}
    public function edit($id) {}
    public function update(Request $request, $id) {}
    public function destroy($id) {}
}
