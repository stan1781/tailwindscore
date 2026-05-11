# Configuration UX Consolidation Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Consolidate preset governance, configuration IA, and scanner enforcement into a premium commerce configuration experience without expanding runtime commerce features.

**Architecture:** Add a preset personality metadata layer beside the existing preset runtime registry, route Kirki preset summaries through that metadata, realign configuration language around governance domains, and extend the governance scanner to detect configuration drift. Keep all outputs SSR-first, registry-first, and read-only.

**Tech Stack:** PHP, WordPress Customizer/Kirki, Node.js, existing governance scanner, Markdown docs

---

### Task 1: Add preset personality metadata foundation

**Files:**
- Create: `inc/presets/metadata/registry.php`
- Modify: `inc/bootstrap.php`
- Test: `tests/preset-personality-metadata-test.php`

- [ ] **Step 1: Write the failing test**

```php
<?php

declare(strict_types=1);

define('ABSPATH', __DIR__);

function sanitize_key(string $value): string {
	$value = strtolower($value);
	return preg_replace('/[^a-z0-9_\-]/', '', $value) ?? '';
}

function apply_filters(string $hook, $value) {
	return $value;
}

require_once __DIR__ . '/../inc/presets/metadata/registry.php';

$metadata = tailwindscore_preset_personality_registry();
$premium  = tailwindscore_preset_personality_definition('premium-dtc');

if (!isset($metadata['premium-dtc'])) {
	fwrite(STDERR, "Expected premium-dtc personality metadata.\n");
	exit(1);
}

foreach (['visual_identity', 'commerce_rhythm', 'density_profile', 'motion_personality', 'mood_family', 'shell_language', 'content_pacing', 'capability_matrix', 'governance_boundary', 'localization_posture'] as $requiredKey) {
	if (!isset($premium[$requiredKey])) {
		fwrite(STDERR, "Missing required personality key: {$requiredKey}\n");
		exit(1);
	}
}

if (($premium['governance_boundary']['template_branching'] ?? null) !== 'prohibited') {
	fwrite(STDERR, "Expected template branching to be prohibited.\n");
	exit(1);
}

fwrite(STDOUT, "OK\n");
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php tests/preset-personality-metadata-test.php`
Expected: FAIL because `inc/presets/metadata/registry.php` does not exist yet

- [ ] **Step 3: Write minimal implementation**

```php
<?php
declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

function tailwindscore_preset_personality_registry(): array {
	return array(
		'premium-dtc' => array(
			'visual_identity'      => array(),
			'commerce_rhythm'      => array(),
			'density_profile'      => array(),
			'motion_personality'   => array(),
			'mood_family'          => array(),
			'shell_language'       => array(),
			'content_pacing'       => array(),
			'capability_matrix'    => array(),
			'governance_boundary'  => array(
				'template_branching' => 'prohibited',
			),
			'localization_posture' => array(),
		),
	);
}

function tailwindscore_preset_personality_definition( string $key ): array {
	$registry = tailwindscore_preset_personality_registry();
	$key      = sanitize_key( $key );

	return $registry[ $key ] ?? array();
}
```

- [ ] **Step 4: Run test to verify it passes**

Run: `php tests/preset-personality-metadata-test.php`
Expected: PASS with `OK`

- [ ] **Step 5: Commit**

```bash
git add tests/preset-personality-metadata-test.php inc/presets/metadata/registry.php inc/bootstrap.php
git commit -m "feat: add preset personality metadata registry"
```

### Task 2: Route preset preview descriptions through personality metadata

**Files:**
- Modify: `inc/configuration/kirki/presets/registry.php`
- Test: `tests/preset-preview-description-test.php`

- [ ] **Step 1: Write the failing test**

```php
<?php

declare(strict_types=1);

define('ABSPATH', __DIR__);

function sanitize_key(string $value): string {
	$value = strtolower($value);
	return preg_replace('/[^a-z0-9_\-]/', '', $value) ?? '';
}

function apply_filters(string $hook, $value) {
	return $value;
}

require_once __DIR__ . '/../inc/presets/metadata/registry.php';
require_once __DIR__ . '/../inc/presets/registry.php';
require_once __DIR__ . '/../inc/configuration/kirki/presets/registry.php';

$description = tailwindscore_kirki_preset_preview_description();

foreach (['Tone:', 'Motion:', 'Density:', 'Commerce pacing:', 'Governance:'] as $expectedSnippet) {
	if (false === strpos($description, $expectedSnippet)) {
		fwrite(STDERR, "Expected snippet missing: {$expectedSnippet}\n");
		exit(1);
	}
}

fwrite(STDOUT, "OK\n");
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php tests/preset-preview-description-test.php`
Expected: FAIL because the preview helper only returns label plus description

- [ ] **Step 3: Write minimal implementation**

```php
function tailwindscore_kirki_preset_preview_description(): string {
	$lines = array();

	foreach ( tailwindscore_preset_registry() as $preset_key => $preset ) {
		$personality = tailwindscore_preset_personality_definition( $preset_key );
		$lines[] = sprintf(
			"%s\nTone: %s\nMotion: %s\nDensity: %s\nCommerce pacing: %s\nGovernance: %s",
			$preset['label'] ?? $preset_key,
			$personality['mood_family']['tone'] ?? '',
			$personality['motion_personality']['description'] ?? '',
			$personality['density_profile']['description'] ?? '',
			$personality['content_pacing']['commerce'] ?? '',
			$personality['governance_boundary']['summary'] ?? ''
		);
	}

	return implode( "\n\n", $lines );
}
```

- [ ] **Step 4: Run test to verify it passes**

Run: `php tests/preset-preview-description-test.php`
Expected: PASS with `OK`

- [ ] **Step 5: Commit**

```bash
git add tests/preset-preview-description-test.php inc/configuration/kirki/presets/registry.php
git commit -m "feat: enrich preset preview summaries"
```

### Task 3: Realign Customizer panel and section language with configuration IA

**Files:**
- Modify: `inc/configuration/kirki/panels.php`
- Modify: `inc/configuration/kirki/sections.php`
- Create: `docs/configuration/admin-ia.md`
- Create: `docs/configuration/admin-language.md`

- [ ] **Step 1: Write the failing test**

No code-level test. Use a content assertion command after edits.

- [ ] **Step 2: Run check to capture current behavior**

Run: `rg -n "Theme Configuration|Preset Foundation|Token Profiles|Site Shell Content|Checkout Guidance|Account Guidance|Search Guidance" inc/configuration/kirki`
Expected: current legacy naming appears

- [ ] **Step 3: Write minimal implementation**

```php
'title' => __( 'Commerce Configuration', 'tailwindscore' ),
'description' => __( 'Preset-governed configuration for design language, commerce experience, content surfaces, and governance visibility.', 'tailwindscore' ),
```

and regroup section titles/descriptions so they map to the IA domains while keeping the same underlying foundation layer and no extra admin pages.

- [ ] **Step 4: Run check to verify it passes**

Run: `rg -n "Commerce Configuration|Design Language|Commerce Experience|Content Surfaces|Governance" inc/configuration/kirki docs/configuration`
Expected: updated IA language appears in code and docs

- [ ] **Step 5: Commit**

```bash
git add inc/configuration/kirki/panels.php inc/configuration/kirki/sections.php docs/configuration/admin-ia.md docs/configuration/admin-language.md
git commit -m "docs: align configuration IA with governance domains"
```

### Task 4: Add preset personality and governance strategy documentation

**Files:**
- Create: `docs/presets/preset-personality-system.md`
- Create: `docs/governance-audit/governance-dashboard.md`
- Create: `docs/content-moods/localization-tone-strategy.md`
- Create: `docs/content-moods/multilingual-governance.md`

- [ ] **Step 1: Write the failing test**

No automated test. Verification is file existence plus terminology checks.

- [ ] **Step 2: Run check to confirm files are absent**

Run: `rg --files docs/presets docs/governance-audit docs/content-moods`
Expected: the four target files are absent

- [ ] **Step 3: Write minimal implementation**

Document:

- preset personality fields, capability matrix, governance boundary, and anti-expansion rules
- governance dashboard read-only metrics and data-contract expectations
- localization tone-safe rules, mood compatibility, and fallback behavior

- [ ] **Step 4: Run check to verify it passes**

Run: `rg -n "capability matrix|governance boundary|governed surface coverage|critical leak count|tone-safe|locale-aware fallback" docs/presets docs/governance-audit docs/content-moods`
Expected: each required concept appears in the new docs

- [ ] **Step 5: Commit**

```bash
git add docs/presets/preset-personality-system.md docs/governance-audit/governance-dashboard.md docs/content-moods/localization-tone-strategy.md docs/content-moods/multilingual-governance.md
git commit -m "docs: define configuration governance consolidation contracts"
```

### Task 5: Extend governance scanner for preset and configuration drift detection

**Files:**
- Modify: `scripts/governance-scan.mjs`
- Test: `npm run governance:scan -- --json`

- [ ] **Step 1: Write the failing test**

Add scanner assertions by running the current script and confirming the new report families are missing.

- [ ] **Step 2: Run test to verify it fails**

Run: `npm run governance:scan -- --json`
Expected: JSON report does not expose dedicated preset compatibility, localization coverage, or admin IA drift reporting yet

- [ ] **Step 3: Write minimal implementation**

Extend the scanner to:

- load preset personality metadata
- validate preset runtime registry keys against metadata keys
- detect missing capability matrix / governance boundary / localization posture
- compare configuration section titles and descriptions against the new IA grouping contract
- report localization mismatches between preset metadata and content mood support
- summarize governance dashboard metrics in JSON output

- [ ] **Step 4: Run test to verify it passes**

Run: `npm run governance:scan -- --json`
Expected: JSON report contains structured configuration-governance summary fields and no runtime crashes

- [ ] **Step 5: Commit**

```bash
git add scripts/governance-scan.mjs
git commit -m "feat: extend governance scanner for configuration drift"
```

### Task 6: Final verification

**Files:**
- Verify: `inc/presets/metadata/registry.php`
- Verify: `inc/configuration/kirki/presets/registry.php`
- Verify: `inc/configuration/kirki/panels.php`
- Verify: `inc/configuration/kirki/sections.php`
- Verify: `scripts/governance-scan.mjs`
- Verify: `docs/presets/preset-personality-system.md`
- Verify: `docs/configuration/admin-ia.md`
- Verify: `docs/configuration/admin-language.md`
- Verify: `docs/governance-audit/governance-dashboard.md`
- Verify: `docs/content-moods/localization-tone-strategy.md`
- Verify: `docs/content-moods/multilingual-governance.md`
- Verify: `tests/preset-personality-metadata-test.php`
- Verify: `tests/preset-preview-description-test.php`

- [ ] **Step 1: Run PHP metadata test**

Run: `php tests/preset-personality-metadata-test.php`
Expected: PASS with `OK`

- [ ] **Step 2: Run PHP preview summary test**

Run: `php tests/preset-preview-description-test.php`
Expected: PASS with `OK`

- [ ] **Step 3: Run governance scan**

Run: `npm run governance:scan`
Expected: report prints without runtime errors

- [ ] **Step 4: Run TypeScript verification**

Run: `npm run typecheck`
Expected: PASS

- [ ] **Step 5: Commit**

```bash
git add docs/superpowers/specs/2026-05-11-configuration-ux-consolidation-design.md docs/superpowers/plans/2026-05-11-configuration-ux-consolidation.md
git commit -m "docs: record configuration ux consolidation plan"
```
