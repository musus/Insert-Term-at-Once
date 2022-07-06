<?php
// delete funciton
if ( isset( $_POST['insert_term_at_once_submit'] ) && isset( $_POST['itao_delete'] ) ) {
	if ( $_POST['itao_delete'] == '1' ) {
		delete_terms_at_once( $_POST['itao_check'] );
		?>
        <div id="message" class="updated fade">
            <p>
                <strong>
					<?php
					_e( 'All terms deleted as ', "insert-term-at-once" );
					foreach ( $_POST['itao_check'] as $term ) {
						echo $term . ' ';
						if ( $term !== end( $_POST['itao_check'] ) ) {
							echo ", ";
						}
					}
					_e( '.', "insert-term-at-once" ); ?>
                </strong>
            </p>
        </div>
		<?php

	}
} elseif ( isset( $_POST['insert_term_at_once_submit'] ) && ! empty( $_FILES['itao_csv'] ) ) {

	$itao_csv = $_FILES['itao_csv'];

	if ( ! $itao_csv['name'] == null && isset( $_POST['itao_check'] ) ) {
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
			?>

            <div id="message" class="updated fade">
                <p>
                    <strong>
						<?php
						_e( 'Created the terms ', "insert-term-at-once" );
						foreach ( $terms as $term ) {
							echo $term[0] . ' ';
						}
						_e( ' for ', "insert-term-at-once" );
						foreach ( $taxonomies as $tax ) {
							echo $tax . ' ';
						}
						_e( '.', "insert-term-at-once" ); ?>
                    </strong>
                </p>
            </div>
		<?php }
	} else { ?>
        <div id="message" class="updated fade">
            <p>
                <strong>
					<?php
					_e( 'Failed to update term. ', "insert-term-at-once" );
					if ( ! isset( $_POST['itao_check'] ) ) {
						_e( 'Taxonomy is not set.', "insert-term-at-once" );
					}
					if ( ! isset( $_FILES['itao_csv'] ) ) {
						_e( 'CSV is not set.', "insert-term-at-once" );
					}
					?>
                </strong>
            </p>
        </div>
	<?php }
}
$message = ""; ?>
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
                    <img src="../wp-content/plugins/insert-term-at-once/img/icon_coffee.png" alt="buy me a coffee"
                         style="height:60px; margin: 10px; float:left;"/>
                    <p>Hi! This plugin from <a href="https://susu.mu?f=itao" target="_blank" title="Susumu Seino">Susumu
                            Seino</a>'s Insert Term at Once.</p>
                    <p>I'm been spending many hours to develop that plugin. <br/>If you like and use this plugin, you
                        can <strong>buy a cup of coffee</strong>.</p>
                </div>
            </div>
			<?php echo $message; ?>

            <form method="post" action="<?php echo admin_url( 'options-general.php?page=insert-term-at-once' ); ?>"
                  enctype="multipart/form-data">

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
                                <th><?php _e( "All delete term", "insert-term-at-once" ) ?></th>
                                <td>
                                    <label>
                                        <input type="checkbox" name="itao_delete"
                                               value="1"> <?php _e( "All delete terms", "insert-term-at-once" ) ?>
                                    </label>
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
                                    <input type="submit" name="insert_term_at_once_submit" class="button button-primary"
                                           value="<?php _e( 'Update term &raquo;' ); ?>"/>
                                </td>
                            </tr>
                        </table>

                    </div>
                </div>

            </form>
        </div>
    </div>
 
<?php ;