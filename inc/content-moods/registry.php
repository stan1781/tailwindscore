<?php
/**
 * Governed editorial commerce content mood registry.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * Default content mood key.
 */
function tailwindscore_content_mood_default_key(): string {
	return 'premium-commerce';
}

/**
 * Canonical content mood registry.
 *
 * @return array<string, array<string, mixed>>
 */
function tailwindscore_content_mood_registry(): array {
	$moods = array(
		'premium-commerce' => array(
			'key'                      => 'premium-commerce',
			'label'                    => 'Premium Commerce',
			'description'              => 'Calm, assured, premium commerce language with editorial restraint and steady reassurance.',
			'supported_surfaces'       => array(
				'announcement_language',
				'trust_messaging',
				'empty_states',
				'support_messaging',
				'newsletter_prompts',
				'footer_messaging',
				'checkout_reassurance',
				'account_messaging',
				'search_guidance',
			),
			'tone_rules'               => array(
				'voice'       => 'calm editorial commerce',
				'pacing'      => 'measured',
				'intensity'   => 'balanced',
				'prohibited'  => array(
					'aggressive DTC hype',
					'startup SaaS language',
					'AI assistant tone',
					'gamified commerce messaging',
					'conversion spam',
				),
			),
			'fallback_behavior'        => 'preset_mapped_then_surface_default',
			'localization_compatibility' => array(
				'translation_safe' => true,
				'slang_heavy'      => false,
				'locale_fragile'   => false,
				'short_sentences'  => true,
			),
			'surface_defaults'         => array(
				'announcement-bar-message'              => __( 'Complimentary shipping on domestic orders over $150.', 'tailwindscore' ),
				'trust-message'                         => __( 'Trust messaging should clarify shipping, returns, and confidence signals without fragmenting into per-component copy fields.', 'tailwindscore' ),
				'support-message'                       => __( 'Support information should stay concise, reassuring, and close to purchase-critical guidance.', 'tailwindscore' ),
				'footer-newsletter-copy'                => __( 'Use this surface for a concise editorial newsletter invitation, not a marketing wall of text.', 'tailwindscore' ),
				'footer-brand-summary'                  => __( 'Editorial commerce foundation for a calm, premium shopping journey.', 'tailwindscore' ),
				'checkout-payment-guidance-message'     => __( 'Choose a payment method and complete the order with one clear confirmation.', 'tailwindscore' ),
				'checkout-mobile-summary-message'       => __( 'Shipping, taxes, and discounts stay visible before purchase completion.', 'tailwindscore' ),
				'checkout-validation-summary-message'   => __( 'Please review the highlighted checkout details.', 'tailwindscore' ),
				'checkout-validation-title'             => __( 'Please review your checkout details', 'tailwindscore' ),
				'checkout-loading-message'              => __( 'Updating checkout', 'tailwindscore' ),
				'account-dashboard-message'             => __( 'Orders, addresses, downloads, and account details arranged in one calm post-purchase space.', 'tailwindscore' ),
				'account-orders-message'                => __( 'Track each purchase, reopen details when needed, and keep your post-purchase history easy to scan.', 'tailwindscore' ),
				'account-view-order-message'            => __( 'A clear summary of status, items, totals, and delivery information without dashboard clutter.', 'tailwindscore' ),
				'account-downloads-message'             => __( 'Keep downloadable products close at hand, with remaining access and expiry details presented quietly.', 'tailwindscore' ),
				'account-login-reassurance-message'     => __( 'Review orders, revisit downloads, and manage saved details from one considered post-purchase surface.', 'tailwindscore' ),
				'account-recovery-message'              => __( 'Enter the email address used for your account and we will send a reset link with the same measured pacing as the rest of the account experience.', 'tailwindscore' ),
				'account-recovery-caption'              => __( 'Use your account email or username to begin the reset flow.', 'tailwindscore' ),
				'account-reset-message'                 => __( 'Create a new password and return to your account without leaving the calm rhythm of the commerce flow.', 'tailwindscore' ),
				'account-reset-support-message'         => __( 'Choose a password that feels easy to remember and hard to guess.', 'tailwindscore' ),
				'account-address-guidance-message'      => __( 'Use the details below for a smoother return to checkout next time.', 'tailwindscore' ),
				'account-message'                       => __( 'Account language should feel composed, helpful, and respectful of the customer history it presents.', 'tailwindscore' ),
				'search-recent-searches-guidance-message' => __( 'Recent searches remain nearby so returning to a product path feels immediate and quiet.', 'tailwindscore' ),
				'search-predictive-empty-message'       => __( 'Try a broader product name or continue through a collection path.', 'tailwindscore' ),
				'cart-drawer-validation-title'          => __( 'Please review your bag', 'tailwindscore' ),
				'cart-drawer-loading-message'           => __( 'Updating bag', 'tailwindscore' ),
				'cart-drawer-update-error-message'      => __( 'We could not update the bag just now. Please try again.', 'tailwindscore' ),
				'cart-drawer-item-updated-message'      => __( 'Bag updated', 'tailwindscore' ),
				'cart-drawer-item-removed-message'      => __( 'Removed from bag', 'tailwindscore' ),
				'empty-state-cart-title'                => __( 'Your cart is empty', 'tailwindscore' ),
				'empty-state-cart-message'              => __( 'Begin with a considered selection and return here when you are ready to check out.', 'tailwindscore' ),
				'empty-state-search-results-message'    => __( 'Try a broader product name or continue through a collection path.', 'tailwindscore' ),
				'empty-state-orders-message'            => __( 'When an order is placed, its progress and details will appear here in a calm, easy-to-review timeline.', 'tailwindscore' ),
			),
		),
		'editorial'         => array(
			'key'                      => 'editorial',
			'label'                    => 'Editorial',
			'description'              => 'Quieter, more spacious language with restrained urgency and a more reflective tone.',
			'supported_surfaces'       => array(
				'announcement_language',
				'empty_states',
				'newsletter_prompts',
				'footer_messaging',
				'search_guidance',
			),
			'tone_rules'               => array(
				'voice'       => 'quiet editorial commerce',
				'pacing'      => 'slow',
				'intensity'   => 'low',
				'prohibited'  => array(
					'sales countdown language',
					'pushy urgency',
					'slang-heavy hooks',
				),
			),
			'fallback_behavior'        => 'premium_commerce_then_surface_default',
			'localization_compatibility' => array(
				'translation_safe' => true,
				'slang_heavy'      => false,
				'locale_fragile'   => false,
				'short_sentences'  => true,
			),
			'surface_defaults'         => array(
				'announcement-bar-message'           => __( 'Considered shipping, careful pacing, and a quieter path through the collection.', 'tailwindscore' ),
				'footer-newsletter-copy'             => __( 'Receive occasional notes on new arrivals, considered releases, and the evolving edit.', 'tailwindscore' ),
				'footer-brand-summary'               => __( 'Editorial commerce shaped for a composed, premium browsing rhythm.', 'tailwindscore' ),
				'search-recent-searches-guidance-message' => __( 'Recent searches quietly preserve a return path into the collection.', 'tailwindscore' ),
				'search-predictive-empty-message'    => __( 'Try a broader term or continue through a nearby collection path.', 'tailwindscore' ),
				'empty-state-search-results-title'   => __( 'No matching pieces found', 'tailwindscore' ),
				'empty-state-search-results-message' => __( 'Try a broader term or continue through a nearby collection path.', 'tailwindscore' ),
			),
		),
		'assured'           => array(
			'key'                      => 'assured',
			'label'                    => 'Assured',
			'description'              => 'Warm, confident reassurance suited to soft luxury commerce without tipping into hype.',
			'supported_surfaces'       => array(
				'announcement_language',
				'trust_messaging',
				'support_messaging',
				'checkout_reassurance',
				'account_messaging',
			),
			'tone_rules'               => array(
				'voice'       => 'warm premium reassurance',
				'pacing'      => 'measured',
				'intensity'   => 'warm',
				'prohibited'  => array(
					'hard-sell claims',
					'overblown exclusivity',
					'novelty-first slang',
				),
			),
			'fallback_behavior'        => 'premium_commerce_then_surface_default',
			'localization_compatibility' => array(
				'translation_safe' => true,
				'slang_heavy'      => false,
				'locale_fragile'   => false,
				'short_sentences'  => true,
			),
			'surface_defaults'         => array(
				'trust-message'                => __( 'Shipping, returns, and purchase guidance should feel steady, warm, and immediately clear.', 'tailwindscore' ),
				'support-message'              => __( 'Support is available with clear guidance before and after purchase.', 'tailwindscore' ),
				'checkout-payment-guidance-message' => __( 'Choose the payment method that suits you and finish with clear confirmation.', 'tailwindscore' ),
				'account-login-reassurance-message' => __( 'Sign in to revisit purchases, saved details, and a lasting customer relationship.', 'tailwindscore' ),
				'account-recovery-message'          => __( 'Enter your account email and we will send a reset link in the same calm rhythm as the rest of the account experience.', 'tailwindscore' ),
				'account-reset-message'             => __( 'Choose a new password and return to your account with clear, steady guidance.', 'tailwindscore' ),
				'account-reset-support-message'     => __( 'Choose a password that feels memorable to you and difficult for others to guess.', 'tailwindscore' ),
				'account-message'              => __( 'Order history, addresses, and account details are presented with calm clarity and lasting confidence.', 'tailwindscore' ),
				'account-address-guidance-message' => __( 'Save billing and shipping details for a smoother return to purchase.', 'tailwindscore' ),
				'account-downloads-message'    => __( 'Downloads remain close at hand, with access and expiry details presented in a calm, lasting rhythm.', 'tailwindscore' ),
			),
		),
		'confident'         => array(
			'key'                      => 'confident',
			'label'                    => 'Confident',
			'description'              => 'Slightly brisker commerce language that stays polished, direct, and premium.',
			'supported_surfaces'       => array(
				'announcement_language',
				'trust_messaging',
				'search_guidance',
				'checkout_reassurance',
			),
			'tone_rules'               => array(
				'voice'       => 'direct premium commerce',
				'pacing'      => 'brisk',
				'intensity'   => 'medium',
				'prohibited'  => array(
					'growth-hack phrasing',
					'app onboarding voice',
					'excessive exclamation',
				),
			),
			'fallback_behavior'        => 'premium_commerce_then_surface_default',
			'localization_compatibility' => array(
				'translation_safe' => true,
				'slang_heavy'      => false,
				'locale_fragile'   => false,
				'short_sentences'  => true,
			),
			'surface_defaults'         => array(
				'announcement-bar-message'     => __( 'Clear shipping thresholds and quick collection guidance for a confident shopping path.', 'tailwindscore' ),
				'checkout-payment-guidance-message' => __( 'Payment, review, and confirmation stay direct and easy to follow.', 'tailwindscore' ),
			),
		),
		'dramatic'          => array(
			'key'                      => 'dramatic',
			'label'                    => 'Dramatic',
			'description'              => 'High-contrast but still disciplined language for darker premium commerce moods.',
			'supported_surfaces'       => array(
				'announcement_language',
				'trust_messaging',
				'footer_messaging',
				'search_guidance',
			),
			'tone_rules'               => array(
				'voice'       => 'focused premium contrast',
				'pacing'      => 'controlled',
				'intensity'   => 'focused',
				'prohibited'  => array(
					'horror theatrics',
					'game-like copy',
					'overwritten mystique',
				),
			),
			'fallback_behavior'        => 'premium_commerce_then_surface_default',
			'localization_compatibility' => array(
				'translation_safe' => true,
				'slang_heavy'      => false,
				'locale_fragile'   => false,
				'short_sentences'  => true,
			),
			'surface_defaults'         => array(
				'announcement-bar-message' => __( 'Focused delivery, premium support, and a deliberate path through the collection.', 'tailwindscore' ),
				'footer-brand-summary'     => __( 'Premium commerce shaped with clear contrast, disciplined pacing, and a steady editorial voice.', 'tailwindscore' ),
				'search-recent-searches-guidance-message' => __( 'Recent searches keep a precise route back into the collection.', 'tailwindscore' ),
			),
		),
	);

	return apply_filters( 'tailwindscore/content_moods/registry', $moods );
}

/**
 * @return array<string, mixed>|null
 */
function tailwindscore_content_mood_definition( string $key ): ?array {
	$registry = tailwindscore_content_mood_registry();
	$key      = sanitize_key( $key );

	return isset( $registry[ $key ] ) && is_array( $registry[ $key ] ) ? $registry[ $key ] : null;
}

/**
 * Resolve the active content mood from the active preset mapping.
 */
function tailwindscore_content_mood_active_key(): string {
	if ( function_exists( 'tailwindscore_preset_active_definition' ) ) {
		$preset_map = tailwindscore_preset_active_definition()['content_mood_overrides'] ?? array();
		$key        = sanitize_key( (string) ( is_array( $preset_map ) ? ( $preset_map['mood_key'] ?? $preset_map['tone'] ?? '' ) : '' ) );

		if ( '' !== $key && is_array( tailwindscore_content_mood_definition( $key ) ) ) {
			return $key;
		}
	}

	return tailwindscore_content_mood_default_key();
}

/**
 * @return array<string, mixed>
 */
function tailwindscore_content_mood_active_definition(): array {
	$definition = tailwindscore_content_mood_definition( tailwindscore_content_mood_active_key() );

	return is_array( $definition ) ? $definition : ( tailwindscore_content_mood_definition( tailwindscore_content_mood_default_key() ) ?? array() );
}

/**
 * Resolve a mood-governed fallback for a surface key.
 *
 * @param mixed $fallback Base surface fallback.
 * @return mixed
 */
function tailwindscore_content_mood_surface_fallback( string $surface_key, $fallback ) {
	$active_mood = tailwindscore_content_mood_active_definition();
	$active_map  = isset( $active_mood['surface_defaults'] ) && is_array( $active_mood['surface_defaults'] ) ? $active_mood['surface_defaults'] : array();

	if ( array_key_exists( $surface_key, $active_map ) ) {
		return $active_map[ $surface_key ];
	}

	$default_mood = tailwindscore_content_mood_definition( tailwindscore_content_mood_default_key() );
	$default_map  = is_array( $default_mood ) && isset( $default_mood['surface_defaults'] ) && is_array( $default_mood['surface_defaults'] ) ? $default_mood['surface_defaults'] : array();

	return $default_map[ $surface_key ] ?? $fallback;
}
