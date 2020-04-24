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


class InfoPendatangTickerWidget extends WP_Widget
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
        'info_pendatang_ticker_widget',
        // Widget name will appear in UI
        'Info Pendatang Ticker',
        // Widget description
        array( 'description' => 'Menampilkan informasi pendatang dalam tulisan berjalan', )
        );
    }

    public function widget($args, $instance)
    {
        include_once INFO_PENDATANG_DIR . "include/shortcodes.php";
        $title = apply_filters('widget_title', $instance['title']);
        $type = $instance['type'];
        $data = call_user_func("info_pendatang_get_$type");
        $lines= call_user_func([$this, "content_$type"], $data);
        if ($instance['limit']) {
            $lines = array_slice($lines, 0, $instance['limit']);
        }
        $lines[] = 'Total: <b>' . info_pendatang_get_total() . ' Orang</b>';
        $lines[] = 'Lap. Terakhir: <b>' . info_pendatang_get_last_update() . '</b>';
        $contact = 'WA <b>' . InfoPendatang::$config['no_wa'] . '</b>';
        $lines[] = $contact;
        echo $args['before_widget'];
        echo '<div class="info-pendatang-ticker-text">';
        echo '<marquee behavior="scroll">';
        printf('<a style="color:white" href="%s">', esc_attr($instance['link']));
        if (! empty($title)) {
            echo "<b>$title : </b>";
        }
        echo implode("&nbsp;&nbsp;&bull;&nbsp;&nbsp;", $lines);
        echo '</a>';
        echo '</marquee>';
        echo '</div>';
        echo $args['after_widget'];
    }
    private function content_summary($data)
    {
        $lines = [];
        foreach ($data as $dusun => $jml) {
            $lines[] = sprintf("%s: %s Orang", esc_html($dusun), $jml);
        }
        return $lines;
    }
    private function content_rtrw($data)
    {
        $lines = [];
        foreach ($data as $dusun => &$rtrw) {
            foreach ($rtrw as $rw => &$rts) {
                $count = 0;
                foreach ($rts as $rt => $jml) {
                    if ($rt && $rw) {
                        $tmp = str_pad($rt, 2, '0', STR_PAD_LEFT) . '/' . str_pad($rw, 2, '0', STR_PAD_LEFT);
                    } else {
                        $tmp = '&mdash;';
                    }
                    $lines[] = sprintf("RT %s: %s Orang", $tmp, $jml);
                }
                unset($rts);
            }
            unset($rtrw);
        }
        return $lines;
    }
    private function content_asal_kota($data)
    {
        $lines = [];
        foreach ($data as $row) {
            $lines[] = sprintf("%s: %s Orang", esc_html($row->asal_kota), $row->jml);
        }
        return $lines;
    }

    // Widget Backend
    public function form($instance)
    {
        $title = isset($instance[ 'title' ]) ? $instance[ 'title' ] : 'Informasi Pendatang';
        $type = isset($instance[ 'type' ]) ? $instance[ 'type' ] : 'summary';
        $limit = isset($instance[ 'limit' ]) ? $instance[ 'limit' ] : 0;
        $link = isset($instance[ 'link' ]) ? $instance[ 'link' ] : ''; ?>
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
            <label>Limit Jumlah:</label> 
            <input class="widefat" 
                id="<?php echo $this->get_field_id('limit'); ?>"
                name="<?php echo $this->get_field_name('limit'); ?>"
                type="text" value="<?php echo esc_attr($limit); ?>" />
        </p>
        <p>
            <label>Link (Ketika Klik):</label> 
            <input class="widefat" 
                id="<?php echo $this->get_field_id('link'); ?>"
                name="<?php echo $this->get_field_name('link'); ?>"
                type="text" value="<?php echo esc_attr($link); ?>" />
        </p>
        <?php
    }
    
    // Updating widget replacing old instances with new
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (! empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['type'] = (! empty($new_instance['type'])) ? strip_tags($new_instance['type']) : 'summary';
        $instance['limit'] = (! empty($new_instance['limit'])) ? intval($new_instance['limit']) : 0;
        $instance['link'] = (! empty($new_instance['link'])) ? strip_tags($new_instance['link']) : '#';
        return $instance;
    }
}
