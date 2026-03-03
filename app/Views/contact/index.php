<?php
$title = trans('content.contact.page_title', 'Contact Us');
ob_start();
?>

<section class="relative py-14 md:py-16 px-4 overflow-hidden" style="background: linear-gradient(135deg, #0F3D3E 0%, #1C1C1C 100%);">
    <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2260%22 height=%2260%22><circle cx=%2230%22 cy=%2230%22 r=%222%22 fill=%22%23C8A951%22/></svg>');"></div>
    <div class="absolute top-8 right-16 w-24 h-24 rounded-full opacity-15 animate-float" style="background: radial-gradient(circle, #C8A951 0%, transparent 70%);"></div>

    <div class="max-w-5xl mx-auto text-center relative z-10" data-aos="fade-up">
        <span class="inline-block px-4 py-2 rounded-full mb-5 text-xs font-semibold tracking-widest uppercase" style="background-color: rgba(200, 169, 81, 0.2); color: #C8A951; font-family: 'Montserrat', sans-serif; letter-spacing: 0.2em;">
            <?php echo htmlspecialchars(trans('content.contact.hero.badge', 'Contact Sapphire Events')); ?>
        </span>
        <h1 class="text-4xl md:text-5xl font-light mb-4 leading-tight text-white" style="font-family: 'Cormorant Garamond', serif; letter-spacing: -0.02em;">
            <?php echo htmlspecialchars(trans('content.contact.hero.title', 'Start Planning With Clarity')); ?>
        </h1>
        <p class="text-base md:text-lg text-gray-300 max-w-3xl mx-auto leading-relaxed" style="font-family: 'Montserrat', sans-serif;">
            <?php echo htmlspecialchars(trans('content.contact.hero.description', 'Share your event details and goals. Our team will review your inquiry and send a tailored recommendation within one business day.')); ?>
        </p>
    </div>
</section>

<section class="py-12 md:py-14 px-4" style="background-color: #F8F5F2;">
    <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 md:gap-5">
        <article class="contact-info-card" data-aos="fade-up">
            <div class="contact-info-icon"><i class="fas fa-envelope"></i></div>
            <h3 class="contact-info-title"><?php echo htmlspecialchars(trans('content.contact.cards.email_title', 'Email')); ?></h3>
            <a href="mailto:Sapphireeventsglitz@gmail.com" class="contact-info-link">Sapphireeventsglitz@gmail.com</a>
            <p class="contact-info-note"><?php echo htmlspecialchars(trans('content.contact.cards.email_note', 'Response target: within 24 hours')); ?></p>
        </article>

        <article class="contact-info-card" data-aos="fade-up" data-aos-delay="80">
            <div class="contact-info-icon"><i class="fas fa-phone-alt"></i></div>
            <h3 class="contact-info-title"><?php echo htmlspecialchars(trans('content.contact.cards.phone_title', 'Phone')); ?></h3>
            <a href="tel:+3725160427" class="contact-info-link">+372-5160427</a>
            <p class="contact-info-note"><?php echo htmlspecialchars(trans('content.contact.cards.phone_note', 'Mon-Fri, 9:00-18:00 (EET)')); ?></p>
        </article>

        <article class="contact-info-card" data-aos="fade-up" data-aos-delay="160">
            <div class="contact-info-icon"><i class="fas fa-map-marker-alt"></i></div>
            <h3 class="contact-info-title"><?php echo htmlspecialchars(trans('content.contact.cards.office_title', 'Office')); ?></h3>
            <p class="contact-info-text"><?php echo nl2br(htmlspecialchars(trans('content.contact.cards.office_text', "Laki 14a, Room 503\n10621 Tallinn, Estonia"))); ?></p>
            <p class="contact-info-note"><?php echo htmlspecialchars(trans('content.contact.cards.office_note', 'Visits by appointment')); ?></p>
        </article>

        <article class="contact-info-card" data-aos="fade-up" data-aos-delay="240">
            <div class="contact-info-icon"><i class="fas fa-hashtag"></i></div>
            <h3 class="contact-info-title"><?php echo htmlspecialchars(trans('content.contact.cards.social_title', 'Social')); ?></h3>
            <div class="flex items-center justify-center gap-2.5 mb-2">
                <a href="https://instagram.com/sapphire_events_decorations" target="_blank" rel="noopener noreferrer" class="social-chip" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" class="social-chip" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="https://tiktok.com/@sapphire_events__" target="_blank" rel="noopener noreferrer" class="social-chip" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                <a href="https://wa.me/3725160427" target="_blank" rel="noopener noreferrer" class="social-chip" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
            </div>
            <p class="contact-info-note"><?php echo htmlspecialchars(trans('content.contact.cards.social_note', 'Daily updates and portfolio highlights')); ?></p>
        </article>
    </div>
</section>

<section class="py-16 md:py-20 px-4">
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-7 lg:gap-8 items-start">
        <div class="lg:col-span-7" data-aos="fade-right">
            <article class="contact-panel">
                <div class="mb-7">
                    <span class="inline-block px-4 py-2 rounded-full mb-4 text-xs font-semibold tracking-[0.16em] uppercase" style="background-color: rgba(15, 61, 62, 0.1); color: #C8A951; font-family: 'Montserrat', sans-serif;">
                        <?php echo htmlspecialchars(trans('content.contact.form.badge', 'Event Inquiry Form')); ?>
                    </span>
                    <h2 class="text-3xl md:text-4xl font-light mb-3" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif; letter-spacing: -0.02em;">
                        <?php echo htmlspecialchars(trans('content.contact.form.title', 'Tell Us About Your Event')); ?>
                    </h2>
                    <p class="text-gray-600 text-sm md:text-base">
                        <?php echo htmlspecialchars(trans('content.contact.form.description', 'The more context you provide, the faster we can prepare relevant service options, timeline guidance, and budget direction.')); ?>
                    </p>
                </div>

                <form id="contact-form" method="POST" action="<?php echo route('/contact'); ?>" enctype="multipart/form-data" class="space-y-5 md:space-y-6">
                    <?php echo \App\Core\CSRF::hidden(); ?>

                    <div>
                        <div>
                            <label class="contact-label" for="contact-name"><?php echo htmlspecialchars(trans('content.contact.form.full_name', 'Full Name')); ?></label>
                            <input id="contact-name" type="text" name="name" required class="contact-input" placeholder="<?php echo htmlspecialchars(trans('content.contact.form.full_name', 'Full Name')); ?>">
                            <small class="contact-error error-name"></small>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="contact-label" for="contact-email"><?php echo htmlspecialchars(trans('content.contact.form.email', 'Email Address')); ?></label>
                            <input id="contact-email" type="email" name="email" required class="contact-input" placeholder="john@example.com">
                            <small class="contact-error error-email"></small>
                        </div>
                        <div>
                            <label class="contact-label" for="contact-phone"><?php echo htmlspecialchars(trans('content.contact.form.phone', 'Phone Number')); ?></label>
                            <input id="contact-phone" type="tel" name="phone" required class="contact-input" placeholder="+372 512 34567">
                            <small class="contact-error error-phone"></small>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="contact-label" for="contact-service-type"><?php echo htmlspecialchars(trans('content.contact.form.service_type', 'Service Type')); ?></label>
                            <select id="contact-service-type" name="service_type" required class="contact-input contact-select">
                                <option value=""><?php echo htmlspecialchars(trans('content.contact.form.service_type_placeholder', 'Select service type')); ?></option>
                                <option value="Luxury Picnic Package">Luxury Picnic Package</option>
                                <option value="Proposal/Engagement Services">Proposal/Engagement Services</option>
                                <option value="Tablescapes">Tablescapes</option>
                                <option value="Event Decoration">Event Decoration</option>
                                <option value="Event Planning">Event Planning</option>
                                <option value="Rental Services">Rental Services</option>
                                <option value="Floral Design">Floral Design</option>
                                <option value="Other">Other</option>
                            </select>
                            <small class="contact-error error-service_type"></small>
                        </div>
                        <div>
                            <label class="contact-label" for="contact-event-type"><?php echo htmlspecialchars(trans('content.contact.form.event_type', 'Event Type')); ?></label>
                            <select id="contact-event-type" name="event_type" required class="contact-input contact-select">
                                <option value=""><?php echo htmlspecialchars(trans('content.contact.form.event_type_placeholder', 'Select event occasion')); ?></option>
                                <option value="Wedding Decoration"><?php echo htmlspecialchars(trans('content.contact.form.event_types.wedding_decoration', 'Wedding Decoration')); ?></option>
                                <option value="Corporate Event"><?php echo htmlspecialchars(trans('content.contact.form.event_types.corporate_event', 'Corporate Event')); ?></option>
                                <option value="Engagement/Proposal"><?php echo htmlspecialchars(trans('content.contact.form.event_types.engagement_proposal', 'Engagement/Proposal')); ?></option>
                                <option value="Event Decoration"><?php echo htmlspecialchars(trans('content.contact.form.event_types.event_decoration', 'Event Decoration')); ?></option>
                                <option value="Tablescape"><?php echo htmlspecialchars(trans('content.contact.form.event_types.tablescape', 'Tablescape')); ?></option>
                                <option value="Backdrop Installation"><?php echo htmlspecialchars(trans('content.contact.form.event_types.backdrop_installation', 'Backdrop Installation')); ?></option>
                                <option value="Floral Services"><?php echo htmlspecialchars(trans('content.contact.form.event_types.floral_services', 'Floral Services')); ?></option>
                                <option value="Rental Service"><?php echo htmlspecialchars(trans('content.contact.form.event_types.rental_service', 'Rental Service')); ?></option>
                                <option value="Other"><?php echo htmlspecialchars(trans('content.contact.form.event_types.other', 'Other')); ?></option>
                            </select>
                            <small class="contact-error error-event_type"></small>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="contact-label" for="contact-event-date"><?php echo htmlspecialchars(trans('content.contact.form.event_date', 'Date of the Event')); ?></label>
                            <input id="contact-event-date" type="text" name="event_date" required class="contact-input" placeholder="YYYY-MM-DD" autocomplete="off">
                            <small class="contact-error error-event_date"></small>
                        </div>
                        <div>
                            <label class="contact-label" for="contact-event-time"><?php echo htmlspecialchars(trans('content.contact.form.event_time', 'Event Time')); ?></label>
                            <input id="contact-event-time" type="text" name="event_time" required class="contact-input" placeholder="HH:MM" autocomplete="off">
                            <small class="contact-error error-event_time"></small>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="contact-label" for="contact-budget"><?php echo htmlspecialchars(trans('content.contact.form.budget', 'What Is Your Budget for the Event?')); ?></label>
                            <input id="contact-budget" type="text" name="budget" required class="contact-input" placeholder="$2,500">
                            <small class="contact-error error-budget"></small>
                        </div>
                        <div>
                            <label class="contact-label" for="contact-guest-count"><?php echo htmlspecialchars(trans('content.contact.form.guest_count', 'How Many Guests Will Attend?')); ?></label>
                            <input id="contact-guest-count" type="text" name="guest_count" required class="contact-input" placeholder="50">
                            <small class="contact-error error-guest_count"></small>
                        </div>
                    </div>

                    <div>
                        <div>
                            <label class="contact-label" for="contact-event-location"><?php echo htmlspecialchars(trans('content.contact.form.event_address', 'Event Address')); ?></label>
                            <input id="contact-event-location" type="text" name="event_location" required class="contact-input" placeholder="<?php echo htmlspecialchars(trans('content.contact.form.event_location_placeholder', 'Please share the full event address')); ?>">
                            <small class="contact-error error-event_location"></small>
                        </div>
                    </div>

                    <div>
                        <label class="contact-label" for="contact-inspiration-image"><?php echo htmlspecialchars(trans('content.contact.form.inspiration_image', 'Inspiration Image Upload')); ?></label>
                        <div id="contact-dropzone" class="contact-dropzone" tabindex="0" role="button" aria-label="Upload inspiration image">
                            <input id="contact-inspiration-image" type="file" name="inspiration_image" accept="image/jpeg,image/png,image/webp,image/avif" class="contact-file-input">
                            <div class="contact-dropzone-content">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p class="contact-dropzone-title"><?php echo htmlspecialchars(trans('content.contact.form.inspiration_upload_hint', 'Drag and drop an image here, or click to browse')); ?></p>
                                <p class="contact-dropzone-subtitle"><?php echo htmlspecialchars(trans('content.contact.form.inspiration_upload_types', 'JPG, PNG, WEBP, or AVIF up to 10MB')); ?></p>
                                <p id="contact-upload-filename" class="contact-dropzone-file hidden"></p>
                            </div>
                        </div>
                        <small class="contact-error error-inspiration_image"></small>
                    </div>

                    <div>
                        <div>
                            <label class="contact-label" for="contact-lead-source"><?php echo htmlspecialchars(trans('content.contact.form.lead_source', 'How Did You Hear About Us?')); ?></label>
                            <select id="contact-lead-source" name="lead_source" required class="contact-input contact-select">
                                <option value=""><?php echo htmlspecialchars(trans('content.contact.form.lead_source_placeholder', 'Select an option')); ?></option>
                                <option value="Instagram"><?php echo htmlspecialchars(trans('content.contact.form.lead_sources.instagram', 'Instagram')); ?></option>
                                <option value="Facebook"><?php echo htmlspecialchars(trans('content.contact.form.lead_sources.facebook', 'Facebook')); ?></option>
                                <option value="Google"><?php echo htmlspecialchars(trans('content.contact.form.lead_sources.google', 'Google')); ?></option>
                                <option value="TikTok"><?php echo htmlspecialchars(trans('content.contact.form.lead_sources.tiktok', 'TikTok')); ?></option>
                                <option value="Friend/Family"><?php echo htmlspecialchars(trans('content.contact.form.lead_sources.friend_family', 'Friend/Family')); ?></option>
                                <option value="Returning Client"><?php echo htmlspecialchars(trans('content.contact.form.lead_sources.returning_client', 'Returning Client')); ?></option>
                                <option value="Vendor Referral"><?php echo htmlspecialchars(trans('content.contact.form.lead_sources.vendor_referral', 'Vendor Referral')); ?></option>
                                <option value="Client Referral"><?php echo htmlspecialchars(trans('content.contact.form.lead_sources.client_referral', 'Client Referral')); ?></option>
                                <option value="Other"><?php echo htmlspecialchars(trans('content.contact.form.lead_sources.other', 'Other')); ?></option>
                            </select>
                            <small class="contact-error error-lead_source"></small>
                        </div>
                    </div>

                    <div>
                        <label class="contact-label" for="contact-message"><?php echo htmlspecialchars(trans('content.contact.form.message', 'Event Vision and Requirements')); ?></label>
                        <textarea id="contact-message" name="message" rows="5" required class="contact-input contact-textarea" placeholder="<?php echo htmlspecialchars(trans('content.contact.form.message_placeholder', 'Share color scheme, event layout, timing, and specific requirements.')); ?>"></textarea>
                        <small class="contact-error error-message"></small>
                    </div>

                    <div id="form-message" class="contact-form-message hidden"></div>

                    <button type="submit" id="submit-btn" class="contact-submit-btn">
                        <span><?php echo htmlspecialchars(trans('content.contact.form.submit', 'Send Inquiry')); ?></span>
                        <i class="fas fa-paper-plane ml-2"></i>
                    </button>
                </form>
            </article>
        </div>

        <div class="lg:col-span-5 space-y-6" data-aos="fade-left">
            <article class="contact-side-card">
                <h3 class="text-2xl mb-5" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif; font-weight: 600;">
                    <?php echo htmlspecialchars(trans('content.contact.side.next_title', 'What Happens Next')); ?>
                </h3>
                <div class="space-y-4">
                    <div class="contact-step-row">
                        <div class="contact-step-icon">1</div>
                        <div>
                            <h4 class="contact-step-title"><?php echo htmlspecialchars(trans('content.contact.side.next_step_1_title', 'Inquiry Review')); ?></h4>
                            <p class="contact-step-text"><?php echo htmlspecialchars(trans('content.contact.side.next_step_1_desc', 'We review your details and identify the right service path for your event type and timeline.')); ?></p>
                        </div>
                    </div>
                    <div class="contact-step-row">
                        <div class="contact-step-icon">2</div>
                        <div>
                            <h4 class="contact-step-title"><?php echo htmlspecialchars(trans('content.contact.side.next_step_2_title', 'Consultation')); ?></h4>
                            <p class="contact-step-text"><?php echo htmlspecialchars(trans('content.contact.side.next_step_2_desc', 'We schedule a quick call to align on expectations, budget, and creative direction.')); ?></p>
                        </div>
                    </div>
                    <div class="contact-step-row">
                        <div class="contact-step-icon">3</div>
                        <div>
                            <h4 class="contact-step-title"><?php echo htmlspecialchars(trans('content.contact.side.next_step_3_title', 'Custom Proposal')); ?></h4>
                            <p class="contact-step-text"><?php echo htmlspecialchars(trans('content.contact.side.next_step_3_desc', 'You receive a structured proposal with scope, inclusions, and next actions.')); ?></p>
                        </div>
                    </div>
                </div>
            </article>

            <article class="contact-side-card">
                <h3 class="text-2xl mb-5" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif; font-weight: 600;">
                    <?php echo htmlspecialchars(trans('content.contact.side.business_title', 'Business Details')); ?>
                </h3>
                <div class="space-y-3 text-sm">
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600"><?php echo htmlspecialchars(trans('content.contact.side.monday_friday', 'Monday-Friday')); ?></span>
                        <span class="font-semibold" style="color: #0F3D3E;">09:00-18:00</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600"><?php echo htmlspecialchars(trans('content.contact.side.saturday', 'Saturday')); ?></span>
                        <span class="font-semibold" style="color: #0F3D3E;">10:00-16:00</span>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <span class="text-gray-600"><?php echo htmlspecialchars(trans('content.contact.side.sunday', 'Sunday')); ?></span>
                        <span class="font-semibold text-gray-400"><?php echo htmlspecialchars(trans('content.contact.side.closed', 'Closed')); ?></span>
                    </div>
                </div>

                <div class="mt-6 rounded-xl p-4" style="background: linear-gradient(135deg, #0F3D3E 0%, #1C1C1C 100%); color: white;">
                    <p class="text-xs uppercase tracking-[0.14em] mb-2" style="color: #C8A951;"><?php echo htmlspecialchars(trans('content.contact.side.registered', 'Registered Business')); ?></p>
                    <p class="font-semibold">Sapphire Events & Decorations</p>
                    <p class="text-sm text-gray-300 mt-1">Registration Code: <span class="text-white font-semibold">16666563</span></p>
                </div>
            </article>

            <article class="contact-side-card">
                <h3 class="text-2xl mb-4" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif; font-weight: 600;">
                    <?php echo htmlspecialchars(trans('content.contact.side.instant_title', 'Prefer Instant Contact?')); ?>
                </h3>
                <p class="text-sm text-gray-600 mb-4"><?php echo htmlspecialchars(trans('content.contact.side.instant_desc', 'If your event is time-sensitive, reach us directly through WhatsApp or phone.')); ?></p>
                <div class="flex flex-col sm:flex-row lg:flex-col gap-3">
                    <a href="https://wa.me/3725160427" target="_blank" rel="noopener noreferrer" class="contact-quick-btn" style="background-color: #25D366; color: white; border-color: #25D366;">
                        <i class="fab fa-whatsapp mr-2"></i> <?php echo htmlspecialchars(trans('content.contact.side.whatsapp', 'WhatsApp Us')); ?>
                    </a>
                    <a href="tel:+3725160427" class="contact-quick-btn" style="background-color: transparent; color: #0F3D3E; border-color: rgba(15, 61, 62, 0.25);">
                        <i class="fas fa-phone-alt mr-2"></i> <?php echo htmlspecialchars(trans('content.contact.side.call_now', 'Call Now')); ?>
                    </a>
                </div>
            </article>
        </div>
    </div>
</section>

<section class="py-16 px-4" style="background-color: #F8F5F2;">
    <div class="max-w-7xl mx-auto grid grid-cols-1 xl:grid-cols-12 gap-7">
        <div class="xl:col-span-7" data-aos="fade-up">
            <article class="contact-panel p-0 overflow-hidden">
                <div class="p-6 md:p-7 border-b border-gray-100">
                    <h2 class="text-3xl md:text-4xl font-light mb-2" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif; letter-spacing: -0.02em;">
                        <?php echo htmlspecialchars(trans('content.contact.office.title', 'Visit Our Tallinn Office')); ?>
                    </h2>
                    <p class="text-gray-600 text-sm md:text-base">
                        <?php echo htmlspecialchars(trans('content.contact.office.description', 'Laki 14a, Room 503, 10621 Tallinn, Estonia. Visits are by appointment.')); ?>
                    </p>
                </div>
                <div class="relative h-[380px] md:h-[430px]">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2031.123456789!2d24.6565!3d59.3956!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNTnCsDIzJzQ0LjIiTiAyNMKwMzknMjMuNCJF!5e0!3m2!1sen!2see!4v1234567890"
                        width="100%"
                        height="100%"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        class="absolute inset-0"
                        title="Sapphire Events location map">
                    </iframe>
                </div>
            </article>
        </div>

        <div class="xl:col-span-5" data-aos="fade-up" data-aos-delay="80">
            <article class="contact-panel h-full">
                <h3 class="text-2xl mb-5" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif; font-weight: 600;">
                    <?php echo htmlspecialchars(trans('content.contact.faq.title', 'Common Questions')); ?>
                </h3>
                <div class="space-y-3" id="faq-accordion">
                    <div class="faq-item">
                        <button class="faq-toggle" type="button" aria-expanded="false">
                            <span><?php echo htmlspecialchars(trans('content.contact.faq.q1', 'How early should we book?')); ?></span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-content hidden">
                            <?php echo htmlspecialchars(trans('content.contact.faq.a1', 'For weddings and large productions, 3-6 months in advance is ideal. Smaller events can often be handled with shorter lead times.')); ?>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-toggle" type="button" aria-expanded="false">
                            <span><?php echo htmlspecialchars(trans('content.contact.faq.q2', 'Do you provide custom packages?')); ?></span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-content hidden">
                            <?php echo htmlspecialchars(trans('content.contact.faq.a2', 'Yes. We combine services based on your scope, venue, audience, and design requirements to build a tailored package.')); ?>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-toggle" type="button" aria-expanded="false">
                            <span><?php echo htmlspecialchars(trans('content.contact.faq.q3', 'What locations do you serve?')); ?></span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-content hidden">
                            <?php echo htmlspecialchars(trans('content.contact.faq.a3', 'We are based in Tallinn and support events across Estonia. Destination events can also be arranged based on logistics.')); ?>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-100">
                    <p class="text-sm text-gray-600 mb-3"><?php echo htmlspecialchars(trans('content.contact.faq.need_more', 'Need more details?')); ?></p>
                    <a href="mailto:Sapphireeventsglitz@gmail.com" class="inline-flex items-center text-sm font-semibold" style="color: #0F3D3E; letter-spacing: 0.05em; text-transform: uppercase;">
                        <?php echo htmlspecialchars(trans('content.contact.faq.email_team', 'Email Our Team')); ?> <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </article>
        </div>
    </div>
</section>

<section class="py-16 px-4 relative overflow-hidden" style="background: linear-gradient(135deg, #0F3D3E 0%, #1C1C1C 100%);">
    <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22100%22 height=%22100%22><circle cx=%2250%22 cy=%2250%22 r=%222%22 fill=%22%23C8A951%22/></svg>');"></div>
    <div class="max-w-3xl mx-auto text-center relative z-10" data-aos="fade-up">
        <h2 class="text-3xl md:text-4xl font-light mb-5 text-white" style="font-family: 'Cormorant Garamond', serif; letter-spacing: -0.02em;">
            <?php echo htmlspecialchars(trans('content.contact.cta.title', 'Ready to Move Forward?')); ?>
        </h2>
        <p class="text-gray-300 mb-8 max-w-2xl mx-auto">
            <?php echo htmlspecialchars(trans('content.contact.cta.description', 'Send your inquiry now and we will help you translate ideas into a clear event plan.')); ?>
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="https://wa.me/3725160427" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center px-8 py-3.5 rounded-lg font-semibold transition-all duration-300 hover:shadow-lg" style="background-color: #25D366; color: white; font-family: 'Montserrat', sans-serif; letter-spacing: 0.08em; text-transform: uppercase; font-size: 0.82rem;">
                <?php echo htmlspecialchars(trans('content.contact.cta.primary', 'Chat on WhatsApp')); ?>
            </a>
            <a href="tel:+3725160427" class="inline-flex items-center justify-center px-8 py-3.5 rounded-lg font-semibold border border-white/40 text-white transition-all duration-300 hover:bg-white/10" style="font-family: 'Montserrat', sans-serif; letter-spacing: 0.08em; text-transform: uppercase; font-size: 0.82rem;">
                <?php echo htmlspecialchars(trans('content.contact.cta.secondary', 'Call Our Team')); ?>
            </a>
        </div>
    </div>
</section>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('contact-form');
        const submitBtn = document.getElementById('submit-btn');
        const messageDiv = document.getElementById('form-message');
        const faqButtons = document.querySelectorAll('.faq-toggle');
        const eventDateInput = document.getElementById('contact-event-date');
        const eventTimeInput = document.getElementById('contact-event-time');
        const dropzone = document.getElementById('contact-dropzone');
        const inspirationInput = document.getElementById('contact-inspiration-image');
        const uploadFilename = document.getElementById('contact-upload-filename');

        if (typeof flatpickr === 'function' && eventDateInput) {
            flatpickr(eventDateInput, {
                dateFormat: 'Y-m-d',
                minDate: 'today',
                disableMobile: true
            });
        } else if (eventDateInput) {
            const today = new Date().toISOString().split('T')[0];
            eventDateInput.setAttribute('min', today);
        }

        if (typeof flatpickr === 'function' && eventTimeInput) {
            flatpickr(eventTimeInput, {
                enableTime: true,
                noCalendar: true,
                dateFormat: 'H:i',
                time_24hr: true,
                minuteIncrement: 5,
                disableMobile: true
            });
        }

        const setUploadFile = function (file) {
            if (!inspirationInput || !uploadFilename) {
                return;
            }

            if (!file) {
                inspirationInput.value = '';
                uploadFilename.textContent = '';
                uploadFilename.classList.add('hidden');
                if (dropzone) {
                    dropzone.classList.remove('is-selected');
                }
                return;
            }

            const transfer = new DataTransfer();
            transfer.items.add(file);
            inspirationInput.files = transfer.files;
            uploadFilename.textContent = file.name + ' (' + Math.max(1, Math.round(file.size / 1024)) + ' KB)';
            uploadFilename.classList.remove('hidden');
            if (dropzone) {
                dropzone.classList.add('is-selected');
            }
        };

        if (dropzone && inspirationInput) {
            dropzone.addEventListener('click', function () {
                inspirationInput.click();
            });

            dropzone.addEventListener('keydown', function (event) {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    inspirationInput.click();
                }
            });

            inspirationInput.addEventListener('change', function () {
                const selectedFile = inspirationInput.files && inspirationInput.files.length ? inspirationInput.files[0] : null;
                setUploadFile(selectedFile);
            });

            ['dragenter', 'dragover'].forEach(function (eventName) {
                dropzone.addEventListener(eventName, function (event) {
                    event.preventDefault();
                    event.stopPropagation();
                    dropzone.classList.add('is-dragover');
                });
            });

            ['dragleave', 'drop'].forEach(function (eventName) {
                dropzone.addEventListener(eventName, function (event) {
                    event.preventDefault();
                    event.stopPropagation();
                    dropzone.classList.remove('is-dragover');
                });
            });

            dropzone.addEventListener('drop', function (event) {
                const droppedFiles = event.dataTransfer && event.dataTransfer.files ? event.dataTransfer.files : null;
                const droppedFile = droppedFiles && droppedFiles.length ? droppedFiles[0] : null;
                setUploadFile(droppedFile);
            });
        }

        if (form) {
            form.addEventListener('submit', async function (e) {
                e.preventDefault();

                const originalBtnHtml = submitBtn.innerHTML;

                document.querySelectorAll('.contact-error').forEach(function (el) {
                    el.textContent = '';
                });

                messageDiv.className = 'contact-form-message hidden';
                messageDiv.innerHTML = '';

                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span class="ml-2"><?php echo htmlspecialchars(trans('content.contact.form.sending', 'Sending...')); ?></span>';

                const formData = new FormData(form);

                try {
                    const response = await fetch('<?php echo route('/contact'); ?>', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    });

                    const contentType = response.headers.get('content-type') || '';
                    let data = {};

                    if (contentType.includes('application/json')) {
                        data = await response.json();
                    } else {
                        throw { error: 'Unexpected server response. Please reload and try again.' };
                    }

                    if (response.ok && data.success) {
                        messageDiv.className = 'contact-form-message contact-form-success';
                        messageDiv.innerHTML = '<i class="fas fa-check-circle"></i><span>' + data.message + '</span>';
                        form.reset();
                        setUploadFile(null);
                        messageDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    } else {
                        throw data;
                    }
                } catch (error) {
                    messageDiv.className = 'contact-form-message contact-form-error';

                    if (error.errors) {
                        Object.keys(error.errors).forEach(function (field) {
                            const errorEl = document.querySelector('.error-' + field);
                            if (errorEl) {
                                errorEl.textContent = error.errors[field];
                            }
                        });
                        messageDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i><span>Please fix the highlighted fields and try again.</span>';
                    } else {
                        messageDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i><span>' + (error.error || 'Something went wrong. Please try again.') + '</span>';
                    }
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnHtml;
                }
            });
        }

        faqButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                const content = button.nextElementSibling;
                const icon = button.querySelector('i');
                const isClosed = content.classList.contains('hidden');

                faqButtons.forEach(function (otherBtn) {
                    const otherContent = otherBtn.nextElementSibling;
                    const otherIcon = otherBtn.querySelector('i');
                    otherContent.classList.add('hidden');
                    otherBtn.setAttribute('aria-expanded', 'false');
                    if (otherIcon) {
                        otherIcon.style.transform = 'rotate(0deg)';
                    }
                });

                if (isClosed) {
                    content.classList.remove('hidden');
                    button.setAttribute('aria-expanded', 'true');
                    if (icon) {
                        icon.style.transform = 'rotate(180deg)';
                    }
                }
            });
        });
    });
</script>

<style>
    .contact-info-card {
        background: #fff;
        border-radius: 1rem;
        padding: 1.25rem;
        border: 1px solid rgba(200, 169, 81, 0.12);
        box-shadow: 0 6px 22px rgba(15, 61, 62, 0.07);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        text-align: center;
    }

    .contact-info-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 34px rgba(15, 61, 62, 0.14);
    }

    .contact-info-icon {
        width: 3rem;
        height: 3rem;
        border-radius: 9999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.8rem;
        color: #C8A951;
        background: linear-gradient(135deg, rgba(15, 61, 62, 0.12), rgba(200, 169, 81, 0.2));
    }

    .contact-info-title {
        color: #0F3D3E;
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.35rem;
        font-weight: 600;
        margin-bottom: 0.2rem;
    }

    .contact-info-link {
        font-size: 0.9rem;
        color: #4B5563;
        transition: color 0.2s ease;
        word-break: break-word;
    }

    .contact-info-link:hover {
        color: #C8A951;
    }

    .contact-info-text {
        font-size: 0.9rem;
        color: #4B5563;
    }

    .contact-info-note {
        margin-top: 0.35rem;
        font-size: 0.75rem;
        color: #9CA3AF;
    }

    .social-chip {
        width: 2rem;
        height: 2rem;
        border-radius: 9999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #0F3D3E;
        background: rgba(15, 61, 62, 0.08);
        transition: transform 0.2s ease, background-color 0.2s ease, color 0.2s ease;
    }

    .social-chip:hover {
        transform: translateY(-2px);
        background: #0F3D3E;
        color: #fff;
    }

    .contact-panel {
        background: #fff;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 8px 30px rgba(15, 61, 62, 0.08);
        border: 1px solid rgba(200, 169, 81, 0.12);
    }

    .contact-label {
        display: block;
        margin-bottom: 0.45rem;
        font-size: 0.8rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #0F3D3E;
        font-family: 'Montserrat', sans-serif;
    }

    .contact-input {
        width: 100%;
        border-radius: 0.75rem;
        border: 1px solid rgba(15, 61, 62, 0.16);
        background: #fff;
        color: #1F2937;
        padding: 0.75rem 0.85rem;
        font-size: 0.95rem;
        transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
    }

    .contact-input:focus {
        outline: none;
        border-color: #C8A951;
        box-shadow: 0 0 0 3px rgba(200, 169, 81, 0.18);
        background-color: #fff;
    }

    .contact-select {
        appearance: none;
        cursor: pointer;
        background-image: linear-gradient(45deg, transparent 50%, #6B7280 50%), linear-gradient(135deg, #6B7280 50%, transparent 50%);
        background-position: calc(100% - 16px) calc(50% - 3px), calc(100% - 10px) calc(50% - 3px);
        background-size: 6px 6px, 6px 6px;
        background-repeat: no-repeat;
    }

    .contact-textarea {
        min-height: 130px;
        resize: vertical;
    }

    .contact-file-input {
        display: none;
    }

    .contact-dropzone {
        position: relative;
        border-radius: 0.85rem;
        border: 1px dashed rgba(15, 61, 62, 0.28);
        background: #FBFBFA;
        padding: 1.2rem 1rem;
        cursor: pointer;
        transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
    }

    .contact-dropzone:focus-visible {
        outline: none;
        border-color: #C8A951;
        box-shadow: 0 0 0 3px rgba(200, 169, 81, 0.18);
    }

    .contact-dropzone.is-dragover,
    .contact-dropzone.is-selected {
        border-color: #C8A951;
        background: #FFFBF2;
        box-shadow: 0 0 0 3px rgba(200, 169, 81, 0.14);
    }

    .contact-dropzone-content {
        text-align: center;
    }

    .contact-dropzone-content i {
        color: #0F3D3E;
        font-size: 1.5rem;
        margin-bottom: 0.45rem;
    }

    .contact-dropzone-title {
        color: #0F3D3E;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 0.2rem;
    }

    .contact-dropzone-subtitle {
        color: #6B7280;
        font-size: 0.76rem;
        margin-bottom: 0.2rem;
    }

    .contact-dropzone-file {
        color: #0F3D3E;
        font-size: 0.78rem;
        font-weight: 600;
        margin-top: 0.25rem;
    }

    .contact-error {
        display: block;
        min-height: 1rem;
        margin-top: 0.3rem;
        color: #DC2626;
        font-size: 0.75rem;
    }

    .contact-submit-btn {
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.75rem;
        border: 1px solid transparent;
        background: linear-gradient(135deg, #0F3D3E 0%, #1C1C1C 100%);
        color: #fff;
        padding: 0.85rem 1rem;
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        letter-spacing: 0.07em;
        text-transform: uppercase;
        font-size: 0.82rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease, opacity 0.2s ease;
    }

    .contact-submit-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 12px 26px rgba(15, 61, 62, 0.22);
    }

    .contact-submit-btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    .contact-form-message {
        border-radius: 0.75rem;
        padding: 0.75rem 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.55rem;
        font-size: 0.9rem;
    }

    .contact-form-success {
        background: #DCFCE7;
        color: #166534;
        border: 1px solid #86EFAC;
    }

    .contact-form-error {
        background: #FEE2E2;
        color: #B91C1C;
        border: 1px solid #FCA5A5;
    }

    .contact-side-card {
        background: #fff;
        border-radius: 1rem;
        padding: 1.35rem;
        box-shadow: 0 8px 30px rgba(15, 61, 62, 0.08);
        border: 1px solid rgba(200, 169, 81, 0.12);
    }

    .contact-step-row {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .contact-step-icon {
        width: 1.95rem;
        height: 1.95rem;
        border-radius: 0.6rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 0.8rem;
        font-weight: 700;
        color: #fff;
        background: linear-gradient(135deg, #0F3D3E 0%, #C8A951 100%);
    }

    .contact-step-title {
        color: #0F3D3E;
        font-size: 0.95rem;
        font-weight: 700;
        margin-bottom: 0.2rem;
    }

    .contact-step-text {
        color: #6B7280;
        font-size: 0.83rem;
        line-height: 1.45;
    }

    .contact-quick-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid;
        border-radius: 0.75rem;
        padding: 0.72rem 0.9rem;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .contact-quick-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 22px rgba(15, 61, 62, 0.14);
    }

    .faq-item {
        border: 1px solid rgba(15, 61, 62, 0.1);
        border-radius: 0.75rem;
        overflow: hidden;
        background: #fff;
    }

    .faq-toggle {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 0.85rem 0.95rem;
        color: #0F3D3E;
        font-weight: 600;
        text-align: left;
        transition: background-color 0.2s ease;
    }

    .faq-toggle:hover {
        background: #F9FAFB;
    }

    .faq-toggle i {
        color: #C8A951;
        transition: transform 0.25s ease;
    }

    .faq-content {
        padding: 0 0.95rem 0.95rem;
        color: #6B7280;
        font-size: 0.9rem;
        line-height: 1.55;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-14px) rotate(3deg); }
    }

    .animate-float {
        animation: float 6s ease-in-out infinite;
    }

    @media (max-width: 1024px) {
        .contact-panel,
        .contact-side-card {
            padding: 1.2rem;
        }
    }

    @media (max-width: 640px) {
        .contact-panel,
        .contact-side-card {
            border-radius: 0.9rem;
        }

        .contact-info-card {
            padding: 1rem;
        }

        .contact-submit-btn {
            letter-spacing: 0.05em;
            font-size: 0.78rem;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .contact-info-card,
        .contact-submit-btn,
        .contact-quick-btn,
        .social-chip {
            transition: none;
        }

        .animate-float {
            animation: none;
        }
    }
</style>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/app.php';
?>
