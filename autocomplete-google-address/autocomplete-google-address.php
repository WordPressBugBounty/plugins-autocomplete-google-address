<?php
/**
 * Plugin Name:       Autocomplete Google Address
 * Plugin URI:        https://www.nishelement.com/google-autocomplete-pro
 * Description:       This plugin will help you to add autocomplete google addres features by using google place api
 * Version:           2.0.3
 * Requires at least: 5.0
 * Tested up to: 5.7
 * Author:            Md Nishath Khandakar
 * Author URI:        https://www.facebook.com/nishat.rafi.60
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       ga-auto
 * Domain Path:       /languages
 */

/*
**/
// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Don\'t make me mad';
	exit;
}
include('admin_settings.php');
define( 'AUTOCOMPLET_VERSION', '1.0.0' );
define( 'AUTOCOMPLET__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'AUTOCOMPLET__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

add_action('wp_enqueue_scripts', 'autocomplete_google_enqueue_scripts');
function autocomplete_google_enqueue_scripts() {
    $google_api_key = myprefix_get_option('google_place_api');

    // Enqueue the custom script that depends on Google Maps
    wp_enqueue_script(
        'autocomplet-custom',
        AUTOCOMPLET__PLUGIN_URL . 'src/custom.js',
        array('jquery-core', 'jquery'),
        '',
        true
    );

    // Enqueue the Google Maps API script
    wp_enqueue_script(
        'google-maps',
        'https://maps.googleapis.com/maps/api/js?key=' . (!empty($google_api_key) ? $google_api_key : 'AIzaSyB16sGmIekuGIvYOfNoW9T44377IU2d2Es') . '&loading=async&libraries=places',
        array('jquery-core', 'jquery', 'autocomplet-custom'),
        '1.0',
        true
    );

    // Add async and defer attributes to the Google Maps script
    wp_script_add_data('google-maps', 'async', true);
    wp_script_add_data('google-maps', 'defer', true);
}

// Putting on wp head
add_action('wp_head','autocomplet_set_google_autocompletegen');
function autocomplet_set_google_autocompletegen(){

	$search_fields = array();
	$gaaf_names = get_option('gaaf_field_name');

	$autocomplet_ids = myprefix_get_option( 'form_id' );
	if(!empty($autocomplet_ids)){
		$check_hash = explode(',', $autocomplet_ids);
		foreach($check_hash as $key){
			if(!empty($key)){
				$findhash = '#';
				if(strpos($key, $findhash) === false){
					$search_fields[] = ' #'.trim($key);
				}else{
					$search_fields[] = trim($key);
				}
			}
		}
	}

	if(count($search_fields) == 0){
		$search_fields[] =' .google_autocomplete';
	}



?>

	<script>
	var inputFields = '<?php echo implode(',' , $search_fields);?>';
	</script>
<?php }

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'support_link' );

function support_link( $links ) {
   $links[] = '<a href="https://checkout.freemius.com/mode/dialog/plugin/6886/plan/11211/" target="_blank">Go For Pro</a> | <a href="https://devsupport.vercel.app/" target="_blank">Contact Developer</a> | <a href="https://youtu.be/2vVqEOcOvKk?si=WOyROQ6dghAP3hkk" target="_blank">Setup Video</a>';
   return $links;
}
