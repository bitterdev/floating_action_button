<?php

namespace Concrete\Package\FloatingActionButton\Controller\SinglePage\Dashboard\FloatingActionButton;

use Concrete\Core\Entity\Site\Site;
use Concrete\Core\Error\ErrorList\ErrorList;
use Concrete\Core\Form\Service\Validation;
use Concrete\Core\Page\Controller\DashboardPageController;

class Settings extends DashboardPageController
{
    public const ALIGN_RIGHT = "right";
    public const ALIGN_LEFT = "left";

    public function view()
    {
        /** @var Site $site */
        /** @noinspection PhpUnhandledExceptionInspection */
        $site = $this->app->make('site')->getSite();
        $config = $site->getConfigRepository();

        if ($this->getRequest()->getMethod() === "POST") {
            /** @var Validation $formValidator */
            /** @noinspection PhpUnhandledExceptionInspection */
            $formValidator = $this->app->make(Validation::class);
            $formValidator->setData($this->request->request->all());
            $formValidator->addRequiredToken("update_settings");

            if ($formValidator->test()) {
                $config->save("floating_action_button.align", $this->request->request->get("align"));
                $config->save("floating_action_button.fade_in", $this->request->request->has("fadeIn"));
                $config->save("floating_action_button.fade_out", $this->request->request->has("fadeOut"));
                $config->save("floating_action_button.target_page_id", (int)$this->request->request->get("targetPageId"));
                $config->save("floating_action_button.image_file_id", (int)$this->request->request->get("imageFileId"));
                $config->save("floating_action_button.image_size", (int)$this->request->request->get("imageSize"));

                if (!$this->error->has()) {
                    $this->set("success", t("The settings has been successfully updated."));
                }
            } else {
                /** @var ErrorList $errorList */
                $errorList = $formValidator->getError();

                foreach ($errorList->getList() as $error) {
                    $this->error->add($error);
                }
            }
        }

        $alignValues = [
            self::ALIGN_RIGHT => t("Right"),
            self::ALIGN_LEFT => t("Left")
        ];

        $this->set("alignValues", $alignValues);
        $this->set("align", $config->get("floating_action_button.align"));
        $this->set("fadeIn", (bool)$config->get("floating_action_button.fade_in"));
        $this->set("fadeOut", (bool)$config->get("floating_action_button.fade_out"));
        $this->set("targetPageId", (int)$config->get("floating_action_button.target_page_id"));
        $this->set("imageFileId", (int)$config->get("floating_action_button.image_file_id"));
        $this->set("imageSize", (int)$config->get("floating_action_button.image_size", 80));
    }
}