<?php
class PS {
    private static $_pspell_instance;

    public static function get_pspell()
    {
        if (!isset(self::$_pspell_instance)) {
            self::$_pspell_instance = pspell_new('en');
        }
        return self::$_pspell_instance;
    }
}


class SpellCheck {
    private $_ps;

    function __construct()
    {
        $this->_ps = PS::get_pspell();
    }

    function check($data)
    {
        $recognized = array();
        if (is_string($data)) {
            $data = array($data);
        }
        foreach ($data as $word) {
            if (pspell_check($this->_ps, $word)) {
                $recognized[] = $word;
            }
        }
        return $recognized;
    }
}
?>
