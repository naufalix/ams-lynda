<?php
defined('APP') or exit('No direct script access allowed.');

$table = 'users';
$id    = $_REQUEST['id'];

if (isset($_GET['yes'])) {
    if ($_GET['id'] == 1) {
        redirectTo('?page=usr');
    } else {
        destroy($pdo, $table, $id);

        $_SESSION['success'] = 'SUKSES! User berhasil dihapus';
        redirectTo('?page=usr');
    }
} else {
    $columns = 'id, username, nama, email, nip, level';
    $row     = findById($pdo, $columns, $table, $id);

    if ($row->total > 0) {
        if ($row->id == 1) {
            redirectTo('?page=usr');
        } ?>

        <div class="row card-delete">
            <div class="card">
                <div class="card-content table-responsive">
                    <table>
                        <thead class="red lighten-5 red-text">
                            <div class="delete-title red-text"><i class="material-icons md-36">error_outline</i>
                            Apakah Anda yakin akan menghapus user ini?</div>
                        </thead>
			            <tbody>
			                <tr>
			                    <td width="120px">Username</td>
			                    <td width="1px">:</td>
			                    <td><?php echo $row->username ?></td>
			                </tr>
			                <tr>
			                    <td>Nama</td>
			                    <td>:</td>
			                    <td><?php echo $row->nama ?></td>
			                </tr>
			                <tr>
			                    <td>Email</td>
			                    <td>:</td>
			                    <td><?php echo $row->email ?></td>
			                </tr>
                            <tr>
                               <td>NIP</td>
                               <td>:</td>
                               <td><?php echo $row->nip ?></td>
                           </tr>
                           <tr>
                               <td>Level akun</td>
                               <td>:</td>
                               <td>
                                   <?php echo $row->level == 1 ? 'Administrator' : 'User Biasa' ?>
                               </td>
                           </tr>
			            </tbody>
			   		</table>
		        </div>
                <div class="card-action">
	                <a href="?page=usr&act=del&id=<?php echo $row->id ?>&yes" class="btn-large deep-orange waves-effect waves-light white-text"><i class="material-icons">delete</i> HAPUS</a>

	                <a href="?page=usr" class="btn-large blue waves-effect waves-light white-text"><i class="material-icons">clear</i> BATAL</a>
	            </div>
            </div>
        </div>
<?php
    } else {
        redirectTo('?page=usr');
    }
}
?>
