<div style="float: right; padding: 10px;">
    <a href="<?= admin_url('admin.php?page=' . InfoPendatang::$name . '&view=settings') ?>"
        class="button-primary info_pendatang_button">
        <span class="dashicons dashicons-admin-settings"></span>
        <span>Pengaturan</span>
    </a>
</div>
<h1>Info Pendatang</h1>
<p>Gunakan shortcode <code>[info_pendatang]</code> untuk menampikan ringkasan pendatang pada halaman</p>
<?php
$result  = info_pendatang_list(null, $_GET['page']);
?>
<table class="widefat fixed" cellspacing="0">
    <thead>
    <tr>
        <th class="manage-column column-nama" scope="col">Nama</th>
        <th class="manage-column column-alamat" scope="col">Alamat</th>
        <th class="manage-column column-asal-kota" scope="col">Asal Kota</th>
        <th class="manage-column column-tgl-kepulangan" scope="col">Tgl. Kedatangan</th>
        <th class="manage-column column-verified" scope="col">Verified</th>
        <th class="manage-column column-aksi" scope="col">Aksi</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($result as $row): ?>
        <tr class="alternate" data-json="<?= esc_attr(json_encode($row)) ?>">
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
            <td class="column-aksi">
                <button  class="button-primary info-pendatang-dialog-button"> Detail </button>
            </td>
        </tr>
    <?php endforeach; ?>
        
    </tbody>
</table>

<div id="info-pendatang-dialog" class="hidden" style="max-width:800px;min-width:300px">
    <table class="form-table">
        <tr>
        <td colspan="3">
            Sumber: <strong id="info-pendatang-sumber"></strong>,
            Pelapor:<strong id="info-pendatang-pelapor"></strong>,
            Status: <strong id="info-pendatang-verified"></strong><br/>
            <span id="info-pendatang-verified-button">
                <button class="button-primary">Klik untuk Verifikasi</button>
            </span>
        </td>
        </tr>
        <tr>
            <td colspan="3">
                <label>Nama: </label><br/>
                <input name="nama" class="regular-text"/>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <label>Nik: </label><br/>
                <input name="nik" class="regular-text"/>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <label>No HP: </label><br/>
                <input name="no_hp" class="regular-text"/>
            </td>
        </tr>
        <tr>
            <td>
                <label>Umur: </label><br/>
                <input name="umur" class="small-text"/>
            </td>
            <td>
                <label>RT: </label><br/>
                <input name="rt" class="small-text"/>
            </td>
            <td>
                <label>RW: </label><br/>
                <input name="rw" class="small-text"/>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <label>Dusun: </label><br/>
                <input name="dusun" class="regular-text"/>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <label>Asal Kota: </label><br/>
                <input name="asal_kota" class="regular-text"/>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <label>Tgl Kepulangan: </label><br/>
                <input name="tgl_kepulangan" class="regular-text"/>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <label>Keluhan: </label><br/>
                <input name="keluhan" class="regular-text"/>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <label>Keterangan Tambahan: </label><br/>
                <textarea name="keterangan" class="regular-text"></textarea>
            </td>
        </tr>
    </table>
    <button id="info-pendatang-dialog-save-button" class="button-primary"> Simpan Perubahan </button>
</div>