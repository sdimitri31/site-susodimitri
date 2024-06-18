<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1a7415c9d58b53a6d03fb02e998bc91c
{
    public static $prefixLengthsPsr4 = array (
        'C' => 
        array (
            'Core\\' => 5,
        ),
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Core\\' => 
        array (
            0 => __DIR__ . '/../..' . '/core',
        ),
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1a7415c9d58b53a6d03fb02e998bc91c::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1a7415c9d58b53a6d03fb02e998bc91c::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit1a7415c9d58b53a6d03fb02e998bc91c::$classMap;

        }, null, ClassLoader::class);
    }
}
