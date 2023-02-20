<?php
    function checkRequire($string) {
        return $string != '' ? true : false;
    }

    function checkLength($string) {
        return strlen($string) >= 6 ? true : false;
    }

    function checkValidEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    function validateMobile($phone)
    {
        return (strlen($phone) >= 9 && strlen($phone) <= 12) ? true : false;
    }

    function checkNumber($string) {
        return is_numeric($string) ? true : false;
    }