<?php
defined('APP') or exit('No direct script access allowed.');

isSuperAdmin();

$dir = 'backups/';
?>

<div class="row">
    <nav class="secondary-nav">
        <div class="nav-wrapper blue-grey darken-1">
            <ul>
                <li class="waves-effect waves-light"><a href="" class="judul"><i class="material-icons">storage</i> Backup Database</a></li>
            </ul>
        </div>
    </nav>
</div>

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
if (isset($_GET['file'])) {
    $file = $dir . $_GET['file'];
    $ext  = getExtension($file);

    if ($ext == 'sql') {
        if (file_exists($file)) {
            downloadFile($file);
        } else {
            $_SESSION['error'] = 'ERROR! File sudah tidak tersedia';
            redirectTo('?page=bdb');
        }
    } else {
        redirectTo('?page=bdb');
    }
}

if (isset($_POST['backup'])) {
    $return = "";
    $conn   = mysqli_connect($hostname, $username, $password, $dbname);
    $tables = [];
    $result = mysqli_query($conn, 'SHOW TABLES');
    while ($row = mysqli_fetch_row($result)) {
        $tables[] = $row[0];
    }

    foreach ($tables as $table) {
        $result     = mysqli_query($conn, 'SELECT * FROM ' . $table);
        $num_fields = mysqli_num_fields($result);

        $return .= 'DROP TABLE `' . $table . '`;';
        $row2    = mysqli_fetch_row(mysqli_query($conn, 'SHOW CREATE TABLE ' . $table));
        $return .= "\n\n" . $row2[1] . ";\n\n";
        $return .= 'TRUNCATE TABLE `' . $table . "`;\n\n";

        for ($i = 0; $i < $num_fields; $i++) {
            while ($row = mysqli_fetch_row($result)) {
                $return .= 'INSERT INTO ' . $table . ' VALUES(';

                for ($j = 0; $j < $num_fields; $j++) {
                    $row[$j] = addslashes($row[$j]);
                    $row[$j] = str_replace("\n", "\n", $row[$j]);

                    if (isset($row[$j])) {
                        $return .= '"' . $row[$j] . '"' ;
                    } else {
                        $return .= '""';
                    }
                    if ($j < ($num_fields - 1)) {
                        $return .= ',';
                    }
                }
                $return .= ");\n";
            }
        }
        $return .= "\n\n\n";
    }

    createDir($dir);

    $sql_file = 'backup-database_'
                . $app_name
                . '_'
                . date('Ymd-His')
                . '.sql';

    $file   = $dir . $sql_file;
    $handle = fopen($file, 'w+');
    fwrite($handle, $return);
    fclose($handle); ?>

    <div class="row">
        <div class="card">
            <div class="card-content">
                <span class="card-title black-text"><div class="green-text"><i class="material-icons md-36">done</i>
                SUKSES! Database berhasil di-backup</div></span>
                <p class="mb-1" style="margin-top: 10px;">Silakan download file hasil backup dan simpan di tempat yang aman.</p>
            </div>
            <div class="card-action">
                <a href="?page=bdb&file=<?php echo $sql_file ?>" class="btn-large blue waves-effect waves-light white-text"><i class="material-icons">file_download</i> DOWNLOAD</a>

                <a href="?page=bdb" class="btn-large deep-orange waves-effect waves-light white-text"><i class="material-icons">add_circle</i> BUAT BARU</a>
            </div>
        </div>
    </div>
<?php
} else { ?>

    <div class="row">
        <div class="card">
            <div class="card-content">
                <p class="mb-1">Lakukan backup database secara berkala untuk membuat cadangan data yang bisa <a class="link-text" href="?page=rdb">di-restore</a> kapan saja ketika dibutuhkan.</p>

                <p class="mb-1">Silakan klik tombol <strong>"Backup"</strong> untuk memulai proses backup data.</p>
            </div>
            <div class="card-action">
                <form method="post" enctype="multipart/form-data" >
                    <button type="submit" class="btn-large blue waves-effect waves-light" name="backup"><i class="material-icons">backup</i> BACKUP</button>
                </form>
            </div>
        </div>
    </div>
<?php
}
?>
