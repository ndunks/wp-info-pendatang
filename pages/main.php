<div style="float: right; padding: 10px;">
    <a href="<?= admin_url('admin.php?page=' . InfoPendatang::$name . '&view=settings') ?>"
        class="button-primary info_pendatang_button">
        <span class="dashicons dashicons-admin-settings"></span>
        <span>Pengaturan</span>
    </a>
</div>
<h1>Info Pendatang</h1>
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

<script>

(function ($,id) {
    var data = null;
    var sumber_maps = {
        'API_WA': 'WhatsApp',
        'WEB_ADMIN': 'Input Web Admin',
        'WEB_PUBLIK': 'Input Web Warga'
    }

    $(id).dialog({
        title: 'Detail Pendatang',
        dialogClass: 'wp-dialog',
        autoOpen: false,
        draggable: false,
        width: 'auto',
        modal: true,
        resizable: false,
        closeOnEscape: true,
        position: {
            my: "center",
            at: "center",
            of: window
        },
        open: function () {
            $('.ui-widget-overlay').bind('click', function(){
                $(id).dialog('close');
            })
        },
        create: function () {
            $('.ui-dialog-titlebar-close').addClass('ui-button');
        },
    });

    $('#info-pendatang-verified-button').click(function(e){
        if(confirm('Anda yakin akan memverifikasi data ini?')){
            $.post(ajaxurl + '?action=info_pendatang&do=patch&id=' + data.id, {
                verified: 1
            }, function(){
                location.reload();
            });
        }
    })

    $('#info-pendatang-dialog-save-button').click(function(e){
        var newData = {};
        $(id).find('[name]').each(function(){
            var el = $(this);
            var name = el.attr('name');
            var newVal = el.val()
            if( !newVal != !data[name] && newVal != data[name] ){
                newData[name] = newVal;
            }
        })

        if(Object.keys(newData).length){
            $.post(ajaxurl + '?action=info_pendatang&do=patch&id=' + data.id, newData, function(){
                location.reload();
            });
        }else{
            alert('Tidak ada perubahan yang disimpan');
            $(id).dialog('close');
        }
    })

  // bind a button or a link to open the dialog
  $('.info-pendatang-dialog-button').click(function(e) {
    e.preventDefault();
    data = jQuery(this).parent().parent().data('json');
    
    // info pendatang apply values
    $(id).find('[name]').each(function(){
        var el = $(this);
        var name = el.attr('name');
        el.val( data[name] );
    })

    $('#info-pendatang-sumber').text(sumber_maps[data.sumber] || data.sumber);
    $('#info-pendatang-pelapor').text(data.pelapor || '(Tdk Tahu)');
    if(data.verified){
        $('#info-pendatang-verified').html('<b style="color:red">Belum di Verifikasi </b>');
    }else{
        $('#info-pendatang-verified').html('<b style="color:green">Sudah di Verifikasi</b>');
    }

    $(id).dialog('open');
  });
})(jQuery, '#info-pendatang-dialog');

</script>