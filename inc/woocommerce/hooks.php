<?php
/**
 * WooCommerce hooks — load layout-flow hook groups.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/hooks/archive-hooks.php';
require_once __DIR__ . '/hooks/single-product-hooks.php';
require_once __DIR__ . '/hooks/single-product-summary.php';
require_once __DIR__ . '/hooks/cart-hooks.php';
require_once __DIR__ . '/hooks/pdp-layout.php';
require_once __DIR__ . '/hooks/gallery-runtime.php';
require_once __DIR__ . '/hooks/pdp-commerce-experience.php';
require_once __DIR__ . '/hooks/variation-experience.php';
require_once __DIR__ . '/hooks/feedback-language.php';
require_once __DIR__ . '/hooks/checkout-feedback.php';
require_once __DIR__ . '/hooks/account-experience.php';
require_once __DIR__ . '/hooks/review-experience.php';
require_once __DIR__ . '/swatches/load.php';

/**
 * Extension surface for templates/plugins.
 */
add_action(
	'tailwindscore/woocommerce/bootstrap',
	static function (): void {
		// Placeholder — fired after hook files register listeners.
	},
	10,
	0
);
