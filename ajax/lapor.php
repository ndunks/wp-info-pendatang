<?php

$raw = [];
$row = [];

// normalize
foreach ($_GET as $key => $value) {
    $raw[ strtolower($key) ] = stripcslashes(trim($value));
}

unset($raw['action'],$raw['do']);

if (empty($raw['nama'])) {
    throw new Exception("Nama tidak boleh kosong", 406);
}

$cols = ['nama', 'nik', 'umur', 'rt', 'rw', 'dusun', 'asal_kota',
        'tgl_kepulangan', 'keluhan', 'no_hp', 'wa_sent', 'pelapor', 'keterangan' ];

foreach ($cols as $col) {
    if (isset($raw[ $col ])) {
        $row[ $col ] = $raw[ $col ];
    }
}

if (isset($row['tgl_kepulangan'])) {
    $tgl_valid = info_pendatang_format_tanggal($row['tgl_kepulangan']);
    if (empty($tgl_valid)) {
        throw new Exception("Format tgl_kepulangan salah, contoh: 16/04/2020", 406);
    } else {
        $row['tgl_kepulangan'] = $tgl_valid;
    }
}

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

$row['raw'] = serialize($raw);
$row['sumber'] = 'API_WA';
if( $wpdb->insert($wpdb->prefix . InfoPendatang::$name, $row) ){
    echo "ok";
}else{
    throw new Exception("Gagal insert DB", 500);
}

echo json_encode($row, JSON_PRETTY_PRINT);
