<?php

namespace Valet;

use Exception;

class Site
{
    /**
     * Link the current working directory with the given name.
     *
     * @param  string  $name
     * @return string
     */
    public static function link($name)
    {
        if (! is_dir($linkPath = $_SERVER['HOME'].'/.valet/Sites')) {
            mkdir($linkPath, 0755);
        }

        Configuration::addPath($linkPath);

        if (file_exists($linkPath.'/'.$name)) {
            throw new Exception("A symbolic link with this name already exists.");
        }

        symlink(getcwd(), $linkPath.'/'.$name);

        return $linkPath;
    }

    /**
     * Unlink the given symbolic link.
     *
     * @param  string  $name
     * @return void
     */
    public static function unlink($name)
    {
        if (! file_exists($path = $_SERVER['HOME'].'/.valet/Sites/'.$name)) {
            return false;
        }

        @unlink($path);

        return true;
    }

    /**
     * Get all of the log files for all sites.
     *
     * @return array
     */
    public static function logs()
    {
        $paths = Configuration::read()['paths'];

        $files = [];

        foreach ($paths as $path) {
            foreach (scandir($path) as $directory) {
                $logPath = $path.'/'.$directory.'/storage/logs/laravel.log';

                if (! in_array($directory, ['.', '..']) && file_exists($logPath)) {
                    $files[] = $logPath;
                }
            }
        }

        return $files;
    }
}
