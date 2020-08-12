<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit71d5b81818dad9524d78fb4c885ea345
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'FuzzyWuzzy\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'FuzzyWuzzy\\' => 
        array (
            0 => __DIR__ . '/..' . '/wyndow/fuzzywuzzy/lib',
        ),
    );

    public static $prefixesPsr0 = array (
        'D' => 
        array (
            'Diff' => 
            array (
                0 => __DIR__ . '/..' . '/phpspec/php-diff/lib',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit71d5b81818dad9524d78fb4c885ea345::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit71d5b81818dad9524d78fb4c885ea345::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit71d5b81818dad9524d78fb4c885ea345::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}