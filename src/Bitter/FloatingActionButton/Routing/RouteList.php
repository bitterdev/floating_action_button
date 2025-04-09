<?php

namespace Bitter\FloatingActionButton\Routing;

use Bitter\FloatingActionButton\API\V1\Middleware\FractalNegotiatorMiddleware;
use Bitter\FloatingActionButton\API\V1\Configurator;
use Concrete\Core\Routing\RouteListInterface;
use Concrete\Core\Routing\Router;

class RouteList implements RouteListInterface
{
    public function loadRoutes(Router $router)
    {
        $router
            ->buildGroup()
            ->setNamespace('Concrete\Package\FloatingActionButton\Controller\Dialog\Support')
            ->setPrefix('/ccm/system/dialogs/floating_action_button')
            ->routes('dialogs/support.php', 'floating_action_button');
    }
}