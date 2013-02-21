<?php

/**
 *
 * @author blumbeet - web.studio
 * @author Thomas Blum
 * @author mail[at]blumbeet[dot]com Thomas Blum
 *
 */


// --- DYN
$REX['ADDON']['REDAXO5']['AUTOLOAD'] = array (
  0 => 'pool',
);
// --- /DYN


$mypage = 'redaxo5';

$basedir = __DIR__;

// REDAXO 5 Hilfsklassen
require_once $basedir . '/lib/util/path.php';
rex_path::init($REX['HTDOCS_PATH'], 'redaxo/include');

require_once $basedir . '/lib/autoload.php';
rex_autoload::register();
rex_autoload::addDirectory(rex_path::addon($mypage . '/lib'));
rex_autoload::addDirectory(rex_path::addon($mypage . '/vendor'));

rex_fragment::addDirectory(rex_path::addon($mypage . '/fragments'));

rex_i18n::addDirectory(rex_path::addon($mypage . '/lang'));

switch ($REX['CUR_CLANG']) {
    case '1';
        rex::setProperty('lang', 'en_en');
        break;
    default:
        rex::setProperty('lang', 'de_de');
        break;
}


$REX['ADDON']['rxid'][$mypage] = '';
$REX['ADDON']['name'][$mypage] = rex_i18n::msg('redaxo5_title');

// Credits
$REX['ADDON']['version'][$mypage]     = '0.0';
$REX['ADDON']['author'][$mypage]      = 'blumbeet - web.studio';
$REX['ADDON']['supportpage'][$mypage] = '';
$REX['ADDON']['perm'][$mypage]        = 'admin[]';

if (count($REX['ADDON']['REDAXO5']['AUTOLOAD']) > 0) {
    foreach ($REX['ADDON']['REDAXO5']['AUTOLOAD'] as $dir) {
        rex_autoload::addDirectory(rex_path::addon($dir . '/lib'));
        rex_autoload::addDirectory(rex_path::addon($dir . '/vendor'));

        rex_fragment::addDirectory(rex_path::addon($dir . '/fragments'));

        rex_i18n::addDirectory(rex_path::addon($dir . '/lang'));
    }
}
