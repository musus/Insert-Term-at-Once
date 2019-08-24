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
			$term_check = term_exists( $term[0], $tax );
			if ( $term_check ) {
				$term_id        = $term_check['term_id'];
				$term_array     = array();
				$parent_term_id = '';
				if ( ! $term[0] == null ) {
					if ( ! empty( $term[3] ) ) {
						$parent_term    = term_exists( $term[3], $tax );
						$parent_term_id = $parent_term['term_id'];
						if ( empty( $parent_term_id ) ) {
							continue;
						}
					}
					$term_array = array(
						'slug'        => $term[1],
						'description' => $term[2],
						'parent'      => $parent_term_id,
					);
				}
				wp_update_term( $term_id, $tax, $term_array );


			} else {
				$term_array     = array();
				$parent_term_id = '';
				if ( ! $term[0] == null ) {
					if ( ! empty( $term[3] ) ) {
						$parent_term    = term_exists( $term[3], $tax );
						$parent_term_id = $parent_term['term_id'];
						if ( empty( $parent_term_id ) ) {
							continue;
						}
					}
					$term_array = array(
						'slug'        => $term[1],
						'description' => $term[2],
						'parent'      => $parent_term_id,
					);
				}
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


function itao_config() {
	include( 'itao-admin.php' );
}

function itao_config_page() {
	if ( function_exists( 'add_submenu_page' ) ) {
		add_options_page( __( 'Insert Term at Once' ), __( 'Insert Term at Once' ), 'manage_options', 'insert-term-at-once', 'itao_config' );
	}
}

add_action( 'admin_menu', 'itao_config_page' );