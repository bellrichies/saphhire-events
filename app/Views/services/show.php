<?php
$title = htmlspecialchars($service['title'] ?? 'Service');
ob_start();
?>

<!-- Service Detail Hero -->
<section class="relative py-20 px-4 overflow-hidden" style="background: linear-gradient(135deg, #0F3D3E 0%, #1C1C1C 100%);">
    <!-- Animated Background Pattern -->
    <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2260%22 height=%2260%22><circle cx=%2230%22 cy=%2230%22 r=%222%22 fill=%22%23C8A951%22/></svg>');"></div>
    
    <!-- Floating Elements -->
    <div class="absolute top-10 right-10 w-40 h-40 rounded-full opacity-10 animate-float" style="background: radial-gradient(circle, #C8A951 0%, transparent 70%);"></div>
    <div class="absolute bottom-10 left-10 w-32 h-32 rounded-full opacity-15 animate-float-delayed" style="background: radial-gradient(circle, #ffffff 0%, transparent 70%);"></div>
    
    <div class="site-container relative z-10">
        <!-- Breadcrumb -->
        <nav class="mb-10" data-aos="fade-up">
            <ol class="flex items-center gap-2 text-sm text-gray-400">
                <li><a href="<?php echo route('/'); ?>" class="hover:text-white transition-colors">Home</a></li>
                <li><i class="fas fa-chevron-right text-xs" style="color: #C8A951;"></i></li>
                <li><a href="<?php echo route('/services'); ?>" class="hover:text-white transition-colors">Services</a></li>
                <li><i class="fas fa-chevron-right text-xs" style="color: #C8A951;"></i></li>
                <li class="text-white font-medium"><?php echo htmlspecialchars($service['title']); ?></li>
            </ol>
        </nav>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div data-aos="fade-right">
                <span class="inline-block px-4 py-2 rounded-full mb-6 text-xs font-semibold tracking-widest uppercase" style="background-color: rgba(200, 169, 81, 0.2); color: #C8A951; font-family: 'Montserrat', sans-serif; letter-spacing: 0.2em;">
                    Professional Service
                </span>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-light mb-6 text-white leading-tight" style="font-family: 'Dancing Script', cursive; letter-spacing: -0.02em;">
                    <?php echo htmlspecialchars($service['title']); ?>
                </h1>
                <p class="text-lg text-gray-300 mb-8 leading-relaxed max-w-xl" style="white-space: pre-wrap;"><?php echo nl2br(htmlspecialchars(trim((string)$service['description']))); ?></p>
                <div class="flex flex-wrap gap-4">
                    <a href="<?php echo route('/contact'); ?>" class="group px-8 py-4 rounded-lg font-semibold transition-all duration-300 hover:shadow-2xl" style="background-color: #C8A951; color: #0F3D3E; font-family: 'Montserrat', sans-serif; letter-spacing: 0.1em; text-transform: uppercase; font-size: 0.85rem;">
                        Get Quote
                        <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    <a href="<?php echo route('/services'); ?>" class="px-8 py-4 rounded-lg font-semibold border-2 border-white/30 text-white transition-all duration-300 hover:bg-white/10" style="font-family: 'Montserrat', sans-serif; letter-spacing: 0.1em; text-transform: uppercase; font-size: 0.85rem;">
                        All Services
                    </a>
                </div>
            </div>
            
            <div class="relative" data-aos="fade-left">
                <div class="relative rounded-2xl overflow-hidden h-80 lg:h-[450px]" style="box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);">
                    <?php if (!empty($service['image'])): ?>
                        <img src="<?php echo htmlspecialchars(uploadedImageUrl($service['image'])); ?>" 
                             alt="<?php echo htmlspecialchars($service['title']); ?>"
                             class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center" style="background: linear-gradient(135deg, #0F3D3E 0%, #2d5a5b 100%);">
                            <i class="fas fa-star text-white text-8xl opacity-20"></i>
                        </div>
                    <?php endif; ?>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                </div>
                <!-- Decorative Element -->
                <div class="absolute -bottom-4 -right-4 w-24 h-24 rounded-xl -z-10" style="background: linear-gradient(135deg, #C8A951 0%, #0F3D3E 100%); opacity: 0.5;"></div>
            </div>
        </div>
    </div>
</section>

<!-- Key Features Section -->
<section class="py-16 px-4" style="background-color: #F8F5F2;">
    <div class="site-container">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="group bg-white rounded-2xl p-8 transition-all duration-300 hover:shadow-xl hover:-translate-y-1" data-aos="fade-up" style="box-shadow: 0 4px 20px rgba(15, 61, 62, 0.08);">
                <div class="w-14 h-14 rounded-xl flex items-center justify-center mb-6 transition-transform duration-300 group-hover:scale-110" style="background: linear-gradient(135deg, rgba(15, 61, 62, 0.1), rgba(200, 169, 81, 0.1));">
                    <i class="fas fa-check-circle text-2xl" style="color: #C8A951;"></i>
                </div>
                <h3 class="text-xl font-bold mb-3" style="color: #0F3D3E; font-family: 'Dancing Script', cursive;">Professional Planning</h3>
                <p class="text-gray-600 text-sm leading-relaxed">Experienced team dedicated to your event success with meticulous attention to detail.</p>
            </div>

            <div class="group bg-white rounded-2xl p-8 transition-all duration-300 hover:shadow-xl hover:-translate-y-1" data-aos="fade-up" data-aos-delay="100" style="box-shadow: 0 4px 20px rgba(15, 61, 62, 0.08);">
                <div class="w-14 h-14 rounded-xl flex items-center justify-center mb-6 transition-transform duration-300 group-hover:scale-110" style="background: linear-gradient(135deg, rgba(15, 61, 62, 0.1), rgba(200, 169, 81, 0.1));">
                    <i class="fas fa-star text-2xl" style="color: #C8A951;"></i>
                </div>
                <h3 class="text-xl font-bold mb-3" style="color: #0F3D3E; font-family: 'Dancing Script', cursive;">Premium Service</h3>
                <p class="text-gray-600 text-sm leading-relaxed">Attention to every detail for a perfect event that exceeds your expectations.</p>
            </div>

            <div class="group bg-white rounded-2xl p-8 transition-all duration-300 hover:shadow-xl hover:-translate-y-1" data-aos="fade-up" data-aos-delay="200" style="box-shadow: 0 4px 20px rgba(15, 61, 62, 0.08);">
                <div class="w-14 h-14 rounded-xl flex items-center justify-center mb-6 transition-transform duration-300 group-hover:scale-110" style="background: linear-gradient(135deg, rgba(15, 61, 62, 0.1), rgba(200, 169, 81, 0.1));">
                    <i class="fas fa-award text-2xl" style="color: #C8A951;"></i>
                </div>
                <h3 class="text-xl font-bold mb-3" style="color: #0F3D3E; font-family: 'Dancing Script', cursive;">Award Winning</h3>
                <p class="text-gray-600 text-sm leading-relaxed">Recognized for excellence in event design and customer satisfaction.</p>
            </div>
        </div>
    </div>
</section>

<!-- What's Included Section -->
<section class="py-20 px-4">
    <div class="site-container">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div data-aos="fade-right">
                <span class="inline-block px-4 py-2 rounded-full mb-6 text-xs font-semibold tracking-widest uppercase" style="background-color: rgba(200, 169, 81, 0.2); color: #C8A951; font-family: 'Montserrat', sans-serif; letter-spacing: 0.2em;">
                    What's Included
                </span>
                <h2 class="text-3xl md:text-4xl font-light mb-8" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif; letter-spacing: -0.02em;">
                    Comprehensive Service<br><span class="italic" style="color: #C8A951;">Package</span>
                </h2>
                
                <div class="space-y-6">
                    <div class="flex items-start gap-5 group">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 transition-transform duration-300 group-hover:scale-110" style="background: linear-gradient(135deg, rgba(15, 61, 62, 0.1), rgba(200, 169, 81, 0.1));">
                            <i class="fas fa-map-marker-alt" style="color: #C8A951;"></i>
                        </div>
                        <div>
                            <h4 class="font-bold mb-2 text-lg" style="color: #0F3D3E; font-family: 'Montserrat', sans-serif;">Venue Selection & Booking</h4>
                            <p class="text-gray-600 text-sm leading-relaxed">Help finding and securing the perfect venue that matches your vision and requirements.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-5 group">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 transition-transform duration-300 group-hover:scale-110" style="background: linear-gradient(135deg, rgba(15, 61, 62, 0.1), rgba(200, 169, 81, 0.1));">
                            <i class="fas fa-paint-brush" style="color: #C8A951;"></i>
                        </div>
                        <div>
                            <h4 class="font-bold mb-2 text-lg" style="color: #0F3D3E; font-family: 'Montserrat', sans-serif;">Decoration Design</h4>
                            <p class="text-gray-600 text-sm leading-relaxed">Custom decorations tailored to your theme, creating a stunning visual experience.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-5 group">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 transition-transform duration-300 group-hover:scale-110" style="background: linear-gradient(135deg, rgba(15, 61, 62, 0.1), rgba(200, 169, 81, 0.1));">
                            <i class="fas fa-users" style="color: #C8A951;"></i>
                        </div>
                        <div>
                            <h4 class="font-bold mb-2 text-lg" style="color: #0F3D3E; font-family: 'Montserrat', sans-serif;">Vendor Coordination</h4>
                            <p class="text-gray-600 text-sm leading-relaxed">Management of all event vendors and suppliers ensuring seamless collaboration.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-5 group">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 transition-transform duration-300 group-hover:scale-110" style="background: linear-gradient(135deg, rgba(15, 61, 62, 0.1), rgba(200, 169, 81, 0.1));">
                            <i class="fas fa-calendar-check" style="color: #C8A951;"></i>
                        </div>
                        <div>
                            <h4 class="font-bold mb-2 text-lg" style="color: #0F3D3E; font-family: 'Montserrat', sans-serif;">Day-of Coordination</h4>
                            <p class="text-gray-600 text-sm leading-relaxed">Full support and management on event day for a stress-free experience.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="relative" data-aos="fade-left">
                <div class="bg-white rounded-3xl p-10 relative overflow-hidden" style="box-shadow: 0 25px 50px -12px rgba(15, 61, 62, 0.15);">
                    <!-- Decorative Background -->
                    <div class="absolute top-0 right-0 w-40 h-40 rounded-full -translate-y-1/2 translate-x-1/2" style="background: linear-gradient(135deg, rgba(200, 169, 81, 0.1), rgba(15, 61, 62, 0.05));"></div>
                    
                    <h3 class="text-2xl font-semibold mb-8 relative" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif;">Quick Inquiry</h3>
                    
                    <form action="<?php echo route('/contact'); ?>" method="GET" class="space-y-5 relative">
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: #0F3D3E; font-family: 'Montserrat', sans-serif;">Your Name</label>
                            <input type="text" name="name" required class="w-full px-4 py-3 rounded-xl border-2 border-gray-100 focus:border-[#C8A951] focus:outline-none transition-colors" placeholder="John Doe">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: #0F3D3E; font-family: 'Montserrat', sans-serif;">Email Address</label>
                            <input type="email" name="email" required class="w-full px-4 py-3 rounded-xl border-2 border-gray-100 focus:border-[#C8A951] focus:outline-none transition-colors" placeholder="john@example.com">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: #0F3D3E; font-family: 'Montserrat', sans-serif;">Event Date</label>
                            <input type="date" name="event_date" class="w-full px-4 py-3 rounded-xl border-2 border-gray-100 focus:border-[#C8A951] focus:outline-none transition-colors">
                        </div>
                        <input type="hidden" name="service" value="<?php echo htmlspecialchars($service['title']); ?>">
                        <button type="submit" class="w-full py-4 rounded-xl font-semibold transition-all duration-300 hover:shadow-lg" style="background-color: #C8A951; color: #0F3D3E; font-family: 'Montserrat', sans-serif; letter-spacing: 0.05em;">
                            Request Consultation
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Banner -->
<section class="py-16 px-4 relative overflow-hidden" style="background: linear-gradient(135deg, #0F3D3E 0%, #1C1C1C 100%);">
    <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22100%22 height=%22100%22><circle cx=%2250%22 cy=%2250%22 r=%222%22 fill=%22%23C8A951%22/></svg>');"></div>
    
    <div class="max-w-4xl mx-auto text-center relative z-10" data-aos="fade-up">
        <h2 class="text-3xl md:text-4xl font-light mb-6 text-white" style="font-family: 'Cormorant Garamond', serif; letter-spacing: -0.02em;">
            Interested in This Service?
        </h2>
        <p class="text-lg mb-10 text-gray-300 leading-relaxed max-w-2xl mx-auto">
            Let's discuss how we can make your event exceptional with our professional services.
        </p>
        <a href="<?php echo route('/contact'); ?>" class="inline-flex items-center px-10 py-4 rounded-xl font-semibold transition-all duration-300 hover:shadow-2xl hover:scale-105" style="background-color: #C8A951; color: #0F3D3E; font-family: 'Montserrat', sans-serif; letter-spacing: 0.1em; text-transform: uppercase;">
            Get Your Free Consultation
            <i class="fas fa-arrow-right ml-2"></i>
        </a>
    </div>
</section>

<!-- Related Services Section -->
<section class="py-20 px-4" style="background-color: #F8F5F2;">
    <div class="site-container">
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="inline-block px-4 py-2 rounded-full mb-6 text-xs font-semibold tracking-widest uppercase" style="background-color: rgba(15, 61, 62, 0.1); color: #C8A951; font-family: 'Montserrat', sans-serif; letter-spacing: 0.2em;">
                Explore More
            </span>
            <h2 class="text-3xl md:text-4xl font-light mb-6" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif; letter-spacing: -0.02em;">
                Our Other Services
            </h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php 
            $count = 0;
            foreach ($services as $otherService): 
                if ($otherService['id'] != $service['id'] && $count < 3):
                    $count++;
            ?>
                <div class="group bg-white rounded-2xl overflow-hidden transition-all duration-500 hover:shadow-2xl" data-aos="fade-up" data-aos-delay="<?php echo $count * 100; ?>" style="box-shadow: 0 4px 20px rgba(15, 61, 62, 0.08);">
                    <div class="relative h-48 overflow-hidden">
                        <?php if (!empty($otherService['image'])): ?>
                            <img src="<?php echo htmlspecialchars(uploadedImageUrl($otherService['image'])); ?>" 
                                 alt="<?php echo htmlspecialchars($otherService['title']); ?>"
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center" style="background: linear-gradient(135deg, #0F3D3E 0%, #2d5a5b 100%);">
                                <i class="fas fa-sparkles text-white text-4xl opacity-30"></i>
                            </div>
                        <?php endif; ?>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>
                    
                    <div class="p-6">
                        <div class="w-10 h-1 rounded-full mb-4 transition-all duration-300 group-hover:w-16" style="background-color: #C8A951;"></div>
                        <h3 class="text-xl font-semibold mb-3 transition-colors duration-300 group-hover:text-[#C8A951]" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif;">
                            <?php echo htmlspecialchars($otherService['title']); ?>
                        </h3>
                        <p class="text-gray-600 text-sm mb-6 line-clamp-2">
                            <?php echo htmlspecialchars(substr($otherService['description'], 0, 120)); ?>...
                        </p>
                        <a href="<?php echo route('/services/' . $otherService['id']); ?>" 
                           class="inline-flex items-center text-sm font-semibold transition-colors duration-300" 
                           style="color: #0F3D3E; font-family: 'Montserrat', sans-serif;">
                            View Details
                            <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                </div>
            <?php 
                endif;
            endforeach; 
            ?>
        </div>
        
        <?php if (count($services) > 1): ?>
        <div class="text-center mt-12" data-aos="fade-up">
            <a href="<?php echo route('/services'); ?>" class="inline-flex items-center px-8 py-4 rounded-xl font-semibold transition-all duration-300 hover:shadow-lg" style="background-color: #0F3D3E; color: white; font-family: 'Montserrat', sans-serif; letter-spacing: 0.05em;">
                View All Services
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
        <?php endif; ?>
    </div>
</section>

<style>
    /* Line Clamp */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* Floating Animations */
    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(5deg); }
    }
    
    @keyframes float-delayed {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-30px) rotate(-5deg); }
    }
    
    .animate-float {
        animation: float 6s ease-in-out infinite;
    }
    
    .animate-float-delayed {
        animation: float-delayed 8s ease-in-out infinite;
        animation-delay: 2s;
    }
    
    /* Form Focus States */
    input:focus, textarea:focus {
        outline: none;
    }
    
    /* Smooth Scroll */
    html {
        scroll-behavior: smooth;
    }
</style>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/app.php';
?>
