<?php

namespace Concrete\Package\FloatingActionButton;

use Bitter\FloatingActionButton\Provider\ServiceProvider;
use Concrete\Core\Package\Package;
use Concrete\Core\Entity\Package as PackageEntity;

class Controller extends Package
{
    protected string $pkgHandle = 'floating_action_button';
    protected string $pkgVersion = '0.0.3';
    protected $appVersionRequired = '9.0.0';
    protected $pkgAutoloaderRegistries = [
        'src/Bitter/FloatingActionButton' => 'Bitter\FloatingActionButton',
    ];

    public function getPackageDescription(): string
    {
        return t('The Floating Action Button add-on for Concrete CMS adds a customizable action button.');
    }

    public function getPackageName(): string
    {
        return t('Floating Action Button');
    }

    public function on_start()
    {
        /** @var ServiceProvider $serviceProvider */
        /** @noinspection PhpUnhandledExceptionInspection */
        $serviceProvider = $this->app->make(ServiceProvider::class);
        $serviceProvider->register();
    }

    public function install(): PackageEntity
    {
        $pkg = parent::install();
        $this->installContentFile("data.xml");
        return $pkg;
    }

    public function upgrade()
    {
        parent::upgrade();
        $this->installContentFile("data.xml");
    }
}
