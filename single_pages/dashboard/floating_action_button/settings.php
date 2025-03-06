<?php

defined('C5_EXECUTE') or die('Access denied');

use Concrete\Core\Application\Service\FileManager;
use Concrete\Core\Application\Service\UserInterface;
use Concrete\Core\Form\Service\Form;
use Concrete\Core\Form\Service\Widget\PageSelector;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Validation\CSRF\Token;

/** @var array $alignValues */
/** @var array $locales */
/** @var array $data */

$app = Application::getFacadeApplication();
/** @var Form $form */
/** @noinspection PhpUnhandledExceptionInspection */
$form = $app->make(Form::class);
/** @var PageSelector $pageSelector */
/** @noinspection PhpUnhandledExceptionInspection */
$pageSelector = $app->make(PageSelector::class);
/** @var FileManager $fileManager */
/** @noinspection PhpUnhandledExceptionInspection */
$fileManager = $app->make(FileManager::class);
/** @var Token $token */
/** @noinspection PhpUnhandledExceptionInspection */
$token = $app->make(Token::class);
/** @var UserInterface $ui */
/** @noinspection PhpUnhandledExceptionInspection */
$ui = $app->make(UserInterface::class);

$tabs = [];

foreach($locales as $locale => $languageName) {
    $tabs[] = [$locale, $languageName, $locale === array_key_first($locales)];
}

echo $ui->tabs($tabs);
?>

<form action="#" method="post">
    <?php echo $token->output("update_settings"); ?>

    <div class="tab-content">
        <?php foreach($locales as $locale => $languageName) { ?>
        <div class="tab-pane <?php echo $locale === array_key_first($locales) ? " active" : "" ?>" id="<?php echo $locale; ?>" role="tabpanel">
            <fieldset>
                <legend>
                    <?php echo t("Settings"); ?>
                </legend>

                <div class="form-group">
                    <?php echo $form->label($locale. "_align", t("Align")); ?>
                    <?php echo $form->select($locale. "_align", $alignValues, $data[$locale]["align"], ["name" => "data[" . $locale . "][align]"]); ?>
                </div>

                <div class="form-group">
                    <div class="form-check">
                        <?php echo $form->checkbox($locale. "_fadeIn", 1, $data[$locale]["fadeIn"], ["name" => "data[" . $locale . "][fadeIn]"]); ?>
                        <?php echo $form->label($locale. "_fadeIn", t("Fade In"), ["class" => "form-check-label"]); ?>
                    </div>

                    <div class="form-check">
                        <?php echo $form->checkbox($locale. "_fadeOut", 1, $data[$locale]["fadeOut"],["name" => "data[" . $locale . "][fadeOut]"]); ?>
                        <?php echo $form->label($locale. "_fadeOut", t("Fade Out"), ["class" => "form-check-label"]); ?>
                    </div>
                </div>

                <div class="form-group">
                    <?php echo $form->label($locale. "_targetPageId", t("Target Page")); ?>
                    <?php echo $pageSelector->selectPage("data[" . $locale . "][targetPageId]", $data[$locale]["targetPageId"]); ?>
                </div>

                <div class="form-group">
                    <?php echo $form->label($locale. "_imageFileId", t("Image")); ?>
                    <?php echo $fileManager->image($locale. "_imageFileId", "data[" . $locale . "][imageFileId]", t("Please select file..."), $data[$locale]["imageFileId"]); ?>
                </div>

                <div class="form-group">
                    <?php echo $form->label($locale. "_imageSize", t("Image Size")); ?>

                    <div class="input-group">
                        <?php echo $form->number($locale. "_imageSize", $data[$locale]["imageSize"], ["min" => 0, "step" => 1, "name" => "data[" . $locale . "][imageSize]"]); ?>

                        <div class="input-group-text">
                            <?php echo t("px"); ?>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
        <?php }?>
    </div>

    <div class="ccm-dashboard-form-actions-wrapper">
        <div class="ccm-dashboard-form-actions">
            <?php echo $form->submit('save', t('Save'), ['class' => 'btn btn-primary float-end']); ?>
        </div>
    </div>
</form>
