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

function info_pendatang_shortcode_summary()
{
    $summary = info_pendatang_get_summary();
    $total = 0; ?>
    <table class="info-pendatang-table">
	<thead>
		<tr>
			<th>RT/RW</th>
			<th>Jumlah</th>
		</tr>
	</thead>
	<tbody>
		<?php
        foreach ($summary['result'] as $row) {
            printf(
                '<tr><td>%s/%s</td><td>%d Orang</td></tr>',
                $row->rt,
                $row->rw,
                $row->jml
            );
            $total += $row->jml;
        } ?>
	</tbody>
	<tfoot>
		<tr>
			<th>Total</th>
			<th><b><?= $total ?> Orang</b></th>
		</tr>
	</tfoot>
</table>
    <?php
}

function info_pendatang_shortcode_per_dusun()
{
    $per_dusun = info_pendatang_get_per_dusun();
    $total     = 0; ?>
    <table class="info-pendatang-table">
        <thead>
            <tr><th>Dusun</th><th>RT/RW</th><th>Jumlah</th></tr>
        </thead>
        <tbody>
            <?php
            foreach ($per_dusun as $dusun => &$rwlist) {
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
                    }
                }
            } ?>
        </tbody>
        <tfoot>
            <tr>
                <th>Total</th>
                <th><b><?= $total ?> Orang</b></th>
            </tr>
        </tfoot>
    </table>
<?php
}
