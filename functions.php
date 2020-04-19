<?php

function info_pendatang_format_tanggal($tgl)
{
    // format tgl
    $tgl_array = preg_split('#[\/\-\s]+#', $tgl, 3);
    if (count($tgl_array) == 3) {
        $tgl_array = array_map('trim', $tgl_array);

        if (strlen($tgl_array[2]) >= 2 &&
            strlen($tgl_array[2]) <= 4 &&
            ctype_digit($tgl_array[0]) &&
            ctype_digit($tgl_array[1]) &&
            ctype_digit($tgl_array[2])
        ) {
            if (strlen($tgl_array[2]) == 2) {
                $tgl_array[2] = 2000 + intval($tgl_array[2]);
            }
            if (strlen($tgl_array[0] < 2)) {
                $tgl_array[0] = "0" . $tgl_array[0];
            }
            if (strlen($tgl_array[1] < 2)) {
                $tgl_array[1] = "0" . $tgl_array[1];
            }
            if ($tgl_array[0] < 33 && $tgl_array[0] > 0 &&
            $tgl_array[1] < 13 && $tgl_array[1] > 0 &&
            $tgl_array[2] < 2100 && $tgl_array[2] > 2010
             ) {
                $tgl_array = array_map('intval', $tgl_array);
                return implode("-", array_reverse($tgl_array));
            }
        }
    }
    
    return false;
}