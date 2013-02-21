<?php

/**
 *
 * @author blumbeet - web.studio
 * @author Thomas Blum
 * @author mail[at]blumbeet[dot]com Thomas Blum
 *
 */

$subpages = array();
if ($REX_USER->isValueOf('rights', 'admin[]')) {
    $subpages[] = array('', rex_i18n::msg('redaxo5_autoload'));
    $subpages[] = array('path', rex_i18n::msg('redaxo5_path'));
}


$mypage = 'redaxo5';

require $REX['INCLUDE_PATH'] . '/layout/top.php';

switch (rex_request('subpage')) {
    case 'path':
        // Test
        rex_title(rex_i18n::msg('redaxo5_title') . ' :: ' . rex_i18n::msg('redaxo5_path'), $subpages);
        include $REX['INCLUDE_PATH'] . '/addons/redaxo5/pages/path.inc.php';
        break;
    default:
        // Autoload
        rex_title(rex_i18n::msg('redaxo5_title') . ' :: ' . rex_i18n::msg('redaxo5_autoload'), $subpages);
        include $REX['INCLUDE_PATH'] . '/addons/redaxo5/pages/autoload.inc.php';
        break;
}

require $REX['INCLUDE_PATH'] . '/layout/bottom.php';