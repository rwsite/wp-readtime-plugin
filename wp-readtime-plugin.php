<?php
/**
 * Plugin Name: Post read time
 * Description: This Wordpress plugin generates post reading time
 * Version:     1.0.0
 * Author:      Aleksey Tikhomirov
 * Author URI:  http://rwsite.ru
 *
 * Requires at least: 4.6
 * Tested up to: 6.3
 * Requires PHP: 8.0+
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

class PostReadTime
{
    public static $inst = 0;
    public function __construct()
    {
        self::$inst++;
        $this->add_actions();
    }
    public function add_actions(){

        if(self::$inst !== 1) {
            return;
        }

        load_plugin_textdomain( 'readtime', false, dirname(plugin_basename(__FILE__)) . '/languages' );

        add_shortcode('read_time', [$this, 'add_shortcode']);
    }

    public function add_shortcode($args = null){
        $args = wp_parse_args($args, [
            'icon' => true
        ]);

        if($args['icon']){
            // add icon to html
        }

        return self::get_read_time();
    }

    public static function get_read_time($post_id = null, $string = null )
    {
        $post = get_post($post_id ?? get_the_ID());
        $string = !empty($string) ? $string : $post->post_content; // без фильтров, что бы сделать работы быстрее
        $wordsPerMinute = 150;

        $string = strip_tags($string);
        $wordCount = str_word_count($string);
        $minutesToRead = round($wordCount / $wordsPerMinute);

        if($minutesToRead < 1){// if the time is less than a minute
            return sprintf( _n( '%s minute', '%s minutes', 1, 'readtime' ), 1 );
        }

        return sprintf( _n( '%s minute', '%s minutes', $minutesToRead, 'readtime' ), $minutesToRead );
    }
}

/**
 * Get post read time
 *
 * @param null        $post
 * @param string|null $post_content
 *
 * @return string
 */
function get_post_read_time($post = null, ?string $post_content = null)
{
    return PostReadTime::get_read_time($post, $post_content );
}

new PostReadTime();