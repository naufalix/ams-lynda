<?php
defined('APP') or exit('No direct script access allowed.') ?>

<div class="row card-detail">
    <h5>Detail Disposisi</h5>
    <div class="card">
        <div class="card-content table-responsive">
            <table>
                <tr>
                    <td width="160px">Tujuan Diposisi</td>
                    <td>:</td>
                    <td><?php echo $row->tujuan ?></td>
                </tr>
                <tr>
                    <td>Isi Disposisi</td>
                    <td>:</td>
                    <td><?php echo $row->isi ?></td>
                </tr>
                <tr>
                    <td>Ditambahkan pada</td>
                    <td>:</td>
                    <td><?php echo localDate($row->created_at) ?></td>
                </tr>
                <tr>
                    <td>Ditambahkan oleh</td>
                    <td>:</td>
                    <td><?php echo $row->nama ?></td>
                </tr>
            </table>
        </div>
        <div class="card-action">
            <a href="?page=sm" class="btn-large green lighten-1 waves-effect waves-light white-text"><i class="material-icons">arrow_back</i> KEMBALI</a>

            <a href="?page=sm&act=disp&id=<?php echo $id ?>&did=<?php echo $row->id ?>&edit" class="btn-large blue waves-effect waves-light white-text"><i class="material-icons">edit</i> EDIT</a>

            <a href="?page=sm&act=print&id=<?php echo $id ?>" class="btn-large indigo lighten-1 waves-effect waves-light white-text" target="_blank" rel="noopener"><i class="material-icons">print</i> CETAK</a>

            <a href="?page=sm&act=disp&id=<?php echo $id ?>&did=<?php echo $row->id ?>&del" class="btn-large deep-orange waves-effect waves-light white-text"><i class="material-icons">delete</i> HAPUS</a>
        </div>
    </div>
</div>
