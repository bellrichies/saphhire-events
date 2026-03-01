# Sapphire Events & Decorations

Modern Event Planning & Decoration Website built with clean MVC architecture.

## Quick Start

### Requirements
- PHP 8.2+
- MySQL 8+
- Composer (optional)
- Apache with mod_rewrite

### Installation

1. **Clone/Copy Project**
   ```bash
   git clone <repo> SapphireEvents
   cd SapphireEvents
   ```

2. **Install Dependencies** (if using Composer)
   ```bash
   composer install
   ```

3. **Configure Database**
   - Edit `.env` file with your database credentials:
   ```
   DB_HOST=localhost
   DB_PORT=3306
   DB_NAME=sapphire_events
   DB_USER=root
   DB_PASSWORD=yourpassword
   ```

4. **Create Database**
   ```bash
   mysql -u root -p -e "CREATE DATABASE sapphire_events;"
   ```

5. **Run Migrations**
   ```bash
   php config/migrate.php
   ```

6. **Configure Web Server**
   - Point your web root to `/public`
   - Ensure `.htaccess` is enabled
   - Base URL in `.env` should match your domain

### First Admin User

After migration, create a super admin:

```bash
php -r "
require 'app/Core/Database.php';
require 'app/Models/Admin.php';

\$db = \App\Core\Database::getInstance()->getConnection();
\$db->prepare('INSERT INTO admins (name, email, password, role) VALUES (?, ?, ?, ?)')
    ->execute(['Admin', 'admin@example.com', password_hash('password', PASSWORD_ARGON2ID), 'super_admin']);

echo 'Admin created: admin@example.com / password\\n';
"
```

### Project Structure

```
/app
    /Core          - Framework core classes
    /Controllers   - Application controllers
    /Models        - Database models
    /Views         - View templates
/config            - Configuration files
/public
    index.php      - Entry point
    /assets        - CSS, JS, images
/uploads           - User uploads
/routes            - Route definitions
/storage           - Cache, logs
.env               - Environment variables
```

### Features

- ✨ Elegant luxury aesthetic
- 🎨 Fully responsive design
- 🖼️ Gallery with category filtering
- 🛠️ Admin dashboard
- 📝 Dynamic content management
- 🔒 Secure authentication
- 📧 Contact inquiry form
- 💾 Clean MVC architecture

### Admin Dashboard

- **URL:** `/admin/login`
- **Features:**
  - Manage gallery items
  - Organize categories
  - Add services & testimonials
  - View inquiries
  - Update homepage content

### API Endpoints

**Public:**
- `GET /` - Homepage
- `GET /gallery` - Gallery listing
- `GET /contact` - Contact form
- `POST /contact` - Submit inquiry

**Admin:**
- `GET/POST /admin/login` - Authentication
- `GET /admin/dashboard` - Dashboard
- `GET/POST /admin/gallery` - Manage gallery
- `GET/POST /admin/categories` - Manage categories
- `GET/POST /admin/services` - Manage services
- `GET/POST /admin/testimonials` - Manage testimonials
- `GET /admin/inquiries` - View inquiries

### Security Features

- CSRF protection on all forms
- Password hashing with Argon2ID
- Session regeneration after login
- Input validation & sanitization
- XSS protection
- SQL injection prevention (PDO prepared statements)
- Login rate limiting
- Secure session cookies

### Performance Optimization

- Database indexing
- Lazy loading images
- CSS/JS minification ready
- Efficient queries
- Proper HTTP caching

### Customization

#### Change Colors
Edit `/app/Views/layouts/app.php` CSS variables:
```css
--emerald: #0F3D3E;
--gold: #C8A951;
--off-white: #F8F5F2;
--charcoal: #1C1C1C;
```

#### Add Pages
1. Create controller: `app/Controllers/PageController.php`
2. Create view: `app/Views/page/index.php`
3. Add route: `routes/web.php`

#### Database Queries
Models in `app/Models/` handle all database operations using prepared statements.

### Troubleshooting

**404 Errors:**
- Check `.htaccess` is in `/public`
- Ensure mod_rewrite is enabled
- Verify `BASE_PATH` matches your installation path

**Database Connection:**
- Check `.env` credentials
- Ensure database exists
- Run migrations again if needed

**Admin Login Issues:**
- Check database has `admins` table
- Verify admin user exists
- Clear cookies and try again

### Deployment

1. Set `.env` to `production`
2. Disable error display in production
3. Update database with real credentials
4. Configure SSL certificate
5. Set proper file permissions:
   ```bash
   chmod 755 app/ public/ uploads/ storage/
   chmod 644 public/index.php
   ```
6. Set up daily backups

### Support

For issues or questions:
- Email: Sapphireeventsglitz@gmail.com
- Phone: +372-5160427
- Location: Laki 14a, room 503, 10621 Tallinn, Estonia

---

Built with ❤️ by Sapphire Events
