<?php

class InfoPendatangAdmin
{
    public static $me		= false;
    public function __construct()
    {
        self::$me	=& $this;
        add_action('init', array($this, 'init'));
        add_action('admin_menu', array($this, 'menu'));
        add_action('admin_bar_menu', array($this, 'admin_bar_menu'), 90);
        add_action('admin_print_scripts', array($this, 'javascripts'));
        add_action('admin_print_styles', array($this, 'stylesheets'));
        register_activation_hook(INFO_PENDATANG_DIR . 'info-pendatang.php', array($this, 'activate'));
        register_deactivation_hook(INFO_PENDATANG_DIR . 'info-pendatang.php', array($this, 'deactivate'));
    }

    public function init()
    {
        wp_register_script(InfoPendatang::$name, INFO_PENDATANG_URL . 'res/js/script.js', ['jquery' ], InfoPendatang::$version, true);
        wp_register_style(InfoPendatang::$name, INFO_PENDATANG_URL . 'res/css/style.css');
    }

    public function javascripts()
    {
        wp_enqueue_script(InfoPendatang::$name);
    }

    public function stylesheets()
    {
        wp_enqueue_style(InfoPendatang::$name);
    }

    public function menu()
    {
        add_menu_page('Info Pendatang', 'Info Pendatang', 'publish_posts', InfoPendatang::$name, array($this, 'main'), 'dashicons-groups', 10);
    }
    
    public function admin_bar_menu($wp_admin_bar)
    {
        $args = array(
            'id' => 'info-pendatang-button',
            'title' => '<span>Info Pendatang</span>',
            'href' => get_admin_url(get_current_blog_id(), 'admin.php?page=' . InfoPendatang::$name)
        );
        $wp_admin_bar->add_menu($args);
    }
    
    //View Router
    public function main()
    {
        if (!empty(@$_GET['page'])) {
            $page   = strtr($_GET['page'], "/\\'\"%./;:*\0", '-----------');
            $page	= INFO_PENDATANG_DIR . 'pages/' . $page . '.php';
            include is_file($page) ? $page : INFO_PENDATANG_DIR . 'pages/main.php';
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
