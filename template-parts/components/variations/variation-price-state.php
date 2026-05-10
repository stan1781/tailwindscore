<?php
/**
 * Live region host for price / sale transitions (WC still outputs `.woocommerce-variation-price`).
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

?>
<div class="ts-variation-price-state screen-reader-text" data-ts-variation-price-state aria-live="polite" aria-atomic="true"></div>
