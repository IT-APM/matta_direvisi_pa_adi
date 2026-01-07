<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
// 
use App\Models\Propinsi;
use App\Models\PropinsiKota;
use App\Models\Negara;
use App\Models\Karyawan;
use App\Models\BpdBiaya;
use App\Models\BpdCoa;
use App\Models\BpdKelompok;
use App\Models\BpdNikPeran;
use App\Models\BpdNikPC;
use App\Models\BpdNonProyekBiayaDalamNegeri;
use App\Models\BpdNonProyekBiayaLuarNegeri;
use App\Models\BpdPeran;
use App\Models\BpdProyek;
use App\Models\BpdProyekAdmin;
use App\Models\BpdProyekUangSaku;
use App\Models\BpdProyekUangSakuKelompok;
use App\Models\BpdStatus;
// 
use App\Models\TrxBpdDetailBiaya;
use App\Models\TrxBpdDetailInfo;
use App\Models\TrxBpdLog;
use App\Models\TrxBpdMaster;
use App\Models\ToDoList;
use App\Models\ToDoListLog;
// 
use App\Models\MtrUtility;
use App\Mail\MattaRebornMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
// 
use Carbon\Carbon;
use DB;
use Elibyy\TCPDF\Facades\TCPDF;
use Excel;
// 

class MtrDataBpdProjectAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Gate::allows('hc_nkp')){
            return redirect()->route('error-403');
        }
        $no = 0;
        $trx = BpdProyek::orderBy( 'project_id', 'asc' )->get();
        return view( 'master.bpd.project_admin.index', compact( 'trx', 'no' ) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Gate::allows('hc_nkp')){
            return redirect()->route('error-403');
        }
        $status = 'create';
        return view( 'master.bpd.project_admin.upsert', compact( 'status' ) );
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
        $trx = New BpdProyek;
        $trx->project_id = $request->project_id;
        $trx->name = $request->name;
        $trx->divisi = $request->divisi;
        $trx->customer = $request->customer;
        $trx->group = $request->group;
        if($request->is_active == 'on'){
            $trx->is_active = 1;
        }else{
            $trx->is_active = 0;
        }
        $trx->save();
        return redirect( 'mtrdataprojectadmin' )->with( 'msg', 'Data is saved successfully.' );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(Gate::allows('hc_nkp')){
            return redirect()->route('error-403');
        }
        $id = Crypt::decrypt($id);
        $no = 0;
        $status = 'edit';
        $project = BpdProyek::find($id);
        $where = ['id_proyek' => $id];
        $trx = BpdProyekAdmin::where($where)->orderBy( 'id', 'asc' )->get();
        // dd($trx);
        return view( 'master.bpd.project_admin.index_pic', compact( 'project', 'trx', 'status', 'no' ) );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(Gate::allows('hc_nkp')){
            return redirect()->route('error-403');
        }
        $id = Crypt::decrypt($id);
        $status = 'edit';
        $trx = BpdProyek::find($id);
        return view( 'master.bpd.project_admin.upsert', compact( 'trx', 'status' ) );
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
        $trx = BpdProyek::find($id);
        $trx->project_id = $request->project_id;
        $trx->name = $request->name;
        $trx->divisi = $request->divisi;
        $trx->customer = $request->customer;
        $trx->group = $request->group;
        if($request->is_active == 'on'){
            $trx->is_active = 1;
        }else{
            $trx->is_active = 0;
        }
        $trx->update();
        return redirect( 'mtrdataprojectadmin' )->with( 'msg', 'Data is saved successfully.' );
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
        $trx = BpdProyek::find($id);
        $trx->delete();
        return redirect( 'mtrdataprojectadmin' )->with( 'msg', 'Data is deleted successfully.' );
    }
}
