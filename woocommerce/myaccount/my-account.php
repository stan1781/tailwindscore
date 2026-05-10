<?php
/**
 * Premium account shell wrapper.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$copy            = tailwindscore_account_surface_copy();
$content_html    = '';
$navigation_items = wc_get_account_menu_items();

ob_start();
do_action( 'woocommerce_account_content' );
$content_html = (string) ob_get_clean();

tailwindscore_account_part(
	'account-layout',
	array(
		'eyebrow'          => $copy['eyebrow'],
		'title'            => $copy['title'],
		'intro'            => $copy['intro'],
		'navigation_items' => $navigation_items,
		'current_endpoint' => tailwindscore_account_current_endpoint(),
		'content_html'     => $content_html,
	)
);
