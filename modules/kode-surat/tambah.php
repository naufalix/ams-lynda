<?php
defined('APP') or exit('No direct script access allowed.');

if (isset($_POST['submit'])) {
    if ($_POST['kode'] == "" || $_POST['uraian'] == "") {
        $_SESSION['error'] = 'ERROR! Semua form wajib diisi';
        redirectTo('?page=ks&act=add');
    } else {
        $table = 'kode_surat';
        $kode  = substr(escapeStr($_POST['kode']), 0, 50);
        $check = isExist(
            $pdo,
            $table,
            ['kode' => $kode]
        );

        if ($check) {
            $_SESSION['exist'] = 'Kode sudah ada!';
            goBack();
        } else {
            $data = [
                'kode'    => $kode,
                'uraian'  => escapeStr($_POST['uraian']),
                'user_id' => $_SESSION['id'],
            ];

            store($pdo, $table, $data);

            $_SESSION['success'] = 'SUKSES! Data berhasil disimpan';
            redirectTo('?page=ks');
        }
    }
} else { ?>

    <div class="row">
        <nav class="secondary-nav">
            <div class="nav-wrapper blue-grey darken-1">
                <ul>
                    <li class="waves-effect waves-light"><a href="" class="judul"><i class="material-icons">turned_in</i> Tambah Kode Surat</a></li>
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
                        <i class="material-icons prefix md-prefix">looks_one</i>
                        <input id="kode" type="text" class="validate" maxlength="50" name="kode" required>
                        <?php
                            if (isset($_SESSION['exist'])) {
                                echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$_SESSION['exist'].'</div>';
                                unset($_SESSION['exist']);
                            }
                        ?>
                        <label for="kode">Kode</label>
                    </div>
                    <div class="input-field col s12 l9">
                        <i class="material-icons prefix md-prefix">description</i>
                        <input id="uraian" type="text" class="validate" name="uraian" required>
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
}
?>
