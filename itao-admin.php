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

<?php if ( ! empty( $_POST['itao_csvfile'] ) ) : ?>
	<div id="message" class="updated fade"><p><strong><?php _e( 'Term updated.' ) ?></strong></p></div>
<?php elseif ( ! empty( $_POST ) ) : ?>
	<div id="message" class="updated fade"><p><strong><?php _e( 'Options saved.' ) ?></strong></p></div>
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

						<table class="form-table">
							<tr>
								<th><?php _e( "WP Plugin Admin Checkbox", "insert-term-at-once" ) ?></th>
								<td><input type="checkbox" name="itao_check" value="1" <?php if ( stripslashes( $itao_options['itao_check'] ) == "1" ) {
										echo "checked='checked'";
									} ?> /></td>
							</tr>

							<tr>
								<th><?php _e( "WP Plugin Admin Dropdown", "insert-term-at-once" ) ?></th>
								<td>
									<select name="itao_dropdown">
										<option value="wordpress" <?php if ( $itao_options['itao_dropdown'] == 'WordPress' )
											echo "selected='selected'" ?>>WordPress
										</option>
										<option value="plugins" <?php if ( $itao_options['itao_dropdown'] == 'Plugins' )
											echo "selected='selected'" ?>>Plugins
										</option>
										<option value="admin" <?php if ( $itao_options['itao_dropdown'] == 'Admin' )
											echo "selected='selected'" ?>>Admin
										</option>
									</select>
								</td>
							</tr>

							</tr>

							<tr>
								<th><?php _e( "WP Plugin Admin Number", "insert-term-at-once" ) ?></th>
								<td>
									<input type="text" name="itao_num" size="2" value="<?php echo stripslashes( $itao_options['itao_num'] ); ?>"/>
								</td>
							</tr>
							<tr>
								<th><?php _e( "WP Plugin Admin Radio button", "insert-term-at-once" ) ?>*</th>
								<td>
									<label for="stats-enabled"><input type="radio" name="itao_radio" id="itao-enabled" value="1" <?php if ( $itao_options['itao_radio'] )
											echo "checked='checked'" ?> /> Enabled</label>
									<label for="stats-disabled"><input type="radio" name="itao_radio" id="itao-disabled" value="0" <?php if ( ! $itao_options['itao_radio'] )
											echo "checked='checked'" ?> /> Disabled</label>
								</td>
							</tr>
							<tr>
								<th><?php _e( "WP Plugin Admin Textarea", "insert-term-at-once" ) ?></th>
								<td>
									<textarea name="itao_code" rows="3" cols="35"><?php echo stripslashes( $itao_options['itao_code'] ); ?></textarea></td>
							</tr>
							<tr>
								<th><?php _e( "CSV File", "insert-term-at-once" ) ?></th>
								<td>
									<input type="file" name="csv"/>
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


