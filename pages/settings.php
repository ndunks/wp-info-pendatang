<h1>Pengaturan</h1>
<fieldset>
    <legend>
        <h3>Nomor WA</h3>
    </legend>
    <input class="regular-text" type="text" name="no_wa" value="<?= esc_attr(InfoPendatang::$config['no_wa']) ?>"/>
    <button id="info_pendatang_settings_no_wa" class="button-primary">Ubah No WA</button>
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