<?php
$cols = "nama,nik,umur,CONCAT(rt,'/',rw) as rtrw,no_hp,asal_kota,tgl_kepulangan,keluhan,keterangan,pelapor,sumber,dibuat";
$results = $wpdb->get_results("SELECT $cols FROM " . InfoPendatang::$table . " ORDER BY id");
$total = info_pendatang_get_total();
$date = date('d-M-Y H i s');
$title = "Pendatang $date Total $total";
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="'.$title.'.xls"');
$table_cols = [
    'Nama','Nik','Umur','RT/RW','No HP','Asal Kota',
    'Tgl Kepulangan','Keluhan','Keterangan','Pelapor','Sumber','Dilaporkan'
];
?><!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title><?= $title ?></title>
    <style type="text/css">
    td { mso-number-format:"\@"; }
    table{ border-collapse: collapse;}
    </style>
</head>
<body>
    <table border="1">
        <tr><th><?= implode('</th><th>', $table_cols) ?></th></tr>
        <?php
        foreach ($results as &$row) {
            if ($row->pelapor && strpos($row->pelapor, '@c.us')) {
                $row->pelapor = str_replace('@c.us', '', $row->pelapor);
            }
            unset($val);
            echo '<tr><td>' . implode('</td><td>', (array) $row). '</td></tr>';
        }
        ?>
        <tr><th colspan="<?= count($table_cols) - 1 ?>"><?= "TOTAL $total Orang" ?></th></tr>
    </table>
</body>
</html>
<?php
