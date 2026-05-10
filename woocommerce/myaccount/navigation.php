<?php
/**
 * Premium account navigation.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

tailwindscore_account_part(
	'account-navigation',
	array(
		'items'            => wc_get_account_menu_items(),
		'current_endpoint' => tailwindscore_account_current_endpoint(),
		'nav_id'           => 'ts-account-nav-panel',
	)
);
