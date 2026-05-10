<?php
/**
 * Governed content surface registry.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * Registry definitions for all supported content surfaces.
 *
 * @return array<string, array<string, mixed>>
 */
function tailwindscore_content_surface_registry(): array {
	static $registry = null;

	if ( null !== $registry ) {
		return $registry;
	}

	$registry = array(
		'announcement-bar-message' => array(
			'key'                 => 'announcement-bar-message',
			'label'               => 'Announcement bar message',
			'fallback'            => __( 'Complimentary shipping on domestic orders over $150.', 'tailwindscore' ),
			'mood_surface'        => 'announcement_language',
			'sanitizer'           => 'tailwindscore_content_surface_sanitize_text',
			'translation_support' => true,
			'ssr_output'          => 'tailwindscore_site_shell_announcement_text',
			'customization_entry' => array(
				'transport'  => 'theme_mod',
				'setting_id' => 'ts_surface_announcement_bar_message',
				'group'      => 'content_surfaces',
				'section'    => 'site_shell',
				'control'    => 'text',
			),
		),
		'footer-brand-summary'    => array(
			'key'                 => 'footer-brand-summary',
			'label'               => 'Footer brand summary',
			'fallback'            => __( 'Editorial commerce foundation for a calm, premium shopping journey.', 'tailwindscore' ),
			'mood_surface'        => 'footer_messaging',
			'sanitizer'           => 'tailwindscore_content_surface_sanitize_textarea',
			'translation_support' => true,
			'ssr_output'          => 'tailwindscore_site_shell_footer_summary',
			'customization_entry' => array(
				'transport'  => 'theme_mod',
				'setting_id' => 'ts_surface_footer_brand_summary',
				'group'      => 'content_surfaces',
				'section'    => 'footer',
				'control'    => 'textarea',
			),
		),
		'footer-legal-notice'     => array(
			'key'                 => 'footer-legal-notice',
			'label'               => 'Footer legal notice',
			'fallback'            => __( 'Copyright %s. All rights reserved.', 'tailwindscore' ),
			'mood_surface'        => 'footer_messaging',
			'sanitizer'           => 'tailwindscore_content_surface_sanitize_text',
			'translation_support' => true,
			'ssr_output'          => 'tailwindscore_site_shell_footer_legal_text',
			'customization_entry' => array(
				'transport'  => 'theme_mod',
				'setting_id' => 'ts_surface_footer_legal_notice',
				'group'      => 'content_surfaces',
				'section'    => 'footer',
				'control'    => 'text',
			),
		),
		'footer-newsletter-copy'  => array(
			'key'                 => 'footer-newsletter-copy',
			'label'               => 'Footer newsletter copy',
			'fallback'            => __( 'Use this surface for a concise editorial newsletter invitation, not a marketing wall of text.', 'tailwindscore' ),
			'mood_surface'        => 'newsletter_prompts',
			'sanitizer'           => 'tailwindscore_content_surface_sanitize_textarea',
			'translation_support' => true,
			'ssr_output'          => 'tailwindscore_content_surface_value',
			'customization_entry' => array(
				'transport'  => 'theme_mod',
				'setting_id' => 'ts_surface_footer_newsletter_copy',
				'group'      => 'content_surfaces',
				'section'    => 'footer',
				'control'    => 'textarea',
			),
		),
		'support-message'         => array(
			'key'                 => 'support-message',
			'label'               => 'Support message',
			'fallback'            => __( 'Support information should stay concise, reassuring, and close to purchase-critical guidance.', 'tailwindscore' ),
			'mood_surface'        => 'support_messaging',
			'sanitizer'           => 'tailwindscore_content_surface_sanitize_textarea',
			'translation_support' => true,
			'ssr_output'          => 'tailwindscore_site_shell_support_message',
			'customization_entry' => array(
				'transport'  => 'theme_mod',
				'setting_id' => 'ts_surface_support_message',
				'group'      => 'content_surfaces',
				'section'    => 'footer',
				'control'    => 'textarea',
			),
		),
		'guarantee-text'          => array(
			'key'                 => 'guarantee-text',
			'label'               => 'Guarantee text',
			'fallback'            => __( 'Guarantee copy belongs in one governed surface so trust language stays consistent across commerce touchpoints.', 'tailwindscore' ),
			'mood_surface'        => 'trust_messaging',
			'sanitizer'           => 'tailwindscore_content_surface_sanitize_textarea',
			'translation_support' => true,
			'ssr_output'          => 'tailwindscore_content_surface_value',
			'customization_entry' => array(
				'transport'  => 'theme_mod',
				'setting_id' => 'ts_surface_guarantee_text',
				'group'      => 'content_surfaces',
				'section'    => 'commerce',
				'control'    => 'textarea',
			),
		),
		'trust-message'           => array(
			'key'                 => 'trust-message',
			'label'               => 'Trust message',
			'fallback'            => __( 'Trust messaging should clarify shipping, returns, and confidence signals without fragmenting into per-component copy fields.', 'tailwindscore' ),
			'mood_surface'        => 'trust_messaging',
			'sanitizer'           => 'tailwindscore_content_surface_sanitize_textarea',
			'translation_support' => true,
			'ssr_output'          => 'tailwindscore_content_surface_value',
			'customization_entry' => array(
				'transport'  => 'theme_mod',
				'setting_id' => 'ts_surface_trust_message',
				'group'      => 'content_surfaces',
				'section'    => 'commerce',
				'control'    => 'textarea',
			),
		),
		'footer-social-links'     => array(
			'key'                 => 'footer-social-links',
			'label'               => 'Footer social links',
			'fallback'            => array(),
			'sanitizer'           => 'tailwindscore_content_surface_sanitize_social_links',
			'translation_support' => false,
			'ssr_output'          => 'tailwindscore_site_shell_social_links',
			'customization_entry' => array(
				'transport'  => 'theme_mod',
				'setting_id' => 'ts_surface_footer_social_links',
				'group'      => 'content_surfaces',
				'section'    => 'footer',
				'control'    => 'repeater',
			),
		),
		'checkout-reassurance-message' => array(
			'key'                 => 'checkout-reassurance-message',
			'label'               => 'Checkout reassurance message',
			'fallback'            => __( 'Checkout is designed to feel clear, secure, and quietly reassuring from review to confirmation.', 'tailwindscore' ),
			'mood_surface'        => 'checkout_reassurance',
			'sanitizer'           => 'tailwindscore_content_surface_sanitize_textarea',
			'translation_support' => true,
			'ssr_output'          => 'tailwindscore_content_surface_value',
			'customization_entry' => array(
				'transport'  => 'theme_mod',
				'setting_id' => 'ts_surface_checkout_reassurance_message',
				'group'      => 'content_surfaces',
				'section'    => 'checkout',
				'control'    => 'textarea',
			),
		),
		'checkout-payment-guidance-message' => array(
			'key'                 => 'checkout-payment-guidance-message',
			'label'               => 'Checkout payment guidance message',
			'fallback'            => __( 'Choose a payment method and complete the order with one clear confirmation.', 'tailwindscore' ),
			'mood_surface'        => 'checkout_reassurance',
			'sanitizer'           => 'tailwindscore_content_surface_sanitize_textarea',
			'translation_support' => true,
			'ssr_output'          => 'tailwindscore_content_surface_value',
			'customization_entry' => array(
				'transport'  => 'theme_mod',
				'setting_id' => 'ts_surface_checkout_payment_guidance_message',
				'group'      => 'content_surfaces',
				'section'    => 'checkout',
				'control'    => 'textarea',
			),
		),
		'checkout-mobile-summary-message' => array(
			'key'                 => 'checkout-mobile-summary-message',
			'label'               => 'Checkout mobile summary message',
			'fallback'            => __( 'Shipping, taxes, and discounts stay visible before purchase completion.', 'tailwindscore' ),
			'mood_surface'        => 'checkout_reassurance',
			'sanitizer'           => 'tailwindscore_content_surface_sanitize_textarea',
			'translation_support' => true,
			'ssr_output'          => 'tailwindscore_content_surface_value',
			'customization_entry' => array(
				'transport'  => 'theme_mod',
				'setting_id' => 'ts_surface_checkout_mobile_summary_message',
				'group'      => 'content_surfaces',
				'section'    => 'checkout',
				'control'    => 'textarea',
			),
		),
		'checkout-validation-summary-message' => array(
			'key'                 => 'checkout-validation-summary-message',
			'label'               => 'Checkout validation summary message',
			'fallback'            => __( 'Please review the highlighted checkout details.', 'tailwindscore' ),
			'mood_surface'        => 'checkout_reassurance',
			'sanitizer'           => 'tailwindscore_content_surface_sanitize_text',
			'translation_support' => true,
			'ssr_output'          => 'tailwindscore_content_surface_value',
			'customization_entry' => array(
				'transport'  => 'theme_mod',
				'setting_id' => 'ts_surface_checkout_validation_summary_message',
				'group'      => 'content_surfaces',
				'section'    => 'checkout',
				'control'    => 'text',
			),
		),
		'checkout-validation-title' => array(
			'key'                 => 'checkout-validation-title',
			'label'               => 'Checkout validation title',
			'fallback'            => __( 'Please review your checkout details', 'tailwindscore' ),
			'mood_surface'        => 'checkout_reassurance',
			'sanitizer'           => 'tailwindscore_content_surface_sanitize_text',
			'translation_support' => true,
			'ssr_output'          => 'tailwindscore_content_surface_value',
			'customization_entry' => array(
				'transport'  => 'theme_mod',
				'setting_id' => 'ts_surface_checkout_validation_title',
				'group'      => 'content_surfaces',
				'section'    => 'checkout',
				'control'    => 'text',
			),
		),
		'checkout-loading-message' => array(
			'key'                 => 'checkout-loading-message',
			'label'               => 'Checkout loading message',
			'fallback'            => __( 'Updating checkout', 'tailwindscore' ),
			'mood_surface'        => 'checkout_reassurance',
			'sanitizer'           => 'tailwindscore_content_surface_sanitize_text',
			'translation_support' => true,
			'ssr_output'          => 'tailwindscore_content_surface_value',
			'customization_entry' => array(
				'transport'  => 'theme_mod',
				'setting_id' => 'ts_surface_checkout_loading_message',
				'group'      => 'content_surfaces',
				'section'    => 'checkout',
				'control'    => 'text',
			),
		),
		'account-dashboard-message' => array(
			'key'                 => 'account-dashboard-message',
			'label'               => 'Account dashboard message',
			'fallback'            => __( 'Orders, addresses, downloads, and account details arranged in one calm post-purchase space.', 'tailwindscore' ),
			'mood_surface'        => 'account_messaging',
			'sanitizer'           => 'tailwindscore_content_surface_sanitize_textarea',
			'translation_support' => true,
			'ssr_output'          => 'tailwindscore_content_surface_value',
			'customization_entry' => array(
				'transport'  => 'theme_mod',
				'setting_id' => 'ts_surface_account_dashboard_message',
				'group'      => 'content_surfaces',
				'section'    => 'account',
				'control'    => 'textarea',
			),
		),
		'account-orders-message' => array(
			'key'                 => 'account-orders-message',
			'label'               => 'Account orders message',
			'fallback'            => __( 'Track each purchase, reopen details when needed, and keep your post-purchase history easy to scan.', 'tailwindscore' ),
			'mood_surface'        => 'account_messaging',
			'sanitizer'           => 'tailwindscore_content_surface_sanitize_textarea',
			'translation_support' => true,
			'ssr_output'          => 'tailwindscore_content_surface_value',
			'customization_entry' => array(
				'transport'  => 'theme_mod',
				'setting_id' => 'ts_surface_account_orders_message',
				'group'      => 'content_surfaces',
				'section'    => 'account',
				'control'    => 'textarea',
			),
		),
		'account-view-order-message' => array(
			'key'                 => 'account-view-order-message',
			'label'               => 'Account view order message',
			'fallback'            => __( 'A clear summary of status, items, totals, and delivery information without dashboard clutter.', 'tailwindscore' ),
			'mood_surface'        => 'account_messaging',
			'sanitizer'           => 'tailwindscore_content_surface_sanitize_textarea',
			'translation_support' => true,
			'ssr_output'          => 'tailwindscore_content_surface_value',
			'customization_entry' => array(
				'transport'  => 'theme_mod',
				'setting_id' => 'ts_surface_account_view_order_message',
				'group'      => 'content_surfaces',
				'section'    => 'account',
				'control'    => 'textarea',
			),
		),
		'account-downloads-message' => array(
			'key'                 => 'account-downloads-message',
			'label'               => 'Account downloads message',
			'fallback'            => __( 'Keep downloadable products close at hand, with remaining access and expiry details presented quietly.', 'tailwindscore' ),
			'mood_surface'        => 'account_messaging',
			'sanitizer'           => 'tailwindscore_content_surface_sanitize_textarea',
			'translation_support' => true,
			'ssr_output'          => 'tailwindscore_content_surface_value',
			'customization_entry' => array(
				'transport'  => 'theme_mod',
				'setting_id' => 'ts_surface_account_downloads_message',
				'group'      => 'content_surfaces',
				'section'    => 'account',
				'control'    => 'textarea',
			),
		),
		'account-login-reassurance-message' => array(
			'key'                 => 'account-login-reassurance-message',
			'label'               => 'Account login reassurance message',
			'fallback'            => __( 'Review orders, revisit downloads, and manage saved details from one considered post-purchase surface.', 'tailwindscore' ),
			'mood_surface'        => 'account_messaging',
			'sanitizer'           => 'tailwindscore_content_surface_sanitize_textarea',
			'translation_support' => true,
			'ssr_output'          => 'tailwindscore_content_surface_value',
			'customization_entry' => array(
				'transport'  => 'theme_mod',
				'setting_id' => 'ts_surface_account_login_reassurance_message',
				'group'      => 'content_surfaces',
				'section'    => 'account',
				'control'    => 'textarea',
			),
		),
		'account-recovery-message' => array(
			'key'                 => 'account-recovery-message',
			'label'               => 'Account recovery message',
			'fallback'            => __( 'Enter the email address used for your account and we will send a reset link with the same measured pacing as the rest of the account experience.', 'tailwindscore' ),
			'mood_surface'        => 'account_messaging',
			'sanitizer'           => 'tailwindscore_content_surface_sanitize_textarea',
			'translation_support' => true,
			'ssr_output'          => 'tailwindscore_content_surface_value',
			'customization_entry' => array(
				'transport'  => 'theme_mod',
				'setting_id' => 'ts_surface_account_recovery_message',
				'group'      => 'content_surfaces',
				'section'    => 'account',
				'control'    => 'textarea',
			),
		),
		'account-recovery-caption' => array(
			'key'                 => 'account-recovery-caption',
			'label'               => 'Account recovery caption',
			'fallback'            => __( 'Use your account email or username to begin the reset flow.', 'tailwindscore' ),
			'mood_surface'        => 'account_messaging',
			'sanitizer'           => 'tailwindscore_content_surface_sanitize_textarea',
			'translation_support' => true,
			'ssr_output'          => 'tailwindscore_content_surface_value',
			'customization_entry' => array(
				'transport'  => 'theme_mod',
				'setting_id' => 'ts_surface_account_recovery_caption',
				'group'      => 'content_surfaces',
				'section'    => 'account',
				'control'    => 'textarea',
			),
		),
		'account-reset-message' => array(
			'key'                 => 'account-reset-message',
			'label'               => 'Account reset message',
			'fallback'            => __( 'Create a new password and return to your account without leaving the calm rhythm of the commerce flow.', 'tailwindscore' ),
			'mood_surface'        => 'account_messaging',
			'sanitizer'           => 'tailwindscore_content_surface_sanitize_textarea',
			'translation_support' => true,
			'ssr_output'          => 'tailwindscore_content_surface_value',
			'customization_entry' => array(
				'transport'  => 'theme_mod',
				'setting_id' => 'ts_surface_account_reset_message',
				'group'      => 'content_surfaces',
				'section'    => 'account',
				'control'    => 'textarea',
			),
		),
		'account-reset-support-message' => array(
			'key'                 => 'account-reset-support-message',
			'label'               => 'Account reset support message',
			'fallback'            => __( 'Choose a password that feels easy to remember and hard to guess.', 'tailwindscore' ),
			'mood_surface'        => 'account_messaging',
			'sanitizer'           => 'tailwindscore_content_surface_sanitize_textarea',
			'translation_support' => true,
			'ssr_output'          => 'tailwindscore_content_surface_value',
			'customization_entry' => array(
				'transport'  => 'theme_mod',
				'setting_id' => 'ts_surface_account_reset_support_message',
				'group'      => 'content_surfaces',
				'section'    => 'account',
				'control'    => 'textarea',
			),
		),
		'account-address-guidance-message' => array(
			'key'                 => 'account-address-guidance-message',
			'label'               => 'Account address guidance message',
			'fallback'            => __( 'Use the details below for a smoother return to checkout next time.', 'tailwindscore' ),
			'mood_surface'        => 'account_messaging',
			'sanitizer'           => 'tailwindscore_content_surface_sanitize_textarea',
			'translation_support' => true,
			'ssr_output'          => 'tailwindscore_content_surface_value',
			'customization_entry' => array(
				'transport'  => 'theme_mod',
				'setting_id' => 'ts_surface_account_address_guidance_message',
				'group'      => 'content_surfaces',
				'section'    => 'account',
				'control'    => 'textarea',
			),
		),
		'account-message'         => array(
			'key'                 => 'account-message',
			'label'               => 'Account message',
			'fallback'            => __( 'Account language should feel composed, helpful, and respectful of the customer history it presents.', 'tailwindscore' ),
			'mood_surface'        => 'account_messaging',
			'sanitizer'           => 'tailwindscore_content_surface_sanitize_textarea',
			'translation_support' => true,
			'ssr_output'          => 'tailwindscore_content_surface_value',
			'customization_entry' => array(
				'transport'  => 'theme_mod',
				'setting_id' => 'ts_surface_account_message',
				'group'      => 'content_surfaces',
				'section'    => 'account',
				'control'    => 'textarea',
			),
		),
		'search-guidance-message' => array(
			'key'                 => 'search-guidance-message',
			'label'               => 'Search guidance message',
			'fallback'            => __( 'Use direct product language and calm guidance to help customers move through discovery with confidence.', 'tailwindscore' ),
			'mood_surface'        => 'search_guidance',
			'sanitizer'           => 'tailwindscore_content_surface_sanitize_textarea',
			'translation_support' => true,
			'ssr_output'          => 'tailwindscore_content_surface_value',
			'customization_entry' => array(
				'transport'  => 'theme_mod',
				'setting_id' => 'ts_surface_search_guidance_message',
				'group'      => 'content_surfaces',
				'section'    => 'search',
				'control'    => 'textarea',
			),
		),
		'search-recent-searches-guidance-message' => array(
			'key'                 => 'search-recent-searches-guidance-message',
			'label'               => 'Search recent searches guidance message',
			'fallback'            => __( 'Recent searches remain nearby so returning to a product path feels immediate and quiet.', 'tailwindscore' ),
			'mood_surface'        => 'search_guidance',
			'sanitizer'           => 'tailwindscore_content_surface_sanitize_textarea',
			'translation_support' => true,
			'ssr_output'          => 'tailwindscore_content_surface_value',
			'customization_entry' => array(
				'transport'  => 'theme_mod',
				'setting_id' => 'ts_surface_search_recent_searches_guidance_message',
				'group'      => 'content_surfaces',
				'section'    => 'search',
				'control'    => 'textarea',
			),
		),
		'account-secondary-action-label' => array(
			'key'                 => 'account-secondary-action-label',
			'label'               => 'Account secondary action label',
			'fallback'            => __( 'Open', 'tailwindscore' ),
			'mood_surface'        => 'account_messaging',
			'sanitizer'           => 'tailwindscore_content_surface_sanitize_text',
			'translation_support' => true,
			'ssr_output'          => 'tailwindscore_content_surface_value',
			'customization_entry' => array(
				'transport'  => 'theme_mod',
				'setting_id' => 'ts_surface_account_secondary_action_label',
				'group'      => 'content_surfaces',
				'section'    => 'account',
				'control'    => 'text',
			),
		),
		'orders-empty-action-label' => array(
			'key'                 => 'orders-empty-action-label',
			'label'               => 'Orders empty action label',
			'fallback'            => __( 'Browse products', 'tailwindscore' ),
			'mood_surface'        => 'account_messaging',
			'sanitizer'           => 'tailwindscore_content_surface_sanitize_text',
			'translation_support' => true,
			'ssr_output'          => 'tailwindscore_content_surface_value',
			'customization_entry' => array(
				'transport'  => 'theme_mod',
				'setting_id' => 'ts_surface_orders_empty_action_label',
				'group'      => 'content_surfaces',
				'section'    => 'account',
				'control'    => 'text',
			),
		),
		'order-quick-detail-label' => array(
			'key'                 => 'order-quick-detail-label',
			'label'               => 'Order quick detail label',
			'fallback'            => __( 'Quick detail', 'tailwindscore' ),
			'mood_surface'        => 'account_messaging',
			'sanitizer'           => 'tailwindscore_content_surface_sanitize_text',
			'translation_support' => true,
			'ssr_output'          => 'tailwindscore_content_surface_value',
			'customization_entry' => array(
				'transport'  => 'theme_mod',
				'setting_id' => 'ts_surface_order_quick_detail_label',
				'group'      => 'content_surfaces',
				'section'    => 'account',
				'control'    => 'text',
			),
		),
		'view-order-back-label' => array(
			'key'                 => 'view-order-back-label',
			'label'               => 'View order back label',
			'fallback'            => __( 'Back to orders', 'tailwindscore' ),
			'mood_surface'        => 'account_messaging',
			'sanitizer'           => 'tailwindscore_content_surface_sanitize_text',
			'translation_support' => true,
			'ssr_output'          => 'tailwindscore_content_surface_value',
			'customization_entry' => array(
				'transport'  => 'theme_mod',
				'setting_id' => 'ts_surface_view_order_back_label',
				'group'      => 'content_surfaces',
				'section'    => 'account',
				'control'    => 'text',
			),
		),
		'search-predictive-empty-message' => array(
			'key'                 => 'search-predictive-empty-message',
			'label'               => 'Search predictive empty message',
			'fallback'            => __( 'Try a broader product name or continue through a collection path.', 'tailwindscore' ),
			'mood_surface'        => 'search_guidance',
			'sanitizer'           => 'tailwindscore_content_surface_sanitize_textarea',
			'translation_support' => true,
			'ssr_output'          => 'tailwindscore_content_surface_value',
			'customization_entry' => array(
				'transport'  => 'theme_mod',
				'setting_id' => 'ts_surface_search_predictive_empty_message',
				'group'      => 'content_surfaces',
				'section'    => 'search',
				'control'    => 'textarea',
			),
		),
		'cart-drawer-validation-title' => array(
			'key'                 => 'cart-drawer-validation-title',
			'label'               => 'Cart drawer validation title',
			'fallback'            => __( 'Please review your bag', 'tailwindscore' ),
			'mood_surface'        => 'support_messaging',
			'sanitizer'           => 'tailwindscore_content_surface_sanitize_text',
			'translation_support' => true,
			'ssr_output'          => 'tailwindscore_content_surface_value',
			'customization_entry' => array(
				'transport'  => 'theme_mod',
				'setting_id' => 'ts_surface_cart_drawer_validation_title',
				'group'      => 'content_surfaces',
				'section'    => 'cart',
				'control'    => 'text',
			),
		),
		'cart-drawer-loading-message' => array(
			'key'                 => 'cart-drawer-loading-message',
			'label'               => 'Cart drawer loading message',
			'fallback'            => __( 'Updating bag', 'tailwindscore' ),
			'mood_surface'        => 'support_messaging',
			'sanitizer'           => 'tailwindscore_content_surface_sanitize_text',
			'translation_support' => true,
			'ssr_output'          => 'tailwindscore_content_surface_value',
			'customization_entry' => array(
				'transport'  => 'theme_mod',
				'setting_id' => 'ts_surface_cart_drawer_loading_message',
				'group'      => 'content_surfaces',
				'section'    => 'cart',
				'control'    => 'text',
			),
		),
		'cart-drawer-update-error-message' => array(
			'key'                 => 'cart-drawer-update-error-message',
			'label'               => 'Cart drawer update error message',
			'fallback'            => __( 'We could not update the bag just now. Please try again.', 'tailwindscore' ),
			'mood_surface'        => 'support_messaging',
			'sanitizer'           => 'tailwindscore_content_surface_sanitize_textarea',
			'translation_support' => true,
			'ssr_output'          => 'tailwindscore_content_surface_value',
			'customization_entry' => array(
				'transport'  => 'theme_mod',
				'setting_id' => 'ts_surface_cart_drawer_update_error_message',
				'group'      => 'content_surfaces',
				'section'    => 'cart',
				'control'    => 'textarea',
			),
		),
		'cart-drawer-item-updated-message' => array(
			'key'                 => 'cart-drawer-item-updated-message',
			'label'               => 'Cart drawer item updated message',
			'fallback'            => __( 'Bag updated', 'tailwindscore' ),
			'mood_surface'        => 'support_messaging',
			'sanitizer'           => 'tailwindscore_content_surface_sanitize_text',
			'translation_support' => true,
			'ssr_output'          => 'tailwindscore_content_surface_value',
			'customization_entry' => array(
				'transport'  => 'theme_mod',
				'setting_id' => 'ts_surface_cart_drawer_item_updated_message',
				'group'      => 'content_surfaces',
				'section'    => 'cart',
				'control'    => 'text',
			),
		),
		'cart-drawer-item-removed-message' => array(
			'key'                 => 'cart-drawer-item-removed-message',
			'label'               => 'Cart drawer item removed message',
			'fallback'            => __( 'Removed from bag', 'tailwindscore' ),
			'mood_surface'        => 'support_messaging',
			'sanitizer'           => 'tailwindscore_content_surface_sanitize_text',
			'translation_support' => true,
			'ssr_output'          => 'tailwindscore_content_surface_value',
			'customization_entry' => array(
				'transport'  => 'theme_mod',
				'setting_id' => 'ts_surface_cart_drawer_item_removed_message',
				'group'      => 'content_surfaces',
				'section'    => 'cart',
				'control'    => 'text',
			),
		),
	);

	$empty_states = array(
		'cart'                   => array(
			'eyebrow' => __( 'Your bag', 'tailwindscore' ),
			'title'   => __( 'Your cart is empty', 'tailwindscore' ),
			'message' => __( 'Begin with a considered selection and return here when you are ready to check out.', 'tailwindscore' ),
		),
		'checkout-unavailable'   => array(
			'eyebrow' => __( 'Purchase flow', 'tailwindscore' ),
			'title'   => __( 'Checkout is not available yet', 'tailwindscore' ),
			'message' => __( 'Sign in or return to your bag to continue with a secure purchase.', 'tailwindscore' ),
		),
		'orders'                 => array(
			'eyebrow' => __( 'Order history', 'tailwindscore' ),
			'title'   => __( 'No orders yet', 'tailwindscore' ),
			'message' => __( 'When an order is placed, its progress and details will appear here in a calm, easy-to-review timeline.', 'tailwindscore' ),
		),
		'downloads'              => array(
			'eyebrow' => __( 'Digital purchases', 'tailwindscore' ),
			'title'   => __( 'No downloads yet', 'tailwindscore' ),
			'message' => __( 'Digital purchases will appear here as soon as they are ready to access.', 'tailwindscore' ),
		),
		'addresses'              => array(
			'eyebrow' => __( 'Address book', 'tailwindscore' ),
			'title'   => __( 'No address saved', 'tailwindscore' ),
			'message' => __( 'Add a billing or shipping address to make your next checkout feel faster and more considered.', 'tailwindscore' ),
		),
		'logged-out'             => array(
			'eyebrow' => __( 'Customer account', 'tailwindscore' ),
			'title'   => __( 'Sign in to continue', 'tailwindscore' ),
			'message' => __( 'Access your orders, saved details, and post-purchase history from one premium account surface.', 'tailwindscore' ),
		),
		'search-results'         => array(
			'eyebrow' => __( 'Search', 'tailwindscore' ),
			'title'   => __( 'No matching pieces found', 'tailwindscore' ),
			'message' => __( 'Try a broader product name or continue through a collection path.', 'tailwindscore' ),
		),
		'search-unavailable'     => array(
			'eyebrow' => __( 'Search', 'tailwindscore' ),
			'title'   => __( 'Search is unavailable for the moment', 'tailwindscore' ),
			'message' => __( 'Please try again in a moment, or continue by browsing the collection directly.', 'tailwindscore' ),
		),
		'archive-empty'          => array(
			'eyebrow' => __( 'Collection', 'tailwindscore' ),
			'title'   => __( 'Nothing is available here just yet', 'tailwindscore' ),
			'message' => __( 'Continue through another collection path to keep browsing the full edit.', 'tailwindscore' ),
		),
		'archive-category-empty' => array(
			'eyebrow' => __( 'Collection', 'tailwindscore' ),
			'title'   => __( 'This collection is currently quiet', 'tailwindscore' ),
			'message' => __( 'Please return to the wider collection and continue browsing from there.', 'tailwindscore' ),
		),
		'archive-filtered-empty' => array(
			'eyebrow' => __( 'Filtered view', 'tailwindscore' ),
			'title'   => __( 'No pieces match this selection', 'tailwindscore' ),
			'message' => __( 'Clear one or two filters to restore a broader collection view.', 'tailwindscore' ),
		),
		'archive-out-of-stock'   => array(
			'eyebrow' => __( 'Availability', 'tailwindscore' ),
			'title'   => __( 'Nothing in this view is currently available', 'tailwindscore' ),
			'message' => __( 'Return to the full collection to continue browsing what is available now.', 'tailwindscore' ),
		),
		'reviews'                => array(
			'eyebrow' => __( 'Customer reviews', 'tailwindscore' ),
			'title'   => __( 'No reviews yet', 'tailwindscore' ),
			'message' => __( 'The first review will appear here once a customer has spent time with this piece.', 'tailwindscore' ),
		),
		'variation-unavailable'  => array(
			'eyebrow' => __( 'Variation selection', 'tailwindscore' ),
			'title'   => __( 'This option is currently unavailable', 'tailwindscore' ),
			'message' => __( 'Choose another combination to continue with an available configuration.', 'tailwindscore' ),
		),
		'out-of-stock'           => array(
			'eyebrow' => __( 'Availability', 'tailwindscore' ),
			'title'   => __( 'Currently out of stock', 'tailwindscore' ),
			'message' => __( 'This item is not available to purchase at the moment. Check back soon for availability.', 'tailwindscore' ),
		),
	);

	foreach ( $empty_states as $context => $copy ) {
		foreach ( $copy as $slot => $fallback ) {
			$key                = sprintf( 'empty-state-%s-%s', $context, $slot );
			$registry[ $key ]   = array(
				'key'                 => $key,
				'label'               => sprintf( 'Empty state %s %s', $context, $slot ),
				'fallback'            => $fallback,
				'mood_surface'        => 'empty_states',
				'sanitizer'           => 'tailwindscore_content_surface_sanitize_text',
				'translation_support' => true,
				'ssr_output'          => 'tailwindscore_feedback_empty_state_copy',
				'customization_entry' => array(
					'transport'  => 'theme_mod',
					'setting_id' => 'ts_surface_' . str_replace( '-', '_', $key ),
					'group'      => 'content_surfaces',
					'section'    => 'empty_states',
					'control'    => 'text',
				),
			);
		}
	}

	/**
	 * Filter registry definitions.
	 *
	 * @param array<string, array<string, mixed>> $registry Surface registry.
	 */
	$registry = apply_filters( 'tailwindscore/content_surfaces/registry', $registry );

	return $registry;
}

/**
 * Fetch a single surface definition.
 *
 * @return array<string, mixed>|null
 */
function tailwindscore_content_surface_definition( string $key ): ?array {
	$registry = tailwindscore_content_surface_registry();
	$key      = sanitize_key( $key );

	return isset( $registry[ $key ] ) && is_array( $registry[ $key ] ) ? $registry[ $key ] : null;
}

/**
 * Resolve the current value for a governed surface.
 *
 * @return mixed
 */
function tailwindscore_content_surface_value( string $key ) {
	$surface = tailwindscore_content_surface_definition( $key );

	if ( ! is_array( $surface ) ) {
		return null;
	}

	$fallback  = $surface['fallback'] ?? '';
	$sanitizer = $surface['sanitizer'] ?? null;
	$value     = null;
	$entry     = isset( $surface['customization_entry'] ) && is_array( $surface['customization_entry'] ) ? $surface['customization_entry'] : array();

	if ( isset( $entry['setting_id'], $entry['transport'] ) ) {
		$setting_id = sanitize_key( (string) $entry['setting_id'] );
		$transport  = sanitize_key( (string) $entry['transport'] );

		if ( '' !== $setting_id ) {
			if ( 'theme_mod' === $transport ) {
				$value = get_theme_mod( $setting_id, null );
			} elseif ( 'option' === $transport ) {
				$value = get_option( $setting_id, null );
			}
		}
	}

	if ( function_exists( 'tailwindscore_content_mood_surface_fallback' ) ) {
		$fallback = tailwindscore_content_mood_surface_fallback( $key, $fallback );
	}

	if ( is_callable( $sanitizer ) ) {
		$value    = null === $value ? null : call_user_func( $sanitizer, $value );
		$fallback = call_user_func( $sanitizer, $fallback );
	}

	if ( null === $value || tailwindscore_content_surface_is_empty( $value ) ) {
		$value = $fallback;
	}

	/**
	 * Filter a resolved content surface value.
	 *
	 * @param mixed                $value   Surface value.
	 * @param string               $key     Surface key.
	 * @param array<string, mixed> $surface Surface definition.
	 */
	return apply_filters( 'tailwindscore/content_surfaces/value', $value, $key, $surface );
}

/**
 * Resolve a governed surface value as string.
 */
function tailwindscore_content_surface_text( string $key, string $default = '' ): string {
	$value = tailwindscore_content_surface_value( $key );

	return is_string( $value ) && '' !== trim( $value ) ? $value : $default;
}
