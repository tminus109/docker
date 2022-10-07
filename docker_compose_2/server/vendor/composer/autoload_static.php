<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit045e7db1599e46e7c309d02352c17fbc
{
    public static $files = array (
        '253c157292f75eb38082b5acb06f3f01' => __DIR__ . '/..' . '/nikic/fast-route/src/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
        'F' => 
        array (
            'FastRoute\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
        'FastRoute\\' => 
        array (
            0 => __DIR__ . '/..' . '/nikic/fast-route/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit045e7db1599e46e7c309d02352c17fbc::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit045e7db1599e46e7c309d02352c17fbc::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
