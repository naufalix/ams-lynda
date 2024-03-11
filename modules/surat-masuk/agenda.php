<?php
defined('APP') or exit('No direct script access allowed.');

use Dompdf\Dompdf;

if (isset($_POST['submit'])) {
    $from = $_POST['dari_tanggal'];
    $to   = $_POST['sampai_tanggal'];

    if (! isValidDate($from) || ! isValidDate($to)) {
        redirectTo('?page=asm');
    } else {
        if ($from == "" || $to == "") {
            redirectTo('?page=asm');
        } else {
            $columns = 'nomor_agenda,
                        nomor_surat,
                        tanggal_surat,
                        asal,
                        perihal,
                        tanggal_diterima,
                        keterangan,
                        diteruskan_kepada,
                        kode';
            $stmt = getBetween($pdo, $columns, 'surat_masuk', $from, $to);

            $path = 'uploads/' . showOrg($pdo)->logo;
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $logo = 'data:image/' . $type . ';base64,' . base64_encode($data);

            $dinas    = showOrg($pdo)->dinas;
            $instansi = showOrg($pdo)->instansi;
            $alamat   = showOrg($pdo)->alamat;
            $telepon  = showOrg($pdo)->telepon;
            $email    = showOrg($pdo)->email;

            $bulan_from = month(explode('-', $from)[1]);
            $bulan_to   = month(explode('-', $to)[1]);
            $tahun_from = explode('-', $from)[0];
            $tahun_to   = explode('-', $to)[0];

            $html = '';
            $html .= <<<HTML
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
                                .title {
                                    font-size: 19px;
                                    font-weight: 700;
                                    margin-bottom: -10px;
                                    text-align: center;
                                }
                                .date {
                                    font-size: 14px;
                                    font-weight: 700;
                                    margin-bottom: 15px;
                                    text-align: center;
                                }
                            </style>
                        </head>
                        <body>
                            <div class="kop">
                                <img class="kop-logo" src="$logo">
                                <h6 class="kop-dinas">$dinas</h6>
                                <h5 class="kop-instansi">$instansi</h5>
                                <p class="kop-alamat">Alamat: $alamat</p>
                                <p class="kop-kontak">Telp: $telepon - Email: $email</p>
                                <div class="kop-separator"></div>
                            </div>

                            <p class="title">BUKU AGENDA SURAT MASUK</p>
                            <p class="date">Bulan $bulan_from-$bulan_to Tahun $tahun_from-$tahun_to</p>

                            <table style="border: 1px solid #222;border-collapse: collapse;width: 100%;">
                                <thead style="background: #baff66">
                                    <tr style="border: 1px solid #222;padding: 7px 10px;">
                                        <th style="border: 1px solid #222;padding: 7px 10px;" width="4%">No</th>
                                        <th style="border: 1px solid #222;padding: 7px 10px;" width="8%">Tanggal Terima</th>
                                        <th style="border: 1px solid #222;padding: 7px 10px;" width="8%">Tanggal Surat</th>
                                        <th style="border: 1px solid #222;padding: 7px 10px;" width="13%">No. Surat</th>
                                        <th style="border: 1px solid #222;padding: 7px 10px;" width="22%">Pengirim</th>
                                        <th style="border: 1px solid #222;padding: 7px 10px;" width="22%">Perihal</th>
                                        <th style="border: 1px solid #222;padding: 7px 10px;" width="15%">Diteruskan Kepada</th>
                                        <th style="border: 1px solid #222;padding: 7px 10px;" width="8%">Kode Arsip (Klasifikasi)</th>
                                    </tr>
                                </thead>
                                <tbody>
                    HTML;

            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch()) {
                    $nomor_agenda     = $row->nomor_agenda;
                    $tanggal_diterima = indoDate($row->tanggal_diterima);
                    $tanggal_surat    = indoDate($row->tanggal_surat);
                    $nomor_surat      = $row->nomor_surat;
                    $asal             = $row->asal;
                    $perihal          = $row->perihal;
                    if (! empty($row->diteruskan_kepada)) {
                        $diteruskan_kepada = '<ol style="margin-left: -1.625em;margin-top: 0;margin-bottom: 0;">';
                        $tujuan = unserialize($row->diteruskan_kepada);
                        foreach ($tujuan as $key => $value) {
                            $diteruskan_kepada .= '<li>' . $value . '</li>';
                        }
                        $diteruskan_kepada .= '</ol>';
                    } else {
                        $diteruskan_kepada = '-';
                    }
                    $kode = $row->kode;

                    $html .= <<<HTML
                            <tr style="border: 1px solid #222;padding: 7px 10px;">
                                <td style="border: 1px solid #222;padding: 7px 10px;text-align: center;">$nomor_agenda</td>
                                <td style="border: 1px solid #222;padding: 7px 10px;">$tanggal_diterima</td>
                                <td style="border: 1px solid #222;padding: 7px 10px;">$tanggal_surat</td>
                                <td style="border: 1px solid #222;padding: 7px 10px;">$nomor_surat</td>
                                <td style="border: 1px solid #222;padding: 7px 10px;">$asal</td>
                                <td style="border: 1px solid #222;padding: 7px 10px;">$perihal</td>
                                <td style="border: 1px solid #222;padding: 7px 10px;">$diteruskan_kepada</td>
                                <td style="border: 1px solid #222;padding: 7px 10px;text-align: center;">$kode</td>
                            </tr>
                            HTML;
                }
            } else {
                $html .= <<<HTML
                        <td colspan="8" style="border: 1px solid #222;padding: 7px 10px;text-align: center;">
                            <h4>Tidak ditemukan data!</h4>
                        </td>
                        HTML;
            }

            $html .= <<<HTML
                                </tbody>
                            </table>
                        </body>
                    </html>
                    HTML;

            $tmp = sys_get_temp_dir();
            $dompdf = new Dompdf([
                // authorize DomPdf to download fonts and other Internet assets
                'isRemoteEnabled' => true,
                // all directories must exist and not end with /
                'fontDir' => $tmp,
                'fontCache' => $tmp,
                'tempDir' => $tmp,
                'chroot' => $tmp,
            ]);

            $dompdf->loadHtml($html, 'UTF-8');
            $dompdf->setPaper('Legal', 'landscape');
            $dompdf->render();

            ob_end_clean();
            $dompdf->stream('Agenda surat masuk ' . indoDate($from) . ' - ' . indoDate($to), ['Attachment' => 0]);
        }
    }
} else { ?>
    <div class="row">
        <nav class="secondary-nav">
            <div class="nav-wrapper blue-grey darken-1">
                <ul>
                    <li class="waves-effect waves-light"><a href="" class="judul"><i class="material-icons">book</i> Agenda Surat Masuk</a></li>
                </ul>
            </div>
        </nav>
    </div>

    <?php include 'parts/date-range-filter.php' ?>

<?php } ?>
