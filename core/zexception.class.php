<?php

class log
{
    public static function run(Exception $e)
    {
        echo 'Uncaught '.get_class($e).', code: ' . $e->getCode() . "<br />Message: " . htmlentities($e->getMessage())."\n";
    }
}