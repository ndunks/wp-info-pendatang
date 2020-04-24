<?php

$no = @$_POST['no'];
$msg = @$_POST['msg'];

if (!$no || !$msg) {
    return 'no & msg wajib diisi';
}
if (info_pendatang_send_wa($no, $msg)) {
    return 'OK';
} else {
    return 'Gagal';
}
