<?php
/**
 * @author: thomas.blum
 */


class redaxo5
{

    private
        $class,
        $ellipsize,
        $filter,
        $regexp,
        $replace,
        $scheme;

    public function __construct($class)
    {
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

                $return[] = '<dl><dt>' . $method['call'] . '</dt><dd>' . $method['return'] . '</dd></dl>';
            }
        }
        return $return;
    }

    public function get()
    {
        $r = array();

        $class   = new ReflectionClass( $this->class );
        $methods = $class->getMethods();

        foreach ($methods as $method) {

            if (in_array($method->getName(), $this->filter)) {
                continue;
            }

            $parameters = $method->getParameters();
            $echo_parameters = array();
            $set_parameters  = array();
            foreach ($parameters as $parameter) {
                $default = '';
                if (isset($this->replace[ $parameter->getName() ])) {
                    $default = ' = ' . var_export($this->replace[ $parameter->getName() ], true);
                    $set_parameters[] = $this->replace[ $parameter->getName() ];
                } elseif ($parameter->isOptional()) {
                    $default = ' = ' . var_export($parameter->getDefaultValue(), true);
                }

                $echo_parameters[] = str_replace(array('[:parameter:]', '[:value:]'), array($parameter->getName(), $default), $this->scheme['parameters']);
            }

            $return = '';
            $name = $method->getName();
            if (is_array($set_parameters)) {
                $return = call_user_func_array(array($this->class, $name), $set_parameters);
            }

            if (is_array($return)) {
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
            $m['name']        = $name;
            $m['parameters']  = implode(', ', $echo_parameters);
            $m['return']      = $return;

            $r[] = array('call'     => str_replace(array('[:class:]', '[:method:]', '[:parameters:]'), array($m['class'], $m['name'], $m['parameters']), $this->scheme['call']),
                         'return'   => str_replace('[:return:]', $m['return'], $this->scheme['return']));

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
