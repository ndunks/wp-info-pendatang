(function ($) {
  var modules = {
    '#info-pendatang-dialog': function (id, me) {
      var data = null;
      var sumber_maps = {
        'API_WA': 'WhatsApp',
        'WEB_ADMIN': 'Input Web Admin',
        'WEB_PUBLIK': 'Input Web Publik'
      }

      me.dialog({
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
          $('.ui-widget-overlay').bind('click', function () {
            me.dialog('close');
          })
        },
        create: function () {
          $('.ui-dialog-titlebar-close').addClass('ui-button');
        },
      });

      $('#info-pendatang-verified-button').click(function (e) {
        if (confirm('Anda yakin akan memverifikasi data ini?')) {
          $.ajax({
            type: "POST",
            url: ajaxurl + '?action=info_pendatang&do=patch&id=' + data.id,
            data: {
              verified: 1,
              no_hp: me.find('[name="no_hp"]').val()
            },
            success: function () {
              location.reload();
            },
            error: function (xhr, textStatus, errorThrown) {
              console.log(arguments)
              if (xhr && xhr.responseText) {
                alert('Mohon Maaf, Terjadi Kesalahan\n' + xhr.responseText);
              }
            }
          });
        }
      })

      $('#info-pendatang-dialog-save-button').click(function (e) {
        var newData = {};
        me.find('[name]').each(function () {
          var el = $(this);
          var name = el.attr('name');
          var newVal = el.val()

          if ((!!newVal) != (!!data[name]) || (!!(newVal || data[name]) && newVal != data[name])) {
            newData[name] = newVal;
          }
        })

        if (Object.keys(newData).length) {
          $.post(ajaxurl + '?action=info_pendatang&do=patch&id=' + data.id, newData, function () {
            location.reload();
          });
        } else {
          alert('Tidak ada perubahan yang disimpan');
          me.dialog('close');
        }
      })
      $('#info-pendatang-dialog-delete-button').click(function (e) {
        if (!confirm('Yakin hapus data ini?')) return;
        $.get(ajaxurl + '?action=info_pendatang&do=delete&id=' + data.id, function () {
          location.reload();
        });
      })
      // bind a button or a link to open the dialog
      $('.info-pendatang-click-row').click(function (e) {
        e.preventDefault();

        data = $(this).data('json');


        // info pendatang apply values
        me.find('[name]').each(function () {
          var el = $(this);
          var name = el.attr('name');
          el.val(data[name]);
        })
        if (parseInt(data['verified'])) {
          $('#info-pendatang-verified-button').hide();
          $('#info-pendatang-verified').html('<span style="color:green">Sudah di Verifikasi</span>');
        } else {
          $('#info-pendatang-verified-button').show();
          $('#info-pendatang-verified').html('<span style="color:red">Belum di Verifikasi </span>');
        }
        $('#info-pendatang-sumber').text(sumber_maps[data.sumber] || data.sumber);
        $('#info-pendatang-pelapor').text(data.pelapor || '(Tdk Tahu)');
        $('#info-pendatang-waktu').text(data.dibuat.replace(/^(\d+)-(\d+)-(\d+)\s(.*)$/, '$3/$2/$1 $4'));

        me.dialog('open');
      });
    },
    '#info_pendatang_settings_wa_server_button': function (id, me) {
      me.click(function () {
        var data = {
          'update': 'bulk'
        }
        $('#info_pendatang_settings_wa_server')
          .find('[name]').each(function () {
            var el = $(this);
            data[el.attr('name')] = el.val();
          })
        $.post(ajaxurl + '?action=info_pendatang&do=config', data, function (res) {
          alert(res);
        })
      })
    },
    '#info_pendatang_settings_wa_server_test_button': function (id, me) {
      me.click(function () {
        var data = {}
        $('#info_pendatang_settings_wa_server')
          .find('[name]').each(function () {
            var el = $(this);
            data[el.attr('name')] = el.val();
          })
        $.post(ajaxurl + '?action=info_pendatang&do=wa_server_test', data, function (res) {
          alert(res);
        })
      })
    },
    '#info_pendatang_settings_no_wa_button': function (id, me) {
      me.click(function () {
        $.post(ajaxurl + '?action=info_pendatang&do=config', {
          'update': 'no_wa',
          'value': $('#info_pendatang_settings_no_wa').val()
        }, function () { alert('No wa disimpan') })
      })
    },
    '#info_pendatang_settings_himbauan_button': function (id, me) {
      me.click(function () {
        $.post(ajaxurl + '?action=info_pendatang&do=config', {
          'update': 'msg_himbauan',
          'value': $('#info_pendatang_settings_himbauan').val()
        }, function () { alert('Himbauan disimpan') })
      })
    },
    '#info_pendatang_send_wa_button': function (id, me) {
      me.click(function () {
        me.attr('disabled', 1);
        var data = {}
        $('#info_pendatang_send_wa')
          .find('[name]').each(function () {
            var el = $(this);
            data[el.attr('name')] = el.val();
          })
        $.post(ajaxurl + '?action=info_pendatang&do=send_wa', data, function (response) {
          me.removeAttr('disabled');
          console.log(response);
          alert(response);
        })
      })
    },
    '#info_pendatang_settings_dusun': function (id, me) {
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
        me.append(el);
      }
      $('#info_pendatang_settings_dusun_add').click(function () {
        var data = {
          no: dusuns.reduce(function (c, v) { return v.no > c ? v.no : c }, 1) + 1
        }
        dusuns.push(data);
        add_dusun(data);
      })
      $('#info_pendatang_settings_dusun_save').click(function () {
        if (!confirm('Simpan perubahan?')) {
          return;
        }
        var newData = [];
        me.children().each(function () {
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
    }
  }

  // Modules loader based on id existences
  $(document).ready(function () {
    for (var id in modules) {
      var me = $(id)
      if (me.length) {
        modules[id](id, me)
      }
    }
  })
})(jQuery);
