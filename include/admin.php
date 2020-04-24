<?php

class InfoPendatangAdmin
{
    public static $me		= false;
    public function __construct()
    {
        self::$me	=& $this;
        add_action('admin_menu', array($this, 'menu'));
        add_action('admin_print_scripts', array($this, 'javascripts'));
        add_action('admin_print_styles', array($this, 'stylesheets'));
        register_activation_hook(INFO_PENDATANG_DIR . 'info-pendatang.php', array($this, 'activate'));
        register_deactivation_hook(INFO_PENDATANG_DIR . 'info-pendatang.php', array($this, 'deactivate'));
        add_action('wp_ajax_info_pendatang', array($this, 'ajax'));
    }

    public function ajax()
    {
        info_pendatang_ajax('ajax_admin', 'ajax');
    }

    public function javascripts()
    {
        if (@$_GET['page'] != InfoPendatang::$name) {
            return;
        }
        wp_enqueue_script('info-pendatang-admin');
    }

    public function stylesheets()
    {
        if (@$_GET['page'] != InfoPendatang::$name) {
            return;
        }
        wp_enqueue_style('info-pendatang-admin');
    }

    public function menu()
    {
        add_menu_page('Info Pendatang', 'Info Pendatang', 'publish_posts', InfoPendatang::$name, array($this, 'main'), 'dashicons-groups', 10);
    }

    
    //View Router
    public function main()
    {
        if (!empty(@$_GET['view'])) {
            $view   = strtr($_GET['view'], "/\\'\"%./;:*\0", '-----------');
            $view	= INFO_PENDATANG_DIR . 'pages/' . $view . '.php';
            include is_file($view) ? $view : INFO_PENDATANG_DIR . 'pages/main.php';
        } else {
            include INFO_PENDATANG_DIR . 'pages/main.php';
        }
    }

    public function activate()
    {
        include INFO_PENDATANG_DIR . 'include/setup.php';
        info_pendatang_setup();
    }

    public function deactivate()
    {
        include INFO_PENDATANG_DIR . 'include/setup.php';
        info_pendatang_remove();
    }

    public static function run()
    {
        return InfoPendatangAdmin::$me ? InfoPendatangAdmin::$me : new InfoPendatangAdmin();
    }
}

InfoPendatangAdmin::run();
