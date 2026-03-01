<?php
$currentLanguage = getCurrentLanguage();
$supportedLanguages = getSupportedLanguages();
$requestUri = $_SERVER['REQUEST_URI'] ?? route('/');
$parts = parse_url($requestUri);
$currentPath = $parts['path'] ?? route('/');
$queryParams = [];
if (!empty($parts['query'])) {
    parse_str($parts['query'], $queryParams);
    unset($queryParams['lang']);
}
$currentRelativeUrl = $currentPath . (empty($queryParams) ? '' : '?' . http_build_query($queryParams));
$buildLanguageUrl = static function (string $lang) use ($currentRelativeUrl): string {
    return route('/lang') . '?' . http_build_query([
        'lang' => $lang,
        'redirect' => $currentRelativeUrl,
    ]);
};
$languageFlags = [
    'en' => route('assets/images/flags/gb.svg'),
    'et' => route('assets/images/flags/ee.svg'),
    'fi' => route('assets/images/flags/fi.svg'),
    'ru' => route('assets/images/flags/ru.svg'),
];
$currentLanguageFlag = $languageFlags[$currentLanguage] ?? route('assets/images/flags/gb.svg');
?>

<header class="bg-white sticky top-0 z-50 border-b border-gray-100">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2 relative">
        <div class="flex justify-between items-center">
            <a href="<?php echo route('/'); ?>" class="flex items-center space-x-2 md:space-x-3 group min-w-0" translate="no">
                <img src="<?php echo route('assets/images/favicon.png'); ?>" alt="Sapphire Events & Decorations Logo" class="w-10 md:w-12 h-auto border border-border group-hover:scale-110 transition-transform shrink-0">
                <div class="flex flex-col leading-none min-w-0">
                    <span class="font-serif text-xl md:text-2xl tracking-[0.24em] md:tracking-[0.38em] font-semibold whitespace-nowrap" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif;">SAPPHIRE</span>
                    <span class="font-sans text-[0.55rem] md:text-[0.65rem] tracking-[0.14em] md:tracking-[0.2em] font-semibold whitespace-nowrap" style="color: #C8A951; font-family: 'Montserrat', sans-serif;">EVENTS & DECORATIONS</span>
                </div>
            </a>

            <div class="hidden md:flex space-x-8 items-center">
                <a href="<?php echo route('/'); ?>" class="hover:text-yellow-600 transition" style="font-family: 'Montserrat', sans-serif; font-weight: 500; letter-spacing: 0.03em; font-size: 0.875rem;"><?php echo htmlspecialchars(trans('pages.header.home', 'Home')); ?></a>
                <a href="<?php echo route('/services'); ?>" class="hover:text-yellow-600 transition" style="font-family: 'Montserrat', sans-serif; font-weight: 500; letter-spacing: 0.03em; font-size: 0.875rem;"><?php echo htmlspecialchars(trans('pages.header.services', 'Services')); ?></a>
                <a href="<?php echo route('/packages'); ?>" class="hover:text-yellow-600 transition" style="font-family: 'Montserrat', sans-serif; font-weight: 500; letter-spacing: 0.03em; font-size: 0.875rem;"><?php echo htmlspecialchars(trans('pages.header.packages', 'Packages')); ?></a>
                <a href="<?php echo route('/gallery'); ?>" class="hover:text-yellow-600 transition" style="font-family: 'Montserrat', sans-serif; font-weight: 500; letter-spacing: 0.03em; font-size: 0.875rem;"><?php echo htmlspecialchars(trans('pages.header.gallery', 'Gallery')); ?></a>
                <a href="<?php echo route('/about'); ?>" class="hover:text-yellow-600 transition" style="font-family: 'Montserrat', sans-serif; font-weight: 500; letter-spacing: 0.03em; font-size: 0.875rem;"><?php echo htmlspecialchars(trans('pages.header.about', 'About')); ?></a>
                <a href="<?php echo route('/faqs'); ?>" class="hover:text-yellow-600 transition" style="font-family: 'Montserrat', sans-serif; font-weight: 500; letter-spacing: 0.03em; font-size: 0.875rem;"><?php echo htmlspecialchars(trans('pages.header.faqs', 'FAQs')); ?></a>
                <a href="<?php echo route('/contact'); ?>" class="hover:text-yellow-600 transition" style="font-family: 'Montserrat', sans-serif; font-weight: 500; letter-spacing: 0.03em; font-size: 0.875rem;"><?php echo htmlspecialchars(trans('pages.header.contact', 'Contact')); ?></a>

                <div class="relative group">
                    <button class="flex items-center space-x-2 px-3 py-2 text-sm font-semibold rounded transition hover:bg-gray-100" style="color: #0F3D3E; font-family: 'Montserrat', sans-serif;" type="button" aria-label="<?php echo htmlspecialchars(trans('pages.header.switch_language', 'Switch Language')); ?>">
                        <img src="<?php echo htmlspecialchars($currentLanguageFlag); ?>" alt="<?php echo htmlspecialchars(strtoupper($currentLanguage)); ?> flag" class="w-5 h-4 rounded-sm object-cover border border-gray-200" loading="lazy" decoding="async">
                        <span><?php echo strtoupper(htmlspecialchars($currentLanguage)); ?></span>
                        <i class="fas fa-chevron-down text-xs opacity-60"></i>
                    </button>
                    <div class="absolute right-0 mt-1 w-44 bg-white border border-gray-200 rounded-lg shadow-lg invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-all duration-200 z-50">
                        <?php foreach ($supportedLanguages as $code => $language): ?>
                            <a href="<?php echo htmlspecialchars($buildLanguageUrl($code)); ?>" class="block w-full text-left px-4 py-3 hover:bg-gray-50 border-b border-gray-100 last:border-b-0" style="font-family: 'Montserrat', sans-serif; color: #1C1C1C; font-size: 0.875rem;">
                                <span class="inline-flex items-center gap-2">
                                    <img src="<?php echo htmlspecialchars($languageFlags[$code] ?? $currentLanguageFlag); ?>" alt="<?php echo htmlspecialchars(strtoupper($code)); ?> flag" class="w-5 h-4 rounded-sm object-cover border border-gray-200" loading="lazy" decoding="async">
                                    <span><?php echo htmlspecialchars(strtoupper($code) . ' - ' . ($language['native'] ?? $language['name'] ?? strtoupper($code))); ?></span>
                                </span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="md:hidden">
                <button class="text-2xl p-2 -mr-2" id="menu-toggle" type="button" aria-label="Toggle navigation menu" aria-expanded="false" aria-controls="mobile-menu">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>

        <div id="mobile-menu" class="hidden md:hidden absolute left-0 right-0 top-full mt-2 z-50 rounded-xl border border-gray-200 bg-white p-4 space-y-4 shadow-xl max-h-[75vh] overflow-y-auto">
            <a href="<?php echo route('/'); ?>" class="block hover:text-yellow-600" style="font-family: 'Montserrat', sans-serif; font-weight: 500;"><?php echo htmlspecialchars(trans('pages.header.home', 'Home')); ?></a>
            <a href="<?php echo route('/services'); ?>" class="block hover:text-yellow-600" style="font-family: 'Montserrat', sans-serif; font-weight: 500;"><?php echo htmlspecialchars(trans('pages.header.services', 'Services')); ?></a>
            <a href="<?php echo route('/packages'); ?>" class="block hover:text-yellow-600" style="font-family: 'Montserrat', sans-serif; font-weight: 500;"><?php echo htmlspecialchars(trans('pages.header.packages', 'Packages')); ?></a>
            <a href="<?php echo route('/gallery'); ?>" class="block hover:text-yellow-600" style="font-family: 'Montserrat', sans-serif; font-weight: 500;"><?php echo htmlspecialchars(trans('pages.header.gallery', 'Gallery')); ?></a>
            <a href="<?php echo route('/about'); ?>" class="block hover:text-yellow-600" style="font-family: 'Montserrat', sans-serif; font-weight: 500;"><?php echo htmlspecialchars(trans('pages.header.about', 'About')); ?></a>
            <a href="<?php echo route('/faqs'); ?>" class="block hover:text-yellow-600" style="font-family: 'Montserrat', sans-serif; font-weight: 500;"><?php echo htmlspecialchars(trans('pages.header.faqs', 'FAQs')); ?></a>
            <a href="<?php echo route('/contact'); ?>" class="block hover:text-yellow-600" style="font-family: 'Montserrat', sans-serif; font-weight: 500;"><?php echo htmlspecialchars(trans('pages.header.contact', 'Contact')); ?></a>

            <div class="pt-4 border-t border-gray-200">
                <p class="text-xs font-semibold text-gray-500 mb-3" style="font-family: 'Montserrat', sans-serif;"><?php echo htmlspecialchars(trans('pages.header.switch_language', 'Switch Language')); ?></p>
                <div class="flex flex-wrap gap-2">
                    <?php foreach ($supportedLanguages as $code => $language): ?>
                        <a href="<?php echo htmlspecialchars($buildLanguageUrl($code)); ?>" class="px-3 py-2 rounded text-sm font-semibold border border-gray-300 hover:bg-gray-100 transition" style="color: #1C1C1C; font-family: 'Montserrat', sans-serif;">
                            <span class="inline-flex items-center gap-2">
                                <img src="<?php echo htmlspecialchars($languageFlags[$code] ?? $currentLanguageFlag); ?>" alt="<?php echo htmlspecialchars(strtoupper($code)); ?> flag" class="w-5 h-4 rounded-sm object-cover border border-gray-200" loading="lazy" decoding="async">
                                <span><?php echo htmlspecialchars(strtoupper($code)); ?></span>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <a href="<?php echo route('/admin/login'); ?>" class="block btn-primary"><?php echo htmlspecialchars(trans('pages.header.admin', 'Admin')); ?></a>
        </div>
    </nav>
</header>

<script>
    const menuToggleButton = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');

    menuToggleButton?.addEventListener('click', () => {
        const isHidden = mobileMenu.classList.toggle('hidden');
        menuToggleButton.setAttribute('aria-expanded', String(!isHidden));
    });

    mobileMenu?.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
            menuToggleButton?.setAttribute('aria-expanded', 'false');
        });
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth >= 768 && mobileMenu && !mobileMenu.classList.contains('hidden')) {
            mobileMenu.classList.add('hidden');
            menuToggleButton?.setAttribute('aria-expanded', 'false');
        }
    });
</script>



