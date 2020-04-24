<h1>Pengaturan</h1>
<p>Anda dapat menyisipkan data info pendatang pada halaman ataupun postingan dengan mengetikan kode
    (shortcode) berikut:
</p>
<table>
    <tr>
        <td><code>[info_pendatang]</code></td>
        <td>Menampikan ringkasan pendatang (per dusun)</td>
    </tr>
    <tr>
        <td><code>[info_pendatang rtrw]</code></td>
        <td>Menampikan info pendatang per-rt/rw</td>
    </tr>
    <tr>
        <td><code>[info_pendatang asal_kota]</code></td>
        <td>Menampikan info pendatang berdasarkan asal kota</td>
    </tr>
    <tr>
        <td><code>[info_pendatang total]</code></td>
        <td>Menampikan jumlah total pendatang</td>
    </tr>
    <tr>
        <td><code>[info_pendatang last_update]</code></td>
        <td>Menampikan jumlah last_update pendatang</td>
    </tr>
</table>
<hr/>
<fieldset>
    <legend>
        <h3>Nomor WA</h3>
    </legend>
    <input id="info_pendatang_settings_no_wa" class="regular-text" type="text" name="no_wa" value="<?= esc_attr(InfoPendatang::$config['no_wa']) ?>"/>
    <button id="info_pendatang_settings_no_wa_button" class="button-primary">Ubah No WA</button>
    <div class="clear">&nbsp;</div>
</fieldset>
<fieldset>
    <legend>
        <h3>Pesan Himbauan</h3>
    </legend>
    <textarea id="info_pendatang_settings_himbauan" class="regular-text" name="msg_himbauan"><?= esc_html(@InfoPendatang::$config['msg_himbauan']) ?></textarea><br/>
    <button id="info_pendatang_settings_himbauan_button" class="button-primary">Ubah Himbauan</button>
    <div class="clear">&nbsp;</div>
</fieldset>
<fieldset>
    <legend>
        <h3>Daftar Dusun</h3>
    </legend>
    <div id="info_pendatang_settings_dusun">
    </div>
    <div class="clear">&nbsp;</div>
    <div style="text-align: center">
        <button id="info_pendatang_settings_dusun_add" class="button-secondary">Tambah Dusun</button>
        <button id="info_pendatang_settings_dusun_save" class="button-primary">Simpan Perubahan</button>
    </div>
    <div class="clear">&nbsp;</div>
</fieldset>

<hr />
<fieldset id="info_pendatang_settings_wa_server">
    <legend>
        <h3>WA Server</h3>
    </legend>
    <label>URL</label><br />
    <input name="wa_server" type="text" class="regular-text" value="<?=  esc_attr(InfoPendatang::$config['wa_server']) ?>" /><br />
    <label>Secret</label><br />
    <input name="wa_secret" type="text" class="large-text" value="<?=  esc_attr(InfoPendatang::$config['wa_secret']) ?>" /><br/>
    <button id="info_pendatang_settings_wa_server_button" class="button-primary">Ubah Server WA</button>
    <button id="info_pendatang_settings_wa_server_test_button" class="button-secondary">Test</button>
    <div class="clear">&nbsp;</div>
</fieldset>
<fieldset>
    <legend>
        <h3>API Secret</h3>
    </legend>
    <label>Secret</label><br />
    <input type="text" class="regular-text" readonly value="<?=  InfoPendatang::$config['secret'] ?>" /><br />
    <label>URL</label><br />
    <input type="text" class="large-text" readonly value="<?=
    admin_url() . 'admin-ajax.php?action=' . InfoPendatang::$name ?>" />
</fieldset>
<fieldset>
    <legend>
        <h3>Tools</h3>
    </legend>
    <a class="button-secondary" target="_BLANK" href="<?=
        admin_url() . 'admin-ajax.php?action=' . InfoPendatang::$name ?>&do=fix_dusun">
        Sesuaikan nama dusun pada data yg tersimpan (fix_dusun)
    </a>
</fieldset>
<script>
    var dusuns = <?= json_encode(InfoPendatang:: $config['dusun']) ?>;
</script>