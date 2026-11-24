<?php
require_once __DIR__ . '/../../bootstrap.php';
requireAdmin();

$pageTitle = $topbarTitle = 'Catatan Rilis';

// Tambahkan rilis baru di atas dan samakan versinya dengan version.php.
$releases = [[
    'version' => APP_VERSION,
    'title' => 'Portal Izin Hukum',
    'changes' => ['Katalog layanan, paket, dan persyaratan tersedia melalui menu Layanan Izin Hukum.'],
]];
?>
<?php include __DIR__ . '/../../partials/head.php'; ?>
<body class="bg-gray-50 dark:bg-gray-950 antialiased">
<div class="flex h-screen overflow-hidden">
  <?php include __DIR__ . '/../../partials/sidebar-admin.php'; ?>
  <div class="flex min-w-0 flex-1 flex-col overflow-hidden lg:ml-60">
    <?php include __DIR__ . '/../../partials/topbar.php'; ?>
    <main class="flex-1 overflow-y-auto p-4 sm:p-5 lg:p-6">
      <div class="mx-auto max-w-3xl">
        <header class="dmi-hero mb-6">
          <div>
            <h2>Catatan Rilis</h2>
            <p>Izin Hukum · terpasang v<?= e(APP_VERSION) ?></p>
          </div>
          <a class="dmi-cta" href="<?= e(APP_URL) ?>/admin/update"><span aria-hidden="true">←</span> Update Sistem</a>
        </header>
        <ol class="space-y-4" aria-label="Riwayat versi Izin Hukum">
          <?php foreach ($releases as $release): ?>
          <?php if (version_compare($release['version'], APP_VERSION, '>')) { continue; } ?>
          <li class="dmi-kajian-panel rounded-xl border border-gray-200 bg-white p-4 sm:p-5 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-wrap items-center gap-3">
              <span class="rounded-lg bg-gray-100 px-2 py-1 font-mono text-sm text-gray-700 dark:bg-gray-800 dark:text-gray-200">v<?= e($release['version']) ?></span>
              <?php if ($release['version'] === APP_VERSION): ?>
              <span class="rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-800 dark:bg-green-900/40 dark:text-green-200">Versi terpasang</span>
              <?php endif; ?>
            </div>
            <h3 class="mt-3 text-lg font-bold text-gray-900 dark:text-white"><?= e($release['title']) ?></h3>
            <ul class="ml-5 mt-2 list-disc space-y-1 text-sm leading-relaxed text-gray-700 dark:text-gray-300">
              <?php foreach ($release['changes'] as $change): ?>
              <li><?= e($change) ?></li>
              <?php endforeach; ?>
            </ul>
          </li>
          <?php endforeach; ?>
        </ol>
      </div>
    </main>
  </div>
</div>
<?php include __DIR__ . '/../../partials/footer.php'; ?>
