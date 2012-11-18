<?php

namespace Dzangocart\Bundle\DzangocartBundle\Util;

class Password
{
    public static function generate($length = 8, $possible = null)
    {
        // start with a blank password
        $password = "";

        // define possible characters if not set
        if (!$possible) {
            $possible = "0123456789abcdefghijkmnpqrstvwxyzABCDEFGHJKLMNPQRSTUVXYZ";
        }

        // set up a counter
        $i = 0;

        // add random characters to $password until $length is reached
        while ($i < $length) {
            // pick a random character from the possible ones
            $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
            // we don't want this character if it's already in the password
            if (!strstr($password, $char)) {
                $password .= $char;
                $i++;
            }
        }

        // done!
        return $password;
    }

    public static function generateKey()
    {
        if (extension_loaded('uuid')) {
            $key = uuid_create(UUID_TYPE_RANDOM);
        } else {
            $key = uniqid(mt_rand(), true);
        }

        return $key;
    }
}
