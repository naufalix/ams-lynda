<?php
defined('APP') or exit('No direct script access allowed.');

if (isset($_GET['dl'])) {
    $file = 'assets/format-pegawai.csv';
    downloadFile($file);
}

if (isset($_POST['submit'])) {
    $mime = [
        'text/x-comma-separated-values',
        'text/comma-separated-values',
        'application/octet-stream',
        'application/vnd.ms-excel',
        'application/vnd.msexcel',
        'application/x-csv',
        'application/excel',
        'application/csv',
        'text/x-csv',
        'text/csv',
        'text/plain',
    ];
    $file = $_FILES['file']['tmp_name'];
    $size = $_FILES['file']['size'];
    $ext  = getExtension(strtolower($_FILES['file']['name']));

    if (! empty($file)) {
        if (isValidFile($file, $mime) && in_array($ext, ['csv'])) {
            if ($size < 2097152) {
                if (is_uploaded_file($file)) {
                    $handle = fopen($file, 'r');
                    $rows   = [];
                    $table  = 'pegawai';
                    $i      = 0;
                    while (($data = fgetcsv($handle, 2048, ','))) {
                        $i++;
                        if ($i == 1) {
                            continue;
                        }

                        $rows[] = [
                            'nama'    => escapeStr($data[1]),
                            'nip'     => escapeStr($data[2]),
                            'pangkat' => escapeStr($data[3]),
                            'jabatan' => escapeStr($data[4]),
                            'satuan'  => escapeStr($data[5]),
                            'user_id' => $_SESSION['id'],
                        ];
                    }

                    $sql = "INSERT IGNORE INTO $table
                            SET nama = :nama,
                                nip = :nip,
                                pangkat = :pangkat,
                                jabatan = :jabatan,
                                satuan = :satuan,
                                user_id = :user_id";

                    try {
                        $pdo->beginTransaction();
                        $stmt = $pdo->prepare($sql);
                        foreach ($rows as $row) {
                            $data = [
                                'nama'    => escapeStr($row['nama']),
                                'nip'     => escapeStr($row['nip']),
                                'pangkat' => escapeStr($row['pangkat']),
                                'jabatan' => escapeStr($row['jabatan']),
                                'satuan'  => escapeStr($row['satuan']),
                            ];
                            if (! isExist($pdo, $table, $data)) {
                                $stmt->execute($row);
                            }
                        }
                        $pdo->commit();

                        fclose($handle);
                        $_SESSION['success'] = 'SUKSES! Data berhasil diimpor';
                        redirectTo('?page=pgw');
                    } catch (\Exception $e) {
                        $pdo->rollback();
                        $_SESSION['error'] = 'ERROR! Data gagal diimpor';
                        redirectTo('?page=pgw&act=imp');
                    }
                } else {
                    $_SESSION['error'] = 'ERROR! File gagal di-upload';
                    redirectTo('?page=pgw&act=imp');
                }
            } else {
                $_SESSION['error'] = 'ERROR! Ukuran file terlalu besar';
                redirectTo('?page=pgw&act=imp');
            }
        } else {
            $_SESSION['error'] = 'ERROR! Format file tidak valid';
            redirectTo('?page=pgw&act=imp');
        }
    } else {
        $_SESSION['error'] = 'ERROR! Silakan pilih file terlebih dahulu';
        redirectTo('?page=pgw&act=imp');
    }
} else { ?>

    <div class="row">
        <nav class="secondary-nav">
            <div class="nav-wrapper blue-grey darken-1">
                <ul>
                    <li class="waves-effect waves-light"><a href="" class="judul"><i class="material-icons">people_outline</i> Impor Pegawai</a></li>
                </ul>
            </div>
        </nav>
    </div>

    <?php if (isset($_SESSION['error'])) { ?>
        <div id="alert-message" class="row">
            <div class="card red lighten-5">
                <div class="card-content notif">
                    <span class="card-title red-text"><i class="material-icons md-36">clear</i> <?php echo $_SESSION['error'] ?></span>
                </div>
            </div>
        </div>
    <?php
        unset($_SESSION['error']);
    } ?>

    <div class="row">
        <div class="card">
            <div class="card-content">
                <p class="mb-1">Silakan buat file data pegawai terlebih dahulu dan simpan dengan format <strong>.CSV</strong> (file excel).</p>

                <p class="mb-1">Contoh format penulisan data pegawai bisa di download melalui link dibawah ini.</p><br>

                <p><a href="?page=pgw&act=imp&dl" name="download" class="waves-effect waves-light blue-text"><i class="material-icons">file_download</i> <strong>DOWNLOAD FORMAT</strong></a></p>
            </div>
            <div class="card-action">
                <form method="post" enctype="multipart/form-data">
                    <div class="file-field input-field col s12 m6 l6">
                        <div class="btn light-green darken-1">
                            <span>File</span>
                            <input type="file" name="file" accept=".csv" required>
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" placeholder="Upload file kode surat" type="text">
                        </div>
                    </div>
                    <div class="input-field col s12 m6 l6">
                        <button type="submit" class="btn-large blue waves-effect waves-light" name="submit"><i class="material-icons">file_upload</i> IMPOR</button>

                        <a href="?page=pgw" class="btn-large deep-orange waves-effect waves-light white-text"><i class="material-icons">clear</i> BATAL</a>
                    </div>
                    <small class="grey-text text-darken-3">Ukuran maksimal <strong>2 MB</strong> | Format <strong>CSV</strong></small>
                </form>
            </div>
        </div>
    </div>
<?php
}
?>
