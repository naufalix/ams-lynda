<?php
define('APP', true);

require_once __DIR__.'/app/database.php';
require_once __DIR__.'/app/functions.php';
require_once __DIR__.'/vendor/dompdf/autoload.inc.php';

$pdo = connectDb($hostname, $username, $password, $dbname);

if (! isLoggedIn()) {
    redirectTo('./');
}
?>
<!--
Name        : <?php echo appInfo()['name']."\n" ?>
Description : <?php echo appInfo()['description']."\n" ?>
Version     : <?php echo appInfo()['version']."\n" ?>
Developer   : <?php echo appInfo()['developer']."\n" ?>
Phone/WA    : <?php echo appInfo()['contact']."\n" ?>
Website     : <?php echo appInfo()['website']."\n" ?>
-->
<!doctype html>
<html lang="en">
<?php include __DIR__.'/parts/head.php' ?>

<body class="">
<header>
<?php include __DIR__.'/parts/menu.php' ?>
</header>

<main>
    <div class="container">

    <?php
    if (isset($_GET['page'])) {
        switch ($_GET['page']) {
            case 'sm':
                include "modules/surat-masuk/surat-masuk.php";
                break;
            case 'sk':
                include "modules/surat-keluar/surat-keluar.php";
                break;
            case 'sc':
                include "modules/surat-cuti/surat-cuti.php";
                break;

            case 'asm':
                include "modules/surat-masuk/agenda.php";
                break;
            case 'ask':
                include "modules/surat-keluar/agenda.php";
                break;
            case 'asc':
                include "modules/surat-cuti/agenda.php";
                break;

            case 'gsm':
                include "modules/surat-masuk/galeri.php";
                break;
            case 'gsk':
                include "modules/surat-keluar/galeri.php";
                break;

            case 'ks':
                include "modules/kode-surat/kode-surat.php";
                break;
            case 'pgw':
                include "modules/pegawai/pegawai.php";
                break;
            case 'usr':
                include "modules/user/user.php";
                break;

            case 'org':
                include "modules/profil-instansi/instansi.php";
                break;

            case 'bdb':
                include "modules/database/backup.php";
                break;
            case 'rdb':
                include "modules/database/restore.php";
                break;

            case 'pro':
                include "modules/profil-user/profil.php";
                break;
            default:
                redirectTo('app.php');
        }
    } else {
        include __DIR__.'/parts/dashboard.php';
    } ?>
    </div>
</main>
<?php include __DIR__.'/parts/footer.php' ?>
</body>
</html>
