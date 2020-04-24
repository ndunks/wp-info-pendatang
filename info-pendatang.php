<?php
/**
 * Plugin Name: Info Pendatang
 * Plugin URI: https://klampok.id/
 * Description: Mendata dan menampilkan informasi pendatang di desa anda
 * Version: 1.0.4
 * Author: Mochamad Arifin
 * Author URI: http://klampok.id/
 *
 * Change Log
 * 1.0.0: Initial
 * 1.0.1: Add edit
 * 1.0.2: send WA after verified
 * 1.0.3: Paging and widget improved
 * 1.0.4: Fix scroll
 * 1.0.5: text scroll using marquee
 */

define('INFO_PENDATANG_DIR', plugin_dir_path(__FILE__));
define('INFO_PENDATANG_URL', plugins_url('', __FILE__) . '/');

class InfoPendatang
{
    public static $title	= 'Info Pendatang';
    public static $name		= 'info_pendatang';
    public static $version	= '1.0.4';
    public static $me		= false;
    public static $config	= null;
    public static $table	= null;
    public static $global	= [];

    public function __construct()
    {
        global $table_prefix;

        self::$me	=& $this;
        add_action('init', array($this, 'init'));
        add_action('admin_bar_menu', array($this, 'admin_bar_menu'), 140);
        /** No Priv only will fail when loggedin */
        add_action('wp_ajax_nopriv_info_pendatang', array($this, 'ajax'));
        add_action('wp_print_scripts', array($this, 'javascripts'));
        add_action('wp_print_styles', array($this, 'stylesheets'));
        add_action('widgets_init', array($this, 'init_widget'));
        // Set global table name
        self::$table = $table_prefix . self::$name;
        // Global functions
        include INFO_PENDATANG_DIR . 'include/functions.php';
        // Widgets
        include INFO_PENDATANG_DIR . 'include/widgets.php';
        if (is_admin()) {
            include INFO_PENDATANG_DIR . 'include/admin.php';
        }

        //intialize default config
        $saved_config = get_option(self::$name);
        if (!empty($saved_config) && is_array($saved_config)) {
            self::$config	= $saved_config;
        }
        
        if (empty(self::$config) || self::$config['version'] != self::$version) {
            include INFO_PENDATANG_DIR . 'include/upgrade.php';
        }
    }

    /**
     * Ajax handler for external access (not logged WP User)
     */
    public function ajax()
    {
        info_pendatang_ajax('ajax');
    }

    public function init()
    {
        add_shortcode(InfoPendatang::$name, array($this, "shortcode_info_pendatang"));
        wp_register_script('info-pendatang-front', INFO_PENDATANG_URL . 'res/js/front.js', [], self::$version);
        wp_register_style('info-pendatang-front', INFO_PENDATANG_URL . 'res/css/front.css', [], self::$version);
        wp_register_script('info-pendatang-admin', INFO_PENDATANG_URL . 'res/js/admin.js', ['jquery-ui-dialog'], self::$version);
        wp_register_style('info-pendatang-admin', INFO_PENDATANG_URL . 'res/css/admin.css', ['info-pendatang-front','wp-jquery-ui-dialog'], self::$version);
    }
    
    public function admin_bar_menu($wp_admin_bar)
    {
        $args = array(
            'id' => 'info-pendatang-button',
            'title' => 'Info Pendatang',
            'href' => get_admin_url(get_current_blog_id(), 'admin.php?page=' . InfoPendatang::$name)
        );
        $wp_admin_bar->add_menu($args);
        $wp_admin_bar->remove_node('wp-logo');
        $wp_admin_bar->remove_node('updates');
        $wp_admin_bar->remove_node('comments');
        $wp_admin_bar->remove_node('wpfc-toolbar-parent');
    }

    public function init_widget()
    {
        register_widget('InfoPendatangWidget');
        register_widget('InfoPendatangTickerWidget');
    }

    public function javascripts()
    {
        wp_enqueue_script('info-pendatang-front');
    }

    public function stylesheets()
    {
        wp_enqueue_style('info-pendatang-front');
    }

    public function shortcode_info_pendatang($atts_ori, $content, $tag)
    {
        $atts = array_change_key_case((array)$atts_ori, CASE_LOWER);
        // override default attributes with user attributes
        if (empty($atts) || empty($atts[0])) {
            $atts[0] = 'summary';
        }
        $type = array_shift($atts);
        include_once INFO_PENDATANG_DIR . "include/shortcodes.php";
        $function = InfoPendatang::$name . "_shortcode_" . $type;
        if (function_exists($function)) {
            return call_user_func_array($function, $atts);
        } else {
            return '<i style="color:red">shortcode tidak dikenal ' . $type . '</i>';
        }
    }

    public static function commit_option($new_key = null, $new_val = null)
    {
        if (!is_null($new_key)) {
            self::$config[$new_key]	= $new_val;
        }
        return update_option(self::$name, self::$config, true);
    }
    
    public static function has_result($var_name)
    {
        return isset(self::$global[$var_name]);
    }

    /**
     * Get or set global result
     */
    public static function result($var_name, $setValue = false)
    {
        if ($setValue !== false) {
            return self::$global[ $var_name ] = $setValue;
        } else {
            return self::$global[ $var_name ];
        }
    }
    public static function run()
    {
        return self::$me ? self::$me : new InfoPendatang();
    }
}

$INFO_PENDATANG	= InfoPendatang::run();
