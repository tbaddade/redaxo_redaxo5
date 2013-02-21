<?php

$dont_show = array('init', 'addonData', 'pluginData');

$methods_parameters = array(
    'addon'   => '<b>ADDON</b>',
    'plugin'  => '<b>PLUGIN</b>',
    'file'    => '<b>FILE</b>',
    'relPath' => $REX['HTDOCS_PATH'],
);


$class = new ReflectionClass('rex_path');
$methods = $class->getMethods();

$echo = array();
foreach ($methods as $method) {

    if (in_array($method->getName(), $dont_show)) {
        continue;
    }

    $parameters = $method->getParameters();
    $echo_parameters = array();
    $set_parameters  = array();
    foreach ($parameters as $parameter) {
        $default = '';
        if ($parameter->isOptional()) {
            $default = ' = ' . var_export($parameter->getDefaultValue(), true);
        } elseif (isset($methods_parameters[ $parameter->getName() ])) {
            $set_parameters[] = $methods_parameters[ $parameter->getName() ];
        }

        $echo_parameters[] = '$' . $parameter->getName() . $default;
    }

    $cell = '';
    $name   = $method->getName();
    if (is_array($set_parameters)) {
        switch (count($set_parameters)) {
            case '3':
                $cell = rex_path::$name($set_parameters[0], $set_parameters[1], $set_parameters[2]);
                break;
            case '2':
                $cell = rex_path::$name($set_parameters[0], $set_parameters[1]);
                break;
            case '1':
                $cell = rex_path::$name($set_parameters[0]);
                break;
            default :
                $cell = rex_path::$name();
                break;
        }
        $cell = preg_replace('@(' . rtrim(rex_path::base(), '/') . ')@', '<span class="redaxo5-expandable">$1</span>', $cell);
    }
    $echo[] = '<th>rex_path::' . $name . '(' . implode(', ', $echo_parameters) . ')</th><td>' . $cell . '</td>';
}


echo '


<style type="text/css">
  .redaxo5-expandable {
    display: inline-block;
    width: 100px;
    overflow: hidden;
    text-overflow: ellipsis;
  }
</style>


    <div class="rex-addon-output">
        <table class="rex-table">
            <thead>
                <tr>
                    <th>' . rex_i18n::msg('redaxo5_path_header_call') . '</th>
                    <th>' . rex_i18n::msg('redaxo5_path_header_return') . '</th>
                </tr>
            </thead>
            <tbody>
                <tr>' . implode('</tr><tr>', $echo) . '</tr>
            </tbody>
        </table>
    </div>';
