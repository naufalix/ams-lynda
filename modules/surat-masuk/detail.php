<?php
defined('APP') or exit('No direct script access allowed.');

$table   = 'surat_masuk';
$id      = $_GET['id'];
$columns = "$table.id,
            nomor_agenda,
            kode,
            nomor_surat,
            tanggal_surat,
            asal,
            perihal,
            indeks,
            keterangan,
            tanggal_diterima,
            diteruskan_kepada,
            $table.created_at,
            nama,
            lampiran";

$row = getMailDetail($pdo, $columns, $table, $id);
if ($row->total > 0) { ?>

    <div class="row">
        <nav class="secondary-nav">
            <div class="nav-wrapper blue-grey darken-1">
                <ul>
                    <li class="waves-effect waves-light"><a href="" class="judul"><i class="material-icons">list</i> Detail Surat Masuk</a></li>

                    <li class="waves-effect waves-light"><a href="?page=sm"><i class="material-icons">arrow_back</i> Kembali</a></li>
                </ul>
            </div>
        </nav>
    </div>

    <div class="row card-detail">
        <div class="card">
            <div class="card-content table-responsive">
                <table>
                    <tbody>
                        <tr>
                            <td width="160px">Nomor Agenda</td>
                            <td>:</td>
                            <td><?php echo $row->nomor_agenda ?></td>
                        </tr>
                        <tr>
                            <td>Kode Arsip (Klasifikasi)</td>
                            <td>:</td>
                            <td><?php echo $row->kode ?></td>
                        </tr>
                        <tr>
                            <td>Nomor Surat</td>
                            <td>:</td>
                            <td><?php echo $row->nomor_surat ?></td>
                        </tr>
                        <tr>
                            <td>Tanggal Surat</td>
                            <td>:</td>
                            <td><?php echo indoDate($row->tanggal_surat) ?></td>
                        </tr>
                        <tr>
                            <td>Asal Surat</td>
                            <td>:</td>
                            <td><?php echo $row->asal ?></td>
                        </tr>
                        <tr>
                            <td>Perihal</td>
                            <td>:</td>
                            <td><?php echo $row->perihal ?></td>
                        </tr>
                        <tr>
                            <td>Indeks</td>
                            <td>:</td>
                            <td><?php echo ! empty($row->indeks) ? $row->indeks : '&minus;' ?></td>
                        </tr>
                        <tr>
                            <td>Keterangan</td>
                            <td>:</td>
                            <td><?php echo ! empty($row->keterangan) ? $row->keterangan : '&minus;' ?></td>
                        </tr>
                        <tr>
                            <td>Diterima pada</td>
                            <td>:</td>
                            <td><?php echo indoDate($row->tanggal_diterima) ?></td>
                        </tr>
                        <tr>
                            <td>Diteruskan Kepada</td>
                            <td>:</td>
                            <td>
                                <?php
                                if (! empty($row->diteruskan_kepada)) {
                                    echo '<ol style="margin-left:-1.75em">';
                                    $tujuan = unserialize($row->diteruskan_kepada);
                                    foreach ($tujuan as $key => $value) {
                                        echo '<li>' . $value . '</li>';
                                    }
                                    echo '</ol>';
                                } ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Ditambahkan pada</td>
                            <td>:</td>
                            <td><?php echo localDate($row->created_at) ?></td>
                        </tr>
                        <tr>
                            <td>Ditambahkan oleh</td>
                            <td>:</td>
                            <td><?php echo $row->nama ?></td>
                        </tr>
                        <tr>
                            <td>Lampiran file</td>
                            <td>:</td>
                            <td>
                                <?php
                                    if (! empty($row->lampiran)) {
                                        $file = 'uploads/surat-masuk/' . $row->lampiran;
                                        $ext  = getExtension($file);

                                        if (isset($_GET['dl'])) {
                                            downloadFile($file);
                                        }

                                        if ($ext == 'pdf') { ?>
                                            <a class="btn green waves-effect waves-light white-text" href="<?php echo $file ?>" target="_blank" rel="noopener"><i class="material-icons">visibility</i> Lihat</a>

                                    <?php } elseif (in_array($ext, ['png', 'jpg', 'jpeg'])) { ?>
                                        <img src="<?php echo $file ?>" alt="Lampiran file">
                                        <br>
                                    <?php } ?>

                                    <a class="btn indigo lighten-1 waves-effect waves-light white-text" href="?page=sm&act=show&id=<?php echo $row->id ?>&dl"><i class="material-icons">file_download</i> Download</a>
                                <?php
                                    } else {
                                        echo '&minus;';
                                    } ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-action">
                <a href="?page=sm" class="btn-large green waves-effect waves-light white-text"><i class="material-icons">arrow_back</i> KEMBALI</a>

                <a href="?page=sm&act=edit&id=<?php echo $id ?>" class="btn-large blue waves-effect waves-light white-text"><i class="material-icons">edit</i> EDIT</a>

                <a href="?page=sm&act=del&id=<?php echo $id ?>" class="btn-large deep-orange waves-effect waves-light white-text"><i class="material-icons">delete</i> HAPUS</a>

                <a href="?page=sm&act=disp&id=<?php echo $id ?>" class="btn-large purple lighten-1 waves-effect waves-light white-text"><i class="material-icons">assignment</i> DISPOSISI</a>

                <a href="?page=sm&act=print&id=<?php echo $id ?>" class="btn-large indigo lighten-1 waves-effect waves-light white-text" target="_blank" rel="noopener"><i class="material-icons">print</i> CETAK DISPOSISI</a>
            </div>
        </div>
    </div>
<?php
} else {
                                        redirectTo('?page=sm');
                                    }
?>
