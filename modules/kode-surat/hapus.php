<?php
defined('APP') or exit('No direct script access allowed.');

$table = 'kode_surat';
$id    = $_REQUEST['id'];

if (isset($_GET['yes'])) {
    destroy($pdo, $table, $id);

    $_SESSION['success'] = 'SUKSES! Data berhasil dihapus';
    redirectTo('?page=ks');
} else {
    $row = findById($pdo, 'id, kode, uraian', $table, $id);
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
                                <td width="120px">Kode Surat</td>
                                <td width="1px">:</td>
                                <td><?php echo $row->kode ?></td>
                            </tr>
                            <tr>
                                <td>Uraian</td>
                                <td>:</td>
                                <td><?php echo $row->uraian ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-action">
                    <a href="?page=ks&act=del&id=<?php echo $row->id ?>&yes" class="btn-large deep-orange waves-effect waves-light white-text"><i class="material-icons">delete</i> HAPUS</a>

                    <a href="?page=ks" class="btn-large blue waves-effect waves-light white-text"><i class="material-icons">clear</i> BATAL</a>
                </div>
            </div>
        </div>
<?php
    } else {
        redirectTo('?page=ks');
    }
}
?>
