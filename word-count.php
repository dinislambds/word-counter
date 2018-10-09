<?php
/*
Plugin Name: Word Count
Plugin URI: https://github.com/dinislambds/word-counter/
Description: Count words from any post content
Version: 1.0
Author: Md Din Islam
Author URI: https://dinislambds.com/
License: GPLv2 or later
Text Domain: word-count
Domain Path: /languages/
*/

/* function wordcount_plugin_activation(){}
register_activation_hook( __FILE__, "wordcount_plugin_activation" );

function wordcount_plugin_deactivation(){}
register_deactivation_hook( __FILE__, "wordcount_plugin_deactivation" ); */

function wordcount_plugin_textdomain(){
    load_plugin_textdomain ( "word-count", false, dirname(__FILE__)."/languages" );
}
add_action("plugins_loaded","wordcount_plugin_textdomain");

function wordcount_word_count_callback( $content ){
    // html/php tag strip out, as well as any comments
    $content_stripped = strip_tags ( $content );

    // Word count
    $count_words = str_word_count ( $content_stripped );
    $label = __( "Total word number is: ", "word-count" );

    // apply_filters for user so they can edit/change later
    $label = apply_filters( "wordcount_filters_for_label", $label );
    $tag = apply_filters( "wordcount_tag", "h2" );

    $content .= sprintf ( "<%s>%s %s</%s>", $tag, $label, $count_words, $tag );

    return $content;
}
add_filter( "the_content", "wordcount_word_count_callback" );


function wordcount_word_count_time_callback( $content ){
    $content_stripped = strip_tags ( $content );
    $count_words = str_word_count ( $content_stripped );

    // Reading time
    $reading_time_minutes = floor( $count_words / 200 ); 
    $reading_time_seconds = floor( $count_words % 200 / ( 200 / 60  ) ); 

    $is_visible = apply_filters( "wordcount_time_visible", 1 );

    if ( $is_visible ) {
        $label = __( "Total Time: ", "word-count" );
        $label = apply_filters( "wordcount_filters_for_time_label", $label );
        $tag = apply_filters( "wordcount_time_tag", "h4" );

        $content .= sprintf ( "<%s>%s %s minutes %s seconds </%s>", $tag, $label, $reading_time_minutes, $reading_time_seconds, $tag );
    }

    return $content;
}
add_filter( "the_content", "wordcount_word_count_time_callback" );