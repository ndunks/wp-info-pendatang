<?php

$raw = [];
$row = [];

// normalize
foreach (array_merge($_GET, $_POST) as $key => $value) {
    $raw[ strtolower($key) ] = stripcslashes(trim($value));
}

$row = info_pendatang_sanitize_data($raw, ['verified']);
$id  = intval($raw['id']);

if (!$id || $id < 1) {
    throw new Exception("Invalid ID", 406);
}

if (empty($row)) {
    return 'Nothing changed';
}
$old = (array) $wpdb->get_row("SELECT * FROM " . InfoPendatang::$table . " WHERE ID = {$id}");
if (empty($old)) {
    throw new Exception("ID Not Exists", 406);
}

if (!$old['verified'] && $row['verified']) {
    $no = $row['no_hp'] ? $row['no_hp'] : $old['no_hp'];
    
    if (info_pendatang_send_wa($no, InfoPendatang::$config['msg_himbauan'])) {
        $row['wa_sent']  = true;
    }
}

if (! $wpdb->update(InfoPendatang::$table, $row, ['id' => $id])) {
    throw new Exception("Gagal mengupdate data", 500);
}

return 'OK';
