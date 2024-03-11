<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])
    && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
    require_once '../../app/database.php';
    require_once '../../app/functions.php';

    $pdo = connectDb($hostname, $username, $password, $dbname);

    if (isLoggedIn()) {
        $keyword = escapeStr($_GET['query']);

        $sql = "SELECT id, kode, uraian
                FROM kode_surat
                WHERE kode LIKE :keyword
                OR uraian LIKE :keyword
                ORDER BY id
                LIMIT 50";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['keyword' => "%$keyword%"]);

        if ($stmt->rowCount() > 0) {
            foreach ($stmt as $row) {
                $output['suggestions'][] = [
                    'value' => $row->kode . " − " . $row->uraian,
                    'kode'  => $row->kode,
                    'id'    => $row->id,
                ];
            }
        } else {
            $output['suggestions'][] = [
                'value' => 'Data tidak ditemukan',
                'kode'  => '',
                'id'    => '',
            ];
        }

        echo json_encode($output);
    }
}
