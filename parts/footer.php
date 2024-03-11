<?php defined('APP') or exit('No direct script access allowed.') ?>

<footer class="page-footer">
    <div class="footer-copyright blue-grey darken-1 white-text">
        <div class="container" id="footer">
            <span class="white-text">
                &copy; <a class="white-text" href="<?php echo showOrg($pdo)->website ?>" target="_blank" rel="noopener"><?php echo showOrg($pdo)->instansi ?></a>
            </span>
        </div>
    </div>
</footer>

<script src="assets/js/main.min.js"></script>
<script src="assets/js/jquery.dataTables.min.js"></script>
<script src="assets/js/jquery.autocomplete.min.js"></script>
<script src="assets/js/select2.min.js"></script>

<script>
    $(document).ready(() => {
        let src;
        <?php if (isset($_GET['page'])) { ?>
            <?php if ($_GET['page'] == 'sm') { ?>
                src = 'master/surat-masuk.php';
            <?php } elseif ($_GET['page'] == 'sk') { ?>
                src = 'master/surat-keluar.php';
            <?php } elseif ($_GET['page'] == 'sc') { ?>
                src = 'master/surat-cuti.php';
            <?php } elseif ($_GET['page'] == 'ks') { ?>
                src = 'master/kode-surat.php';
            <?php } elseif ($_GET['page'] == 'pgw') { ?>
                src = 'master/pegawai.php';
            <?php } else { ?>
                src = 'master/user.php';
            <?php } ?>
                $('#table').DataTable({
                    processing: true,
                    serverSide: true,
                    deferRender: true,
                    ajax: base + src,
                    autoWidth: false,
                    order: [[0, "desc"]],
                    searchDelay: 0,
                    columnDefs: [
                        {
                            targets: ['_all'],
                            className: "mdc-data-table__cell"
                        }
                    ],
                    language: {
                        processing: "Memproses...",
                        loadingRecords: "Sedang memuat...",
                        search: "Pencarian <i class='material-icons'>search</i>",
                        searchPlaceholder: "Ketikkan kata kunci",
                        lengthMenu: "Tampilkan _MENU_ data",
                        info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                        infoEmpty: "Menampilkan 0 - 0 dari 0 data",
                        infoFiltered: "",
                        zeroRecords: "Tidak ada data yang ditemukan",
                        emptyTable: "Tidak ada data",
                        thousands: ".",
                        paginate: {
                            first: "Awal",
                            previous: "Sebelumnya",
                            next: "Selanjutnya",
                            last: "Akhir"
                        }
                    },
                });
            <?php
        } ?>

        $('.datepicker').on('mousedown',(e) => e.preventDefault())
        $('.datepicker').pickadate({
            selectMonths: true,
            selectYears: 5,
            format: 'yyyy-mm-dd',
            onSet: function(arg) {
                if ('select' in arg) {
                    this.close();
                }
            }
        }).prop('readonly', false);

        $('#kode_preview').autocomplete({
            serviceUrl: base + 'autocomplete/kode-surat.php',
            dataType: 'JSON',
            onSelect: (suggestion) => {
                $(this).val(suggestion.kode);
                $('#kode').val(suggestion.id);
            }
        });

        $('#asal').autocomplete({
            serviceUrl: base + 'autocomplete/surat-masuk.php',
            dataType: 'JSON',
            deferRequestBy: 0,
            onSelect: (suggestion) => {
                $(this).val(suggestion.asal);
            }
        });

        $('#tujuan').autocomplete({
            serviceUrl: base + 'autocomplete/surat-keluar.php',
            dataType: 'JSON',
            deferRequestBy: 0,
            onSelect: (suggestion) => {
                $(this).val(suggestion.tujuan);
            }
        });

        $('#tujuan_disposisi').autocomplete({
            serviceUrl: base + 'autocomplete/disposisi.php',
            dataType: 'JSON',
            deferRequestBy: 0,
            onSelect: (suggestion) => {
                $(this).val(suggestion.tujuan);
            }
        });

        $('#diteruskan_kepada').select2({
            placeholder: 'Diteruskan Kepada',
            dropdownAutoWidth: true
        });

        $('#nama_preview').autocomplete({
            serviceUrl: base + 'autocomplete/pegawai.php',
            dataType: 'JSON',
            deferRequestBy: 0,
            onSelect: (suggestion) => {
                $(this).val(suggestion.nama);
                $('#pegawai').val(suggestion.id);
                $('#nip').val(suggestion.nip);
                $('#pangkat').val(suggestion.pangkat);
                $('#jabatan').val(suggestion.jabatan);
                $('#satuan').val(suggestion.satuan);
                $('.actv').addClass('active');
                $('label[for=nama_preview]').addClass('active');
                $('label[for=nip]').addClass('active');
                $('label[for=pangkat]').addClass('active');
                $('label[for=jabatan]').addClass('active');
                $('label[for=satuan]').addClass('active');
                $('#nip').addClass('valid active');
                $('#pangkat').addClass('valid active');
                $('#jabatan').addClass('valid active');
                $('#satuan').addClass('valid active');
            }
        });

        $('.button-collapse').sideNav({
            menuWidth: 240,
            edge: 'left',
            closeOnClick: true,
        });

        <?php if (isset($_GET['page'])) { ?>
            <?php if ($_GET['page'] == 'gsm') { ?>
                src = 'gsm';
            <?php } else { ?>
                src = 'gsk';
            <?php } ?>
        <?php } ?>

        $('#loader').on('inview', (event, isInView) => {
            if (isInView) {
                let nextPage = parseInt($('#pageno').val()) + 1;
                $.ajax({
                    type: 'GET',
                    url:  base + 'galeri/galeri.php?type=' + src,
                    data: {
                        pageno: nextPage
                    },
                    success: (response) => {
                        if (response != '') {
                            $('#galeri-grid').append(response);
                            $('#pageno').val(nextPage);
                        } else {
                           $('#loader').hide();
                        }
                    }
                });
            }
        });

        $('#alert-message').delay(3000).slideUp(500);
        $('.dropdown-button').dropdown({hover: false});
        $('.tooltipped').tooltip({delay: 0});
        $('select').material_select();

        $('textarea').trigger('autoresize');
        $('.modal-trigger').leanModal();
    });

    paceOptions = {
        ajax: false,
        document: false,
        eventLag: false,
    };
</script>
