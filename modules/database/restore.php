<?php
defined('APP') or exit('No direct script access allowed.');

isSuperAdmin();

if (isset($_POST['restore'])) {
    $conn = mysqli_connect($hostname, $username, $password, $dbname);

    $mime = ['application/octet-stream', 'text/plain'];
    $file = $_FILES['file']['tmp_name'];
    $size = $_FILES['file']['size'];
    $ext  = getExtension(strtolower($_FILES['file']['name']));

    if (! empty($file)) {
        if (isValidFile($file, $mime) && in_array($ext, ['sql'])) {
            if ($size < 33554432) {
                if ($size > 5120) {
                    if (is_uploaded_file($file)) {
                        $templine = [];
                        $lines    = file($file);

                        foreach ($lines as $line) {
                            if (substr($line, 0, 2) == '--' || $line == '') {
                                continue;
                            }

                            $templine .= $line;

                            if (substr(trim($line), -1, 1) == ';') {
                                mysqli_query($conn, $templine);
                                $templine = '';
                            }
                        }

                        unlink($file);
                        $_SESSION['success'] = 'SUKSES! Database berhasil di-restore';
                        redirectTo('?page=rdb');
                    } else {
                        $_SESSION['error'] = 'ERROR! File gagal di-upload';
                        redirectTo('?page=rdb');
                    }
                } else {
                    $_SESSION['error'] = 'ERROR! Ukuran file terlalu kecil';
                    redirectTo('?page=rdb');
                }
            } else {
                $_SESSION['error'] = 'ERROR! Ukuran file terlalu besar';
                redirectTo('?page=rdb');
            }
        } else {
            $_SESSION['error'] = 'ERROR! Format file tidak valid';
            redirectTo('?page=rdb');
        }
    } else {
        $_SESSION['error'] = 'ERROR! Silakan pilih file terlebih dahulu';
        redirectTo('?page=rdb');
    }
} else { ?>

    <div class="row">
        <nav class="secondary-nav">
            <div class="nav-wrapper blue-grey darken-1">
                <ul>
                    <li class="waves-effect waves-light"><a href="" class="judul"><i class="material-icons">history</i> Restore Database</a></li>
                </ul>
            </div>
        </nav>
    </div>

    <?php if (isset($_SESSION['success'])) { ?>
        <div id="alert-message" class="row">
            <div class="card green lighten-5">
                <div class="card-content notif">
                    <span class="card-title green-text"><i class="material-icons md-36">done</i> <?php echo $_SESSION['success'] ?></span>
                </div>
            </div>
        </div>
    <?php
        unset($_SESSION['success']);
    } ?>

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
                <div class="delete-title red-text">
                    <h5 class="red-text"><i class="material-icons md-36">error_outline</i> <strong>PERINGATAN!</strong></h5>

                    <p class="restore-warning"><strong>Restore database akan mengganti data yang ada dengan data baru. Tindakan ini tidak dapat dibatalkan</strong>.</p>
                </div>

                <p class="mb-1">Pastikan file database yang akan digunakan untuk me-restore adalah <strong>"benar - benar"</strong> file <a class="link-text" href="?page=bdb">backup</a> yang telah dibuat sebelumnya, sehingga sistem dapat berjalan dengan normal dan tidak terjadi error.</p>
            </div>
            <div class="card-action">
                <form method="post" enctype="multipart/form-data">
                    <div class="file-field input-field col s12 m6 l6">
                        <div class="btn light-green darken-1">
                            <span>File</span>
                            <input type="file" name="file" accept=".sql" required>
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" placeholder="Upload file sql database" type="text">
                        </div>
                    </div>
                    <div class="input-field col s12 m6 l6">
                        <button type="submit" class="btn-large blue waves-effect waves-light" name="restore"><i class="material-icons">restore</i> RESTORE</button>
                    </div>
                    <small class="grey-text text-darken-3">Ukuran maksimal <strong>32 MB</strong> | Format <strong>SQL</strong></small>
                </form>
            </div>
        </div>
    </div>
<?php
}
?>
