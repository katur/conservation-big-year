<?php
/*
Plugin Name: Background Patterns
Description: Use a library of patterns to decorate your website.
Author: Hassan Derakhshandeh
Version: 0.2.1
Author URI: http://tween.ir/


		* 	Copyright (C) 2011  Hassan Derakhshandeh
		*	http://tween.ir/
		*	hassan.derakhshandeh@gmail.com

		This program is free software; you can redistribute it and/or modify
		it under the terms of the GNU General Public License as published by
		the Free Software Foundation; either version 2 of the License, or
		(at your option) any later version.

		This program is distributed in the hope that it will be useful,
		but WITHOUT ANY WARRANTY; without even the implied warranty of
		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
		GNU General Public License for more details.

		You should have received a copy of the GNU General Public License
		along with this program; if not, write to the Free Software
		Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class Boom_Background_Patterns {

	function Boom_Background_Patterns() {
		add_action( 'admin_footer-appearance_page_custom-background', array( &$this, 'admin_scripts' ) );
		add_action( 'load-appearance_page_custom-background', array( &$this, 'load_custom_background_page' ) );
		add_action( 'after_setup_theme', array( &$this, 'add_theme_support' ), 1000 );

		define( 'PATTERNS_BASE_URI', 'http://weber.ir/patterns/patterns/' );
	}

	/**
	 * Save bg options in the database & loads the thickbox library.
	 *
	 * @since 0.2
	 * @return void
	 */
	function load_custom_background_page() {

		/* queue thickbox library */
		add_thickbox();

		if( isset( $_POST['background_image'] ) && $_POST['background_pattern_updated'] == 1 ) {
			set_theme_mod( 'background_image', esc_url( $_POST['background_image'] ) ); // used by WP
			set_theme_mod( 'background_image_thumb', esc_url( $_POST['background_image'] ) );
			set_theme_mod( 'background_repeat', 'repeat' );
			set_theme_mod( 'background_pattern', $_POST['background_pattern'] ); // used by the plugin
			set_theme_mod( 'background_pattern_page', $_POST['background_pattern_page'] );
		}
	}

	/**
	 * Prints JavaScript codes in the background options page
	 *
	 * @since 0.1
	 * @return void
	 */
	function admin_scripts() { ?>
		<script>
			var bg_patterns_base_url = '<?php echo PATTERNS_BASE_URI; ?>';
			jQuery(function($){
				$('#submit').after('<input type="button" class="button-secondary" id="pattern-select" value=" Pattern Library " />');
				$('#pattern-select').click(function(){
					jQuery.get( "<?php echo plugins_url( 'options.php', __FILE__ ) ?>", function(b) {
						jQuery("#select-pattern").remove();
						$(b).hide().appendTo('body');
						var width = jQuery(window).width(),
							height = jQuery(window).height();
						width = 720 < width ? 720 : width;
						width -= 80;
						height -= 84;
						tb_show( "Select a Pattern", "#TB_inline?width=" + width + "&height=" + height + "&inlineId=select-pattern");
					})
				});
				$('#save-background-options').before('<input type="hidden" name="background_image" id="background_image" value="<?php echo get_theme_mod('background_image') ?>" /><input type="hidden" name="background_pattern" id="background_pattern" value="<?php echo get_theme_mod('background_pattern') ?>" /><input type="hidden" name="background_pattern_page" id="background_pattern_page" value="<?php echo get_theme_mod('background_pattern_page') ?>" /><input type="hidden" name="background_pattern_updated" id="background_pattern_updated" value="0" />');
			});
		</script>
	<?php }

	/**
	 * Register support for WP 3.0 Custom Backgrounds even if the theme doesn't support it.
	 *
	 * @since 0.1
	 * @return void
	 */
	function add_theme_support() {
		if( ! current_theme_supports( 'custom-background' ) )
			add_custom_background( array( &$this, 'custom_background_cb' ) );
	}

	/**
	 * Default custom background callback.
	 *
	 * @since 0.2.1
	 * @see add_custom_background()
	 */
	function custom_background_cb() {
		$background = get_background_image();
		$color = get_background_color();
		if ( ! $background && ! $color )
			return;

		$style = $color ? "background-color: #$color;" : '';

		if ( $background ) {
			$image = " background-image: url('$background');";

			$repeat = get_theme_mod( 'background_repeat', 'repeat' );
			if ( ! in_array( $repeat, array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ) ) )
				$repeat = 'repeat';
			$repeat = " background-repeat: $repeat;";

			$position = get_theme_mod( 'background_position_x', 'left' );
			if ( ! in_array( $position, array( 'center', 'right', 'left' ) ) )
				$position = 'left';
			$position = " background-position: top $position;";

			$attachment = get_theme_mod( 'background_attachment', 'scroll' );
			if ( ! in_array( $attachment, array( 'fixed', 'scroll' ) ) )
				$attachment = 'scroll';
			$attachment = " background-attachment: $attachment;";

			$style .= $image . $repeat . $position . $attachment;
		}
	?>
	<style type="text/css">
	body { <?php echo trim( $style ); ?> }
	</style>
	<?php
	}
}
new Boom_Background_Patterns;