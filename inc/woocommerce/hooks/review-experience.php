<?php
/**
 * WooCommerce review experience hooks.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * @return array<string, string>
 */
function tailwindscore_review_surface_copy(): array {
	return array(
		'title'                     => tailwindscore_content_surface_text( 'reviews-title', __( 'Customer reviews', 'tailwindscore' ) ),
		'intro'                     => tailwindscore_content_surface_text( 'reviews-intro', __( 'Measured notes from customers, arranged with the same quiet hierarchy as the rest of the product story.', 'tailwindscore' ) ),
		'pagination_label'          => tailwindscore_content_surface_text( 'reviews-pagination-label', __( 'Reviews pagination', 'tailwindscore' ) ),
		'access_eyebrow'            => tailwindscore_content_surface_text( 'reviews-access-eyebrow', __( 'Review access', 'tailwindscore' ) ),
		'access_title'              => tailwindscore_content_surface_text( 'reviews-access-title', __( 'Purchase required to review', 'tailwindscore' ) ),
		'access_message'            => tailwindscore_content_surface_text( 'reviews-access-message', __( 'Only customers who have purchased this item can leave a review, which keeps the conversation grounded in ownership.', 'tailwindscore' ) ),
		'access_sign_in_label'      => tailwindscore_content_surface_text( 'reviews-access-sign-in-label', __( 'Sign in', 'tailwindscore' ) ),
		'form_title'                => tailwindscore_content_surface_text( 'reviews-form-title', __( 'Write a review', 'tailwindscore' ) ),
		'form_title_reply_to'       => tailwindscore_content_surface_text( 'reviews-form-title-reply-to', __( 'Reply to %s', 'tailwindscore' ) ),
		'form_intro'                => tailwindscore_content_surface_text( 'reviews-form-intro', __( 'Share a concise note on fit, feel, quality, or everyday use.', 'tailwindscore' ) ),
		'form_submit_label'         => tailwindscore_content_surface_text( 'reviews-form-submit-label', __( 'Submit review', 'tailwindscore' ) ),
		'form_rating_label'         => tailwindscore_content_surface_text( 'reviews-form-rating-label', __( 'Your rating', 'tailwindscore' ) ),
		'form_rating_label_optional' => tailwindscore_content_surface_text( 'reviews-form-rating-label-optional', __( 'Your rating (optional)', 'tailwindscore' ) ),
		'form_rating_placeholder'   => tailwindscore_content_surface_text( 'reviews-form-rating-placeholder', __( 'Rate the product', 'tailwindscore' ) ),
		'form_rating_option_5'      => tailwindscore_content_surface_text( 'reviews-form-rating-option-5', __( 'Perfect', 'tailwindscore' ) ),
		'form_rating_option_4'      => tailwindscore_content_surface_text( 'reviews-form-rating-option-4', __( 'Good', 'tailwindscore' ) ),
		'form_rating_option_3'      => tailwindscore_content_surface_text( 'reviews-form-rating-option-3', __( 'Average', 'tailwindscore' ) ),
		'form_rating_option_2'      => tailwindscore_content_surface_text( 'reviews-form-rating-option-2', __( 'Not that bad', 'tailwindscore' ) ),
		'form_rating_option_1'      => tailwindscore_content_surface_text( 'reviews-form-rating-option-1', __( 'Very poor', 'tailwindscore' ) ),
		'form_review_label'         => tailwindscore_content_surface_text( 'reviews-form-review-label', __( 'Your review', 'tailwindscore' ) ),
		'form_name_label'           => tailwindscore_content_surface_text( 'reviews-form-name-label', __( 'Name', 'tailwindscore' ) ),
		'form_email_label'          => tailwindscore_content_surface_text( 'reviews-form-email-label', __( 'Email', 'tailwindscore' ) ),
		'cookies_consent'           => tailwindscore_content_surface_text( 'reviews-form-cookies-consent', __( 'Save my name, email, and website in this browser for the next time I comment.', 'tailwindscore' ) ),
		'verified_owner_label'      => tailwindscore_content_surface_text( 'reviews-verified-owner-label', __( 'Verified owner', 'tailwindscore' ) ),
	);
}

/**
 * Inject a class into the first matching HTML tag.
 */
function tailwindscore_review_add_class_to_tag( string $html, string $tag, string $class_name ): string {
	if ( '' === trim( $html ) ) {
		return $html;
	}

	$pattern = sprintf( '/<%1$s\b([^>]*)class=(["\'])([^"\']*)(["\'])([^>]*)>/i', preg_quote( $tag, '/' ) );

	if ( 1 === preg_match( $pattern, $html ) ) {
		return (string) preg_replace(
			$pattern,
			sprintf( '<%1$s$1class=$2$3 %2$s$4$5>', $tag, $class_name ),
			$html,
			1
		);
	}

	return (string) preg_replace(
		sprintf( '/<%s\b([^>]*)>/i', preg_quote( $tag, '/' ) ),
		sprintf( '<%1$s class="%2$s"$1>', $tag, $class_name ),
		$html,
		1
	);
}

/**
 * Default review form args with WooCommerce-compatible field structure.
 *
 * @return array<string, mixed>
 */
function tailwindscore_review_form_args(): array {
	$copy               = tailwindscore_review_surface_copy();
	$commenter          = wp_get_current_commenter();
	$require_name_email = (bool) get_option( 'require_name_email', 1 );
	$rating_field       = '';

	if ( wc_review_ratings_enabled() ) {
		$options = array(
			''  => (string) ( $copy['form_rating_placeholder'] ?? '' ),
			'5' => (string) ( $copy['form_rating_option_5'] ?? '' ),
			'4' => (string) ( $copy['form_rating_option_4'] ?? '' ),
			'3' => (string) ( $copy['form_rating_option_3'] ?? '' ),
			'2' => (string) ( $copy['form_rating_option_2'] ?? '' ),
			'1' => (string) ( $copy['form_rating_option_1'] ?? '' ),
		);

		$rating_options = '';

		foreach ( $options as $value => $label ) {
			$rating_options .= sprintf(
				'<option value="%1$s">%2$s</option>',
				esc_attr( $value ),
				esc_html( $label )
			);
		}

		$required_attr = wc_review_ratings_required() ? ' required' : '';
		$label_text    = wc_review_ratings_required()
			? (string) ( $copy['form_rating_label'] ?? '' )
			: (string) ( $copy['form_rating_label_optional'] ?? '' );

		$rating_field = sprintf(
			'<p class="comment-form-rating"><label class="ts-label" for="rating">%1$s</label><select name="rating" id="rating" class="ts-select"%2$s>%3$s</select></p>',
			$label_text,
			$required_attr,
			$rating_options
		);
	}

	$comment_field = sprintf(
		'%1$s<p class="comment-form-comment"><label class="ts-label" for="comment">%2$s</label><textarea id="comment" name="comment" class="ts-textarea" cols="45" rows="7" required></textarea></p>',
		$rating_field,
		esc_html( (string) ( $copy['form_review_label'] ?? '' ) )
	);

	$fields = array(
		'author' => sprintf(
			'<p class="comment-form-author"><label class="ts-label" for="author">%1$s%2$s</label><input id="author" name="author" type="text" class="ts-input" value="%3$s" size="30" autocomplete="name"%4$s></p>',
			esc_html( (string) ( $copy['form_name_label'] ?? '' ) ),
			$require_name_email ? ' <span class="required">*</span>' : '',
			esc_attr( $commenter['comment_author'] ?? '' ),
			$require_name_email ? ' required' : ''
		),
		'email'  => sprintf(
			'<p class="comment-form-email"><label class="ts-label" for="email">%1$s%2$s</label><input id="email" name="email" type="email" class="ts-input" value="%3$s" size="30" autocomplete="email"%4$s></p>',
			esc_html( (string) ( $copy['form_email_label'] ?? '' ) ),
			$require_name_email ? ' <span class="required">*</span>' : '',
			esc_attr( $commenter['comment_author_email'] ?? '' ),
			$require_name_email ? ' required' : ''
		),
	);

	if ( function_exists( 'wp_is_comment_cookie_opt_in' ) ) {
		$consent           = empty( $commenter['comment_author_email'] ) ? '' : ' checked="checked"';
		$fields['cookies'] = sprintf(
			'<p class="comment-form-cookies-consent"><label class="ts-choice"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" class="ts-checkbox" value="yes"%1$s><span class="ts-choice__label">%2$s</span></label></p>',
			$consent,
			esc_html( (string) ( $copy['cookies_consent'] ?? '' ) )
		);
	}

	return array(
		'title_reply'          => $copy['form_title'] ?? '',
		'title_reply_to'       => $copy['form_title_reply_to'] ?? '',
		'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title">',
		'title_reply_after'    => '</h3>',
		'comment_notes_before' => '<p class="ts-review-form__intro">' . esc_html( (string) ( $copy['form_intro'] ?? '' ) ) . '</p>',
		'comment_notes_after'  => '',
		'label_submit'         => $copy['form_submit_label'] ?? '',
		'class_form'           => 'ts-review-form comment-form',
		'class_submit'         => 'submit ts-btn ts-btn--primary',
		'logged_in_as'         => '',
		'fields'               => $fields,
		'comment_field'        => $comment_field,
	);
}

/**
 * Review list callback.
 */
function tailwindscore_review_list_item( WP_Comment $comment, array $args, int $depth ): void {
	unset( $args, $depth );

	$copy        = tailwindscore_review_surface_copy();
	$author      = (string) $comment->comment_author;
	$content     = apply_filters( 'comment_text', get_comment_text( $comment ), $comment );
	$rating      = max( 0, min( 5, (int) get_comment_meta( $comment->comment_ID, 'rating', true ) ) );
	$rating_html = $rating > 0 ? wc_get_rating_html( (float) $rating ) : '';
	$date        = get_comment_date( wc_date_format(), $comment );
	$verified    = function_exists( 'wc_review_is_from_verified_owner' ) && wc_review_is_from_verified_owner( $comment->comment_ID );
	?>
	<li <?php comment_class( 'ts-review-card', $comment ); ?> id="li-review-<?php comment_ID(); ?>">
		<article id="review-<?php comment_ID(); ?>" class="ts-review-card__inner">
			<header class="ts-review-card__header">
				<div class="ts-review-card__meta">
					<h4 class="ts-review-card__author"><?php echo esc_html( $author ); ?></h4>
					<p class="ts-review-card__detail">
						<time datetime="<?php echo esc_attr( get_comment_date( 'c', $comment ) ); ?>"><?php echo esc_html( $date ); ?></time>
						<?php if ( $verified ) : ?>
							<span class="ts-review-card__dot" aria-hidden="true"></span>
							<span><?php echo esc_html( (string) ( $copy['verified_owner_label'] ?? '' ) ); ?></span>
						<?php endif; ?>
					</p>
				</div>
				<?php if ( '' !== $rating_html ) : ?>
					<div class="ts-review-card__rating ts-rating"><?php echo wp_kses_post( $rating_html ); ?></div>
				<?php endif; ?>
			</header>
			<div class="ts-review-card__body ts-prose">
				<?php echo wp_kses_post( $content ); ?>
			</div>
		</article>
	</li>
	<?php
}

add_filter(
	'woocommerce_product_review_comment_form_args',
	static function ( array $args ): array {
		$copy                         = tailwindscore_review_surface_copy();
		$args['title_reply']          = $copy['form_title'] ?? '';
		$args['title_reply_to']       = $copy['form_title_reply_to'] ?? '';
		$args['comment_notes_before'] = '<p class="ts-review-form__intro">' . esc_html( (string) ( $copy['form_intro'] ?? '' ) ) . '</p>';
		$args['class_form']           = trim( (string) ( $args['class_form'] ?? '' ) . ' ts-review-form' );
		$args['class_submit']         = 'submit ts-btn ts-btn--primary';
		$args['label_submit']         = $copy['form_submit_label'] ?? '';

		$comment_field = $args['comment_field'] ?? '';
		if ( is_string( $comment_field ) ) {
			$comment_field = tailwindscore_review_add_class_to_tag( $comment_field, 'label', 'ts-label' );
			$comment_field = tailwindscore_review_add_class_to_tag( $comment_field, 'textarea', 'ts-textarea' );
			$args['comment_field'] = $comment_field;
		}

		$fields = is_array( $args['fields'] ?? null ) ? $args['fields'] : array();
		foreach ( $fields as $key => $field ) {
			if ( ! is_string( $field ) ) {
				continue;
			}

			$field          = tailwindscore_review_add_class_to_tag( $field, 'label', 'ts-label' );
			$fields[ $key ] = tailwindscore_review_add_class_to_tag( $field, 'input', 'ts-input' );
		}

		$args['fields'] = $fields;

		return $args;
	}
);
