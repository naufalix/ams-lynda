<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])
    && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
    require_once '../../app/database.php';
    require_once '../../app/functions.php';

    $pdo = connectDb($hostname, $username, $password, $dbname);

    if (isLoggedIn()) {
        $table = 'surat_masuk';
        $cols  = 'id, nomor_surat, tanggal_surat, asal, perihal, lampiran';

        // DataTable column index => database column name
        $columns = [
            0 => 'id',
            1 => 'nomor_surat',
            2 => 'tanggal_surat',
            3 => 'asal',
            4 => 'perihal',
            5 => 'id',
        ];

        $postData = $_REQUEST;
        $keyword  = escapeStr($postData['search']['value']);

        // Getting total number records without any search
        $sql  = "SELECT $cols FROM $table ORDER BY id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $totalData     = $stmt->rowCount();
        $totalFiltered = $totalData; // When there is no search parameter then total number rows = total number filtered rows.

        // If there is a search parameter
        if (! empty($keyword)) {
            $sql = "SELECT $cols
                    FROM $table
                    WHERE nomor_surat LIKE :keyword
                    OR tanggal_surat LIKE :keyword
                    OR asal LIKE :keyword
                    OR perihal LIKE :keyword
                    OR tanggal_diterima LIKE :keyword
                    OR keterangan LIKE :keyword";

            $stmt = $pdo->prepare($sql);
            $stmt->execute(['keyword' => "%$keyword%"]);

            $totalFiltered = $stmt->rowCount(); // When there is a search parameter then we have to modify total number filtered rows as per search result without limit in the query.

            $sql.=" ORDER BY "
                . $columns[$postData['order'][0]['column']]
                . " "
                . $postData['order'][0]['dir']
                . " LIMIT "
                . $postData['start']
                . " ,"
                . $postData['length']
                . " "; // $postData['order'][0]['column'] contains colmun index, $postData['order'][0]['dir'] contains order such as asc/desc , $postData['start'] contains start row number ,$postData['length'] contains limit length.

            $stmt = $pdo->prepare($sql);
            $stmt->execute(['keyword' => "%$keyword%"]);
        } else {
            $sql = "SELECT $cols FROM $table";
            $sql.=" ORDER BY "
                . $columns[$postData['order'][0]['column']]
                . " "
                . $postData['order'][0]['dir']
                . " LIMIT "
                . $postData['start']
                . " ,"
                . $postData['length']
                . " ";

            $stmt = $pdo->prepare($sql);
            $stmt->execute();
        }

        $no   = $postData['start'];
        $data = [];
        foreach ($stmt as $row) {
            $icon = ! empty($row->lampiran) ? '<br><i class="material-icons attachment-icon">link</i>' : '';

            $no++;
            $nestedData   = [];
            $nestedData[] = number_format($no, 0, 0, '.') . $icon;
            $nestedData[] = $row->nomor_surat;
            $nestedData[] = indoDate($row->tanggal_surat);
            $nestedData[] = $row->asal;
            $nestedData[] = $row->perihal;
            $nestedData[] = '
                <a class="btn green waves-effect waves-light" title="Lihat detail" href="?page=sm&act=show&id=' . $row->id . '"><i class="material-icons">list</i></a>

                <a class="btn blue waves-effect waves-light" title="Edit" href="?page=sm&act=edit&id=' . $row->id . '"><i class="material-icons">edit</i></a>

                <a class="btn deep-orange waves-effect waves-light" title="Hapus" href="?page=sm&act=del&id=' . $row->id . '"><i class="material-icons">delete</i></a>

                <a class="btn purple lighten-1 waves-effect waves-light" title="Disposisi" href="?page=sm&act=disp&id=' . $row->id . '"><i class="material-icons">assignment</i></a>

                <a class="btn indigo lighten-1 waves-effect waves-light" title="Cetak disposisi" href="?page=sm&act=print&id=' . $row->id . '" target="_blank" rel="noopener"><i class="material-icons">print</i></a>';

            $data[] = $nestedData;
        }

        $json_data = [
            "draw"            => intval($postData['draw']),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        ];

        echo json_encode($json_data);
    }
}
