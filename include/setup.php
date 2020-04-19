<?php

function info_pendatang_setup()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . InfoPendatang::$name;

    $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
        `id` SMALLINT NOT NULL,
        `nama` INT NOT NULL,
        `nik` VARCHAR(20) CHARACTER SET ascii COLLATE ascii_bin NULL,
        `umur` TINYINT UNSIGNED NULL,
        `rt` TINYINT UNSIGNED NULL,
        `rw` TINYINT UNSIGNED NULL,
        `dusun` VARCHAR(20) CHARACTER SET ascii COLLATE ascii_general_ci NULL,
        `asal_kota` VARCHAR(200) CHARACTER SET ascii COLLATE ascii_general_ci NULL,
        `tgl_kepulangan` DATE NULL,
        `keluhan` TEXT CHARACTER SET ascii COLLATE ascii_general_ci NULL,
        `no_hp` VARCHAR(20) NULL,
        `wa_sent` BOOLEAN NULL,
        `no_pelapor` VARCHAR(20) NULL,
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
