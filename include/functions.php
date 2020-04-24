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
    } elseif (is_file($file = INFO_PENDATANG_DIR . $handle_path . "/main.php")) {
        $do = $file;
    } else {
        $do = INFO_PENDATANG_DIR . "ajax/main.php";
    }
    $_JSON = [];
    // Parse JSON
    if (strpos(strtolower(@$_SERVER['HTTP_CONTENT_TYPE']), 'application/json') !== false) {
        $_JSON = json_decode(file_get_contents('php://input'), true);
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

function info_pendatang_format_tanggal_indo($tgl)
{
    $comp = explode(' ', $tgl);
    $comp[0] = explode('-', $comp[0]);
    return "{$comp[0][2]}/{$comp[0][1]}/{$comp[0][0]} {$comp[1]}";
}

function info_pendatang_format_tanggal($tgl)
{
    // format tgl
    if (preg_match('/^\d{4}-\d{1,2}-\d{1,2}$/', $tgl)) {
        return $tgl;
    }
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

function info_pendatang_get_result()
{
    global $wpdb;
    
    if (InfoPendatang::has_result('result')) {
        return InfoPendatang::result('result');
    }
    $rw_maps = [];
    foreach (InfoPendatang::$config['dusun'] as $dusun) {
        foreach ($dusun['rw'] as $rw) {
            $rw_maps[$rw] = $dusun['nama'];
        }
    }
    $query = "SELECT dusun, rt, rw, count(rw) as jml FROM " .
            InfoPendatang::$table . " WHERE verified = 1 GROUP BY rw,rt order by rw,rt";
    $result = $wpdb->get_results($query);
    foreach ($result as &$row) {
        if (empty($row->dusun)) {
            // Auto set dusun name based on config
            if (isset($rw_maps[ $row->rw ])) {
                $row->dusun = $rw_maps[ $row->rw ];
            } else {
                $row->dusun = '(Kosong)';
            }
        }
    }
    return InfoPendatang::result('result', $result);
}

function info_pendatang_get_rtrw()
{
    if (InfoPendatang::has_result('rtrw')) {
        return InfoPendatang::result('rtrw');
    }
    
    $result = info_pendatang_get_result();
    $rtrw = [];
    foreach ($result as $row) {
        if (! isset($rtrw[ $row->dusun ])) {
            $rtrw[ $row->dusun] = [];
        }
        $dusun =& $rtrw[ $row->dusun];

        if (! isset($dusun[ $row->rw ])) {
            $dusun[ $row->rw ] = [];
        }
        $rw =& $dusun[ $row->rw ];
        $rw[ $row->rt ] = $row->jml;
        unset($dusun, $rw);
    }
    return InfoPendatang::result('rtrw', $rtrw);
}

function info_pendatang_get_summary()
{
    if (InfoPendatang::has_result('summary')) {
        return InfoPendatang::result('summary');
    }

    $result = info_pendatang_get_result();
    $summary = [];
    foreach ($result as $row) {
        if (!isset($summary[ $row->dusun ])) {
            $summary[ $row->dusun ] = 0;
        }
        $summary[ $row->dusun ] += $row->jml;
    }
    return InfoPendatang::result('summary', $summary);
}

function info_pendatang_get_asal_kota()
{
    global $wpdb;
    if (InfoPendatang::has_result('asal_kota')) {
        return InfoPendatang::result('asal_kota');
    }
    $query = "SELECT asal_kota, count(*) as jml FROM " .
    InfoPendatang::$table . " WHERE verified = 1 group by asal_kota order by jml desc";
    return InfoPendatang::result('asal_kota', $wpdb->get_results($query));
}

function info_pendatang_get_last_update()
{
    global $wpdb;
    if (InfoPendatang::has_result('last_update')) {
        return InfoPendatang::result('last_update');
    }

    $query = "SELECT max(dibuat) as tgl FROM " .  InfoPendatang::$table .
    " WHERE verified = 1";
    $tgl   = $wpdb->get_results($query)[0]->tgl;
    return InfoPendatang::result(
        'last_update',
        info_pendatang_format_tanggal_indo($tgl)
    );
}

function info_pendatang_get_total()
{
    global $wpdb;
    
    if (InfoPendatang::has_result('total')) {
        return InfoPendatang::result('total');
    } else {
        $query = "SELECT count(*) as jml FROM " . InfoPendatang::$table .
        " WHERE verified = 1";
        $result = $wpdb->get_results($query);
        return InfoPendatang::result('total', $result[0]->jml);
    }
}

function info_pendatang_send_wa($no, $msg)
{
    $wa_server = InfoPendatang::$config['wa_server'];
    $wa_secret = InfoPendatang::$config['wa_secret'];

    $query = [
        'p' => $wa_secret,
        'no' => $no,
        'msg' => $msg
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
        throw new Exception('Koneksi ke ' . $wa_server . ' gagal', 500);
    } elseif ($code == 200) {
        return true;
    } elseif ($code == 403) {
        throw new Exception("Response: $code Secret salah", 500);
    } else {
        throw new Exception("Response: $code $res", 500);
    }
}
