<?php

/**
 * Template für den privaten YForm-Datentyp "rex_yform_value_tabs".
 */

// namespace Project;

// use rex_yform_value_tabs;

/**
 * @var rex_yform_value_tabs $this
 * @var string $option
 * @var array<rex_yform_value_tabs> $tabset
 */

/**
 * Tabset insgesamt öffnen, also das Menü aufbauen und den Container öffnen
 * Der letzte Tab ($sequence === PHP_INT_MAX) ignorieren, das ist der
 * Platzhalter zum Schließen ohne eigenen Menüeintrag.
 */
if ('open_tabset' === $option) {
    $activeId = $tabset[array_key_first($tabset)]->getId();
    $uid = 'yff' . uniqid();
    echo '<ul class="nav nav-tabs" id="',$uid,'-t">',PHP_EOL;
    foreach ($tabset as $tab) {
        if (PHP_INT_MAX !== $tab->sequence) {
            $tabLabel = $tab->getLabel();
            $tabHTMLid = $tab->getHTMLId();
            $class = [];
            if ($tab->selected) {
                $class[] = 'active';
                $activeId = $tab->getId();
            }
            if ($tab->hasErrorField) {
                $class[] = $tab->hasErrorField;
                $tabLabel = '<span class="text-danger"><i class="fa fa-warning"></i> ' . $tabLabel . '</span>';
            }
            $class = $class ? ' class="' . implode(' ', $class) . '"' : '';
            echo '  <li role="presentation"',$class,'><a data-toggle="tab" href="#',$tabHTMLid,'" data-field="',$tab->getId(),'">',$tabLabel,'</a></li>',PHP_EOL;
        }
    }
    echo '</ul>',PHP_EOL;
    echo '<input type="hidden" name="',md5($this->getFieldName()),'" value="',$activeId,'" />';
    echo '<script>$(\'#',$uid,'-t\').on("show.bs.tab",e=>{e.currentTarget.nextElementSibling.value=e.target.dataset.field;});</script>';
    echo '<div class="panel panel-default tab-content">',PHP_EOL;
}

/**
 * Schließt den gesamten Tabset.
 */
if ('close_tabset' === $option) {
    echo '</div> <!-- close tab-content -->',PHP_EOL;
}

/**
 * öffnet den Tab.
 */
if ('open_tab' === $option) {
    $isActive = $this->selected ? ' in active' : '';
    $tabHTMLid = $this->getHTMLId();
    echo '<div role="tabpanel" id="',$tabHTMLid,'" class="tab-pane xfade',$isActive,'">',PHP_EOL;
}

/**
 * schließt den Tab.
 */
if ('close_tab' === $option) {
    $tabHTMLid = $this->getHTMLId();
    echo '</div> <!-- close tab (',$tabHTMLid,')-->',PHP_EOL;
}
