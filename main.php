<?php

function json_encode($o)
{
    switch (gettype($o)) {
        case 'NULL':
            return 'null';
        case 'integer':
        case 'double':
            return strval($o);
        case 'string':
            return '"' . addslashes($o) . '"';
        case 'boolean':
            return $o ? 'true' : 'false';
        case 'object':
            $o = (array)$o;
        case 'array':
            $foundKeys = false;

            foreach ($o as $k => $v) {
                if (!is_numeric($k)) {
                    $foundKeys = true;
                    break;
                }
            }

            $result = array();

            if ($foundKeys) {
                foreach ($o as $k => $v) {
                    $result [] = json_encode($k) . ':' . json_encode($v);
                }

                return '{' . implode(',', $result) . '}';
            } else {
                foreach ($o as $k => $v) {
                    $result [] = json_encode($v);
                }
                return '[' . implode(',', $result) . ']';
            }
    }
}



function validateX($inp) {
    return isset($inp) && is_numeric($inp) && $inp >= 1 && $inp <=3 && $inp * 2 == intval($inp * 2);
}

function validateY($inp)
{
    if (!isset($inp)) return false;

    $Y_MIN = -3;
    $Y_MAX = 3;

    $y_num = str_replace(",", ".", $inp);
    return is_numeric($y_num) && $Y_MIN < $y_num && $y_num < $Y_MAX && strlen($inp) <= 7;
}

function validateR($inp)
{
    if (!isset($inp)) return false;

    $R_MIN = 1;
    $R_MAX = 4;

    $r_num = str_replace(",", ".", $inp);
    return is_numeric($r_num) && $R_MIN < $r_num && $r_num < $R_MAX && strlen($inp) <= 7;
}

function validateTimezone($inp) {
    return isset($inp) && is_numeric($inp) && abs($inp) <= 24 * 60;
}

function isSquareHit($x, $y, $r) {
    return ($x > 0 && $y > 0 && $x > -$r && $y < $r);
}

function isTriangleHit($x, $y, $r) {
    $hypotenuse = -1/2 * $y - $r/2;
    return ($x < 0 && $y < 0 && $y > $hypotenuse);

}

function isCircleHit($x, $y, $r)
{
    $isInsideCircle = pow($x, 2) + pow($y, 2) < pow($r, 2);
    return ($x > 0 && $y < 0 && $isInsideCircle);
}

function isBlueAreaHit($x, $y, $r) {
    return isCircleHit($x, $y, $r) || isTriangleHit($x, $y, $r) || isSquareHit($x, $y, $r);
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$x = $_GET['x'];
$y = $_GET['y'];
$r = $_GET['r'];
$timezone = $_GET['timezone'];

    $isValid = validateR($r) && validateX($x) && validateY($y) && validateTimezone($timezone);
    $isBlueAreaHit = NULL;
    $userTime = NULL;
    $timePassed = NULL;
    if ($isValid) {
        $isBlueAreaHit = isBlueAreaHit($x, $y, $r);
        $userTime = @date('H:i:s', time() - $timezone * 60);
        $timePassed = round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 4);
    }
    $response[] = array(
        "isValid" => $isValid,
        "isBlueAreaHit" => $isBlueAreaHit,
        "userTime" => $userTime,
        "execTime" => $timePassed,
        "x" => $x,
        "y" => $y,
        "r" => $r
    );
echo json_encode($response);

