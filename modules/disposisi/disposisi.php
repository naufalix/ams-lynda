<?php
defined('APP') or exit('No direct script access allowed.') ?>

<div class="row">
    <nav class="secondary-nav">
        <div class="nav-wrapper blue-grey darken-1">
            <ul>
                <li class="waves-effect waves-light"><a href="" class="judul"><i class="material-icons">assignment</i> Disposisi Surat</a></li>

                <?php if (isset($_GET['edit'])) { ?>
                    <li class="waves-effect waves-light"><a href="?page=sm&act=disp&id=<?php echo $_GET['id'] ?>"><i class="material-icons">arrow_back</i> Kembali</a></li>
                <?php } else { ?>
                    <li class="waves-effect waves-light"><a href="?page=sm"><i class="material-icons">arrow_back</i> Kembali</a></li>
                <?php } ?>
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

<?php
$table = 'disposisi';
$id    = $_REQUEST['id'];

include 'detail-surat.php';

if (empty($row->id)) {
    include 'tambah.php';
} else {
    if (isset($_GET['del'])) {
        destroy($pdo, $table, $_GET['did']);

        $_SESSION['success'] = 'SUKSES! Data berhasil dihapus';
        redirectTo('?page=sm&act=disp&id=' . $id);
    } elseif (isset($_GET['edit'])) {
        include 'edit.php';
    } else {
        include 'detail.php';
    }
}
?>
