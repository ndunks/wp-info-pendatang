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

if (! $wpdb->update(InfoPendatang::$table, $row, ['id' => $id])) {
    throw new Exception("Gagal mengupdate data", 500);
}

return 'OK';
