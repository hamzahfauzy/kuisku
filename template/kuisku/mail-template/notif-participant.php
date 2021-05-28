<?php
$data->sesi();
$waktu_mulai = str_replace('T',' ',$data->sesi->meta('waktu_mulai'));
$waktu_selesai = str_replace('T',' ',$data->sesi->meta('waktu_selesai'));
?>
<h2>Informasi Jadwal Ujian</h2>

<h4><?= $data->sesi->post_title ?></h4>
<?= $data->sesi->post_content ?>


<table cellpadding="5">
<tr>
    <td>Link Ujian :</td>
    <td><?= route('login') ?></td>
</tr>
<tr>
    <td>Waktu Mulai :</td>
    <td><?= $waktu_mulai ?></td>
</tr>
<tr>
    <td>Waktu Selesai :</td>
    <td><?= $waktu_selesai ?></td>
</tr>
</table>

<p>Terima Kasih</p>