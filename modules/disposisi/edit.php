<?php
defined('APP') or exit('No direct script access allowed.');

if (isset($_POST['edit'])) {
    if ($_POST['tujuan'] == "" || $_POST['isi'] == "") {
        $_SESSION['error'] = 'ERROR! Semua form wajib diisi';
        redirectTo('?page=sm&act=disp&id=' . $id . '&did=' . $row->id . '&edit');
    } else {
        $data = [
            'tujuan'   => escapeStr($_POST['tujuan']),
            'isi'      => escapeStr($_POST['isi']),
            'user_id'  => $_SESSION['id'],
        ];

        foreach ($data as $key => $value) {
            $column[] = $key . ' = :' . $key;
        }

        $columns = implode(', ', $column);
        $datas   = array_merge($data, ['surat_id' => $id]);
        $sql     = "UPDATE IGNORE $table
                    SET $columns
                    WHERE surat_id = :surat_id";

        $pdo->prepare($sql)->execute($datas);

        $_SESSION['success'] = 'SUKSES! Data berhasil diperbarui';
        redirectTo('?page=sm&act=disp&id=' . $id);
    }
} else { ?>

    <div class="row card-detail">
        <h5>Edit Disposisi</h5>
        <form method="post" action="">
            <div class="card">
                <div class="card-content row">
                    <div class="input-field col s12">
                        <i class="material-icons prefix md-prefix">person_pin</i>
                        <input id="tujuan_disposisi" type="text" class="validate" name="tujuan" value="<?php echo $row->tujuan ?>" required>
                        <label for="tujuan_disposisi">Tujuan Disposisi</label>
                    </div>
                    <div class="input-field col s12">
                        <i class="material-icons prefix md-prefix">description</i>
                        <textarea id="isi" name="isi" class="validate" required oninput="this.style.height = 'auto'; this.style.height = this.scrollHeight + 'px'"><?php echo $row->isi ?></textarea>
                        <label for="isi">Isi Disposisi</label>
                    </div>
                </div>
                <div class="card-action">
                    <button type="submit" name="edit" class="btn-large blue waves-effect waves-light"><i class="material-icons">done</i> SIMPAN</button>

                    <a href="?page=sm&act=disp&id=<?php echo $id ?>" class="btn-large deep-orange waves-effect waves-light white-text"><i class="material-icons">clear</i> BATAL</a>
                </div>
            </div>
        </form>
    </div>
<?php
}
?>
