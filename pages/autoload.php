<?php

/**
 *
 * @author blumbeet - web.studio
 * @author Thomas Blum
 * @author mail[at]blumbeet[dot]com Thomas Blum
 *
 */

$func = rex_request('func', 'string');

$mypage = 'redaxo5';

$error   = '';
$success = '';

if ($func == 'update') {
    $req = rex_request('redaxo5_autoload', 'array');
    $REX['ADDON']['REDAXO5']['AUTOLOAD'] = $req;

    $content = '
$REX[\'ADDON\'][\'REDAXO5\'][\'AUTOLOAD\'] = ' . var_export($REX['ADDON']['REDAXO5']['AUTOLOAD'], true) . ';
    ';

    $config_file = $REX['INCLUDE_PATH'] . '/addons/redaxo5/config.inc.php';

    if (rex_replace_dynamic_contents($config_file, $content) !== false) {
        $success = rex_i18n::msg('redaxo5_config_updated');
    } else {
        $error = rex_i18n::msg('redaxo5_config_update_failed', $config_file);
    }

}

$sel = new rex_select();
$sel->setSize(20);
$sel->setName('redaxo5_autoload[]');
$sel->setId('rex-redaxo5-autoload');
$sel->setMultiple(true);

$addons = OOAddon::getAvailableAddons();
unset($addons[array_search($mypage, $addons)]); // eigenes AddOn entfernen

foreach ($addons as $addon) {

    if (OOAddon::isActivated($addon)) {

        $title = OOAddon::getProperty($addon, 'name', '');
        $title = $title == '' ? $addon : $title;
        $value = $addon;
        $sel->addOption($title, $value);

        if (in_array($value, $REX['ADDON']['REDAXO5']['AUTOLOAD'])) {
            $sel->setSelected($value);
        }

        $plugins = OOPlugin::getAvailablePlugins($addon);
        foreach ($plugins as $plugin) {

            $title = OOPlugin::getProperty($plugin, 'name', '');
            $title = $title == '' ? $plugin : $title;
            $value = $addon . '/' . $title;
            $sel->addOption('- - - ' . $title, $value);

            if (in_array($value, $REX['ADDON']['REDAXO5']['AUTOLOAD'])) {
                $sel->setSelected($value);
            }
        }
    }
}




if ($error != '') {
    echo rex_warning($error);
}
if ($success != '') {
    echo rex_info($success);
}

echo '

    <style type="text/css">
        #rex-page-redaxo5 .rex-addon-content dl dt {
            clear: both;
            float: left;
            font-weight: bold;
        }
        #rex-page-redaxo5 .rex-addon-content dl dd {
            margin-left: 155px;
        }
    </style>


    <div class="rex-addon-output">
        <div class="rex-form">
            <form action="index.php" method="post">

                <fieldset class="rex-form-col-1">
                    <legend>' . rex_i18n::msg('redaxo5_autoload') . '</legend>

                    <div class="rex-form-wrapper">
                        <input type="hidden" name="page" value="redaxo5" />
                        <input type="hidden" name="func" value="update" />

                        <div class="rex-form-row">
                            <p class="rex-form-col-a">
                                <label for="rex-redaxo5-autoload">' . rex_i18n::msg('redaxo5_autoload_addon_plugin') . '</label>
                                ' . $sel->get() . '
                                <span class="rex-form-notice">' . rex_i18n::msg('redaxo5_autoload_addon_plugin_note') . '</span>
                            </p>
                        </div>

                    </div>

                </fieldset>

                <fieldset class="rex-form-col-1">
                    <div class="rex-form-wrapper">
                        <div class="rex-form-row">
                            <p class="rex-form-col-a rex-form-submit">
                                <input type="submit" class="rex-form-submit" value="' . rex_i18n::msg('redaxo5_save') . '" title="' . rex_i18n::msg('redaxo5_save') . '" />
                            </p>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>


    <div class="rex-addon-output">
        <h2 class="rex-hl2" style="font-size: 100%;">' . rex_i18n::msg('redaxo5_structure') . '</h2>
        <div class="rex-addon-content">
            <dl>
                <dt>fragments/</dt><dd>Fragmente</dd>
                <dt>lang/</dt><dd>Sprachdateien</dd>
                <dt>lib/</dt><dd>interne Klassen</dd>
                <dt>vendor/</dt><dd>externe Klassen</dd>
            </dl>
        </div>
    </div>';
