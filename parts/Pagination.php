<?php

namespace parts;

class Pagination {

    private static $_page, $_countPages, $_collPages;

    /** 
     * @param array $arr
     * @param array $max number of pages to paginate
     * @return array $_page paginated data
     */ 
    public static function set($arr, $max) {

        $allNum = count($arr);
        self::$_countPages = ceil($allNum/$max);

        if($max < $allNum) {
            for($i = 1; $i <= $max; $i++) {
                self::$_page[] = $arr[$i];
            }
        }

        if(submitted('page')) {
            for($i = 1; $i <= self::$_countPages; $i++) {
                if(get('page') == $i) {

                    $from = $i * $max - $max;
                    $to = $from + $max;
                    
                    self::$_page = array_slice($arr, $from, $to - $from);
                
                    if($from > $allNum) {
                        $number = $i * $max - $allNum;
                    }
                }
            }
        }

        return self::$_page;
    }

    /** 
     * @return array $paginated paginated numbers
     */     
    public static function getPages() {

        for($i = 1; $i <= self::$_countPages; $i++) {
            self::$_collPages[] = $i;
        }
        return self::$_collPages;
    }
}