<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Package;
use App\Models\PackageCategory;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        $service = new Service();
        $package = new Package();
        $packageCategory = new PackageCategory();
        $services = $service->getAllWithImage();
        $featuredPackages = $package->getFeatured(6);
        $packageCategories = $packageCategory->getWithPackageCount();

        $this->view('services.index', [
            'services' => $services,
            'featuredPackages' => $featuredPackages,
            'packageCategories' => $packageCategories,
            'seo' => [
                'title' => 'Event Services | Sapphire Events & Decorations',
                'description' => 'Explore our event services, from concept design and venue styling to full planning and coordination for weddings, birthdays, proposals, and corporate events.',
                'canonical' => route('/services'),
                'url' => route('/services'),
                'image' => 'assets/images/proposal-service.avif',
                'image_alt' => 'Sapphire Events service showcase',
            ],
        ]);
    }

    public function show()
    {
        $service = new Service();
        $id = $this->getPathVariable('id');

        if (!$id || !is_numeric($id)) {
            http_response_code(404);
            echo "Service not found";
            exit;
        }

        $currentService = $service->find((int)$id);

        if (!$currentService) {
            http_response_code(404);
            echo "Service not found";
            exit;
        }

        $allServices = $service->getAllWithImage();

        $this->view('services.show', [
            'service' => $currentService,
            'services' => $allServices,
            'seo' => [
                'title' => ($currentService['title'] ?? 'Service') . ' | Sapphire Events',
                'description' => $currentService['description'] ?? 'Professional event planning and decoration services by Sapphire Events.',
                'canonical' => route('/services/' . (int)$id),
                'url' => route('/services/' . (int)$id),
                'image' => $currentService['image'] ?? 'assets/images/ceo-image.png',
                'image_alt' => ($currentService['title'] ?? 'Service') . ' by Sapphire Events',
            ],
        ]);
    }

    private function getPathVariable(string $name): ?string
    {
        $router = \App\Core\App::getInstance()->getRouter();
        return $router->getPathVariable($name);
    }
}
