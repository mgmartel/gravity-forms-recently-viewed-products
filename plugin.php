<?php
/*
Plugin Name: Gravity Forms / WooCommerce Recently Viewed Products
Plugin URI: http://trenvo.com
Description: Adds recently viewed products to all Gravity Form submissions
Version: 1.1
Author: Mike Martel
Author URI: http://trenvo.com
License: GPLv2
*/

/*
Copyright (C) 2014 Mike Martel

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*/

if ( !class_exists( 'GF_Recently_Viewed_Products' ) )
    require plugin_dir_path( __FILE__ ) . 'gf-recently-viewed-products.php';

if ( !class_exists( 'WC_GF_Recently_Viewed_Products' ) )
    require plugin_dir_path( __FILE__ ) . 'wc-gf-recently-viewed-products.php';

// Plugin depends on WooCommerce and GF, but doesn't fail if depedencies aren't met
add_action( 'plugins_loaded', array( 'WC_GF_Recently_Viewed_Products', 'get_instance' ) );