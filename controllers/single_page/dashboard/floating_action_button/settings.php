<?php

namespace Concrete\Package\FloatingActionButton\Controller\SinglePage\Dashboard\FloatingActionButton;

use Concrete\Core\Entity\Site\Site;
use Concrete\Core\Error\ErrorList\ErrorList;
use Concrete\Core\Form\Service\Validation;
use Concrete\Core\Multilingual\Service\Detector;
use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Page\Page;

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

        $locales = [];

        /** @var Detector $dl */
        $dl = $this->app->make('multilingual/detector');

        foreach($dl->getAvailableSections() as $section) {
            /** @var $section \Concrete\Core\Multilingual\Page\Section\Section */
            $locales[$section->getLocale()] = $section->getLanguageText($section->getLocale());
        }

        if ($this->getRequest()->getMethod() === "POST") {
            /** @var Validation $formValidator */
            /** @noinspection PhpUnhandledExceptionInspection */
            $formValidator = $this->app->make(Validation::class);
            $formValidator->setData($this->request->request->all());
            $formValidator->addRequiredToken("update_settings");

            if ($formValidator->test()) {
                $data = $this->request->request->get("data", []);

                foreach(array_keys($locales) as $locale) {
                    if (is_array($data) && isset($data[$locale])) {
                        $config->save("floating_action_button." . $locale . ".align", $data[$locale]["align"] ?? self::ALIGN_RIGHT);
                        $config->save("floating_action_button." . $locale . ".fade_in", isset($data[$locale]["fadeIn"]));
                        $config->save("floating_action_button." . $locale . ".fade_out", isset($data[$locale]["fadeOut"]));
                        $config->save("floating_action_button." . $locale . ".target_page_id", isset($data[$locale]["targetPageId"]) ? (int)$data[$locale]["targetPageId"] : 0);
                        $config->save("floating_action_button." . $locale . ".image_file_id", isset($data[$locale]["imageFileId"]) ? (int)$data[$locale]["imageFileId"] : 0);
                        $config->save("floating_action_button." . $locale . ".image_size", isset($data[$locale]["imageSize"]) ? (int)$data[$locale]["imageSize"] : 0);
                    }
                }

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

        $this->set("locales", $locales);
        $this->set("alignValues", $alignValues);

        $data = [];

        foreach(array_keys($locales) as $locale) {
            $data[$locale]["align"] = $config->get("floating_action_button." . $locale . ".align");
            $data[$locale]["fadeIn"] = (bool)$config->get("floating_action_button." . $locale . ".fade_in", false);
            $data[$locale]["fadeOut"] = (bool)$config->get("floating_action_button." . $locale . ".fade_out", false);
            $data[$locale]["targetPageId"] =(int) $config->get("floating_action_button." . $locale . ".target_page_id");
            $data[$locale]["imageFileId"] = (int)$config->get("floating_action_button." . $locale . ".image_file_id");
            $data[$locale]["imageSize"] = (int)$config->get("floating_action_button." . $locale . ".image_size", 120);
        }

        $this->set("data", $data);
    }
}