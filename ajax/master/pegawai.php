<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])
    && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
    require_once '../../app/database.php';
    require_once '../../app/functions.php';

    $pdo = connectDb($hostname, $username, $password, $dbname);

    if (isLoggedIn()) {
        $table = 'pegawai';
        $cols  = 'id, nama, nip, pangkat, jabatan, satuan';

        // DataTable column index => database column name
        $columns = [
            0 => 'id',
            1 => 'nama',
            2 => 'nip',
            3 => 'pangkat',
            4 => 'jabatan',
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
                    WHERE nama LIKE :keyword
                    OR nip LIKE :keyword
                    OR pangkat LIKE :keyword
                    OR jabatan LIKE :keyword
                    OR satuan LIKE :keyword";

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
            $no++;
            $nestedData   = [];
            $nestedData[] = number_format($no, 0, 0, '.');
            $nestedData[] = $row->nama;
            $nestedData[] = $row->nip;
            $nestedData[] = $row->pangkat;
            $nestedData[] = $row->jabatan;
            $nestedData[] = '
                <a class="btn green waves-effect waves-light" title="Lihat detail" href="?page=pgw&act=show&id=' . $row->id . '"><i class="material-icons">list</i></a>

                <a class="btn blue waves-effect waves-light" title="Edit" href="?page=pgw&act=edit&id=' . $row->id . '"><i class="material-icons">edit</i></a>

                <a class="btn deep-orange waves-effect waves-light" title="Hapus" href="?page=pgw&act=del&id=' . $row->id . '"><i class="material-icons">delete</i></a>';

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
