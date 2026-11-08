'use strict';

/** OptiFit APEX theme service. Stores appearance preferences only. */
(() => {
    const STORAGE_KEY = 'optifit.apex.appearance.v1';
    const DEFAULT_THEME = 'volt';
    const CUSTOM_DEFAULT = '#7df8e6';
    const themes = Object.freeze({
        volt: { accent: '#c8ff46', hover: '#e4ffac', background: '#0d120f', surface: '#182019', alt: '#171e18', deep: '#0f1711', sidebar: '#111713', raised: '#283428' },
        glacier: { accent: '#66dfff', hover: '#b7f1ff', background: '#07111d', surface: '#12273b', alt: '#102136', deep: '#0b1b2c', sidebar: '#0b1a2a', raised: '#24425b' },
        ultraviolet: { accent: '#d2a4ff', hover: '#e9caff', background: '#130d1c', surface: '#251b34', alt: '#211729', deep: '#1a1324', sidebar: '#191323', raised: '#392747' },
        ember: { accent: '#ffab82', hover: '#ffd3bc', background: '#170d0e', surface: '#2c1d20', alt: '#26191b', deep: '#1e1314', sidebar: '#1d1315', raised: '#43272a' },
        royal: { accent: '#ffdd7b', hover: '#ffeaaa', background: '#13120d', surface: '#28241b', alt: '#211f19', deep: '#1b1913', sidebar: '#191811', raised: '#363023' },
        tidal: { accent: '#63f4e0', hover: '#b2fff0', background: '#071b1e', surface: '#143039', alt: '#0f2831', deep: '#0c2027', sidebar: '#0d2228', raised: '#25505b' },
        cobalt: { accent: '#8eadff', hover: '#c5d3ff', background: '#080e24', surface: '#192544', alt: '#15203b', deep: '#101a32', sidebar: '#0e1932', raised: '#29375b' },
        sakura: { accent: '#ff9bce', hover: '#ffd0e7', background: '#1b0e19', surface: '#342030', alt: '#2d1a29', deep: '#261521', sidebar: '#241523', raised: '#512a45' },
        crimson: { accent: '#ff6d78', hover: '#ffb7bc', background: '#1c0c11', surface: '#341b23', alt: '#2b1620', deep: '#26131b', sidebar: '#22121a', raised: '#522e39' },
        graphite: { accent: '#e3eaf3', hover: '#ffffff', background: '#0e1116', surface: '#252c35', alt: '#1d242e', deep: '#171d25', sidebar: '#131a22', raised: '#343f49' },
        copper: { accent: '#f2b881', hover: '#ffdbbc', background: '#1b130e', surface: '#32251a', alt: '#2b2017', deep: '#221b14', sidebar: '#241a14', raised: '#473426' },
        solar: { accent: '#f8e869', hover: '#fff6b3', background: '#17160b', surface: '#2f2d17', alt: '#262513', deep: '#211f12', sidebar: '#232114', raised: '#403d21' },
        mint: { accent: '#95ffc2', hover: '#d3ffe3', background: '#071b13', surface: '#183528', alt: '#142b21', deep: '#0d241b', sidebar: '#0f261c', raised: '#2b4b3a' },
        fuchsia: { accent: '#ff91f5', hover: '#ffd2fa', background: '#190d21', surface: '#322040', alt: '#2b1a39', deep: '#211729', sidebar: '#24152f', raised: '#492955' }
    });

    const isHex = (value) => /^#[0-9a-f]{6}$/i.test(String(value || ''));
    const customPalette = (color) => ({ accent: isHex(color) ? color : CUSTOM_DEFAULT, hover: isHex(color) ? color : CUSTOM_DEFAULT, background: '#10131d', surface: '#222638', alt: '#1d2132', deep: '#151b29', sidebar: '#161b29', raised: '#31374c' });
    const read = () => {
        try {
            const parsed = JSON.parse(window.localStorage.getItem(STORAGE_KEY) || 'null');
            if (parsed?.id === 'custom' && isHex(parsed.color)) return { id: 'custom', color: parsed.color };
            if (themes[parsed?.id]) return { id: parsed.id };
        } catch (_) {}
        return { id: DEFAULT_THEME };
    };
    const write = (selection) => {
        try { window.localStorage.setItem(STORAGE_KEY, JSON.stringify(selection)); } catch (_) {}
    };
    const setVariables = (palette) => {
        const root = document.documentElement;
        const variables = {
            '--accent': palette.accent, '--accent-hover': palette.hover, '--background': palette.background,
            '--surface': palette.surface, '--surface-alt': palette.alt, '--surface-deep': palette.deep,
            '--sidebar': palette.sidebar, '--raised': palette.raised,
            '--apx-a': palette.accent, '--apx-ah': palette.hover, '--apx-bg': palette.background,
            '--apx-surface': palette.surface, '--apx-alt': palette.alt, '--apx-deep': palette.deep,
            '--apx-side': palette.sidebar, '--apx-raised': palette.raised,
            '--a': palette.accent, '--ah': palette.hover, '--bg': palette.background,
            '--alt': palette.alt, '--deep': palette.deep
        };
        Object.entries(variables).forEach(([name, value]) => root.style.setProperty(name, value));
    };
    const apply = (themeId, options = {}) => {
        const saved = read();
        const color = isHex(options.color) ? options.color : saved.color;
        const id = themeId === 'custom' ? 'custom' : (themes[themeId] ? themeId : DEFAULT_THEME);
        const palette = id === 'custom' ? customPalette(color) : themes[id];
        const selection = id === 'custom' ? { id, color: palette.accent } : { id };
        setVariables(palette);
        document.documentElement.dataset.theme = id;
        document.documentElement.dataset.apxTheme = id;
        document.querySelector('meta[name="theme-color"]')?.setAttribute('content', palette.background);
        if (options.persist !== false) write(selection);
        window.dispatchEvent(new CustomEvent('optifit:themechange', { detail: { ...selection, palette } }));
        return { ...selection, palette };
    };
    const current = () => read();
    window.OptiFitTheme = Object.freeze({
        storageKey: STORAGE_KEY, defaultTheme: DEFAULT_THEME, themes, current,
        get: () => current().id,
        getCustomColor: () => current().color || CUSTOM_DEFAULT,
        apply,
        reset: () => apply(DEFAULT_THEME)
    });
    const saved = current();
    apply(saved.id, { color: saved.color, persist: false });
})();
