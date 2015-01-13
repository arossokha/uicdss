<?php


class Util
{
    public static function duplicateArray($arr,$c = 1) {
        $result = $arr;
        for( $i = 0 ; $i < $c-1 ; $i++ ) {
            foreach ($arr as $val) {
                $result[] = $val;
            }
        }

        return $result;
    }

}