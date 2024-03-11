<?php
defined('APP') or exit('No direct script access allowed.');

if (isset($_POST['submit'])) {
    if ($_POST['password'] == ""
        || $_POST['konfirmasi_password'] == ""
        || $_POST['password_lama'] == "") {
        $_SESSION['error'] = 'ERROR! Semua form wajib diisi';
        redirectTo('?page=pro&act=pass');
    } else {
        $password            = $_POST['password'];
        $konfirmasi_password = $_POST['konfirmasi_password'];
        $password_lama       = $_POST['password_lama'];

        if (strlen($password) < 5) {
            $_SESSION['errorPassword'] = 'Password minimal 5 karakter!';
            redirectTo('?page=pro&act=pass');
        } else {
            if ($konfirmasi_password != $password) {
                $_SESSION['errorKonfirmasiPassword'] = 'Konfirmasi password tidak cocok!';
                redirectTo('?page=pro&act=pass');
            } else {
                $saved_pass = userProfile($pdo)->password;
                if (password_verify($password_lama, $saved_pass)) {
                    $data = [
                        'password' => password_hash($password, PASSWORD_BCRYPT),
                    ];

                    update($pdo, 'users', $data, ['id' => $_SESSION['id']]);

                    $_SESSION['success'] = 'SUKSES! Password berhasil diperbarui';
                    redirectTo('?page=pro');
                } else {
                    $_SESSION['errorPasswordLama'] = 'Password lama Anda tidak cocok!';
                    redirectTo('?page=pro&act=pass');
                }
            }
        }
    }
} else { ?>

    <div class="row">
        <nav class="secondary-nav">
            <div class="nav-wrapper blue-grey darken-1">
                <ul>
                    <li class="waves-effect waves-light"><a href="" class="judul"><i class="material-icons">edit</i> Ganti Password</a></li>
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
        <form method="post" action="">
            <div class="card">
                <div class="card-content row">
                    <div class="input-field col s12 l6">
                        <i class="material-icons prefix md-prefix">lock_outline</i>
                        <input id="password" type="password" class="validate" name="password" minlength="5" required>
                        <?php
                            if (isset($_SESSION['errorPassword'])) {
                                echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$_SESSION['errorPassword'].'</div>';
                                unset($_SESSION['errorPassword']);
                            }
                        ?>
                        <label for="password">Password baru</label>
                    </div>
                    <div class="input-field col s12 l6">
                        <i class="material-icons prefix md-prefix">lock_open_outline</i>
                        <input id="konfirmasi_password" type="password" class="validate" name="konfirmasi_password" required>
                        <?php
                            if (isset($_SESSION['errorKonfirmasiPassword'])) {
                                echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$_SESSION['errorKonfirmasiPassword'].'</div>';
                                unset($_SESSION['errorKonfirmasiPassword']);
                            }
                        ?>
                        <label for="konfirmasi_password">Konfirmasi password baru</label>
                    </div>
                    <div class="input-field col s12 l6">
                        <i class="material-icons prefix md-prefix">lock</i>
                        <input id="password_lama" type="password" class="validate" name="password_lama" required>
                        <?php
                            if (isset($_SESSION['errorPasswordLama'])) {
                                echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$_SESSION['errorPasswordLama'].'</div>';
                                unset($_SESSION['errorPasswordLama']);
                            }
                        ?>
                        <label for="password_lama">Masukkan password lama</label>
                    </div>
                </div>
                <div class="card-action">
                    <button type="submit" name="submit" class="btn-large blue waves-effect waves-light"><i class="material-icons">done</i> SIMPAN</button>

                    <a href="?page=pro" class="btn-large deep-orange waves-effect waves-light white-text"><i class="material-icons">clear</i> BATAL</a>
                </div>
            </div>
        </form>
    </div>
<?php
}
?>
