<?php

namespace App\Core;

abstract class File
{
    public static function findFile(string $filename, string $dir)
    {
        $files = scandir($dir);

        if (array_search($filename, $files) && is_file("$dir/$filename"))
            return "$dir/$filename";

        foreach ($files as $file)
        {
            $filePath = "$dir/$file";

            if ($file === '.' || $file === '..' || !is_dir("$filePath")) continue;

            $result = File::findFile($filename, $filePath);

            if (!is_null($result)) return $result;;
        }

        return null;
    }
}