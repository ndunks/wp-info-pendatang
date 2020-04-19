<?php
/**
 * Plugin Name: Info Pendatang
 * Plugin URI: https://klampok.id/
 * Description: Mendata dan menampilkan informasi pendatang di desa anda
 * Version: 1.0.0
 * Author: Mochamad Arifin
 * Author URI: http://klampok.id/
 */

define('INFO_PENDATANG_DIR', plugin_dir_path(__FILE__));
define('INFO_PENDATANG_URL', plugins_url('', __FILE__) . '/');

class InfoPendatang
{
    public static $title	= 'Info Pendatang';
    public static $name	= 'info_pendatang';
    public static $version= '1.0.0';
    public static $me		= false;
    public static $config	= null;

    public function __construct()
    {
        self::$me	=& $this;

        add_action('init', array($this, 'init'));
        //add_action('wp_ajax_info_pendatang', array($this, 'ajax'));
        
        if (is_admin()) {
            include INFO_PENDATANG_DIR . 'include/admin.php';
        }

        //intialize default config
        $saved_config = get_option(self::$name);
        if (!empty($saved_config) && is_array($saved_config)) {
            self::$config	= $saved_config;
        } else {
            //Default config
            self::$config	= [
                'secret'=> 'InfoPendatang123',
                'version' => self::$version,
                'dusun' => [
                    [
                        'no' => 1,
                        'nama' => 'Dusun 1',
                        'rw' => [1,2,3]
                    ],
                    [
                        'no' => 2,
                        'nama' => 'Dusun 2',
                        'rw' => [4,5,6]
                    ],
                ]
            ];
        }
    }
    
    public function init()
    {
        add_shortcode(self::$name, array($this, "shortcode_info_pendatang"));
    }

    public function shortcode_info_pendatang($arg, $conteng, $tag)
    {
        include INFO_PENDATANG_DIR . "pages/display.php";
    }

    public static function commit_option($new_key = null, $new_val = null)
    {
        if (!is_null($new_key)) {
            self::$config[$new_key]	= $new_val;
        }
        return update_option(self::$name, self::$config, true);
    }
    
    public static function run()
    {
        return self::$me ? self::$me : new InfoPendatang();
    }
}

$INFO_PENDATANG	= InfoPendatang::run();
