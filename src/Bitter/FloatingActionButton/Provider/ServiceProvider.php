<?php

namespace Bitter\FloatingActionButton\Provider;

use Concrete\Core\Application\Application;
use Concrete\Core\Entity\File\Version;
use Concrete\Core\Entity\Site\Site;
use Concrete\Core\File\File;
use Concrete\Core\Foundation\Service\Provider;
use Concrete\Core\Html\Service\Html;
use Concrete\Core\Page\Page;
use Concrete\Core\Entity\File\File as FileEntity;
use Concrete\Core\Support\Facade\Url;
use Concrete\Core\View\View;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ServiceProvider extends Provider
{
    protected EventDispatcherInterface $eventDispatcher;

    public function __construct(
        Application              $app,
        EventDispatcherInterface $eventDispatcher
    )
    {
        parent::__construct($app);

        $this->eventDispatcher = $eventDispatcher;
    }

    public function register()
    {
        $this->eventDispatcher->addListener("on_before_render", function () {
            $c = Page::getCurrentPage();

            if (!$c->isError() && !$c->isAdminArea()) {
                /** @var Html $htmlHelper */
                $htmlHelper = $this->app->make(Html::class);
                /** @var Site $site */
                $site = $this->app->make('site')->getSite();
                $config = $site->getConfigRepository();
                $align = $config->get("floating_action_button.align");
                $fadeIn = (bool)$config->get("floating_action_button.fade_in");
                $fadeOut = (bool)$config->get("floating_action_button.fade_out");
                $targetPageId = (int)$config->get("floating_action_button.target_page_id");
                $targetPage = Page::getByID($targetPageId);
                $imageFileId = (int)$config->get("floating_action_button.image_file_id");
                $imageFile = File::getByID($imageFileId);
                $imageSize = (int)$config->get("floating_action_button.image_size", 80);

                if (!$targetPage->isError() &&
                    $imageFile instanceof FileEntity &&
                    $imageFile->getApprovedVersion() instanceof Version) {

                    $options = [
                        "align" => $align,
                        "imageUrl" => $imageFile->getApprovedVersion()->getURL(),
                        "targetUrl" => (string)Url::to($targetPage),
                        "fadeIn" => $fadeIn,
                        "fadeOut" => $fadeOut,
                        "imageSize" => $imageSize
                    ];

                    $v = View::getInstance();

                    $v->requireAsset("javascript", "jquery");
                    $v->addFooterItem($htmlHelper->javascript("floating-action-button.js", "floating_action_button"));
                    $v->addHeaderItem($htmlHelper->css("floating-action-button.css", "floating_action_button"));
                    /** @noinspection BadExpressionStatementJS */
                    /** @noinspection JSUnresolvedVariable */
                    $v->addFooterItem(sprintf(
                        "<script>(function($) { $(function(){ $(\".ccm-page\").floatingActionButton(%s) }); })(jQuery);</script>",
                        json_encode($options)
                    ));
                }
            }
        });
    }
}
