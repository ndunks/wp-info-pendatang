<?php

$result = $wpdb->get_results("SELECT id,rw from " . InfoPendatang::$table);
$rw_maps = [];
foreach (InfoPendatang::$config['dusun'] as $dusun) {
    foreach ($dusun['rw'] as $rw) {
        $rw_maps[$rw] = $dusun['nama'];
    }
}
$updates = [];
foreach ($result as $row) {
    if (isset($rw_maps[$row->rw])) {
        $updates[] = "UPDATE " . InfoPendatang::$table .
                    " set dusun = '" . addslashes($rw_maps[$row->rw]) .
                        "' WHERE id = {$row->id}";
    }
}
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
var_dump(dbDelta(implode(";\n", $updates)));
die("\nOK");
