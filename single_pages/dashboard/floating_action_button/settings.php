<?php

defined('C5_EXECUTE') or die('Access denied');

use Concrete\Core\Application\Service\FileManager;
use Concrete\Core\Form\Service\Form;
use Concrete\Core\Form\Service\Widget\PageSelector;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Validation\CSRF\Token;

/** @var array $alignValues */
/** @var string $align */
/** @var bool $fadeIn */
/** @var bool $fadeOut */
/** @var int $targetPageId */
/** @var int $imageFileId */
/** @var int $imageSize */

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
?>

<form action="#" method="post">
    <?php echo $token->output("update_settings"); ?>

    <fieldset>
        <legend>
            <?php echo t("Settings"); ?>
        </legend>

        <div class="form-group">
            <?php echo $form->label("align", t("Align")); ?>
            <?php echo $form->select("align", $alignValues, $align); ?>
        </div>

        <div class="form-group">
            <div class="form-check">
                <?php echo $form->checkbox("fadeIn", 1, $fadeIn); ?>
                <?php echo $form->label("fadeIn", t("Fade In"), ["class" => "form-check-label"]); ?>
            </div>

            <div class="form-check">
                <?php echo $form->checkbox("fadeOut", 1, $fadeOut); ?>
                <?php echo $form->label("fadeOut", t("Fade Out"), ["class" => "form-check-label"]); ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo $form->label("targetPageId", t("Target Page")); ?>
            <?php echo $pageSelector->selectPage("targetPageId", $targetPageId); ?>
        </div>

        <div class="form-group">
            <?php echo $form->label("imageFileId", t("Image")); ?>
            <?php echo $fileManager->image("imageFileId", "imageFileId", t("Please select file..."), $imageFileId); ?>
        </div>

        <div class="form-group">
            <?php echo $form->label("imageSize", t("Image Size")); ?>

            <div class="input-group">
                <?php echo $form->number("imageSize", $imageSize, ["min" => 0, "step" => 1]); ?>

                <div class="input-group-text">
                    <?php echo t("px"); ?>
                </div>
            </div>
        </div>
    </fieldset>

    <div class="ccm-dashboard-form-actions-wrapper">
        <div class="ccm-dashboard-form-actions">
            <?php echo $form->submit('save', t('Save'), ['class' => 'btn btn-primary float-end']); ?>
        </div>
    </div>
</form>
