<?php
/**
 * @author: thomas.blum
 */
/*
class rex_reflection_class
{

    public function __construct($class)
    {
        $this->name = $class;
    }


}

class rex_reflection_method
{

    public function __construct($method)
    {
        $this->name = $method;
    }

    public function getMagicMethods()
    {

    }

}
*/

class redaxo5
{

    private
        $args,
        $class,
        $ellipsize,
        $filter,
        $regexp,
        $replace,
        $scheme;

    public function __construct($class, array $args = array())
    {
        $this->args      = $args;
        $this->class     = $class;
        $this->ellipsize = array();
        $this->filter    = array();
        $this->regexp    = array();
        $this->replace   = array();
        $this->setScheme();
    }

    public function setEllipsize($max_length, $position = 1, $ellipsis = '&hellip;')
    {
        $this->ellipsize = array($max_length, $position, $ellipsis);
    }

    public function setRegexp($pattern, $replace)
    {
        $this->regexp = array($pattern, $replace);
    }

    public function setScheme(array $scheme = array())
    {

        if (!isset($scheme['call']) && !isset($scheme['return']) && !isset($scheme['parameters']))
            $scheme = array(
                'call'          => '[:class:]::<b>[:method:]</b>([:parameters:])',
                'return'        => '<b>[:return:]</b>',
                'parameters'    => '<b>$[:parameter:]</b> [:value:]',
            );

        $this->scheme = $scheme;
    }

    public function addFilter(array $filter)
    {
        $this->filter = $filter;
    }

    public function addReplace(array $replace)
    {
        $this->replace = $replace;
    }

    public function getFormatted()
    {
        $return  = array();
        $methods = $this->get();

        if (count($methods) > 0) {
            foreach ($methods as $method) {

                $return[] = '<dl><dt>' . $method['call'] . '</dt><dd ' . (strip_tags($method['return']) != '' ? ' class="redaxo5-return"' : '') . '>' . $method['return'] . '</dd></dl>';
            }
        }
        return $return;
    }

    public function get()
    {
        $r = array();

        $class    = new ReflectionClass($this->class);
        $instance = $class->newInstanceArgs($this->args);
        $methods  = $class->getMethods();

        foreach ($methods as $method) {

            $method_name = $method->getName();
            if (in_array($method_name, $this->filter)) {
                continue;
            }

            $scheme = $this->scheme;
            $parameters = $method->getParameters();
            $echo_parameters = array();
            $set_parameters  = array();
            foreach ($parameters as $parameter) {
                $parameter_name = $parameter->getName();
                $default = '';
                if (isset($this->replace[$parameter_name]) || isset($this->replace[$method_name][$parameter_name]) ) {

                    if (isset($this->replace[$method_name][$parameter_name])) {
                        $param = $this->replace[$method_name][$parameter_name];
                    } else {
                        $param = $this->replace[$parameter_name];
                    }

                    $default = ' = ' . var_export($param, true);
                    $set_parameters[] = $param;
                } elseif ($parameter->isOptional()) {
                    $default = ' = ' . var_export($parameter->getDefaultValue(), true);
                }

                $echo_parameters[] = trim(str_replace(array('[:parameter:]', '[:value:]'), array($parameter_name, $default), $scheme['parameters']));
            }

            if ($method->isPrivate() || $method->isProtected()) {
                continue;
            } elseif ($method->isStatic()) {
                $return = call_user_func_array(array($this->class, $method_name), $set_parameters);
            } elseif ($method->isConstructor()) {
                $scheme['call'] = str_replace(array('[:class:]::', '[:method:]'), array('$instance = new ' . $this->class, ''), $scheme['call']);
                $return = call_user_func_array(array($instance, $method_name), $set_parameters);
            } elseif ($method->isPublic()) {
                $scheme['call'] = str_replace('[:class:]::', '$instance->', $scheme['call']);
                $return = call_user_func_array(array($instance, $method_name), $set_parameters);
            } else {
                continue;
            }

            if (is_array($return) || is_object($return)) {
                $return = var_export($return, true);
            }

            if ($return != '') {
                $return = htmlspecialchars_decode($return);
                if (count($this->regexp) == 2) {
                    $return = preg_replace($this->regexp[0], $this->regexp[1], $return);
                }

                if (count($this->ellipsize) == 3) {
                    $return = redaxo5_helper::ellipsize($return, $this->ellipsize[0], $this->ellipsize[1], $this->ellipsize[2]);
                }
            }

            $m = array();
            $m['class']       = $this->class;
            $m['name']        = $method_name;
            $m['parameters']  = implode(', ', $echo_parameters);
            $m['return']      = $return;

            $r[] = array(
                'call'     => str_replace(array('[:class:]', '[:method:]', '[:parameters:]'), array($m['class'], $m['name'], $m['parameters']), $scheme['call']),
                'return'   => str_replace('[:return:]', $m['return'], $scheme['return'])
            );

        }

        return $r;
    }
}

class redaxo5_helper
{
    /**
     * Ellipsize String
     *
     * This function will strip tags from a string, split it at its max_length and ellipsize
     *
     * @param    string    string to ellipsize
     * @param    int       max length of string
     * @param    mixed     int (1|0) or float, .5, .2, etc for position to split
     * @param    string    ellipsis ; Default '...'
     * @return   string    ellipsized string
     */
    public static function ellipsize($str, $max_length, $position = 1, $ellipsis = '&hellip;')
    {
        // Strip tags
        $str = trim(strip_tags($str));

        // Is the string long enough to ellipsize?
        if (strlen($str) <= $max_length) {
            return $str;
        }

        $beg = substr($str, 0, floor($max_length * $position));
        $position = ($position > 1) ? 1 : $position;

        if ($position === 1) {
            $end = substr($str, 0, -($max_length - strlen($beg)));
        } else {
            $end = substr($str, -($max_length - strlen($beg)));
        }

        return $beg . $ellipsis . $end;
    }
}
