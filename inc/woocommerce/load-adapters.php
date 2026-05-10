<?php
/**
 * Load WooCommerce adapters (pure prop maps; no templates).
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

require_once TAILWINDSCORE_PATH . 'inc/woocommerce/adapters/badge.php';
require_once TAILWINDSCORE_PATH . 'inc/woocommerce/adapters/price.php';
require_once TAILWINDSCORE_PATH . 'inc/woocommerce/adapters/product-card.php';
require_once TAILWINDSCORE_PATH . 'inc/woocommerce/adapters/gallery.php';
require_once TAILWINDSCORE_PATH . 'inc/woocommerce/adapters/gallery/runtime-props.php';
require_once TAILWINDSCORE_PATH . 'inc/woocommerce/adapters/review.php';

require_once TAILWINDSCORE_PATH . 'inc/woocommerce/adapters/single-product/title.php';
require_once TAILWINDSCORE_PATH . 'inc/woocommerce/adapters/single-product/rating.php';
require_once TAILWINDSCORE_PATH . 'inc/woocommerce/adapters/single-product/price.php';
require_once TAILWINDSCORE_PATH . 'inc/woocommerce/adapters/single-product/excerpt.php';
