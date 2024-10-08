<?php
/**
 * Accordion wrapper
 *
 * This file is being included into "../class-shortcodes.php" file's shortcode_render() method.
 *
 * @since    1.0
 * @version  1.5.0
 *
 * @param  integer active
 * @param  string behaviour  Synonym for "mode" attribute.
 * @param  string class
 * @param  boolean filter
 * @param  string mode
 */



//Shortcode attributes
	$defaults = apply_filters( 'wmhook_shortcode_' . '_defaults', array(
			'active'    => 0,
			'behaviour' => 'accordion',
			'class'     => '',
			'filter'    => false,
			'mode'      => '',
		), $shortcode );
	$atts = apply_filters( 'wmhook_shortcode_' . '_attributes', $atts, $shortcode );
	$atts = shortcode_atts( $defaults, $atts, $prefix_shortcode . $shortcode );

//Helper variables
	global $wm_shortcode_helper_variable;
	$wm_shortcode_helper_variable = $shortcode; //Passing the parent shortcode for "wm_item" shortcodes

//Validation
	//active
		$atts['active'] = absint( $atts['active'] );
	//mode
		if ( $atts['behaviour'] && ! $atts['mode'] ) {
			$atts['mode'] = $atts['behaviour'];
		}
		$atts['mode'] = trim( $atts['mode'] );
		if ( ! in_array( $atts['mode'], array( 'accordion', 'toggle' ) ) ) {
			$atts['mode'] = $atts['behaviour'] = 'accordion';
		}
	//content
		$content = apply_filters( 'wmhook_shortcode_' . '_content', $content, $shortcode, $atts );
		$content = apply_filters( 'wmhook_shortcode_' . $shortcode . '_content', $content, $atts );
		if ( $atts['filter'] ) {
			//Prepare filter output
				$tags = array();
				preg_match_all( '/(data-tag-names)=("[^"]*")/i', $content, $tags );
				if (
						is_array( $tags )
						&& ! empty( $tags )
						&& isset( $tags[2] )
					) {
					$tags = $tags[2];
					$tags = implode( '|', $tags );
					$tags = str_replace( '"', '', $tags );
					$tags = explode( '|', $tags );
					$tags = array_unique( $tags );
					asort( $tags );

					//Filter output
						$atts['filter'] = '<li class="wm-filter-items-all active"><a href="#" data-filter="*">' . __( 'All', 'webman-amplifier' ) . '</a></li>';
						foreach ( $tags as $tag ) {
							$tag_class = esc_attr( 'tag-' . sanitize_html_class( $tag ) );
							$atts['filter'] .= '<li class="wm-filter-items-' . $tag_class . '"><a href="#" data-filter=".' . $tag_class . '">' . html_entity_decode( $tag ) . '</a></li>';
						}
						$atts['filter'] = '<div class="wm-filter"><ul>' . $atts['filter'] . '</ul></div>';
				}
			//Implement filter output
				$atts['content'] = $atts['filter'] . '<div class="wm-filter-this-simple">' . $content . '</div>';
		} else {
			$atts['content'] = $content;
		}
	//class
		$atts['class'] = trim( 'wm-accordion clearfix ' . trim( $atts['class'] ) );
		if ( $atts['filter'] ) {
			$atts['class'] .= ' filterable-simple';
		}
		$atts['class'] = apply_filters( 'wmhook_shortcode_' . $shortcode . '_classes', $atts['class'], $atts );

//Enqueue scripts
	$enqueue_scripts = array(
			'wm-shortcodes-accordion'
		);

	WM_Shortcodes::enqueue_scripts( $shortcode, $enqueue_scripts, $atts );


// Output

	if ( ! empty( $atts['content'] ) ) {
		$output = '<div class="' . esc_attr( $atts['class'] ) . '" data-active="' . esc_attr( $atts['active'] ) . '" data-mode="' . esc_attr( $atts['mode'] ) . '">' . $atts['content'] . '</div>';
	} else {
		$output = esc_html__( 'Sorry, there is nothing to display here&hellip;', 'webman-amplifier' );
	}
