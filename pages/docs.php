<?php

/**
 *
 * @author blumbeet - web.studio
 * @author Thomas Blum
 * @author mail[at]blumbeet[dot]com Thomas Blum
 *
 */

$class = $subpage;
if (isset($docs[$class])) {

    $doc = $docs[$class];

    $c = new redaxo5($class);

    if (isset($doc['filter']) && count($doc['filter']) > 0) {
        $c->addFilter(($doc['filter']));
    }

    if (isset($doc['regex']) && count($doc['regex']) == 2) {
        $c->setRegexp($doc['regex']['pattern'], $doc['regex']['replace']);
    }

    if (isset($doc['replace']) && count($doc['replace']) > 0) {
        $c->addReplace($doc['replace']);
    }

    if (isset($doc['scheme']) && count($doc['scheme']) > 0) {
        $c->setScheme(($doc['scheme']));
    }

    echo '
    <div class="rex-addon-output">
        <div class="redaxo5-class">' . implode('', $c->getFormatted()) . '</div>
    </div>';

}
else {
    echo rex_view::warning('redaxo5_no_documentation_found');
}