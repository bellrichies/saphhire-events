<?php
ob_start();
?>

<section class="flex items-center justify-center min-h-screen" style="background: linear-gradient(135deg, #0F3D3E 0%, #1C1C1C 100%);">
    <div class="w-full max-w-md px-4" data-aos="zoom-in">
        <div class="bg-white rounded-lg luxury-shadow p-8">
            <h1 class="text-3xl font-bold text-center mb-2" style="color: #0F3D3E; font-family: 'Playfair Display';">
                Sapphire Admin
            </h1>
            <p class="text-center text-gray-600 mb-8">Sign in to manage your content</p>

            <form id="login-form" method="POST" action="<?php echo route('/admin/login'); ?>">
                <?php echo \App\Core\CSRF::hidden(); ?>

                <div class="mb-6">
                    <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Email</label>
                    <input type="email" name="email" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600">
                    <small class="text-red-500 error-email"></small>
                </div>

                <div class="mb-8">
                    <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Password</label>
                    <input type="password" name="password" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600">
                    <small class="text-red-500 error-password"></small>
                </div>

                <div id="form-message" class="mb-4 p-4 rounded-lg hidden"></div>

                <button type="submit" class="btn-primary w-full font-semibold">Sign In</button>
            </form>

            <p class="text-center text-gray-600 mt-6">
                <a href="<?php echo route('/'); ?>" class="text-yellow-600 hover:text-yellow-700">← Back to Home</a>
            </p>
        </div>
    </div>
</section>

<script>
document.getElementById('login-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const messageDiv = document.getElementById('form-message');

    try {
        const response = await fetch('<?php echo route('/admin/login'); ?>', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success && data.redirect) {
            window.location.href = data.redirect;
        } else {
            throw data;
        }
    } catch (error) {
        messageDiv.className = 'mb-4 p-4 rounded-lg bg-red-100 text-red-700';
        
        if (error.errors) {
            Object.keys(error.errors).forEach(field => {
                const errorSpan = document.querySelector(`.error-${field}`);
                if (errorSpan) {
                    errorSpan.textContent = error.errors[field];
                }
            });
        } else {
            messageDiv.textContent = error.error || 'An error occurred. Please try again.';
        }
    }
});
</script>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
?>
