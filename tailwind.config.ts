import type { Config } from 'tailwindcss';

/**
 * TailwindScore — Tailwind entry config.
 * Token defaults live in CSS (:root --ts-*) for Kirki overrides; @theme maps them for utilities.
 */
export default {
	content: [
		'./*.php',
		'./inc/**/*.php',
		'./template-parts/**/*.php',
		'./woocommerce/**/*.php',
		'./src/ts/**/*.ts',
	],
} satisfies Config;
