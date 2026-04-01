<?php
$title = trans('content.faqs_page.page_title', 'Frequently Asked Questions');
ob_start();

$groupedFaqs = [];
foreach (($faqs ?? []) as $item) {
    $category = $item['category'] ?? trans('content.faqs_page.labels.default_category', 'General');
    if (!isset($groupedFaqs[$category])) {
        $groupedFaqs[$category] = [];
    }
    $groupedFaqs[$category][] = $item;
}

$faqCount = count($faqs ?? []);
$categoryCount = count($groupedFaqs);

$faqSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => array_map(static function ($faq) {
        return [
            '@type' => 'Question',
            'name' => $faq['question'] ?? '',
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => strip_tags((string) ($faq['answer'] ?? '')),
            ],
        ];
    }, $faqs ?? []),
];
?>

<section class="relative py-14 md:py-16 px-4 overflow-hidden" style="<?php echo innerHeroBackgroundStyle(); ?>">
    <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2260%22 height=%2260%22><circle cx=%2230%22 cy=%2230%22 r=%222%22 fill=%22%23C8A951%22/></svg>');"></div>
    <div class="absolute top-8 right-16 w-24 h-24 rounded-full opacity-15 faqs-float" style="background: radial-gradient(circle, var(--theme-accent) 0%, transparent 70%);"></div>

    <div class="max-w-5xl mx-auto text-center relative z-10" data-aos="fade-up">
        <span class="inline-block px-4 py-2 rounded-full mb-5 text-xs font-semibold tracking-widest uppercase" style="background-color: color-mix(in srgb, var(--theme-accent) 20%, transparent); color: var(--theme-accent); font-family: var(--font-ui); letter-spacing: 0.2em;">
            <?php echo htmlspecialchars(trans('content.faqs_page.hero.badge', 'Support Center')); ?>
        </span>
        <h1 class="text-4xl md:text-5xl font-light mb-4 leading-tight text-white" style="letter-spacing: -0.02em;">
            <?php echo htmlspecialchars(trans('content.faqs_page.hero.title', 'Frequently Asked Questions')); ?>
        </h1>
        <p class="text-base md:text-lg text-gray-300 max-w-3xl mx-auto leading-relaxed" style="font-family: var(--font-ui);">
            <?php echo htmlspecialchars(trans('content.faqs_page.hero.description', 'Find answers to common questions and learn about the step-by-step process for booking, decorating, and planning your special day with Sapphire Events.')); ?>
        </p>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 md:gap-4 max-w-2xl mx-auto mt-8" data-aos="fade-up" data-aos-delay="80">
            <div class="faqs-kpi-card">
                <p class="faqs-kpi-value"><?php echo (int) $faqCount; ?></p>
                <p class="faqs-kpi-label"><?php echo htmlspecialchars(trans('content.faqs_page.kpis.answered_questions', 'Answered Questions')); ?></p>
            </div>
            <div class="faqs-kpi-card">
                <p class="faqs-kpi-value"><?php echo (int) $categoryCount; ?></p>
                <p class="faqs-kpi-label"><?php echo htmlspecialchars(trans('content.faqs_page.kpis.help_categories', 'Help Categories')); ?></p>
            </div>
            <div class="faqs-kpi-card col-span-2 md:col-span-1">
                <p class="faqs-kpi-value">24h</p>
                <p class="faqs-kpi-label"><?php echo htmlspecialchars(trans('content.faqs_page.kpis.response_time', 'Typical Response Time')); ?></p>
            </div>
        </div>
    </div>
</section>

<section class="page-deferred-section py-12 md:py-14 px-4" style="background-color: #F8F5F2;">
    <div class="site-container">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-7 lg:gap-8 items-start">
            <aside class="lg:col-span-4" data-aos="fade-right">
                <article class="faqs-panel lg:sticky lg:top-24">
                    <h3 class="text-3xl mb-4" style="color: #0F3D3E; font-weight: 600;">
                        <?php echo htmlspecialchars(trans('content.faqs_page.sidebar.title', 'Find Answers Faster')); ?>
                    </h3>
                    <p class="text-sm text-gray-600 mb-5">
                        <?php echo htmlspecialchars(trans('content.faqs_page.sidebar.description', 'Search by keyword or jump to a category. Questions are grouped to make browsing faster.')); ?>
                    </p>

                    <div class="relative mb-5">
                        <input
                            type="text"
                            id="faqSearch"
                            placeholder="<?php echo htmlspecialchars(trans('content.faqs_page.sidebar.search_placeholder', 'Search questions or answers')); ?>"
                            class="faqs-search-input"
                            aria-label="<?php echo htmlspecialchars(trans('content.faqs_page.sidebar.search_aria', 'Search FAQs')); ?>"
                        >
                        <i class="fas fa-search faqs-search-icon" aria-hidden="true"></i>
                    </div>

                    <div class="flex items-center gap-2 mb-4">
                        <button type="button" id="faqs-expand-all" class="faqs-action-chip"><?php echo htmlspecialchars(trans('content.faqs_page.sidebar.expand_all', 'Expand all')); ?></button>
                        <button type="button" id="faqs-collapse-all" class="faqs-action-chip"><?php echo htmlspecialchars(trans('content.faqs_page.sidebar.collapse_all', 'Collapse all')); ?></button>
                    </div>

                    <div class="space-y-2" id="faq-category-nav">
                        <button type="button" class="faqs-category-btn is-active" data-category="all"><?php echo htmlspecialchars(trans('content.faqs_page.sidebar.all_categories', 'All Categories')); ?></button>
                        <?php foreach ($groupedFaqs as $category => $items): ?>
                            <button type="button" class="faqs-category-btn" data-category="<?php echo htmlspecialchars($category); ?>">
                                <?php echo htmlspecialchars($category); ?>
                                <span class="faqs-category-count"><?php echo count($items); ?></span>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </article>
            </aside>

            <div class="lg:col-span-8" data-aos="fade-left">
                <?php if (!empty($groupedFaqs)): ?>
                    <div id="faqContainer" class="space-y-7">
                        <?php $delay = 0; ?>
                        <?php foreach ($groupedFaqs as $category => $items): ?>
                            <section class="faq-group" data-category="<?php echo htmlspecialchars($category); ?>">
                                <header class="faqs-group-header">
                                    <h3 class="text-2xl md:text-3xl" style="color: #0F3D3E; letter-spacing: -0.02em;">
                                        <?php echo htmlspecialchars($category); ?>
                                    </h3>
                                    <p class="text-xs font-semibold uppercase tracking-[0.14em]" style="color: #C8A951; font-family: 'Montserrat', sans-serif;">
                                        <?php echo count($items); ?> <?php echo htmlspecialchars(trans('content.faqs_page.labels.questions', 'Questions')); ?>
                                    </p>
                                </header>

                                <div class="space-y-3.5">
                                    <?php foreach ($items as $faq): ?>
                                        <?php
                                        $faqId = (int) ($faq['id'] ?? 0);
                                        $question = (string) ($faq['question'] ?? '');
                                        $answer = (string) ($faq['answer'] ?? '');
                                        $searchBlob = strtolower(strip_tags($category . ' ' . $question . ' ' . $answer));
                                        ?>
                                        <article class="faq-item" data-search="<?php echo htmlspecialchars($searchBlob); ?>" data-category="<?php echo htmlspecialchars($category); ?>" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                                            <button class="faq-question" type="button" aria-expanded="false" aria-controls="faq-answer-<?php echo $faqId; ?>">
                                                <span><?php echo htmlspecialchars($question); ?></span>
                                                <i class="fas fa-chevron-down" aria-hidden="true"></i>
                                            </button>
                                            <div id="faq-answer-<?php echo $faqId; ?>" class="faq-answer hidden" role="region">
                                                <div><?php echo $answer; ?></div>
                                            </div>
                                        </article>
                                        <?php $delay = ($delay + 40) % 220; ?>
                                    <?php endforeach; ?>
                                </div>
                            </section>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <article class="faqs-panel text-center py-12">
                        <h3 class="text-2xl mb-2" style="color: #0F3D3E;"><?php echo htmlspecialchars(trans('content.faqs_page.empty.title', 'No FAQs Available')); ?></h3>
                        <p class="text-gray-600"><?php echo htmlspecialchars(trans('content.faqs_page.empty.description', 'Questions and answers will appear here once published.')); ?></p>
                    </article>
                <?php endif; ?>

                <div id="noResults" class="hidden faqs-panel text-center mt-6 py-10">
                    <i class="fas fa-search text-3xl mb-3" style="color: #C8A951;"></i>
                    <h3 class="text-2xl mb-2" style="color: #0F3D3E;"><?php echo htmlspecialchars(trans('content.faqs_page.no_results.title', 'No matching results')); ?></h3>
                    <p class="text-gray-600"><?php echo htmlspecialchars(trans('content.faqs_page.no_results.description', 'Try a broader keyword or switch back to all categories.')); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="page-deferred-section py-10 md:py-8 px-4 text-center" style="background: linear-gradient(135deg, #F8F5F2 0%, #ffffff 100%);">
    <div class="max-w-3xl mx-auto" data-aos="fade-up">
        <h2 class="text-2xl md:text-3xl font-semibold mb-3" style="font-family: 'Cormorant Garamond', serif; color: #0F3D3E; letter-spacing: -0.01em;">
            <?php echo htmlspecialchars(trans('content.faqs_page.cta.title', 'Need More Information?')); ?>
        </h2>
        <p class="text-sm md:text-base text-gray-600 mb-5 max-w-2xl mx-auto leading-relaxed">
            <?php echo htmlspecialchars(trans('content.faqs_page.cta.description', 'If you have any other questions or need additional assistance, do not hesitate to reach out to us directly. We are here to help make your event unforgettable.')); ?>
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="<?php echo route('/contact'); ?>" class="inline-flex items-center justify-center px-7 py-3 rounded-lg font-bold transition-all duration-300 hover:shadow-md w-full sm:w-auto" style="background-color: #C8A951; color: #0F3D3E; font-family: 'Montserrat', sans-serif; letter-spacing: 0.06em; text-transform: uppercase; font-size: 0.75rem; box-shadow: 0 2px 8px rgba(200, 169, 81, 0.25);">
                <?php echo htmlspecialchars(trans('content.faqs_page.cta.primary', 'Contact Our Team')); ?>
            </a>
            <a href="<?php echo route('/services'); ?>" class="inline-flex items-center justify-center px-7 py-3 rounded-lg font-bold border-2 transition-all duration-300 hover:shadow-md w-full sm:w-auto" style="border-color: #C8A951; color: #0F3D3E; font-family: 'Montserrat', sans-serif; letter-spacing: 0.06em; text-transform: uppercase; font-size: 0.75rem; background-color: transparent;">
                <?php echo htmlspecialchars(trans('content.faqs_page.cta.secondary', 'Explore Services')); ?>
            </a>
        </div>
    </div>
</section>

<script type="application/ld+json">
<?php echo json_encode($faqSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?>
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('faqSearch');
        const faqItems = Array.from(document.querySelectorAll('.faq-item'));
        const faqQuestions = Array.from(document.querySelectorAll('.faq-question'));
        const categoryButtons = Array.from(document.querySelectorAll('.faqs-category-btn'));
        const faqGroups = Array.from(document.querySelectorAll('.faq-group'));
        const noResults = document.getElementById('noResults');
        const expandAllBtn = document.getElementById('faqs-expand-all');
        const collapseAllBtn = document.getElementById('faqs-collapse-all');

        let activeCategory = 'all';

        function toggleItem(item, expanded) {
            const button = item.querySelector('.faq-question');
            const answer = item.querySelector('.faq-answer');
            const icon = button ? button.querySelector('i') : null;

            if (!button || !answer) {
                return;
            }

            button.setAttribute('aria-expanded', expanded ? 'true' : 'false');
            answer.classList.toggle('hidden', !expanded);
            if (icon) {
                icon.style.transform = expanded ? 'rotate(180deg)' : 'rotate(0deg)';
            }
        }

        function applyFilters() {
            const term = (searchInput.value || '').toLowerCase().trim();
            let visibleCount = 0;

            faqItems.forEach(function (item) {
                const haystack = item.getAttribute('data-search') || '';
                const itemCategory = item.getAttribute('data-category') || '';

                const matchCategory = activeCategory === 'all' || itemCategory === activeCategory;
                const matchSearch = term === '' || haystack.includes(term);
                const visible = matchCategory && matchSearch;

                item.classList.toggle('hidden', !visible);
                if (!visible) {
                    toggleItem(item, false);
                }
                if (visible) {
                    visibleCount += 1;
                }
            });

            faqGroups.forEach(function (group) {
                const hasVisibleItems = group.querySelector('.faq-item:not(.hidden)');
                group.classList.toggle('hidden', !hasVisibleItems);
            });

            noResults.classList.toggle('hidden', visibleCount > 0);
        }

        faqQuestions.forEach(function (button) {
            button.addEventListener('click', function () {
                const item = button.closest('.faq-item');
                const currentlyExpanded = button.getAttribute('aria-expanded') === 'true';
                const visibleItems = faqItems.filter(function (el) { return !el.classList.contains('hidden'); });

                visibleItems.forEach(function (el) {
                    toggleItem(el, false);
                });

                if (!currentlyExpanded && item && !item.classList.contains('hidden')) {
                    toggleItem(item, true);
                }
            });
        });

        categoryButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                activeCategory = button.getAttribute('data-category') || 'all';

                categoryButtons.forEach(function (btn) {
                    btn.classList.remove('is-active');
                });
                button.classList.add('is-active');

                applyFilters();
            });
        });

        if (searchInput) {
            searchInput.addEventListener('input', applyFilters);
        }

        if (expandAllBtn) {
            expandAllBtn.addEventListener('click', function () {
                faqItems.forEach(function (item) {
                    if (!item.classList.contains('hidden')) {
                        toggleItem(item, true);
                    }
                });
            });
        }

        if (collapseAllBtn) {
            collapseAllBtn.addEventListener('click', function () {
                faqItems.forEach(function (item) {
                    toggleItem(item, false);
                });
            });
        }

        applyFilters();
    });
</script>

<style>
    .page-deferred-section {
        content-visibility: auto;
        contain-intrinsic-size: 1px 980px;
    }

    .faqs-kpi-card {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.14);
        border-radius: 0.85rem;
        padding: 0.8rem;
    }

    .faqs-kpi-value {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.8rem;
        line-height: 1;
        color: #C8A951;
        margin-bottom: 0.1rem;
    }

    .faqs-kpi-label {
        font-size: 0.65rem;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        font-weight: 700;
        color: #E5E7EB;
        font-family: 'Montserrat', sans-serif;
    }

    .faqs-panel {
        background: #fff;
        border-radius: 1rem;
        border: 1px solid rgba(200, 169, 81, 0.12);
        box-shadow: 0 8px 30px rgba(15, 61, 62, 0.08);
        padding: 1.25rem;
    }

    .faqs-search-input {
        width: 100%;
        border-radius: 0.75rem;
        border: 1px solid rgba(15, 61, 62, 0.16);
        padding: 0.78rem 2.5rem 0.78rem 0.85rem;
        font-size: 0.93rem;
        background: #fff;
        color: #1F2937;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .faqs-search-input:focus {
        outline: none;
        border-color: #C8A951;
        box-shadow: 0 0 0 3px rgba(200, 169, 81, 0.16);
    }

    .faqs-search-icon {
        position: absolute;
        right: 0.9rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9CA3AF;
        font-size: 0.9rem;
    }

    .faqs-action-chip {
        border-radius: 9999px;
        border: 1px solid rgba(15, 61, 62, 0.18);
        padding: 0.34rem 0.7rem;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-weight: 700;
        color: #0F3D3E;
        transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease;
    }

    .faqs-action-chip:hover {
        background: #0F3D3E;
        border-color: #0F3D3E;
        color: #fff;
    }

    .faqs-category-btn {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.8rem;
        padding: 0.6rem 0.72rem;
        border: 1px solid rgba(15, 61, 62, 0.12);
        border-radius: 0.7rem;
        color: #0F3D3E;
        font-size: 0.82rem;
        font-weight: 600;
        text-align: left;
        transition: border-color 0.2s ease, background-color 0.2s ease, transform 0.2s ease;
    }

    .faqs-category-btn:hover {
        border-color: rgba(200, 169, 81, 0.7);
        background: rgba(200, 169, 81, 0.08);
        transform: translateY(-1px);
    }

    .faqs-category-btn.is-active {
        border-color: #C8A951;
        background: rgba(200, 169, 81, 0.14);
    }

    .faqs-category-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 1.5rem;
        height: 1.5rem;
        border-radius: 9999px;
        background: rgba(15, 61, 62, 0.12);
        color: #0F3D3E;
        font-size: 0.7rem;
        font-weight: 700;
    }

    .faqs-group-header {
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 0.9rem;
        padding-bottom: 0.65rem;
        border-bottom: 1px solid rgba(15, 61, 62, 0.12);
    }

    .faq-item {
        background: #fff;
        border: 1px solid rgba(15, 61, 62, 0.12);
        border-radius: 0.85rem;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(15, 61, 62, 0.06);
        transition: box-shadow 0.25s ease, border-color 0.25s ease, transform 0.25s ease;
    }

    .faq-item:hover {
        border-color: rgba(200, 169, 81, 0.65);
        box-shadow: 0 10px 26px rgba(15, 61, 62, 0.12);
        transform: translateY(-1px);
    }

    .faq-question {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.95rem;
        padding: 0.95rem 1rem;
        color: #0F3D3E;
        font-size: 0.97rem;
        font-weight: 700;
        text-align: left;
        line-height: 1.4;
    }

    .faq-question i {
        color: #C8A951;
        transition: transform 0.25s ease;
        flex-shrink: 0;
    }

    .faq-answer {
        padding: 0 1rem 1rem;
        color: #4B5563;
        font-size: 0.92rem;
        line-height: 1.6;
    }

    .faq-question[aria-expanded="true"] i {
        transform: rotate(180deg);
    }

    @keyframes faqs-float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-14px) rotate(3deg); }
    }

    .faqs-float {
        animation: faqs-float 6s ease-in-out infinite;
    }

    @media (max-width: 1024px) {
        .faqs-panel {
            padding: 1rem;
        }

        .faqs-group-header {
            align-items: flex-start;
            flex-direction: column;
            gap: 0.4rem;
        }
    }

    @media (max-width: 640px) {
        .faq-question {
            font-size: 0.92rem;
            padding: 0.82rem 0.85rem;
        }

        .faq-answer {
            padding: 0 0.85rem 0.85rem;
            font-size: 0.88rem;
        }

        .faqs-category-btn {
            font-size: 0.78rem;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .faq-item,
        .faqs-category-btn,
        .faqs-action-chip {
            transition: none;
        }

        .faqs-float {
            animation: none;
        }
    }
</style>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/app.php';
?>
