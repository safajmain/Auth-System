'use strict';

(() => {
    const themeService = window.OptiFitTheme;
    const body = document.body;
    const sidebar = document.querySelector('[data-sidebar]');
    const sidebarOpen = document.querySelector('[data-sidebar-open]');
    const sidebarClose = document.querySelector('[data-sidebar-close]');
    const sidebarCollapse = document.querySelector('[data-sidebar-collapse]');
    const studio = document.querySelector('[data-theme-studio]');
    const studioBackdrop = document.querySelector('.theme-backdrop');
    const themeName = document.querySelector('[data-theme-name]');
    let returnFocus = null;

    const syncBodyLock = () => {
        body.classList.toggle(
            'is-locked',
            Boolean(studio?.classList.contains('is-open') || sidebar?.classList.contains('is-mobile-open'))
        );
    };

    const closeSidebar = () => {
        sidebar?.classList.remove('is-mobile-open');
        sidebarClose?.classList.remove('is-open');
        sidebarOpen?.setAttribute('aria-expanded', 'false');
        syncBodyLock();
    };

    const openSidebar = () => {
        sidebar?.classList.add('is-mobile-open');
        sidebarClose?.classList.add('is-open');
        sidebarOpen?.setAttribute('aria-expanded', 'true');
        syncBodyLock();
        sidebar?.querySelector('a, button')?.focus();
    };

    const closeStudio = () => {
        if (!studio) return;
        studio.classList.remove('is-open');
        studioBackdrop?.classList.remove('is-open');
        studio.setAttribute('aria-hidden', 'true');
        syncBodyLock();
        if (returnFocus instanceof HTMLElement) returnFocus.focus();
    };

    const openStudio = (trigger) => {
        if (!studio) return;
        closeSidebar();
        returnFocus = trigger instanceof HTMLElement ? trigger : document.activeElement;
        studio.classList.add('is-open');
        studioBackdrop?.classList.add('is-open');
        studio.setAttribute('aria-hidden', 'false');
        syncBodyLock();
        window.setTimeout(() => studio.querySelector('[data-theme-close]')?.focus(), 30);
    };

    const syncThemeControls = (themeId) => {
        document.querySelectorAll('button[data-theme]').forEach((button) => {
            const selected = button.dataset.theme === themeId;
            button.classList.toggle('is-selected', selected);
            button.setAttribute('aria-pressed', String(selected));
        });

        const selectedButton = document.querySelector(`button[data-theme="${themeId}"]`);
        if (themeName) themeName.textContent = selectedButton?.dataset.themeLabel || (themeId === 'custom' ? 'لون مخصص' : themeId);
    };

    const applyTheme = (themeId) => {
        const applied = themeService?.apply(themeId) || { id: themeId };
        syncThemeControls(applied.id);
    };

    sidebarOpen?.addEventListener('click', openSidebar);
    sidebarClose?.addEventListener('click', closeSidebar);
    sidebarCollapse?.addEventListener('click', () => {
        const expanded = sidebar?.classList.toggle('is-expanded') ?? false;
        sidebarCollapse.setAttribute('aria-expanded', String(expanded));
        sidebarCollapse.setAttribute('aria-label', expanded ? 'طي القائمة' : 'توسيع القائمة');
    });

    document.querySelectorAll('[data-theme-open]').forEach((button) => {
        button.addEventListener('click', () => openStudio(button));
    });
    document.querySelectorAll('[data-theme-close]').forEach((button) => button.addEventListener('click', closeStudio));
    document.querySelector('[data-theme-reset]')?.addEventListener('click', () => {
        const applied = themeService?.reset() || { id: 'volt' };
        syncThemeControls(applied.id);
    });
    document.querySelectorAll('button[data-theme]').forEach((button) => {
        button.addEventListener('click', () => applyTheme(button.dataset.theme));
    });

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

    document.querySelectorAll('[data-dialog-open]').forEach((button) => {
        button.addEventListener('click', () => {
            const dialog = document.getElementById(button.dataset.dialogOpen);
            if (dialog instanceof HTMLDialogElement) dialog.showModal();
        });
    });
    document.querySelectorAll('[data-dialog-close]').forEach((button) => {
        button.addEventListener('click', () => button.closest('dialog')?.close());
    });
    document.querySelectorAll('.gym-dialog').forEach((dialog) => {
        dialog.addEventListener('click', (event) => {
            if (event.target === dialog) dialog.close();
        });
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            if (studio?.classList.contains('is-open')) closeStudio();
            else if (sidebar?.classList.contains('is-mobile-open')) closeSidebar();
            return;
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

    window.addEventListener('resize', () => {
        if (window.innerWidth > 880) closeSidebar();
    });

    window.addEventListener('optifit:themechange', (event) => syncThemeControls(event.detail.id));
    syncThemeControls(themeService?.get() || 'volt');
})();
