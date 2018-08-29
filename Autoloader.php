<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014 Rob Dunham
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

/**
 * Simple Recursive Autoloader
 *
 * A simple autoloader that loads class files recursively starting in the directory
 * where this class resides.  Additional options can be provided to control the naming
 * convention of the class files.
 *
 * @package Autoloader
 * @license http://opensource.org/licenses/MIT  MIT License
 * @author  Rob Dunham <contact@robunham.info>
 */
class Autoloader
{
    /**
     * File extension as a string. Defaults to ".php".
     */
    protected static $fileExt = '.php';

    /**
     * The topmost directory where recursion should begin. Defaults to the current
     * directory.
     */
    protected static $pathTop = __DIR__;

    /**
     * A placeholder to hold the file iterator so that directory traversal is only
     * performed once.
     */
    protected static $fileIterator = null;

    /**
     * Array of paths to exclude from traversal.
     */
    // protected static $excludes = array();

    /**
     * Autoload function for registration with spl_autoload_register
     *
     * Looks recursively through project directory and loads class files based on
     * filename match.
     *
     * @param string $className
     */
    public static function loader($className)
    {
        echo "loading {$className}...<br />";
        // echo "excluded paths: " . var_dump(self::$excludes) . "<br />";
        echo 'recursively retrieve all classes...<br />';
        $directory = new RecursiveDirectoryIterator(static::$pathTop);
        $filter = new class($directory) extends RecursiveFilterIterator {
            public function accept() {
                $filename = $this->current()->getFilename();
                // Skip hidden files and directories.
                if ($name[0] === '.') {
                  return FALSE;
                }
              }
        };

        if (is_null(static::$fileIterator)) {

            static::$fileIterator = new RecursiveIteratorIterator($filter, RecursiveIteratorIterator::LEAVES_ONLY);

        }

        $filename = $className . static::$fileExt;
        echo "{$className} location is {$filename}<br />";

        $dump = var_dump(static::$fileIterator);
        echo "about to iterate over {$dump}<br />";
        $skipInclusion = false;
        foreach (static::$fileIterator as $file)
        {
            // echo "analazing " . var_dump($file);
            // foreach ($exludes as $excludedPath)
            // {
            //     echo "checking for $excludedPath...<br />";
            //     if ($pos = strpos($file->getPathname(), $excludedPath) != 0)
            //     {
            //         echo "position $pos<br />";
            //         echo "not exploring $file->getPathname()<br />";
            //         $skipInclusion = true;
            //     }
            //     else
            //     {
            //         echo "exploring $file->getPathname()";
            //     }
            // }

            // if ($skipInclusion)
            // {
            //     echo 'not iterating over file' . var_dump($file) . "<br />";
            //     continue;
            // }
            // else
            // {
            //     echo 'iterating over file' . var_dump($file) . "<br />";
            // }
            if (strtolower($file->getFilename()) === strtolower($filename)) {

                if ($file->isReadable()) {

                    echo "found class $file->getPathname()<br />";
                    include_once $file->getPathname();

                }
                break;

            }

            echo '<hr />';
        }

    }

    /**
     * Sets the $fileExt property
     *
     * @param string $fileExt The file extension used for class files.  Default is "php".
     */
    public static function setFileExt($fileExt)
    {
        static::$fileExt = $fileExt;
    }

    /**
     * Sets the $path property
     *
     * @param string $path The path representing the top level where recursion should
     *                     begin. Defaults to the current directory.
     */
    public static function setPath($path)
    {
        static::$pathTop = $path;
    }

    public static function setExclude($path)
    {
        array_push(static::$excludes, $path);
    }

}

Autoloader::setFileExt('.php');
// Autoloader::setExclude('.git');
// Autoloader::setExclude('test');
echo 'Calling autoloader class...<br />';
spl_autoload_register('Autoloader::loader');

// EOF
