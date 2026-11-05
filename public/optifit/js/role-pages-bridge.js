(function(){
  'use strict';
  const ctx = window.__OPTIFIT_LIVE;
  if (!ctx) return;
  document.addEventListener('click', function(ev) {
    const btn = ev.target.closest('[data-role], [data-view], [data-go], [data-catalog-route], [data-catalog-view]');
    if (!btn) return;
    // المرحلة الأولى: منع تغيير الدور أو التنقل إلى صفحات لم نربطها بعد.
    ev.preventDefault();
    ev.stopImmediatePropagation();
    if ((btn.dataset.view === 'dashboard' || btn.dataset.go === 'dashboard') && btn.closest('nav')) {
      window.location.assign('/dashboard');
    }
  }, true);
})();
