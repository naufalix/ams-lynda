<?php
defined('APP') or exit('No direct script access allowed.');

$table   = 'surat_cuti';
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
            tanggal_cuti";

$row = getLeaveDetail($pdo, $columns, $table, $_GET['id']);
if ($row->total > 0) { ?>

    <style>
        body {
            font-size: 14px !important;
            line-height: 1.475;
        }
        .header {
            font-size: 16px;
            margin: 80px 0 0 35%;
        }
        .header p {
            margin: 0 !important;
        }
        .tanggal_surat {
            margin-right: 20px;
            text-align: right;
        }
        h5 {
            font-size: 18px;
            font-weight: 700;
            margin: 20px auto -15px;
            text-decoration: underline;
            text-transform: uppercase;
        }
        .nomor_surat {
            margin-bottom: 20px;
        }
        ol {
            margin-left: -30px;
        }
        .ta-justify {
            text-align: justify;
            text-justify: inter-word;
        }
        table {
            margin: 0 25px 30px;
        }
        td {
            padding: 0;
        }
        td:nth-child(1) {
            width: 150px;
        }
        td:nth-child(2) {
            padding: 0 1px 0 0;
        }
        .lama_cuti {
            margin: 0 15px 15px 10px;
        }
        .ketentuan {
            margin-left: -15px;
        }
        .ketentuan li {
            margin-bottom: 15px;
        }
        .ttd {
            margin-left: 60%;
            text-align: left;
        }
        .tempat {
            margin-bottom: 60px;
        }
        .pimpinan {
            font-weight: 700;
            text-decoration: underline;
        }
        .tembusan {
            font-size: 13px;
            font-style: italic;
            margin-top: 25px;
        }
        .tembusan p {
            margin-bottom: -15px;
        }
        .tembusan li {
            margin-left: 5px;
        }
        @media print {
            tr, td {
                border: 0 !important;
            }
        }
    </style>

    <div class="row">
        <div class="header">
            <p>PERATURAN BADAN KEPEGAWAIAN NEGARA</p>
            <p>REPUBLIK INDONESIA</p>
            <p>NOMOR 24 TAHUN 2017</p>
            <p>TENTANG</p>
            <p>TATA CARA PEMBERIAN CUTI PEGAWAI NEGERI SIPIL</p>
        </div>
        <p class="tanggal_surat">Nganjuk, <?php echo indoDate($row->tanggal_surat) ?></p>

        <h5 class="center">IZIN PELAKSANAAN <?php echo $row->jenis_cuti ?></h5>
        <p class="center nomor_surat">Nomor: <?php echo $row->nomor_surat ?></p>

        <div class="ta-justify">
            <ol>
                <li>Diberikan izin untuk melaksanakan <?php echo $row->jenis_cuti ?> untuk Tahun <?php echo date('Y') ?> Kepada Pegawai Negeri Sipil:</li>
            </ol>
        </div>

        <table>
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td><?php echo $row->nama ?></td>
            </tr>
            <tr>
                <td>NIP</td>
                <td>:</td>
                <td><?php echo $row->nip ?></td>
            </tr>
            <tr>
                <td>Pangkat Gol</td>
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
        </table>

        <div class="ta-justify">
            <p class="lama_cuti">Selama <?php echo $row->lama_cuti ?> terhitung tanggal <?php echo indoDate($row->tanggal_cuti) ?> dengan ketentuan sebagai berikut:</p>

            <ol type="a" class="ketentuan">
                <li>Sebelum menjalankan <?php echo strtolower($row->jenis_cuti) ?> wajib menyerahkan pekerjaannya kepada atasan langsungnya atau pejabat lain yang ditunjuk.</li>
                <li>Segera setelah menjalankan <?php echo strtolower($row->jenis_cuti) ?> wajib melaporkan kepada atasan langsungnya dan bekerja kembali sebagaimana biasa.</li>
            </ol>

            <ol start="2">
                <li>Demikian izin melaksanakan <?php echo $row->jenis_cuti ?> ini dibuat untuk dapat digunakan sebagaimana mestinya.</li>
            </ol>
        </div>

        <div class="ttd">
            <div class="kepala_kantor">Kepala Kantor Kementerian Agama</div>
            <div class="tempat">Kabupaten Nganjuk,</div>
            <div class="pimpinan"><?php echo showOrg($pdo)->pimpinan ?></div>
            NIP: <?php echo showOrg($pdo)->nip ?>
        </div>

        <div class="tembusan">
            <p>Tembusan:</p>
            <ol>
                <li>Kepala Kantor Wilayah Kementerian Agama Provinsi Jawa Timur di Surabaya</li>
                <li>Pegawai yang bersangkutan</li>
            </ol>
        </div>
    </div>

    <script>
        window.onload = () => window.print()
        window.onafterprint = window.close
    </script>

<?php
    } else {
        redirectTo('?page=sc');
    }
?>
