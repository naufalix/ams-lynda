<?php
defined('APP') or exit('No direct script access allowed.');

$sql = "SELECT COUNT(*) AS total,
        nomor_agenda,
        nomor_surat,
        tanggal_surat,
        asal,
        perihal,
        tanggal_diterima,
        indeks,
        keterangan,
        $table.id,
        tujuan,
        isi,
        $table.created_at,
        nama
        FROM surat_masuk
        LEFT JOIN $table
        ON $table.surat_id = surat_masuk.id
        LEFT JOIN users
        ON users.id = $table.user_id
        WHERE surat_masuk.id = :id
        LIMIT 1";

$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);

$row = $stmt->fetch();
if ($row->total > 0) { ?>

    <div class="row card-detail">
        <div class="card">
            <div class="card-content table-responsive">
                <table>
                    <tr>
                        <td width="160px">Nomor Agenda</td>
                        <td>:</td>
                        <td><?php echo $row->nomor_agenda ?></td>
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
                        <td>Diterima pada</td>
                        <td>:</td>
                        <td><?php echo indoDate($row->tanggal_diterima) ?></td>
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
                </table>
            </div>
        </div>
    </div>
<?php } else {
    redirectTo('?page=sm');
} ?>
