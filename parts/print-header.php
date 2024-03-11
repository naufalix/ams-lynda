<?php defined('APP') or exit('No direct script access allowed.') ?>

<div class="kop show-print">
    <?php
        $path = 'uploads/' . showOrg($pdo)->logo;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
    ?>
    <img class="kop-logo" src="<?php echo $logo ?>">
    <h6 class="kop-dinas"><?php echo showOrg($pdo)->dinas ?></h6>
    <h5 class="kop-instansi"><?php echo showOrg($pdo)->instansi ?></h5>
    <p class="kop-alamat">Alamat: <?php echo showOrg($pdo)->alamat ?></p>
    <p class="kop-kontak">Telp: <?php echo showOrg($pdo)->telepon.' - Email: '.showOrg($pdo)->email ?></p>
    <div class="kop-separator"></div>
</div>
