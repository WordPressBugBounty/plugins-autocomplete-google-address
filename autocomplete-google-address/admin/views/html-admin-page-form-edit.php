<?php
/**
 * Provides the modern admin area view for the form edit page.
 *
 * @package    Autocomplete_Google_Address
 * @subpackage Autocomplete_Google_Address/admin/views
 */

defined( 'ABSPATH' ) || exit;

global $post;

// Get the current saved values
$mode              = get_post_meta( $post->ID, 'Nish_aga_mode', true ) ?: 'single_line';
$activate_globally = get_post_meta( $post->ID, 'Nish_aga_activate_globally', true );
$main_selector     = get_post_meta( $post->ID, 'Nish_aga_main_selector', true );
$country_restriction = get_post_meta( $post->ID, 'Nish_aga_country_restriction', true );
$language_override = get_post_meta( $post->ID, 'Nish_aga_language_override', true );

// Single Line fields
$lat_selector      = get_post_meta( $post->ID, 'Nish_aga_lat_selector', true );
$lng_selector      = get_post_meta( $post->ID, 'Nish_aga_lng_selector', true );
$place_id_selector = get_post_meta( $post->ID, 'Nish_aga_place_id_selector', true );

// Smart Mapping fields
$street_selector         = get_post_meta( $post->ID, 'Nish_aga_street_selector', true );
$city_selector           = get_post_meta( $post->ID, 'Nish_aga_city_selector', true );
$state_selector          = get_post_meta( $post->ID, 'Nish_aga_state_selector', true );
$zip_selector            = get_post_meta( $post->ID, 'Nish_aga_zip_selector', true );
$country_selector        = get_post_meta( $post->ID, 'Nish_aga_country_selector', true );
$map_lat_selector        = get_post_meta( $post->ID, 'Nish_aga_map_lat_selector', true );
$map_lng_selector        = get_post_meta( $post->ID, 'Nish_aga_map_lng_selector', true );
$smart_place_id_selector = get_post_meta( $post->ID, 'Nish_aga_smart_place_id_selector', true );
$state_format            = get_post_meta( $post->ID, 'Nish_aga_state_format', true ) ?: 'long';
$country_format          = get_post_meta( $post->ID, 'Nish_aga_country_format', true ) ?: 'long';

// Page selector data
$all_pages      = get_pages();
$selected_pages = get_post_meta( $post->ID, 'Nish_aga_load_on_pages', true );
if ( ! is_array( $selected_pages ) ) {
	$selected_pages = array();
}

?>
<div class="aga-form-config-wrapper">
	<div class="aga-config-columns">
		<!-- Left Column -->
		<div class="aga-config-col">
			<div class="aga-box">
				<div class="aga-box-header">
					<span class="dashicons dashicons-admin-settings"></span>
					<h2><?php esc_html_e( 'Core Setup', 'autocomplete-google-address' ); ?></h2>
				</div>
				<div class="aga-box-body">
					<div class="aga-field-group">
						<label for="aga_main_selector"><?php esc_html_e( 'Trigger Field Selector', 'autocomplete-google-address' ); ?></label>
						<input type="text" id="aga_main_selector" name="Nish_aga_main_selector" value="<?php echo esc_attr( $main_selector ); ?>" class="widefat" placeholder="#id, .class, [name='field_name']" required />
						<p class="description">
							<?php esc_html_e( 'Find the selector by right-clicking the field on your form and choosing "Inspect".', 'autocomplete-google-address' ); ?>
							<br><code>ID: #billing_address</code> <code>Class: .address-field</code> <code>Name: [name="billing_address"]</code>
						</p>
					</div>
					<hr/>
					<div class="aga-field-group">
						<label><?php esc_html_e( 'Country Restriction', 'autocomplete-google-address' ); ?>
							<?php if ( function_exists( 'google_autocomplete' ) && ! google_autocomplete()->is_premium() ) : ?>
								<?php echo sprintf( '<a href="%s" target="_blank" style="color: red; font-weight: bold;"><small>%s</small></a>', esc_url( function_exists( 'google_autocomplete' ) ? google_autocomplete()->checkout_url() : '#' ), esc_html__( 'Unlock Pro', 'autocomplete-google-address' ) ); ?>
							<?php endif; ?>
						</label>
						<input type="text" name="Nish_aga_country_restriction" value="<?php echo esc_attr( $country_restriction ); ?>" class="widefat" placeholder="e.g., US, CA (Leave blank for all)" <?php echo ( function_exists( 'google_autocomplete' ) && ! google_autocomplete()->is_premium() ) ? 'disabled' : ''; ?> />
						<p class="description"><?php esc_html_e( 'Optional: Enter a two-letter country code to restrict results.', 'autocomplete-google-address' ); ?></p>
					</div>
					<hr/>
					<div class="aga-field-group">
						<label><?php esc_html_e( 'Language', 'autocomplete-google-address' ); ?>
							<?php if ( function_exists( 'google_autocomplete' ) && ! google_autocomplete()->is_premium() ) : ?>
								<?php echo sprintf( '<a href="%s" target="_blank" style="color: red; font-weight: bold;"><small>%s</small></a>', esc_url( function_exists( 'google_autocomplete' ) ? google_autocomplete()->checkout_url() : '#' ), esc_html__( 'Unlock Pro', 'autocomplete-google-address' ) ); ?>
							<?php endif; ?>
						</label>
						<input type="text" name="Nish_aga_language_override" value="<?php echo esc_attr( $language_override ); ?>" class="widefat" placeholder="e.g., en, fr" <?php echo ( function_exists( 'google_autocomplete' ) && ! google_autocomplete()->is_premium() ) ? 'disabled' : ''; ?> />
						<p class="description"><?php esc_html_e( 'Optional: Enter a language code for results. Defaults to site language.', 'autocomplete-google-address' ); ?></p>
					</div>
				</div>
			</div>
			<div class="aga-box">
				<div class="aga-box-header">
					<span class="dashicons dashicons-controls-play"></span>
					<h2><?php esc_html_e( 'Activation', 'autocomplete-google-address' ); ?></h2>
				</div>
				<div class="aga-box-body">
					<div class="aga-field-group">
						<label for="Nish_aga_load_on_pages"><?php esc_html_e( 'Load on Specific Pages', 'autocomplete-google-address' ); ?>
							<?php if ( function_exists( 'google_autocomplete' ) && ! google_autocomplete()->is_premium() ) : ?>
								<?php echo sprintf( '<a href="%s" target="_blank" style="color: red; font-weight: bold;"><small>%s</small></a>', esc_url( function_exists( 'google_autocomplete' ) ? google_autocomplete()->checkout_url() : '#' ), esc_html__( 'Unlock Pro', 'autocomplete-google-address' ) ); ?>
							<?php endif; ?>
						</label>
						<select id="Nish_aga_load_on_pages" name="Nish_aga_load_on_pages[]" multiple="multiple" class="widefat select2" <?php echo ( function_exists( 'google_autocomplete' ) && ! google_autocomplete()->is_premium() ) ? 'disabled' : ''; ?> >
							<?php foreach ( $all_pages as $page ) : ?>
								<option value="<?php echo esc_attr( $page->ID ); ?>" <?php selected( in_array( $page->ID, $selected_pages, true ) ); ?> >
									<?php echo esc_html( $page->post_title ); ?>
								</option>
							<?php endforeach; ?>
						</select>
						<p class="description">
							<?php esc_html_e( 'Select pages where this should be active. Leave blank if using Global Activation.', 'autocomplete-google-address' ); ?>
						</p>
					</div>
					<hr/>
					<div class="aga-field-group">
						<label for="Nish_aga_activate_globally" style="display: inline-block; vertical-align: middle; margin-right: 10px;"><?php esc_html_e( 'Global Activation', 'autocomplete-google-address' ); ?></label>
						<label class="aga-switch">
							<input type="checkbox" id="Nish_aga_activate_globally" name="Nish_aga_activate_globally" value="1" <?php checked( $activate_globally, '1' ); ?> >
							<span class="aga-slider"></span>
						</label>
						<p class="description"><?php esc_html_e( 'Activate on all pages. Use for checkout pages or if the page selector doesn\'t work.', 'autocomplete-google-address' ); ?></p>
					</div>
				</div>
			</div>
		</div>
		<!-- Right Column -->
		<div class="aga-config-col">
			<div class="aga-box">
				<div class="aga-box-header">
					<span class="dashicons dashicons-forms"></span>
					<h2><?php esc_html_e( 'Mapping Mode', 'autocomplete-google-address' ); ?></h2>
				</div>
				<div class="aga-box-body">
					<div class="aga-field-group">
						<div class="aga-mode-selector">
							<input type="radio" id="mode_single_line" name="Nish_aga_mode" value="single_line" <?php checked( $mode, 'single_line' ); ?> >
							<label for="mode_single_line"><?php esc_html_e( 'Single Line', 'autocomplete-google-address' ); ?></label>
							<input type="radio" id="mode_smart_mapping" name="Nish_aga_mode" value="smart_mapping" <?php checked( $mode, 'smart_mapping' ); ?> >
							<label for="mode_smart_mapping"><?php esc_html_e( 'Smart Mapping', 'autocomplete-google-address' ); ?></label>
						</div>
					</div>
					<!-- Single Line Panel -->
					<div id="aga-panel-single_line" class="aga-mode-panel <?php echo 'single_line' === $mode ? 'active' : ''; ?>">
						<p><?php esc_html_e( 'The full address is placed in the trigger field. You can optionally save extra data to hidden fields.', 'autocomplete-google-address' ); ?></p>
						<div class="aga-field-group">
							<label for="aga_lat_selector"><?php esc_html_e( 'Latitude Selector', 'autocomplete-google-address' ); ?></label>
							<input type="text" id="aga_lat_selector" name="Nish_aga_lat_selector" value="<?php echo esc_attr( $lat_selector ); ?>" class="widefat" placeholder="#id, .class, [name='field_name']" />
						</div>
						<div class="aga-field-group">
							<label for="aga_lng_selector"><?php esc_html_e( 'Longitude Selector', 'autocomplete-google-address' ); ?></label>
							<input type="text" id="aga_lng_selector" name="Nish_aga_lng_selector" value="<?php echo esc_attr( $lng_selector ); ?>" class="widefat" placeholder="#id, .class, [name='field_name']" />
						</div>
						<div class="aga-field-group">
							<label for="aga_place_id_selector"><?php esc_html_e( 'Place ID Selector', 'autocomplete-google-address' ); ?></label>
							<input type="text" id="aga_place_id_selector" name="Nish_aga_place_id_selector" value="<?php echo esc_attr( $place_id_selector ); ?>" class="widefat" placeholder="#id, .class, [name='field_name']" />
						</div>
					</div>
					<!-- Smart Mapping Panel -->
					<div id="aga-panel-smart_mapping" class="aga-mode-panel <?php echo 'smart_mapping' === $mode ? 'active' : ''; ?>">
						<p><?php esc_html_e( 'The address is split into multiple fields. Map each component below.', 'autocomplete-google-address' ); ?></p>
						<div class="aga-mapping-grid">
							<div class="aga-field-group">
								<label for="aga_street_selector"><?php esc_html_e( 'Street', 'autocomplete-google-address' ); ?>
									<?php if ( function_exists( 'google_autocomplete' ) && ! google_autocomplete()->is_premium() ) : ?>
										<?php echo sprintf( '<a href="%s" target="_blank" style="color: red; font-weight: bold;"><small>%s</small></a>', esc_url( function_exists( 'google_autocomplete' ) ? google_autocomplete()->checkout_url() : '#' ), esc_html__( 'Unlock Pro', 'autocomplete-google-address' ) ); ?>
									<?php endif; ?>
								</label>
								<input type="text" id="aga_street_selector" name="Nish_aga_street_selector" value="<?php echo esc_attr( $street_selector ); ?>" class="widefat" placeholder="#id, .class, [name='field_name']" <?php echo ( function_exists( 'google_autocomplete' ) && ! google_autocomplete()->is_premium() ) ? 'disabled' : ''; ?> />
							</div>
							<div class="aga-field-group">
								<label for="aga_city_selector"><?php esc_html_e( 'City', 'autocomplete-google-address' ); ?>
									<?php if ( function_exists( 'google_autocomplete' ) && ! google_autocomplete()->is_premium() ) : ?>
										<?php echo sprintf( '<a href="%s" target="_blank" style="color: red; font-weight: bold;"><small>%s</small></a>', esc_url( function_exists( 'google_autocomplete' ) ? google_autocomplete()->checkout_url() : '#' ), esc_html__( 'Unlock Pro', 'autocomplete-google-address' ) ); ?>
									<?php endif; ?>
								</label>
								<input type="text" id="aga_city_selector" name="Nish_aga_city_selector" value="<?php echo esc_attr( $city_selector ); ?>" class="widefat" placeholder="#id, .class, [name='field_name']" <?php echo ( function_exists( 'google_autocomplete' ) && ! google_autocomplete()->is_premium() ) ? 'disabled' : ''; ?> />
							</div>
							<div class="aga-field-group">
								<label for="aga_state_selector"><?php esc_html_e( 'State/Region', 'autocomplete-google-address' ); ?>
									<?php if ( function_exists( 'google_autocomplete' ) && ! google_autocomplete()->is_premium() ) : ?>
										<?php echo sprintf( '<a href="%s" target="_blank" style="color: red; font-weight: bold;"><small>%s</small></a>', esc_url( function_exists( 'google_autocomplete' ) ? google_autocomplete()->checkout_url() : '#' ), esc_html__( 'Unlock Pro', 'autocomplete-google-address' ) ); ?>
									<?php endif; ?>
								</label>
								<input type="text" id="aga_state_selector" name="Nish_aga_state_selector" value="<?php echo esc_attr( $state_selector ); ?>" class="widefat" placeholder="#id, .class, [name='field_name']" <?php echo ( function_exists( 'google_autocomplete' ) && ! google_autocomplete()->is_premium() ) ? 'disabled' : ''; ?> />
							</div>
							<div class="aga-field-group">
								<label for="aga_zip_selector"><?php esc_html_e( 'Zip/Postal Code', 'autocomplete-google-address' ); ?>
									<?php if ( function_exists( 'google_autocomplete' ) && ! google_autocomplete()->is_premium() ) : ?>
										<?php echo sprintf( '<a href="%s" target="_blank" style="color: red; font-weight: bold;"><small>%s</small></a>', esc_url( function_exists( 'google_autocomplete' ) ? google_autocomplete()->checkout_url() : '#' ), esc_html__( 'Unlock Pro', 'autocomplete-google-address' ) ); ?>
									<?php endif; ?>
								</label>
								<input type="text" id="aga_zip_selector" name="Nish_aga_zip_selector" value="<?php echo esc_attr( $zip_selector ); ?>" class="widefat" placeholder="#id, .class, [name='field_name']" <?php echo ( function_exists( 'google_autocomplete' ) && ! google_autocomplete()->is_premium() ) ? 'disabled' : ''; ?> />
							</div>
							<div class="aga-field-group">
								<label for="aga_country_selector"><?php esc_html_e( 'Country', 'autocomplete-google-address' ); ?>
									<?php if ( function_exists( 'google_autocomplete' ) && ! google_autocomplete()->is_premium() ) : ?>
										<?php echo sprintf( '<a href="%s" target="_blank" style="color: red; font-weight: bold;"><small>%s</small></a>', esc_url( function_exists( 'google_autocomplete' ) ? google_autocomplete()->checkout_url() : '#' ), esc_html__( 'Unlock Pro', 'autocomplete-google-address' ) ); ?>
									<?php endif; ?>
								</label>
								<input type="text" id="aga_country_selector" name="Nish_aga_country_selector" value="<?php echo esc_attr( $country_selector ); ?>" class="widefat" placeholder="#id, .class, [name='field_name']" <?php echo ( function_exists( 'google_autocomplete' ) && ! google_autocomplete()->is_premium() ) ? 'disabled' : ''; ?> />
							</div>
							<div class="aga-field-group">
								<label for="aga_smart_place_id_selector"><?php esc_html_e( 'Place ID', 'autocomplete-google-address' ); ?>
									<?php if ( function_exists( 'google_autocomplete' ) && ! google_autocomplete()->is_premium() ) : ?>
										<?php echo sprintf( '<a href="%s" target="_blank" style="color: red; font-weight: bold;"><small>%s</small></a>', esc_url( function_exists( 'google_autocomplete' ) ? google_autocomplete()->checkout_url() : '#' ), esc_html__( 'Unlock Pro', 'autocomplete-google-address' ) ); ?>
									<?php endif; ?>
								</label>
								<input type="text" id="aga_smart_place_id_selector" name="Nish_aga_smart_place_id_selector" value="<?php echo esc_attr( $smart_place_id_selector ); ?>" class="widefat" placeholder="#id, .class, [name='field_name']" <?php echo ( function_exists( 'google_autocomplete' ) && ! google_autocomplete()->is_premium() ) ? 'disabled' : ''; ?> />
							</div>
							<div class="aga-field-group">
								<label for="aga_map_lat_selector"><?php esc_html_e( 'Latitude', 'autocomplete-google-address' ); ?>
									<?php if ( function_exists( 'google_autocomplete' ) && ! google_autocomplete()->is_premium() ) : ?>
										<?php echo sprintf( '<a href="%s" target="_blank" style="color: red; font-weight: bold;"><small>%s</small></a>', esc_url( function_exists( 'google_autocomplete' ) ? google_autocomplete()->checkout_url() : '#' ), esc_html__( 'Unlock Pro', 'autocomplete-google-address' ) ); ?>
									<?php endif; ?>
								</label>
								<input type="text" id="aga_map_lat_selector" name="Nish_aga_map_lat_selector" value="<?php echo esc_attr( $map_lat_selector ); ?>" class="widefat" placeholder="#id, .class, [name='field_name']" <?php echo ( function_exists( 'google_autocomplete' ) && ! google_autocomplete()->is_premium() ) ? 'disabled' : ''; ?> />
								</div>
								<div class="aga-field-group">
									<label for="aga_map_lng_selector"><?php esc_html_e( 'Longitude', 'autocomplete-google-address' ); ?>
										<?php if ( function_exists( 'google_autocomplete' ) && ! google_autocomplete()->is_premium() ) : ?>
											<?php echo sprintf( '<a href="%s" target="_blank" style="color: red; font-weight: bold;"><small>%s</small></a>', esc_url( function_exists( 'google_autocomplete' ) ? google_autocomplete()->checkout_url() : '#' ), esc_html__( 'Unlock Pro', 'autocomplete-google-address' ) ); ?>
										<?php endif; ?>
									</label>
									<input type="text" id="aga_map_lng_selector" name="Nish_aga_map_lng_selector" value="<?php echo esc_attr( $map_lng_selector ); ?>" class="widefat" placeholder="#id, .class, [name='field_name']" <?php echo ( function_exists( 'google_autocomplete' ) && ! google_autocomplete()->is_premium() ) ? 'disabled' : ''; ?> />
								</div>
							</div>
							<hr/>
							<h4><?php esc_html_e( 'Formatting Options', 'autocomplete-google-address' ); ?></h4>
							<div class="aga-mapping-grid">
								<div class="aga-field-group">
									<label for="aga_state_format"><?php esc_html_e( 'State Format', 'autocomplete-google-address' ); ?>
										<?php if ( function_exists( 'google_autocomplete' ) && ! google_autocomplete()->is_premium() ) : ?>
											<?php echo sprintf( '<a href="%s" target="_blank" style="color: red; font-weight: bold;"><small>%s</small></a>', esc_url( function_exists( 'google_autocomplete' ) ? google_autocomplete()->checkout_url() : '#' ), esc_html__( 'Unlock Pro', 'autocomplete-google-address' ) ); ?>
										<?php endif; ?>
									</label>
									<select id="aga_state_format" name="Nish_aga_state_format" <?php echo ( function_exists( 'google_autocomplete' ) && ! google_autocomplete()->is_premium() ) ? 'disabled' : ''; ?>>
										<option value="long" <?php selected( $state_format, 'long' ); ?>><?php esc_html_e( 'Long Name (e.g., California)', 'autocomplete-google-address' ); ?></option>
										<option value="short" <?php selected( $state_format, 'short' ); ?>><?php esc_html_e( 'Short Name (e.g., CA)', 'autocomplete-google-address' ); ?></option>
									</select>
								</div>
								<div class="aga-field-group">
									<label for="aga_country_format"><?php esc_html_e( 'Country Format', 'autocomplete-google-address' ); ?>
										<?php if ( function_exists( 'google_autocomplete' ) && ! google_autocomplete()->is_premium() ) : ?>
											<?php echo sprintf( '<a href="%s" target="_blank" style="color: red; font-weight: bold;"><small>%s</small></a>', esc_url( function_exists( 'google_autocomplete' ) ? google_autocomplete()->checkout_url() : '#' ), esc_html__( 'Unlock Pro', 'autocomplete-google-address' ) ); ?>
										<?php endif; ?>
									</label>
									<select id="aga_country_format" name="Nish_aga_country_format" <?php echo ( function_exists( 'google_autocomplete' ) && ! google_autocomplete()->is_premium() ) ? 'disabled' : ''; ?>>
										<option value="long" <?php selected( $country_format, 'long' ); ?>><?php esc_html_e( 'Long Name (e.g., United States)', 'autocomplete-google-address' ); ?></option>
										<option value="short" <?php selected( $country_format, 'short' ); ?>><?php esc_html_e( 'Short Name (e.g., US)', 'autocomplete-google-address' ); ?></option>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>