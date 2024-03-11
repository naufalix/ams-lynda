<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])
    && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
    require_once '../../app/database.php';
    require_once '../../app/functions.php';

    $pdo = connectDb($hostname, $username, $password, $dbname);

    if (isLoggedIn()) {
        $table = 'users';
        $cols  = 'id, username, nama, email, nip, level';

        // DataTable column index => database column name
        $columns = [
            0 => 'id',
            1 => 'username',
            2 => 'nama',
            3 => 'email',
            4 => 'nip',
            5 => 'level',
            6 => 'id',
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
                    WHERE username LIKE :keyword
                    OR nama LIKE :keyword
                    OR email LIKE :keyword";

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
            if ($row->id == 1) {
                $level = 'Super Admin <i class="material-icons orange-text">star</i>';
            } else {
                $level = $row->level == 1 ? 'Administrator' : 'User Biasa';
            }

            if ($row->id == 1) {
                $action = null;
            } else {
                $action = '
                    <a class="btn blue waves-effect waves-light" title="Edit" href="?page=usr&act=edit&id=' . $row->id . '"><i class="material-icons">edit</i></a>

                    <a class="btn deep-orange waves-effect waves-light" title="Hapus" href="?page=usr&act=del&id=' . $row->id . '"><i class="material-icons">delete</i></a>';
            }

            $no++;
            $nestedData   = [];
            $nestedData[] = number_format($no, 0, 0, '.');
            $nestedData[] = $row->username;
            $nestedData[] = $row->nama;
            $nestedData[] = $row->email;
            $nestedData[] = $row->nip;
            $nestedData[] = $level;
            $nestedData[] = $action;

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
