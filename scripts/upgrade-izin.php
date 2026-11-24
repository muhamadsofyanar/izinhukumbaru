<?php
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}
require __DIR__ . '/../config.php';
require __DIR__ . '/../db.php';

$db = getDB();
$db->beginTransaction();
try {
    $marker = 'izin_brand_upgrade_v1';
    $claim = $db->prepare("INSERT IGNORE INTO settings (setting_key, setting_value, setting_group) VALUES (?, '1', 'general')");
    $claim->execute([$marker]);
    if ($claim->rowCount()) {
        $rename = $db->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ? AND setting_value = ?");
        foreach (['app_name', 'homepage_headline'] as $key) {
            foreach (['STIFIn Mulia', 'STIFIn Cabang', 'Averion'] as $previous) {
                $rename->execute(['Izin Hukum', $key, $previous]);
            }
        }
        $rename->execute(['Layanan pendirian badan usaha, perizinan, dan dokumen hukum', 'homepage_subheadline', 'Kantor virtual cabang untuk belajar, transaksi, dan voucher tes']);
        $db->exec("UPDATE products SET status = 'inactive', hide_from_catalog = 1
                   WHERE slug IN ('wsl-1','wsl-2','wst-health','wst-learning','wst-tahfizh','voucher-tes-stifin','ebook-coba-peran-promotor-stifin')");
        $db->exec("UPDATE canvas_pages SET is_active = 0, is_homepage = 0
                   WHERE slug IN ('ujian-wsl-1','ujian-wsl-2','ujian-wst-health','ujian-wst-learning','ujian-wst-tahfizh','beli-voucher-stifin')");
    }
    $db->commit();
} catch (Throwable $e) {
    $db->rollBack();
    throw $e;
}
