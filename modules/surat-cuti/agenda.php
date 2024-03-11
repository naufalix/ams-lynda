<?php
defined('APP') or exit('No direct script access allowed.');

include 'parts/print-css.php'; ?>

<div class="row">
    <nav class="secondary-nav">
        <div class="nav-wrapper blue-grey darken-1">
            <ul>
                <li class="waves-effect waves-light"><a href="" class="judul"><i class="material-icons">book</i> Agenda Surat Cuti</a></li>
            </ul>
        </div>
    </nav>
</div>

<?php include 'parts/date-range-filter.php' ?>

<?php
if (isset($_POST['submit'])) {
    $from = $_POST['dari_tanggal'];
    $to   = $_POST['sampai_tanggal'];

    if (! isValidDate($from) || ! isValidDate($to)) {
        redirectTo('?page=asc');
    } else {
        if ($from == "" || $to == "") {
            redirectTo('?page=asc');
        } else { ?>

            <div class="row agenda">
                <?php include 'parts/print-header.php' ?>
                <h5 class="show-print print-title">AGENDA SURAT CUTI</h5>
                <div class="col s12 m8 l10">
                    <p class="title-agenda hide-print">Agenda Surat Cuti <strong><?php echo indoDate($from) ?></strong> &minus; <strong><?php echo indoDate($to) ?></strong></p>

                    <p class="date-agenda show-print"><strong><?php echo indoDate($from) ?></strong> &minus; <strong><?php echo indoDate($to) ?></strong></p>
                </div>
                <div class="col s12 m4 l2">
                    <button type="submit" onClick="window.print()" class="btn-large deep-orange waves-effect waves-light right"><i class="material-icons">print</i> CETAK</button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="bordered table-agenda">
                    <thead class="blue lighten-4">
                        <tr>
                            <th width="1%">No. Agenda</th>
                            <th width="18%">Nomor Surat</th>
                            <th width="15%">Tanggal Surat</th>
                            <th width="17%">Nama Pegawai</th>
                            <th width="20%">Jenis Cuti</th>
                            <th width="15%">Tanggal Cuti</th>
                        </tr>
                    </thead>
                    <tbody>
            <?php
            $table   = 'surat_cuti';
            $columns = 'nomor_agenda,
                        nomor_surat,
                        tanggal_surat,
                        pegawai.nama,
                        jenis_cuti,
                        tanggal_cuti,
                        users.nama AS petugas';

            $sql = "SELECT $columns FROM $table
                    LEFT JOIN kode_surat
                    ON kode_surat.id = $table.kode_id
                    LEFT JOIN pegawai
                    ON pegawai.id = $table.pegawai_id
                    LEFT JOIN users
                    ON users.id = $table.user_id
                    WHERE $table.created_at
                    BETWEEN :dari_tanggal
                    AND :sampai_tanggal
                    ORDER BY $table.id
                    LIMIT 100";

            $data = [
                'dari_tanggal'   => $from . ' 00:00:00',
                'sampai_tanggal' => $to . ' ' . date('H:i:s'),
            ];

            $stmt = $pdo->prepare($sql);
            $stmt->execute($data);
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch()) { ?>
                    <tr>
                        <td><?php echo $row->nomor_agenda ?></td>
                        <td><?php echo $row->nomor_surat ?></td>
                        <td><?php echo indoDate($row->tanggal_surat) ?></td>
                        <td><?php echo $row->nama ?></td>
                        <td><?php echo $row->jenis_cuti ?></td>
                        <td><?php echo indoDate($row->tanggal_cuti)  ?></td>
                    </tr>
    <?php }
            } else { ?>
                <tr>
                    <td colspan="9" class="center">
                        <h5>Tidak ada agenda</h5>
                    </td>
                </tr>
    <?php } ?>
                </tbody>
            </table>
        </div>
<?php
        }
    }
} ?>
