<?php
defined('APP') or exit('No direct script access allowed.');

$table = 'kode_surat';
$id    = $_REQUEST['id'];

if (isset($_POST['submit'])) {
    if ($_POST['kode'] == "" || $_POST['uraian'] == "") {
        $_SESSION['error'] = 'ERROR! Semua form wajib diisi';
        redirectTo('?page=ks&act=edit&id=' . $id);
    } else {
        $data = [
            'kode'    => escapeStr($_POST['kode']),
            'uraian'  => escapeStr($_POST['uraian']),
            'user_id' => $_SESSION['id'],
        ];

        update($pdo, $table, $data, ['id' => $id]);

        $_SESSION['success'] = 'SUKSES! Data berhasil diperbarui';
        redirectTo('?page=ks');
    }
} else {
    $row = findById($pdo, 'id, kode, uraian', $table, $id);
    if ($row->total > 0) { ?>

        <div class="row">
            <nav class="secondary-nav">
                <div class="nav-wrapper blue-grey darken-1">
                    <ul>
                        <li class="waves-effect waves-light"><a href="" class="judul"><i class="material-icons">edit</i> Edit Kode Surat</a></li>
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
                        <div class="input-field col s12 l3">
                            <input type="hidden" value="<?php echo $row->id ?>" name="id">
                            <i class="material-icons prefix md-prefix">looks_one</i>
                            <input id="kode" type="text" class="validate" name="kode" maxlength="50" value="<?php echo $row->kode ?>" required>
                            <label for="kode">Kode</label>
                        </div>
                        <div class="input-field col s12 l9">
                            <i class="material-icons prefix md-prefix">description</i>
                            <input id="uraian" type="text" class="validate" name="uraian" value="<?php echo $row->uraian ?>" required>
                            <label for="uraian">Uraian</label>
                        </div>
                    </div>
                    <div class="card-action">
                        <button type="submit" name="submit" class="btn-large blue waves-effect waves-light"><i class="material-icons">done</i> SIMPAN</button>

                        <a href="?page=ks" class="btn-large deep-orange waves-effect waves-light white-text"><i class="material-icons">clear</i> BATAL</a>
                    </div>
                </div>
            </form>
        </div>
<?php
    } else {
        redirectTo('?page=ks');
    }
}
?>
