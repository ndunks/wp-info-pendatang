<?php

function info_pendatang_setup()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . InfoPendatang::$name;

    $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
        `id` SMALLINT NOT NULL AUTO_INCREMENT,
        `nama` VARCHAR(40) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
        `nik` VARCHAR(20) CHARACTER SET ascii COLLATE ascii_bin NULL,
        `umur` TINYINT UNSIGNED NULL,
        `rt` TINYINT UNSIGNED NULL,
        `rw` TINYINT UNSIGNED NULL,
        `dusun` VARCHAR(20) CHARACTER SET ascii COLLATE ascii_general_ci NULL,
        `asal_kota` VARCHAR(200) CHARACTER SET ascii COLLATE ascii_general_ci NULL,
        `tgl_kepulangan` DATE NULL,
        `keluhan` TEXT CHARACTER SET ascii COLLATE ascii_general_ci NULL,
        `no_hp` VARCHAR(20) NULL,
        `wa_sent` BOOLEAN NULL DEFAULT 0 COMMENT 'Apakah sudah dikirimi WA',
        `verified` BOOLEAN NULL DEFAULT 0 COMMENT 'Apakah sudah diverfikasi oleh pihak desa',
        `pelapor` VARCHAR(40) CHARACTER SET ascii COLLATE ascii_general_ci NULL COMMENT 'Nomor WA yang melaporkan via WA atau ID User atau email',
        `sumber` VARCHAR(10) CHARACTER SET ascii COLLATE ascii_general_ci NULL COMMENT 'Sumber laporan, apakah dari sistem API_WA, WEB_ADMIN, WEB_PUBLIK',
        `keterangan` TEXT CHARACTER SET ascii COLLATE ascii_general_ci NULL,
        `raw` TEXT CHARACTER SET ascii COLLATE ascii_general_ci NULL,
        `dibuat` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`), INDEX( `rt`, `rw`), INDEX( `dusun` )
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    // Save config
    InfoPendatang::commit_option();
}

function info_pendatang_remove()
{
    /* global $wpdb;
    $table_name = $wpdb->prefix . InfoPendatang::$name;
    $wpdb->query("DROP TABLE IF EXISTS $table_name");
    delete_option(InfoPendatang::$name); */
}
