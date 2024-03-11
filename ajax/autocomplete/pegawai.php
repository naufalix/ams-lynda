<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])
    && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
    require_once '../../app/database.php';
    require_once '../../app/functions.php';

    $pdo = connectDb($hostname, $username, $password, $dbname);

    if (isLoggedIn()) {
        $keyword = escapeStr($_GET['query']);

        $sql = "SELECT id, nama, nip, pangkat, jabatan, satuan
                FROM pegawai
                WHERE nama LIKE :keyword
                ORDER BY id
                LIMIT 25";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['keyword' => "%$keyword%"]);

        if ($stmt->rowCount() > 0) {
            foreach ($stmt as $row) {
                $output['suggestions'][] = [
                    'value'   => $row->nama,
                    'id'      => $row->id,
                    'nama'    => $row->nama,
                    'nip'     => $row->nip,
                    'pangkat' => $row->pangkat,
                    'jabatan' => $row->jabatan,
                    'satuan'  => $row->satuan,
                ];
            }
        } else {
            $output['suggestions'][] = [
                'value'   => 'Data tidak ditemukan',
                'id'      => '',
                'nama'    => '',
                'nip'     => '',
                'pangkat' => '',
                'jabatan' => '',
                'satuan'  => '',
            ];
        }

        echo json_encode($output);
    }
}
