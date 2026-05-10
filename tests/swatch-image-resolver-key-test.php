<?php

declare(strict_types=1);

define('ABSPATH', __DIR__);

function sanitize_title(string $value): string {
	return strtolower(trim($value));
}

class WC_Product_Variable {
	/** @var int[] */
	private array $children;

	/**
	 * @param int[] $children
	 */
	public function __construct(array $children) {
		$this->children = $children;
	}

	/**
	 * @return int[]
	 */
	public function get_children(): array {
		return $this->children;
	}
}

class FakeVariationProduct {
	/**
	 * @param array<string, string> $attributes
	 */
	public function __construct(
		private array $attributes,
		private int $imageId
	) {
	}

	public function is_type(string $type): bool {
		return 'variation' === $type;
	}

	/**
	 * @return array<string, string>
	 */
	public function get_attributes(): array {
		return $this->attributes;
	}

	public function get_image_id(): int {
		return $this->imageId;
	}
}

/** @var array<int, FakeVariationProduct> $GLOBALS['ts_test_products'] */
$GLOBALS['ts_test_products'] = array();

function wc_get_product(int $id): ?FakeVariationProduct {
	return $GLOBALS['ts_test_products'][ $id ] ?? null;
}

require_once __DIR__ . '/../inc/woocommerce/swatches/swatch-image-resolver.php';

$GLOBALS['ts_test_products'][101] = new FakeVariationProduct(
	array(
		'pa_color' => 'red',
	),
	123
);

$product = new WC_Product_Variable(array(101));
$actual  = tailwindscore_swatch_resolve_variation_featured_attachment_id($product, 'pa_color', 'red');

if (123 !== $actual) {
	fwrite(STDERR, "Expected variation featured image 123, got {$actual}\n");
	exit(1);
}

fwrite(STDOUT, "OK\n");
