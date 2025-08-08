<?php

/**
 * @var rex_yform_value_abstract $this
 * @psalm-scope-this rex_yform_value_abstract
 * @var array $attributes
 * @var array $choices
 * @var string $datalist_id
 */

$notices = [];
if ($this->getElement('notice')) {
    $notices[] = rex_i18n::translate($this->getElement('notice'), false);
}
if (isset($this->params['warning_messages'][$this->getId()]) && !$this->params['hide_field_warning_messages']) {
    $notices[] = '<span class="text-warning">' . rex_i18n::translate($this->params['warning_messages'][$this->getId()], false) . '</span>';
}

$class = 'form-control';
if (isset($attributes['class'])) {
    if (is_array($attributes['class'])) {
        $attributes['class'][] = $class;
    } else {
        $attributes['class'] = trim($attributes['class'] . ' ' . $class);
    }
} else {
    $attributes['class'] = $class;
}

$attributes['class'] = trim($attributes['class'] . ' ' . $this->getWarningClass());

?>

<div class="form-group<?= $this->getWarningClass() ?>">
    <?php if ($this->getLabel()): ?>
    <label class="control-label" for="<?= $this->getFieldId() ?>">
        <?= $this->getLabelStyle($this->getLabel()) ?>
    </label>
    <?php endif ?>

    <input<?= rex_string::buildAttributes($attributes) ?> />

    <datalist id="<?= rex_escape($datalist_id) ?>">
        <?php foreach ($choices as $value => $label): ?>
        <option value="<?= rex_escape($value) ?>"><?= rex_escape($label) ?></option>
        <?php endforeach ?>
    </datalist>

    <?php if ($notices): ?>
    <p class="help-block small"><?= implode('<br />', $notices) ?></p>
    <?php endif ?>
</div>