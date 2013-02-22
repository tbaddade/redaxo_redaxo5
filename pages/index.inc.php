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
    $subpages[] = array(''              , rex_i18n::msg('redaxo5_autoload'));
    $subpages[] = array('rex_path'      , rex_i18n::msg('redaxo5_rex_path'));
    $subpages[] = array('rex_string'    , rex_i18n::msg('redaxo5_rex_string'));
    $subpages[] = array('rex_url'       , rex_i18n::msg('redaxo5_rex_url'));
    $subpages[] = array('rex_fragment'  , rex_i18n::msg('redaxo5_rex_fragment'));
}

if (!function_exists('redaxo5_expandable')) {
    function redaxo5_expandable($string, $subject) {
        return preg_replace('@(' . preg_quote( rtrim($string, '/'), '@') . ')@', '<span class="redaxo5-expandable">$1</span>', $subject);
    }
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

    .redaxo5-class dl {
        margin-top: -1px;
        border-top: 1px solid #CBCBCB;
        font-size: 12px;
    }
    .redaxo5-class dt,
    .redaxo5-class dd {
        padding: 10px 5px;
        font-family: monospace;
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
    .redaxo5-class dd.redaxo5-return:before {
        display: inline-block;
        content: "return";
        margin-bottom: 5px;
        padding-right: 5px;
        font-weight: normal;
    }
</style>';


$replace = array(

    'rex_path' => array(
        'addon'         => '<b>addoff</b>',
        'plugin'        => '<b>plugout</b>',
        //'file'        => '<b>bullet.php</b>',
        'relPath'       => $REX['HTDOCS_PATH'],
    ),
    'rex_string' => array(
        'attributes'    => array('id' => 'rex-id', 'class' => 'rex-class'),
        'params'        => array('article_id' => 1, 'clang' => 0),
        'string'        => 'REDAXO Version 4.5',
        'version1'      => '4.4.2',
        'version2'      => '4.5.0',
    ),
    'rex_url' => array(
        'addon'         => 'addoff',
        'plugin'        => 'plugout',
        'backend'       => redaxo5_expandable(rex_path::base(), rex_path::backend()),
        'htdocs'        => $REX['HTDOCS_PATH'],
        'page'          => 'butler',
        'params'        => array('article_id' => 1, 'clang' => 0),
    ),
    'rex_fragment' => array(
        'name'          => 'redaxo5',
        'path'          => redaxo5_expandable(rex_path::base(), rex_path::addon('redaxo5/fragments')),
        'decorate'      => array('filename' => 'redaxo5.tpl', 'params' => array()),
        'subfragment'   => array('filename' => 'redaxo5.tpl', 'params' => array()),
        'i18n'          => array('key' => 'redaxo5_title'),
        'parse'         => array('filename' => 'redaxo5.tpl'),
        'setVar'        => array('name' => 'redaxo5', 'value' => 'REDAXO 4.5'),

    ),
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
