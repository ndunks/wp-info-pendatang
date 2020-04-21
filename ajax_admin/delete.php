<?php

$id  = intval($_GET['id']);

if (!$id || $id < 1) {
    throw new Exception("Invalid ID", 406);
}

if (! $wpdb->delete(InfoPendatang::$table, ['id' => $id])) {
    throw new Exception("Gagal menghapus data", 500);
}

return 'OK';
