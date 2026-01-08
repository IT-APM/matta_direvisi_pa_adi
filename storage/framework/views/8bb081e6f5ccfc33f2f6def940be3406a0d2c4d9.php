<!DOCTYPE html>
<html>

<style>
.center {
  line-height: 55px;
  text-align: center;
}
.border-left {
  border-left: 0.5px thin black;
}
.border-right {
  border-right: 0.5px thin black;
}
.border-top {
    border-top: 0.5px thin black;
}
.border-bottom {
    border-bottom: 0.5px thin black;
}
</style>
<head>
	<title>Matta Reborn</title>
</head>

<body>
	<!-- start -->
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<table width="100%" border="0.5px" cellspacing="0" cellpadding="2">
					<tr>
						<td width="25%">&nbsp;<img src="<?php echo e(public_path('assets/images/matta/alita_new.png')); ?>" alt="Logo Alita"></td>
                        <td width="50%"><div class="center" style="font-weight: bold; font-size: 12px">FORM IDENTITAS PELAMAR KERJA</div></td>
                        <td width="25%"><div class="center">No: <?php echo e($trx->id); ?></div></td>
					</tr>
				</table>
				<br/><br/>
				<table width="100%" class="border-top border-bottom border-left border-right" cellspacing="0" cellpadding="2">
					<tr>
						<td align="center"><b>IDENTITAS DIRI</b></td>
					</tr>
				</table>
				<table class="border-top border-bottom border-left border-right" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="24%">Nama</td>
                        <td width="2%">:</td>
                        <td width="74%"><?php echo e($trx->nama); ?></td>
                    </tr>
                    <tr>
                        <td>Tempat/Tanggal Lahir</td>
                        <td>:</td>
                        <td><?php echo e($trx->tempat_lahir); ?>/<?php echo e(Carbon\Carbon::parse($trx->tanggal_lahir)->format('d-m-Y')); ?></td>
                    </tr>
                    <tr>
                        <td>Nomor KTP</td>
                        <td>:</td>
                        <td><?php echo e($trx->ktp); ?></td>
                    </tr>
                    <tr>
                        <td>Alamat (Sesuai KTP)</td>
                        <td>:</td>
                        <td><?php echo e($trx->alamat_ktp); ?></td>
                    </tr>
                    <tr>
                        <td>Alamat Domisili</td>
                        <td>:</td>
                        <td><?php echo e($trx->alamat_domisili); ?></td>
                    </tr>
                    <tr>
                        <td>Agama</td>
                        <td>:</td>
                        <td><?php echo e($trx->agama); ?></td>
                    </tr>
                    <tr>
                        <td>Nomor NPWP</td>
                        <td>:</td>
                        <td><?php echo e($trx->npwp); ?></td>
                    </tr>
                    <tr>
                        <td>Nomor Telepon</td>
                        <td>:</td>
                        <td><?php echo e($trx->telepon); ?></td>
                    </tr>
                    <?php if(!empty($trx->telepon_darurat)): ?>
                    <?php
                        $telepon_darurat = array_map('trim', explode(',', $trx->telepon_darurat));
                        $str_telepon_darurat = $telepon_darurat[1].', '.$telepon_darurat[2];
                        if(count($telepon_darurat) > 3){
                            $str_telepon_darurat = $telepon_darurat[2].', '.$telepon_darurat[3];
                        }
                    ?>
                    <tr>
                        <td>Nomor Telepon Darurat</td>
                        <td>:</td>
                        <td><?php echo e($str_telepon_darurat); ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td>Email</td>
                        <td>:</td>
                        <td><?php echo e($trx->email); ?></td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>:</td>
                        <td><?php echo e($trx->status_hidup); ?> <?php if(!empty($trx->tanggal_status) && in_array($trx->status_hidup, ['Menikah','Cerai Hidup','Cerai Mati'])): ?> sejak <?php echo e(Carbon\Carbon::parse($trx->tanggal_status)->format('d-m-Y')); ?><?php endif; ?></td>
                    </tr>
                </table>
				
				<table width="100%" class="border-top border-bottom border-left border-right" cellspacing="0" cellpadding="2">
					<tr>
						<td align="center"><b>SUSUNAN KELUARGA (Jika Sudah Menikah)</b></td>
					</tr>
				</table>
				<table border="0.5px" width="100%" cellspacing="0" cellpadding="2">
                    
                    <tr align="center" style="font-weight: bold;">
                        <td width="12%">Pasangan</td>
                        <td width="28%">Nama</td>
                        <td width="10%">Usia (th)</td>
                        <td width="12%">L/P</td>
                        <td width="13%">Pendidikan</td>
                        <td width="25%">Pekerjaan</td>
                    </tr>
                    <tr>
                        <td><?php echo e($trx->kelamin_pasangan); ?></td>
                        <td><?php echo e($trx->nama_pasangan); ?></td>
                        <td><?php echo e($trx->usia_pasangan); ?></td>
                        <td><?php if($trx->kelamin_pasangan=='Istri'): ?>Perempuan <?php elseif($trx->kelamin_pasangan=='Suami'): ?>Laki-laki <?php endif; ?></td>
                        <td><?php echo e($trx->pendidikan_pasangan); ?></td>
                        <td><?php echo e($trx->pekerjaan_pasangan); ?></td>
                    </tr>
                    
                    <tr align="center" style="font-weight: bold;">
                        <td>Anak</td>
                        <td>Nama</td>
                        <td>Usia (th)</td>
                        <td>L/P</td>
                        <td>Pendidikan</td>
                        <td>Pekerjaan</td>
                    </tr>
                    <?php
                    $where = ['id_identitas' => $trx->id];
                    $anak = App\Models\TrxRecruitmentAnak::where($where)->orderBy('usia', 'desc')->get();
                    $ke = 1;
                    ?>
                    <?php $__empty_1 = true; $__currentLoopData = $anak; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>Anak ke-<?php echo e($ke++); ?></td>
                            <td><?php echo e($data->nama); ?></td>
                            <td><?php echo e($data->usia); ?></td>
                            <td><?php echo e($data->kelamin); ?></td>
                            <td><?php echo e($data->pendidikan); ?></td>
                            <td><?php echo e($data->pekerjaan); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    <?php endif; ?>
                </table>
				
				<table width="100%" class="border-top border-bottom border-left border-right" cellspacing="0" cellpadding="2">
					<tr>
						<td align="center"><b>SUSUNAN KELUARGA (Termasuk Diri Anda)</b></td>
					</tr>
				</table>
				<table border="0.5px" width="100%" cellspacing="0" cellpadding="2">
                    
                    <tr align="center" style="font-weight: bold;">
                        <td width="12%">Orang Tua</td>
                        <td width="28%">Nama</td>
                        <td width="10%">Usia (th)</td>
                        <td width="12%">L/P</td>
                        <td width="13%">Pendidikan</td>
                        <td width="25%">Pekerjaan</td>
                    </tr>
                    <tr>
                        <td>Ayah</td>
                        <td><?php echo e($trx->nama_bapak); ?></td>
                        <td><?php echo e($trx->usia_bapak); ?></td>
                        <td>Laki-laki</td>
                        <td><?php echo e($trx->pendidikan_bapak); ?></td>
                        <td><?php echo e($trx->pekerjaan_bapak); ?></td>
                    </tr>
                    <tr>
                        <td>Ibu</td>
                        <td><?php echo e($trx->nama_ibu); ?></td>
                        <td><?php echo e($trx->usia_ibu); ?></td>
                        <td>Perempuan</td>
                        <td><?php echo e($trx->pendidikan_ibu); ?></td>
                        <td><?php echo e($trx->pekerjaan_ibu); ?></td>
                    </tr>
                    
                    <tr align="center" style="font-weight: bold;">
                        <td>Hubungan</td>
                        <td>Nama</td>
                        <td>Usia (th)</td>
                        <td>L/P</td>
                        <td>Pendidikan</td>
                        <td>Pekerjaan</td>
                    </tr>
                    <?php
                    $where = ['id_identitas' => $trx->id];
                    $saudara = App\Models\TrxRecruitmentSaudara::select('nama','usia','kelamin','pendidikan','pekerjaan')->where($where)->orderBy('usia', 'desc')->get()->toArray();
                    array_push($saudara, ['nama'=>$trx->nama, 'usia'=>$trx->usia, 'kelamin'=>$trx->kelamin, 'pendidikan'=>$trx->pendidikan, 'pekerjaan'=>$trx->pekerjaan]);
                    $keys = array_column($saudara, 'usia');
                    array_multisort($keys, SORT_DESC, $saudara);
                    ?>
                    <?php $__empty_1 = true; $__currentLoopData = $saudara; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php if($data['usia'] > $trx->usia): ?>Kakak <?php elseif($data['usia'] < $trx->usia): ?>Adik <?php elseif($data['usia'] == $trx->usia): ?>Diri Sendiri <?php endif; ?></td>
                            <td><?php echo e($data['nama']); ?></td>
                            <td><?php echo e($data['usia']); ?></td>
                            <td><?php echo e($data['kelamin']); ?></td>
                            <td><?php echo e($data['pendidikan']); ?></td>
                            <td><?php echo e($data['pekerjaan']); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    <?php endif; ?>
                </table>
				
				<table width="100%" class="border-top border-bottom border-left border-right" cellspacing="0" cellpadding="2">
					<tr>
						<td align="center"><b>RIWAYAT PENDIDIKAN DAN ORGANISASI</b></td>
					</tr>
				</table>
                
				<table width="100%" class="border-top border-bottom border-left border-right" cellspacing="0" cellpadding="2">
					<tr>
						<td align="left"><b>1. Pendidikan Formal</b></td>
					</tr>
				</table>
				<table border="0.5px" width="100%" cellspacing="0" cellpadding="2">
                    <tr align="center" style="font-weight: bold;">
                        <td width="11%">Jenjang</td>
                        <td width="21%">Nama Sekolah</td>
                        <td width="21%">Kota</td>
                        <td width="21%">Jurusan</td>
                        <td width="8%">Tahun<br/>Masuk</td>
                        <td width="8%">Tahun<br/>Lulus</td>
                        <td width="10%">IPK/NEM</td>
                    </tr>
                    <?php
                    $where = ['id_identitas' => $trx->id];
                    $formal = App\Models\TrxRecruitmentFormal::where($where)->orderBy('tahun_masuk', 'asc')->get();
                    ?>
                    <?php $__empty_1 = true; $__currentLoopData = $formal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($data->jenjang); ?></td>
                            <td><?php echo e($data->nama); ?></td>
                            <td><?php echo e($data->kota); ?></td>
                            <td><?php echo e($data->jurusan); ?></td>
                            <td><?php echo e($data->tahun_masuk); ?></td>
                            <td><?php echo e($data->tahun_lulus); ?></td>
                            <td><?php echo e($data->nilai); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    <?php endif; ?>
                </table>
                
				<table width="100%" class="border-top border-bottom border-left border-right" cellspacing="0" cellpadding="2">
					<tr>
						<td align="left"><b>2. Pendidikan Informal (Kursus, Training, Workshop, dll) dan Keterampilan</b></td>
					</tr>
				</table>
				<table border="0.5px" width="100%" cellspacing="0" cellpadding="2">
                    <tr align="center" style="font-weight: bold;">
                        <td width="53%">Bidang</td>
                        <td width="10%">Tahun</td>
                        <td width="27%">Tempat/Lembaga</td>
                        <td width="10%">Sertifikat</td>
                    </tr>
                    <?php
                    $where = ['id_identitas' => $trx->id];
                    $informal = App\Models\TrxRecruitmentInformal::where($where)->orderBy('tahun', 'asc')->get();
                    ?>
                    <?php $__empty_1 = true; $__currentLoopData = $informal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($data->bidang); ?></td>
                            <td><?php echo e($data->tahun); ?></td>
                            <td><?php echo e($data->lembaga); ?></td>
                            <td><?php echo e($data->sertifikat); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    <?php endif; ?>
                </table>
                
				<table width="100%" class="border-top border-bottom border-left border-right" cellspacing="0" cellpadding="2">
					<tr>
						<td align="left"><b>3. Organisasi</b></td>
					</tr>
				</table>
				<table border="0.5px" width="100%" cellspacing="0" cellpadding="2">
                    <tr align="center" style="font-weight: bold;">
                        <td width="30%">Nama Organisasi</td>
                        <td width="10%">Periode</td>
                        <td width="30%">Jabatan</td>
                        <td width="30%">Keterangan</td>
                    </tr>
                    <?php
                    $where = ['id_identitas' => $trx->id];
                    $org = App\Models\TrxRecruitmentOrganisasi::where($where)->orderBy('periode', 'asc')->get();
                    ?>
                    <?php $__empty_1 = true; $__currentLoopData = $org; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($data->nama); ?></td>
                            <td><?php echo e($data->periode); ?></td>
                            <td><?php echo e($data->jabatan); ?></td>
                            <td><?php echo e($data->keterangan); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    <?php endif; ?>
                </table>
                
				<table width="100%" class="border-top border-bottom border-left border-right" cellspacing="0" cellpadding="2">
					<tr>
						<td align="center"><b>RIWAYAT PEKERJAAN</b></td>
					</tr>
				</table>
				<table border="0.5px" width="100%" cellspacing="0" cellpadding="2">
                    <tr align="center" style="font-weight: bold;">
                        <td width="16%" rowspan="2">Perusahaan</td>
                        <td width="16%" rowspan="2">Kota</td>
                        <td width="20%" colspan="2">Masa Kerja (tahun)</td>
                        <td width="16%" rowspan="2">Jabatan</td>
                        <td width="16%" rowspan="2">Upah</td>
                        <td width="16%" rowspan="2">Alasan Keluar</td>
                    </tr>
                    <tr align="center" style="font-weight: bold;">
                        <td>Masuk</td>
                        <td>Keluar</td>
                    </tr>
                    <?php
                    $where = ['id_identitas' => $trx->id];
                    $pekerjaan = App\Models\TrxRecruitmentPekerjaan::where($where)->orderBy('tahun_masuk', 'asc')->get();
                    ?>
                    <?php $__empty_1 = true; $__currentLoopData = $pekerjaan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($data->nama); ?></td>
                            <td><?php echo e($data->kota); ?></td>
                            <td><?php echo e($data->tahun_masuk); ?></td>
                            <td><?php echo e($data->tahun_keluar); ?></td>
                            <td><?php echo e($data->jabatan); ?></td>
                            <td><?php if(!empty($data->gaji)): ?>Rp. <?php echo e(number_format($data->gaji, 0)); ?><?php endif; ?></td>
                            <td><?php echo e($data->alasan); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    <?php endif; ?>
                </table>
                
				<table width="100%" class="border-top border-bottom border-left border-right" cellspacing="0" cellpadding="2">
					<tr>
						<td align="center"><b>LAIN-LAIN</b></td>
					</tr>
				</table>
				<table width="100%" class="border-top border-bottom border-left border-right" cellspacing="0" cellpadding="2">
                    <tr style="font-weight: bold;">
                        <td>1. Apakah hobi atau kegemaran Anda?</td>
                    </tr>
                    <tr>
                        <td><?php echo nl2br(e($trx->hobi)); ?></td>
                    </tr>
                    <tr style="font-weight: bold;">
                        <td>2. Faktor-faktor apakah yang menjadi kelebihan (Strong Point) Anda?</td>
                    </tr>
                    <tr>
                        <td><?php echo nl2br(e($trx->kelebihan)); ?></td>
                    </tr>
                    <tr style="font-weight: bold;">
                        <td>3. Hal apa yang perlu ditingkatkan (Areas to Be Improved) dalam diri Anda?</td>
                    </tr>
                    <tr>
                        <td><?php echo nl2br(e($trx->peningkatan)); ?></td>
                    </tr>
                    <tr style="font-weight: bold;">
                        <td>4. Apakah Anda menderita sakit keras atau sakit yang berkepanjangan?</td>
                    </tr>
                    <tr>
                        <td><span style="font-family:zapfdingbats;">4</span> <?php echo e($trx->is_sakit); ?><?php if($trx->is_sakit=='Pernah Sakit'): ?><br/>Penyakit <?php echo e($trx->sakit_apa); ?>, Tahun <?php echo e($trx->sakit_tahun); ?><br/>Kondisi saat ini apakah sudah sembuh atau masih dalam proses pengobatan?<br/><?php echo e($trx->sakit_kondisi); ?><?php endif; ?></td>
                    </tr>
                    <tr style="font-weight: bold;">
                        <td>5. Alasan Anda ingin bergabung di PT. Alita Praya Mitra</td>
                    </tr>
                    <tr>
                        <td><?php echo nl2br(e($trx->alasan)); ?></td>
                    </tr>
                    <tr style="font-weight: bold;">
                        <td>6. Gaji yang diharapkan (Rupiah)</td>
                    </tr>
                    <tr>
                        <td>Rp. <?php echo e(number_format($trx->gaji, 0)); ?></td>
                    </tr>
                </table>
                <?php
					// 3=Apply
					$where = ['id_identitas' => $trx->id, 'id_status' => 3];
					$flow1 = App\Models\TrxRecruitmentLog::where($where)->first();
                ?>
				<table width="100%" class="border-top border-bottom border-left border-right" align="center" cellpadding="2" cellspacing="0">
                    <tr>
                        <td>Kandidat<br/><img src="<?php echo e(public_path('assets/images/matta/verified_new.png')); ?>" height="30" /><br/><?php echo e($flow1->pic); ?><br/><?php echo e($flow1->created_at); ?></td>
                    </tr>
				</table>
			</td>
		</tr>
	</table>
	<!-- finish -->
</body>

</html><?php /**PATH C:\xampp\htdocs\matta\resources\views/recruitment/pdf_applicant.blade.php ENDPATH**/ ?>