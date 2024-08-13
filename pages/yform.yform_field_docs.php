<?php

/**
 * @var rex_addon $this
 * @psalm-scope-this rex_addon
 */

$page = rex_be_controller::getPageObject('yform/yform_field_docs');

echo rex_view::title($this->i18n('yform_field_title'));

[$Toc, $Content] = rex_markdown::factory()->parseWithToc(rex_file::get(rex_path::addon('yform_field', 'README.md')), 2, 3, [
    rex_markdown::SOFT_LINE_BREAKS => false,
    rex_markdown::HIGHLIGHT_PHP => true,
]);

$fragment = new rex_fragment();
$fragment->setVar('content', $Content, false);
$fragment->setVar('toc', $Toc, false);
$content = $fragment->parse('core/page/docs.php');

$fragment = new rex_fragment();
$fragment->setVar('title', rex_i18n::msg('package_help') . ' YForm Field', false);
$fragment->setVar('body', $content, false);
echo $fragment->parse('core/page/section.php');
