'use strict';

document.querySelectorAll('[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (!window.confirm(form.dataset.confirm || 'هل أنت متأكد؟')) event.preventDefault();
    });
});

(() => {
    const themeService = window.OptiFitTheme;
    const body = document.body;
    const studio = document.querySelector('[data-apx-theme-studio]');
    const backdrop = document.querySelector('.apx-theme-backdrop');
    const themeName = document.querySelector('[data-apx-theme-name]');
    let returnFocus = null;

    const syncThemeControls = (themeId) => {
        document.querySelectorAll('button[data-apx-theme]').forEach((button) => {
            const selected = button.dataset.apxTheme === themeId;
            button.classList.toggle('is-selected', selected);
            button.setAttribute('aria-pressed', String(selected));
        });
        const selectedButton = document.querySelector(`button[data-apx-theme="${themeId}"]`);
        if (themeName) themeName.textContent = selectedButton?.dataset.apxThemeLabel || (themeId === 'custom' ? 'لون مخصص' : themeId);
    };

    const applyTheme = (themeId) => {
        const applied = themeService?.apply(themeId) || { id: themeId };
        syncThemeControls(applied.id);
    };
    const syncBodyLock = () => {
        body.classList.toggle('apx-locked', Boolean(studio?.classList.contains('is-open') || document.querySelector('[data-apx-drawer].is-open')));
    };
    const closeTheme = () => {
        if (!studio) return;
        studio.classList.remove('is-open');
        backdrop?.classList.remove('is-open');
        studio.setAttribute('aria-hidden', 'true');
        syncBodyLock();
        returnFocus?.focus?.();
    };
    const openTheme = (trigger) => {
        if (!studio) return;
        returnFocus = trigger;
        studio.classList.add('is-open');
        backdrop?.classList.add('is-open');
        studio.setAttribute('aria-hidden', 'false');
        syncBodyLock();
        window.setTimeout(() => studio.querySelector('[data-apx-theme-close]')?.focus(), 30);
    };

    document.querySelectorAll('[data-apx-theme-open]').forEach((button) => button.addEventListener('click', () => openTheme(button)));
    document.querySelectorAll('[data-apx-theme-close]').forEach((button) => button.addEventListener('click', closeTheme));
    document.querySelectorAll('button[data-apx-theme]').forEach((button) => button.addEventListener('click', () => applyTheme(button.dataset.apxTheme)));
    document.querySelector('[data-apx-theme-reset]')?.addEventListener('click', () => {
        const applied = themeService?.reset() || { id: 'volt' };
        syncThemeControls(applied.id);
    });

    document.querySelectorAll('[data-password-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const input = document.getElementById(button.dataset.passwordToggle);
            if (!input) return;
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            button.setAttribute('aria-label', show ? 'إخفاء كلمة المرور' : 'إظهار كلمة المرور');
            input.focus();
        });
    });

    const drawer = document.querySelector('[data-apx-drawer]');
    const drawerShade = document.querySelector('[data-apx-drawer-shade]');
    const drawerOpen = document.querySelector('[data-apx-drawer-open]');
    const closeDrawer = () => {
        drawer?.classList.remove('is-open');
        drawerShade?.classList.remove('is-open');
        drawerOpen?.setAttribute('aria-expanded', 'false');
        syncBodyLock();
    };
    drawerOpen?.addEventListener('click', () => {
        drawer?.classList.add('is-open');
        drawerShade?.classList.add('is-open');
        drawerOpen.setAttribute('aria-expanded', 'true');
        syncBodyLock();
        drawer?.querySelector('a, button')?.focus();
    });
    drawerShade?.addEventListener('click', closeDrawer);

    document.querySelectorAll('[data-apx-submit]').forEach((form) => {
        form.addEventListener('submit', () => {
            const submit = form.querySelector('[type="submit"]');
            if (!submit) return;
            submit.disabled = true;
            submit.setAttribute('aria-busy', 'true');
            const label = submit.querySelector('[data-submit-label]');
            if (label) label.textContent = 'جارٍ الحفظ…';
        });
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            if (studio?.classList.contains('is-open')) closeTheme();
            else closeDrawer();
        }
        if (event.key === 'Tab' && studio?.classList.contains('is-open')) {
            const focusable = [...studio.querySelectorAll('button:not([disabled]), a[href], input:not([disabled])')];
            if (!focusable.length) return;
            const first = focusable[0];
            const last = focusable[focusable.length - 1];
            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        }
    });
    window.addEventListener('resize', () => { if (window.innerWidth > 880) closeDrawer(); });
    window.addEventListener('optifit:themechange', (event) => syncThemeControls(event.detail.id));
    syncThemeControls(themeService?.get() || 'volt');
})();
