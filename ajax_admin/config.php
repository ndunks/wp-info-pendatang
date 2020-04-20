<?php
if (isset($_POST['dusun']) && is_array($_POST['dusun'])) {
    $newDusun = [];
    foreach ($_POST['dusun'] as $val) {
        if (empty($val['no']) && empty($val['nama'])) {
            continue;
        }
        $newDusun[] = [
            'no' => intval($val['no']),
            'nama' => trim($val['nama']),
            'rw' => is_array(@$val['rw']) ? array_map('intval', $val['rw']) : []
        ];
    }
    InfoPendatang::commit_option('dusun', $newDusun);
    return 'ok';
}
return InfoPendatang::$config;
