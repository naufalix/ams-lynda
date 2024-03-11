<?php
defined('APP') or exit('No direct script access allowed.');

$table   = 'surat_cuti';
$id      = $_GET['id'];
$columns = "nomor_agenda,
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

$row = getLeaveDetail($pdo, $columns, $table, $id);
if ($row->total > 0) { ?>

    <div class="row">
        <nav class="secondary-nav">
            <div class="nav-wrapper blue-grey darken-1">
                <ul>
                    <li class="waves-effect waves-light"><a href="" class="judul"><i class="material-icons">list</i> Detail Surat Cuti</a></li>

                    <li class="waves-effect waves-light"><a href="?page=sc"><i class="material-icons">arrow_back</i> Kembali</a></li>
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
                <a href="?page=sc" class="btn-large green waves-effect waves-light white-text"><i class="material-icons">arrow_back</i> KEMBALI</a>

                <a href="?page=sc&act=edit&id=<?php echo $id ?>" class="btn-large blue waves-effect waves-light white-text"><i class="material-icons">edit</i> EDIT</a>

                <a href="?page=sc&act=del&id=<?php echo $id ?>" class="btn-large deep-orange waves-effect waves-light white-text"><i class="material-icons">delete</i> HAPUS</a>

                <a href="?page=sc&act=print&id=<?php echo $id ?>" class="btn-large indigo lighten-1 waves-effect waves-light white-text" target="_blank" rel="noopener"><i class="material-icons">print</i> CETAK</a>
            </div>
        </div>
    </div>
<?php
} else {
    redirectTo('?page=sc');
}
?>
