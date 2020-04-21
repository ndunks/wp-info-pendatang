<?php

function info_pendatang_shortcode_total(){
    global $wpdb;
    
    if (InfoPendatang::has_result('total')) {
        return InfoPendatang::result('total');
    }else{
        $query = "SELECT count(*) as jml FROM " . InfoPendatang::$table;
        $result = $wpdb->get_results($query);
        return InfoPendatang::result('total', $result[0]->jml);
    }
}

function info_pendatang_shortcode_summary()
{
    ob_start();
    include INFO_PENDATANG_DIR . "pages/display.php";
    return ob_get_clean();
}