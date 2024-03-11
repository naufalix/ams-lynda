<?php
defined('APP') or exit('No direct script access allowed.');

if (isset($_GET['act'])) {
    switch ($_GET['act']) {
        case 'edit':
            include "modules/profil-user/edit.php";
            break;
        case 'pass':
            include "modules/profil-user/password.php";
            break;
        default:
            redirectTo('app.php');
    }
} else { ?>

    <div class="row">
        <nav class="secondary-nav">
            <div class="nav-wrapper blue-grey darken-1">
                <ul>
                    <li class="waves-effect waves-light"><a href="" class="judul"><i class="material-icons">person</i> Profil Saya</a></li>
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

    <div class="row card-detail">
        <div class="card">
            <div class="card-content table-responsive">
                <table>
                    <tbody>
                        <tr>
                            <td width="120px">Username</td>
                            <td width="1px">:</td>
                            <td><?php echo userProfile($pdo)->username ?></td>
                        </tr>
                        <tr>
                            <td>Nama</td>
                            <td>:</td>
                            <td><?php echo userProfile($pdo)->nama ?></td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td>:</td>
                            <td><?php echo userProfile($pdo)->email ?></td>
                        </tr>
                        <tr>
                           <td>NIP</td>
                           <td>:</td>
                           <td><?php echo userProfile($pdo)->nip ?></td>
                        </tr>
                        <tr>
                            <td>Level akun</td>
                            <td>:</td>
                            <td>
                                <?php
                                if (userProfile($pdo)->id == 1) {
                                    $level = 'Super Admin <i class="material-icons orange-text">star</i>';
                                } else {
                                    $level = userProfile($pdo)->level == 1 ?  'Administrator' : 'User Biasa';
                                } echo $level ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-action">
                <a href="?page=pro&act=edit" class="btn-large blue waves-effect waves-light white-text"><i class="material-icons">edit</i> EDIT PROFIL</a>

                <a href="?page=pro&act=pass" class="btn-large deep-orange waves-effect waves-light white-text"><i class="material-icons">lock_open</i> GANTI PASSWORD</a>
            </div>
        </div>
    </div>
<?php
}
?>
