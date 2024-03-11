<?php
defined('APP') or exit('No direct script access allowed.');

if (isset($_GET['act'])) {
    switch ($_GET['act']) {
        case 'add':
            include "modules/pegawai/tambah.php";
            break;
        case 'show':
            include "modules/pegawai/detail.php";
            break;
        case 'edit':
            include "modules/pegawai/edit.php";
            break;
        case 'del':
            include "modules/pegawai/hapus.php";
            break;
        case 'imp':
            include "modules/pegawai/import.php";
            break;
        default:
            redirectTo('app.php');
    }
} else { ?>

    <div class="row">
        <nav class="secondary-nav">
            <div class="nav-wrapper blue-grey darken-1">
                <ul>
                    <li class="waves-effect waves-light hide-on-small-only"><a href="" class="judul"><i class="material-icons">people_outline</i> Pegawai</a></li>

                    <li class="waves-effect waves-light"><a href="?page=pgw&act=add"><i class="material-icons md-24">add_circle</i> Tambah Data</a>
                    </li>

                    <li class="waves-effect waves-light"><a href="?page=pgw&act=imp"><i class="material-icons md-24">file_upload</i> Impor Data</a></li>
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
                    <th width="25%">Nama</th>
                    <th width="18%">NIP</th>
                    <th width="20%">Pangkat Golongan</th>
                    <th width="25%">Jabatan</th>
                    <th width="5%">Tindakan</th>
                </tr>
            </thead>
        </table>
    </div>
<?php } ?>
