<?php
//add_action( 'plugins_loaded', array( 'GF_Recently_Viewed_Products', 'get_instance' ) );

/**
 * Add recently viewed products to Gravity Form submissions
 *
 * This class does nothing in itself. Either instantiate it (anytime) and pass in
 * products using the filters in _get_current_recently_viewed_products or override
 * said method in a child class.
 */
class GF_Recently_Viewed_Products
{
    const VERSION = '1.1';

    protected $_field_key = 'recently_viewed_products';
    protected $_merge_tag = 'recently_viewed_products';

    public static function get_instance() {
        static $instance = false;

        if ( !$instance ) {
            $class = get_called_class();
            $instance = new $class();
        }

        return $instance;
    }

	protected function __construct() {
        load_plugin_textdomain( 'gform-recently-viewed-products', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

        // Store recently viewed products on form submission
		add_action( 'gform_entry_created', array( &$this, 'add_viewed_products' ) );

        // Show recently viewed products on GF entry page
		add_action( 'gform_entry_detail', array( &$this, 'output_details_template' ), 10, 2 );

        // Add merge tags to merge tags dropdown
		add_filter( 'gform_custom_merge_tags',  array( &$this, 'add_custom_merge_tags' ), 10, 4 );

        // Replace merge tags in templates
		add_filter( 'gform_replace_merge_tags', array( &$this, 'add_replace_merge_tags' ), 10, 7 );
	}

	public function add_viewed_products( $entry ) {
        if ( !$products = $this->_get_current_recently_viewed_products() )
            return;

        // Save space by only storing relevant data, but do store the product data
        // so the query doesn't have to be run every time.
        $data = array();
        foreach( $products as $product ) {
            $data[] = array(
                'ID'    => $product->ID,
                'title' => $product->post_title,
                'permalink' => get_permalink( $product )
            );
        }

        gform_update_meta( $entry['id'], $this->_field_key, $data );
	}

        /**
         * Gets recently viewed products on submission
         *
         * Nb: this method is called on form submission. Use the filters (see below)
         * or a child class to implement this method
         *
         * Use the following filters to modify recently viewed products:
         *   - gform_recently_viewed_product_ids    (array) List of post ids
         *   - gform_recently_viewed_products_query (array) Query for get_posts (use to change the post type)
         *   - gform_recently_viewed_products       (array) List of posts
         */
        protected function _get_current_recently_viewed_products() {
            $viewed_products = apply_filters( 'gform_recently_viewed_product_ids', $viewed_products );

            if ( !$viewed_products )
                return false;

            $products = get_posts( apply_filters( 'gform_recently_viewed_products_query', array(
                'post_type'   => 'any',
                'post__in'    => $viewed_products,
                'orderby'     => 'post__in',
                'numberposts' => -1
            ) ) );

            return apply_filters( 'gform_recently_viewed_products', $products );
        }

	public function output_details_template( $form, $lead ) {

        echo "<div class='postbox' id='viewed-products'>";
        echo "<h3 style='cursor:default'>" . __( 'Recently Viewed Products', 'wc-gf-viewed-products' ) . "</h3>";
        echo "<div class='inside'>";

        $data = gform_get_meta( $lead['id'], $this->_field_key );
        if ( $data && is_array( $data ) ) {
            echo "<ol>";
            foreach( $data as $product ) {
                echo "<li style='list-style-type:decimal'><a href='{$product['permalink']}'>{$product['title']}</a></li>";
            }
            echo "</ol>";
        } else {
            _e( 'No recently viewed products available for this entry.', 'wc-gf-viewed-products' );
        }

        echo "</div><!-- .inside -->";
        echo "</div><!-- #viewed-products -->";

	}


	public function add_custom_merge_tags( $merge_tags ) {
		$merge_tags[] = array( 'label' => 'Recently Viewed Products', 'tag' => '{' . $this->_merge_tag . '}' );
		return $merge_tags;
	}

	public function add_replace_merge_tags($text, $form, $entry, $url_encode, $esc_html, $nl2br, $format) {
		if( strpos( $text, '{' . $this->_merge_tag . '}' ) !== false ) {
			$text = $this->_do_merge_tag( $text, $entry[ 'id' ], $format );
		}

		return $text;
	}

        private function _do_merge_tag( $text, $entryid, $format ) {
            $to_replace = "";

            $data = gform_get_meta( $entryid, $this->_field_key );
            if ( $data && !empty( $data ) ) {

                if( $format == 'html' ) {
                    // Email helps me cling to the past
                    $to_replace .= "<table width='99%' border='0' cellpadding='1' cellspacing='0' bgcolor='#EAEAEA'><tr><td><table width='100%' border='0' cellpadding='5' cellspacing='0' bgcolor='#FFFFFF'>\n";
                    $to_replace .= "<tr bgcolor='#EAF2FA'>
                                        <td colspan='2'>
                                            <font style='font-family:sans-serif;font-size:12px'><strong>" . __( 'Recently Viewed Products', 'wc-gf-viewed-products' ) . "</strong></font>
                                        </td>
                                    </tr><tbody>\n";
                }

                $i = 1;
                foreach( $data as $product ) {
                    if( $format == 'html' ) {
                        $to_replace .= "<tr bgcolor='#FFFFFF'>
                                            <td width='20'>&nbsp;</td>
                                            <td>
                                                <font style='font-family:sans-serif;font-size:12px'>$i. <a href='{$product['permalink']}'>{$product['title']}</a></font>
                                            </td>
                                        </tr>";
                    } else {
                        $to_replace .= "$i. {$product['title']} - {$product['permalink']}\n";
                    }

                    $i++;
                }
                if( $format == 'html' ) {
                    $to_replace .= "</tbody>\n</table></td></tr></table>\n\n";
                }

            }

            return str_replace( '{' . $this->_merge_tag . '}', $to_replace, $text );
        }

}