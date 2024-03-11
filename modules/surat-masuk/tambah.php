<?php
defined('APP') or exit('No direct script access allowed.');

$table = 'surat_masuk';

if (isset($_POST['submit'])) {
    if ($_POST['nomor_agenda'] == ""
        || $_POST['kode'] == ""
        || $_POST['kode_preview'] == ""
        || $_POST['nomor_surat'] == ""
        || $_POST['tanggal_surat'] == ""
        || $_POST['asal'] == ""
        || $_POST['perihal'] == ""
        || $_POST['tanggal_diterima'] == ""
        || $_POST['diteruskan_kepada'] == "") {
        $_SESSION['error'] = 'ERROR! Semua form wajib diisi';
        redirectTo('?page=sm&act=add');
    } else {
        $nomor_agenda     = $_POST['nomor_agenda'];
        $kode             = $_POST['kode'];
        $nomor_surat      = substr(escapeStr($_POST['nomor_surat']), 0, 100);
        $tanggal_surat    = $_POST['tanggal_surat'];
        $asal             = $_POST['asal'];
        $perihal          = $_POST['perihal'];
        $tanggal_diterima = $_POST['tanggal_diterima'];
        $indeks           = $_POST['indeks'];
        $keterangan       = $_POST['keterangan'];
        $diteruskan_kepada = $_POST['diteruskan_kepada'];

        if (! isNumeric($nomor_agenda)) {
            $_SESSION['nomor_agenda'] = 'Nomor agenda hanya boleh berisi angka!';
            goBack();
        } else {
            if (! isNumeric($kode)) {
                $_SESSION['kode'] = 'Kode surat hanya boleh berisi angka!';
                goBack();
            } else {
                $check = isExist(
                    $pdo,
                    $table,
                    ['nomor_surat' => $nomor_surat]
                );

                if ($check) {
                    $_SESSION['nomor_surat'] = 'Nomor surat sudah ada!';
                    goBack();
                } else {
                    if (! isValidDate($tanggal_surat)) {
                        $_SESSION['tanggal_surat'] = 'Tanggal surat tidak valid!';
                        goBack();
                    } else {
                        if (! isValidDate($tanggal_diterima)) {
                            $_SESSION['tanggal_diterima'] = 'Tanggal diterima tidak valid!';
                            goBack();
                        } else {
                            $data = [
                                'nomor_agenda'  => escapeStr($nomor_agenda),
                                'kode_id'       => escapeStr($kode),
                                'nomor_surat'   => $nomor_surat,
                                'tanggal_surat' => escapeStr($tanggal_surat),
                                'asal'    => escapeStr($asal),
                                'perihal' => escapeStr($perihal),
                                'tanggal_diterima' => escapeStr($tanggal_diterima),
                                'indeks'     => escapeStr($indeks),
                                'keterangan' => escapeStr($keterangan),
                                'tahun'      => date('Y'),
                                'diteruskan_kepada' => serialize($diteruskan_kepada),
                                'user_id'    => $_SESSION['id'],
                            ];

                            $all_mime = [
                                'image/png',
                                'image/jpg',
                                'image/jpeg',
                                'application/pdf',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            ];
                            $all_ext  = ['png', 'jpg', 'jpeg', 'pdf', 'doc', 'docx'];
                            $tmp_file = $_FILES['file']['tmp_name'];
                            $file = strtolower($_FILES['file']['name']);
                            $size = $_FILES['file']['size'];
                            $dir  = 'uploads/surat-masuk/';

                            createDir($dir);

                            if (! empty($file)) {
                                $ext   = getExtension($file);
                                $nfile = md5(time()) . '.' . $ext;

                                if (isValidFile($tmp_file, $all_mime)
                                && in_array($ext, $all_ext)) {
                                    if ($size < 2097152) {
                                        if (move_uploaded_file($tmp_file, $dir . $nfile)) {
                                            $data['lampiran'] = $nfile;

                                            store($pdo, $table, $data);

                                            $_SESSION['success'] = 'SUKSES! Data berhasil ditambahkan';
                                            redirectTo('?page=sm');
                                        } else {
                                            $_SESSION['error'] = 'ERROR! File gagal di-upload';
                                            goBack();
                                        }
                                    } else {
                                        $_SESSION['errorFile'] = 'Ukuran file terlalu besar!';
                                        goBack();
                                    }
                                } else {
                                    $_SESSION['errorFile'] = 'Format file tidak valid!';
                                    goBack();
                                }
                            } else {
                                store($pdo, $table, $data);

                                $_SESSION['success'] = 'SUKSES! Data berhasil ditambahkan';
                                redirectTo('?page=sm');
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
                    <li class="waves-effect waves-light"><a href="" class="judul"><i class="material-icons">mail</i> Tambah Surat Masuk</a></li>
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
        <form method="post" action="" enctype="multipart/form-data">
            <div class="card">
                <div class="card-content row">
                    <div class="input-field col s12 l6">
                        <i class="material-icons prefix md-prefix">looks_one</i>
                        <input id="nomor_agenda" type="number" class="validate" maxlength="10" name="nomor_agenda" value="<?php echo lastAgendaNumber($pdo, $table) ?>" required>
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
                        <input id="kode" type="hidden" name="kode">
                        <input id="kode_preview" type="text" name="kode_preview" class="validate" required>
                        <?php
                            if (isset($_SESSION['kode'])) {
                                echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$_SESSION['kode'].'</div>';
                                unset($_SESSION['kode']);
                            }
                        ?>
                        <label for="kode_preview">Kode Arsip (Klasifikasi)</label>
                    </div>
                    <div class="input-field col s12 l6">
                        <i class="material-icons prefix md-prefix">looks_two</i>
                        <input id="nomor_surat" type="text" class="validate" name="nomor_surat" required>
                        <?php
                            if (isset($_SESSION['nomor_surat'])) {
                                echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$_SESSION['nomor_surat'].'</div>';
                                unset($_SESSION['nomor_surat']);
                            }
                        ?>
                        <label for="nomor_surat">Nomor Surat</label>
                    </div>
                    <div class="input-field col s12 l6">
                        <i class="material-icons prefix md-prefix">date_range</i>
                        <input id="tanggal_surat" type="text" class="validate datepicker" name="tanggal_surat" required>
                        <?php
                            if (isset($_SESSION['tanggal_surat'])) {
                                echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$_SESSION['tanggal_surat'].'</div>';
                                unset($_SESSION['tanggal_surat']);
                            }
                        ?>
                        <label for="tanggal_surat">Tanggal Surat</label>
                    </div>
                    <div class="input-field col s12 l6">
                        <i class="material-icons prefix md-prefix">place</i>
                        <input id="asal" type="text" class="validate" name="asal" required>
                        <label for="asal">Asal Surat</label>
                    </div>
                    <div class="input-field col s12 l6">
                        <i class="material-icons prefix md-prefix">description</i>
                        <textarea id="perihal" name="perihal" class="validate" maxlength="255" required oninput="this.style.height = 'auto'; this.style.height = this.scrollHeight + 'px'"></textarea>
                        <label for="perihal">Perihal</label>
                    </div>
                    <div class="input-field col s12 l6">
                        <i class="material-icons prefix md-prefix">date_range</i>
                        <input id="tanggal_diterima" type="text" class="validate datepicker" name="tanggal_diterima" value="<?php echo date('Y-m-d') ?>" required>
                        <?php
                            if (isset($_SESSION['tanggal_diterima'])) {
                                echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$_SESSION['tanggal_diterima'].'</div>';
                                unset($_SESSION['tanggal_diterima']);
                            }
                        ?>
                        <label for="tanggal_diterima">Tanggal diterima</label>
                    </div>
                    <div class="input-field col s12 l6">
                        <i class="material-icons prefix md-prefix">folder_open</i>
                        <input id="indeks" type="text" class="validate" name="indeks">
                        <label for="indeks">Indeks</label>
                    </div>
                    <div class="input-field col s12 l6">
                        <i class="material-icons prefix md-prefix">featured_play_list</i>
                        <input id="keterangan" type="text" class="validate" name="keterangan">
                        <label for="keterangan">Keterangan</label>
                    </div>
                    <div class="input-field col s12 l6">
                        <div class="file-field">
                            <div class="btn light-green darken-1">
                                <span>File</span>
                                <input type="file" id="file" name="file" accept=".png, .jpg, .jpeg, .pdf, .docx, .doc">
                            </div>
                            <div class="file-path-wrapper">
                                <input class="file-path validate" type="text" placeholder="Upload file lampiran">
                            </div>
                            <?php
                                if (isset($_SESSION['errorFile'])) {
                                    echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$_SESSION['errorFile'].'</div>';
                                    unset($_SESSION['errorFile']);
                                }
                            ?>
                            <small class="grey-text text-darken-3">Ukuran maksimal <strong>2 MB</strong> | Format <strong>PNG</strong>, <strong>JPG</strong>, <strong>PDF</strong> dan <strong>DOC</strong></small>
                        </div>
                    </div>
                    <div class="input-field col s12 l6">
                        <div style="display:flex;justify-content:space-between">
                            <div class="input-icon-prefix">
                                <i class="material-icons prefix md-prefix">person_pin</i>
                            </div>
                            <div class="input-container">
                                <select class="browser-default validate" name="diteruskan_kepada[]" id="diteruskan_kepada" required multiple="multiple">
                                    <?php
                                    $sql = "SELECT nama
                                            FROM pegawai
                                            LIMIT 100";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute();
                                    $rows = $stmt->fetchAll();
                                    if (count($rows) > 0) {
                                        foreach($rows as $row){ ?>
                                            <option value="<?php echo $row->nama ?>"><?php echo $row->nama ?></option>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <option value="">Tidak ada data!</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-action">
                    <button type="submit" name="submit" class="btn-large blue waves-effect waves-light"><i class="material-icons">done</i> SIMPAN</button>

                    <a href="?page=sm" class="btn-large deep-orange waves-effect waves-light white-text"><i class="material-icons">clear</i> BATAL</a>
                </div>
            </div>
        </form>
    </div>
<?php
}
?>
