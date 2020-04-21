<?php

function info_pendatang_ajax($handle_path, $handle_path_fallback = null)
{
    global $wpdb;
    // Clean path traversal
    $do = strtr(@$_GET['do'], "/\\'\"%./;:*\0", '-----------');

    if (is_file($file = INFO_PENDATANG_DIR . $handle_path . "/$do.php")) {
        $do = $file;
    } elseif ($handle_path_fallback
        && is_file($file = INFO_PENDATANG_DIR . $handle_path_fallback . "/$do.php")) {
        $do = $file;
    } else {
        $do = INFO_PENDATANG_DIR . $handle_path . "/main.php";
    }

    try {
        $result = include($do);
    } catch (\Exception $th) {
        if ($th->getCode() > 200 && $th->getCode() < 600) {
            http_response_code($th->getCode());
        } else {
            http_response_code(500);
        }
        $result = $th->getMessage();
    }

    if (!empty($result)) {
        if (is_array($result)) {
            header("Content-Type: application/json");
            die(json_encode($result));
        } else {
            header("Content-Type: text/plain");
            die($result);
        }
    }
    die();
}

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

function info_pendatang_list($filter = null, $page = 1, $per_page = 20)
{
    global $wpdb;
    $page = intval($page);
    if ($page < 1) {
        $page = 1;
    }
    
    $start = ($page - 1) *  $per_page;
    $query = "SELECT * FROM " . InfoPendatang::$table .
            " ORDER BY dibuat DESC LIMIT $start, $per_page ";
    $result= $wpdb->get_results($query);
    return $result;
}

function info_pendatang_sanitize_data(&$dirty, $allowOtherCols = null)
{
    $cols = ['nama', 'nik', 'umur', 'rt', 'rw', 'dusun', 'asal_kota',
    'tgl_kepulangan', 'keluhan', 'no_hp', 'wa_sent', 'pelapor', 'keterangan' ];
    if ($allowOtherCols) {
        $cols = array_merge($cols, $allowOtherCols);
    }
    $clean = [];

    foreach ($cols as $col) {
        if (isset($dirty[ $col ])) {
            $clean[ $col ] = $dirty[ $col ];
        }
    }

    if (isset($clean['tgl_kepulangan'])) {
        $tgl_valid = info_pendatang_format_tanggal($clean['tgl_kepulangan']);
        if (empty($tgl_valid)) {
            throw new Exception("Format tgl_kepulangan salah, contoh: 16/04/2020", 406);
        } else {
            $clean['tgl_kepulangan'] = $tgl_valid;
        }
    }

    return $clean;
}

function info_pendatang_get_summary()
{
    global $wpdb;
    
    if (InfoPendatang::has_result('summary')) {
        return InfoPendatang::result('summary');
    }
    $query = "SELECT rt, rw, count(rw) as jml FROM " .
            InfoPendatang::$table . " GROUP BY rw,rt order by rw,rt";
    $result = $wpdb->get_results($query);
    $mapped = [];
    $totalRW = [];
    $total = 0;
    foreach ($result as $row) {
        if (! isset($totalRW[ $row->rw ])) {
            $totalRW[ $row->rw ] = 0;
            $mapped[ $row->rw ] = [];
        }
        $totalRW[$row->rw] +=  $row->jml;
        $mapped[ $row->rw ][ $row->rt ] = $row->jml;
        $total += $row->jml;
    }
    InfoPendatang::result('total', $total);
    return InfoPendatang::result('summary', compact('total', 'totalRW', 'mapped', 'result'));
}
