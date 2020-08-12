<?php
    include('../../connection.php');
    $first = true;
    $result = $mysqli->query('SELECT * FROM mdc_senior.sanitation_result1');
    $fp = fopen('php://output', 'w');
    if ($fp && $result) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="export.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');
        while ($row = $result->fetch_assoc()) {
            if ($first) {
                fputcsv($fp, array_keys($row));
                $first = false;
            }
            fputcsv($fp, array_values($row));
        }
    }
    //echo("<script>location.href='export_report.php';</script>");
    //header('Location:export_report.php');
?>
