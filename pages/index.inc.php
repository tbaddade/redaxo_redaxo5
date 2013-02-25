<?php

/**
 *
 * @author blumbeet - web.studio
 * @author Thomas Blum
 * @author mail[at]blumbeet[dot]com Thomas Blum
 *
 */
$subpage = rex_request('subpage', 'string');
$subpage = $subpage != '' ? $subpage : 'autoload';

$subpages = array();
if ($REX_USER->isValueOf('rights', 'admin[]')) {
    $subpages[] = array(''              , rex_i18n::msg('redaxo5_autoload'));
    $subpages[] = array('rex_path'      , 'rex_path');
    $subpages[] = array('rex_url'       , 'rex_url');
    $subpages[] = array('rex_string'    , 'rex_string');
    $subpages[] = array('rex_fragment'  , 'rex_fragment');
    $subpages[] = array('rex_i18n'      , 'rex_i18n');
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
        white-space: nowrap;
    }

    .redaxo5-class dl {
        margin-top: -1px;
        border-top: 1px solid #CBCBCB;
        font-size: 11px;
    }
    .redaxo5-class dt,
    .redaxo5-class dd {
        padding: 10px 5px;
        font-family: Menlo, Monaco, monospace;
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


$docs = array(

    'rex_fragment'  => array(
        'replace'       => array(
            'name'          => 'redaxo5',
            'path'          => redaxo5_expandable(rex_path::base(), rex_path::addon('redaxo5/fragments')),
            'decorate'      => array('filename' => 'redaxo5.tpl', 'params' => array()),
            'subfragment'   => array('filename' => 'redaxo5.tpl', 'params' => array()),
            'i18n'          => array('key' => 'redaxo5_title'),
            'parse'         => array('filename' => 'redaxo5.tpl'),
            'setVar'        => array('name' => 'redaxo5', 'value' => 'REDAXO 4.5'),
        ),

    ),
    'rex_i18n'      => array(
        'filter'        => array(
            '__get',
            '__isset',
        ),
        'replace'       => array(
            'locale'            => 'de_DE',
            'dir'               => redaxo5_expandable(rex_path::base(), rex_path::addon('addoff/lang')),
            'key'               => 'redaxo5_i18n_key',
            'addMsg'            => array('key' => 'redaxo5_i18n_new_key', 'msg' => rex_i18n::msg('redaxo5_i18n_addMsg')),
            'text'              => 'translate:redaxo5_title',
            'translateArray'    => array('array' => array('translate:redaxo5_title', 'translate:redaxo5_i18n_new_key')),
        ),
    ),
    'rex_path'      => array(
        'filter'        => array(
            'init',
            'addonData',
            'pluginData',
        ),
        'regex'         => array(
            'pattern'       => '@(' . preg_quote( rtrim(rex_path::base(), '/'), '@') . ')@',
            'replace'       => '<span class="redaxo5-expandable">$1</span>',
        ),
        'replace'       => array(
            'addon'         => '<b>addoff</b>',
            'plugin'        => '<b>plugout</b>',
            //'file'        => '<b>bullet.php</b>',
            'relPath'       => $REX['HTDOCS_PATH'],
        ),
        'scheme'        => array(
            'call'          => '[:class:]::<b>[:method:]</b>([:parameters:])',
            'return'        => '[:return:]',
            'parameters'    => '<b>$[:parameter:]</b> [:value:]',
        ),
    ),
    'rex_string'    => array(
        'filter'        => array(
            'yamlEncode',
            'yamlDecode',
            'pluginData',
        ),
        'replace'       => array(
            'attributes'    => array('id' => 'rex-id', 'class' => 'rex-class'),
            'params'        => array('article_id' => 1, 'clang' => 0),
            'string'        => 'REDAXO Version 4.5',
            'version1'      => '4.4.2',
            'version2'      => '4.5.0',
        ),
    ),
    'rex_url'       => array(
        'filter'        => array(
            'currentBackendPage',
        ),
        'replace'       => array(
            'addon'         => 'addoff',
            'plugin'        => 'plugout',
            'backend'       => redaxo5_expandable(rex_path::base(), rex_path::backend()),
            'htdocs'        => $REX['HTDOCS_PATH'],
            'page'          => 'butler',
            'params'        => array('article_id' => 1, 'clang' => 0),
        ),
    ),
);



switch ($subpage) {
        case 'autoload':
            // Autoload
            rex_title(rex_i18n::msg('redaxo5_title') . ' :: ' . rex_i18n::msg('redaxo5_autoload'), $subpages);
            include $REX['INCLUDE_PATH'] . '/addons/redaxo5/pages/' . $subpage . '.php';
            break;
        default:
            rex_title(rex_i18n::msg('redaxo5_title') . ' :: ' . $subpage, $subpages);
            include $REX['INCLUDE_PATH'] . '/addons/redaxo5/pages/docs.php';
            break;
}

require $REX['INCLUDE_PATH'] . '/layout/bottom.php';
