import { existsSync, readFileSync, readdirSync, statSync } from 'node:fs';
import path from 'node:path';

const ROOT = process.cwd();
const REPORT_DATE = new Date().toISOString().slice(0, 10);
const BASELINE_PATH = 'governance-baseline.json';
const SEVERITY_ORDER = { critical: 0, warning: 1, notice: 2, 'accepted-exception': 3 };

const SCAN_TARGETS = [
	{
		name: 'checkout',
		owner: 'Checkout surfaces',
		trustCritical: true,
		runtimeCritical: true,
		roots: ['template-parts/checkout', 'woocommerce/checkout', 'src/ts/modules/checkout', 'src/ts/modules/checkout-feedback.ts'],
		registrySignals: ['tailwindscore_checkout_surface_copy', 'checkout-', 'tailwindscore_content_surface_text'],
	},
	{
		name: 'account',
		owner: 'Account surfaces',
		trustCritical: true,
		runtimeCritical: false,
		roots: ['template-parts/account', 'woocommerce/myaccount', 'src/ts/modules/account', 'inc/account/helpers.php'],
		registrySignals: ['tailwindscore_account_surface_text', 'tailwindscore_account_recovery_copy', 'account-', 'support-message'],
	},
	{
		name: 'cart',
		owner: 'Cart surfaces',
		trustCritical: true,
		runtimeCritical: true,
		roots: ['template-parts/cart-surface', 'src/ts/modules/cart-surface', 'src/ts/modules/commerce', 'inc/cart-surface/helpers.php'],
		registrySignals: ['tailwindscore_cart_surface_copy', 'cart-drawer-', 'tailwindscore_content_surface_text'],
	},
	{
		name: 'search',
		owner: 'Discovery surfaces',
		trustCritical: false,
		runtimeCritical: true,
		roots: ['template-parts/search', 'src/ts/modules/search'],
		registrySignals: ['search-predictive-empty-message', 'tailwindscore_feedback_empty_state_copy', 'tailwindscore_content_surface_text'],
	},
	{
		name: 'reviews',
		owner: 'Review surfaces',
		trustCritical: false,
		runtimeCritical: false,
		roots: ['woocommerce/single-product-reviews.php', 'inc/woocommerce/hooks/review-experience.php'],
		registrySignals: ['tailwindscore_feedback_empty_state_copy', 'empty-state-reviews-'],
	},
	{
		name: 'archive',
		owner: 'Archive discovery',
		trustCritical: false,
		runtimeCritical: false,
		roots: ['template-parts/woocommerce', 'template-parts/components/archive', 'src/ts/modules/archive'],
		registrySignals: ['archive-', 'tailwindscore_feedback_empty_state_copy', 'tailwindscore_content_surface_text'],
	},
];

const TRUST_KEYWORDS = [
	'secure',
	'security',
	'trust',
	'guarantee',
	'reassur',
	'support',
	'help',
	'return',
	'refund',
	'payment',
	'checkout',
	'delivery',
	'shipping',
	'confirm',
	'reset',
	'password',
	'try again',
];

const TONE_KEYWORDS = ['calm', 'clear', 'confidence', 'considered', 'premium', 'quiet', 'reassuring', 'helpful'];

const STRUCTURAL_LABELS = new Set([
	'cart',
	'bag',
	'checkout',
	'orders',
	'downloads',
	'addresses',
	'product',
	'subtotal',
	'shipping',
	'total',
	'payment',
	'quantity',
	'remove',
	'search',
	'close menu',
	'close cart',
	'open menu',
	'view cart',
	'place order',
	'update totals',
	'sort by',
	'store notices',
	'store feedback',
	'customer reviews',
	'write a review',
	'submit review',
]);

const HIGH_RISK_PATTERNS = [
	/\bsecure\b/i,
	/\bsupport\b/i,
	/\bpayment\b/i,
	/\bcheckout\b/i,
	/\breset password\b/i,
	/\bsign in\b/i,
	/\btry again\b/i,
	/\bcould not\b/i,
	/\breview your\b/i,
	/\bavailable payment methods\b/i,
	/\bunavailable\b/i,
];

const REGISTRY_SOURCES = ['inc/content-surfaces/registry.php', 'inc/content-moods/registry.php'];
const CONFIGURATION_ALLOWED_KIRKI_ROOT = 'inc/configuration/kirki/';
const CONFIGURATION_APPROVED_INLINE_STYLE_FILES = new Set(['inc/presets/loader.php']);
const CONFIGURATION_SCAN_ROOTS = ['functions.php', 'inc'];
const CONFIGURATION_TRANSPORT_ROOTS = ['inc/configuration', 'inc/presets'];
const PRESET_RUNTIME_REGISTRY_PATH = 'inc/presets/registry.php';
const PRESET_METADATA_REGISTRY_PATH = 'inc/presets/metadata/registry.php';
const CONTENT_MOOD_REGISTRY_PATH = 'inc/content-moods/registry.php';
const KIRKI_PANELS_PATH = 'inc/configuration/kirki/panels.php';
const KIRKI_SECTIONS_PATH = 'inc/configuration/kirki/sections.php';
const PRESET_METADATA_REQUIRED_KEYS = [
	'visual_identity',
	'commerce_rhythm',
	'density_profile',
	'motion_personality',
	'mood_family',
	'shell_language',
	'content_pacing',
	'capability_matrix',
	'governance_boundary',
	'localization_posture',
];
const IA_SECTION_EXPECTATIONS = [
	{ sectionId: 'tailwindscore_preset_foundation', titleIncludes: 'Design Language' },
	{ sectionId: 'tailwindscore_token_foundation', titleIncludes: 'Design Language' },
	{ sectionId: 'tailwindscore_checkout_content', titleIncludes: 'Commerce Experience' },
	{ sectionId: 'tailwindscore_account_content', titleIncludes: 'Commerce Experience' },
	{ sectionId: 'tailwindscore_site_shell_content', titleIncludes: 'Content Surfaces' },
	{ sectionId: 'tailwindscore_search_content', titleIncludes: 'Content Surfaces' },
];
const LEGACY_IA_LABELS = ['Colors', 'Typography', 'Header', 'Footer'];

function normalizeWhitespace(value) {
	return value.replace(/\s+/g, ' ').trim();
}

function read(relativePath) {
	return readFileSync(path.join(ROOT, relativePath), 'utf8');
}

function readIfExists(relativePath) {
	if (!existsSync(path.join(ROOT, relativePath))) {
		return '';
	}

	return read(relativePath);
}

function walk(relativePath) {
	const absolutePath = path.join(ROOT, relativePath);
	const stats = statSync(absolutePath);
	if (stats.isFile()) {
		return [relativePath];
	}

	const files = [];
	for (const entry of readdirSync(absolutePath, { withFileTypes: true })) {
		const nextRelativePath = path.join(relativePath, entry.name).replace(/\\/g, '/');
		if (entry.isDirectory()) {
			files.push(...walk(nextRelativePath));
			continue;
		}

		if (/\.(php|ts)$/.test(entry.name)) {
			files.push(nextRelativePath);
		}
	}

	return files;
}

function unique(values) {
	return [...new Set(values)];
}

function extractTopLevelArrayBlocks(content) {
	const matches = [...content.matchAll(/^\t\t'([a-z0-9\-_]+)'\s*=>\s*array\(/gm)];
	const blocks = {};

	for (let index = 0; index < matches.length; index += 1) {
		const match = matches[index];
		const start = match.index ?? 0;
		const end = index + 1 < matches.length ? matches[index + 1].index ?? content.length : content.length;
		blocks[match[1]] = content.slice(start, end);
	}

	return blocks;
}

function extractBetween(content, startMarker, endMarker) {
	const start = content.indexOf(startMarker);
	if (start < 0) {
		return '';
	}

	const end = content.indexOf(endMarker, start);
	if (end < 0) {
		return content.slice(start);
	}

	return content.slice(start, end);
}

function extractSingleQuotedValue(block, key) {
	const match = block.match(new RegExp(`'${key}'\\s*=>\\s*'([^']+)'`));
	return match ? match[1] : '';
}

function extractStringArray(block, key) {
	const match = block.match(new RegExp(`'${key}'\\s*=>\\s*array\\(([\\s\\S]*?)\\)\\s*,`, 'm'));
	if (!match) {
		return [];
	}

	return [...match[1].matchAll(/'([^']+)'/g)].map((item) => item[1]);
}

function compareSeverity(left, right) {
	return (SEVERITY_ORDER[left] ?? 99) - (SEVERITY_ORDER[right] ?? 99);
}

function loadBaseline() {
	if (!existsSync(path.join(ROOT, BASELINE_PATH))) {
		return {
			path: BASELINE_PATH,
			entries: [],
			meta: { version: 0, updated: null, stage: 'No baseline file present' },
		};
	}

	const parsed = JSON.parse(read(BASELINE_PATH));
	const entries = Array.isArray(parsed.entries) ? parsed.entries : [];

	return {
		path: BASELINE_PATH,
		entries,
		meta: {
			version: parsed.version ?? 1,
			updated: parsed.updated ?? null,
			stage: parsed.stage ?? 'Sustainable Governance Baseline System',
		},
	};
}

function collectRegistryFallbacks() {
	const fallbacks = new Map();

	for (const source of REGISTRY_SOURCES) {
		const content = read(source);
		const matches = content.matchAll(/\b(?:__|esc_html__|esc_attr__)\(\s*'((?:\\'|[^'])+)'\s*,\s*'tailwindscore'\s*\)/g);
		for (const match of matches) {
			const value = normalizeWhitespace(match[1].replace(/\\'/g, "'"));
			if (!value) {
				continue;
			}

			const locations = fallbacks.get(value) ?? [];
			locations.push(source);
			fallbacks.set(value, locations);
		}
	}

	return fallbacks;
}

function extractPhpLiterals(content) {
	const literals = [];
	const regex = /\b(?:__|esc_html__|esc_attr__|esc_html_e|esc_attr_e)\(\s*'((?:\\'|[^'])+)'\s*,\s*'tailwindscore'\s*\)/g;

	for (const match of content.matchAll(regex)) {
		literals.push(match[1].replace(/\\'/g, "'"));
	}

	return literals;
}

function extractTsLiterals(content) {
	const literals = [];
	const regex = /(?<![A-Za-z0-9_$])(['"`])((?:\\.|(?!\1)[\s\S])*?)\1/g;

	for (const match of content.matchAll(regex)) {
		const raw = match[2];
		if (raw.includes('${')) {
			continue;
		}
		literals.push(raw.replace(/\\'/g, "'").replace(/\\"/g, '"').replace(/\\n/g, ' '));
	}

	return literals;
}

function isCustomerFacingLiteral(value) {
	const normalized = normalizeWhitespace(value);
	if (!normalized) {
		return false;
	}

	const lower = normalized.toLowerCase();
	if (lower.length < 3) {
		return false;
	}

	if (/^(data-|ts-|wc-|aria-|http|#|\.|\/|[a-z0-9_-]+:[a-z0-9_-]+)/.test(lower)) {
		return false;
	}

	if (/[<>={}[\]]/.test(normalized)) {
		return false;
	}

	if (/^[a-z0-9_.:-]+(?:\s+[a-z0-9_.:-]+)+$/i.test(normalized) && !/[A-Z]/.test(normalized) && !/[.,]/.test(normalized)) {
		return false;
	}

	if (/^[a-z0-9_.-]+$/.test(lower) && !STRUCTURAL_LABELS.has(lower)) {
		return false;
	}

	if (/(queryselector|addEventListener|classList|json|content-type|application\/json|true|false|null|undefined)/i.test(normalized)) {
		return false;
	}

	if (/(updated_checkout|checkout_error|added_to_cart|removed_from_cart|ts-checkout-|ts-cart-|ts:)/i.test(normalized)) {
		return false;
	}

	if (/^[A-Z_]+$/.test(normalized)) {
		return false;
	}

	if (normalized.split(' ').length >= 3) {
		return true;
	}

	if (TRUST_KEYWORDS.some((keyword) => lower.includes(keyword))) {
		return true;
	}

	return STRUCTURAL_LABELS.has(lower);
}

function classifyLiteral(literal, context) {
	const normalized = normalizeWhitespace(literal);
	const lower = normalized.toLowerCase();
	const duplicateFallback = context.registryFallbacks.has(normalized);
	const trustLike = TRUST_KEYWORDS.some((keyword) => lower.includes(keyword));
	const tonal = TONE_KEYWORDS.some((keyword) => lower.includes(keyword));
	const highRisk = HIGH_RISK_PATTERNS.some((pattern) => pattern.test(normalized));
	const structural =
		STRUCTURAL_LABELS.has(lower) ||
		(normalized.split(' ').length <= 3 && !/[.,]/.test(normalized) && !trustLike && !tonal && !highRisk);
	const runtimeInline = context.kind === 'ts' || context.line.includes('data-feedback-') || context.line.includes('aria-label');

	let severity = 'notice';
	if (structural && !duplicateFallback) {
		severity = 'accepted-exception';
	} else if ((context.surface.trustCritical && highRisk) || (runtimeInline && highRisk)) {
		severity = 'critical';
	} else if (duplicateFallback || runtimeInline || trustLike || tonal) {
		severity = 'warning';
	}

	return {
		severity,
		surface: context.surface.name,
		governanceOwner: context.surface.owner,
		trustCritical: context.surface.trustCritical,
		runtimeInline,
		duplicateFallback,
		value: normalized,
	};
}

function lineAt(content, literal) {
	const index = content.indexOf(literal);
	if (index < 0) {
		return 1;
	}

	return content.slice(0, index).split('\n').length;
}

function lineAtRegex(content, regex) {
	const match = content.match(regex);
	return match ? lineAt(content, match[0]) : 1;
}

function inferCoverage(surface, files, findings) {
	const filesWithFindings = new Set(findings.map((finding) => finding.file));
	const governedFiles = files.filter((file) => file.usesRegistry || file.usesGovernedEmptyState || file.usesRuntimeDatasetBridge).length;
	const mixedFiles = files.filter((file) => (file.usesRegistry || file.usesGovernedEmptyState || file.usesRuntimeDatasetBridge) && filesWithFindings.has(file.path)).length;
	const ungovernedFiles = files.filter((file) => !file.usesRegistry && !file.usesGovernedEmptyState && !file.usesRuntimeDatasetBridge && filesWithFindings.has(file.path)).length;

	let status = 'governed';
	if (ungovernedFiles > 0) {
		status = 'unguarded';
	} else if (mixedFiles > 0) {
		status = 'mixed';
	}

	return {
		surface: surface.name,
		owner: surface.owner,
		trustCritical: surface.trustCritical,
		scannedFiles: files.length,
		governedFiles,
		mixedFiles,
		unguardedFiles: ungovernedFiles,
		duplicateFallbackFindings: findings.filter((finding) => finding.duplicateFallback).length,
		runtimeFindings: findings.filter((finding) => finding.runtimeInline).length,
		status,
	};
}

function summarizeSeverity(findings) {
	return findings.reduce(
		(summary, finding) => {
			summary[finding.severity] = (summary[finding.severity] ?? 0) + 1;
			return summary;
		},
		{ critical: 0, warning: 0, notice: 0, 'accepted-exception': 0 },
	);
}

function baseFindingType(finding) {
	if (finding.runtimeInline) {
		return 'new-runtime-inline-leakage';
	}

	if (finding.trustCritical || finding.severity === 'critical') {
		return 'new-trust-critical-issue';
	}

	return 'new-governance-leak';
}

function findingMatchesBaseline(finding, entry) {
	if (entry.surface !== finding.surface || entry.file !== finding.file) {
		return false;
	}

	const matchValues = Array.isArray(entry.matchValues) ? entry.matchValues.map(normalizeWhitespace) : [];
	if (matchValues.length > 0) {
		return matchValues.includes(finding.value);
	}

	return normalizeWhitespace(entry.matchValue ?? '') === finding.value;
}

function applyBaselineToFindings(findings, baselineEntries) {
	const matchedBaseline = [];
	const deltaFindings = [];
	const rawFindings = [];
	const matchedEntryIds = new Set();

	for (const finding of findings) {
		const entry = baselineEntries.find((candidate) => findingMatchesBaseline(finding, candidate));
		rawFindings.push(finding);

		if (!entry) {
			deltaFindings.push({
				...finding,
				deltaType: baseFindingType(finding),
			});
			continue;
		}

		matchedEntryIds.add(entry.id);

		if (compareSeverity(finding.severity, entry.severity) < 0) {
			deltaFindings.push({
				...finding,
				baselineId: entry.id,
				baselineStatus: entry.status,
				baselineSeverity: entry.severity,
				deltaType: 'severity-escalation',
			});
			continue;
		}

		matchedBaseline.push({
			id: entry.id,
			status: entry.status,
			severity: entry.severity,
			file: entry.file,
			surface: entry.surface,
			value: finding.value,
			plannedResolutionStage: entry.plannedResolutionStage,
		});
	}

	const unmatchedBaselineEntries = baselineEntries.filter((entry) => !matchedEntryIds.has(entry.id));

	return {
		rawFindings,
		deltaFindings,
		matchedBaseline,
		unmatchedBaselineEntries,
	};
}

function summarizeBaseline(matchedBaseline, baselineEntries, unmatchedBaselineEntries) {
	const statusSummary = baselineEntries.reduce(
		(summary, entry) => {
			summary[entry.status] = (summary[entry.status] ?? 0) + 1;
			return summary;
		},
		{ accepted: 0, deferred: 0 },
	);

	return {
		totalEntries: baselineEntries.length,
		matchedFindings: matchedBaseline.length,
		unmatchedEntries: unmatchedBaselineEntries.length,
		statusSummary,
	};
}

function summarizeDelta(findings) {
	return findings.reduce(
		(summary, finding) => {
			summary[finding.deltaType] = (summary[finding.deltaType] ?? 0) + 1;
			return summary;
		},
		{
			'new-governance-leak': 0,
			'new-runtime-inline-leakage': 0,
			'new-trust-critical-issue': 0,
			'severity-escalation': 0,
		},
	);
}

function collectFindings() {
	const registryFallbacks = collectRegistryFallbacks();
	const findings = [];
	const coverage = [];
	const scannedFiles = [];

	for (const surface of SCAN_TARGETS) {
		const filePaths = unique(surface.roots.flatMap((root) => walk(root)));
		const surfaceFiles = [];
		const surfaceFindings = [];

		for (const relativePath of filePaths) {
			const content = read(relativePath);
			const kind = relativePath.endsWith('.ts') ? 'ts' : 'php';
			const literals = kind === 'ts' ? extractTsLiterals(content) : extractPhpLiterals(content);
			const fileRecord = {
				path: relativePath,
				surface: surface.name,
				usesRegistry: surface.registrySignals.some((signal) => content.includes(signal)),
				usesGovernedEmptyState: content.includes('tailwindscore_feedback_empty_state_copy'),
				usesRuntimeDatasetBridge: /data-feedback-[a-z-]+/.test(content) || /\.dataset\.[A-Za-z]+/.test(content),
			};

			surfaceFiles.push(fileRecord);
			scannedFiles.push(fileRecord);

			for (const literal of unique(literals).filter(isCustomerFacingLiteral)) {
				const lineNumber = lineAt(content, literal);
				const line = content.split('\n')[lineNumber - 1] ?? '';
				const classification = classifyLiteral(literal, {
					kind,
					line,
					surface,
					registryFallbacks,
				});

				if (classification.severity === 'accepted-exception' && !classification.duplicateFallback) {
					continue;
				}

				const finding = {
					...classification,
					file: relativePath,
					line: lineNumber,
				};
				findings.push(finding);
				surfaceFindings.push(finding);
			}
		}

		coverage.push(inferCoverage(surface, surfaceFiles, surfaceFindings));
	}

	return {
		findings: findings.sort((left, right) => compareSeverity(left.severity, right.severity) || left.file.localeCompare(right.file) || left.line - right.line),
		coverage,
		scannedFiles,
	};
}

function collectConfigurationFindings() {
	const kirkiUsagePaths = unique(
		CONFIGURATION_SCAN_ROOTS.flatMap((root) => {
			if (!existsSync(path.join(ROOT, root))) {
				return [];
			}
			return walk(root);
		}),
	);
	const transportPaths = unique(
		CONFIGURATION_TRANSPORT_ROOTS.flatMap((root) => {
			if (!existsSync(path.join(ROOT, root))) {
				return [];
			}
			return walk(root);
		}),
	);
	const findings = [];
	const scannedFiles = [];

	for (const relativePath of unique([...kirkiUsagePaths, ...transportPaths])) {
		if (!relativePath.endsWith('.php') && !relativePath.endsWith('.ts')) {
			continue;
		}

		const content = read(relativePath);
		scannedFiles.push({
			path: relativePath,
			surface: 'configuration',
			usesRegistry: relativePath.startsWith(CONFIGURATION_ALLOWED_KIRKI_ROOT),
			usesGovernedEmptyState: false,
			usesRuntimeDatasetBridge: false,
		});

		const directKirkiPattern = /\bKirki::add_(?:config|panel|section|field)\b|new\s+\\?Kirki\\Field/;
		if (!relativePath.startsWith(CONFIGURATION_ALLOWED_KIRKI_ROOT) && directKirkiPattern.test(content)) {
			findings.push({
				severity: 'critical',
				surface: 'configuration',
				governanceOwner: 'Theme configuration governance',
				trustCritical: false,
				runtimeInline: false,
				duplicateFallback: false,
				value: 'Direct Kirki usage outside foundation layer',
				file: relativePath,
				line: lineAtRegex(content, directKirkiPattern),
			});
		}

		const inlineCssPattern = /wp_add_inline_style\s*\(|:root\s*\{/;
		if (transportPaths.includes(relativePath) && !CONFIGURATION_APPROVED_INLINE_STYLE_FILES.has(relativePath) && inlineCssPattern.test(content)) {
			findings.push({
				severity: 'warning',
				surface: 'configuration',
				governanceOwner: 'Theme configuration governance',
				trustCritical: false,
				runtimeInline: true,
				duplicateFallback: false,
				value: 'Arbitrary CSS output outside governed preset transport',
				file: relativePath,
				line: lineAtRegex(content, inlineCssPattern),
			});
		}

		const inlineStyleLeakagePattern = /style\s*=\s*["']/;
		if (transportPaths.includes(relativePath) && relativePath.endsWith('.php') && inlineStyleLeakagePattern.test(content)) {
			findings.push({
				severity: 'warning',
				surface: 'configuration',
				governanceOwner: 'Theme configuration governance',
				trustCritical: false,
				runtimeInline: true,
				duplicateFallback: false,
				value: 'Inline style leakage detected in PHP template output',
				file: relativePath,
				line: lineAtRegex(content, inlineStyleLeakagePattern),
			});
		}
	}

	const transportPath = 'inc/configuration/kirki/fields/api.php';
	const transportContent = existsSync(path.join(ROOT, transportPath))
		? read(transportPath)
		: '';
	if (transportContent && /'transport'\s*=>\s*'(?!refresh)[^']+'/.test(transportContent)) {
		findings.push({
			severity: 'critical',
			surface: 'configuration',
			governanceOwner: 'Theme configuration governance',
			trustCritical: false,
			runtimeInline: true,
			duplicateFallback: false,
			value: 'Invalid non-SSR transport detected in governed Kirki registration',
			file: transportPath,
			line: lineAtRegex(transportContent, /'transport'\s*=>\s*'(?!refresh)[^']+'/),
		});
	}

	const presetRegistryPath = 'inc/presets/registry.php';
	if (existsSync(path.join(ROOT, presetRegistryPath))) {
		const presetContent = read(presetRegistryPath);
		const presetBoundaryPattern = /'(?:template|templates|bundle|bundles|layout|layouts|markup|markup_structure|stylesheet|stylesheets)'\s*=>/;
		if (presetBoundaryPattern.test(presetContent)) {
			findings.push({
				severity: 'critical',
				surface: 'configuration',
				governanceOwner: 'Preset governance',
				trustCritical: false,
				runtimeInline: false,
				duplicateFallback: false,
				value: 'Preset boundary violation detected in preset registry',
				file: presetRegistryPath,
				line: lineAtRegex(presetContent, presetBoundaryPattern),
			});
		}
	}

	const presetRuntimeContent = readIfExists(PRESET_RUNTIME_REGISTRY_PATH);
	const presetMetadataContent = readIfExists(PRESET_METADATA_REGISTRY_PATH);
	const contentMoodContent = readIfExists(CONTENT_MOOD_REGISTRY_PATH);
	const presetRuntimeBlocks = extractTopLevelArrayBlocks(
		extractBetween(presetRuntimeContent, '$presets = array(', 'return apply_filters( \'tailwindscore/presets/registry\''),
	);
	const presetMetadataBlocks = extractTopLevelArrayBlocks(
		extractBetween(presetMetadataContent, '$presets = array(', 'return apply_filters( \'tailwindscore/presets/personality_registry\''),
	);
	const moodBlocks = extractTopLevelArrayBlocks(
		extractBetween(contentMoodContent, '$moods = array(', 'return apply_filters( \'tailwindscore/content_moods/registry\''),
	);
	const panelContent = readIfExists(KIRKI_PANELS_PATH);
	const sectionContent = readIfExists(KIRKI_SECTIONS_PATH);
	const sectionBlocks = extractTopLevelArrayBlocks(sectionContent);
	const missingPresetMetadata = [];
	const stalePresetMetadata = [];
	const presetsMissingLocalizationPosture = [];
	const presetSurfaceMismatches = [];
	let alignedPresetCount = 0;

	for (const presetKey of Object.keys(presetRuntimeBlocks)) {
		const runtimeBlock = presetRuntimeBlocks[presetKey];
		const metadataBlock = presetMetadataBlocks[presetKey] ?? '';

		if (!metadataBlock) {
			missingPresetMetadata.push(presetKey);
			findings.push({
				severity: 'critical',
				surface: 'configuration',
				governanceOwner: 'Preset governance',
				trustCritical: false,
				runtimeInline: false,
				duplicateFallback: false,
				value: `Preset personality metadata missing for ${presetKey}`,
				file: PRESET_METADATA_REGISTRY_PATH,
				line: 1,
			});
			continue;
		}

		let metadataComplete = true;

		for (const requiredKey of PRESET_METADATA_REQUIRED_KEYS) {
			if (!metadataBlock.includes(`'${requiredKey}'`)) {
				metadataComplete = false;
				findings.push({
					severity: 'warning',
					surface: 'configuration',
					governanceOwner: 'Preset governance',
					trustCritical: false,
					runtimeInline: false,
					duplicateFallback: false,
					value: `Preset personality metadata missing required key ${requiredKey} for ${presetKey}`,
					file: PRESET_METADATA_REGISTRY_PATH,
					line: lineAt(readIfExists(PRESET_METADATA_REGISTRY_PATH), presetKey),
				});
			}
		}

		if (!metadataBlock.includes("'template_branching'  => 'prohibited'")) {
			findings.push({
				severity: 'critical',
				surface: 'configuration',
				governanceOwner: 'Preset governance',
				trustCritical: false,
				runtimeInline: false,
				duplicateFallback: false,
				value: `Preset governance boundary must prohibit template branching for ${presetKey}`,
				file: PRESET_METADATA_REGISTRY_PATH,
				line: lineAt(readIfExists(PRESET_METADATA_REGISTRY_PATH), presetKey),
			});
		}

		if (!metadataBlock.includes("'localization_posture'")) {
			presetsMissingLocalizationPosture.push(presetKey);
		}

		const moodKey = extractSingleQuotedValue(runtimeBlock, 'mood_key');
		const runtimeSurfaces = extractStringArray(runtimeBlock, 'supported_surfaces');
		const moodBlock = moodBlocks[moodKey] ?? '';

		if (!moodBlock) {
			findings.push({
				severity: 'critical',
				surface: 'configuration',
				governanceOwner: 'Localization governance',
				trustCritical: false,
				runtimeInline: false,
				duplicateFallback: false,
				value: `Preset ${presetKey} maps to missing content mood ${moodKey || 'unknown'}`,
				file: PRESET_RUNTIME_REGISTRY_PATH,
				line: lineAt(readIfExists(PRESET_RUNTIME_REGISTRY_PATH), presetKey),
			});
		} else {
			const moodSurfaces = extractStringArray(moodBlock, 'supported_surfaces');
			const unsupportedRuntimeSurfaces = runtimeSurfaces.filter((surface) => !moodSurfaces.includes(surface));

			if (unsupportedRuntimeSurfaces.length > 0) {
				presetSurfaceMismatches.push({
					presetKey,
					moodKey,
					unsupportedRuntimeSurfaces,
				});
				findings.push({
					severity: 'warning',
					surface: 'configuration',
					governanceOwner: 'Localization governance',
					trustCritical: false,
					runtimeInline: false,
					duplicateFallback: false,
					value: `Preset ${presetKey} uses surfaces unsupported by mood ${moodKey}: ${unsupportedRuntimeSurfaces.join(', ')}`,
					file: PRESET_RUNTIME_REGISTRY_PATH,
					line: lineAt(readIfExists(PRESET_RUNTIME_REGISTRY_PATH), presetKey),
				});
			}
		}

		if (metadataComplete) {
			alignedPresetCount += 1;
		}
	}

	for (const presetKey of Object.keys(presetMetadataBlocks)) {
		if (!presetRuntimeBlocks[presetKey]) {
			stalePresetMetadata.push(presetKey);
			findings.push({
				severity: 'warning',
				surface: 'configuration',
				governanceOwner: 'Preset governance',
				trustCritical: false,
				runtimeInline: false,
				duplicateFallback: false,
				value: `Preset personality metadata exists without runtime preset ${presetKey}`,
				file: PRESET_METADATA_REGISTRY_PATH,
				line: lineAt(readIfExists(PRESET_METADATA_REGISTRY_PATH), presetKey),
			});
		}
	}

	const panelAligned = panelContent.includes('Commerce Configuration') && panelContent.includes('design language, commerce experience, content surfaces, and governance visibility');
	if (!panelAligned) {
		findings.push({
			severity: 'warning',
			surface: 'configuration',
			governanceOwner: 'Theme configuration governance',
			trustCritical: false,
			runtimeInline: false,
			duplicateFallback: false,
			value: 'Configuration panel language has drifted from governance IA',
			file: KIRKI_PANELS_PATH,
			line: 1,
		});
	}

	let alignedSections = 0;
	let invalidGroupingCount = 0;

	for (const expectation of IA_SECTION_EXPECTATIONS) {
		const block = sectionBlocks[expectation.sectionId] ?? '';
		if (!block || !block.includes(expectation.titleIncludes)) {
			invalidGroupingCount += 1;
			findings.push({
				severity: 'warning',
				surface: 'configuration',
				governanceOwner: 'Theme configuration governance',
				trustCritical: false,
				runtimeInline: false,
				duplicateFallback: false,
				value: `Admin IA drift detected for ${expectation.sectionId}; expected ${expectation.titleIncludes}`,
				file: KIRKI_SECTIONS_PATH,
				line: lineAt(sectionContent, expectation.sectionId),
			});
		} else {
			alignedSections += 1;
		}
	}

	for (const legacyLabel of LEGACY_IA_LABELS) {
		if (panelContent.includes(`'${legacyLabel}'`) || sectionContent.includes(`'${legacyLabel}'`)) {
			invalidGroupingCount += 1;
			findings.push({
				severity: 'warning',
				surface: 'configuration',
				governanceOwner: 'Theme configuration governance',
				trustCritical: false,
				runtimeInline: false,
				duplicateFallback: false,
				value: `Legacy configuration grouping label detected: ${legacyLabel}`,
				file: panelContent.includes(`'${legacyLabel}'`) ? KIRKI_PANELS_PATH : KIRKI_SECTIONS_PATH,
				line: panelContent.includes(`'${legacyLabel}'`) ? lineAt(panelContent, legacyLabel) : lineAt(sectionContent, legacyLabel),
			});
		}
	}

	const summary = {
		presetCompatibility: {
			runtimePresetCount: Object.keys(presetRuntimeBlocks).length,
			metadataPresetCount: Object.keys(presetMetadataBlocks).length,
			alignedPresetCount,
			missingMetadataPresets: missingPresetMetadata,
			staleMetadataPresets: stalePresetMetadata,
			surfaceMismatchCount: presetSurfaceMismatches.length,
		},
		localizationCoverage: {
			presetCount: Object.keys(presetRuntimeBlocks).length,
			presetsMissingLocalizationPosture,
			moodMappingsChecked: Object.keys(presetRuntimeBlocks).length,
			mismatchedMoodMappings: presetSurfaceMismatches.map((item) => item.presetKey),
		},
		adminIADrift: {
			panelAligned,
			alignedSections,
			totalSections: IA_SECTION_EXPECTATIONS.length,
			driftCount: invalidGroupingCount,
		},
	};

	return {
		findings: findings.sort((left, right) => compareSeverity(left.severity, right.severity) || left.file.localeCompare(right.file) || left.line - right.line),
		coverage: [
			{
				surface: 'configuration',
				owner: 'Theme configuration governance',
				trustCritical: false,
				scannedFiles: scannedFiles.length,
				governedFiles: scannedFiles.filter(
					(file) => file.path.startsWith(CONFIGURATION_ALLOWED_KIRKI_ROOT) || CONFIGURATION_APPROVED_INLINE_STYLE_FILES.has(file.path),
				).length,
				mixedFiles: 0,
				unguardedFiles: findings.length > 0 ? 1 : 0,
				duplicateFallbackFindings: 0,
				runtimeFindings: findings.filter((finding) => finding.runtimeInline).length,
				status: findings.length > 0 ? 'unguarded' : 'governed',
			},
		],
		scannedFiles,
		summary,
	};
}

function buildReport() {
	const collected = collectFindings();
	const configuration = collectConfigurationFindings();
	const combinedFindings = [...collected.findings, ...configuration.findings].sort(
		(left, right) => compareSeverity(left.severity, right.severity) || left.file.localeCompare(right.file) || left.line - right.line,
	);
	const baseline = loadBaseline();
	const baselineResult = applyBaselineToFindings(combinedFindings, baseline.entries);
	const governedRows = [...collected.coverage, ...configuration.coverage];
	const governedSurfaceCoverage = {
		total: governedRows.length,
		governed: governedRows.filter((row) => row.status === 'governed').length,
		mixed: governedRows.filter((row) => row.status === 'mixed').length,
		unguarded: governedRows.filter((row) => row.status === 'unguarded').length,
	};
	const criticalLeakCount = baselineResult.deltaFindings.filter((finding) => finding.severity === 'critical').length;
	const runtimeAlignmentHealth = {
		status: configuration.findings.some((finding) => finding.runtimeInline || finding.severity === 'critical') ? 'attention' : 'aligned',
		runtimeInlineFindings: configuration.findings.filter((finding) => finding.runtimeInline).length,
		criticalConfigurationFindings: configuration.findings.filter((finding) => finding.severity === 'critical').length,
	};
	const governanceDashboard = {
		governedSurfaceCoverage,
		criticalLeakCount,
		presetCompatibility: configuration.summary.presetCompatibility,
		localizationCoverage: configuration.summary.localizationCoverage,
		runtimeAlignmentHealth,
		adminIADrift: configuration.summary.adminIADrift,
	};

	return {
		report: {
			name: 'TailwindScore Sustainable Governance Baseline System',
			date: REPORT_DATE,
			stage: 'Sustainable Governance Baseline System',
			baselinePath: baseline.path,
			baselineUpdated: baseline.meta.updated,
		},
		severitySummary: summarizeSeverity(baselineResult.rawFindings),
		deltaSeveritySummary: summarizeSeverity(baselineResult.deltaFindings),
		deltaSummary: summarizeDelta(baselineResult.deltaFindings),
		baselineSummary: summarizeBaseline(baselineResult.matchedBaseline, baseline.entries, baselineResult.unmatchedBaselineEntries),
		coverage: governedRows,
		presetCompatibility: configuration.summary.presetCompatibility,
		localizationCoverage: configuration.summary.localizationCoverage,
		adminIADrift: configuration.summary.adminIADrift,
		governanceDashboard,
		findings: baselineResult.deltaFindings,
		rawFindings: baselineResult.rawFindings,
		matchedBaselineFindings: baselineResult.matchedBaseline,
		unmatchedBaselineEntries: baselineResult.unmatchedBaselineEntries,
		scannedFiles: [...collected.scannedFiles, ...configuration.scannedFiles],
	};
}

function printConsoleReport(report, options) {
	const findingsToPrint = options.allFindings ? report.rawFindings : report.findings;
	const header = options.allFindings ? 'All Findings' : 'Delta Findings';

	console.log(`Governance Detection Report - ${report.report.date}`);
	console.log(`Stage: ${report.report.stage}`);
	console.log(`Baseline: ${report.report.baselinePath} (${report.report.baselineUpdated ?? 'unversioned'})`);
	console.log('');
	console.log('Baseline Summary');
	console.log(`- entries: ${report.baselineSummary.totalEntries}`);
	console.log(`- matched findings: ${report.baselineSummary.matchedFindings}`);
	console.log(`- unmatched baseline entries: ${report.baselineSummary.unmatchedEntries}`);
	console.log(`- accepted baseline entries: ${report.baselineSummary.statusSummary.accepted ?? 0}`);
	console.log(`- deferred baseline entries: ${report.baselineSummary.statusSummary.deferred ?? 0}`);
	console.log('');
	console.log('Coverage');
	for (const row of report.coverage) {
		console.log(
			`- ${row.surface}: ${row.status} | governed ${row.governedFiles}/${row.scannedFiles} | mixed ${row.mixedFiles} | unguarded ${row.unguardedFiles} | duplicate fallback ${row.duplicateFallbackFindings}`,
		);
	}

	console.log('');
	console.log('Delta Summary');
	for (const [deltaType, count] of Object.entries(report.deltaSummary)) {
		console.log(`- ${deltaType}: ${count}`);
	}

	console.log('');
	console.log(header);
	if (findingsToPrint.length === 0) {
		console.log('- none');
		return;
	}

	for (const finding of findingsToPrint.slice(0, 20)) {
		const prefix = options.allFindings ? `[${finding.severity}]` : `[${finding.deltaType}] [${finding.severity}]`;
		console.log(`- ${prefix} ${finding.surface} :: ${finding.file}:${finding.line} :: ${finding.value}`);
	}
}

const jsonOnly = process.argv.includes('--json');
const allFindings = process.argv.includes('--all-findings');
const report = buildReport();

if (jsonOnly) {
	console.log(JSON.stringify(report, null, 2));
} else {
	printConsoleReport(report, { allFindings });
}
