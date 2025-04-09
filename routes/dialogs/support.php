<?php

defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\Routing\Router;

/**
 * @var Router $router
 * Base path: /ccm/system/dialogs/floating_action_button
 * Namespace: Concrete\Package\FloatingActionButton\Controller\Dialog
 */

$router->all('/create_ticket', 'CreateTicket::view');
$router->all('/create_ticket/submit', 'CreateTicket::submit');