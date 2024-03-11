<?php
require_once __DIR__.'/app/database.php';
require_once __DIR__.'/app/functions.php';

$pdo = connectDb($hostname, $username, $password, $dbname);

isAuthenticated();

if (isset($_POST['submit'])) {
    $sql = "SELECT count(*) as total,
                    id,
                    username,
                    password,
                    nama,
                    email,
                    nip,
                    level
            FROM users
            WHERE username = :username
            LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'username' => escapeStr(strtolower($_POST['username'])),
    ]);

    $row = $stmt->fetch();

    if ($row->total > 0) {
        if (password_verify($_POST['password'], $row->password)) {
            $_SESSION             = [];
            $_SESSION['id']       = $row->id;
            $_SESSION['username'] = $row->username;
            $_SESSION['nama']     = $row->nama;
            $_SESSION['email']    = $row->email;
            $_SESSION['nip']      = $row->nip;
            $_SESSION['level']    = $row->level;
            redirectTo('app.php');
        } else {
            $_SESSION['error'] = 'Username & password tidak sesuai';
            redirectTo('./');
        }
    } else {
        $_SESSION['error'] = 'Username tidak ditemukan';
        redirectTo('./');
    }
} else {
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
<head>
    <title><?php echo appInfo()['name'] . ' - ' . showOrg($pdo)->instansi ?></title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="description" content="<?php echo appInfo()['description'] ?>">
    <meta name="generator" content="masrud.com">

    <link rel="shortcut icon" href="uploads/<?php echo showOrg($pdo)->logo ?>">
    <link rel="stylesheet" href="assets/css/materialize.min.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <style>
        .bg::before {
            background-image: url('assets/img/<?php echo $bg_image ?>');
            opacity: <?php echo $opacity ?>;
        }
    </style>
    <?php include __DIR__.'/parts/noscript.js.php' ?>
</head>
<body class="bg">
    <div class="container">
        <div class="card-panel z-depth-1">
            <div class="card-content center">
                <img class="logo" src="uploads/<?php echo showOrg($pdo)->logo ?>">
                <h5 class="app-name"><?php echo appInfo()['name'] ?></h5>
                <h4 class="org-name"><?php echo showOrg($pdo)->instansi ?></h4>
                <div class="line"></div>
            </div>
            <form action="" method="post">
                <?php if (isset($_SESSION['error'])) { ?>
                    <div id="alert-message" class="red lighten-5">
                        <div class="center">
                            <strong>LOGIN GAGAL!</strong>
                            <br>
                            <?php echo $_SESSION['error'] ?>
                        </div>
                    </div>
                <?php unset($_SESSION['error']); } ?>
                <div class="input-field">
                    <i class="material-icons prefix md-prefix">account_circle</i>
                    <input id="username" type="text" class="validate" name="username" autocomplete="off" required>
                    <label for="username">Username</label>
                </div>
                <div class="input-field">
                    <i class="material-icons prefix md-prefix">lock</i>
                    <input id="password" type="password" class="validate" name="password" autocomplete="off" required>
                    <label for="password">Password</label>
                </div>
                <div class="input-field">
                    <button type="submit" class="btn-large waves-effect waves-light blue-grey" name="submit">LOGIN</button>
                </div>
            </form>
        </div>
    </div>

    <script src="assets/js/main.min.js"></script>
    <script>
        $(document).ready(() => {
            $("#alert-message").delay(3000).slideUp(500);
        });

        paceOptions = {
            ajax: false,
            document: false,
            eventLag: false,
        };
    </script>
</body>
</html>
<?php
} ?>
