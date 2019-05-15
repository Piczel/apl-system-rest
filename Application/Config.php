<?php
declare(strict_types = 1);

namespace Application;

use Exception;

class Config
{
    private static $settings = null;

    private static function load_settings()
    {
        $file = 'config.ini';
        if(!is_file($file))
            throw new Exception('Could not locate file: '. $file);
            
        self::$settings = parse_ini_file($file, true, INI_SCANNER_TYPED);
    }

    public static function get(
        string $section,
        string $setting
    ) {
        if(self::$settings === null)
        {
            self::load_settings();
        }
        return self::$settings[$section][$setting];
    }
}