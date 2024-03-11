<?php defined('APP') or exit('No direct script access allowed.') ?>

<div class="card row hide-print">
    <form class="card-content" method="post" action="" target="_blank">
        <div class="input-field col s12 m4 l3">
            <i class="material-icons prefix md-prefix">date_range</i>
            <input type="text" name="dari_tanggal" id="dari_tanggal" class="datepicker" required>
            <label for="dari_tanggal">Dari Tanggal</label>
        </div>
        <div class="input-field col s12 m4 l3">
            <i class="material-icons prefix md-prefix">date_range</i>
            <input type="text" name="sampai_tanggal" id="sampai_tanggal" class="datepicker" required>
            <label for="sampai_tanggal">Sampai Tanggal</label>
        </div>
        <div class="col s12 m4 l3">
            <button type="submit" name="submit" class="btn-large blue waves-effect waves-light"><i class="material-icons">sort</i> TAMPILKAN</button>
        </div>
    </form>
</div>
