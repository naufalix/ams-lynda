<?php
defined('APP') or exit('No direct script access allowed.');

if (isset($_POST['submit'])) {
    if ($_POST['username'] == ""
        || $_POST['password'] == ""
        || $_POST['nama'] == ""
        || $_POST['email'] == ""
        || $_POST['nip'] == ""
        || $_POST['level'] == "") {
        $_SESSION['error'] = 'ERROR! Semua form wajib diisi!';
        redirectTo('?page=usr&act=add');
    } else {
        $table    = 'users';
        $username = strtolower($_POST['username']);
        $password = $_POST['password'];
        $nama     = $_POST['nama'];
        $email    = $_POST['email'];
        $nip      = $_POST['nip'];
        $level    = $_POST['level'];

        $checkUsername = isExist(
            $pdo,
            $table,
            ['username' => $username]
        );
        if ($checkUsername) {
            $_SESSION['errorUsername'] = 'Username sudah terpakai. Gunakan yang lain!';
            goBack();
        } else {
            if (strlen($username) < 5) {
                $_SESSION['errorUsername'] = 'Username minimal 5 karakter!';
                goBack();
            } else {
                $checkEmail = isExist(
                    $pdo,
                    $table,
                    ['email' => $email]
                );
                if ($checkEmail) {
                    $_SESSION['errorEmail'] = 'Email sudah terdaftar!';
                    goBack();
                } else {
                    if (! isValidEmail($email)) {
                        $_SESSION['errorEmail'] = 'Email tidak valid!';
                        goBack();
                    } else {
                        if (strlen($password) < 5) {
                            $_SESSION['errorPassword'] = 'Password minimal 5 karakter!';
                            goBack();
                        } else {
                            if (! isValidUserLevel($level)) {
                                $_SESSION['levelAkun'] = 'Level akun hanya boleh berisi angka 1 atau 2!';
                                goBack();
                            } else {
                                $data = [
                                    'username' => escapeStr($username),
                                    'password' => password_hash($password, PASSWORD_BCRYPT),
                                    'nama'     => escapeStr($nama),
                                    'email'    => escapeStr($email),
                                    'nip'      => escapeStr($nip),
                                    'level'    => escapeStr($level),
                                ];

                                store($pdo, $table, $data);

                                $_SESSION['success'] = 'SUKSES! User baru berhasil ditambahkan';
                                redirectTo('?page=usr');
                            }
                        }
                    }
                }
            }
        }
    }
} else { ?>

    <div class="row">
        <nav class="secondary-nav">
            <div class="nav-wrapper blue-grey darken-1">
                <ul>
                    <li class="waves-effect waves-light"><a href="" class="judul"><i class="material-icons">person_add</i> Tambah User</a></li>
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
                        <i class="material-icons prefix md-prefix">account_circle</i>
                        <input id="username" type="text" class="validate" name="username" minlength="5" autocomplete="off" required>
                        <?php
                            if (isset($_SESSION['errorUsername'])) {
                                echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$_SESSION['errorUsername'].'</div>';
                                unset($_SESSION['errorUsername']);
                            }
                        ?>
                        <label for="username">Username</label>
                    </div>
                    <div class="input-field col s12 l6">
                        <i class="material-icons prefix md-prefix">text_fields</i>
                        <input id="nama" type="text" class="validate" name="nama" required>
                        <label for="nama">Nama</label>
                    </div>
                    <div class="input-field col s12 l6">
                        <i class="material-icons prefix md-prefix">mail</i>
                        <input id="email" type="email" class="validate" name="email" required>
                        <?php
                            if (isset($_SESSION['errorEmail'])) {
                                echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$_SESSION['errorEmail'].'</div>';
                                unset($_SESSION['errorEmail']);
                            }
                        ?>
                        <label for="email">Email</label>
                    </div>
                    <div class="input-field col s12 l6">
                        <i class="material-icons prefix md-prefix">looks_one</i>
                        <input id="nip" type="text" class="validate" name="nip" required>
                        <label for="nip">NIP</label>
                    </div>
                    <div class="input-field col s12 l6">
                        <i class="material-icons prefix md-prefix">lock</i>
                        <input id="password" type="password" class="validate" name="password" minlength="5" autocomplete="off" required>
                        <?php
                            if (isset($_SESSION['errorPassword'])) {
                                echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$_SESSION['errorPassword'].'</div>';
                                unset($_SESSION['errorPassword']);
                            }
                        ?>
                        <label for="password">Password</label>
                    </div>
                    <div class="input-field col s12 l6">
                        <i class="material-icons prefix md-prefix">supervisor_account</i>
                        <select name="level" id="level" required>
                            <option value="">Pilih level akun</option>
                            <option value="2">User Biasa</option>
                            <option value="1">Administrator</option>
                        </select>
                        <?php
                            if (isset($_SESSION['levelAkun'])) {
                                echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$_SESSION['levelAkun'].'</div>';
                                unset($_SESSION['levelAkun']);
                            }
                        ?>
                    </div>
                </div>
                <div class="card-action">
                    <button type="submit" name="submit" class="btn-large blue waves-effect waves-light"><i class="material-icons">done</i> SIMPAN</button>

                    <a href="?page=usr" class="btn-large deep-orange waves-effect waves-light white-text"><i class="material-icons">clear</i> BATAL</a>
                </div>
            </div>
        </form>
    </div>
<?php
    }
?>
