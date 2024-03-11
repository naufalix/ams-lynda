<?php
defined('APP') or exit('No direct script access allowed.');

if (isset($_POST['add'])) {
    if ($_POST['tujuan'] == "" || $_POST['isi'] == "") {
        $_SESSION['error'] = 'ERROR! Semua form wajib diisi';
        redirectTo('?page=sm&act=disp&id=' . $id);
    } else {
        $data = [
            'surat_id' => escapeStr($id),
            'tujuan'   => escapeStr($_POST['tujuan']),
            'isi'      => escapeStr($_POST['isi']),
            'user_id'  => $_SESSION['id'],
        ];

        store($pdo, $table, $data);

        $_SESSION['success'] = 'SUKSES! Data berhasil disimpan';
        redirectTo('?page=sm&act=disp&id=' . $id);
    }
} else { ?>

    <div class="row card-detail">
        <h5>Tambah Disposisi</h5>
        <form method="post" action="">
            <div class="card">
                <div class="card-content row">
                    <div class="input-field col s12">
                        <i class="material-icons prefix md-prefix">person_pin</i>
                        <input id="tujuan_disposisi" type="text" class="validate" name="tujuan" required>
                        <label for="tujuan_disposisi">Tujuan Disposisi</label>
                    </div>
                    <div class="input-field col s12">
                        <i class="material-icons prefix md-prefix">description</i>
                        <textarea id="isi" name="isi" class="validate" required oninput="this.style.height = 'auto'; this.style.height = this.scrollHeight + 'px'"></textarea>
                        <label for="isi">Isi Disposisi</label>
                    </div>
                </div>
                <div class="card-action">
                    <button type="submit" name="add" class="btn-large blue waves-effect waves-light"><i class="material-icons">done</i> SIMPAN</button>

                    <a href="?page=sm" class="btn-large deep-orange waves-effect waves-light white-text"><i class="material-icons">clear</i> BATAL</a>
                </div>
            </div>
        </form>
    </div>
<?php
}
?>
