<?php

/**
 * @var rex_yform_value_submit_once $this
 * @psalm-scope-this rex_yform_value_submit_once
 */

$label ??= '';

$classes = [];
$classes[] = 'btn';

if (isset($css_classes) && '' != trim($css_classes)) {
    $classes[] = trim($css_classes);
}
if ('' != $this->getWarningClass()) {
    $classes[] = $this->getWarningClass();
}

$id = $this->getFieldId() . '-' . rex_string::normalize($label);
$label_translated = rex_i18n::translate($label, true);

echo '<button onclick="history.pushState({ page: 1 }, "", "#"); this.disabled=true;this.value=\"' . $loading . '\"; this.form.submit();" class="' . implode(' ', $classes) . '" type="submit" name="' . $this->getFieldName() . '" id="' . $id . '">' . $label_translated . '</button>';
