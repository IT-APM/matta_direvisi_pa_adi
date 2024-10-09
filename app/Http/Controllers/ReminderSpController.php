<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MtrReminderSp;
use App\Models\TrxReminderSp;
use App\Models\TrxReminderSpSend;
use App\Models\MtrUtility;
use App\Mail\MattaRebornMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use DB;
use App\Models\Karyawan;
use App\Models\WorkingDay;

class ReminderSpController extends Controller
{
    // reminder_sp
    public function reminder_sp($id)
    {
        // dijalankan hanya di tanggal 01 setiap bulannya
        if(explode('-', Carbon::now()->format('Y-m-d'))[2] == '01'){
            $date_from = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
            $date_to = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
            // dd($date_from, $date_to);
            // pengecualian nik
            $kecuali = explode(',', MtrReminderSp::find(2)->isi);
            array_push($kecuali, 'Hco.all', 'IT.BM');
            $where = ['is_aktif' => 1];
            $karyawan = Karyawan::where($where)->whereNotIn('nik', $kecuali)->where('nik', 'not like', 'CS%')->get();
            $arr_date = (new RecapAttendanceController)->dateRange($date_from, $date_to);
            // pengecualian karena pada tanggal ini terjadi suspend domain alita.id
            $arr_date = array_values(array_diff($arr_date, ['2024-09-23']));
            // dd($arr_date);
            $trx = [];
            foreach ($karyawan as $_karyawan) {
                $nik = $_karyawan->nik;
                $arr_base = [];
                $arr_clock = [];
                $tanggal_bergabung = $_karyawan->tanggal_bergabung;
                // cek apakah karyawan berjenis kerja reguler
                if ($_karyawan->_wg->is_reguler == 1) {
                    foreach ($arr_date as $data) {
                        if (!(new RecapAttendanceController)->isWeekend($data)) {
                            if (!(new RecapAttendanceController)->isHoliday($data)) {
                                if (!(new RecapAttendanceController)->isCuti($nik, $data)) {
                                    array_push($arr_base, $data);
                                }
                                if ((new RecapAttendanceController)->isSakit($nik, $data)) {
                                    if (($key = array_search($data, $arr_base)) !== false) {
                                        unset($arr_base[$key]);
                                    }
                                }
                                if ($tanggal_bergabung > $data) {
                                    if (($key = array_search($data, $arr_base)) !== false) {
                                        unset($arr_base[$key]);
                                    }
                                }
                            }
                        }
                    }
                }
                // karyawan non reguler dengan jadwal kerja sesuai working group nya
                else {
                    $where = ['nik' => $nik];
                    $data = WorkingDay::where($where)->whereIn('working_date', $arr_date)->get();
                    foreach ($data as $_data) {
                        if (!(new RecapAttendanceController)->isCuti($nik, $_data->working_date)) {
                            array_push($arr_base, $_data->working_date);
                        }
                        if ((new RecapAttendanceController)->isSakit($nik, $_data->working_date)) {
                            if (($key = array_search($_data->working_date, $arr_base)) !== false) {
                                unset($arr_base[$key]);
                            }
                        }
                        if ($tanggal_bergabung > $data) {
                            if (($key = array_search($data, $arr_base)) !== false) {
                                unset($arr_base[$key]);
                            }
                        }
                    }
                }
                foreach ($arr_base as $data) {
                    if ((new RecapAttendanceController)->isClockin($nik, $data)) {
                        array_push($arr_clock, $data);
                    }
                }
                $persentase = 0;
                if (count($arr_base) > 0) {
                    $persentase = count($arr_clock) / count($arr_base);
                }
                $nik_property = $this->getNikProperty($nik);
                // tangkap jika kurang dari 80% dan ada hari kehadirannya
                if(round($persentase*100) < MtrReminderSp::find(1)->isi && count($arr_base) > 0){
                    $remindersp = ['date_from' => $date_from, 'date_to' => $date_to, 'nik' => $nik, 'nik_property' => $nik_property, 'arr_base' => count($arr_base), 'arr_clock' => count($arr_clock), 'persentase' => $persentase * 100];
                    array_push($trx, $remindersp);
                    $this->insertTrxReminderSp($remindersp);
                }
            }
            // dd($trx);
            dd('DONE');
        }else{
            dd('NA');
        }
    }

    public function insertTrxReminderSp($trx)
    {
        $data = new TrxReminderSp;
        $data->date_from = $trx['date_from'];
        $data->date_to = $trx['date_to'];
        $data->nik = $trx['nik'];
        $data->nik_property = $trx['nik_property'];
        $data->arr_base = $trx['arr_base'];
        $data->arr_clock = $trx['arr_clock'];
        $data->persentase = $trx['persentase'];
        $data->save();
    }

    public function insertTrxReminderSpSend($trx, $number, $type, $body_email)
    {
        $data = new TrxReminderSpSend;
        $data->number = $number;
        $data->type = $type;
        $data->date_from = $trx['date_from'];
        $data->date_to = $trx['date_to'];
        $data->nik = $trx['nik'];
        $data->nik_property = $trx['nik_property'];
        $data->arr_base = $trx['arr_base'];
        $data->arr_clock = $trx['arr_clock'];
        $data->persentase = $trx['persentase'];
        $data->body_email = $body_email;
        $data->save();
    }

    public function getNikProperty($nik)
    {
        $data = DB::select(DB::raw("SELECT a.nik,a.nama,a.layer1,a.layer2,a.layer3,a.kode_status,b.status_karyawan,a.email,a.wg_id,c.wg_name,a.kode_perusahaan,d.nama AS perusahaan,a.kode_divisi,e.nama AS divisi,a.kode_departemen,f.nama AS departemen,a.kode_sub_departemen,g.nama AS sub_departemen,a.kode_level,h.level,a.kode_jabatan,i.jabatan,a.tanggal_mulai,a.tanggal_bergabung,a.tanggal_berakhir,a.tanggal_karyawan_tetap FROM mtr_tbl_karyawan AS a LEFT JOIN mtr_tbl_status_karyawan AS b ON b.id=a.kode_status LEFT JOIN mtr_tbl_working_group AS c ON c.id=a.wg_id LEFT JOIN mtr_tbl_perusahaan AS d ON d.id=a.kode_perusahaan LEFT JOIN mtr_tbl_divisi AS e ON e.id=a.kode_divisi LEFT JOIN mtr_tbl_departemen AS f ON f.id=a.kode_departemen LEFT JOIN mtr_tbl_sub_departemen AS g ON g.id=a.kode_sub_departemen LEFT JOIN mtr_tbl_level AS h ON h.id=a.kode_level LEFT JOIN mtr_tbl_jabatan AS i ON i.id=a.kode_jabatan WHERE a.nik='".$nik."'"));
        return json_encode($data[0]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // start uji coba
        // $trx = TrxReminderSp::find(3);
        // $teguran_ke = 'Pertama';
        // $history_email = '';
        // $mailData['title'] = 'Surat Teguran '.$teguran_ke;
        // $mailData['filename'] = 'emails.reminder1';
        // // $mailData['filename'] = 'emails.warning1';
        // $mailData['to'] = 'riznaky.roosmanita@alita.id';
        // // $mailData['to'] = 'abdulhadi@tigaluhursejahtera.com';
        // $data = ['trx' => $trx, 'teguran_ke' => $teguran_ke, 'history_email' => $history_email, 'datetime' => Carbon::now()];
        // $mailData['data'] = $data;
        // $ccEmails = ['abdulhadi@tigaluhursejahtera.com','hco.all@alita.id','hadidisini@yahoo.com'];
        // // $ccEmails = ['hadidisini@yahoo.com'];
        // $mail = Mail::to($mailData['to'])->cc($ccEmails)->send(new MattaRebornMail($mailData));
        // dd($mail);
        // end uji coba
        //
        $trx = TrxReminderSp::orderBy( 'persentase', 'asc' )->get();
        return view( 'reminder_sp.index', compact( 'trx' ) );
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
        $submit = $request->all();
        // dd($submit);
        foreach($submit as $_id => $value){
            if(str_starts_with($_id, 'submit')){
                if(empty($request->reminder_sp)){
                    if($value == 'execute'){
                        return redirect( 'reminder_sp' )->with( 'msg', 'No Data is executed.' );
                    }else if($value == 'delete'){
                        return redirect( 'reminder_sp' )->with( 'msg', 'No Data is deleted.' );
                    }
                }else{
                    if($value == 'execute'){
                        $reminder_sp = $request->reminder_sp;
                        foreach($reminder_sp as $_reminder_sp){
                            $trx = TrxReminderSp::find($_reminder_sp);
                            $nik_property = json_decode($trx->nik_property, true);
                            // check history
                            $teguran_ke = 'Pertama';
                            $number = 1;
                            $type = 'reminder';
                            $history_email = '';
                            // check nik yang sama pada 1 bulan sebelumnya
                            $where = ['nik' => $trx->nik, 'date_from' => Carbon::parse($trx->date_from)->modify('-1 month')->format('Y-m-d')];
                            $trxSend = TrxReminderSpSend::where($where)->orderBy( 'id', 'desc' )->first();
                            if(!empty($trxSend)){
                                $history_email = $trxSend->body_email;
                                if($trxSend->type == 'reminder'){
                                    if($trxSend->number == 1){
                                        $teguran_ke = 'Kedua';
                                        $number = 2;
                                    }elseif($trxSend->number == 2){
                                        $teguran_ke = 'Ketiga';
                                        $number = 3;
                                    }elseif($trxSend->number == 3){
                                        $teguran_ke = 'Keempat';
                                        $number = 4;
                                    }elseif($trxSend->number == 4){
                                        $teguran_ke = 'Kelima';
                                        $number = 5;
                                    }elseif($trxSend->number == 5){
                                        $teguran_ke = 'Pertama';
                                        $number = 1;
                                        $type = 'warning';
                                    }
                                }elseif($trxSend->type == 'warning'){
                                    $type = 'warning';
                                    if($trxSend->number == 1){
                                        $teguran_ke = 'Kedua';
                                        $number = 2;
                                    }elseif($trxSend->number == 2){
                                        $teguran_ke = 'Ketiga';
                                        $number = 3;
                                    }
                                }
                            }
                            // start
                            // parameter yang dipassing ke sendemail
                            $mailData['title'] = 'Surat Teguran '.$teguran_ke;
                            $mailData['filename'] = 'emails.reminder1';
                            if($type == 'warning'){
                                $mailData['title'] = 'Surat Peringatan '.$teguran_ke;
                                $mailData['filename'] = 'emails.warning1';
                            }
                            $mailData['to'] = $nik_property['email'];
                            $data = ['trx' => $trx, 'teguran_ke' => $teguran_ke, 'history_email' => $history_email, 'datetime' => Carbon::now()];
                            $mailData['data'] = $data;
                            $ccEmails = explode(',', MtrUtility::find(11)->informasi);
                            $where = ['nik' => $nik_property['layer1']];
                            // cc email ke layer1
                            $layer1 = Karyawan::where($where)->first();
                            array_push($ccEmails, $layer1->email);
                            if($type == 'reminder'){
                                if(in_array($number, [4, 5])){
                                    // cc email ke layer2
                                    $where = ['nik' => $nik_property['layer2']];
                                    $layer2 = Karyawan::where($where)->first();
                                    array_push($ccEmails, $layer2->email);
                                    if(in_array($number, [5])){
                                        // cc email head of cs
                                        array_push($ccEmails, MtrReminderSp::find(3)->isi);
                                    }
                                }
                            }elseif($type == 'warning'){
                                // cc email ke layer2
                                $where = ['nik' => $nik_property['layer2']];
                                $layer2 = Karyawan::where($where)->first();
                                array_push($ccEmails, $layer2->email);
                                // cc email head of cs
                                array_push($ccEmails, MtrReminderSp::find(3)->isi);

                            }
                            Mail::to($mailData['to'])->cc($ccEmails)->send(new MattaRebornMail($mailData));
                            // end
                            $this->insertTrxReminderSpSend($trx, $number, $type, view($mailData['filename'], compact('data')));
                            // dihapus jika sudah diemail
                            $this->destroy($_reminder_sp);
                        }
                        return redirect( 'reminder_sp' )->with( 'msg', 'Data is executed successfully.' );
                    }else if($value == 'delete'){
                        $reminder_sp = $request->reminder_sp;
                        foreach($reminder_sp as $_reminder_sp){
                            $this->destroy($_reminder_sp);
                        }
                        return redirect( 'reminder_sp' )->with( 'msg', 'Data is deleted successfully.' );
                    }
                }
            }
        }
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
        $trx = TrxReminderSp::find( $id );
        $trx->delete();
    }
}
