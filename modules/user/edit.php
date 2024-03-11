<?php
defined('APP') or exit('No direct script access allowed.');

$table = 'users';
$id    = $_REQUEST['id'];

if (isset($_POST['submit'])) {
    if ($id == 1) {
        redirectTo('?page=usr');
    } else {
        if (! isValidUserLevel($_POST['level'])) {
            $_SESSION['levelAkun'] = 'Level akun hanya boleh berisi angka 1 atau 2!';
            goBack();
        } else {
            $data = ['level' => escapeStr($_POST['level'])];

            update($pdo, $table, $data, ['id' => $id]);

            $_SESSION['success'] = 'SUKSES! Level akun berhasil diperbarui';
            redirectTo('?page=usr');
        }
    }
} else {
    $columns = 'id, username, nama, email, nip, level';
    $row     = findById($pdo, $columns, $table, $id);

    if ($row->total > 0) {
        if ($row->id == 1) {
            redirectTo('?page=usr');
        } ?>

        <div class="row">
            <nav class="secondary-nav">
                <div class="nav-wrapper blue-grey darken-1">
                    <ul>
                        <li class="waves-effect waves-light"><a href="" class="judul"><i class="material-icons">edit</i> Edit Level Akun</a></li>
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
                            <input type="hidden" value="<?php echo $row->id ?>" name="id">
                            <i class="material-icons prefix md-prefix">account_circle</i>
                            <input id="username" type="text" value="<?php echo $row->username ?>" class="grey-text text-darken-2" disabled>
                            <label for="username">Username</label>
                        </div>
                        <div class="input-field col s12 l6">
                            <i class="material-icons prefix md-prefix">text_fields</i>
                            <input id="nama" type="text" value="<?php echo $row->nama ?>" class="grey-text text-darken-2" disabled>
                            <label for="nama">Nama</label>
                        </div>
                        <div class="input-field col s12 l6">
                            <i class="material-icons prefix md-prefix">mail</i>
                            <input id="email" type="email" value="<?php echo $row->email ?>" class="grey-text text-darken-2" disabled>
                            <label for="email">Email</label>
                        </div>
                        <div class="input-field col s12 l6">
                            <i class="material-icons prefix md-prefix">looks_one</i>
                            <input id="nip" type="text" value="<?php echo $row->nip ?>" class="grey-text text-darken-2" disabled>
                            <label for="nip">NIP</label>
                        </div>
                        <div class="input-field col s12 l6">
                            <i class="material-icons prefix md-prefix">supervisor_account</i>
                            <select name="level" id="level" required>
                                <option value="<?php echo $row->level ?>"><?php echo $row->level == 1 ? 'Administrator': 'User Biasa' ?></option>
                                <option value="2">User Biasa</option>
                                <option value="1">Administrator</option>
                            </select>
                            <?php
                            if (isset($_SESSION['levelAkun'])) {
                                echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$_SESSION['levelAkun'].'</div>';
                                unset($_SESSION['levelAkun']);
                            } ?>
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
    } else {
        redirectTo('?page=usr');
    }
}
?>
