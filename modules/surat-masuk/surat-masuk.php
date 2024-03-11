<?php
defined('APP') or exit('No direct script access allowed.');

if (isset($_GET['act'])) {
    switch ($_GET['act']) {
        case 'add':
            include "modules/surat-masuk/tambah.php";
            break;
        case 'show':
            include "modules/surat-masuk/detail.php";
            break;
        case 'edit':
            include "modules/surat-masuk/edit.php";
            break;
        case 'del':
            include "modules/surat-masuk/hapus.php";
            break;
        case 'disp':
            include "modules/disposisi/disposisi.php";
            break;
        case 'print':
            include "modules/disposisi/cetak.php";
            break;
        default:
            redirectTo('app.php');
    }
} else { ?>

    <div class="row">
        <nav class="secondary-nav">
            <div class="nav-wrapper blue-grey darken-1">
                <ul>
                    <li class="waves-effect waves-light hide-on-small-only"><a href="" class="judul"><i class="material-icons">mail</i> Surat Masuk</a></li>

                    <li class="waves-effect waves-light"><a href="?page=sm&act=add"><i class="material-icons md-24">add_circle</i> Tambah Data</a>
                    </li>
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
                    <th width="18%">Nomor Surat</th>
                    <th width="15%">Tanggal Surat</th>
                    <th width="25%">Asal Surat</th>
                    <th width="30%">Perihal</th>
                    <th width="5%">Tindakan</th>
                </tr>
            </thead>
        </table>
    </div>
<?php } ?>
