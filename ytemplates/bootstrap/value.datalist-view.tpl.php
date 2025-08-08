<?php

/**
 * @var rex_yform_value_abstract $this
 * @psalm-scope-this rex_yform_value_abstract
 * @var string $value
 */

$notices = [];
if ($this->getElement('notice')) {
    $notices[] = rex_i18n::translate($this->getElement('notice'), false);
}

?>

<div class="form-group">
    <?php if ($this->getLabel()): ?>
    <label class="control-label">
        <?= $this->getLabelStyle($this->getLabel()) ?>
    </label>
    <?php endif ?>

    <div class="form-control-static">
        <?= rex_escape($value) ?>
    </div>

    <?php if ($notices): ?>
    <p class="help-block small"><?= implode('<br />', $notices) ?></p>
    <?php endif ?>
</div>