redaxo5 - AddOn für REDAXO 4.5
================================================================================


Beinhaltet einige nützliche Klassen von redaxo5.



Autoload
--------------------------------------------------------------------------------

Das AddOn kann automatisch Fragmente, Sprachdateien und Klassen von anderen AddOns laden.

Dazu muss auf der "Autoload"-Seite vom redaxo5-AddOn das eigene AddOn oder Plugin ausgewählt werden und folgende Ordnerstruktur besitzen.


### Ordnerstruktur von Fremd-AddOns ############################################

| Ordner        | Beschreibung      |
| ------------- | ----------------- |
| *fragments/*  | Fragmente         |
| *lang/*       | Sprachdateien     |
| *lib/*        | interne Klassen   |
| *vendor/*     | externe Klassen   |




Features
--------------------------------------------------------------------------------

### rex_fragment ###############################################################
--------------------------------------------------------------------------------

#### Aufruf ####################################################################

```php
<?php
$fragment = new rex_fragment();
$fragment->setVar('title', $value = 'AddOn für REDAXO 4.5', $escape = true);
$fragment->setVar('html', $value = '<p>HTML</p>', $escape = false);
$fragment->parse('redaxo5.tpl');
```


#### Fragmentinhalt von redaxo5.tpl ############################################

```php
<?php
echo $this->title . $this->html;
```






### rex_path ###################################################################
--------------------------------------------------------------------------------

rex_path wird für absolute Pfade genutzt

weitere callables im Backend vorhanden

#### Beispiele #################################################################

$file kann leer sein.

```php
$path = rex_path::backend();
> /absoluter/Pfad/redaxo/include/
```


```php
$path = rex_path::backend($file = 'file.php');
> /absoluter/Pfad/redaxo/file.php
```


```php
$path = rex_path::src($file = 'file.php');
> /absoluter/Pfad/redaxo/include/file.php
```


```php
$path = rex_path::cache($file = '');
> /absoluter/Pfad/redaxo/include/generated/files/
```


```php
$path = rex_path::media($file = 'image.jpg');
> /absoluter/Pfad/files/image.jpg
```


```php
$path = rex_path::assets($file = 'image.jpg');
> /absoluter/Pfad/files/image.jpg
```



```php
rex_path::data($file = '')
/absoluter/Pfad/redaxo/data/
```


```php
$path = rex_path::addon($addon = 'addoff', $file = '');
> /absoluter/Pfad/redaxo/include/addons/addoff/
```


```php
$path = rex_path::plugin($addon = 'addoff', $plugin = 'plugout', $file = '');
> /absoluter/Pfad/redaxo/include/addons/addoff/plugins/plugout/
```



### rex_url ####################################################################
--------------------------------------------------------------------------------

rex_url wird für relative Pfade, meist bei Ausgabe im Frontend, genutzt

callables im Backend vorhanden

#### Beispiele #################################################################

$file kann leer sein.

```php
$path = rex_url::frontend($file = '');
> ../
```


```php
$path = rex_url::frontendController($params = array ('article_id' => 1, 'clang' => 0))
> ../index.php?article_id=1&clang=0
```




### rex_string #################################################################
--------------------------------------------------------------------------------

```php
$string = rex_string::size($string = 'REDAXO Version 4.5')
> 18
```


```php
$array = rex_string::split($string = 'REDAXO Version 4.5')
> array ( 0 => 'REDAXO', 1 => 'Version', 2 => '4.5')
```


```php
$bool = rex_string::compareVersions($version1 = '4.4.2', $version2 = '4.5.0', $comparator = NULL)
> 1
```


```php
$string = rex_string::buildQuery($params = array('article_id' => 1, 'clang' => 0), $argSeparator = '&')
> article_id=1&clang=0
```


```php
$string = rex_string::buildAttributes($attributes = array ( 'id' => 'rex-id', 'class' => 'rex-class'))
> id="rex-id" class="rex-class"
```


```php
$string = rex_string::highlight($string = 'REDAXO Version 4.5')
> <p class="rex-code"><code><span style="color: #000"> REDAXO&nbsp;Version&nbsp;4.5</span> </code></p>
```


```php
$string = rex_string::widont($string = 'REDAXO Version 4.5')
> REDAXO Version&nbsp;4.5
```




### rex_i18n ###################################################################
--------------------------------------------------------------------------------

```php
rex_i18n::msg($key = 'i18n_key')
> Wert von "i18n_key"
```


```php
rex_i18n::rawMsg($key = 'i18n_html_key')
> <p>Wert von <span>"i18n_html_key"</span></p>
```




Auflistung mitgelieferter Klassen
--------------------------------------------------------------------------------

- rex [1]
- rex_exception
- rex_dir
- rex_file
- rex_finder
- rex_fragment
- rex_i18n
- rex_pager
- rex_path
- rex_socket
- rex_socket_proxy
- rex_socket_response
- rex_sortable_iterator
- rex_string
- rex_timer
- rex_type
- rex_url [1]
- rex_view [1]

[1]: nicht voll nutzbar

