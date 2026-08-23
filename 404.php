<?php
$pageTitle = 'Page Not Found | HomeFix Quetta';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>
<section class="min-h-[60vh] flex items-center justify-center py-20 px-4 bg-slate-50">
  <div class="text-center max-w-md mx-auto page-enter">
    <div class="w-20 h-20 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-6">
      <i data-lucide="search-x" class="w-10 h-10"></i>
    </div>
    <h1 class="text-4xl font-extrabold font-heading text-slate-900 mb-3">Page Not Found</h1>
    <p class="text-sm text-slate-500 mb-8 leading-relaxed">We couldn't find the page you're looking for. It may have been moved or doesn't exist.</p>
    <div class="flex flex-col sm:flex-row gap-3 justify-center">
      <a href="<?= base_url('index.php') ?>" class="btn-primary px-6 py-3 rounded-xl text-sm font-bold inline-flex items-center justify-center gap-2">
        <i data-lucide="home" class="w-4 h-4"></i> Go Home
      </a>
      <a href="<?= base_url('services.php') ?>" class="px-6 py-3 rounded-xl border border-slate-300 text-slate-700 text-sm font-bold hover:bg-slate-100 transition inline-flex items-center justify-center gap-2">
        <i data-lucide="grid-3x3" class="w-4 h-4"></i> Browse Services
      </a>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
