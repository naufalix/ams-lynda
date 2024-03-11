<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])
    && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
    require_once '../../app/database.php';
    require_once '../../app/functions.php';

    $pdo = connectDb($hostname, $username, $password, $dbname);

    if (isLoggedIn()) {
        $keyword = escapeStr($_GET['query']);

        $sql = "SELECT id, asal
                FROM surat_masuk
                WHERE asal LIKE :keyword
                GROUP BY asal
                ORDER BY id
                LIMIT 25";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['keyword' => "%$keyword%"]);

        if ($stmt->rowCount() > 0) {
            foreach ($stmt as $row) {
                $output['suggestions'][] = [
                    'value' => $row->asal,
                    'asal'  => $row->asal,
                ];
            }
        } else {
            $output['suggestions'][] = [
                'value' => '',
                'asal'  => '',
            ];
        }

        echo json_encode($output);
    }
}
