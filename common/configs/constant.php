<?php
    function getCurrentUrl()
    {
        return substr($_SERVER["SCRIPT_NAME"], strrpos($_SERVER["SCRIPT_NAME"], "/") + 1);
    }

    function limitWordString($string)
    {
        $string = (strlen($string) > 15) ? substr($string, 0, 15) . '...' : $string;
        return $string;
    }

    function limitWord($string)
    {
        $string = (strlen($string) > 18) ? substr($string, 0, 18) . '...' : $string;
        return $string;
    }

    const ACTIVE_DELETE = 2;
    const ACTIVE = 1;
    const INACTIVE = 0;

    $inactive = 0;
    $active = 1;
    $activeDelete = 2;