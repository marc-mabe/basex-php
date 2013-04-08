<?php

// set manimum error reporting
error_reporting(E_ALL | E_STRICT);

// test installed PHPUnit version
if (class_exists('PHPUnit_Runner_Version', true)) {
    $phpUnitVersion = PHPUnit_Runner_Version::id();
    if ('@package_version@' !== $phpUnitVersion && version_compare($phpUnitVersion, '3.6.0', '<')) {
        echo "This test suite requires PHPUnit >= 3.6, installed {$phpUnitVersion}" . PHP_EOL;
        exit(1);
    }
    unset($phpUnitVersion);
}

// init autoload
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    include_once __DIR__ . '/../vendor/autoload.php';
} else {
    // if composer autoloader is missing, explicitly define an own
    set_include_path(
        dirname(__DIR__) . '/src'
        . PATH_SEPARATOR . get_include_path()
    );
    spl_autoload_register(function ($class) {
        $classfile = str_replace(array('_', '\\'), DIRECTORY_SEPARATOR, $class) . '.php';
        return @include $classfile;
    });
}
