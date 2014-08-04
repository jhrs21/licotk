<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Esta clase abarca metodos comunes a muchas clases/objetos
 *
 * @author Jacobo Martínez
 */
class Util {

    const CHAR_MIX = 0;
    const CHAR_NUM = 1;
    const CHAR_WORD = 2;

    /**
     * Genera una cadena de caracteres de longitud $len con caracteres alfanuméricos ($type = 0),
     * numéricos ($type = 1) o alfabéticos ($type = 2)
     * 
     * @param int $len
     * @param int $type Los valores admitidos son 0 (caracteres mixtos), 1 (caracteres numéricos) y 2 (caracteres alfabéticos)
     * @param string $chars Si <i>$chars</i> es vacio, la cadena '123456789BCDFGHJKLMNPQRSTWXYZ' es utilizada por defecto
     * 
     * @return string 
     */
    static function GenSecret($len = 8, $type = self::CHAR_WORD, $chars = '') {
        mt_srand(self::make_seed());

        $secret = '';

        if (strlen($chars) == 0) {
            $chars = '123456789BCDFGHJKLMNPRSTXYZ';
        }

        $limit = strlen($chars) - 1;

        for ($i = 0; $i < $len; $i++) {
            if (self::CHAR_NUM == $type) {
                if (0 == $i) {
                    $secret .= chr(mt_rand(49, 57));
                } else {
                    $secret .= chr(mt_rand(48, 57));
                }
            } else if (self::CHAR_WORD == $type) {
                $secret .= chr(mt_rand(65, 90));
            } else {
                $secret .= $chars[mt_rand(0, $limit)];

// esto es lo que originalmente definia la función
//                if (0 == $i)
//                {
//                    $secret .= chr(rand(65, 90));
//                } 
//                else 
//                {
//                    $secret .= (0 == rand(0,1)) ? chr(rand(65, 90)) : chr(rand(49,57));
//                }
            }
        }
        mt_srand(); //RETORNA EL RAND A UN SEED ALEATORIO

        return $secret;
    }

    /**
     * Genera un cadena de caracteres única
     * 
     * @param string $salt
     * @param integer $len
     * @return string 
     */
    public static function gen_uuid($salt = '', $len = 10) {
        if (!strlen($salt)) {
            $salt = '0504c69064e0f07fd1dfffd1012e5a5f67ca37b4f6052559eb7eb883ae1af936';
        }

        $hex = md5($salt . uniqid("", true));

        $pack = pack('H*', $hex);

        $tmp = base64_encode($pack);

        $uid = preg_replace("#(*UTF8)[^A-Za-z0-9]#", "", $tmp);

        $len = max(4, min(128, $len));

        while (strlen($uid) < $len) {
            $uid .= self::gen_uuid('', 22);
        }

        return substr($uid, 0, $len);
    }

    /**
     * Comprueba si una variable es un arreglo asociativo.
     * Retorna true en caso de que el arreglo sea asociativo "puro", esto incluye arreglos vacíos, false en caso contrario
     * 
     * @param mixed $array
     * @return boolean
     */
    public static function is_assoc_array($arr) {
        return (is_array($arr) && count(array_filter(array_keys($arr), 'is_string')) == count($arr));
    }

    // Append associative array elements
    public static function array_push_associative(&$arr) {
        $ret = 0;
        $args = func_get_args();
        foreach ($args as $arg) {
            if (is_array($arg)) {
                foreach ($arg as $key => $value) {
                    $arr[$key] = $value;
                    $ret++;
                }
            } else {
                $arr[$arg] = "";
            }
        }
        return $ret;
    }

    public static function extractFromCSV($file) {
        $arrResult = array();
        $arrLines = file($file);
        foreach ($arrLines as $line) {
            array_push($arrResult, $line);
        }
        return $arrResult;
    }

    // seed with microseconds
    public static function make_seed() {
        return (double) microtime() * 1000003;
    }

    /**
     * Translates a number to a short alhanumeric version
     *
     * Translated any number up to 9007199254740992
     * to a shorter version in letters e.g.:
     * 9007199254740989 --> PpQXn7COf
     *
     * specifiying the second argument true, it will
     * translate back e.g.:
     * PpQXn7COf --> 9007199254740989
     *
     * this function is based on any2dec && dec2any by
     * fragmer[at]mail[dot]ru
     * see: http://nl3.php.net/manual/en/function.base-convert.php#52450
     *
     * If you want the alphaID to be at least 3 letter long, use the
     * $pad_up = 3 argument
     *
     * In most cases this is better than totally random ID generators
     * because this can easily avoid duplicate ID's.
     * For example if you correlate the alpha ID to an auto incrementing ID
     * in your database, you're done.
     *
     * The reverse is done because it makes it slightly more cryptic,
     * but it also makes it easier to spread lots of IDs in different
     * directories on your filesystem. Example:
     * $part1 = substr($alpha_id,0,1);
     * $part2 = substr($alpha_id,1,1);
     * $part3 = substr($alpha_id,2,strlen($alpha_id));
     * $destindir = "/".$part1."/".$part2."/".$part3;
     * // by reversing, directories are more evenly spread out. The
     * // first 26 directories already occupy 26 main levels
     *
     * more info on limitation:
     * - http://blade.nagaokaut.ac.jp/cgi-bin/scat.rb/ruby/ruby-talk/165372
     *
     * if you really need this for bigger numbers you probably have to look
     * at things like: http://theserverpages.com/php/manual/en/ref.bc.php
     * or: http://theserverpages.com/php/manual/en/ref.gmp.php
     * but I haven't really dugg into this. If you have more info on those
     * matters feel free to leave a comment.
     *
     * @author  Kevin van Zonneveld <kevin@vanzonneveld.net>
     * @author  Simon Franz
     * @author  Deadfish
     * @copyright 2008 Kevin van Zonneveld (http://kevin.vanzonneveld.net)
     * @license   http://www.opensource.org/licenses/bsd-license.php New BSD Licence
     * @version   SVN: Release: $Id: alphaID.inc.php 344 2009-06-10 17:43:59Z kevin $
     * @link    http://kevin.vanzonneveld.net/
     *
     * @param mixed   $in    String or long input to translate
     * @param boolean $to_num  Reverses translation when true
     * @param mixed   $pad_up  Number or boolean padds the result up to a specified length
     * @param string  $passKey Supplying a password makes it harder to calculate the original ID
     *
     * @return mixed string or long
     */
    public static function alphaID($in, $to_num = false, $pad_up = false, $passKey = null, $index = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ") {
        if ($passKey !== null) {
            // Although this function's purpose is to just make the
            // ID short - and not so much secure,
            // with this patch by Simon Franz (http://blog.snaky.org/)
            // you can optionally supply a password to make it harder
            // to calculate the corresponding numeric ID

            for ($n = 0; $n < strlen($index); $n++) {
                $i[] = substr($index, $n, 1);
            }

            $passhash = hash('sha256', $passKey);
            $passhash = (strlen($passhash) < strlen($index)) ? hash('sha512', $passKey) : $passhash;

            for ($n = 0; $n < strlen($index); $n++) {
                $p[] = substr($passhash, $n, 1);
            }

            array_multisort($p, SORT_DESC, $i);
            $index = implode($i);
        }

        $base = strlen($index);

        if ($to_num) {
            // Digital number  <<--  alphabet letter code
            $in = strrev($in);
            $out = 0;
            $len = strlen($in) - 1;
            for ($t = 0; $t <= $len; $t++) {
                $bcpow = bcpow($base, $len - $t);
                $out = $out + strpos($index, substr($in, $t, 1)) * $bcpow;
            }

            if (is_numeric($pad_up)) {
                $pad_up--;
                if ($pad_up > 0) {
                    $out -= pow($base, $pad_up);
                }
            }
            $out = sprintf('%F', $out);
            $out = substr($out, 0, strpos($out, '.'));
        } else {
            // Digital number  -->>  alphabet letter code
            if (is_numeric($pad_up)) {
                $pad_up--;
                if ($pad_up > 0) {
                    $in += pow($base, $pad_up);
                }
            }

            $out = "";
            for ($t = floor(log($in, $base)); $t >= 0; $t--) {
                $bcp = bcpow($base, $t);
                $a = floor($in / $bcp) % $base;
                $out = $out . substr($index, $a, 1);
                $in = $in - ($a * $bcp);
            }
            $out = strrev($out); // reverse
        }

        return $out;
    }

    /**
     *  Given a web accessible file, i.e. /css/base.css, replaces it with a string containing the
     *  file's mtime, i.e. /css/base.1221534296.css.
     *  
     *  @param $file  The file to be loaded.  Must be an absolute path (i.e. starting with slash).
     * 
     */
    public static function auto_version($file) {
        if (strpos($file, '/') !== 0 || !file_exists(sfConfig::get('sf_web_dir') . $file))
            return $file;

        $mtime = filemtime(sfConfig::get('sf_web_dir') . $file);
        return preg_replace('{\\.([^./]+)$}', ".$mtime.\$1", $file);
    }

    static public function slugify($text) {
        // replace non letter or digits by -
        $text = preg_replace('#[^\\pL\d]+#u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        if (function_exists('iconv')) {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('#[^-\w]+#', '', $text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

}