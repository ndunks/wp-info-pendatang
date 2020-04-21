<?php

class InfoPendatangWidget extends WP_Widget
{
    public static $typeList = [
        'summary' => 'Ringkasan Perdusun',
        'rtrw'    => 'Detail RT/RW',
        'asal_kota' => 'Asal Kota'
    ];

    public function __construct()
    {
        parent::__construct(

        // Base ID of your widget
        'info_pendatang_widget',
        // Widget name will appear in UI
        'Info Pendatang',
        // Widget description
        array( 'description' => 'Menampilkan informasi pendatang', )
        );
    }

    // Creating widget front-end
    public function widget($args, $instance)
    {
        include_once INFO_PENDATANG_DIR . "include/shortcodes.php";
        $title = apply_filters('widget_title', $instance['title']);
        $type = $instance['type'];

        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if (! empty($title)) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        $function_call = "info_pendatang_shortcode_$type";
        echo call_user_func($function_call);
        echo '<p>Laporan Terakhir: <b>' . info_pendatang_get_last_update() . '</b></p>';
        echo $instance['html'];
        echo $args['after_widget'];
    }

    // Widget Backend
    public function form($instance)
    {
        $title = isset($instance[ 'title' ]) ? $instance[ 'title' ] : 'Informasi Pendatang';
        $type = isset($instance[ 'type' ]) ? $instance[ 'type' ] : 'summary';
        $html = isset($instance[ 'html' ]) ? $instance[ 'html' ] : ''; ?>
        <p>
            <label>Judul:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label>Tampilkan:</label> 
            <select class="widefat" id="<?php echo $this->get_field_id('type'); ?>" name="<?php echo $this->get_field_name('type'); ?>">
                <?php
                foreach (self::$typeList as $id => $txt) {
                    printf(
                        '<option value="%s" %s>%s</option>',
                        $id,
                        $type == $id ? 'selected' : '',
                        $txt
                    );
                } ?>
            </select>
        </p>
        <p>
            <label>Tambahkan Teks:</label> 
            <textarea class="widefat" id="<?php echo $this->get_field_id('html'); ?>" name="<?php echo $this->get_field_name('html'); ?>"><?= htmlentities($html); ?></textarea>
        </p>
        <?php
    }
    
    // Updating widget replacing old instances with new
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (! empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['type'] = (! empty($new_instance['type'])) ? strip_tags($new_instance['type']) : 'summary';
        $instance['html'] = (! empty($new_instance['html'])) ? $new_instance['html'] : '';
        return $instance;
    }
}
