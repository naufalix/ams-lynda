<?php
ob_start();
session_start();

require_once __DIR__.'/config.php';

date_default_timezone_set($timezone);

function d($code)
{
    echo "<pre>";
    var_dump($code);
    echo "</pre>";
    die;
}

function connectDb($hostname, $username, $password, $dbname)
{
    $dsn = "mysql:host=$hostname;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_EMULATE_PREPARES   => true,
    ];

    try {
        $pdo = new \PDO($dsn, $username, $password, $options);
        return $pdo;
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
}

function showOrg($pdo)
{
    $sql  = "SELECT * FROM instansi WHERE id = 1 LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetch();
}

function userProfile($pdo)
{
    $sql = "SELECT id,
                    username,
                    password,
                    nama,
                    email,
                    nip,
                    level,
                    token
            FROM users
            WHERE id = :id
            LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $_SESSION['id']]);

    return $stmt->fetch();
}

function countAll($pdo, $table)
{
    $sql  = "SELECT count(*) AS total FROM $table";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return number_format($stmt->fetch()->total, 0, 0, '.');
}

function isExist($pdo, $table, $data)
{
    $column = [];
    foreach ($data as $key => $value) {
        $column[] = $key;
    }

    $condition = [];
    foreach ($data as $key => $value) {
        $condition[] = $key . ' = :' . $key;
    }

    $columns    = implode(', ', $column);
    $conditions = implode(' AND ', $condition);

    $sql = "SELECT count(*) AS total, $columns
            FROM $table
            WHERE $conditions
            LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);

    return $stmt->fetch()->total > 0 ? true : false;
}

function store($pdo, $table, $data)
{
    $column = [];
    foreach ($data as $key => $value) {
        $column[] = $key . ' = :' . $key;
    }

    $columns = implode(', ', $column);
    $sql     = "INSERT IGNORE INTO $table SET $columns";

    return $pdo->prepare($sql)->execute($data);
}

function findById($pdo, $columns, $table, $id)
{
    $sql = "SELECT count(*) AS total, $columns
            FROM $table
            WHERE id = :id
            LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);

    return $stmt->fetch();
}

function getMailDetail($pdo, $columns, $table, $id)
{
    $sql = "SELECT COUNT(*) AS total, $columns
            FROM $table
            LEFT JOIN kode_surat
            ON kode_surat.id = $table.kode_id
            LEFT JOIN users
            ON users.id = $table.user_id
            WHERE $table.id = :id
            LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);

    return $stmt->fetch();
}

function getLeaveDetail($pdo, $columns, $table, $id)
{
    $sql = "SELECT COUNT(*) AS total, $columns
            FROM $table
            LEFT JOIN kode_surat
            ON kode_surat.id = $table.kode_id
            LEFT JOIN pegawai
            ON pegawai.id = $table.pegawai_id
            LEFT JOIN users
            ON users.id = $table.user_id
            WHERE $table.id = :id
            LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);

    return $stmt->fetch();
}

function update($pdo, $table, $data, $condition)
{
    $column = [];
    foreach ($data as $key => $value) {
        $column[] = $key . ' = :' . $key;
    }

    $columns = implode(', ', $column);
    $datas   = array_merge($data, $condition);
    $sql     = "UPDATE IGNORE $table SET $columns WHERE id = :id";

    return $pdo->prepare($sql)->execute($datas);
}

function destroy($pdo, $table, $id)
{
    $sql = "DELETE FROM $table WHERE id = :id";

    return $pdo->prepare($sql)->execute(['id' => $id]);
}

function deleteFile($pdo, $table, $id, $dir)
{
    $file = findByID($pdo, 'lampiran', $table, $id)->lampiran;
    if (! empty($file) && file_exists($dir . $file)) {
        unlink($dir . $file);
    }
}

function getBetween($pdo, $columns, $table, $from, $to)
{
    $sql = "SELECT $columns FROM $table
            LEFT JOIN kode_surat
            ON kode_surat.id = $table.kode_id
            LEFT JOIN users
            ON users.id = $table.user_id
            WHERE $table.created_at
            BETWEEN :dari_tanggal
            AND :sampai_tanggal
            ORDER BY $table.id
            LIMIT 100";

    $data = [
        'dari_tanggal'   => $from . ' 00:00:00',
        'sampai_tanggal' => $to . ' ' . date('H:i:s'),
    ];

    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);

    return $stmt;
}

function lastAgendaNumber($pdo, $table)
{
    $sql = "SELECT nomor_agenda
            FROM $table
            ORDER BY id DESC
            LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    if ($stmt->rowCount() == 0) {
        return 1;
    } else {
        return $stmt->fetch()->nomor_agenda + 1;
    }
}

function getAllFile($pdo, $columns, $table)
{
    $sql = "SELECT $columns
            FROM $table
            WHERE lampiran != ''
            ORDER BY id DESC
            LIMIT 8";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt;
}

function isAuthenticated()
{
    if (isset($_SESSION['id'])) {
        redirectTo('app.php');
    }
}

function isLoggedIn()
{
    return ! empty($_SESSION['id']) ? true : false;
}

function isAdmin()
{
    if ($_SESSION['level'] != 1) {
        redirectTo('./');
    }
}

function isSuperAdmin()
{
    if ($_SESSION['id'] != 1) {
        redirectTo('./');
    }
}

function redirectTo($url)
{
    header('location: ' . $url);
}

function goBack()
{
    echo '<script>window.history.back();</script>';
}

function escapeStr($str)
{
    return htmlspecialchars($str, ENT_QUOTES);
}

function isValidEmail($str)
{
    return filter_var($str, FILTER_VALIDATE_EMAIL);
}

function isValidUrl($str)
{
    return filter_var($str, FILTER_VALIDATE_URL);
}

function isNumeric($str)
{
    return preg_match("/^[0-9]*$/", $str);
}

function isValidUserLevel($str)
{
    return preg_match("/^[1-2]*$/", $str);
}

function getExtension($file)
{
    return strtolower(pathinfo($file)['extension']);
}

function isValidFile($file, $allowed_mime)
{
    $mime = mime_content_type($file);
    return in_array($mime, $allowed_mime) ? true : false;
}

function downloadFile($file)
{
    if (file_exists($file)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: private');
        header('Pragma: private');
        header('Content-Length: ' . filesize($file));
        ob_clean();
        flush();
        readfile($file);
        exit;
    }
}

function createDir($dir)
{
    if (! is_dir($dir)) {
        mkdir($dir, 0755, true);
        createIndexHtml($dir);
        createHtaccess($dir);
    }
}

function createIndexHtml($dir)
{
    $file = fopen($dir . 'index.html', 'w');
    fwrite($file, '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>');
    fclose($file);
}

function createHtaccess($dir)
{
    $file = fopen($dir . '.htaccess', 'w');
    fwrite($file, 'Options -Indexes');
    fclose($file);
}

function isValidDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

function localDate($date)
{
    $exp   = explode('-', $date);
    $day   = explode(' ', $exp[2])[0];
    $clock = explode(' ', $exp[2])[1];

    return $day . ' ' . month($exp[1])
                . ' ' . $exp[0]
                . ' &minus; Pukul ' . $clock;
}

function indoDate($date)
{
    $exp = explode('-', substr($date, 0, 10));
    return $exp[2] . ' ' . month($exp[1]) . ' ' . $exp[0];
}

function month($kode)
{
    $month = '';
    switch ($kode) {
        case '01':
            $month = 'Januari';
            break;
        case '02':
            $month = 'Februari';
            break;
        case '03':
            $month = 'Maret';
            break;
        case '04':
            $month = 'April';
            break;
        case '05':
            $month = 'Mei';
            break;
        case '06':
            $month = 'Juni';
            break;
        case '07':
            $month = 'Juli';
            break;
        case '08':
            $month = 'Agustus';
            break;
        case '09':
            $month = 'September';
            break;
        case '10':
            $month = 'Oktober';
            break;
        case '11':
            $month = 'November';
            break;
        case '12':
            $month = 'Desember';
            break;
    }
    return $month;
}

function appInfo()
{
    $app = [
        'name'        => 'Aplikasi Manajemen Surat',
        'description' => 'Aplikasi untuk mengelola data surat masuk dan keluar secara digital.',
        'version'     => 'v0.1.0',
        'developer'   => '',
        'contact'     => '',
        'website'     => '',
    ];

    return $app;
}
