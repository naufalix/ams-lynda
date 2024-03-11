<?php
defined('APP') or exit('No direct script access allowed.');

$table = 'surat_cuti';
$id    = $_REQUEST['id'];

if (isset($_GET['yes'])) {
    destroy($pdo, $table, $id);

    $_SESSION['success'] = 'SUKSES! Data berhasil dihapus';
    redirectTo('?page=sc');
} else {
    $columns = "$table.id,
                nomor_agenda,
                kode,
                nomor_surat,
                tanggal_surat,
                pegawai.nama,
                pegawai.nip,
                pangkat,
                jabatan,
                satuan,
                jenis_cuti,
                lama_cuti,
                tanggal_cuti,
                $table.created_at,
                users.nama as petugas";

    $row = getLeaveDetail($pdo, $columns, $table, $_GET['id']);
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
                                <td><?php echo indoDate($row->tanggal_surat) ?></td>
                            </tr>
                            <tr>
                                <td>Nama Pegawai</td>
                                <td>:</td>
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
                            <tr>
                                <td>Jenis Cuti</td>
                                <td>:</td>
                                <td><?php echo $row->jenis_cuti ?></td>
                            </tr>
                            <tr>
                                <td>Lama Cuti</td>
                                <td>:</td>
                                <td><?php echo $row->lama_cuti ?></td>
                            </tr>
                            <tr>
                                <td>Tanggal Cuti</td>
                                <td>:</td>
                                <td><?php echo indoDate($row->tanggal_cuti) ?></td>
                            </tr>
                            <tr>
                                <td>Ditambahkan pada</td>
                                <td>:</td>
                                <td><?php echo localDate($row->created_at) ?></td>
                            </tr>
                            <tr>
                                <td>Ditambahkan oleh</td>
                                <td>:</td>
                                <td><?php echo $row->petugas ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-action">
                    <a href="?page=sc&act=del&id=<?php echo $row->id ?>&yes" class="btn-large deep-orange waves-effect waves-light white-text"><i class="material-icons">delete</i> HAPUS</a>

                    <a href="?page=sc" class="btn-large blue waves-effect waves-light white-text"><i class="material-icons">clear</i> BATAL</a>
                </div>
            </div>
        </div>
<?php
    } else {
        redirectTo('?page=sc');
    }
}
?>
