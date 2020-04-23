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

if (!empty($_POST['update']) && !empty($_POST['value'])) {
    InfoPendatang::commit_option($_POST['update'], stripcslashes($_POST['value']));
    return 'ok';
}
return InfoPendatang::$config;
