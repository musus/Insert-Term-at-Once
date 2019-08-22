<?php

/*
Plugin Name: Insert Term at Once
Description: Insert term at once on CSV file
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


/*********************************/
/*
/* 基本機能
/*
/*********************************/

function insert_term_at_once( $terms, $taxonomies ) {

	foreach ( $taxonomies as $tax ) {
		foreach ( $terms as $term ) {
			$term_array = array();
			if ( ! $term[0] == null ) {
				if ( ! $term[3] == null ) {
					$parent_term    = term_exists( $term[3], $tax ); // array is returned if taxonomy is given
					$parent_term_id = $parent_term['term_id'];         // get numeric term id
				}
				$term_array = array(
					'slug'        => $term[1],
					'description' => $term[2],
					'parent'      => $parent_term_id,
				);
			}
			if ( ! $term[3] && ! $parent_term_id == null ) {
				wp_insert_term( $term[0], $tax, $term_array );
			}
		}
	}
}


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

add_action( 'activate_insert-term-at-once/insert-term-at-once.php', 'itao_init' );

function itao_get_options() {
	return get_option( 'itao_options' );
}

function itao_config() {
	include( 'itao-admin.php' );
}

function itao_config_page() {
	if ( function_exists( 'add_submenu_page' ) ) {
		add_options_page( __( 'Insert Term at Once' ), __( 'Insert Term at Once' ), 'manage_options', 'insert-term-at-once', 'itao_config' );
	}
}

add_action( 'admin_menu', 'itao_config_page' );