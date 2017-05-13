<?php
$output = $icon = $link_icon = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );;


/* Styles */
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );

$text_styles = array();
$text_style = '';

if( !empty( $text_font_size ) ) {
    $text_styles[] = 'font-size:' . $text_font_size;
}

if( !empty( $text_color ) ) {
    $text_styles[] = 'color:' . $text_color;
}

if( !empty( $text_margin ) ) {
    $text_margin_array = explode( ',', $text_margin );
    $text_margin_css = implode( ' ', $text_margin_array );
    $text_styles[] = 'margin:' . $text_margin_css;
}

if( !empty( $text_styles ) ) {
    $text_style = 'style='. implode( ';', $text_styles ).'';
}

// Icon style
$icon_styles = array();
$icon_style = '';

if( !empty( $icon_size ) ) {
    $icon_styles[] = 'font-size:' . $icon_size;
}

if( !empty( $icon_color ) ) {
    $icon_styles[] = 'color:' . $icon_color;
}

if( !empty( $icon_styles ) ) {
    $icon_style = 'style='. implode( ';', $icon_styles ).'';
}

$subscribe_id = uniqid('cta-subscribe-');

if( !empty( ${'icon_' . $icon_type} ) ) {
    $icon = '<span class="' . esc_attr( ${'icon_' . $icon_type} ) . '"></span>';
}

$output .= '<div class="call-to-action'. esc_attr( $css_class ) .'">';
$output .= '<div class="call-to-action-inner clearfix">';
if( ! empty( $content ) ) {
    $output .= '<div class="call-to-action__text">';
    if( !empty( $icon ) ) {
        $output .= '<div class="call-to-action__text-icon" '. esc_attr( $icon_style ) .'>' . $icon . '</div>';
    }
    $output .= '<div class="call-to-action__text-body" '. esc_attr( $text_style ) .'>'. wpb_js_remove_wpautop( $content, true ) .'</div>';
    $output .= '</div>';
}
if( $cta_type == 'subscribe_form' ) {

    $output .= '<form action="/" class="form form_cta-subscribe" id="'. esc_attr( $subscribe_id ) .'">';
    $output .= '<button class="form__field-button btn btn_size_sm btn_view_default btn_type_outline" type="submit">'. __( 'Subscribe', 'healthcoach' ) .'</button>';
    $output .= '<div class="form__field_email"><input class="form__field-email" type="email" name="email" placeholder="' . __( 'Enter your e-mail address', 'healthcoach' ) . '"></div>';
    $output .= '</form>';
}

if( $cta_type == 'button' ) {
    $output .= '<div class="call-to-action__buttons-group">';

    if( !empty( $button1 ) ) {
        $button1 = vc_build_link( $button1 );

        if( $button1['url'] ) {
            if( ! $button1['target'] ) {
                $button1['target'] = '_self';
            }

            $output .= '<a href="'. esc_url( $button1['url'] ) .'" class="btn btn_view_default btn_type_outline" target="'. esc_attr( $button1['target'] ) .'">'. esc_html( $button1['title'] ) .'</a>';
        }
    }

    if( !empty( $button2 ) ) {
        $button2 = vc_build_link( $button2 );

        if( $button2['url'] ) {
            if( ! $button2['target'] ) {
                $button2['target'] = '_self';
            }

            $output .= '<a href="'. esc_url( $button2['url'] ) .'" class="btn btn_view_default" target="'. esc_attr( $button2['target'] ) .'">'. esc_html( $button2['title'] ) .'</a>';
        }
    }

    $output .= '</div>';
}

if( $cta_type == 'link' && !empty( $link ) ) {
    $link = vc_build_link( $link );
    if( $link['url'] ) {
        if( ! $link['target'] ) {
            $link['target'] = '_self';
        }

        if( $link_icon_enable && ${'link_icon_' . $link_icon_type} ) {

            $link_icon_style = '';
            $link_icon_styles = array();

            if ( !empty( $link_icon_size ) ) {
                $link_icon_styles[] = 'font-size:' . $link_icon_size;
            }

            if ( !empty( $link_icon_color ) ) {
                $link_icon_styles[] = 'color:' . $link_icon_color;
            }

            if( !empty( $link_icon_styles ) ) {
                $link_icon_style = 'style=' . esc_attr( implode( ';', $link_icon_styles ) ) . '';
            }

            $link_icon = '<span class="call-to-action__link-icon" '. esc_attr( $link_icon_style ) .'><span class="' . esc_attr( ${'link_icon_' . $link_icon_type} ) . '"></span></span>';
        }

        $link_style = '';
        $link_styles = array();

        if ( !empty( $link_size ) ) {
            $link_styles[] = 'font-size:' . $link_size;
        }

        if ( !empty( $link_color ) ) {
            $link_styles[] = 'color:' . $link_color;
        }

        if( !empty( $link_icon_styles ) ) {
            $link_style = 'style=' . esc_attr( implode( ';', $link_styles ) ) . '';
        }

        $output .= '<a href="'. esc_url( $link['url'] ) .'" class="call-to-action__link" target="'. esc_attr( $link['target'] ) .'" '. esc_attr( $link_style ) .'>'. esc_html( $link['title'] ) .''. $link_icon .'</a>';
    }
}
$output .= '</div>';
$output .= '</div>';
echo $output;

if( $cta_type == 'subscribe_form' ) {
?>
<script type="text/javascript">
    jQuery(document).ready( function($){
    $("<?php echo esc_js( "#" . $subscribe_id ) ?>").on('submit', function () {
            var $this = $(this);
            $.ajax({
                type: 'POST',
                data: 'action=stm_subscribe&email=' + $($this).find(".stm-subscribe-form-text").val(),
                dataType: 'json',
                url: ajaxurl,
                success: function (json) {
                    if (json['success']) {
                        $($this).replaceWith('<div class="success_message">' + json['success'] + '</div>');
                    }
                    if (json['error']) {
                        alert(json['error']);
                    }
                }
            });
            return false;
        });
    })
</script>
<?php
}