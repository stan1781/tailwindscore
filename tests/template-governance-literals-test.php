<?php

declare(strict_types=1);

$expectations = array(
	'template-parts/cart-surface/cart-summary.php' => array(
		'Shipping and taxes calculated at checkout.',
		'View cart',
	),
	'template-parts/checkout/checkout-payment.php' => array(
		'No payment is needed for this order.',
		'Enter your billing details to view available payment methods.',
	),
	'template-parts/account/order-detail.php' => array(
		'Shipping method',
		'No shipping required',
		'Shipping address',
		'No shipping address provided.',
		'To be confirmed',
	),
	'woocommerce/single-product-reviews.php' => array(
		'Customer reviews',
		'Measured notes from customers, arranged with the same quiet hierarchy as the rest of the product story.',
		'Reviews pagination',
		'Purchase required to review',
		'Only customers who have purchased this item can leave a review, which keeps the conversation grounded in ownership.',
	),
);

foreach ( $expectations as $relative_path => $forbidden_literals ) {
	$absolute_path = __DIR__ . '/../' . $relative_path;
	$content       = file_get_contents( $absolute_path );

	if ( false === $content ) {
		fwrite( STDERR, "Could not read {$relative_path}\n" );
		exit( 1 );
	}

	foreach ( $forbidden_literals as $literal ) {
		if ( str_contains( $content, $literal ) ) {
			fwrite( STDERR, "Found inline governed literal in {$relative_path}: {$literal}\n" );
			exit( 1 );
		}
	}
}

fwrite( STDOUT, "OK\n" );
