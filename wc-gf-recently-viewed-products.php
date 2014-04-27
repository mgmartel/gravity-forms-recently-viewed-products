<?php
// Exit if accessed directly
if ( !defined ( 'ABSPATH' ) )
    exit;

class WC_GF_Recently_Viewed_Products extends GF_Recently_Viewed_Products
{

    /**
     * Gets recently viewed products on submission
     *
     * Use the following filters to modify recently viewed products:
     *   - wc_gf_recently_viewed_product_ids    (array) List of post ids
     *   - wc_gf_recently_viewed_products_query (array) Query for get_posts (use to change the post type)
     *   - wc_gf_recently_viewed_products       (array) List of posts
     */
    protected function _get_current_recently_viewed_products() {
        if ( !isset( $_COOKIE['woocommerce_recently_viewed'] ) || empty( $_COOKIE['woocommerce_recently_viewed'] ) )
            return false;

        $viewed_products = explode( '|', $_COOKIE['woocommerce_recently_viewed'] );
        $viewed_products = array_reverse( array_filter( array_map( 'absint', $viewed_products ) ) );
        $viewed_products = apply_filters( 'wc_gf_recently_viewed_product_ids', $viewed_products );

        $products = get_posts( apply_filters( 'wc_gf_recently_viewed_products_query', array(
            'post_type'   => 'product',
            'post__in'    => $viewed_products,
            'orderby'     => 'post__in',
            'numberposts' => -1
        ) ) );

        return apply_filters( 'wc_gf_recently_viewed_products', $products );
    }

}