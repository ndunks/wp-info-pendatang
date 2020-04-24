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
        ],
        'wa_server' => '',
        'wa_secret' => '',
        'msg_himbauan' => 'Selamat siang...Sdr. %nama% kami menghimbau selama pulang berada dirumah agar anda mengisolasi diri dirumah , tidak pergi atau berkumpul dikeramaian, menjaga kebersihan diri dengan pembiasaan hidup bersih dan sehat, apabila terdapat keluhan batuk,demam, atau sesak nafas segera periksa ke puskesmas terdekat.. Mohon untuk menjadi perhatian.
Salam Sehat
#SATGAS COVID 19 DESA KLAMPOK
http://klampok.id'
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
