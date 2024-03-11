<?php
defined('APP') or exit('No direct script access allowed.');

$table = 'surat_keluar';
$id    = $_REQUEST['id'];
$dir   = 'uploads/surat-keluar/';

if (isset($_GET['yes'])) {
    deleteFile($pdo, $table, $id, $dir);
    destroy($pdo, $table, $id);

    $_SESSION['success'] = 'SUKSES! Data berhasil dihapus';
    redirectTo('?page=sk');
} else {
    $columns = "$table.id,
                nomor_agenda,
                kode,
                nomor_surat,
                tanggal_surat,
                tujuan,
                perihal,
                keterangan,
                $table.created_at,
                nama,
                lampiran";

    $row = getMailDetail($pdo, $columns, $table, $id);
    if ($row->total > 0) { ?>

	    <div class="row card-delete">
            <div class="card">
                <div class="card-content table-responsive">
                    <table>
                        <thead class="red lighten-5 red-text">
                            <div class="delete-title red-text"><i class="material-icons md-36">error_outline</i>
                            Apakah Anda yakin akan menghapus data ini?</div>
                        </thead>
                        <tbody>
                            <tr>
                                <td width="160px">Nomor Agenda</td>
                                <td>:</td>
                                <td><?php echo $row->nomor_agenda ?></td>
                            </tr>
                            <tr>
                                <td>Kode Surat</td>
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
                                <td><?php echo IndoDate($row->tanggal_surat) ?></td>
                            </tr>
                            <tr>
                                <td>Tujuan Surat</td>
                                <td>:</td>
                                <td><?php echo $row->tujuan ?></td>
                            </tr>
                            <tr>
                                <td>Perihal</td>
                                <td>:</td>
                                <td><?php echo $row->perihal ?></td>
                            </tr>
                            <tr>
                                <td>Keterangan</td>
                                <td>:</td>
                                <td><?php echo ! empty($row->keterangan) ? $row->keterangan : '&minus;' ?></td>
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
                                            $file = $dir . $row->lampiran;
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

                                        <a class="btn indigo lighten-1 waves-effect waves-light white-text" href="?page=sk&act=del&id=<?php echo $row->id ?>&dl"><i class="material-icons">file_download</i> Download</a>
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
                    <a href="?page=sk&act=del&id=<?php echo $row->id ?>&yes" class="btn-large deep-orange waves-effect waves-light white-text"><i class="material-icons">delete</i> HAPUS</a>

                    <a href="?page=sk" class="btn-large blue waves-effect waves-light white-text"><i class="material-icons">clear</i> BATAL</a>
                </div>
            </div>
        </div>
<?php
    } else {
        redirectTo('?page=sk');
    }
}
?>
