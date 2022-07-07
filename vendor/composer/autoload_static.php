<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5a9f7d28ca9c02e55a093a7c02952885
{
    public static $prefixLengthsPsr4 = array (
        'l' => 
        array (
            'longzy\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'longzy\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit5a9f7d28ca9c02e55a093a7c02952885::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit5a9f7d28ca9c02e55a093a7c02952885::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit5a9f7d28ca9c02e55a093a7c02952885::$classMap;

        }, null, ClassLoader::class);
    }
}