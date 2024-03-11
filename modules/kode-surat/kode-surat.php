<?php
defined('APP') or exit('No direct script access allowed.');

if (isset($_GET['act'])) {
    switch ($_GET['act']) {
        case 'add':
            include "modules/kode-surat/tambah.php";
            break;
        case 'edit':
            include "modules/kode-surat/edit.php";
            break;
        case 'del':
            include "modules/kode-surat/hapus.php";
            break;
        case 'imp':
            include "modules/kode-surat/import.php";
            break;
        default:
            redirectTo('app.php');
    }
} else { ?>

    <div class="row">
        <nav class="secondary-nav">
            <div class="nav-wrapper blue-grey darken-1">
                <ul>
                    <li class="waves-effect waves-light hide-on-small-only"><a href="" class="judul"><i class="material-icons">turned_in</i> Kode Surat</a></li>

                    <li class="waves-effect waves-light"><a href="?page=ks&act=add"><i class="material-icons md-24">add_circle</i> Tambah Data</a></li>

                    <li class="waves-effect waves-light"><a href="?page=ks&act=imp"><i class="material-icons md-24">file_upload</i> Impor Data</a></li>
                </ul>
            </div>
        </nav>
    </div>

    <?php if (isset($_SESSION['success'])) { ?>
        <div id="alert-message" class="row">
            <div class="card green lighten-5">
                <div class="card-content notif">
                    <span class="card-title green-text"><i class="material-icons md-36">done</i> <?php echo $_SESSION['success'] ?></span>
                </div>
            </div>
        </div>
    <?php
        unset($_SESSION['success']);
    } ?>

    <div class="row">
        <table class="bordered mdl-data-table" id="table">
            <thead class="blue lighten-4">
                <tr>
                    <th width="1%">No</th>
                    <th width="20%">Kode</th>
                    <th width="55%">Uraian</th>
                    <th width="20%">Tindakan</th>
                </tr>
            </thead>
        </table>
    </div>
<?php
}
?>
