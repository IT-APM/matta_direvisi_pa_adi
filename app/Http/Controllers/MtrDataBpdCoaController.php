<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BpdCoa;

class MtrDataBpdCoaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $no = 0;
        $trx = BpdCoa::orderBy( 'account', 'asc' )->get();
        return view( 'master.bpd.coa.index', compact( 'trx', 'no' ) );
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
        return view( 'master.bpd.coa.upsert', compact( 'status' ) );
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
        $trx = New BpdCoa;
        $trx->account = $request->account;
        $trx->name = $request->name;
        $trx->type = $request->type;
        $trx->category = $request->category;
        $trx->divisi = $request->divisi;
        $trx->save();
        return redirect( 'mtrdatacoabpd' )->with( 'msg', 'Data is saved successfully.' );
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
        $status = 'edit';
        $trx = BpdCoa::find( $id );
        return view( 'master.bpd.coa.upsert', compact( 'trx', 'status' ) );
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
        $trx = BpdCoa::find( $id );
        $trx->account = $request->account;
        $trx->name = $request->name;
        $trx->type = $request->type;
        $trx->category = $request->category;
        $trx->divisi = $request->divisi;
        $trx->update();
        return redirect( 'mtrdatacoabpd' )->with( 'msg', 'Data is saved successfully.' );
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
        $trx = BpdCoa::find( $id );
        $trx->delete();
        return redirect( 'mtrdatacoabpd' )->with( 'msg', 'Data is deleted successfully.' );
    }
}
