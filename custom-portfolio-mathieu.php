<?php 
/*
Plugin Name: MMPortfolio
Plugin URI: http://mathieumaas.nl
Description: Creates a filterable portfolio
Author: Mathieu Maas
Version: 1.0 Beta
Author URI: http://mathieumaas.nl
*/

/**
 * Setup the settings
 */
require('mm_shortcode.php');
require('mm_portfolio_item.php');

function mm_settings()
{
	require('mm_settings.php');
	$settings = new mm_settings();
}
 
function mm_actions()
{
	add_submenu_page( 'edit.php?post_type=mm_portfolio_item','Settings', 'Settings', 'manage_options', 'settings', 'mm_settings');
}
 
add_action('admin_menu', 'mm_actions');

/**
 * Enqueue plugin styles
 */
function mm_styles()
{
    wp_register_style( 'mm-styling', plugins_url('main-portfolio.css', __FILE__) );
    wp_enqueue_style( 'mm-styling' );//
    wp_enqueue_style( 'dashicons' );
}

add_action( 'wp_enqueue_scripts', 'mm_styles' );

function mm_scripts()
{
    wp_register_script( 'mm-scripts', plugins_url('/js/mixitup.min.js', __FILE__), ['jquery'] );
    wp_enqueue_script( 'mm-scripts' );
}

add_action( 'wp_enqueue_scripts', 'mm_scripts' );

function mm_setup()
{
    register_post_type('mm_portfolio_item',[
        'labels' => [
            'name' => _x('MMPortfolio', 'post type general name'),
            'singular_name' => _x('MPortfolio', 'post type singular name'),
            'add_new' => _x('Add New portfolio item', 'mm_portfolio_item'),
            'add_new_item' => __('Add New portfolio item'),
        ],
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'mm_portfolio_item','with_front' => FALSE],
        'capability_type' => 'post',
        'hierarchical' => true,
        'menu_position' => null,
        'supports' => ['title',]
    ]);


    register_taxonomy('specification', 'mm_portfolio_item', [
        'labels'            => [
            'name'               => 'Specifications',
            'singular_name'      => 'Specification',
            'search_items'       => 'Search Specifications',
            'all_items'          => 'All Specifications',
            'parent_item'        => 'Parent Specification',
            'parent_item_colon'  => 'Parent Specification:',
            'update_item'        => 'Update Specification',
            'edit_item'          => 'Edit Specification',
            'add_new_item'       => 'Add New Specification',
            'new_item_name'      => 'New Specification Name',
            'menu_name'          => get_option('mm_name')
        ],
        'hierarchical'      => true,
        'show_ui'           => true,
        'how_in_nav_menus'  => true,
        'public'            => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'specification']
    ]);

    flush_rewrite_rules();

    //Register actions
    new mm_portfolio_item();

}

add_action('init', 'mm_setup');
add_theme_support( 'post-thumbnails' );
add_image_size( 'mm-portfolio', 600, 400, true );

/**
 * Setup Shortcode
 *
 */
add_shortcode( 'mm_portfolio', ['mm_shortcode', 'mm_portfolio_items']);


function getTemplateForPortfolioItems($single_template)
{
     global $post;

     if ($post->post_type == 'mm_portfolio_item') {
          $single_template = dirname( __FILE__ ) . '/custom-single.php';
     }

     return $single_template;
}

add_filter( 'single_template', 'getTemplateForPortfolioItems' );
?>