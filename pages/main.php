<?php
global $wpdb;
$per_page  = 20;
$page = intval(@$_GET['page_no']);

if ($page < 1) {
    $page = 1;
}
$where = isset($_GET['q']) ?
"WHERE nama like '%" . esc_sql(stripslashes($_GET['q'])) . "%'" :
'';

$start = ($page - 1) *  $per_page;
$total = $wpdb->get_var("SELECT COUNT(*) FROM " . InfoPendatang::$table . " $where");
$total_page = ceil($total / $per_page);
$query = "SELECT * FROM " . InfoPendatang::$table . " $where " .
        " ORDER BY dibuat DESC LIMIT $start, $per_page ";
$result= $wpdb->get_results($query);
?><div style="float: right; padding: 10px;">
    <a href="<?= admin_url('admin.php?page=' . InfoPendatang::$name . '&view=settings') ?>"
        class="button-primary info_pendatang_button">
        <span class="dashicons dashicons-admin-settings"></span>
        <span>Pengaturan</span>
    </a>
    <a href="<?= admin_url('admin.php?page=' . InfoPendatang::$name . '&view=send_wa') ?>"
        class="button-primary info_pendatang_button">
        <span class="dashicons dashicons-email"></span>
        <span>Kirim WA</span>
    </a>
    <a href="<?= admin_url("admin-ajax.php?action=" . InfoPendatang::$name . "&do=export") ?>"
        class="button-primary info_pendatang_button">
        <span class="dashicons dashicons-download"></span>
        <span>Unduh</span>
    </a>
</div>

<div style="text-align: center" class="clear">
    <h1>Laporan Pendatang Total <?= $total ?></h1>
</div>
<hr />
<div class="tablenav">
    <form style="float: left" class="info-pendatang-search-box">
        <input type="hidden" name="page" value="<?= InfoPendatang::$name ?>"/>
        <input placeholder="Cari nama" type="text" class="medium-text" name="q" value="<?= esc_attr(@$_GET['q']) ?>"/>
        <button type="submit" class="button-secondary">Cari</button>
    </form>
	<div class="tablenav-pages">
        <?php if ($page > 1): ?>
        <a class='prev-page button-primary' title='Halaman sebelumnya'
        href='<?= admin_url('admin.php?page=' . InfoPendatang::$name . '&page_no=' . ($page - 1)) ?>'>&lsaquo;</a>
        <?php endif ?>
		<span class="paging-input">
            Halaman <?= $page ?> dari <span class='total-pages'><?= $total_page ?></span>
        </span>
        <?php if ($page < $total_page): ?>
        <a class='next-page button-primary' title='Halaman selanjutnya'
        href='<?= admin_url('admin.php?page=' . InfoPendatang::$name . '&page_no=' . ($page + 1)) ?>'>&rsaquo;</a>
        <?php endif ?>
    </div>
</div>

<table class="widefat fixed" cellspacing="0">
    <thead>
        <tr>
            <th class="manage-column column-nama" scope="col">Nama</th>
            <th class="manage-column column-alamat" scope="col">Alamat</th>
            <th class="manage-column column-asal-kota" scope="col">Asal Kota</th>
            <th class="manage-column column-tgl-kepulangan" scope="col">Kedatangan</th>
            <th class="manage-column column-verified" scope="col">Verified</th>
            <th class="manage-column column-dibuat" scope="col">Laporan</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($result as $row): ?>
        <tr  class="alternate info-pendatang-click-row" data-json="<?= esc_attr(json_encode($row)) ?>">
            <td class="column-nama">
                <?= esc_html($row->nama) ?: '&mdash;' ?>
            </td>
            <td class="column-alamat">
                <?php
                $txt = "";
                if ($row->rt || $row->rw) {
                    $txt = "{$row->rt}/{$row->rw}";
                }
                if ($row->dusun) {
                    $txt .= " ({$row->dusun})";
                }
                echo $txt ? esc_html($txt) : '&mdash;' ?>
            </td>
            <td class="column-asal-kota">
                <?= esc_html($row->asal_kota) ?: '&mdash;' ?>
            </td>
            <td class="column-tgl-kepulangan">
                <?= esc_html($row->tgl_kepulangan) ?: '&mdash;' ?>
            </td>
            <td class="column-tgl-verified">
                <?= $row->verified ? '<span style="color:green">Sudah</span>' : '<span style="color:red">Belum</span>' ?>
            </td>
            <td class="column-dibuat">
                <?= info_pendatang_format_date_indo($row->dibuat) ?>
            </td>
        </tr>
        <?php endforeach; ?>

    </tbody>
</table>

<div id="info-pendatang-dialog" class="hidden" style="max-width:800px;min-width:300px">
    <table class="form-table">
        <tr>
            <td colspan="3">
                <small>
                    Sumber: <strong id="info-pendatang-sumber"></strong><br/>
                    Pelapor:<strong id="info-pendatang-pelapor"></strong><br/>
                    Waktu:<strong id="info-pendatang-waktu"></strong><br/>
                    Status: <strong id="info-pendatang-verified"></strong><br />
                </small>
                <span id="info-pendatang-verified-button">
                    <button class="button-primary">Klik untuk Verifikasi</button>
                </span>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <label>Nama: </label><br />
                <input name="nama" class="regular-text" />
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <label>Nik: </label><br />
                <input name="nik" class="regular-text" />
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <label>No HP: </label><br />
                <input name="no_hp" class="regular-text" />
            </td>
        </tr>
        <tr>
            <td>
                <label>Umur: </label><br />
                <input name="umur" class="small-text" />
            </td>
            <td>
                <label>RT: </label><br />
                <input name="rt" class="small-text" />
            </td>
            <td>
                <label>RW: </label><br />
                <input name="rw" class="small-text" />
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <label>Dusun: </label><br />
                <input name="dusun" class="regular-text" />
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <label>Asal Kota: </label><br />
                <input name="asal_kota" class="regular-text" />
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <label>Tgl Kepulangan: </label><br />
                <input name="tgl_kepulangan" class="regular-text" />
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <label>Keluhan: </label><br />
                <input name="keluhan" class="regular-text" />
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <label>Keterangan Tambahan: </label><br />
                <textarea name="keterangan" class="regular-text"></textarea>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <label>Isi Pesan WA: </label><br />
                <textarea style="height:180px" name="raw" class="regular-text"></textarea>
            </td>
        </tr>
    </table>
    <div>
        <button id="info-pendatang-dialog-delete-button" class="button-secondary"> (!) HAPUS </button>
        <button style="float: right"  id="info-pendatang-dialog-save-button" class="button-primary"> Simpan Perubahan </button>
    </div>
    <div class="clear"></div>

</div>