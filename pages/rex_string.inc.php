<?php

/**
 *
 * @author blumbeet - web.studio
 * @author Thomas Blum
 * @author mail[at]blumbeet[dot]com Thomas Blum
 *
 */

$class   = 'rex_string';
$filter  = array('yamlEncode', 'yamlDecode', 'pluginData');

$c = new redaxo5($class);
$c->addFilter($filter);
$c->addReplace($replace);


echo '
    <div class="rex-addon-output">
        <div class="redaxo5-class">' . implode('', $c->getFormatted()) . '</div>
    </div>';
