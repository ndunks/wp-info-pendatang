<?php

$summary = info_pendatang_get_summary();
$total = 0;
?>
<table>
	<thead>
		<tr>
			<th>RT/RW</th>
			<th>Jumlah</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($summary['result'] as $row):
		$total += $row->jml;
		?>
		<tr>
			<td><?= "{$row->rt}/{$row->rw}" ?></td>
			<td><?= $row->jml ?> Orang</td>
		</tr>
		<?php endforeach ?>
	</tbody>
	<tfoot>
		<tr>
			<th>Total</th>
			<th><b><?= $total ?> Orang</b></th>
		</tr>
	</tfoot>
</table>