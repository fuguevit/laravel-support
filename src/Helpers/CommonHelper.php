<?php

namespace Fuguevit\Support\Helpers;

class CommonHelper
{
    /**
     * Return a gravatar image.
     *
     * @param $email
     * @param null $size
     * @return null
     */
    public static function gravatar($email, $size = null)
    {
        $url = 'https://www.gravatar.com/avatar/'.e(md5(strtolower($email)));
        if ($size) {
            $url .= '?s='.$size;
        }

        return $url;
    }
    
    /**
     * Generate {number} numbers digital string.
     *
     * @param int $number
     * @return mixed
     */
    public static function randomDigitalStr($number = 6)
    {
        return str_pad(rand(0, pow(10, $number)-1), $number, '0', STR_PAD_LEFT);
    }
}