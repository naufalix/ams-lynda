<?php
defined('APP') or exit('No direct script access allowed.');

$sql = "SELECT COUNT(*) AS total,
        nomor_agenda,
        nomor_surat,
        tanggal_surat,
        asal,
        perihal,
        tanggal_diterima,
        tujuan,
        isi
        FROM surat_masuk
        LEFT JOIN disposisi
        ON disposisi.surat_id = surat_masuk.id
        WHERE surat_masuk.id = :id
        LIMIT 1";

$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $_GET['id']]);

$row = $stmt->fetch();
if ($row->total > 0) { ?>

    <!DOCTYPE html>
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <style>
                body {
                    font-family: Helvetica, sans-serif;
                    font-size: 13px;
                }
                .agenda {
                    position: relative;
                }
                .kop {
                    margin-top: 0;
                    margin-bottom: 25px;
                    text-align: center;
                }
                .kop-logo {
                    height: auto;
                    left: 0;
                    margin-top: 0;
                    max-width: 80px;
                    max-height: 80px;
                    position: absolute;
                }
                .kop-dinas,
                .kop-instansi {
                    text-transform: uppercase;
                }
                .kop-dinas {
                    font-size: 16px;
                    margin-top: 0;
                }
                .kop-instansi {
                    font-size: 20px;
                    font-weight: 700;
                    margin: -35px 0 12px;
                }
                .kop-alamat,
                .kop-kontak {
                    font-size: 13px;
                    margin-top: -10px;
                }
                .kop-separator {
                    border-bottom: 2px solid #616161;
                    margin-top: -2px;
                }
                .show-print {
                    display: block;
                }
                .hide-print {
                    display: none
                }
                .print-title {
                    font-size: 19px;
                    font-weight: 700;
                    margin-bottom: 20px;
                    text-align: center;
                }
                tr {
                    border: 1px solid #222;
                }
                td:nth-child(1) {
                    width: 150px;
                }
                td:nth-child(2) {
                    padding: 10px 0;
                    width: 5px;
                }
                .disp-table tr:nth-child(2) td {
                    vertical-align: top;
                }
                .disp-table {
                    margin-bottom: 30px;
                }
                .space {
                    height: 30px;
                }
                .isi {
                    min-height: 100px;
                }
                .ttd {
                    margin-left: 65%;
                    text-align: left;
                }
                .kepala_kantor {
                    margin-bottom: 60px;
                }
                .pimpinan {
                    font-weight: 700;
                    text-decoration: underline;
                }
                .pace {
                    display: none;
                }
                .container,
                .row,
                .kop,
                table {
                    margin-left: 0;
                    margin-right: 0;
                    width: 100%;
                    font-size: 13px;
                }
                td {
                    border: 0;
                    padding: 10px;
                }
                td:nth-child(1) {
                    width: 120px;
                }
                td:nth-child(2) {
                    padding: 10px 0;
                    width: 5px;
                }
            </style>
        </head>
        <body>
            <div class="row agenda">
                <?php include 'parts/print-header.php' ?>
                <h5 class="show-print print-title">LEMBAR DISPOSISI</h5>
                <table style="border: 1px solid #222; border-collapse: collapse">
                    <tr>
                        <td>Nomor Agenda</td>
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
                        <td>Tanggal diterima</td>
                        <td>:</td>
                        <td><?php echo indoDate($row->tanggal_diterima) ?></td>
                    </tr>
                </table>

                <div class="space"></div>

                <table class="disp-table" style="border: 1px solid #222; border-collapse: collapse">
                    <tr>
                        <td>Disposisi kepada</td>
                        <td>:</td>
                        <td><?php echo $row->tujuan ?></td>
                    </tr>
                    <tr>
                        <td>Isi Disposisi</td>
                        <td>:</td>
                        <td>
                            <div class="isi">
                                <?php echo $row->isi ?>
                            </div>
                        </td>
                    </tr>
                </table>

                <div class="ttd">
                    <div class="kepala_kantor">Pimpinan</div>
                    <div class="pimpinan"><?php echo showOrg($pdo)->pimpinan ?></div>
                    NIP: <?php echo ! empty(showOrg($pdo)->nip) ? showOrg($pdo)->nip : '−' ?>
                </div>

            </div>
        </body>
    </html>
<?php
    } else {
        redirectTo('?page=sm');
    }
?>
