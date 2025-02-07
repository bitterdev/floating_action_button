<?php

namespace Concrete\Package\FloatingActionButton\Controller\SinglePage\Dashboard;

use Concrete\Core\Page\Controller\DashboardPageController;

class FloatingActionButton extends DashboardPageController
{
    public function view()
    {
        return $this->buildRedirectToFirstAccessibleChildPage();
    }
}