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
);
// --- /DYN


$mypage = 'redaxo5';

$basedir = __DIR__;

// REDAXO 5 Hilfsklassen
require_once $basedir . '/vendor/redaxo5/lib/util/path.php';

// absoluten Pfad initialisieren
rex_path::init($REX['HTDOCS_PATH'], 'redaxo');

require_once $basedir . '/vendor/redaxo5/lib/autoload.php';
rex_autoload::register();
rex_autoload::addDirectory(rex_path::addon($mypage, 'lib'));
rex_autoload::addDirectory(rex_path::addon($mypage, 'vendor'));

rex_fragment::addDirectory(rex_path::addon($mypage, 'fragments'));

rex_i18n::addDirectory(rex_path::addon($mypage, 'lang'));

// relativen Pfad initialisieren
rex_url::init($REX['HTDOCS_PATH'], rex_path::backend());


// start timer at the very beginning
rex::setProperty('timer', new rex_timer);
// add backend flag to rex
rex::setProperty('redaxo', $REX['REDAXO']);

// ----------------- VERSION
rex::setProperty('version', $REX['VERSION']);
rex::setProperty('subversion', $REX['SUBVERSION']);
rex::setProperty('minorversion', $REX['MINORVERSION']);

// aus der config.yml uebernommen
rex::setProperty('table_prefix', $REX['TABLE_PREFIX']);
rex::setProperty('temp_prefix', $REX['TEMP_PREFIX']);
rex::setProperty('accesskeys', $REX['ACKEY']);
rex::setProperty('use_accesskeys', true);


if (rex::isBackend() && $REX['USER']) {
    rex::setProperty('user', $REX['USER']);
}


// data Ordner erstellen -> /redaxo/data
$data_dir = rtrim(rex_path::data(), DIRECTORY_SEPARATOR);
if(!is_dir($data_dir)) {
    $src_dir = rex_path::core('_tmp/data');
    rex_dir::copy($src_dir, $data_dir);
}


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
        rex_autoload::addDirectory(rex_path::addon($dir, 'lib'));
        rex_autoload::addDirectory(rex_path::addon($dir, 'vendor'));

        rex_fragment::addDirectory(rex_path::addon($dir, 'fragments'));

        rex_i18n::addDirectory(rex_path::addon($dir, 'lang'));
    }
}
