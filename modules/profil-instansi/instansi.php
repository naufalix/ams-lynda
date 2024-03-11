<?php
defined('APP') or exit('No direct script access allowed.');

isAdmin();

if (isset($_POST['submit'])) {
    if ($_POST['dinas'] == ""
        || $_POST['instansi'] == ""
        || $_POST['alamat'] == ""
        || $_POST['telepon'] == ""
        || $_POST['email'] == ""
        || $_POST['website'] == ""
        || $_POST['pimpinan'] == ""
        || $_POST['nip'] == "") {
        $_SESSION['error'] = 'ERROR! Semua form wajib diisi';
        redirectTo('?page=org');
    } else {
        if (! isValidEmail($_POST['email'])) {
            $_SESSION['errorEmail'] = 'Email tidak valid!';
            redirectTo('?page=org');
        } else {
            if (! isValidUrl($_POST['website'])) {
                $_SESSION['errorWebsite'] = 'Url website tidak valid!';
                redirectTo('?page=org');
            } else {
                $table = 'instansi';
                $data  = [
                    'dinas'    => escapeStr($_POST['dinas']),
                    'instansi' => escapeStr($_POST['instansi']),
                    'alamat'   => escapeStr($_POST['alamat']),
                    'telepon'  => escapeStr($_POST['telepon']),
                    'email'    => escapeStr($_POST['email']),
                    'website'  => escapeStr($_POST['website']),
                    'pimpinan' => escapeStr($_POST['pimpinan']),
                    'nip'      => escapeStr($_POST['nip']),
                ];

                $all_mime = ['image/png', 'image/jpg', 'image/jpeg'];
                $all_ext  = ['png', 'jpg', 'jpeg'];
                $tmp_file = $_FILES['file']['tmp_name'];
                $file     = strtolower($_FILES['file']['name']);
                $size     = $_FILES['file']['size'];
                $dir      = 'uploads/';

                createDir($dir);

                if (! empty($file)) {
                    $ext   = getExtension($file);
                    $nfile = md5(time()) . '.' . $ext;

                    if (isValidFile($tmp_file, $all_mime)
                        && in_array($ext, $all_ext)) {
                        if ($size < 1048576) {
                            if (move_uploaded_file($tmp_file, $dir . $nfile)) {
                                unlink($dir . showOrg($pdo)->logo);

                                $data['logo'] = $nfile;

                                update($pdo, $table, $data, ['id' => 1]);

                                $_SESSION['success'] = 'SUKSES! Profil instansi berhasil diperbarui';
                                redirectTo('?page=org');
                            } else {
                                $_SESSION['error'] = 'ERROR! File gagal di-upload';
                                redirectTo('?page=org');
                            }
                        } else {
                            $_SESSION['errorFile'] = 'Ukuran file terlalu besar!';
                            goBack();
                        }
                    } else {
                        $_SESSION['errorFile'] = 'Format file tidak valid!';
                        goBack();
                    }
                } else {
                    update($pdo, $table, $data, ['id' => 1]);

                    $_SESSION['success'] = 'SUKSES! Profil instansi berhasil diperbarui';
                    redirectTo('?page=org');
                }
            }
        }
    }
} else { ?>

    <div class="row">
        <nav class="secondary-nav">
            <div class="nav-wrapper blue-grey darken-1">
                <ul>
                    <li class="waves-effect waves-light"><a href="" class="judul"><i class="material-icons">business</i> Profil Instansi</a></li>
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
        <form method="post" action="" enctype="multipart/form-data">
            <div class="card">
                <div class="card-content row">
                    <div class="input-field col s12 l6">
                        <i class="material-icons prefix md-prefix">work</i>
                        <input id="dinas" type="text" class="validate" name="dinas" value="<?php echo showOrg($pdo)->dinas ?>" required>
                        <label for="dinas">Nama Dinas/Yayasan</label>
                    </div>
                    <div class="input-field col s12 l6">
                        <i class="material-icons prefix md-prefix">business</i>
                        <input id="instansi" type="text" class="validate" name="instansi" value="<?php echo showOrg($pdo)->instansi ?>" required>
                        <label for="instansi">Nama Instansi</label>
                    </div>
                    <div class="input-field col s12 l6">
                        <i class="material-icons prefix md-prefix">place</i>
                        <input id="alamat" type="text" class="validate" name="alamat" value="<?php echo showOrg($pdo)->alamat ?>" required>
                        <label for="alamat">Alamat</label>
                    </div>
                    <div class="input-field col s12 l6">
                        <i class="material-icons prefix md-prefix">phone</i>
                        <input id="telepon" type="text" class="validate" name="telepon" value="<?php echo showOrg($pdo)->telepon ?>" required>
                        <label for="telepon">Telepon</label>
                    </div>
                    <div class="input-field col s12 l6">
                        <i class="material-icons prefix md-prefix">mail</i>
                        <input id="email" type="email" class="validate" name="email" value="<?php echo showOrg($pdo)->email ?>" required>
                        <?php
                            if (isset($_SESSION['errorEmail'])) {
                                echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$_SESSION['errorEmail'].'</div>';
                                unset($_SESSION['errorEmail']);
                            }
                        ?>
                        <label for="email">Email</label>
                    </div>
                    <div class="input-field col s12 l6">
                        <i class="material-icons prefix md-prefix">language</i>
                        <input id="website" type="url" class="validate" name="website" value="<?php echo showOrg($pdo)->website ?>" required>
                        <?php
                            if (isset($_SESSION['errorWebsite'])) {
                                echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$_SESSION['errorWebsite'].'</div>';
                                unset($_SESSION['errorWebsite']);
                            }
                        ?>
                        <label for="website">Website</label>
                    </div>
                    <div class="input-field col s12 l6">
                        <i class="material-icons prefix md-prefix">account_box</i>
                        <input id="pimpinan" type="text" class="validate" name="pimpinan" value="<?php echo showOrg($pdo)->pimpinan ?>" required>
                        <label for="pimpinan">Pimpinan</label>
                    </div>
                    <div class="input-field col s12 l6">
                        <i class="material-icons prefix md-prefix">looks_one</i>
                        <input id="nip" type="text" class="validate" name="nip" value="<?php echo showOrg($pdo)->nip ?>" required>
                        <label for="nip">NIP Pimpinan</label>
                    </div>
                    <div class="input-field col s12 l6">
                        <div class="file-field">
                            <div class="btn light-green darken-1">
                                <span>File</span>
                                <input type="file" id="file" name="file" accept=".png, .jpg, .jpeg">
                            </div>
                            <div class="file-path-wrapper">
                                <input class="file-path validate" type="text" placeholder="Upload logo instansi">
                            </div>
                            <?php
                                if (isset($_SESSION['errorFile'])) {
                                    echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$_SESSION['errorFile'].'</div>';
                                    unset($_SESSION['errorFile']);
                                }
                            ?>
                            <small class="grey-text text-darken-3">Ukuran maksimal <strong>1 MB</strong> | Format <strong>PNG</strong> atau <strong>JPG</strong></small>
                        </div>
                    </div>
                    <div class="input-field col s12 l6">
                        <img class="org-logo" src="uploads/<?php echo showOrg($pdo)->logo; ?>">
                    </div>
                </div>
                <div class="card-action">
                    <button type="submit" name="submit" class="btn-large blue waves-effect waves-light"><i class="material-icons">done</i> SIMPAN</button>
                </div>
            </div>
        </form>
    </div>
<?php
}
?>
