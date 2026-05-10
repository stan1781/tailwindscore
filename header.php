<?php
/**
 * Theme header — minimal document scaffold.
 *
 * @package TailwindScore
 */

declare(strict_types=1);
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="ts-site-shell">
<?php get_template_part( 'template-parts/site/utility-bar' ); ?>
<?php get_template_part( 'template-parts/site/header' ); ?>
