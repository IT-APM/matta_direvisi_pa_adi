<p>Hai {{ $data['trx']->nama }},
<br/>Jika anda terima email ini, maka selamat anda telah lulus dan lolos pada tahapan psikotes dan wawancara.
<br/>Untuk selanjutnya silahkan klik link <b>PENAWARAN</b> dibawah ini untuk mengetahui penawaran yang kami berikan.
<br/>Jika anda setuju/tidak setuju, silahkan klik tombol setuju/tidak setuju.
<br/>Berikan catatan dan lampiran jika dibutuhkan terhadap penawaran yang kami berikan ini.
<br/>Terima kasih,</p>
<p><a href="{{ route('recruitment.negotiation_process', [Crypt::encrypt(array($data['trx']->id, 'negotiation'))]) }}" data-toggle="tooltip" title='Link Penawaran'>PENAWARAN</a></p>
<p>Best Regards,<br/>Human Capital & Organization<br/><a href="https://alita.id/" target="_blank">www.alita.id</a><br/><strong>"Passion - Integrity - Excellent"</strong></p>