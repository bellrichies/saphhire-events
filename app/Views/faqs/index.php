<?php
$title = "Frequently Asked Questions";
ob_start();

$groupedFaqs = [];
foreach (($faqs ?? []) as $item) {
    $category = $item['category'] ?? 'General';
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
                'text' => $faq['answer'] ?? ''
            ]
        ];
    }, $faqs ?? [])
];
?>

<section class="relative py-14 md:py-16 px-4 overflow-hidden" style="background: linear-gradient(135deg, #0F3D3E 0%, #1C1C1C 100%);">
    <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2260%22 height=%2260%22><circle cx=%2230%22 cy=%2230%22 r=%222%22 fill=%22%23C8A951%22/></svg>');"></div>
    <div class="absolute top-8 right-16 w-24 h-24 rounded-full opacity-15 faqs-float" style="background: radial-gradient(circle, #C8A951 0%, transparent 70%);"></div>

    <div class="max-w-5xl mx-auto text-center relative z-10" data-aos="fade-up">
        <span class="inline-block px-4 py-2 rounded-full mb-5 text-xs font-semibold tracking-widest uppercase" style="background-color: rgba(200, 169, 81, 0.2); color: #C8A951; font-family: 'Montserrat', sans-serif; letter-spacing: 0.2em;">
            Support Center
        </span>
        <h1 class="text-4xl md:text-5xl font-light mb-4 leading-tight text-white" style="font-family: 'Cormorant Garamond', serif; letter-spacing: -0.02em;">
            Frequently Asked Questions
        </h1>
        <p class="text-base md:text-lg text-gray-300 max-w-3xl mx-auto leading-relaxed" style="font-family: 'Montserrat', sans-serif;">
            Browse clear answers about planning, pricing, timelines, and event execution. If you need more guidance, our team is ready to help.
        </p>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 md:gap-4 max-w-2xl mx-auto mt-8" data-aos="fade-up" data-aos-delay="80">
            <div class="faqs-kpi-card">
                <p class="faqs-kpi-value"><?php echo (int)$faqCount; ?></p>
                <p class="faqs-kpi-label">Answered Questions</p>
            </div>
            <div class="faqs-kpi-card">
                <p class="faqs-kpi-value"><?php echo (int)$categoryCount; ?></p>
                <p class="faqs-kpi-label">Help Categories</p>
            </div>
            <div class="faqs-kpi-card col-span-2 md:col-span-1">
                <p class="faqs-kpi-value">24h</p>
                <p class="faqs-kpi-label">Typical Response Time</p>
            </div>
        </div>
    </div>
</section>

<section class="py-12 md:py-14 px-4" style="background-color: #F8F5F2;">
    <div class="site-container">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-7 lg:gap-8 items-start">
            <aside class="lg:col-span-4" data-aos="fade-right">
                <article class="faqs-panel lg:sticky lg:top-24">
                    <h2 class="text-2xl mb-4" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif; font-weight: 600;">
                        Find Answers Faster
                    </h2>
                    <p class="text-sm text-gray-600 mb-5">
                        Search by keyword or jump to a category. Questions are grouped to make browsing faster.
                    </p>

                    <div class="relative mb-5">
                        <input type="text" id="faqSearch" placeholder="Search questions or answers" class="faqs-search-input" aria-label="Search FAQs">
                        <i class="fas fa-search faqs-search-icon" aria-hidden="true"></i>
                    </div>

                    <div class="flex items-center gap-2 mb-4">
                        <button type="button" id="faqs-expand-all" class="faqs-action-chip">Expand all</button>
                        <button type="button" id="faqs-collapse-all" class="faqs-action-chip">Collapse all</button>
                    </div>

                    <div class="space-y-2" id="faq-category-nav">
                        <button type="button" class="faqs-category-btn is-active" data-category="all">All Categories</button>
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
                                    <h3 class="text-2xl md:text-3xl" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif; letter-spacing: -0.02em;">
                                        <?php echo htmlspecialchars($category); ?>
                                    </h3>
                                    <p class="text-xs font-semibold uppercase tracking-[0.14em]" style="color: #C8A951; font-family: 'Montserrat', sans-serif;">
                                        <?php echo count($items); ?> Questions
                                    </p>
                                </header>

                                <div class="space-y-3.5">
                                    <?php foreach ($items as $faq): ?>
                                        <?php
                                            $faqId = (int)($faq['id'] ?? 0);
                                            $question = $faq['question'] ?? '';
                                            $answer = $faq['answer'] ?? '';
                                            $searchBlob = strtolower($category . ' ' . $question . ' ' . $answer);
                                        ?>
                                        <article class="faq-item" data-search="<?php echo htmlspecialchars($searchBlob); ?>" data-category="<?php echo htmlspecialchars($category); ?>" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                                            <button class="faq-question" type="button" aria-expanded="false" aria-controls="faq-answer-<?php echo $faqId; ?>">
                                                <span><?php echo htmlspecialchars($question); ?></span>
                                                <i class="fas fa-chevron-down" aria-hidden="true"></i>
                                            </button>
                                            <div id="faq-answer-<?php echo $faqId; ?>" class="faq-answer hidden" role="region">
                                                <p><?php echo htmlspecialchars($answer); ?></p>
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
                        <h3 class="text-2xl mb-2" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif;">No FAQs Available</h3>
                        <p class="text-gray-600">Questions and answers will appear here once published.</p>
                    </article>
                <?php endif; ?>

                <div id="noResults" class="hidden faqs-panel text-center mt-6 py-10">
                    <i class="fas fa-search text-3xl mb-3" style="color: #C8A951;"></i>
                    <h3 class="text-2xl mb-2" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif;">No matching results</h3>
                    <p class="text-gray-600">Try a broader keyword or switch back to all categories.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-16 px-4 relative overflow-hidden" style="background: linear-gradient(135deg, #0F3D3E 0%, #1C1C1C 100%);">
    <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22100%22 height=%22100%22><circle cx=%2250%22 cy=%2250%22 r=%222%22 fill=%22%23C8A951%22/></svg>');"></div>
    <div class="max-w-3xl mx-auto text-center relative z-10" data-aos="fade-up">
        <h2 class="text-3xl md:text-4xl font-light mb-5 text-white" style="font-family: 'Cormorant Garamond', serif; letter-spacing: -0.02em;">
            Need More Help?
        </h2>
        <p class="text-gray-300 mb-8 max-w-2xl mx-auto">
            If your question is not covered here, our team can guide you directly based on your event requirements.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="<?php echo route('/contact'); ?>" class="inline-flex items-center justify-center px-8 py-3.5 rounded-lg font-semibold transition-all duration-300 hover:shadow-lg" style="background-color: #C8A951; color: #0F3D3E; font-family: 'Montserrat', sans-serif; letter-spacing: 0.08em; text-transform: uppercase; font-size: 0.82rem;">
                Contact Our Team
            </a>
            <a href="<?php echo route('/services'); ?>" class="inline-flex items-center justify-center px-8 py-3.5 rounded-lg font-semibold border border-white/40 text-white transition-all duration-300 hover:bg-white/10" style="font-family: 'Montserrat', sans-serif; letter-spacing: 0.08em; text-transform: uppercase; font-size: 0.82rem;">
                Explore Services
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
