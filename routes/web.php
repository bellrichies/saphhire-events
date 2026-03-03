<?php

use App\Core\App;

$app = App::getInstance();
$router = $app->getRouter();

// Language Routes
$router->get('/lang', 'LanguageController@switch');
$router->get('/api/language/info', 'LanguageController@info');

// Public Routes
$router->get('/', 'HomeController@index');
$router->get('/gallery', 'GalleryController@index');
$router->get('/services', 'ServiceController@index');
$router->get('/services/{id}', 'ServiceController@show');
$router->get('/packages', 'PackageController@index');
$router->post('/packages/book', 'PackageController@book');
$router->get('/packages/{slug}', 'PackageController@show');
$router->get('/about', 'AboutController@index');
$router->get('/faqs', 'FaqController@index');
$router->get('/contact', 'ContactController@index');
$router->post('/contact', 'ContactController@store');
$router->post('/newsletter/subscribe', 'NewsletterController@store');

// Admin Authentication
$router->get('/admin/login', 'AdminController@loginForm');
$router->post('/admin/login', 'AdminController@login');
$router->get('/admin/logout', 'AdminController@logout');

// Admin Dashboard
$router->get('/admin/dashboard', 'AdminController@dashboard');
$router->post('/admin/translations/cache/clear', 'AdminController@clearTranslationCache');

// Admin Gallery Routes
$router->get('/admin/gallery', 'GalleryAdminController@index');
$router->get('/admin/gallery/create', 'GalleryAdminController@create');
$router->get('/admin/gallery/edit', 'GalleryAdminController@edit');
$router->post('/admin/gallery', 'GalleryAdminController@store');
$router->post('/admin/gallery/update', 'GalleryAdminController@update');
$router->post('/admin/gallery/delete', 'GalleryAdminController@delete');

// Admin Category Routes
$router->get('/admin/categories', 'CategoryAdminController@index');
$router->get('/admin/categories/create', 'CategoryAdminController@create');
$router->post('/admin/categories', 'CategoryAdminController@store');
$router->post('/admin/categories/delete', 'CategoryAdminController@delete');

// Admin Service Routes
$router->get('/admin/services', 'ServiceAdminController@index');
$router->get('/admin/services/create', 'ServiceAdminController@create');
$router->get('/admin/services/edit', 'ServiceAdminController@edit');
$router->post('/admin/services', 'ServiceAdminController@store');
$router->post('/admin/services/update', 'ServiceAdminController@update');
$router->post('/admin/services/delete', 'ServiceAdminController@delete');

// Admin Package Category Routes
$router->get('/admin/package-categories', 'PackageCategoryAdminController@index');
$router->get('/admin/package-categories/create', 'PackageCategoryAdminController@create');
$router->get('/admin/package-categories/edit', 'PackageCategoryAdminController@edit');
$router->post('/admin/package-categories', 'PackageCategoryAdminController@store');
$router->post('/admin/package-categories/update', 'PackageCategoryAdminController@update');
$router->post('/admin/package-categories/delete', 'PackageCategoryAdminController@delete');

// Admin Package Routes
$router->get('/admin/packages', 'PackageAdminController@index');
$router->get('/admin/packages/create', 'PackageAdminController@create');
$router->get('/admin/packages/edit', 'PackageAdminController@edit');
$router->post('/admin/packages', 'PackageAdminController@store');
$router->post('/admin/packages/update', 'PackageAdminController@update');
$router->post('/admin/packages/delete', 'PackageAdminController@delete');

// Admin Testimonial Routes
$router->get('/admin/testimonials', 'TestimonialAdminController@index');
$router->get('/admin/testimonials/create', 'TestimonialAdminController@create');
$router->get('/admin/testimonials/edit', 'TestimonialAdminController@edit');
$router->post('/admin/testimonials', 'TestimonialAdminController@store');
$router->post('/admin/testimonials/update', 'TestimonialAdminController@update');
$router->post('/admin/testimonials/delete', 'TestimonialAdminController@delete');

// Admin Inquiry Routes
$router->get('/admin/inquiries', 'InquiryAdminController@index');
$router->get('/admin/inquiries/show', 'InquiryAdminController@show');
$router->get('/admin/inquiries/export/csv', 'InquiryAdminController@exportCsv');
$router->get('/admin/inquiries/export/txt', 'InquiryAdminController@exportTxt');
$router->post('/admin/inquiries/delete', 'InquiryAdminController@delete');

// Admin Newsletter Routes
$router->get('/admin/newsletters', 'NewsletterAdminController@index');
$router->get('/admin/newsletters/export/csv', 'NewsletterAdminController@exportCsv');
$router->get('/admin/newsletters/export/txt', 'NewsletterAdminController@exportTxt');
$router->post('/admin/newsletters/status', 'NewsletterAdminController@updateStatus');
$router->post('/admin/newsletters/delete', 'NewsletterAdminController@delete');

// Admin Team Routes
$router->get('/admin/team', 'TeamAdminController@index');
$router->get('/admin/team/create', 'TeamAdminController@create');
$router->get('/admin/team/edit', 'TeamAdminController@edit');
$router->post('/admin/team', 'TeamAdminController@store');
$router->post('/admin/team/update', 'TeamAdminController@update');
$router->post('/admin/team/delete', 'TeamAdminController@delete');

// Admin Media Library Routes
$router->get('/admin/media', 'MediaAdminController@index');
$router->get('/admin/media/list', 'MediaAdminController@list');
$router->post('/admin/media/upload', 'MediaAdminController@upload');
$router->post('/admin/media/delete', 'MediaAdminController@delete');

// SEO Routes
$router->get('/sitemap.xml', 'SitemapController@generate');
