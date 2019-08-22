<?php
$itao_options = get_option( 'itao_options' );
if ( isset( $_POST['submit'] ) ) {

	$itao_options['itao_radio']    = htmlspecialchars( $_POST['itao_radio'] );
	$itao_options['itao_check']    = htmlspecialchars( $_POST['itao_check'] );
	$itao_options['itao_dropdown'] = htmlspecialchars( $_POST['itao_dropdown'] );
	$itao_options['itao_num']      = htmlspecialchars( $_POST['itao_num'] );
	$itao_options['itao_code']     = htmlspecialchars( $_POST['itao_code'] );

	update_option( 'itao_options', $itao_options );
}
$message = "";
?>

<?php if ( ! empty( $_POST['itao_csv'] ) ) : ?>
	<div id="message" class="updated fade"><p><strong><?php _e( 'Term updated.' ) ?></strong></p></div>
<?php endif; ?>
	<div class="wrap">
	<h2><?php _e( 'Insert Term at Once Configuration', 'insert-term-at-once' ); ?></h2>

	<div class="metabox-holder" id="poststuff">
		<div class="meta-box-sortables">
			<script>
                jQuery(document).ready(function ($) {
                    $('.postbox').children('h3, .handlediv').click(function () {
                        $(this).siblings('.inside').toggle();
                    });
                });
			</script>
			<div class="postbox">
				<div title="<?php _e( "Click to open/close", "insert-term-at-once" ); ?>" class="handlediv">
					<br>
				</div>
				<h3 class="hndle"><span><?php _e( "Is it work?", "insert-term-at-once" ); ?></span></h3>
				<div class="inside" style="display: block;">
					<img src="../wp-content/plugins/insert-term-at-once/img/icon_coffee.png" alt="buy me a coffee" style="height:60px; margin: 10px; float:left;"/>
					<p>Hi! This plugin from <a href="https://susu.mu?f=itao" target="_blank" title="Susumu Seino">Susumu Seino</a>'s Insert Term at Once.</p>
					<p>I'm been spending many hours to develop that plugin. <br/>If you like and use this plugin, you can <strong>buy a cup of coffee</strong>.</p>
				</div>
			</div>
			<?php echo $message; ?>

			<form method="post" action="<?php echo admin_url( 'options-general.php?page=insert-term-at-once' ); ?>" enctype="multipart/form-data">

				<div class="postbox">
					<div title="<?php _e( "Click to open/close", "insert-term-at-once" ); ?>" class="handlediv">
						<br>
					</div>
					<h3 class="hndle"><span><?php _e( "Options", "insert-term-at-once" ); ?></span></h3>
					<div class="inside" style="display: block;">
						<?php
						$args       = array(
							'public' => true
						);
						$taxonomies = get_taxonomies( $args, 'objects' );
						?>

						<table class="form-table">
							<tr>
								<th><?php _e( "Select taxonomy", "insert-term-at-once" ) ?></th>
								<td>
									<ul>
										<?php
										foreach ( $taxonomies as $taxonomy_obj ) {

											$tax_name  = esc_html( $taxonomy_obj->name );
											$tax_label = esc_html( $taxonomy_obj->label );
											echo "<li><label>";
											echo '<input type="checkbox" name="itao_check[]" value="' . $tax_name . '"> ' . $tax_label . ' ( ' . $tax_name . ' )';
											echo "</label></li>";
										}
										?>
									</ul>
								</td>
							</tr>
							<tr>
								<th><?php _e( "CSV File", "insert-term-at-once" ) ?></th>
								<td>
									<input type="file" name="itao_csv"/>
								</td>
							</tr>

							<tr>
								<th></th>
								<td>
									<input type="submit" name="insert_term_at_once_submit" class="button button-primary" value="<?php _e( 'Update term &raquo;' ); ?>"/>
								</td>
							</tr>
						</table>

					</div>
				</div>

			</form>
		</div>
	</div>

<?php


if ( ! empty( $_FILES['itao_csv'] ) ) {

	if ( isset( $_POST['itao_check'] ) ) {
		$taxonomies = $_POST['itao_check'];
	}

	if ( is_uploaded_file( $_FILES["itao_csv"]["tmp_name"] ) ) {
		if ( move_uploaded_file( $_FILES["itao_csv"]["tmp_name"], $_FILES["itao_csv"]["name"] ) ) {
			chmod( $_FILES["itao_csv"]["name"], 0644 );

			setlocale( LC_ALL, 'ja_JP.UTF-8' );    //ロケール情報の設定
			$data = file_get_contents( $_FILES["itao_csv"]["name"] );
			$temp = tmpfile();    //テンポラリファイルの作成
			$meta = stream_get_meta_data( $temp );    //メタデータからファイルパスを取得して読み込み
			fwrite( $temp, $data );    //バイナリセーフなファイル書き込み処理
			rewind( $temp );    //ファイルポインタの位置を先頭に戻す
			$file = new SplFileObject( $meta['uri'] );    //fgetitao_csvよりSplFileObjectを使うほうが高速らしい。
			$file->setFlags( SplFileObject::READ_CSV );
			$terms = array();
			foreach ( $file as $line ) {
				$terms[] = $line;
			}
			fclose( $temp );
			$file = null;


		} else {
			_e( "Doesn't upload", "insert-term-at-once" );
		}

		insert_term_at_once( $terms, $taxonomies );
	}
}