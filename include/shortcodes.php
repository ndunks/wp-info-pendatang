<?php

function info_pendatang_shortcode_total()
{
    return info_pendatang_get_total();
}

function info_pendatang_shortcode_last_update()
{
    return info_pendatang_get_last_update();
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
                        if ($rt && $rw) {
                            $tmp = 'RT ' . str_pad($rt, 2, '0', STR_PAD_LEFT) . '/' . str_pad($rw, 2, '0', STR_PAD_LEFT);
                        } else {
                            $tmp = '&mdash;';
                        }
                        printf(
                            '<tr><td>%s</td><td>%s</td><td>%d Orang</td></tr>',
                            esc_html($dusun),
                            $tmp,
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
                <th colspan="2">Total Keseluruhan</th>
                <th><b><?= $total ?> Orang</b></th>
            </tr>
        </tfoot>
    </table>
<?php
return ob_get_clean();
}
