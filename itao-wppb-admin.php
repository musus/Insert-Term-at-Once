<?php
$wppb_options = get_option( 'wppb_options' );
if ( isset( $_POST['submit'] ) ) {

	$wppb_options['wppb_radio']    = htmlspecialchars( $_POST['wppb_radio'] );
	$wppb_options['wppb_check']    = htmlspecialchars( $_POST['wppb_check'] );
	$wppb_options['wppb_dropdown'] = htmlspecialchars( $_POST['wppb_dropdown'] );
	$wppb_options['wppb_num']      = htmlspecialchars( $_POST['wppb_num'] );
	$wppb_options['wppb_code']     = htmlspecialchars( $_POST['wppb_code'] );

	update_option( 'wppb_options', $wppb_options );
}
$message = "";
?>
<?php if ( ! empty( $_POST ) ) : ?>
	<div id="message" class="updated fade"><p><strong><?php _e( 'Options saved.' ) ?></strong></p></div>
<?php endif; ?>
<div class="wrap">
	<h2><?php _e( 'PV Count Swiper Configuration', 'pv-count-swiper' ); ?></h2>

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
				<div title="<?php _e( "Click to open/close", "pv-count-swiper" ); ?>" class="handlediv">
					<br>
				</div>
				<h3 class="hndle"><span><?php _e( "Is it work?", "pv-count-swiper" ); ?></span></h3>
				<div class="inside" style="display: block;">
					<img src="../wp-content/plugins/pv-count-swiper/img/icon_coffee.png" alt="buy me a coffee" style="height:60px; margin: 10px; float:left;"/>
					<p>Hi! This plugin from <a href="https://susu.mu?f=wppb" target="_blank" title="Susumu Seino">Susumu Seino</a>'s PV Count Swiper.</p>
					<p>I'm been spending many hours to develop that plugin. <br/>If you like and use this plugin, you can <strong>buy a cup of coffee</strong>.</p>
				</div>
			</div>
			<?php echo $message; ?>
			<form action="" method="post">


				<div class="postbox">
					<div title="<?php _e( "Click to open/close", "pv-count-swiper" ); ?>" class="handlediv">
						<br>
					</div>
					<h3 class="hndle"><span><?php _e( "Options", "pv-count-swiper" ); ?></span></h3>
					<div class="inside" style="display: block;">

						<table class="form-table">
							<tr>
								<th><?php _e( "WP Plugin Admin Checkbox", "pv-count-swiper" ) ?></th>
								<td><input type="checkbox" name="wppb_check" value="1" <?php if ( stripslashes( $wppb_options['wppb_check'] ) == "1" ) {
										echo "checked='checked'";
									} ?> /></td>
							</tr>

							<tr>
								<th><?php _e( "WP Plugin Admin Dropdown", "pv-count-swiper" ) ?></th>
								<td>
									<select name="wppb_dropdown">
										<option value="wordpress" <?php if ( $wppb_options['wppb_dropdown'] == 'WordPress' )
											echo "selected='selected'" ?>>WordPress
										</option>
										<option value="plugins" <?php if ( $wppb_options['wppb_dropdown'] == 'Plugins' )
											echo "selected='selected'" ?>>Plugins
										</option>
										<option value="admin" <?php if ( $wppb_options['wppb_dropdown'] == 'Admin' )
											echo "selected='selected'" ?>>Admin
										</option>
									</select>
								</td>
							</tr>

							</tr>

							<tr>
								<th><?php _e( "WP Plugin Admin Number", "pv-count-swiper" ) ?></th>
								<td>
									<input type="text" name="wppb_num" size="2" value="<?php echo stripslashes( $wppb_options['wppb_num'] ); ?>"/>
								</td>
							</tr>
							<tr>
								<th><?php _e( "WP Plugin Admin Radio button", "pv-count-swiper" ) ?>*</th>
								<td>
									<label for="stats-enabled"><input type="radio" name="wppb_radio" id="wppb-enabled" value="1" <?php if ( $wppb_options['wppb_radio'] )
											echo "checked='checked'" ?> /> Enabled</label>
									<label for="stats-disabled"><input type="radio" name="wppb_radio" id="wppb-disabled" value="0" <?php if ( ! $wppb_options['wppb_radio'] )
											echo "checked='checked'" ?> /> Disabled</label>
								</td>
							</tr>
							<tr>
								<th><?php _e( "WP Plugin Admin Textarea", "pv-count-swiper" ) ?></th>
								<td>
									<textarea name="wppb_code" rows="3" cols="35"><?php echo stripslashes( $wppb_options['wppb_code'] ); ?></textarea></td>
							</tr>

							<tr>
								<th></th>
								<td>
									<input type="submit" name="submit" class="button button-primary" value="<?php _e( 'Update options &raquo;' ); ?>"/>
								</td>
							</tr>
						</table>

					</div>
				</div>

			</form>
		</div>
	</div>
