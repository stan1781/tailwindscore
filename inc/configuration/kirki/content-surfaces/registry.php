<?php
/**
 * Curated content surface control registry.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * @return array<string, array<string, mixed>>
 */
function tailwindscore_kirki_configurable_content_surfaces(): array {
	return array(
		'announcement-bar-message'      => array(
			'section' => 'tailwindscore_site_shell_content',
		),
		'footer-brand-summary'          => array(
			'section' => 'tailwindscore_site_shell_content',
		),
		'support-message'               => array(
			'section' => 'tailwindscore_site_shell_content',
		),
		'checkout-reassurance-message'  => array(
			'section' => 'tailwindscore_checkout_content',
		),
		'account-message'               => array(
			'section' => 'tailwindscore_account_content',
		),
		'search-guidance-message'       => array(
			'section' => 'tailwindscore_search_content',
		),
	);
}
