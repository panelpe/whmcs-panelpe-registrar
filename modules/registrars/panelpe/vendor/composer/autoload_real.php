<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitef9f926394b6930e03eb2a970102b1bb
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInitef9f926394b6930e03eb2a970102b1bb', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitef9f926394b6930e03eb2a970102b1bb', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        \Composer\Autoload\ComposerStaticInitef9f926394b6930e03eb2a970102b1bb::getInitializer($loader)();

        $loader->register(true);

        return $loader;
    }
}
