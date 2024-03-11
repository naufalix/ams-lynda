<?php defined('APP') or exit('No direct script access allowed.') ?>

<nav class="blue-grey darken-1 hide-on-large-only">
    <div class="nav-wrapper">
        <a href="./" class="brand-logo center"><?php echo $app_name ?></a>
        <ul id="slide-out" class="side-nav" data-simplebar-direction="vertical">
            <li class="no-padding">
                <div class="side-header blue-grey darken-3">
                    <img class="side-logo" src="uploads/<?php echo showOrg($pdo)->logo ?>">
                    <h5 class="side-org-name"><?php echo showOrg($pdo)->instansi ?></h5>
                    <p class="side-org-address"><?php echo showOrg($pdo)->alamat ?></p>
                    <p class="side-org-contact"><?php echo showOrg($pdo)->telepon ?> <?php echo showOrg($pdo)->email ?></p>
                </div>
            </li>
            <li class="no-padding blue-grey darken-4">
                <ul class="collapsible collapsible-accordion">
                    <li>
                        <a class="collapsible-header"><i class="material-icons">account_circle</i><?php echo ! empty($_SESSION['id']) ? substr(userProfile($pdo)->nama, 0, 15) : '' ?></a>
                        <div class="collapsible-body">
                            <ul>
                                <li><a href="?page=pro">Profil Saya</a></li>
                                <li><a href="?page=pro&act=pass">Ganti Password</a></li>
                                <li><a href="logout.php">Logout</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </li>
            <li><a href="./"><i class="material-icons middle">dashboard</i>Beranda</a></li>
            <li class="no-padding">
                <ul class="collapsible collapsible-accordion">
                    <li>
                        <a class="collapsible-header"><i class="material-icons">repeat</i> Transaksi</a>
                        <div class="collapsible-body">
                            <ul>
                                <li><a href="?page=sm">Surat Masuk</a></li>
                                <li><a href="?page=sk">Surat Keluar</a></li>
                                <li><a href="?page=sc">Surat Cuti</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </li>
            <li class="no-padding">
                <ul class="collapsible collapsible-accordion">
                    <li>
                        <a class="collapsible-header"><i class="material-icons">assignment</i> Agenda</a>
                        <div class="collapsible-body">
                            <ul>
                                <li><a href="?page=asm">Surat Masuk</a></li>
                                <li><a href="?page=ask">Surat Keluar</a></li>
                                <li><a href="?page=asc">Surat Cuti</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </li>
            <li class="no-padding">
                <ul class="collapsible collapsible-accordion">
                    <li>
                        <a class="collapsible-header"><i class="material-icons">image</i> Galeri File</a>
                        <div class="collapsible-body">
                            <ul>
                                <li><a href="?page=gsm">Surat Masuk</a></li>
                                <li><a href="?page=gsk">Surat Keluar</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </li>
            <li class="no-padding">
                <ul class="collapsible collapsible-accordion">
                    <li>
                        <a class="collapsible-header"><i class="material-icons">storage</i> Master Data</a>
                        <div class="collapsible-body">
                            <ul>
                                <li><a href="?page=ks">Kode Surat</a></li>
                                <li><a href="?page=pgw">Pegawai</a></li>
                                <?php if (! empty($_SESSION['level'])) { ?>
                                    <?php if ($_SESSION['level'] == 1) { ?>
                                        <li><a href="?page=usr">User</a></li>
                                    <?php } ?>
                                <?php } ?>
                            </ul>
                        </div>
                    </li>
                </ul>
            </li>
            <?php if (! empty($_SESSION['level'])) { ?>
                <?php if ($_SESSION['level'] == 1) { ?>
                    <li class="no-padding">
                        <ul class="collapsible collapsible-accordion">
                            <li>
                                <a class="collapsible-header"><i class="material-icons">settings</i> Pengaturan</a>
                                <div class="collapsible-body">
                                    <ul>
                                        <li><a href="?page=org">Profil Instansi</a></li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </li>
                <?php } ?>
            <?php } ?>

            <?php if (! empty($_SESSION['id'])) { ?>
                <?php if ($_SESSION['id'] == 1) { ?>
                    <li class="no-padding">
                        <ul class="collapsible collapsible-accordion">
                            <li>
                                <a class="collapsible-header"><i class="material-icons">widgets</i> Utilitas</a>
                                <div class="collapsible-body">
                                    <ul>
                                        <li><a href="?page=bdb">Backup Database</a></li>
                                        <li><a href="?page=rdb">Restore Database</a></li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </li>
                <?php } ?>
            <?php } ?>
        </ul>

        <a href="#" data-activates="slide-out" class="button-collapse" id="menu"><i class="material-icons">menu</i></a>
    </div>
</nav>

<nav class="blue-grey darken-1 hide-on-med-and-down fixed-nav">
    <div class="nav-wrapper">
        <ul class="center">
            <li><a href="./" class="app-name"><?php echo $app_name ?></a></li>
            <?php if (! empty($app_name)) { ?>
                <li>
                    <div class="grs">
                    </div>
                </li>
            <?php } ?>
            <li><a href="./"><i class="material-icons"></i>&nbsp; Beranda</a></li>
            <li><a class="dropdown-button" href="#!" data-activates="transaksi">Transaksi <i class="material-icons md-18">arrow_drop_down</i></a></li>
                <ul id="transaksi" class='dropdown-content'>
                    <li><a href="?page=sm">Surat Masuk</a></li>
                    <li><a href="?page=sk">Surat Keluar</a></li>
                    <li><a href="?page=sc">Surat Cuti</a></li>
                </ul>
            <li><a class="dropdown-button" href="#!" data-activates="agenda">Agenda <i class="material-icons md-18">arrow_drop_down</i></a></li>
            <ul id="agenda" class='dropdown-content'>
                <li><a href="?page=asm">Surat Masuk</a></li>
                <li><a href="?page=ask">Surat Keluar</a></li>
                <li><a href="?page=asc">Surat Cuti</a></li>
            </ul>
            <li><a class="dropdown-button" href="#!" data-activates="galeri">Galeri File <i class="material-icons md-18">arrow_drop_down</i></a></li>
            <ul id="galeri" class='dropdown-content'>
                <li><a href="?page=gsm">Surat Masuk</a></li>
                <li><a href="?page=gsk">Surat Keluar</a></li>
            </ul>
            <li><a class="dropdown-button" href="#!" data-activates="master">Master Data <i class="material-icons md-18">arrow_drop_down</i></a></li>
            <ul id="master" class='dropdown-content'>
                <li><a href="?page=ks">Kode Surat</a></li>
                <li><a href="?page=pgw">Pegawai</a></li>
                <?php if (! empty($_SESSION['level'])) { ?>
                    <?php if ($_SESSION['level'] == 1) { ?>
                        <li class="divider"></li>
                        <li><a href="?page=usr">User</a></li>
                    <?php } ?>
                <?php } ?>
            </ul>
            <?php if (! empty($_SESSION['level'])) { ?>
                <?php if ($_SESSION['level'] == 1) { ?>
                    <li><a class="dropdown-button" href="#!" data-activates="pengaturan">Pengaturan <i class="material-icons md-18">arrow_drop_down</i></a></li>
                    <ul id="pengaturan" class='dropdown-content'>
                        <li><a href="?page=org">Profil Instansi</a></li>
                        <?php if (! empty($_SESSION['id'])) { ?>
                            <?php if ($_SESSION['id'] == 1) { ?>
                                <li class="divider"></li>
                                <li><a href="?page=bdb">Backup Database</a></li>
                                <li><a href="?page=rdb">Restore Database</a></li>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                <?php } ?>
            <?php } ?>
            <li class="right"><a class="dropdown-button" href="#!" data-activates="akun"><i class="material-icons">account_circle</i> <?php echo ! empty($_SESSION['id']) ? substr(userProfile($pdo)->nama, 0, 15) : '' ?> <i class="material-icons md-18">arrow_drop_down</i></a></li>
            <ul id="akun" class='dropdown-content'>
                <li><a href="?page=pro">Profil Saya</a></li>
                <li><a href="?page=pro&act=pass">Ganti Password</a></li>
                <li class="divider"></li>
                <li><a href="logout.php"><i class="material-icons">settings_power</i> Logout</a></li>
            </ul>
        </ul>
    </div>
</nav>
