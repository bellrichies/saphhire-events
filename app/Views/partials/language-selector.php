<?php

/**
 * Language Selector Partial
 * Clean, simple dropdown UI without Google branding
 * No Tailwind classes to avoid conflicts
 */
?>

<div class="language-selector-wrapper" style="position: relative; display: inline-block; z-index: 100;">
    <!-- Desktop Language Selector (hidden on mobile via CSS) -->
    <div class="language-desktop-selector" style="display: none;">
        <button 
            id="lang-toggle" 
            class="language-toggle-btn"
            type="button"
            aria-label="Toggle language menu"
            aria-expanded="false"
            style="
                display: flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.5rem 0.75rem;
                background-color: transparent;
                border: 1px solid #e5e7eb;
                border-radius: 0.375rem;
                cursor: pointer;
                font-size: 0.875rem;
                font-weight: 500;
                color: #1c1c1c;
                transition: all 0.2s ease;
                font-family: 'Montserrat', sans-serif;
                letter-spacing: 0.03em;
            "
        >
            <span id="current-lang-display" style="display: flex; align-items: center; gap: 0.375rem;">
                <img class="lang-flag" id="current-flag" src="" alt="" style="width: 1.25rem; height: 0.875rem; line-height: 1; border-radius: 0.125rem; object-fit: cover; border: 1px solid #e5e7eb;">
                <span class="lang-code" id="current-code" style="text-transform: uppercase; min-width: 1.5rem; text-align: center; font-weight: 600; letter-spacing: 0.05em;"></span>
            </span>
            <i class="fas fa-chevron-down" style="transition: transform 0.2s ease;"></i>
        </button>

        <div 
            id="lang-menu" 
            class="language-menu"
            role="menu"
            style="
                position: absolute;
                top: 100%;
                right: 0;
                margin-top: 0.5rem;
                width: 192px;
                background-color: #ffffff;
                border: 1px solid #e5e7eb;
                border-radius: 0.5rem;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
                z-index: 9999;
                min-width: max-content;
                display: none;
            "
        >
            <div class="language-menu-list" id="language-list" style="max-height: 320px; overflow-y: auto; padding: 0.5rem 0; list-style: none; margin: 0;"></div>
        </div>
    </div>

    <!-- Mobile Language Selector (shown on mobile via CSS) -->
    <div class="language-mobile-selector" style="display: none;">
        <select 
            id="mobile-lang-select" 
            class="mobile-lang-select"
            aria-label="Select language"
            style="
                width: auto;
                min-width: 120px;
                padding: 0.5rem 0.75rem;
                padding-right: 1.75rem;
                border: 1px solid #e5e7eb;
                border-radius: 0.375rem;
                background-color: #ffffff;
                color: #1c1c1c;
                font-family: 'Montserrat', sans-serif;
                font-size: 0.875rem;
                font-weight: 500;
                cursor: pointer;
                appearance: none;
                background-image: url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2212%22 height=%2712%22 viewBox=%220 0 12 12%22%3E%3Cpath fill=%22%231c1c1c%22 d=%22M6 9L1 4h10z%22/%3E%3C/svg%3E');
                background-repeat: no-repeat;
                background-position: right 0.5rem center;
                background-size: 12px;
            "
        ></select>
    </div>
</div>

<style>
    @media (min-width: 768px) {
        .language-desktop-selector {
            display: block !important;
        }
        .language-mobile-selector {
            display: none !important;
        }
    }
    
    @media (max-width: 767px) {
        .language-desktop-selector {
            display: none !important;
        }
        .language-mobile-selector {
            display: block !important;
        }
    }
    
    .language-toggle-btn:hover {
        background-color: #f9fafb;
        border-color: #d1d5db;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }
    
    .language-toggle-btn:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(15, 61, 62, 0.1);
    }
</style>

<script>
(function() {
    'use strict';
    
    // Debug helper
    const debug = (msg, data = null) => {
        console.log('[LanguageSelector] ' + msg, data || '');
    };
    
    // Guard flag to prevent multiple initializations
    let initialized = false;
    
    // Wait for DOM to be fully ready
    function initLanguageSelector() {
        // Prevent duplicate initialization
        if (initialized) {
            debug('Already initialized, skipping');
            return;
        }
        
        initialized = true;
        debug('Initializing language selector');
        
        // Get current language from HTML lang attribute
        const currentLanguage = document.documentElement.lang || 'en';
        debug('Current language: ' + currentLanguage);
        
        // Language configuration
        const languages = {
            'en': { name: 'English', native: 'English', flag: '<?php echo route('assets/images/flags/gb.svg'); ?>' },
            'et': { name: 'Estonian', native: 'Eesti', flag: '<?php echo route('assets/images/flags/ee.svg'); ?>' },
            'fi': { name: 'Finnish', native: 'Suomi', flag: '<?php echo route('assets/images/flags/fi.svg'); ?>' },
            'ru': { name: 'Russian', native: 'Russkiy', flag: '<?php echo route('assets/images/flags/ru.svg'); ?>' }
        };
        
        // Get DOM elements
        const langToggle = document.getElementById('lang-toggle');
        const langMenu = document.getElementById('lang-menu');
        const langList = document.getElementById('language-list');
        const mobileSelect = document.getElementById('mobile-lang-select');
        
        // Check if elements exist
        if (!langToggle) {
            debug('ERROR: lang-toggle button not found');
            return;
        }
        if (!langMenu) {
            debug('ERROR: lang-menu div not found');
            return;
        }
        if (!langList) {
            debug('ERROR: language-list div not found');
            return;
        }
        
        debug('All DOM elements found successfully');
        
        // === DESKTOP MENU INITIALIZATION ===
        if (langToggle && langMenu && langList) {
            debug('Initializing desktop language menu');
            
            // Clear any existing options first
            langList.innerHTML = '';
            
            // Populate the menu with language options
            Object.entries(languages).forEach(([code, data]) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'language-option';
                button.setAttribute('data-lang', code);
                button.setAttribute('role', 'menuitem');
                
                // Add active class if this is the current language
                if (code === currentLanguage) {
                    button.style.backgroundColor = '#ede9fe';
                }
                
                button.style.cssText = `
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                    padding: 0.75rem 1rem;
                    cursor: pointer;
                    transition: all 0.15s ease;
                    font-family: 'Montserrat', sans-serif;
                    border: none;
                    background: transparent;
                    width: 100%;
                    text-align: left;
                    font-size: 0.875rem;
                    color: #1c1c1c;
                `;
                
                button.innerHTML = `
                    <img src="${data.flag}" alt="${code.toUpperCase()} flag" style="width: 1.25rem; height: 0.875rem; line-height: 1; border-radius: 0.125rem; object-fit: cover; border: 1px solid #e5e7eb;">
                    <span style="flex: 1; display: flex; flex-direction: column;">
                        <span style="font-weight: 500; line-height: 1.2;">${data.name}</span>
                        <span style="font-size: 0.75rem; color: #6b7280; font-style: italic; line-height: 1;">${data.native}</span>
                    </span>
                `;
                
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    debug('Language selected: ' + code);
                    closeMenu();
                    switchLanguage(code);
                });
                
                button.addEventListener('mouseover', () => {
                    button.style.backgroundColor = '#f9fafb';
                });
                
                button.addEventListener('mouseout', () => {
                    if (code !== currentLanguage) {
                        button.style.backgroundColor = 'transparent';
                    }
                });
                
                langList.appendChild(button);
            });
            
            debug('Desktop menu populated with ' + langList.children.length + ' languages');
            
            // Update the display with current language
            updateCurrentLanguageDisplay(currentLanguage);
            
            // === EVENT LISTENERS FOR DESKTOP MENU ===
            
            // Click on toggle button
            langToggle.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                debug('Toggle button clicked');
                toggleMenu();
            });
            
            // Click anywhere on document to potentially close menu
            document.addEventListener('click', (e) => {
                // Only close if click is outside the wrapper
                const wrapper = document.querySelector('.language-selector-wrapper');
                if (!wrapper) {
                    return;
                }
                
                // If clicked element is NOT inside wrapper, close the menu
                if (!wrapper.contains(e.target)) {
                    if (langMenu.style.display === 'block') {
                        debug('Click outside detected, closing menu');
                        closeMenu();
                    }
                }
            });
            
            // Escape key to close menu
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    if (langMenu.style.display === 'block') {
                        debug('Escape key pressed, closing menu');
                        closeMenu();
                    }
                }
            });
        }
        
        // === MOBILE SELECTOR INITIALIZATION ===
        if (mobileSelect) {
            debug('Initializing mobile language selector');
            
            // Clear any existing options first
            mobileSelect.innerHTML = '';
            
            Object.entries(languages).forEach(([code, data]) => {
                const option = document.createElement('option');
                option.value = code;
                option.textContent = data.name;
                if (code === currentLanguage) {
                    option.selected = true;
                }
                mobileSelect.appendChild(option);
            });
            
            mobileSelect.addEventListener('change', (e) => {
                debug('Mobile select changed to: ' + e.target.value);
                switchLanguage(e.target.value);
            });
            
            debug('Mobile selector initialized with ' + mobileSelect.options.length + ' languages');
        }
        
        // === HELPER FUNCTIONS ===
        
        function toggleMenu() {
            if (langMenu.style.display === 'none') {
                openMenu();
            } else {
                closeMenu();
            }
        }
        
        function openMenu() {
            debug('Opening menu');
            langMenu.style.display = 'block';
            langToggle.setAttribute('aria-expanded', 'true');
            
            // Add animation to chevron
            const chevron = langToggle.querySelector('.fa-chevron-down');
            if (chevron) {
                chevron.style.transform = 'rotate(180deg)';
            }
        }
        
        function closeMenu() {
            debug('Closing menu');
            langMenu.style.display = 'none';
            langToggle.setAttribute('aria-expanded', 'false');
            
            // Reset chevron animation
            const chevron = langToggle.querySelector('.fa-chevron-down');
            if (chevron) {
                chevron.style.transform = 'rotate(0deg)';
            }
        }
        
        function updateCurrentLanguageDisplay(langCode) {
            debug('Updating display for language: ' + langCode);
            const data = languages[langCode];
            const flagEl = document.getElementById('current-flag');
            const codeEl = document.getElementById('current-code');
            
            if (flagEl) {
                flagEl.src = data.flag;
                flagEl.alt = langCode.toUpperCase() + ' flag';
            }
            if (codeEl) {
                codeEl.textContent = langCode.toUpperCase();
            }
        }
        
        function switchLanguage(langCode) {
            debug('Switching to language: ' + langCode);
            
            // Build URL with language parameter
            const url = new URL(window.location.href);
            url.searchParams.set('lang', langCode);
            
            debug('Redirecting to: ' + url.toString());
            window.location.href = url.toString();
        }
        
        debug('Language selector initialization complete');
    }
    
    // Initialize when DOM is ready - use only one method
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initLanguageSelector, { once: true });
    } else {
        // DOM is already loaded
        initLanguageSelector();
    }
})();
</script>

