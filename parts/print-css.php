<?php defined('APP') or exit('No direct script access allowed.') ?>

<style>
    .agenda {
        position: relative;
    }
    .show-print {
        display: none;
    }
    .title-agenda {
        font-size: 1.5rem;
    }
    .table-responsive {
        box-shadow: 0 2px 5px 0 rgba(0,0,0,0.16), 0 2px 10px 0 rgba(0,0,0,0.12);
        margin-bottom: 4rem;
        margin-left: -.75rem;
        margin-right: -.75rem;
        overflow-x: auto !important;
    }
    .table-agenda {
        background: #fff;
        width: 100vw;
    }
    .table-agenda td:first-of-type {
        text-align: center;
    }
    @media print {
        .pace {
            display: none !important;
        }
        .container,
        .row,
        .kop,
        table {
            font-size: 13px !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
            width: 100% !important;
        }
        .kop {
            margin: 40px 0 25px !important;
        }
        .kop-logo {
            height: auto;
            left: 0;
            margin-top: -2px;
            max-width: 92px;
            position: absolute;
        }
        .kop-dinas,
        .kop-instansi {
            text-transform: uppercase;
        }
        .kop-dinas {
            font-size: 16px;
        }
        .kop-instansi {
            font-size: 20px;
            font-weight: 700;
            margin: 2px 0 12px;
        }
        .kop-alamat,
        .kop-kontak {
            font-size: 13px;
            margin-top: -10px;
        }
        .kop-separator {
            border-bottom: 2px solid #616161;
            margin-top: -2px;
        }
        .show-print {
            display: block;
        }
        .hide-print {
            display: none
        }
        .print-title {
            font-size: 19px;
            font-weight: 700;
            margin-bottom: -10px;
        }
        .date-agenda {
            font-size: 15px;
        }
        .table-responsive {
            box-shadow: none;
            margin-left: 0;
            margin-right: 0;
            margin-top: -15px;
        }
    }
</style>
