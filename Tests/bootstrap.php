<?php


if (file_exists($file = __DIR__.'/autoload.php')) {
    include_once $file;
} elseif (file_exists($file = __DIR__.'/autoload.php.dist')) {
    include_once $file;
}