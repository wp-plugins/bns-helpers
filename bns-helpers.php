<?php
/*
Plugin Name: BNS Helpers
Plugin URI: http://buynowshop.com/
Description: A collections of shortcodes and other helpful functions
Version: 0.3
Author: Edward Caissie
Author URI: http://edwardcaissie.com/
Textdomain: bns-helpers
License: GNU General Public License v2
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

/**
 * BNS Helpers
 *
 * @package     BNS_Helpers
 * @version     0.3
 * @date        April 2015
 *
 * @link        http://buynowshop.com/plugins/bns-helpers/
 * @link        https://github.com/Cais/bns-helpers/
 * @link        http://wordpress.org/plugins/bns-helpers/
 *
 * @author      Edward Caissie <edward.caissie@gmail.com>
 * @copyright   Copyright (c) 2015, Edward Caissie
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License version 2, as published by the
 * Free Software Foundation.
 *
 * You may NOT assume that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to:
 *
 *      Free Software Foundation, Inc.
 *      51 Franklin St, Fifth Floor
 *      Boston, MA  02110-1301  USA
 *
 * The license for this software can also likely be found here:
 * http://www.gnu.org/licenses/gpl-2.0.html
 */
class BNS_Helpers {

	/**
	 * Constructor
	 *
	 * Bringing it all together ...
	 *
	 * @package BNS_Helpers
	 * @since   0.1
	 *
	 * @link    https://developer.wordpress.org/reference/functions/__return_true/
	 * @link    https://developer.wordpress.org/reference/functions/do_shortcode/
	 * @link    https://wordpress.org/plugins/bns-login
	 *
	 * @uses    (CONSTANT)  WP_CONTENT_DIR
	 * @uses    __return_true (as a callback)
	 * @uses    add_filter
	 * @uses    add_shortcode
	 * @uses    content_URL
	 * @uses    do_shortcode (as a callback)
	 * @uses    plugin_dir_path
	 * @uses    plugin_dir_url
	 */
	function __construct() {

		/**
		 * Check installed WordPress version for compatibility
		 *
		 * @internal    Version 3.6 in reference to `shortcode_atts` filter option
		 * @link        https://developer.wordpress.org/reference/functions/shortcode_atts/
		 */
		global $wp_version;
		$exit_message = sprintf( __( 'BNS Helpers requires WordPress version 3.6 or newer. %1$s', 'bns-helpers' ), '<a href="http://codex.wordpress.org/Upgrading_WordPress">' . __( 'Please Update!', 'bns-helpers' ) . '</a>' );
		$exit_message .= '<br />';
		$exit_message .= sprintf( __( 'In reference to the shortcode default attributes filter. See %1$s.', 'bns-helpers' ), '<a href="https://developer.wordpress.org/reference/functions/shortcode_atts/">' . __( 'this link', 'bns-helpers' ) . '</a>' );
		if ( version_compare( $wp_version, "3.6", "<" ) ) {
			exit( $exit_message );
		}

		/** Define some constants to save some keying */
		if ( ! defined( 'BNS_HELPERS_URL' ) ) {
			define( 'BNS_HELPERS_URL', plugin_dir_url( __FILE__ ) );
		}
		if ( ! defined( 'BNS_HELPERS_PATH' ) ) {
			define( 'BNS_HELPERS_PATH', plugin_dir_path( __FILE__ ) );
		}

		/** Define location for BNS plugin customizations */
		if ( ! defined( 'BNS_CUSTOM_PATH' ) ) {
			define( 'BNS_CUSTOM_PATH', WP_CONTENT_DIR . '/bns-customs/' );
		}
		if ( ! defined( 'BNS_CUSTOM_URL' ) ) {
			define( 'BNS_CUSTOM_URL', content_url( '/bns-customs/' ) );
		}

		/** Enqueue Scripts and Styles */
		add_action( 'wp_enqueue_scripts', array(
			$this,
			'scripts_and_styles'
		) );

		/** Add shortcode parsing to Text Widgets */
		add_filter( 'widget_text', 'do_shortcode' );

		/** Add support for BNS Login use of dashicons */
		add_filter( 'bns_login_dashed_set', '__return_true' );

		/** Provide (temporary?) support against stored XSS issue with too long comments */
		add_filter( 'preprocess_comment', array(
			$this,
			'stop_over_sized_comments'
		) );

		/** Add Child Pages Shortcode */
		add_shortcode( 'child_pages', array(
			$this,
			'child_pages_shortcode'
		) );

		/** Add Drop-Down Child Pages Shortcode */
		add_shortcode( 'dropdown_child_pages', array(
			$this,
			'dropdown_child_pages_shortcode'
		) );

		/** Add Tool-Tip Shortcode */
		add_shortcode( 'tool_tip', array(
			$this,
			'tool_tip_shortcode'
		) );

	}


	/**
	 * Enqueue Plugin Scripts and Styles
	 *
	 * Adds plugin stylesheet and allows for custom stylesheet to be added by end-user.
	 *
	 * @package BNS_Helpers
	 * @since   0.1
	 *
	 * @uses    (CONSTANT)  BNS_CUSTOM_PATH
	 * @uses    (CONSTANT)  BNS_CUSTOM_URL
	 * @uses    (CONSTANT)  BNS_HELPERS_PATH
	 * @uses    (CONSTANT)  BNS_HELPERS_URL
	 * @uses    BNS_Helpers::plugin_data
	 * @uses    wp_enqueue_script
	 * @uses    wp_enqueue_style
	 */
	function scripts_and_styles() {

		/** @var object $bns_helpers_data - holds the plugin header data */
		$bns_helpers_data = $this->plugin_data();

		/** Enqueue Scripts */
		/** Enqueue toggling script which calls jQuery as a dependency */
		wp_enqueue_script( 'bns_helpers_script', BNS_HELPERS_URL . 'js/bns-helpers-script.js', array(
			'jquery',
			'jquery-ui-widget',
			'jquery-ui-tooltip',
			'jquery-ui-position',
			'jquery-ui-core'
		), $bns_helpers_data['Version'], true );

		/** Enqueue Style Sheets */
		wp_enqueue_style( 'BNS-Helpers-Style', BNS_HELPERS_URL . 'css/bns-helpers-style.css', array(), $bns_helpers_data['Version'], 'screen' );

		/** This location is recommended as upgrade safe */
		if ( is_readable( BNS_CUSTOM_PATH . 'bns-helpers-custom-styles.css' ) ) {
			wp_enqueue_style( 'BNS-Helpers-Custom-Styles', BNS_CUSTOM_URL . 'bns-helpers-custom-styles.css', array(), $bns_helpers_data['Version'], 'screen' );
		}

	}


	/**
	 * Plugin Data
	 *
	 * Returns the plugin header data as an array
	 *
	 * @package    BNS_Helpers
	 * @since      0.1
	 *
	 * @uses       get_plugin_data
	 *
	 * @return array
	 */
	function plugin_data() {

		/** Call the wp-admin plugin code */
		require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		/** @var $plugin_data - holds the plugin header data */
		$plugin_data = get_plugin_data( __FILE__ );

		return $plugin_data;

	}


	/**
	 * Child Pages Shortcode
	 *
	 * Leverage `wp_list_pages` to output a list of linked child-pages of the
	 * current page.
	 *
	 * @package BNS_Helpers
	 * @since   0.1
	 *
	 * @link    https://developer.wordpress.org/reference/functions/wp_list_pages/
	 *
	 * @uses    (GLOBAL) $post
	 * @uses    __
	 * @uses    apply_filters
	 * @uses    get_option
	 * @uses    wp_list_pages
	 * @uses    wp_parse_args
	 *
	 * @param $atts
	 *
	 * @return null|string
	 */
	function child_pages_shortcode( $atts ) {

		/** We'll need some details from the `$post` global */
		global $post;

		/** @var array $required_arguments - these should not be changed */
		$required_arguments = array(
			'echo'     => 0,
			'child_of' => $post->ID,
		);

		/** @var array $defaults - optional arguments as shortcode parameters */
		$defaults = shortcode_atts( array(
			'authors'     => '',
			'date_format' => get_option( 'date_format' ),
			'depth'       => 0,
			'exclude'     => '',
			'include'     => '',
			'link_after'  => '',
			'link_before' => '',
			'post_type'   => 'page',
			'post_status' => 'publish',
			'show_date'   => '',
			'sort_column' => 'menu_order, post_title',
			'sort_order'  => '',
			'title_li'    => sprintf( __( 'Child Pages of "%1$s"', 'bns-helpers' ), $post->post_title ),
			'walker'      => ''
		), $atts, 'child_pages' );

		/** @var array $output_arguments - uses `wp_parse_args` to merge */
		$output_arguments = wp_parse_args( $required_arguments, $defaults );

		/** @var string $output - wrap `wp_list_pages` in its own `div` */
		$output = '<div class="bns-helpers-child-pages-output">';
		$output .= '<ul>' . wp_list_pages( $output_arguments ) . '</ul>';
		$output .= '</div><!-- .bns-helpers-child-pages-output -->';

		/** We're all set ... send the (filtered) results back */

		//return wp_list_pages( $output_arguments );
		return apply_filters( 'bns_helpers_child_pages_shortcode', $output );

	}


	/**
	 * Drop-Down Child Pages Shortcode
	 *
	 * Leverage `wp_dropdown_pages` to output a drop-down list of child-pages of
	 * the current page. Also include parameters from `get_pages`
	 *
	 * @package BNS_Helpers
	 * @since   0.1
	 *
	 * @link    https://developer.wordpress.org/reference/functions/wp_dropdown_pages/
	 * @link    https://developer.wordpress.org/reference/functions/get_pages/
	 *
	 * @uses    (GLOBAL) $post
	 * @uses    __
	 * @uses    apply_filters
	 * @uses    get_bloginfo
	 * @uses    wp_dropdown_pages
	 * @uses    wp_parse_args
	 *
	 * @param $atts
	 *
	 * @return null|string
	 *
	 * @todo    Clean up the form $output code?
	 */
	function dropdown_child_pages_shortcode( $atts ) {

		/** We'll need some details from the `$post` global */
		global $post;

		/** @var array $required_arguments - these should not be changed */
		$required_arguments = array(
			'echo'     => 0,
			'child_of' => $post->ID,
		);

		/** @var array $defaults - optional arguments as shortcode parameters */
		$defaults = shortcode_atts( array(
			'depth'                 => 0,
			'selected'              => 0,
			'name'                  => 'page_id',
			'id'                    => null,
			'show_option_none'      => null,
			'show_option_no_change' => null,
			'option_none_value'     => null,
			/** Additional parameters from `get_pages` */
			'sort_order'            => 'ASC',
			'sort_column'           => 'post_title',
			'hierarchical'          => 1,
			'exclude'               => '',
			'include'               => '',
			'meta_key'              => '',
			'meta_value'            => '',
			'authors'               => '',
			'exclude_tree'          => '',
			'post_type'             => 'page',
			/** Arbitrary parameters */
			'dropdown_title'        => sprintf( __( 'Child Pages of "%1$s"', 'bns-helpers' ), $post->post_title )
		), $atts, 'dropdown_child_pages' );


		/** @var array $output_arguments - uses `wp_parse_args` to merge */
		$output_arguments = wp_parse_args( $required_arguments, $defaults );

		/** @var string $output - create a simple form output */
		$output = '<div class="bns-helpers-dropdown-child-pages-output">';
		$output .= '<form action="' . get_bloginfo( 'url' ) . '" method="get">';
		$output .= '<legend>' . $defaults['dropdown_title'] . '</legend>';
		$output .= wp_dropdown_pages( $output_arguments );
		$output .= '<input class="button" type="submit" name="submit" value="' . __( 'View', 'bns-helpers' ) . '" />';
		$output .= '</form>';
		$output .= '</div><!-- .bns-helpers-dropdown-child-pages-output -->';

		/** We're all set ... send the (filtered) results back */

		return apply_filters( 'bns_helpers_dropdown_child_pages_shortcode', $output );

	}


	/**
	 * Tool Tip Shortcode
	 *
	 * Produces a tool-tip shortcode useful for any number of ideas using the
	 * default of an exclamation mark ( ! ) as the reference point.
	 *
	 * @package BNS_Helpers
	 * @since   0.2
	 *
	 * @uses    apply_filters
	 * @uses    esc_attr
	 * @uses    esc_html
	 * @uses    shortcode_atts
	 * @uses    wp_localize_script
	 *
	 * @param      $atts
	 * @param null $content
	 *
	 * @return mixed|void
	 */
	function tool_tip_shortcode( $atts, $content = null ) {

		/** @var array $defaults - optional arguments as shortcode parameters */
		$defaults = shortcode_atts( array(
			'character' => '!'
		), $atts, 'tool_tip' );

		/** Sanity check - no content no reason to create any output */
		if ( ! empty( $content ) ) {

			/** @var string $content - make sure the content is sanitized */
			$content = esc_attr( $content );

			/** Pass the $content to the `bns_helpers_script` JavaScript */
			wp_localize_script( 'bns_helpers_script', 'tool_tip_text', $content );

			/** @var string $tool_tip_output - create the output */
			$tool_tip_output = '<sup class="bns-tool-tip" title="' . $content . '">&nbsp;' . esc_html( $defaults['character'] ) . '&nbsp;</sup>';

		} else {

			$tool_tip_output = null;

		}

		return apply_filters( 'bns_helpers_tool_tip_output', $tool_tip_output );

	}


	/**
	 * Stop Over Sized Comments
	 *
	 * Just in case, if the comment exceeds 5000 characters we're not going to
	 * allow it as a preventative measure for a stored XSS vulnerability
	 *
	 * @link    http://klikki.fi/adv/wordpress2.html
	 *
	 * @package BNS_Helpers
	 * @since   0.3
	 *
	 * @uses    wp_die
	 *
	 * @param $comment
	 *
	 * @return mixed
	 */
	function stop_over_sized_comments( $comment ) {

		if ( strlen( $comment['comment_content'] ) > 5000 ) {
			wp_die( __( 'Comment is too long.', 'bns-helpers' ) );
		}

		return $comment;

	}


}

/** End class - BNS Helpers */

/** @var object $bns_helpers - initialize the class */
$bns_helpers = new BNS_Helpers();

/** ------------------------------------------------------------------------- */

/**
 * BNS Helpers In Plugin Update Message
 *
 * @package BNS_Helpers
 * @since   0.1
 *
 * @uses    get_transient
 * @uses    is_wp_error
 * @uses    set_transient
 * @uses    wp_kses_post
 * @uses    wp_remote_get
 *
 * @param $args
 */
function BNS_Helpers_in_plugin_update_message( $args ) {

	require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
	$bns_helpers_data = get_plugin_data( __FILE__ );

	$transient_name = 'bns_helpers_upgrade_notice_' . $args['Version'];
	if ( false === ( $upgrade_notice = get_transient( $transient_name ) ) ) {

		/** @var string $response - get the readme.txt file from WordPress */
		$response = wp_remote_get( 'https://plugins.svn.wordpress.org/bns-helpers/trunk/readme.txt' );

		if ( ! is_wp_error( $response ) && ! empty( $response['body'] ) ) {
			$matches = null;
		}
		$regexp         = '~==\s*Changelog\s*==\s*=\s*(.*)\s*=(.*)(=\s*' . preg_quote( $bns_helpers_data['Version'] ) . '\s*=|$)~Uis';
		$upgrade_notice = '';

		if ( preg_match( $regexp, $response['body'], $matches ) ) {
			$version = trim( $matches[1] );
			$notices = (array) preg_split( '~[\r\n]+~', trim( $matches[2] ) );

			if ( version_compare( $bns_helpers_data['Version'], $version, '<' ) ) {

				/** @var string $upgrade_notice - start building message (inline styles) */
				$upgrade_notice = '<style type="text/css">
							.bns_helpers_plugin_upgrade_notice { padding-top: 20px; }
							.bns_helpers_plugin_upgrade_notice ul { width: 50%; list-style: disc; margin-left: 20px; margin-top: 0; }
							.bns_helpers_plugin_upgrade_notice li { margin: 0; }
						</style>';

				/** @var string $upgrade_notice - start building message (begin block) */
				$upgrade_notice .= '<div class="bns_helpers_plugin_upgrade_notice">';

				$ul = false;

				foreach ( $notices as $index => $line ) {

					if ( preg_match( '~^=\s*(.*)\s*=$~i', $line ) ) {

						if ( $ul ) {
							$upgrade_notice .= '</ul><div style="clear: left;"></div>';
						}

						$upgrade_notice .= '<hr/>';

					}

					/** @var string $return_value - body of message */
					$return_value = '';

					if ( preg_match( '~^\s*\*\s*~', $line ) ) {

						if ( ! $ul ) {
							$return_value = '<ul">';
							$ul           = true;
						}
						/** End if - unordered list not started */

						$line = preg_replace( '~^\s*\*\s*~', '', htmlspecialchars( $line ) );
						$return_value .= '<li style=" ' . ( $index % 2 == 0 ? 'clear: left;' : '' ) . '">' . $line . '</li>';

					} else {

						if ( $ul ) {
							$return_value = '</ul><div style="clear: left;"></div>';
							$return_value .= '<p>' . $line . '</p>';
							$ul = false;
						} else {
							$return_value .= '<p>' . $line . '</p>';
						}

					}

					$upgrade_notice .= wp_kses_post( preg_replace( '~\[([^\]]*)\]\(([^\)]*)\)~', '<a href="${2}">${1}</a>', $return_value ) );

				}

				$upgrade_notice .= '</div>';

			}

		}

		/** Set transient - minimize calls to WordPress to once a day */
		set_transient( $transient_name, $upgrade_notice, DAY_IN_SECONDS );

	}

	echo $upgrade_notice;

}

/** Add Plugin Update Message */
add_action( 'in_plugin_update_message-' . plugin_basename( __FILE__ ), 'BNS_Helpers_in_plugin_update_message' );