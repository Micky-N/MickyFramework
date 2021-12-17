<?php

namespace HTML;

/**
 * Class FormHelper
 * Permet de générer un formulaire rapidement et simplement
 */
class Form
{

    /**
     * Tableau d'option class, id,...
     * @param array $options
     *
     * @return string
     */
    public static function FormOptions(array $options)
    {
        if(!empty($options)){
            return implode(' ', array_map(
                function ($v, $k) {
                    return sprintf("%s='%s'", $k, $v);
                },
                $options,
                array_keys($options)
            ));
        }
    }

    /**
     * ouvre la balise form
     * @param null $action
     * @param $method
     * @param array|null $options
     *
     * @return string
     */
    public static function open(string $action = null, string $method, array $options = null)
    {
        $options = $options ? ' '.self::FormOptions($options) : $options;
        echo "<form action='$action' method='$method'$options>";
    }

    /**
     * ferme la balise form
     * @return string
     */
    public static function close()
    {
        echo "</form>";
    }


    /**
     * créer une balise label
     * @param string $for
     * @param string $name
     * @param array|null $options
     *
     * @return string
     */
    public static function label(string $name, string $for, array $options = null)
    {
        $options = $options ? ' '.self::FormOptions($options) : $options;
        echo "<label for='$for'$options>$name</label>";
    }

    /**
     * créer une balise input type text
     * @param string $name
     * @param string|null $value
     * @param array $options
     *
     * @return string
     */
    public static function text(string $name, string $value = null, array $options = null)
    {
        $options = $options ? ' '.self::FormOptions($options) : $options;
        echo "<input type='text'$options name='$name' value='$value'>";
    }

    /**
     * créer une balise input type email
     * @param string $name
     * @param string|null $value
     * @param array $options
     *
     * @return string
     */
    public static function email(string $name, string $value = null, array $options = null)
    {
        $options = $options ? ' '.self::FormOptions($options) : $options;
        echo "<input type='email'$options name='$name' value='$value'>";
    }

    /**
     * créer une balise input type file
     * @param string $name
     * @param array $options
     *
     * @return string
     */
    public static function file(string $name, array $options = null)
    {
        $options = $options ? ' '.self::FormOptions($options) : $options;
        echo "<input type='file'$options name='$name'>";
    }

    /**
     * créer une balise input type checkbox
     * @param string $name
     * @param string $value
     * @param bool|false $checked
     * @param array $options
     *
     * @return string
     */
    public static function checkbox(string $name, string $value, bool $checked = false, array $options = null)
    {
        $options = $options ? ' '.self::FormOptions($options) : $options;
        echo "<input type='checkbox'$options name='$name'".($checked ? ' checked' : '')." value='$value'>";
    }

    /**
     * créer une balise input type radio
     * @param string $name
     * @param string $value
     * @param bool|false $checked
     * @param array $options
     *
     * @return string
     */
    public static function radio(string $name, string $value, bool $checked = false, array $options = null)
    {
        $options = $options ? ' '.self::FormOptions($options) : $options;
        echo "<input type='radio'$options name='$name'".($checked ? ' checked' : '')." value='$value'>";
    }

    /**
     * créer une balise input type password
     * @param string $name
     * @param array $options
     *
     * @return string
     */
    public static function password(string $name, array $options = null)
    {
        $options = $options ? ' '.self::FormOptions($options) : $options;
        echo "<input type='password'$options name='$name'>";
    }

    /**
     * Créer une balise textarea
     * @param string $name
     * @param string|null $value
     * @param array $options
     *
     * @return string
     */
    public static function textarea(string $name, string $value = null, array $options = null)
    {
        $options = $options ? ' '.self::FormOptions($options) : $options;
        echo "<textarea name='$name'$options>$value</textarea>";
    }

    /**
     * Créer une balise select
     * @param string $name
     * @param array $list
     * @param string $placeholder
     * @param mixed|null $selected
     * @param array|null $options
     *
     * @return string
     */
    public static function select(string $name, array $list, $selected = null, string $placeholder = '', array $options = null)
    {
        $options = $options ? ' '.self::FormOptions($options) : $options;
        $select = "<select name='$name'$options>";
        if($placeholder){
            $select .= "<option" . (is_null($selected) ? ' selected' : '') . " value=''>$placeholder</option>";
        }
        foreach ($list as $key => $value) {
            if(is_array($value)){
                $select .= "<optgroup label='$key'>";
                foreach ($value as $k => $v) {
                    $select .= "<option" . ($k == $selected ? ' selected' : '') . " value='$k'>$v</option>";
                }
                $select .= "</optgroup>";
            } else {
                $select .= "<option" . ($key == $selected ? ' selected' : '') . " value='$key'>$value</option>";
            }
        }
        $select .= "</select>";
        echo $select;
    }

    /**
     * créer une balise input type number
     * @param string $name
     * @param string|null $value
     * @param array $options
     *
     * @return string
     */
    public static function number(string $name, string $value = null, array $options = null)
    {
        $options = $options ? ' '.self::FormOptions($options) : $options;
        echo "<input type='number'$options name='$name' value='$value'>";
    }

    /**
     * créer une balise input type date
     * @param string $name
     * @param string|null $value
     * @param array $options
     *
     * @return string
     */
    public static function date(string $name, string $value = null, array $options = null)
    {
        $options = $options ? ' '.self::FormOptions($options) : $options;
        echo "<input type='date'$options name='$name' value='$value'>";
    }

    /**
     * Créer un bouton submit
     * @param string $btn
     * @param array|null $options
     * @return string
     */
    public static function submit(string $btn, array $options = null)
    {
        $options = $options ? ' '.self::FormOptions($options) : $options;
        echo "<button type='submit'$options>$btn</button>";
    }
}
