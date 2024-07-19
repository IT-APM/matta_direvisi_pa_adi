<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use App\Models\Divisi;
use App\Models\Departemen;
use App\Models\SubDepartemen;
use Illuminate\Http\Request;
// 
use App\Models\TrxBpdDetailBiaya;
use App\Models\TrxBpdDetailInfo;
use App\Models\TrxBpdMaster;
use App\Models\BpdProyekUangSaku;
use App\Models\BpdProyekUangSakuKelompok;
use App\Models\BpdNonProyekBiayaDalamNegeri;
use App\Models\BpdNonProyekBiayaLuarNegeri;
use DB;
// 
class OpenDataController extends Controller
{
    public function getKaryawanPerusahaan($id){
        $data = DB::select("SELECT COUNT(a.nik) AS jumlah, b.nama_singkat AS nama FROM mtr_tbl_karyawan AS a LEFT JOIN mtr_tbl_perusahaan AS b ON a.kode_perusahaan=b.id WHERE a.is_aktif=1 AND a.nik NOT IN ('IT.BM','Hco.all') GROUP BY a.kode_perusahaan");
        $perusahaan = array();
        $karyawan = array();
        $total = 0;
        foreach($data as $_data){
            array_push($perusahaan, $_data->nama);
            array_push($karyawan, $_data->jumlah);
            $total += $_data->jumlah;
        }
        return response()->json(['perusahaan'=>$perusahaan, 'karyawan'=>$karyawan, 'total'=>$total]);
    }

    public function getDivisi($id) {
        $data = Divisi::whereIn('kode_perusahaan', explode(',', $id))->get();
        return json_decode($data, true);
    }

    public function getDepartemen($id) {
        $data = Departemen::whereIn('kode_divisi', explode(',', $id))->get();
        return json_decode($data, true);
    }

    public function getSubDepartemen($id) {
        $data = SubDepartemen::whereIn('kode_departemen', explode(',', $id))->get();
        return json_decode($data, true);
    }

    public static function getUangSaku($id) {
        $biaya = TrxBpdDetailBiaya::find($id);
        $trx = TrxBpdMaster::find($biaya->id_trx);
        $info = TrxBpdDetailInfo::find($biaya->id_info);
        $nominal = array();
        $tujuan1 = 0;
        if(!empty($info->tujuan1)){
            $tujuan1 = $info->tujuan1;
        }else{
            array_push($nominal, $biaya->nominal);
        }
        $is_project = $trx->is_project;
        $luar_dalam = $trx->luar_dalam;
        $project_id = $trx->project_id;
        $kelompok_id = $trx->kelompok_id;
        // project
        if($is_project == 1){
            // ca_dalam
            if($luar_dalam == 'ca_dalam' || $luar_dalam == 'reimburse'){
                // foreach ($trxDetailInfo as $data){
                    // $hari = Carbon::parse($data->tanggal_mulai)->diffInDays(Carbon::parse($data->tanggal_akhir))+1;
                    // $hari = $data->hari;
                    // dd($hari);
                    // jika Project IOH dan ada id_kelompok nya
                    $_where = ['id_proyek'=>$project_id, 'id_kelompok'=>$kelompok_id];
                    $uang_saku = BpdProyekUangSakuKelompok::where($_where)->get();
                    if(count($uang_saku)>0){
                        // $_uang_saku = $uang_saku->uangsaku;
                        foreach ($uang_saku as $data){
                            array_push($nominal, $data->uangsaku);
                        }
                        // 1 = 500240 = BIAYA UANG SAKU = Project
                        // 2 = 500240 = BIAYA UANG SAKU = Support
                        // $biaya = 1;
                        // $satuan = 'IDR';
                        // $bpd_biaya = ['id_info'=>$data->id, 'jenis'=>$jenis, 'id'=>$id, 'biaya'=>array($biaya), 'satuan'=>array($satuan), 'nominal'=>array($_uang_saku), 'jumlah'=>array($hari), 'is_approve'=>$is_approve];
                        // $this->insertBpdBiaya($bpd_biaya);
                    }
                    // jika bukan Project IOH atau tidak ada id_kelompok nya
                    else{
                        // pecah-pecah jumlah hari sesuai dengan batasan jumlah hari berdasarkan kelompok
                        // $arr_hari['hari'] = array();
                        // $arr_hari['kelompok'] = array();
                        // // jumlah hari dari 1 sd 7 hari
                        // if($hari < 8){
                        //     array_push($arr_hari['hari'], $hari);
                        //     array_push($arr_hari['kelompok'], 7);
                        // }
                        // // jumlah hari dari 8 sd 30 hari
                        // else if($hari > 7 && $hari < 31){
                        //     array_push($arr_hari['hari'], 7);
                        //     array_push($arr_hari['kelompok'], 7);
                        //     array_push($arr_hari['hari'], $hari-7);
                        //     array_push($arr_hari['kelompok'], 30);
                        // }
                        // // jumlah hari lebih dari 30 hari
                        // else if($hari > 30){
                        //     array_push($arr_hari['hari'], 7);
                        //     array_push($arr_hari['kelompok'], 7);
                        //     array_push($arr_hari['hari'], 23);
                        //     array_push($arr_hari['kelompok'], 30);
                        //     array_push($arr_hari['hari'], $hari-30);
                        //     array_push($arr_hari['kelompok'], 365);
                        // }
                        // 
                        // for ($x = 0; $x < count($arr_hari['hari']); $x++){
                            $_where = ['id_propinsi'=>$tujuan1];
                            $uang_saku = BpdProyekUangSaku::where($_where)->get();
                            // dd($uang_saku);
                            // 1 = Kelompok I
                            if($kelompok_id == 1){
                                // $_uang_saku = $uang_saku->uangsaku_1;
                                foreach ($uang_saku as $data){
                                    array_push($nominal, $data->uangsaku_1);
                                }
                            }
                            // 2 = Kelompok II
                            else if($kelompok_id == 2){
                                // $_uang_saku = $uang_saku->uangsaku_2;
                                foreach ($uang_saku as $data){
                                    array_push($nominal, $data->uangsaku_2);
                                }
                            }
                            // 3 = Kelompok III
                            else if($kelompok_id == 3){
                                // $_uang_saku = $uang_saku->uangsaku_3;
                                foreach ($uang_saku as $data){
                                    array_push($nominal, $data->uangsaku_3);
                                }
                            }
                            // 4 = Kelompok IV
                            else if($kelompok_id == 4){
                                // $_uang_saku = $uang_saku->uangsaku_4;
                                foreach ($uang_saku as $data){
                                    array_push($nominal, $data->uangsaku_4);
                                }
                            }
                            // 5 = Kelompok V
                            else if($kelompok_id == 5){
                                // $_uang_saku = $uang_saku->uangsaku_5;
                                foreach ($uang_saku as $data){
                                    array_push($nominal, $data->uangsaku_5);
                                }
                            }
                            // 1 = 500240 = BIAYA UANG SAKU = Project
                            // 2 = 500240 = BIAYA UANG SAKU = Support
                            // $biaya = 1;
                            // $satuan = 'IDR';
                            // $bpd_biaya = ['id_info'=>$data->id, 'jenis'=>$jenis, 'id'=>$id, 'biaya'=>array($biaya), 'satuan'=>array($satuan), 'nominal'=>array($_uang_saku), 'jumlah'=>array($arr_hari['hari'][$x]), 'is_approve'=>$is_approve];
                            // $this->insertBpdBiaya($bpd_biaya);
                        // }
                    }
                // }
            }
            // ca_luar
            else if($luar_dalam == 'ca_luar'){
                // NA
            }
        }
        // non project
        else if($is_project == 0){
            // 
            if($luar_dalam == 'ca_dalam' || $luar_dalam == 'reimburse'){
                // foreach ($trxDetailInfo as $data){
                    // $hari = Carbon::parse($data->tanggal_mulai)->diffInDays(Carbon::parse($data->tanggal_akhir))+1;
                    // $uang_saku = BpdNonProyekBiayaDalamNegeri::find(1);
                    $_where = ['id_biaya'=>1];
                    $uang_saku = BpdNonProyekBiayaDalamNegeri::where($_where)->get();
                    // 6 = Kelompok I
                    if($kelompok_id == 6){
                        // $_uang_saku = $uang_saku->kelompok_1;
                        foreach ($uang_saku as $data){
                            array_push($nominal, $data->kelompok_1);
                        }
                    }
                    // 7 = Kelompok II
                    else if($kelompok_id == 7){
                        // $_uang_saku = $uang_saku->kelompok_2;
                        foreach ($uang_saku as $data){
                            array_push($nominal, $data->kelompok_2);
                        }
                    }
                    // 8 = Kelompok III
                    else if($kelompok_id == 8){
                        // $_uang_saku = $uang_saku->kelompok_3;
                        foreach ($uang_saku as $data){
                            array_push($nominal, $data->kelompok_3);
                        }
                    }
                    // 9 = Kelompok IV
                    else if($kelompok_id == 9){
                        // $_uang_saku = $uang_saku->kelompok_4;
                        foreach ($uang_saku as $data){
                            array_push($nominal, $data->kelompok_4);
                        }
                    }
                    // 10 = Kelompok V
                    else if($kelompok_id == 10){
                        // $_uang_saku = $uang_saku->kelompok_5;
                        foreach ($uang_saku as $data){
                            array_push($nominal, $data->kelompok_5);
                        }
                    }
                    // 11 = Kelompok VI
                    else if($kelompok_id == 11){
                        // $_uang_saku = $uang_saku->kelompok_6;
                        foreach ($uang_saku as $data){
                            array_push($nominal, $data->kelompok_6);
                        }
                    }
                    // 1 = 500240 = BIAYA UANG SAKU = Project
                    // 2 = 500240 = BIAYA UANG SAKU = Support
                    // $biaya = 2;
                    // $satuan = 'IDR';
                    // $bpd_biaya = ['id_info'=>$data->id, 'jenis'=>$jenis, 'id'=>$id, 'biaya'=>array($biaya), 'satuan'=>array($satuan), 'nominal'=>array($_uang_saku), 'jumlah'=>array($hari), 'is_approve'=>$is_approve];
                    // $this->insertBpdBiaya($bpd_biaya);
                // }
            }
            // ca_luar
            else if($luar_dalam == 'ca_luar'){
                // foreach ($trxDetailInfo as $data){
                    // $hari = Carbon::parse($data->tanggal_mulai)->diffInDays(Carbon::parse($data->tanggal_akhir))+1;
                    $where = ['id_negara'=>$tujuan1, 'id_biaya'=>1];
                    $uang_saku = BpdNonProyekBiayaLuarNegeri::where($where)->get();
                    // 6 = Kelompok I
                    if($kelompok_id == 6){
                        // $_uang_saku = $uang_saku->kelompok_1;
                        foreach ($uang_saku as $data){
                            array_push($nominal, $data->kelompok_1);
                        }
                    }
                    // 7 = Kelompok II
                    else if($kelompok_id == 7){
                        // $_uang_saku = $uang_saku->kelompok_2;
                        foreach ($uang_saku as $data){
                            array_push($nominal, $data->kelompok_2);
                        }
                    }
                    // 8 = Kelompok III
                    else if($kelompok_id == 8){
                        // $_uang_saku = $uang_saku->kelompok_3;
                        foreach ($uang_saku as $data){
                            array_push($nominal, $data->kelompok_3);
                        }
                    }
                    // 9 = Kelompok IV
                    else if($kelompok_id == 9){
                        // $_uang_saku = $uang_saku->kelompok_4;
                        foreach ($uang_saku as $data){
                            array_push($nominal, $data->kelompok_4);
                        }
                    }
                    // 10 = Kelompok V
                    else if($kelompok_id == 10){
                        // $_uang_saku = $uang_saku->kelompok_5;
                        foreach ($uang_saku as $data){
                            array_push($nominal, $data->kelompok_5);
                        }
                    }
                    // 11 = Kelompok VI
                    else if($kelompok_id == 11){
                        // $_uang_saku = $uang_saku->kelompok_6;
                        foreach ($uang_saku as $data){
                            array_push($nominal, $data->kelompok_6);
                        }
                    }
                    // 1 = 500240 = BIAYA UANG SAKU = Project
                    // 2 = 500240 = BIAYA UANG SAKU = Support
                    // $biaya = 2;
                    // $satuan = 'USD';
                    // $bpd_biaya = ['id_info'=>$data->id, 'jenis'=>$jenis, 'id'=>$id, 'biaya'=>array($biaya), 'satuan'=>array($satuan), 'nominal'=>array($_uang_saku), 'jumlah'=>array($hari), 'is_approve'=>$is_approve];
                    // $this->insertBpdBiaya($bpd_biaya);
                // }
            }
        }
        // $data = SubDepartemen::whereIn('kode_departemen', explode(',', $id))->get();
        return $nominal;
    }
}
