<h1>Pengaturan</h1>
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
<script>
    var dusuns = <?= json_encode(InfoPendatang:: $config['dusun']) ?>;
</script>