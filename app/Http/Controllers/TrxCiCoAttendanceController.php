<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\WorkingDay;
use Carbon\Carbon;
use URL;
use App\Http\Controllers\Api\MattaApi;

class TrxCiCoAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->check() && auth()->user()->userrole === 'nhc') {
            return app(\App\Http\Controllers\MattaUserController::class)->dashboard();
        }
        $nik = auth()->user()->nik;
        $karyawan = Karyawan::where('nik', $nik)->first();
        if (!$karyawan) {
            $karyawan = Karyawan::where('email', auth()->user()->email)->first();
            if (!$karyawan) {
                $karyawan = new Karyawan([
                    'nik' => $nik,
                    'nama' => auth()->user()->name,
                    'email' => auth()->user()->email,
                    'wg_id' => 1,
                    'is_aktif' => 1
                ]);
            }
        }
        
        $mattaApi = new MattaApi();
        $isReguler = optional($karyawan->_wg)->is_reguler ?? 1;

        if ($isReguler == 1) {
            $arr = $mattaApi->getProfile($nik);
            $arr = json_decode(json_encode($arr), true);
            return view('cico.attendance', compact('arr', 'karyawan'));
        } else {
            $data = $mattaApi->getProfileGroup($nik);
            $data = json_decode(json_encode($data), true);
            return view('cico.attendance_group', compact('data', 'karyawan'));
        }
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
        $nik = auth()->user()->nik;
        $karyawan = Karyawan::where('nik', $nik)->first();
        if (!$karyawan) {
            $karyawan = Karyawan::where('email', auth()->user()->email)->first();
            if (!$karyawan) {
                $karyawan = new Karyawan([
                    'nik' => $nik,
                    'nama' => auth()->user()->name,
                    'email' => auth()->user()->email,
                    'wg_id' => 1,
                    'is_aktif' => 1
                ]);
            }
        }

        $isReguler = optional($karyawan->_wg)->is_reguler ?? 1;

        if ($isReguler == 1) {
            $_att_status = '';
            $clockin = date("Y-m-d") . ' ' . (optional($karyawan->_wg)->clockin ?? '08:30:00');
            if (Carbon::now() > Carbon::parse($clockin)) {
                $_att_status = 'Late-in';
            } else {
                $_att_status = 'Book-in';
            }
            
            $att_type = $request->att_type;
            $att_data_src = 'Regular';
            $att_status = '';
            if ($att_type == 'Clock-out' || $att_type == 'Check-in') {
                $att_status = 'Book-in';
            } else if ($att_type == 'Clock-in') {
                $att_status = $_att_status;
            }

            $mattaApi = new MattaApi();
            $apiReq = new Request([
                'att_nik' => $nik,
                'att_type' => $att_type,
                'att_data_src' => $att_data_src,
                'att_wd_ot_id' => 0,
                'att_status' => $att_status,
                'att_long' => !empty($request->att_long) ? $request->att_long : 106.8227,
                'att_lat' => !empty($request->att_lat) ? $request->att_lat : -6.2088,
                'att_address' => !empty($request->att_address) ? $request->att_address : 'Office'
            ]);

            $response = $mattaApi->store($apiReq);
            $resData = json_decode($response->getContent(), true);

            if ($resData['success'] ?? false) {
                return redirect('cico_att')->with('msg', $resData['message']);
            }
            return redirect('cico_att')->with('msg', 'Attendance recorded successfully');
        } else {
            $data = WorkingDay::find($request->id);
            $_att_status = '';
            $clockin = $data ? $data->clockin : date("Y-m-d H:i:s");
            if (Carbon::now() > Carbon::parse($clockin)) {
                $_att_status = 'Late-in';
            } else {
                $_att_status = 'Book-in';
            }

            $att_type = $request->att_type;
            $att_data_src = 'Regular';
            $att_status = '';
            if ($att_type == 'Clock-out' || $att_type == 'Check-in') {
                $att_status = 'Book-in';
            } else if ($att_type == 'Clock-in') {
                $att_status = $_att_status;
            }

            $mattaApi = new MattaApi();
            $apiReq = new Request([
                'att_nik' => $nik,
                'att_type' => $att_type,
                'att_data_src' => $att_data_src,
                'att_wd_ot_id' => $request->id ?? 0,
                'att_status' => $att_status,
                'att_long' => !empty($request->att_long) ? $request->att_long : 106.8227,
                'att_lat' => !empty($request->att_lat) ? $request->att_lat : -6.2088,
                'att_address' => !empty($request->att_address) ? $request->att_address : 'Office'
            ]);

            $response = $mattaApi->store($apiReq);
            $resData = json_decode($response->getContent(), true);

            if ($resData['success'] ?? false) {
                return redirect('cico_att')->with('msg', $resData['message']);
            }
            return redirect('cico_att')->with('msg', 'Attendance recorded successfully');
        }
    }

    public function show($id) {}
    public function edit($id) {}
    public function update(Request $request, $id) {}
    public function destroy($id) {}
}
