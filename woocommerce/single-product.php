<?php
/**
 * Single product shell — hook bridge preserving WooCommerce execution order.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

while ( have_posts() ) {
	the_post();
	wc_get_template_part( 'content', 'single-product' );
}

get_footer( 'shop' );
