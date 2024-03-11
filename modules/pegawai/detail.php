<?php
defined('APP') or exit('No direct script access allowed.');

$table   = 'pegawai';
$id      = $_GET['id'];
$columns = "nama,
            nip,
            pangkat,
            jabatan,
            satuan";
$row = findById($pdo, $columns, $table, $id);
if ($row->total > 0) { ?>

    <div class="row">
        <nav class="secondary-nav">
            <div class="nav-wrapper blue-grey darken-1">
                <ul>
                    <li class="waves-effect waves-light"><a href="" class="judul"><i class="material-icons">list</i> Detail Pegawai</a></li>
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
                <a href="?page=pgw" class="btn-large green waves-effect waves-light white-text"><i class="material-icons">arrow_back</i> KEMBALI</a>

                <a href="?page=pgw&act=edit&id=<?php echo $id ?>" class="btn-large blue waves-effect waves-light white-text"><i class="material-icons">edit</i> EDIT</a>

                <a href="?page=pgw&act=del&id=<?php echo $id ?>" class="btn-large deep-orange waves-effect waves-light white-text"><i class="material-icons">delete</i> HAPUS</a>
            </div>
        </div>
    </div>
<?php
} else {
    redirectTo('?page=pgw');
}
?>
