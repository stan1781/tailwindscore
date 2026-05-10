<?php
/**
 * Main template — structural shell only (no storefront UI).
 *
 * @package TailwindScore
 */

declare(strict_types=1);

get_header();
?>
<main id="primary" class="site-main ts-container ts-stack-section">
<?php
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		the_content();
	}
}
?>
</main>
<?php
get_footer();
