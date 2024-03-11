<?php
defined('APP') or exit('No direct script access allowed.');

if (isset($_POST['submit'])) {
    if ($_POST['nama'] == ""
        || $_POST['email'] == ""
        || $_POST['nip'] == "") {
        $_SESSION['error'] = 'ERROR! Semua form wajib diisi';
        redirectTo('?page=pro&act=edit');
    } else {
        if (! isValidEmail($_POST['email'])) {
            $_SESSION['errorEmail'] = 'Email tidak valid!';
            redirectTo('?page=pro&act=edit');
        } else {
            $data = [
                'nama'  => escapeStr($_POST['nama']),
                'email' => escapeStr($_POST['email']),
                'nip'   => escapeStr($_POST['nip']),
            ];

            update($pdo, 'users', $data, ['id' => $_SESSION['id']]);

            $_SESSION['success'] = 'SUKSES! Profil berhasil diperbarui';
            redirectTo('?page=pro');
        }
    }
} else { ?>

    <div class="row">
        <nav class="secondary-nav">
            <div class="nav-wrapper blue-grey darken-1">
                <ul>
                    <li class="waves-effect waves-light"><a href="" class="judul"><i class="material-icons">edit</i> Edit Profil</a></li>
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
                        <input id="username" type="text" class="validate grey-text" name="username" value="<?php echo userProfile($pdo)->username ?>" class="grey-text text-darken-2" disabled>
                        <label for="username">Username</label>
                    </div>
                    <div class="input-field col s12 l6">
                        <i class="material-icons prefix md-prefix">text_fields</i>
                        <input id="nama" type="text" class="validate" name="nama" value="<?php echo userProfile($pdo)->nama ?>" required>
                        <label for="nama">Nama</label>
                    </div>
                    <div class="input-field col s12 l6">
                        <i class="material-icons prefix md-prefix">email</i>
                        <input id="email" type="email" class="validate" name="email" value="<?php echo userProfile($pdo)->email ?>" required>
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
                        <input id="nip" type="text" class="validate" name="nip" value="<?php echo userProfile($pdo)->nip ?>" required>
                        <label for="nip">NIP</label>
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
