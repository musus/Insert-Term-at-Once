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

function insert_term_at_once( $terms ) {
//	$parent_term = term_exists( 'bar', 'category' ); // array is returned if taxonomy is given
//	$parent_term_id = $parent_term['term_id'];         // get numeric term id

//	$terms = [
//		[ 'ばなな', 'banana' ],
//		[ 'オレンジ', 'orange' ]
//	];

	foreach ( $terms as $term ) {
		wp_insert_term( $term[0],   // the term
			'category', // the taxonomy
			array(
				'slug'        => $term[1],
				'description' => $term[2],
				//'parent'      => $parent_term_id,
			) );
	}
}

if ( ! empty( $_FILES['csv'] ) ) {
	if ( is_uploaded_file( $_FILES["csv"]["tmp_name"] ) ) {
		if ( move_uploaded_file( $_FILES["csv"]["tmp_name"], $_FILES["csv"]["name"] ) ) {
			chmod( $_FILES["csv"]["name"], 0644 );

			setlocale( LC_ALL, 'ja_JP.UTF-8' );    //ロケール情報の設定
			$data = file_get_contents( $_FILES["csv"]["name"] );
//			$data = mb_convert_encoding( $data, 'UTF-8', 'sjis-win' );    //文字エンコードをUTF-8へ変換
			$temp = tmpfile();    //テンポラリファイルの作成
			$meta = stream_get_meta_data( $temp );    //メタデータからファイルパスを取得して読み込み
			fwrite( $temp, $data );    //バイナリセーフなファイル書き込み処理
			rewind( $temp );    //ファイルポインタの位置を先頭に戻す
			$file = new SplFileObject( $meta['uri'] );    //fgetcsvよりSplFileObjectを使うほうが高速らしい。
			$file->setFlags( SplFileObject::READ_CSV );
			$csv = array();
			foreach ( $file as $line ) {
				$terms[] = $line;
			}
			fclose( $temp );
			$file = null;


		} else {
			echo "ファイルをアップロードできません。";
		}
	}
	insert_term_at_once( $terms );
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