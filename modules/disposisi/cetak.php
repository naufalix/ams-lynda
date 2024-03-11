<?php
defined('APP') or exit('No direct script access allowed.');

use Dompdf\Dompdf;

$sql = "SELECT COUNT(*)
        AS total, tanggal_diterima
        FROM surat_masuk
        WHERE surat_masuk.id = :id
        LIMIT 1";

$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $_GET['id']]);

$row = $stmt->fetch();
if ($row->total > 0) {

    ob_start();
    include './modules/disposisi/cetak_template.php';
    $disposisi = ob_get_contents();
    ob_end_clean();

    $tmp = sys_get_temp_dir();
    $dompdf = new Dompdf([
        // authorize DomPdf to download fonts and other Internet assets
        'isRemoteEnabled' => true,
        // all directories must exist and not end with /
        'fontDir' => $tmp,
        'fontCache' => $tmp,
        'tempDir' => $tmp,
        'chroot' => $tmp,
    ]);

    $dompdf->loadHtml($disposisi, 'UTF-8');
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    ob_end_clean();
    $dompdf->stream('Disposisi surat masuk tanggal ' . indoDate($row->tanggal_diterima), ['Attachment' => 0]);
} else {
    redirectTo('?page=sm');
}
