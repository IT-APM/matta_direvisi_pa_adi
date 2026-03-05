<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrxPelatihanSertifikat;
use App\Models\PelatihanJenis;

class TrxPelatihanSertifikatController extends Controller
{
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
        $status = 'edit';
        $id = (new OpenDataController)->decryptWithKey($id);
        $trx = TrxPelatihanSertifikat::find($id);
        $jenis = PelatihanJenis::orderBy('jenis', 'asc')->get();
        $int_ext = collect([
            'Internal' => 'Internal',
            'Eksternal' => 'Eksternal',
        ]);
        $kta = collect([
            'Ada' => 'Ada',
            'Tidak Ada' => 'Tidak Ada',
        ]);
        return view('formulir.pelatihan.upsert_sertifikat', compact('trx', 'status', 'jenis', 'int_ext', 'kta'));
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
        $trx = TrxPelatihanSertifikat::find($id);
        $trx->nama = $request->nama;
        $trx->nik = $request->nik;
        $trx->jenis = $request->jenis;
        $trx->int_ext = $request->int_ext;
        $trx->penerbit = $request->penerbit;
        $trx->kta = $request->kta;
        $trx->judul = $request->judul;
        $trx->lembaga = $request->lembaga;
        $trx->nomor_sertifikat = $request->nomor_sertifikat;
        $trx->biaya = $request->biaya;
        $trx->tanggal_mulai = $request->tanggal_mulai;
        $trx->tanggal_akhir = $request->tanggal_akhir;
        $trx->tanggal_sertifikat = $request->tanggal_sertifikat;
        $trx->tanggal_kadaluarsa = $request->tanggal_kadaluarsa;
        $trx->sertifikat_link = $request->sertifikat_link;
        $trx->update();
        // return redirect('trx')->with('msg', 'Data is saved successfully.');
        // return redirect()->to($request->back_url)->with( 'msg', 'Data is saved successfully.' );
        return redirect()->route('pelatihan.show', [(new OpenDataController)->encryptWithKey(4),'view' => (new OpenDataController)->encryptWithKey('list_certificate')])->with('msg', 'Data is saved successfully.');
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
