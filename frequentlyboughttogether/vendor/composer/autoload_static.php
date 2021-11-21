<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit61ba55f8286554e3334187893fcf7e8a
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Frequentlyboughttogether\\' => 25,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Frequentlyboughttogether\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'ProductAssociation' => __DIR__ . '/../..' . '/src/Entity/productassociation.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit61ba55f8286554e3334187893fcf7e8a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit61ba55f8286554e3334187893fcf7e8a::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit61ba55f8286554e3334187893fcf7e8a::$classMap;

        }, null, ClassLoader::class);
    }
}