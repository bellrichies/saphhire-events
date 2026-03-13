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
$siteName = appConfig('site.name', 'Sapphire Events & Decorations');
$logoUrl = route('assets/images/logo.png');
$desktopLeftNav = [
    ['href' => route('/'), 'label' => trans('pages.header.home', 'Home')],
    ['href' => route('/services'), 'label' => trans('pages.header.services', 'Services')],
    ['href' => route('/packages'), 'label' => trans('pages.header.packages', 'Packages')],
    ['href' => route('/gallery'), 'label' => trans('pages.header.gallery', 'Gallery')],
];
$desktopRightNav = [
    ['href' => route('/about'), 'label' => trans('pages.header.about', 'About')],
    ['href' => route('/team'), 'label' => trans('pages.header.team', 'Team')],
    ['href' => route('/faqs'), 'label' => trans('pages.header.faqs', 'FAQs')],
    ['href' => route('/contact'), 'label' => trans('pages.header.contact', 'Contact')],
];
?>

<header class="sticky top-0 z-50 border-b border-[#f0d7eb]/70 bg-white">
    <nav class="site-gutter py-2 relative">
        <div class="flex justify-between items-center md:hidden">
            <a href="<?php echo route('/'); ?>" class="flex items-center space-x-2 md:space-x-3 group min-w-0" translate="no" aria-label="<?php echo htmlspecialchars($siteName); ?>">
                <img
                    src="<?php echo htmlspecialchars($logoUrl); ?>"
                    alt="<?php echo htmlspecialchars($siteName); ?> logo"
                    class="h-12 w-auto md:h-14 lg:h-[80px] shrink-0 object-contain transition-transform group-hover:scale-105"
                    decoding="async"
                    fetchpriority="high"
                >
                <!-- <div class="flex flex-col leading-none min-w-0">
                    <span class="font-serif text-xl md:text-2xl tracking-[0.24em] md:tracking-[0.38em] font-semibold whitespace-nowrap" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif;">SAPPHIRE</span>
                    <span class="font-sans text-[0.55rem] md:text-[0.65rem] tracking-[0.14em] md:tracking-[0.2em] font-semibold whitespace-nowrap" style="color: #C8A951; font-family: 'Montserrat', sans-serif;">EVENTS & DECORATIONS</span>
                </div> -->
            </a>

            <div class="md:hidden">
                <button class="text-2xl p-2 -mr-2" id="menu-toggle" type="button" aria-label="Toggle navigation menu" aria-expanded="false" aria-controls="mobile-menu">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>

        <div class="hidden md:grid md:grid-cols-[1fr_auto_1fr] md:items-center md:gap-6 lg:gap-10">
            <div class="flex items-center justify-end gap-6 lg:gap-8 min-w-0">
                <?php foreach ($desktopLeftNav as $item): ?>
                    <a href="<?php echo htmlspecialchars($item['href']); ?>" class="site-nav-link transition whitespace-nowrap" style="font-family: 'Montserrat', sans-serif; font-weight: 500; letter-spacing: 0.03em; font-size: 0.875rem; color: #0F3D3E;">
                        <?php echo htmlspecialchars($item['label']); ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <a href="<?php echo route('/'); ?>" class="flex items-center justify-center group min-w-0" translate="no" aria-label="<?php echo htmlspecialchars($siteName); ?>">
                <img
                    src="<?php echo htmlspecialchars($logoUrl); ?>"
                    alt="<?php echo htmlspecialchars($siteName); ?> logo"
                    class="h-16 w-auto lg:h-[80px] shrink-0 object-contain transition-transform group-hover:scale-105"
                    decoding="async"
                    fetchpriority="high"
                >
            </a>

            <div class="flex items-center justify-start gap-6 lg:gap-8 min-w-0">
                <?php foreach ($desktopRightNav as $item): ?>
                    <a href="<?php echo htmlspecialchars($item['href']); ?>" class="site-nav-link transition whitespace-nowrap" style="font-family: 'Montserrat', sans-serif; font-weight: 500; letter-spacing: 0.03em; font-size: 0.875rem; color: #0F3D3E;">
                        <?php echo htmlspecialchars($item['label']); ?>
                    </a>
                <?php endforeach; ?>

                <div class="relative group shrink-0">
                    <button class="flex items-center space-x-2 px-3 py-2 text-sm font-semibold rounded transition hover:bg-white/70" style="color: #0F3D3E; font-family: 'Montserrat', sans-serif;" type="button" aria-label="<?php echo htmlspecialchars(trans('pages.header.switch_language', 'Switch Language')); ?>">
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
        </div>

        <div id="mobile-menu" class="hidden md:hidden absolute left-0 right-0 top-full mt-2 z-50 rounded-xl border border-[#f0d7eb] bg-[#fff9fe] p-4 space-y-4 shadow-xl max-h-[75vh] overflow-y-auto">
            <a href="<?php echo route('/'); ?>" class="block site-nav-link" style="font-family: 'Montserrat', sans-serif; font-weight: 500;"><?php echo htmlspecialchars(trans('pages.header.home', 'Home')); ?></a>
            <a href="<?php echo route('/services'); ?>" class="block site-nav-link" style="font-family: 'Montserrat', sans-serif; font-weight: 500;"><?php echo htmlspecialchars(trans('pages.header.services', 'Services')); ?></a>
            <a href="<?php echo route('/packages'); ?>" class="block site-nav-link" style="font-family: 'Montserrat', sans-serif; font-weight: 500;"><?php echo htmlspecialchars(trans('pages.header.packages', 'Packages')); ?></a>
            <a href="<?php echo route('/gallery'); ?>" class="block site-nav-link" style="font-family: 'Montserrat', sans-serif; font-weight: 500;"><?php echo htmlspecialchars(trans('pages.header.gallery', 'Gallery')); ?></a>
            <a href="<?php echo route('/about'); ?>" class="block site-nav-link" style="font-family: 'Montserrat', sans-serif; font-weight: 500;"><?php echo htmlspecialchars(trans('pages.header.about', 'About')); ?></a>
            <a href="<?php echo route('/team'); ?>" class="block site-nav-link" style="font-family: 'Montserrat', sans-serif; font-weight: 500;"><?php echo htmlspecialchars(trans('pages.header.team', 'Team')); ?></a>
            <a href="<?php echo route('/faqs'); ?>" class="block site-nav-link" style="font-family: 'Montserrat', sans-serif; font-weight: 500;"><?php echo htmlspecialchars(trans('pages.header.faqs', 'FAQs')); ?></a>
            <a href="<?php echo route('/contact'); ?>" class="block site-nav-link" style="font-family: 'Montserrat', sans-serif; font-weight: 500;"><?php echo htmlspecialchars(trans('pages.header.contact', 'Contact')); ?></a>

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
<!-- The container for the widget -->
<div id="google_translate_element"></div>

<!-- The initialization script -->
<script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({
    pageLanguage: 'en', 
    layout: google.translate.TranslateElement.InlineLayout.SIMPLE
  }, 'google_translate_element');
}
</script>

<!-- The external Google script -->
<script type="text/javascript" src="//://translate.google.com"></script>
