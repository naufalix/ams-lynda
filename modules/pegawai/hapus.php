<?php
defined('APP') or exit('No direct script access allowed.');

$table = 'pegawai';
$id    = $_REQUEST['id'];

if (isset($_GET['yes'])) {
    destroy($pdo, $table, $id);

    $_SESSION['success'] = 'SUKSES! Data berhasil dihapus';
    redirectTo('?page=pgw');
} else {
    $columns = "id,
                nama,
                nip,
                pangkat,
                jabatan,
                satuan";
    $row = findById($pdo, $columns, $table, $id);
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
                                <td width="160px">Nama Pegawai</td>
                                <td width="1px">:</td>
                                <td><?php echo $row->nama ?></td>
                            </tr>
                            <tr>
                                <td>NIP</td>
                                <td>:</td>
                                <td><?php echo $row->nip ?></td>
                            </tr>
                            <tr>
                                <td>Pangkat Golongan</td>
                                <td>:</td>
                                <td><?php echo $row->pangkat ?></td>
                            </tr>
                            <tr>
                                <td>Jabatan</td>
                                <td>:</td>
                                <td><?php echo $row->jabatan ?></td>
                            </tr>
                            <tr>
                                <td>Satuan Organisasi</td>
                                <td>:</td>
                                <td><?php echo $row->satuan ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-action">
                    <a href="?page=pgw&act=del&id=<?php echo $row->id ?>&yes" class="btn-large deep-orange waves-effect waves-light white-text"><i class="material-icons">delete</i> HAPUS</a>

                    <a href="?page=pgw" class="btn-large blue waves-effect waves-light white-text"><i class="material-icons">clear</i> BATAL</a>
                </div>
            </div>
        </div>
<?php
    } else {
        redirectTo('?page=pgw');
    }
}
?>
