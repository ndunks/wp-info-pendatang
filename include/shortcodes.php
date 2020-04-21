<?php

function info_pendatang_shortcode_total()
{
    global $wpdb;
    
    if (InfoPendatang::has_result('total')) {
        return InfoPendatang::result('total');
    } else {
        $query = "SELECT count(*) as jml FROM " . InfoPendatang::$table;
        $result = $wpdb->get_results($query);
        return InfoPendatang::result('total', $result[0]->jml);
    }
}
function info_pendatang_shortcode_asal_kota()
{
    $result = info_pendatang_get_asal_kota();
    $total     = 0;
    ob_start(); ?>
    <table class="info-pendatang-table">
        <thead>
            <tr><th>Asal Kota</th><th>Jumlah</th></tr>
        </thead>
            <?php
            foreach ($result as $row) {
                printf(
                    '<tr><td>%s</td><td>%d Orang</td></tr>',
                    esc_html($row->asal_kota),
                    $row->jml
                );
                $total += $row->jml;
            } ?>
        <tfoot>
            <tr>
                <th>Total</th>
                <th><b><?= $total ?> Orang</b></th>
            </tr>
        </tfoot>
    </table>
<?php
return ob_get_clean();
}

function info_pendatang_shortcode_summary()
{
    $summary = info_pendatang_get_summary();
    $total     = 0;
    ob_start(); ?>
    <table class="info-pendatang-table">
        <thead>
            <tr><th>Dusun</th><th>Jumlah</th></tr>
        </thead>
            <?php
            foreach ($summary as $dusun => $jml) {
                printf(
                    '<tr><td>%s</td><td>%d Orang</td></tr>',
                    esc_html($dusun),
                    $jml
                );
                $total += $jml;
            } ?>
        <tfoot>
            <tr>
                <th>Total</th>
                <th><b><?= $total ?> Orang</b></th>
            </tr>
        </tfoot>
    </table>
<?php
return ob_get_clean();
}

function info_pendatang_shortcode_rtrw()
{
    $per_dusun = info_pendatang_get_rtrw();
    $total     = 0;
    ob_start(); ?>
    <table class="info-pendatang-table">
        <thead>
            <tr><th>Dusun</th><th>RT/RW</th><th>Jumlah</th></tr>
        </thead>
            <?php
            foreach ($per_dusun as $dusun => &$rwlist) {
                $total_dusun = 0;
                echo '<tbody>';
                foreach ($rwlist as $rw => $rtlist) {
                    foreach ($rtlist as $rt => $jml) {
                        printf(
                            '<tr><td>%s</td><td>%s/%s</td><td>%d Orang</td></tr>',
                            esc_html($dusun),
                            $rt,
                            $rw,
                            $jml
                        );
                        $total += $jml;
                        $total_dusun += $jml;
                    }
                }
                echo '</tbody>';
                echo '<thead>';
                printf(
                    '<tr><th colspan="2">Total %s</th><th>%d Orang</th></tr>',
                    esc_html($dusun),
                    $total_dusun
                );
                echo '</thead>';
            } ?>
        <tfoot>
            <tr>
                <th colspan="2">Total</th>
                <th><b><?= $total ?> Orang</b></th>
            </tr>
        </tfoot>
    </table>
<?php
return ob_get_clean();
}
