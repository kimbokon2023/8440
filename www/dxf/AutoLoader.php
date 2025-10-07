<?php

function AutoLoader($classname)
{
    $file = __DIR__ . DIRECTORY_SEPARATOR . str_replace(('\\'), DIRECTORY_SEPARATOR, $classname) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
}
spl_autoload_register('AutoLoader');
