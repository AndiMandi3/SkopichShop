<?php
/**
 * Plugin Name: Kama SpamBlock
 *
 * Description: Block spam when comment is posted by robot. Checks pings/trackbacks for real backlink. To disable the plugin define the constant: <code>define('KS_FORCE_DISABLE', 1);</code> in theme functions.php file.
 *
 * Text Domain: kama-spamblock
 * Domain Path: /languages
 *
 * Author: Kama
 * Author URI: http://wp-kama.ru
 * Plugin URI: http://wp-kama.ru/?p=95
 *
 * Version: 1.8
 */


add_action( 'plugins_loaded', 'kama_spamblock_init', 11 );
function kama_spamblock_init(){
	new Kama_Spamblock();
}


class Kama_Spamblock {

	const OPT_NAME = 'ks_options';

	static $process_comment_types = [ '', 'comment' ]; // `comment` for WP 5.5+

	public $opt;

	private $nonce;

	function __construct(){
		$this->opt = (object) array_merge( $this->def_opt(), get_option( self::OPT_NAME, array() ) );

		if( ! defined('DOING_AJAX') )
			load_plugin_textdomain( 'kama-spamblock', false, basename(dirname(__FILE__)) .'/languages' );

		if( is_admin() )
			$this->admin_init();

		if( ! is_admin() && ! defined('KS_FORCE_DISABLE') )
			$this->init();
	}

	function init(){
		// to exactly output js
		add_action( 'get_footer', array( $this, 'main_js') );
		add_action( 'wp_footer',  array( $this, 'main_js') );

		$this->nonce = self::make_nonce( date('jn') . $this->opt->unique_code );

		add_action( 'preprocess_comment', array( $this, 'spamblock'), 0);
	}

	## blocking
	function spamblock( $commentdata ){

		$com_type = $commentdata['comment_type'];

		// Pings and trackbacks protect
		if( in_array( $com_type, array('trackback','pingback'), true ) ){
			$external_html = wp_remote_retrieve_body( wp_remote_get( $commentdata['comment_author_url'] ) );

			// no back link
			if( ! preg_match('~<a[^>]+href=[\'"](https?:)?//'. preg_quote( parse_url( home_url(), PHP_URL_HOST ) ) .'~si', $external_html) )
				die('no backlink...');
		}
		// spam blocking. Only for comment_type == '' (comment)
		$_nonce = isset($_POST['ksbn_code']) ? trim( self::make_nonce($_POST['ksbn_code']) ) : false;

		if( in_array( $com_type, self::$process_comment_types, true ) && $_nonce !== $this->nonce ){
			wp_die( $this->block_form() );
		}

		return $commentdata;
	}

	static function make_nonce( $key ){

		if( preg_match('/^[a-f0-9]{32}$/', $key) )
			return $key; // уже md5

		return md5( $key );
	}

	function main_js(){
		static $done; if( isset($done) ) return; $done = 1; // do it once

		// if( ! is_singular() ) return; // in some themes this check not work
		?>
		<script type="text/javascript">
		try {
			var sbmt     = document.getElementById('<?php echo esc_html( $this->opt->sibmit_button_id ) ?>'),
				npt      = document.createElement('input'),
				d        = new Date(),
				__ksinit = function(){ sbmt.parentNode.insertBefore( npt, sbmt ); };

			npt.value = d.getUTCDate() +''+ (d.getUTCMonth()+1) + '<?php echo esc_html( $this->opt->unique_code ) ?>';
			npt.name  = 'ksbn_code';
			npt.type  = 'hidden';
			sbmt.onmousedown = __ksinit;
			sbmt.onkeypress  = __ksinit;
		}catch(e){}
		</script>
		<?php
	}

	## Output form when comment has been blocked
	function block_form(){
		unset( $_POST['ksbn_code'] );

		$fileds = '';
		foreach( (array) $_POST as $key => $val ){
			$fileds .= '<textarea style="display:none;" name="'. $key .'">'. esc_textarea( stripslashes($val) ) .'</textarea>';
		}

		$out  = '
		<h1>'. __('Antispam block your comment!','kama-spamblock') .'</h1>

		<form action="'. site_url('/wp-comments-post.php' ) .'" method="post">
			<p>
				'.
				sprintf(
					__('Copy %1$s to the field %2$s and press button','kama-spamblock'),
					'<code style="background:#eee;">'. $this->nonce .'</code>',
					'<input type="text" name="ksbn_code" value="" style="width:150px; border:1px solid #ccc; border-radius:3px; padding:.3em;" />'
				)
			.'
			</p>

			<input type="submit" style="height:70px;width:100%;font-size:35px;cursor:pointer;border:none;color:#fff;background:#555;" value="'. __('Send comment again','kama-spamblock') .'" />

			'. $fileds .'
		</form>';

		return $out;
	}

	## default_options
	function def_opt(){
		// тут никаких динамических опций!
		return array(
			'sibmit_button_id' => 'submit',
			'unique_code'      => 'uniq9065',
		);
	}


	// ADMIN -------

	## admin
	function admin_init(){
		add_action( 'admin_init', array( $this, 'admin_options' ) );
		add_filter('plugin_action_links_'. plugin_basename(__FILE__), array( $this, 'settings_link') );
	}

	function settings_link( $links ){
		array_push( $links, '<a href="'. admin_url('/options-discussion.php#wpfooter') .'">'. __('Settings','kama-spamblock') .'</a>' );
		return $links;
	}

	function admin_options(){
		add_settings_section( 'kama_spamblock', '', '', 'discussion' ); // set no title

		add_settings_field( self::OPT_NAME .'_field', __('Kama Spamblock settings','kama-spamblock'), array( $this, 'options_field'), 'discussion', 'kama_spamblock' );

		register_setting( 'discussion', self::OPT_NAME, array(__CLASS__, 'sanitize_opt') );
	}

	static function sanitize_opt( $opts ){

		foreach( $opts as $key => & $val ){
			if( 'sibmit_button_id' === $key )
				$val = sanitize_html_class( $val );
			elseif( 'unique_code' === $key )
				$val = preg_replace( '~[^A-Za-z0-9*%$#@!_-]~', '', $val );
			else
				$val = sanitize_text_field( $val );
		}

		return $opts;
	}

	function options_field(){
		echo '
		<p>
			<input type="text" name="'. self::OPT_NAME .'[sibmit_button_id]" value="'. esc_attr( $this->opt->sibmit_button_id ) .'" /> '.
			__('ID attribute of comment form submit button. Default: <code>submit</code>','kama-spamblock') .'
		</p>
		<p>
			<input type="text" name="'. self::OPT_NAME .'[unique_code]" value="'. esc_attr( $this->opt->unique_code ) .'" /> '.
			__('Any unique code. Change it if  you receave spam comment...','kama-spamblock') .'
		</p>
		';
	}

}
