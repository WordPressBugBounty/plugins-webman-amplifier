<?php
/**
 * Item (can be accordion/tab item)
 *
 * This file is being included into "../class-shortcodes.php" file's shortcode_render() method.
 *
 * @since    1.0
 * @version  1.5.0
 *
 * @param  string icon
 * @param  string heading_tag (heading tag option for better accessibility setup)
 * @param  string tags
 * @param  string title
 */



//Shortcode attributes
	$defaults = apply_filters( 'wmhook_shortcode_' . '_defaults', array(
			'icon'        => '',
			'heading_tag' => 'h3',
			'tags'        => '',
			'title'       => 'TITLE?',
		), $shortcode );
	$atts = apply_filters( 'wmhook_shortcode_' . '_attributes', $atts, $shortcode );
	$atts = shortcode_atts( $defaults, $atts, $prefix_shortcode . $shortcode );

//Helper variables
	global $wm_shortcode_helper_variable; //Passing the parent shortcode for "wm_item" shortcodes

//Validation
	//content
		$atts['content'] = apply_filters( 'wmhook_shortcode_' . '_content', $content, $shortcode, $atts );
		$atts['content'] = apply_filters( 'wmhook_shortcode_' . $shortcode . '_content', $atts['content'], $atts );
	//title
		$atts['title'] = wp_kses( trim( $atts['title'] ), WM_Shortcodes::get_inline_tags() );
	//icon
		$atts['icon'] = trim( $atts['icon'] );
		if ( $atts['icon'] ) {
			$atts['title'] = '<span class="' . esc_attr( $atts['icon'] ) . '" aria-hidden="true"> </span>' . $atts['title'];
		}
	//tags
		$atts['tag_names'] = array();
		$atts['tags']      = trim( $atts['tags'] );
		$atts['tags']      = str_replace( ', ', ',', $atts['tags'] );
		$atts['tags']      = explode( ',', $atts['tags'] );
		foreach ( $atts['tags'] as $key => $tag ) {
			$tag = trim( $tag );
			if ( $tag ) {
				$atts['tag_names'][$key] = $tag;
				$atts['tags'][$key]      = 'tag-' . sanitize_html_class( $tag );
			} else {
				unset( $atts['tags'][$key] );
			}
		}
		$atts['tags'] = implode( ' ', $atts['tags'] );
	//class
		$atts['class'] = array(
				'wrapper' => 'wm-item wm-item-wrap',
				'title'   => 'wm-item-title',
				'content' => 'wm-item-content clearfix',
			);
		$atts['class'] = apply_filters( 'wmhook_shortcode_' . $shortcode . '_classes', $atts['class'], $atts );

//Output
	if ( 'accordion' == $wm_shortcode_helper_variable ) {
	//Markup for "wm_accordion" parent shortcode

		$output  = "\r\n" . '<div class="' .  esc_attr( trim( $atts['class']['wrapper'] . ' ' . $atts['tags'] ) ) . '">';
		$output .= '<' . tag_escape( $atts['heading_tag'] ) . ' class="' . esc_attr( trim( $atts['class']['title'] . ' ' . $atts['tags'] ) ) . '" data-tags="' . esc_attr( $atts['tags'] ) . '" data-tag-names="' . esc_attr( implode( '|', $atts['tag_names'] ) ) . '">' . $atts['title'] . '</' . tag_escape( $atts['heading_tag'] ) . '>';
		$output .= '<div class="' . esc_attr( trim( $atts['class']['content'] . ' ' . sanitize_html_class( wp_strip_all_tags( $atts['title'] ) ) ) ) . '">' . $atts['content'] . '</div>';
		$output .= '</div>' . "\r\n";

	} elseif ( 'tabs' == $wm_shortcode_helper_variable ) {
	//Markup for "wm_tabs" parent shortcode

		$i = rand( 100, 999 );

		$output  = "\r\n" . '<div class="' . esc_attr( trim( $atts['class']['wrapper'] . ' ' . sanitize_html_class( wp_strip_all_tags( $atts['title'] ) ) . '_' . $i ) ) . '" id="' . sanitize_html_class( wp_strip_all_tags( $atts['title'] ) ) . '_' . $i . '" data-title="' . sanitize_html_class( wp_strip_all_tags( $atts['title'] ) ) . '_' . $i . '&&' . esc_attr( $atts['title'] ) . '">';
		$output .= '<' . tag_escape( $atts['heading_tag'] ) . ' class="screen-reader-text ' . esc_attr( trim( $atts['class']['title'] ) ) . '">' . $atts['title'] . '</' . tag_escape( $atts['heading_tag'] ) . '>';
		$output .= $atts['content'];
		$output .= '</div>' . "\r\n";

	}
