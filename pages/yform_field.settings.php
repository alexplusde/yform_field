<?php

echo rex_view::title(rex_i18n::msg('yform_field_settings'));

$addon = rex_addon::get('yform_field');

$form = rex_config_form::factory($addon->getName());

$fragment = new rex_fragment();
$fragment->setVar('class', 'edit', false);
$fragment->setVar('title', $addon->i18n('yform_field_config'), false);
$fragment->setVar('body', $form->get(), false);

?>

<div class="row">
	<div class="col-lg-8">
		<?= $fragment->parse('core/page/section.php') ?>
	</div>
	<div class="col-lg-4">
		<?php
$anchor = '<a target="_blank" href="https://donate.alexplus.de/?addon=yform_field"><img src="' . rex_url::addonAssets('yform_field', 'jetzt-beauftragen.svg') . '" style="width: 100% max-width: 400px;"></a>';

    $fragment = new rex_fragment();
        $fragment->setVar('class', 'info', false);
        $fragment->setVar('title', $this->i18n('yform_field_donate'), false);
        $fragment->setVar('body', '<p>' . $this->i18n('yform_field_info_donate') . '</p>' . $anchor, false);
        echo !rex_config::get('alexplusde', 'donated') ? $fragment->parse('core/page/section.php') : '';
        ?>
	</div>
</div>
