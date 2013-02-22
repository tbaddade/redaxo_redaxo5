<?php

/**
 *
 * @author blumbeet - web.studio
 * @author Thomas Blum
 * @author mail[at]blumbeet[dot]com Thomas Blum
 *
 */
$subpage = rex_request('subpage', 'string', 'autoload');

$subpages = array();
if ($REX_USER->isValueOf('rights', 'admin[]')) {
    $subpages[] = array(''          , rex_i18n::msg('redaxo5_autoload'));
    $subpages[] = array('rex_path'  , rex_i18n::msg('redaxo5_rex_path'));
    $subpages[] = array('rex_string', rex_i18n::msg('redaxo5_rex_string'));
    $subpages[] = array('rex_url'   , rex_i18n::msg('redaxo5_rex_url'));
}


$mypage = 'redaxo5';

require $REX['INCLUDE_PATH'] . '/layout/top.php';

echo '
<style type="text/css">
  .redaxo5-expandable {
    display: inline-block;
    width: 150px;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .redaxo5-class dt,
  .redaxo5-class dd {
    padding: 10px 5px;
  }
  .redaxo5-class dt {
    display: block;
    background-color: #DFE9E9;
  }
  .redaxo5-class dd {
    display: inline-block;
    margin: 0 0 20px 0;
    padding-left: 20px;
  }
  .redaxo5-class dd:before {
    display: inline-block;
    content: "return";
    margin-bottom: 5px;
    padding-right: 5px;
    font-weight: normal;
  }
</style>';


$replace = array(
    'addon'         => '<b>addoff</b>',
    'plugin'        => '<b>plugout</b>',
    //'file'        => '<b>bullet.php</b>',
    'page'          => 'butler',
    'relPath'       => $REX['HTDOCS_PATH'],
    'htdocs'        => $REX['HTDOCS_PATH'],
    'backend'       => rex_path::backend(),
    'string'        => 'REDAXO Version 4.5',
    'version1'      => '4.4.2',
    'version2'      => '4.5.0',
    'params'        => array('article_id' => 1, 'clang' => 0),
    'attributes'    => array('id' => 'rex-id', 'class' => 'rex-class'),
);


switch ($subpage) {
    case 'autoload':
        // Autoload
        rex_title(rex_i18n::msg('redaxo5_title') . ' :: ' . rex_i18n::msg('redaxo5_autoload'), $subpages);
        include $REX['INCLUDE_PATH'] . '/addons/redaxo5/pages/autoload.inc.php';
        break;
    default:
        rex_title(rex_i18n::msg('redaxo5_title') . ' :: ' . rex_i18n::msg('redaxo5_' . $subpage), $subpages);
        include $REX['INCLUDE_PATH'] . '/addons/redaxo5/pages/' . $subpage . '.inc.php';
        break;
}

require $REX['INCLUDE_PATH'] . '/layout/bottom.php';