<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])
    && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
    require_once '../../app/database.php';
    require_once '../../app/functions.php';

    $pdo = connectDb($hostname, $username, $password, $dbname);

    if (isLoggedIn()) {
        $type = escapeStr($_GET['type']);

        if ($type == 'gsm') {
            $table = 'surat_masuk';
            $org   = 'asal';
            $url   = '?page=sm&act=show&id=';
            $dir   = 'uploads/surat-masuk/';
        } else {
            $table = 'surat_keluar';
            $org   = 'tujuan';
            $url   = '?page=sk&act=show&id=';
            $dir   = 'uploads/surat-keluar/';
        }

        $pageno   = escapeStr($_GET['pageno']);
        $per_page = 8;
        $offset   = ($pageno - 1) * $per_page;

        $sql = "SELECT id, nomor_surat, tanggal_surat, $org, lampiran
                FROM $table
                WHERE lampiran != ''
                ORDER BY id DESC
                LIMIT $offset, $per_page";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo '<div class="grid">';
            foreach ($stmt as $row) {
                $org   = $type == 'gsm' ? $row->asal : $row->tujuan;
                $title = $row->nomor_surat
                        . ' ― ' . indoDate($row->tanggal_surat)
                        . ' ― ' . $org;
                $file = $dir . $row->lampiran;
                $ext  = getExtension($file); ?>

                <a href="<?php echo $url . $row->id ?>" target="_blank" rel="noopener" title="<?php echo $title ?>">
                    <?php
                    if (in_array($ext, ['png', 'jpg', 'jpeg'])) { ?>
                        <img src="<?php echo $file ?>" alt="<?php echo $title ?>">
                    <?php } elseif ($ext == 'pdf') { ?>
                        <img src="assets/img/pdf.png" alt="<?php echo $title ?>">
                    <?php } else { ?>
                        <img src="assets/img/word.png" alt="<?php echo $title ?>">
                    <?php } ?>
                </a>
<?php
            }
            echo '</div>';
        }
    }
}
?>
