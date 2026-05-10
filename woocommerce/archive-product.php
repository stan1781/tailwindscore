<?php
/**
 * Shop archive template — loop only; loop item delegates to adapter + product-card component.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

do_action( 'woocommerce_before_main_content' );
get_template_part( 'template-parts/woocommerce/archive-discovery' );

do_action( 'woocommerce_after_main_content' );

get_footer( 'shop' );
