<?php defined('APP') or exit('No direct script access allowed.') ?>

<div class="row">
    <div class="col s12 dashboard-header hide-on-med-and-down">
        <div class="card">
            <div class="card-content">
                <div class="left">
                    <img class="org-logo" src="uploads/<?php echo showOrg($pdo)->logo ?>">
                </div>
                <h5 class="org-name"><?php echo showOrg($pdo)->instansi ?></h5>
                <p class="org-info"><?php echo showOrg($pdo)->alamat ?></p>
                <p class="org-info"><?php echo showOrg($pdo)->telepon ?> &minus; <?php echo showOrg($pdo)->email ?></p>
            </div>
        </div>
    </div>

    <div class="col s12 welcome">
        <div class="card blue lighten-5">
            <div class="card-content">
                <h4>Selamat Datang <?php echo ! empty($_SESSION['id']) ? userProfile($pdo)->nama : '' ?></h4>
                <p class="welcome-text">Berikut adalah informasi data yang tersimpan dalam sistem.
                </p>
            </div>
        </div>
    </div>

    <a href="?page=sm">
        <div class="col s12 m6 l4">
            <div class="card cyan">
                <div class="card-content">
                    <span class="card-title white-text"><i class="material-icons md-36">mail</i> Surat Masuk</span>
                    <h4 class="white-text link"><?php echo countAll($pdo, 'surat_masuk') ?></h4>
                </div>
            </div>
        </div>
    </a>

    <a href="?page=sk">
        <div class="col s12 m6 l4">
            <div class="card lime darken-1">
                <div class="card-content">
                    <span class="card-title white-text"><i class="material-icons md-36">send</i> Surat Keluar</span>
                    <h4 class="white-text link"><?php echo countAll($pdo, 'surat_keluar') ?></h4>
                </div>
            </div>
        </div>
    </a>

    <a href="?page=sc">
        <div class="col s12 m6 l4">
            <div class="card yellow darken-3">
                <div class="card-content">
                    <span class="card-title white-text"><i class="material-icons md-36">event</i> Surat Cuti</span>
                    <h4 class="white-text link"><?php echo countAll($pdo, 'surat_cuti') ?></h4>
                </div>
            </div>
        </div>
    </a>

    <a href="?page=ks">
        <div class="col s12 m6 l4">
            <div class="card blue accent-2">
                <div class="card-content">
                    <span class="card-title white-text"><i class="material-icons md-36">bookmark</i> Kode Surat</span>
                    <h4 class="white-text link"><?php echo countAll($pdo, 'kode_surat') ?></h4>
                </div>
            </div>
        </div>
    </a>

    <a href="?page=pgw">
        <div class="col s12 m6 l4">
            <div class="card green lighten-1">
                <div class="card-content">
                    <span class="card-title white-text"><i class="material-icons md-36">people_outline</i> Pegawai</span>
                    <h4 class="white-text link"><?php echo countAll($pdo, 'pegawai') ?></h4>
                </div>
            </div>
        </div>
    </a>

    <?php if (! empty($_SESSION['level'])) { ?>
        <?php if ($_SESSION['level'] == 1) { ?>
            <a href="?page=usr">
                <div class="col s12 m6 l4">
                    <div class="card deep-orange accent-2">
                        <div class="card-content">
                            <span class="card-title white-text"><i class="material-icons md-36">people</i> User</span>
                            <h4 class="white-text link"><?php echo countAll($pdo, 'users') ?></h4>
                        </div>
                    </div>
                </div>
            </a>
        <?php } ?>
    <?php } ?>

</div>
