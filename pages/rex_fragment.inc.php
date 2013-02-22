<?php

/**
 *
 * @author blumbeet - web.studio
 * @author Thomas Blum
 * @author mail[at]blumbeet[dot]com Thomas Blum
 *
 */

$class   = 'rex_fragment';
$filter  = array('__isset', '__get');

$c = new redaxo5($class);
$c->addFilter($filter);
$c->addReplace($replace[$class]);

echo '
    <div class="rex-addon-output">
        <div class="redaxo5-class">' . implode('', $c->getFormatted()) . '</div>
    </div>';
