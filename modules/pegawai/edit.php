<?php
defined('APP') or exit('No direct script access allowed.');

$table = 'pegawai';
$id    = $_REQUEST['id'];

if (isset($_POST['submit'])) {
    if ($_POST['nama'] == ""
        || $_POST['nip'] == ""
        || $_POST['pangkat'] == ""
        || $_POST['jabatan'] == ""
        || $_POST['satuan'] == "") {
        $_SESSION['error'] = 'ERROR! Semua form wajib diisi';
        redirectTo('?page=pgw&act=edit&id=' . $id);
    } else {
        $data = [
            'nama'    => escapeStr($_POST['nama']),
            'nip'     => escapeStr($_POST['nip']),
            'pangkat' => escapeStr($_POST['pangkat']),
            'jabatan' => escapeStr($_POST['jabatan']),
            'satuan'  => escapeStr($_POST['satuan']),
            'user_id' => $_SESSION['id'],
        ];

        update($pdo, $table, $data, ['id' => $id]);

        $_SESSION['success'] = 'SUKSES! Data berhasil diperbarui';
        redirectTo('?page=pgw');
    }
} else {
    $columns = "id,
                nama,
                nip,
                pangkat,
                jabatan,
                satuan";
    $row = findById($pdo, $columns, $table, $id);
    if ($row->total > 0) { ?>

        <div class="row">
            <nav class="secondary-nav">
                <div class="nav-wrapper blue-grey darken-1">
                    <ul>
                        <li class="waves-effect waves-light"><a href="" class="judul"><i class="material-icons">edit</i> Edit Pegawai</a></li>
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
                            <input type="hidden" value="<?php echo $row->id ?>" name="id">
                            <input id="nama" type="text" name="nama" class="validate" value="<?php echo $row->nama ?>" required>
                            <label for="nama">Nama</label>
                        </div>
                        <div class="input-field col s12 l6">
                            <i class="material-icons prefix md-prefix">looks_one</i>
                            <input id="nip" type="text" name="nip" class="validate" value="<?php echo $row->nip ?>" required>
                            <label for="nip">NIP</label>
                        </div>
                        <div class="input-field col s12 l6">
                            <i class="material-icons prefix md-prefix">assignment_ind</i>
                            <input id="pangkat" type="text" name="pangkat" class="validate" value="<?php echo $row->pangkat ?>" required>
                            <label for="pangkat">Pangkat Golongan</label>
                        </div>
                        <div class="input-field col s12 l6">
                            <i class="material-icons prefix md-prefix">work</i>
                            <input id="jabatan" type="text" name="jabatan" class="validate" value="<?php echo $row->jabatan ?>" required>
                            <label for="jabatan">Jabatan</label>
                        </div>
                        <div class="input-field col s12 l6">
                            <i class="material-icons prefix md-prefix">business</i>
                            <input id="satuan" type="text" name="satuan" class="validate" value="<?php echo $row->satuan ?>" required>
                            <label for="satuan">Satuan Organisasi</label>
                        </div>
                    </div>
                    <div class="card-action">
                        <button type="submit" name="submit" class="btn-large blue waves-effect waves-light"><i class="material-icons">done</i> SIMPAN</button>

                        <a href="?page=pgw" class="btn-large deep-orange waves-effect waves-light white-text"><i class="material-icons">clear</i> BATAL</a>
                    </div>
                </div>
            </form>
        </div>
<?php
    } else {
        redirectTo('?page=pgw');
    }
}
?>
