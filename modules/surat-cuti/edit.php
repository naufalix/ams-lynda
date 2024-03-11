<?php
defined('APP') or exit('No direct script access allowed.');

$table = 'surat_cuti';
$id    = $_REQUEST['id'];

if (isset($_POST['submit'])) {
    if ($_POST['nomor_agenda'] == ""
        || $_POST['kode'] == ""
        || $_POST['kode_preview'] == ""
        || $_POST['nomor_surat'] == ""
        || $_POST['tanggal_surat'] == ""
        || $_POST['pegawai'] == ""
        || $_POST['nama_preview'] == ""
        || $_POST['jenis_cuti'] == ""
        || $_POST['lama_cuti'] == ""
        || $_POST['tanggal_cuti'] == "") {
        $_SESSION['error'] = 'ERROR! Semua form wajib diisi';
        redirectTo('?page=sc&act=edit&id=' . $id);
    } else {
        $nomor_agenda  = $_POST['nomor_agenda'];
        $kode          = $_POST['kode'];
        $nomor_surat   = $_POST['nomor_surat'];
        $tanggal_surat = $_POST['tanggal_surat'];
        $pegawai       = $_POST['pegawai'];
        $jenis_cuti    = $_POST['jenis_cuti'];
        $lama_cuti     = $_POST['lama_cuti'];
        $tanggal_cuti  = $_POST['tanggal_cuti'];

        if (! isNumeric($nomor_agenda)) {
            $_SESSION['nomor_agenda'] = 'Nomor agenda hanya boleh berisi angka!';
            goBack();
        } else {
            if (! isNumeric($kode)) {
                $_SESSION['kode'] = 'Kode surat hanya boleh berisi angka!';
                goBack();
            } else {
                if (! isValidDate($tanggal_surat)) {
                    $_SESSION['tanggal_surat'] = 'Tanggal surat tidak valid!';
                    goBack();
                } else {
                    if (! isNumeric($pegawai)) {
                        $_SESSION['pegawai'] = 'ID pegawai hanya boleh berisi angka!';
                        goBack();
                    } else {
                        if (! isValidDate($tanggal_cuti)) {
                            $_SESSION['tanggal_cuti'] = 'Tanggal cuti tidak valid!';
                            goBack();
                        } else {
                            $data = [
                                'nomor_agenda' => escapeStr($nomor_agenda),
                                'kode_id'      => escapeStr($kode),
                                'nomor_surat'  => escapeStr($nomor_surat),
                                'tanggal_surat' => escapeStr($tanggal_surat),
                                'pegawai_id'   => escapeStr($pegawai),
                                'jenis_cuti'   => escapeStr($jenis_cuti),
                                'lama_cuti'    => escapeStr($lama_cuti),
                                'tanggal_cuti' => escapeStr($tanggal_cuti),
                                'user_id'      => $_SESSION['id'],
                            ];

                            update($pdo, $table, $data, ['id' => $id]);

                            $_SESSION['success'] = 'SUKSES! Data berhasil diperbarui';
                            redirectTo('?page=sc');
                        }
                    }
                }
            }
        }
    }
} else {
    $columns = "$table.id,
                kode_id,
                nomor_agenda,
                kode,
                nomor_surat,
                tanggal_surat,
                pegawai_id,
                pegawai.nama,
                pegawai.nip,
                pangkat,
                jabatan,
                satuan,
                jenis_cuti,
                lama_cuti,
                tanggal_cuti";

    $row = getLeaveDetail($pdo, $columns, $table, $id);
    if ($row->total > 0) { ?>

        <div class="row">
            <nav class="secondary-nav">
                <div class="nav-wrapper blue-grey darken-1">
                    <ul>
                        <li class="waves-effect waves-light"><a href="" class="judul"><i class="material-icons">edit</i> Edit Surat Cuti</a></li>
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
                            <i class="material-icons prefix md-prefix">looks_one</i>
                            <input type="hidden" name="id" value="<?php echo $row->id ?>">

                            <input id="nomor_agenda" type="number" class="validate" maxlength="10" name="nomor_agenda" value="<?php echo $row->nomor_agenda ?>" required>
                            <?php
                                if (isset($_SESSION['nomor_agenda'])) {
                                    echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$_SESSION['nomor_agenda'].'</div>';
                                    unset($_SESSION['nomor_agenda']);
                                }
                            ?>
                            <label for="nomor_agenda">Nomor Agenda</label>
                        </div>
                        <div class="input-field col s12 l6">
                            <i class="material-icons prefix md-prefix">bookmark</i>
                            <input id="kode" type="hidden" name="kode" value="<?php echo $row->kode_id ?>">

                            <input id="kode_preview" type="text" name="kode_preview" class="validate" value="<?php echo $row->kode ?>" required>
                            <?php
                                if (isset($_SESSION['kode'])) {
                                    echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$_SESSION['kode'].'</div>';
                                    unset($_SESSION['kode']);
                                }
                            ?>
                            <label for="kode_preview">Kode Surat</label>
                        </div>
                        <div class="input-field col s12 l6">
                            <i class="material-icons prefix md-prefix">looks_two</i>
                            <input id="nomor_surat" type="text" class="validate" name="nomor_surat" value="<?php echo $row->nomor_surat ?>" required>
                            <label for="nomor_surat">Nomor Surat</label>
                        </div>
                        <div class="input-field col s12 l6">
                            <i class="material-icons prefix md-prefix">date_range</i>
                            <input id="tanggal_surat" type="text" class="validate datepicker" name="tanggal_surat" value="<?php echo $row->tanggal_surat ?>" required>
                            <?php
                                if (isset($_SESSION['tanggal_surat'])) {
                                    echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$_SESSION['tanggal_surat'].'</div>';
                                    unset($_SESSION['tanggal_surat']);
                                }
                            ?>
                            <label for="tanggal_surat">Tanggal Surat</label>
                        </div>

                        <div class="input-field col s12 l6">
                            <i class="material-icons prefix md-prefix actv">account_circle</i>
                            <input id="pegawai" type="hidden" name="pegawai" value="<?php echo $row->pegawai_id ?>">

                            <input id="nama_preview" type="text" class="validate" name="nama_preview" value="<?php echo $row->nama ?>" required>
                            <?php
                                if (isset($_SESSION['pegawai'])) {
                                    echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$_SESSION['pegawai'].'</div>';
                                    unset($_SESSION['pegawai']);
                                }
                            ?>
                            <label for="nama_preview">Nama Pegawai</label>
                        </div>
                        <div class="input-field col s12 l6">
                            <i class="material-icons prefix md-prefix actv">looks_one</i>
                            <input id="nip" type="text" name="nip" class="validate" value="<?php echo $row->nip ?>" required>
                            <label for="nip">NIP</label>
                        </div>
                        <div class="input-field col s12 l6">
                            <i class="material-icons prefix md-prefix actv">assignment_ind</i>
                            <input id="pangkat" type="text" name="pangkat" class="validate" value="<?php echo $row->pangkat ?>" required>
                            <label for="pangkat">Pangkat Golongan</label>
                        </div>
                        <div class="input-field col s12 l6">
                            <i class="material-icons prefix md-prefix actv">work</i>
                            <input id="jabatan" type="text" name="jabatan" class="validate" value="<?php echo $row->jabatan ?>" required>
                            <label for="jabatan">Jabatan</label>
                        </div>
                        <div class="input-field col s12 l6">
                            <i class="material-icons prefix md-prefix actv">business</i>
                            <input id="satuan" type="text" name="satuan" class="validate" value="<?php echo $row->satuan ?>" required>
                            <label for="satuan">Satuan Organisasi</label>
                        </div>

                        <div class="input-field col s12 l6">
                            <i class="material-icons prefix md-prefix">menu</i>
                            <select class="validate" name="jenis_cuti" id="jenis_cuti" required>
                                <option value="<?php echo $row->jenis_cuti ?>"><?php echo $row->jenis_cuti ?></option>
                                <option value="Cuti Tahunan">Cuti Tahunan</option>
                                <option value="Cuti Sakit">Cuti Sakit</option>
                                <option value="Cuti Besar">Cuti Besar</option>
                                <option value="Cuti Melahirkan">Cuti Melahirkan</option>
                                <option value="Cuti Karena Alasan Penting">Cuti Karena Alasan Penting</option>
                                <option value="Cuti diluar tanggungan negara">Cuti diluar tanggungan negara</option>
                            </select>
                        </div>
                        <div class="input-field col s12 l6">
                            <i class="material-icons prefix md-prefix actv">access_time</i>
                            <input id="lama_cuti" type="text" name="lama_cuti" class="validate" value="<?php echo $row->lama_cuti ?>" required>
                            <label for="lama_cuti">Lama Cuti</label>
                        </div>
                        <div class="input-field col s12 l6">
                            <i class="material-icons prefix md-prefix">date_range</i>
                            <input id="tanggal_cuti" type="text" class="validate datepicker" name="tanggal_cuti" value="<?php echo $row->tanggal_cuti ?>" required>
                            <?php
                                if (isset($_SESSION['tanggal_cuti'])) {
                                    echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$_SESSION['tanggal_cuti'].'</div>';
                                    unset($_SESSION['tanggal_cuti']);
                                }
                            ?>
                            <label for="tanggal_cuti">Tanggal Cuti</label>
                        </div>
                    </div>
                    <div class="card-action">
                        <button type="submit" name="submit" class="btn-large blue waves-effect waves-light"><i class="material-icons">done</i> SIMPAN</button>

                        <a href="?page=sc" class="btn-large deep-orange waves-effect waves-light white-text"><i class="material-icons">clear</i> BATAL</a>
                    </div>
                </div>
            </form>
        </div>
<?php
    } else {
        redirectTo('?page=sc');
    }
}
?>
