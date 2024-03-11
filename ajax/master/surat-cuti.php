<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])
    && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
    require_once '../../app/database.php';
    require_once '../../app/functions.php';

    $pdo = connectDb($hostname, $username, $password, $dbname);

    if (isLoggedIn()) {
        $table = 'surat_cuti';
        $cols  = "$table.id, nomor_surat, tanggal_surat, jenis_cuti, nama";

        // DataTable column index => database column name
        $columns = [
            0 => 'id',
            1 => 'nomor_surat',
            2 => 'tanggal_surat',
            3 => 'nama',
            4 => 'jenis_cuti',
            5 => 'id',
        ];

        $postData = $_REQUEST;
        $keyword  = escapeStr($postData['search']['value']);

        // Getting total number records without any search
        $sql  = "SELECT $cols
                 FROM $table
                 LEFT JOIN pegawai
                 ON pegawai.id = $table.pegawai_id
                 ORDER BY $table.id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $totalData     = $stmt->rowCount();
        $totalFiltered = $totalData; // When there is no search parameter then total number rows = total number filtered rows.

        // If there is a search parameter
        if (! empty($keyword)) {
            $sql = "SELECT $cols
                    FROM $table
                    LEFT JOIN pegawai
                    ON pegawai.id = $table.pegawai_id
                    WHERE nomor_surat LIKE :keyword
                    OR tanggal_surat LIKE :keyword
                    OR jenis_cuti LIKE :keyword
                    OR nama LIKE :keyword";

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
            $sql = "SELECT $cols
                    FROM $table
                    LEFT JOIN pegawai
                    ON pegawai.id = $table.pegawai_id";
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
            $nestedData[] = $row->nomor_surat;
            $nestedData[] = indoDate($row->tanggal_surat);
            $nestedData[] = $row->nama;
            $nestedData[] = $row->jenis_cuti;
            $nestedData[] = '
                <a class="btn green waves-effect waves-light" title="Lihat detail" href="?page=sc&act=show&id=' . $row->id . '"><i class="material-icons">list</i></a>

                <a class="btn blue waves-effect waves-light" title="Edit" href="?page=sc&act=edit&id=' . $row->id . '"><i class="material-icons">edit</i></a>

                <a class="btn deep-orange waves-effect waves-light" title="Hapus" href="?page=sc&act=del&id=' . $row->id . '"><i class="material-icons">delete</i></a>

                <a class="btn indigo lighten-1 waves-effect waves-light" title="Cetak" href="?page=sc&act=print&id=' . $row->id . '" target="_blank" rel="noopener"><i class="material-icons">print</i></a>';

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
