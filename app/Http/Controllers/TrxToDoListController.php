<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ToDoList;

class TrxToDoListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // kebutuhan0
        $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.kebutuhan0'];
        $trxKebutuhan = ToDoList::where($where)->orderBy('id', 'desc')->get();
        // cuti0
        $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.cuti0'];
        $trxCuti = ToDoList::where($where)->orderBy('id', 'desc')->get();
        // izin0
        $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.izin0'];
        $trxIzin = ToDoList::where($where)->orderBy('id', 'desc')->get();
        // nonaktif0
        $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.nonaktif0'];
        $trxNonAktif = ToDoList::where($where)->orderBy('id', 'desc')->get();
        // pelatihan0
        $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.pelatihan0'];
        $trxPelatihan = ToDoList::where($where)->orderBy('id', 'desc')->get();
        // penilaian0 dan penilaian0_outsourcing
        // $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.penilaian0'];
        $where = ['nik' => auth()->user()->nik];
        $trxPenilaian = ToDoList::where($where)->whereIn('filename', ['emails.penilaian0', 'emails.penilaian0_outsourcing'])->orderBy('id', 'desc')->get();
        // reimburse0
        $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.reimburse0'];
        $trxReimburse = ToDoList::where($where)->orderBy('id', 'desc')->get();
        // rekomendasi0
        $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.rekomendasi0'];
        $trxRekomendasi = ToDoList::where($where)->orderBy('id', 'desc')->get();
        // lembur0
        $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.lembur0'];
        $trxOvertime = ToDoList::where($where)->orderBy('id', 'desc')->get();
        // bpd0
        // $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.bpd0'];
        $where = ['nik' => auth()->user()->nik];
        $trxBpd = ToDoList::where($where)->whereIn('filename', ['emails.bpd0', 'emails.bpd_done'])->orderBy('id', 'desc')->get();
        // sakit0
        $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.sakit0'];
        $trxSakit = ToDoList::where($where)->orderBy('id', 'desc')->get();
        // 
        return view('todolist.index', compact('trxKebutuhan','trxCuti','trxIzin','trxNonAktif','trxPelatihan','trxPenilaian','trxReimburse','trxRekomendasi','trxOvertime','trxBpd','trxSakit'));
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
        //
        // return response()->json(['trx' => $trx]);die;
        $no= 0;
        $filename = $request->filename;
        // kebutuhan0
        if($filename == 'emails.kebutuhan0'){
            $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.kebutuhan0'];
            $trx = ToDoList::where($where)->orderBy('id', 'desc')->get();
            return view('todolist.list', compact('no', 'trx'));
        }
        // penilaian0
        else if($filename == 'emails.penilaian0'){
            // $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.penilaian0'];
            $where = ['nik' => auth()->user()->nik];
            $trx = ToDoList::where($where)->whereIn('filename', ['emails.penilaian0', 'emails.penilaian0_outsourcing'])->orderBy('id', 'desc')->get();
            return view('todolist.list', compact('no', 'trx'));
        }
        // rekomendasi0
        else if($filename == 'emails.rekomendasi0'){
            $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.rekomendasi0'];
            $trx = ToDoList::where($where)->orderBy('id', 'desc')->get();
            return view('todolist.list', compact('no', 'trx'));
        }
        // nonaktif0
        else if($filename == 'emails.nonaktif0'){
            $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.nonaktif0'];
            $trx = ToDoList::where($where)->orderBy('id', 'desc')->get();
            return view('todolist.list', compact('no', 'trx'));
        }
        // reimburse0
        else if($filename == 'emails.reimburse0'){
            $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.reimburse0'];
            $trx = ToDoList::where($where)->orderBy('id', 'desc')->get();
            return view('todolist.list', compact('no', 'trx'));
        }
        // cuti0
        else if($filename == 'emails.cuti0'){
            $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.cuti0'];
            $trx = ToDoList::where($where)->orderBy('id', 'desc')->get();
            return view('todolist.list', compact('no', 'trx'));
        }
        // izin0
        else if($filename == 'emails.izin0'){
            $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.izin0'];
            $trx = ToDoList::where($where)->orderBy('id', 'desc')->get();
            return view('todolist.list', compact('no', 'trx'));
        }
        // pelatihan0
        else if($filename == 'emails.pelatihan0'){
            $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.pelatihan0'];
            $trx = ToDoList::where($where)->orderBy('id', 'desc')->get();
            return view('todolist.list', compact('no', 'trx'));
        }
        // lembur0
        else if($filename == 'emails.lembur0'){
            $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.lembur0'];
            $trx = ToDoList::where($where)->orderBy('id', 'desc')->get();
            return view('todolist.list', compact('no', 'trx'));
        }
        // bpd0
        else if($filename == 'emails.bpd0'){
            // $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.bpd0'];
            $where = ['nik' => auth()->user()->nik];
            $trx = ToDoList::where($where)->whereIn('filename', ['emails.bpd0', 'emails.bpd_done'])->orderBy('id', 'desc')->get();
            return view('todolist.list', compact('no', 'trx'));
        }
        // sakit0
        else if($filename == 'emails.sakit0'){
            $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.sakit0'];
            $trx = ToDoList::where($where)->orderBy('id', 'desc')->get();
            return view('todolist.list', compact('no', 'trx'));
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
