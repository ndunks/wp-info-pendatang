<?php

function info_pendatang_default_config()
{
    return [
        'secret'=> uniqid() . uniqid(),
        'version' => InfoPendatang::$version,
        'no_wa' => '08xxxxxxxxxx',
        'dusun' => [
            [
                'no' => 1,
                'nama' => 'Dusun 1',
                'rw' => [1,2,3]
            ],
            [
                'no' => 2,
                'nama' => 'Dusun 2',
                'rw' => [4,5,6]
            ],
        ]
    ];
}

function info_pendatang_merge_config()
{
    $ignore = ['dusun','secret'];
    $default = info_pendatang_default_config();
    foreach ($default as $key => $value) {
        if (in_array($key, $ignore)) {
            continue;
        }
        if (empty(@InfoPendatang::$config[$key])) {
            InfoPendatang::$config[$key] = $value;
        }
    }
    InfoPendatang::$config['version'] = infoPendatang::$version;
}

if (empty(InfoPendatang::$config)) {
    InfoPendatang::$config = info_pendatang_default_config();
    InfoPendatang::commit_option();
} elseif (InfoPendatang::$config['version'] != InfoPendatang::$version) {
    info_pendatang_merge_config();
    InfoPendatang::commit_option();
}
