<?php

namespace Core\Validate;

class Validator
{

    public static function check(array $data, array $rules)
    {
        $rules = new Rules($data, $rules);
        foreach ($data as $key => $d) {
            $data[$key] = $rules->checkRule($key, $d);
        }
        if(!empty($rules->getErrors())){
            
            return redirectBack();
        }
        return $data;
    }
}
