<?php

/**
 *
 * @author blumbeet - web.studio
 * @author Thomas Blum
 * @author mail[at]blumbeet[dot]com Thomas Blum
 *
 */

$class   = 'rex_path';
$filter  = array('init', 'addonData', 'pluginData');

$c = new redaxo5($class);
$c->addFilter($filter);
$c->addReplace($replace[$class]);
$c->setRegexp('@(' . preg_quote( rtrim(rex_path::base(), '/'), '@') . ')@', '<span class="redaxo5-expandable">$1</span>');
$c->setScheme(array(
                    'call'          => '[:class:]::<b>[:method:]</b>([:parameters:])',
                    'return'        => '[:return:]',
                    'parameters'    => '<b>$[:parameter:]</b> [:value:]',
                ));

echo '
    <div class="rex-addon-output">
        <div class="redaxo5-class">' . implode('', $c->getFormatted()) . '</div>
    </div>';
