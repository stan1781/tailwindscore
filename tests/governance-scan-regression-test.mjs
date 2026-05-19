import { buildReport } from '../scripts/governance-scan.mjs';
import baseline from '../governance-baseline.json' with { type: 'json' };

const report = buildReport();
const forbidden = new Set([
	'Customer reviews',
	'Measured notes from customers, arranged with the same quiet hierarchy as the rest of the product story.',
	'Purchase required to review',
	'Only customers who have purchased this item can leave a review, which keeps the conversation grounded in ownership.',
	'Write a review',
	'Reply to %s',
	'Share a concise note on fit, feel, quality, or everyday use.',
	'Submit review',
	'Your rating',
	'Your rating (optional)',
	'Rate the product',
	'Perfect',
	'Good',
	'Average',
	'Not that bad',
	'Very poor',
	'Your review',
	'Name',
	'Email',
	'Save my name, email, and website in this browser for the next time I comment.',
	'Verified owner',
]);

const falsePositives = report.findings.filter(
	(finding) =>
		finding.file === 'inc/woocommerce/hooks/review-experience.php' &&
		forbidden.has(finding.value),
);

if (falsePositives.length > 0) {
	process.stderr.write('Governed review helper fallbacks should not appear in governance delta\n');
	for (const finding of falsePositives) {
		process.stderr.write(`- ${finding.file}:${finding.line} :: ${finding.value}\n`);
	}
	process.exit(1);
}

const staleBaselineIds = new Set([
	'checkout-compatibility-payment-notices',
	'cart-summary-note-inline',
	'cart-drawer-ssr-fallbacks',
	'cart-governed-helper-duplicates',
	'cart-runtime-inline-errors',
	'cart-runtime-secondary-messages',
	'account-helper-duplicate-fallback',
	'account-helper-legacy-copy',
	'account-order-detail-legacy-fallbacks',
	'review-access-legacy-copy',
	'review-experience-legacy-intro',
]);

const unexpectedUnmatched = report.unmatchedBaselineEntries.filter((entry) => staleBaselineIds.has(entry.id));

if (unexpectedUnmatched.length > 0) {
	process.stderr.write('Resolved helper and review debt should not remain in unmatched baseline entries\n');
	for (const entry of unexpectedUnmatched) {
		process.stderr.write(`- ${entry.id} :: ${entry.file}\n`);
	}
	process.exit(1);
}

const restoredBaselineEntries = baseline.entries.filter((entry) => staleBaselineIds.has(entry.id));

if (restoredBaselineEntries.length > 0) {
	process.stderr.write('Resolved helper and review debt should not be restored to the baseline\n');
	for (const entry of restoredBaselineEntries) {
		process.stderr.write(`- ${entry.id} :: ${entry.file}\n`);
	}
	process.exit(1);
}

process.stdout.write('OK\n');
