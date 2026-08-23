<?php
$pageTitle = 'Something Went Wrong | HomeFix Quetta';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>
<section class="min-h-[60vh] flex items-center justify-center py-20 px-4 bg-slate-50">
  <div class="text-center max-w-md mx-auto page-enter">
    <div class="w-20 h-20 rounded-full bg-slate-100 text-rose-500 flex items-center justify-center mx-auto mb-6">
      <i data-lucide="alert-triangle" class="w-10 h-10"></i>
    </div>
    <h1 class="text-4xl font-extrabold font-heading text-slate-900 mb-3">Something Went Wrong</h1>
    <p class="text-sm text-slate-500 mb-8 leading-relaxed">We experienced an internal server error. Please try again later or contact support if the issue persists.</p>
    <div class="flex flex-col sm:flex-row gap-3 justify-center">
      <button onclick="location.reload()" class="btn-primary px-6 py-3 rounded-xl text-sm font-bold inline-flex items-center justify-center gap-2">
        <i data-lucide="refresh-cw" class="w-4 h-4"></i> Try Again
      </button>
      <a href="<?= base_url('index.php') ?>" class="px-6 py-3 rounded-xl border border-slate-300 text-slate-700 text-sm font-bold hover:bg-slate-100 transition inline-flex items-center justify-center gap-2">
        <i data-lucide="home" class="w-4 h-4"></i> Go Home
      </a>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
