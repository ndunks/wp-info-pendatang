<?php

$wa_server = @$_POST['wa_server'];
$wa_secret = @$_POST['wa_secret'];

if (!function_exists('curl_init')) {
    return 'No CURL';
}
$query = [
    'p' => stripcslashes($wa_secret),
];
$url = rtrim($wa_server, '\/\\?') . '/send?' . http_build_query($query);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYSTATUS, false);
$res = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
if (!$code) {
    return 'Koneksi ke ' . $wa_server . ' gagal';
} elseif ($code == 406) {
    return 'OK, koneksi berhasil';
} elseif ($code == 403) {
    return "Response: $code Secret salah";
} else {
    return "Response: $code $res";
}
