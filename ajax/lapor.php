<?php

$raw = [];
$row = [];

// normalize
foreach (array_merge($_GET, $_POST, $_JSON) as $key => $value) {
    $raw[ strtolower($key) ] = stripcslashes(trim($value));
}

if (!isset($raw['key']) || $raw['key'] != InfoPendatang::$config['secret']) {
    throw new Exception("Not allowed", 403);
}

unset($raw['key']);

if (empty($raw['nama'])) {
    throw new Exception("Nama tidak boleh kosong", 406);
}

$row = info_pendatang_sanitize_data($raw);

if (isset($row['rt'])) {
    $row['rt'] = intval($row['rt']);
}
if (isset($row['rw'])) {
    $row['rw'] = intval($row['rw']);

    // Auto dusun
    foreach (InfoPendatang::$config['dusun'] as &$dusun) {
        if (in_array($row['rw'], $dusun['rw'])) {
            $row['dusun'] = $dusun['nama'];
        }
    }
}

$row['raw'] = isset($raw['raw']) ? $raw['raw'] : serialize($raw);
$row['sumber'] = 'API_WA';
if ($wpdb->insert(InfoPendatang::$table, $row)) {
    return "ok";
} else {
    throw new Exception("Gagal insert DB", 500);
}
