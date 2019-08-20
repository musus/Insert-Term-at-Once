<?php

/*
Plugin Name: Insert Term at Once
Description: insert term at once on CSV file
Version: 1.0
Author: Susumu Seino
Author URI: https://susu.mu
*/


function itao_add_stylesheet() {

	if ( is_admin() or ! is_super_admin() ) {
		return;
	}

	$wp_version = get_bloginfo( 'version' );

	if ( $wp_version >= '3.8' ) {
		$is_older_than_3_8 = '';
	} else {
		$is_older_than_3_8 = '-old';
	}

	$stylesheet_path = plugins_url( 'css/main' . $is_older_than_3_8 . '.css', __FILE__ );
	wp_register_style( 'current-template-style', $stylesheet_path );
	wp_enqueue_style( 'current-template-style' );
	wp_enqueue_script( 'itao', plugins_url( '/js/app.js', __FILE__ ), array( 'jquery' ) );
}


add_action( 'wp_enqueue_scripts', "itao_add_stylesheet", 9999 );


function add_swiper_sets() {
	$swiper_css_path = plugins_url( 'css/swiper' . '.css', __FILE__ );
	wp_enqueue_style( 'swiper-css', $swiper_css_path );
	wp_enqueue_script( 'itao', plugins_url( '/js/swiper.min.js', __FILE__ ), array( 'jquery' ) );
}

add_action( 'wp_enqueue_scripts', 'add_swiper_sets' );

/*********************************/
/*
/* 基本機能
/*
/*********************************/
function insert_term_at_once() {
//	$parent_term = term_exists( 'bar', 'category' ); // array is returned if taxonomy is given
//	$parent_term_id = $parent_term['term_id'];         // get numeric term id
	wp_insert_term( 'Apple',   // the term
		'categoory', // the taxonomy
		array(
			'description' => 'A yummy apple.',
			'slug'        => 'apple',
			//'parent'      => $parent_term_id,
		) );
}

add_action( 'init', 'insert_term_at_once' );


/*********************************/
/*
/* 管理画面
/*
/*********************************/

function itao_init() {
	$itao_options                  = array();
	$itao_options['itao_radio']    = "TEXT";
	$itao_options['itao_check']    = 1;
	$itao_options['itao_dropdown'] = "WordPress";
	$itao_options['itao_text']     = 10;
	$itao_options['itao_code']     = "<p>add code</p>";

	add_option( 'itao_options', $itao_options );
}

add_action( 'activate_pv-count-swiper/pv-count-swiper.php', 'itao_init' );

function itao_get_options() {
	return get_option( 'itao_options' );
}

function itao_config() {
	include( 'itao-admin.php' );
}

function itao_config_page() {
	if ( function_exists( 'add_submenu_page' ) ) {
		add_options_page( __( 'WordPress Plugin Base' ), __( 'WordPress Plugin Base' ), 'manage_options', 'pv-count-swiper', 'itao_config' );
	}
}

add_action( 'admin_menu', 'itao_config_page' );

