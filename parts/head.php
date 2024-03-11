<?php defined('APP') or exit('No direct script access allowed.') ?>

<head>
    <title><?php echo appInfo()['name'] . ' - ' . showOrg($pdo)->instansi ?></title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="description" content="<?php echo appInfo()['description'] ?>">
    <meta name="generator" content="masrud.com">

    <link rel="shortcut icon" href="uploads/<?php echo showOrg($pdo)->logo ?>">
    <link rel="stylesheet" href="assets/css/materialize.min.css">
    <link rel="stylesheet" href="assets/css/dataTables.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="assets/css/select2.min.css">
    <style>
        .bg::before {
            background-image: url('assets/img/<?php echo $bg_image ?>');
        }
    </style>
    <?php include 'noscript.js.php' ?>
    <script>const base = 'ajax/';</script>
</head>
