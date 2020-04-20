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
    (function ($, id) {
        var dusuns = <?= json_encode(InfoPendatang:: $config['dusun']) ?>;

        console.log(dusuns);
        function add_dusun(data) {
            var el = $('<div class="info_pendatang_settings_dusun_item">' +
                '<label>Nomor Dusun</label><br>' +
                '<input type="text" class="no small-text" /><br>' +
                '<label>Nama Dusun</label><br>' +
                '<input type="text" class="nama regular-text" /><br>' +
                '<label>Daftar RW</label><br>' +
                '<input type="text" class="rw regular-text" /><br>' +
                '</div>');
            el.find('.no').val(data.no);
            el.find('.nama').val(data.nama);
            el.find('.rw').val((data.rw || []).join(','));
            $(id).append(el);
        }
        $('#info_pendatang_settings_dusun_add').click(function () {
            var data = {
                no: dusuns.reduce(function (c, v) { return v.no > c ? v.no : c }, 1) + 1
            }
            console.log(data)
            dusuns.push(data);
            add_dusun(data);
        })
        $('#info_pendatang_settings_dusun_save').click(function () {
            if (!confirm('Simpan perubahan?')) {
                return;
            }
            var newData = [];
            $(id).children().each(function () {
                var el = $(this);
                var item = {
                    no: parseInt(el.find('.no').val()),
                    nama: el.find('.nama').val(),
                    rw: (el.find('.rw').val() || "").split(",")
                        .map(function (v) {
                            return parseInt(v)
                        }).filter(function (v) {
                            return v > 0 && v < 200
                        }),
                }
                newData.push(item)
            })
            console.log(newData);
            $.post(ajaxurl + '?action=info_pendatang&do=config', { dusun: newData }, function () {
                alert('Berhasil disimpan');
                location.reload();
            });
        })
        dusuns.forEach(add_dusun);
    })(jQuery, '#info_pendatang_settings_dusun')
</script>