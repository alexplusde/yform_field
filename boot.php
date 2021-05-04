<?php
/**
 *  This file is part of the REDAXO-AddOn "yform_fields".
 *
 *  @author      FriendsOfREDAXO @ GitHub <https://github.com/FriendsOfREDAXO/focuspoint>
 *  @version     0.1
 *  @copyright   FriendsOfREDAXO <https://friendsofredaxo.github.io/>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 *
 *  ------------------------------------------------------------------------------------------------
 *
 */

rex_extension::register('PACKAGES_INCLUDED', function (rex_extension_point $ep) {
    rex_yform::addTemplatePath($this->getPath('ytemplates'));
    rex_view::addJsFile( $this->getAssetsUrl('for.js') );
});
