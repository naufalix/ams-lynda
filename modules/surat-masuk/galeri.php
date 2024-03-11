<?php
defined('APP') or exit('No direct script access allowed.') ?>

<div class="row">
    <nav class="secondary-nav">
        <div class="nav-wrapper blue-grey darken-1">
            <ul>
                <li class="waves-effect waves-light hide-on-small-only"><a href="" class="judul"><i class="material-icons">collections</i> Galeri Surat Masuk</a></li>
            </ul>
        </div>
    </nav>
</div>

<br>
<div class="row" id="galeri-grid">
<?php
    $columns = 'id, nomor_surat, tanggal_surat, asal, lampiran';
    $stmt    = getAllFile($pdo, $columns, 'surat_masuk');
    if ($stmt->rowCount() > 0) { ?>
        <div class="grid">
        <?php
            foreach ($stmt as $row) {
                $title = $row->nomor_surat
                        . ' ― ' . indoDate($row->tanggal_surat)
                        . ' ― ' . $row->asal;
                $file = 'uploads/surat-masuk/' . $row->lampiran;
                $ext  = getExtension($file); ?>

                <a href="?page=sm&act=show&id=<?php echo $row->id ?>" target="_blank" rel="noopener" title="<?php echo $title ?>">
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
            } ?>
        </div>
<?php } else { ?>
        <h5 class="center">Belum ada file yang diupload</h5>
    <?php } ?>
</div>
<input type="hidden" id="pageno" value="1">
<div id="loader"></div>
