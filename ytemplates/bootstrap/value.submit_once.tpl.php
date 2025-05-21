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
?>
<button
    class="<?= implode(' ', $classes) ?>"
    type="submit"
    name="<?= $this->getFieldName() ?>"
    id="<?= $id ?>"
>
<?= $label_translated ?>
</button>
<script nonce="<?= rex_response::getNonce() ?>">
document.getElementById('<?= $id ?>').addEventListener('click', function(e) {
    history.pushState({ page: 1 }, '', '#');
    this.disabled = true;
    this.value = '<?= $loading ?>';
    HTMLFormElement.prototype.submit.call(this.form);
});
</script>
