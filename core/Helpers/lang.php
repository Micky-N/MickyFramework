<?php

if(!function_exists('get_plural')){
    function get_plural(string $word){
        $plural = json_decode(file_get_contents(ROOT.'lang/plural_word.json'), true);
        if($plural){
            return $plural[$word] ?: false;
        }
        return false;
    }
}

if(!function_exists('get_singular')){
    function get_singular(string $word){
        $plural = json_decode(file_get_contents(ROOT.'lang/plural_word.json'), true);
        if($plural){
            return array_search($word, $plural);
        }
        return false;
    }
}