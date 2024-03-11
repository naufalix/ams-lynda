<?php
defined('APP') or exit('No direct script access allowed.');

isAdmin();

if (isset($_GET['act'])) {
    switch ($_GET['act']) {
        case 'add':
            include "tambah.php";
            break;
        case 'edit':
            include "edit.php";
            break;
        case 'del':
            include "hapus.php";
            break;
        default:
            redirectTo('app.php');
    }
} else { ?>

    <div class="row">
        <nav class="secondary-nav">
            <div class="nav-wrapper blue-grey darken-1">
                <ul>
                    <li class="waves-effect waves-light hide-on-small-only"><a href="" class="judul"><i class="material-icons">people</i> Manajemen User</a></li>

                    <li class="waves-effect waves-light"><a href="?page=usr&act=add"><i class="material-icons md-24">person_add</i> Tambah User</a></li>
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
                    <th width="14%">Username</th>
                    <th width="20%">Nama</th>
                    <th width="20%">Email</th>
                    <th width="18%">NIP</th>
                    <th width="13%">Level akun</th>
                    <th width="5%">Tindakan</th>
                </tr>
            </thead>
        </table>
    </div>
<?php } ?>
