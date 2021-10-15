<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita16082e67a204a3eca36e54901896407
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Psr\\Log\\' => 8,
            'PhpMqtt\\Client\\' => 15,
        ),
        'M' => 
        array (
            'MyCLabs\\Enum\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
        'PhpMqtt\\Client\\' => 
        array (
            0 => __DIR__ . '/..' . '/php-mqtt/client/src',
        ),
        'MyCLabs\\Enum\\' => 
        array (
            0 => __DIR__ . '/..' . '/myclabs/php-enum/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita16082e67a204a3eca36e54901896407::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita16082e67a204a3eca36e54901896407::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInita16082e67a204a3eca36e54901896407::$classMap;

        }, null, ClassLoader::class);
    }
}
