# Commerce Archive Experience Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build the premium commerce collection foundation for WooCommerce archive cards with SSR-first media, display-only swatches, restrained action reveal, archive runtime enhancement, and supporting documentation.

**Architecture:** Extend the existing `product-card` shell instead of replacing the WooCommerce loop. Add archive-specific SSR subcomponents and structured adapter data, then enhance them with a small TypeScript archive runtime that only reads SSR state and updates local card UI.

**Tech Stack:** PHP, WooCommerce, WordPress template parts, TypeScript, existing TailwindScore runtime registry, CSS component layers

---

## File Map

### Existing files to modify

- `inc/woocommerce/adapters/product-card.php`
  - Expand flat card props into grouped archive-friendly structures.
- `template-parts/components/product-card.php`
  - Keep as the outer shell and compose archive subcomponents inside it.
- `src/ts/modules/register.ts`
  - Register the archive runtime entry.
- `src/css/components/commerce/index.css`
  - Import the new archive CSS system or switch product-card styling import ownership cleanly.

### New PHP component files

- `template-parts/components/archive/product-card-media.php`
  - Render primary and secondary media, media host, ratio shell, and preview state attributes.
- `template-parts/components/archive/product-card-swatches.php`
  - Render display-only swatch previews with fixed-size controls and `+N`.
- `template-parts/components/archive/product-card-actions.php`
  - Render SSR-first action reveal area for simple-product add to cart and optional slots.
- `template-parts/components/archive/product-card-badges.php`
  - Render badge stack in an archive-specific wrapper.

### New TypeScript files

- `src/ts/modules/archive/index.ts`
  - Mount archive card modules inside archive roots.
- `src/ts/modules/archive/product-card-hover.ts`
  - Preload and switch secondary image on pointer/focus interaction.
- `src/ts/modules/archive/archive-swatches.ts`
  - Keep display-only swatch selected state and sync preview media within the current card.
- `src/ts/modules/archive/collection-grid.ts`
  - Apply pointer and density state classes to the archive grid.

### New CSS files

- `src/css/components/product-archive/index.css`
- `src/css/components/product-archive/product-card.css`
- `src/css/components/product-archive/product-card-media.css`
- `src/css/components/product-archive/product-card-swatches.css`
- `src/css/components/product-archive/product-card-actions.css`
- `src/css/components/product-archive/collection-grid.css`

### New docs

- `docs/archive-experience/collection-rules.md`
- `docs/archive-experience/product-card-ux.md`
- `docs/archive-experience/mobile-grid.md`
- `docs/archive-experience/archive-swatches.md`

---

### Task 1: Expand Product Card Adapter Data

**Files:**
- Modify: `inc/woocommerce/adapters/product-card.php`

- [ ] **Step 1: Write the failing inspection script**

Create a temporary inspection script that documents the target adapter shape before implementation:

```php
<?php
declare(strict_types=1);

fwrite(STDOUT, json_encode(array(
	'expected_keys' => array(
		'media',
		'swatches',
		'actions',
		'badges',
		'collection',
	),
), JSON_PRETTY_PRINT) . PHP_EOL);
```

- [ ] **Step 2: Run the inspection script**

Run: `php temp/archive-card-adapter-shape.php`
Expected: JSON output listing `media`, `swatches`, `actions`, `badges`, `collection`

- [ ] **Step 3: Implement grouped adapter props in `inc/woocommerce/adapters/product-card.php`**

Add grouped structures while keeping existing core fields during the transition:

```php
$props = array(
	'permalink' => get_permalink( $product->get_id() ),
	'title'     => $product->get_name(),
	'title_tag' => 'h3',
	'media'     => array(
		'primary'       => array(
			'image_id' => $image_id,
			'url'      => $image_url,
			'alt'      => $alt,
			'width'    => null !== $width ? $width : '',
			'height'   => null !== $height ? $height : '',
		),
		'secondary'     => array(
			'image_id' => 0,
			'url'      => '',
			'alt'      => '',
			'width'    => '',
			'height'   => '',
		),
		'aspect_ratio'  => '4 / 5',
		'hover_enabled' => false,
	),
	'swatches'  => array(
		'mode'        => 'preview',
		'items'       => array(),
		'max_visible' => 5,
		'more_count'  => 0,
	),
	'actions'   => array(
		'primary'            => array(),
		'wishlist_slot_html' => '',
		'quick_slot_html'    => '',
		'reveal_mode'        => 'hover',
	),
	'badges'    => $badges,
	'collection'=> array(
		'density'        => 'default',
		'card_style'     => 'quiet',
		'mobile_compact' => false,
	),
	'price'     => tailwindscore_adapter_price_props( $product ),
);
```

- [ ] **Step 4: Run PHP lint**

Run: `php -l inc/woocommerce/adapters/product-card.php`
Expected: `No syntax errors detected`

- [ ] **Step 5: Commit**

```bash
git add inc/woocommerce/adapters/product-card.php
git commit -m "feat: add grouped archive product card props"
```

### Task 2: Compose Archive Subcomponents In Product Card Shell

**Files:**
- Modify: `template-parts/components/product-card.php`
- Create: `template-parts/components/archive/product-card-media.php`
- Create: `template-parts/components/archive/product-card-swatches.php`
- Create: `template-parts/components/archive/product-card-actions.php`
- Create: `template-parts/components/archive/product-card-badges.php`

- [ ] **Step 1: Write the failing shell target markup note**

Record the target shell anatomy that the implementation must produce:

```html
<article class="ts-product-card">
  <a class="ts-product-card__shell">
    <div class="ts-product-card__media-shell"></div>
    <div class="ts-product-card__body"></div>
  </a>
  <div class="ts-product-card__swatches-row"></div>
  <div class="ts-product-card__footer"></div>
</article>
```

- [ ] **Step 2: Inspect current shell**

Run: `Get-Content template-parts/components/product-card.php`
Expected: current shell lacks archive subcomponent composition and separate swatches row

- [ ] **Step 3: Implement archive badge component**

Use a narrow wrapper component:

```php
<?php
declare(strict_types=1);
defined( 'ABSPATH' ) || exit;

$badges = isset( $args['badges'] ) && is_array( $args['badges'] ) ? $args['badges'] : array();
if ( array() === $badges ) {
	return;
}
?>
<div class="ts-product-card__badges">
	<?php foreach ( $badges as $badge_args ) : ?>
		<?php if ( is_array( $badge_args ) ) { tailwindscore_component( 'badge', $badge_args ); } ?>
	<?php endforeach; ?>
</div>
```

- [ ] **Step 4: Implement archive media component**

Render stable SSR media markup with optional secondary image:

```php
<?php
declare(strict_types=1);
defined( 'ABSPATH' ) || exit;

$media = isset( $args['media'] ) && is_array( $args['media'] ) ? $args['media'] : array();
$primary = isset( $media['primary'] ) && is_array( $media['primary'] ) ? $media['primary'] : array();
$secondary = isset( $media['secondary'] ) && is_array( $media['secondary'] ) ? $media['secondary'] : array();
$ratio = isset( $media['aspect_ratio'] ) ? (string) $media['aspect_ratio'] : '4 / 5';
?>
<div class="ts-product-card__media" data-ts-archive-media style="--ts-product-card-ratio: <?php echo esc_attr( $ratio ); ?>">
	<div class="ts-product-card__media-frame">
		<img class="ts-product-card__image ts-product-card__image--primary" src="<?php echo esc_url( (string) ( $primary['url'] ?? '' ) ); ?>" alt="<?php echo esc_attr( (string) ( $primary['alt'] ?? '' ) ); ?>" />
		<?php if ( ! empty( $secondary['url'] ) ) : ?>
			<img class="ts-product-card__image ts-product-card__image--secondary" src="<?php echo esc_url( (string) $secondary['url'] ); ?>" alt="" aria-hidden="true" data-ts-secondary-image />
		<?php endif; ?>
	</div>
</div>
```

- [ ] **Step 5: Implement archive swatches component**

Keep it display-only:

```php
<?php
declare(strict_types=1);
defined( 'ABSPATH' ) || exit;

$swatches = isset( $args['swatches'] ) && is_array( $args['swatches'] ) ? $args['swatches'] : array();
$items = isset( $swatches['items'] ) && is_array( $swatches['items'] ) ? $swatches['items'] : array();
if ( array() === $items ) {
	return;
}
?>
<div class="ts-product-card__swatches-row" data-ts-archive-swatches>
	<?php foreach ( $items as $item ) : ?>
		<button type="button" class="ts-archive-swatch" data-ts-archive-swatch data-preview-image="<?php echo esc_attr( (string) ( $item['preview_image'] ?? '' ) ); ?>" aria-pressed="<?php echo ! empty( $item['selected'] ) ? 'true' : 'false'; ?>">
			<span class="ts-archive-swatch__chip"></span>
		</button>
	<?php endforeach; ?>
	<?php if ( ! empty( $swatches['more_count'] ) ) : ?>
		<span class="ts-product-card__swatches-more">+<?php echo esc_html( (string) $swatches['more_count'] ); ?></span>
	<?php endif; ?>
</div>
```

- [ ] **Step 6: Implement archive actions component**

```php
<?php
declare(strict_types=1);
defined( 'ABSPATH' ) || exit;

$actions = isset( $args['actions'] ) && is_array( $args['actions'] ) ? $args['actions'] : array();
$primary = isset( $actions['primary'] ) && is_array( $actions['primary'] ) ? $actions['primary'] : array();
?>
<div class="ts-product-card__actions" data-ts-archive-actions>
	<?php if ( ! empty( $primary['html'] ) ) : ?>
		<?php echo tailwindscore_kses_actions_slot( (string) $primary['html'] ); ?>
	<?php endif; ?>
	<?php if ( ! empty( $actions['wishlist_slot_html'] ) ) : ?>
		<?php echo tailwindscore_kses_actions_slot( (string) $actions['wishlist_slot_html'] ); ?>
	<?php endif; ?>
	<?php if ( ! empty( $actions['quick_slot_html'] ) ) : ?>
		<?php echo tailwindscore_kses_actions_slot( (string) $actions['quick_slot_html'] ); ?>
	<?php endif; ?>
</div>
```

- [ ] **Step 7: Recompose `template-parts/components/product-card.php` to use subcomponents**

Replace direct media and badge rendering with:

```php
tailwindscore_component( 'archive/product-card-media', array(
	'media'  => $args['media'],
	'badges' => $args['badges'],
) );
```

Insert swatches after the linked shell:

```php
tailwindscore_component( 'archive/product-card-swatches', array(
	'swatches' => $args['swatches'],
) );
```

Replace footer action slot with:

```php
tailwindscore_component( 'archive/product-card-actions', array(
	'actions' => $args['actions'],
) );
```

- [ ] **Step 8: Run PHP lint on all modified template files**

Run:

```bash
php -l template-parts/components/product-card.php
php -l template-parts/components/archive/product-card-media.php
php -l template-parts/components/archive/product-card-swatches.php
php -l template-parts/components/archive/product-card-actions.php
php -l template-parts/components/archive/product-card-badges.php
```

Expected: `No syntax errors detected` for each file

- [ ] **Step 9: Commit**

```bash
git add template-parts/components/product-card.php template-parts/components/archive
git commit -m "feat: compose archive product card subcomponents"
```

### Task 3: Add Archive Runtime Entry And Card Hover Module

**Files:**
- Create: `src/ts/modules/archive/index.ts`
- Create: `src/ts/modules/archive/product-card-hover.ts`
- Modify: `src/ts/modules/register.ts`

- [ ] **Step 1: Write the failing TypeScript shape note**

Document the mount contract:

```ts
export function mount(root: HTMLElement): void
export function mountProductCardHover(card: HTMLElement): () => void
```

- [ ] **Step 2: Confirm archive runtime is not registered yet**

Run: `Get-Content src/ts/modules/register.ts`
Expected: no archive module registration exists

- [ ] **Step 3: Implement `product-card-hover.ts`**

Use local-card ownership only:

```ts
function preload(url: string): void {
	if (!url) return;
	const img = new Image();
	img.decoding = 'async';
	img.src = url;
}

export function mountProductCardHover(card: HTMLElement): () => void {
	const secondary = card.querySelector<HTMLImageElement>('[data-ts-secondary-image]');
	if (!secondary) return () => {};
	preload(secondary.currentSrc || secondary.src);
	const activate = (): void => card.classList.add('is-media-active');
	const deactivate = (): void => card.classList.remove('is-media-active');
	card.addEventListener('pointerenter', activate);
	card.addEventListener('pointerleave', deactivate);
	card.addEventListener('focusin', activate);
	card.addEventListener('focusout', deactivate);
	return () => {
		card.removeEventListener('pointerenter', activate);
		card.removeEventListener('pointerleave', deactivate);
		card.removeEventListener('focusin', activate);
		card.removeEventListener('focusout', deactivate);
	};
}
```

- [ ] **Step 4: Implement `src/ts/modules/archive/index.ts`**

```ts
import { mountProductCardHover } from './product-card-hover';
import { mountArchiveSwatches } from './archive-swatches';
import { mountCollectionGrid } from './collection-grid';

export function mount(root: HTMLElement): void {
	const archiveRoots = root.matches('.woocommerce, .products') ? [root] : Array.from(root.querySelectorAll<HTMLElement>('.products'));
	archiveRoots.forEach((archiveRoot) => {
		const cleanups: Array<() => void> = [];
		cleanups.push(mountCollectionGrid(archiveRoot));
		archiveRoot.querySelectorAll<HTMLElement>('.ts-product-card').forEach((card) => {
			cleanups.push(mountProductCardHover(card));
			cleanups.push(mountArchiveSwatches(card));
		});
		window.addEventListener('beforeunload', () => cleanups.forEach((fn) => fn()), { once: true });
	});
}
```

- [ ] **Step 5: Register the archive module**

Add to `src/ts/modules/register.ts`:

```ts
import { mount as mountArchiveRuntime } from './archive';

registerModule('tailwindscore-archive-runtime', mountArchiveRuntime);
```

- [ ] **Step 6: Run TypeScript build**

Run: `npm run build`
Expected: build completes without TypeScript errors

- [ ] **Step 7: Commit**

```bash
git add src/ts/modules/archive src/ts/modules/register.ts
git commit -m "feat: add archive runtime entry and hover behavior"
```

### Task 4: Add Display-Only Archive Swatches Runtime

**Files:**
- Create: `src/ts/modules/archive/archive-swatches.ts`

- [ ] **Step 1: Write the failing behavior note**

Document the required behavior:

```ts
// Clicking a card swatch updates only the local card.
// If data-preview-image exists, swap the visible preview image source.
// No variation events are dispatched.
```

- [ ] **Step 2: Implement `archive-swatches.ts`**

```ts
function syncPressed(buttons: HTMLButtonElement[], active: HTMLButtonElement): void {
	buttons.forEach((button) => {
		const selected = button === active;
		button.setAttribute('aria-pressed', selected ? 'true' : 'false');
		button.classList.toggle('is-selected', selected);
	});
}

export function mountArchiveSwatches(card: HTMLElement): () => void {
	const root = card.querySelector<HTMLElement>('[data-ts-archive-swatches]');
	const primary = card.querySelector<HTMLImageElement>('.ts-product-card__image--primary');
	if (!root || !primary) return () => {};
	const buttons = Array.from(root.querySelectorAll<HTMLButtonElement>('[data-ts-archive-swatch]'));
	const onClick = (event: Event): void => {
		const button = (event.target as HTMLElement | null)?.closest<HTMLButtonElement>('[data-ts-archive-swatch]');
		if (!button) return;
		syncPressed(buttons, button);
		const preview = button.getAttribute('data-preview-image') ?? '';
		if (preview) {
			primary.src = preview;
		}
	};
	root.addEventListener('click', onClick);
	return () => root.removeEventListener('click', onClick);
}
```

- [ ] **Step 3: Run TypeScript build**

Run: `npm run build`
Expected: build completes without TypeScript errors

- [ ] **Step 4: Commit**

```bash
git add src/ts/modules/archive/archive-swatches.ts
git commit -m "feat: add archive display-only swatch runtime"
```

### Task 5: Add Collection Grid Runtime

**Files:**
- Create: `src/ts/modules/archive/collection-grid.ts`

- [ ] **Step 1: Write the failing behavior note**

```ts
// Apply pointer mode classes to archive root.
// Keep behavior presentational only.
```

- [ ] **Step 2: Implement `collection-grid.ts`**

```ts
export function mountCollectionGrid(root: HTMLElement): () => void {
	const finePointer = window.matchMedia('(pointer: fine)').matches;
	root.classList.toggle('is-pointer-fine', finePointer);
	root.classList.toggle('is-touch', !finePointer);
	return () => {
		root.classList.remove('is-pointer-fine');
		root.classList.remove('is-touch');
	};
}
```

- [ ] **Step 3: Run TypeScript build**

Run: `npm run build`
Expected: build completes without TypeScript errors

- [ ] **Step 4: Commit**

```bash
git add src/ts/modules/archive/collection-grid.ts
git commit -m "feat: add archive collection grid state module"
```

### Task 6: Build Archive CSS System

**Files:**
- Create: `src/css/components/product-archive/index.css`
- Create: `src/css/components/product-archive/product-card.css`
- Create: `src/css/components/product-archive/product-card-media.css`
- Create: `src/css/components/product-archive/product-card-swatches.css`
- Create: `src/css/components/product-archive/product-card-actions.css`
- Create: `src/css/components/product-archive/collection-grid.css`
- Modify: `src/css/components/commerce/index.css`

- [ ] **Step 1: Write the failing style checklist**

```text
- quieter card surface
- fixed media ratio
- secondary image fade
- stable swatch sizing
- restrained action reveal
- mobile fallback without hover dependence
```

- [ ] **Step 2: Implement archive CSS entry**

`src/css/components/product-archive/index.css`

```css
@import './product-card.css';
@import './product-card-media.css';
@import './product-card-swatches.css';
@import './product-card-actions.css';
@import './collection-grid.css';
```

- [ ] **Step 3: Implement base archive card CSS**

`src/css/components/product-archive/product-card.css`

```css
@layer components {
	.ts-product-card {
		position: relative;
		display: flex;
		flex-direction: column;
		height: 100%;
		background: var(--ts-color-surface);
		border: 1px solid color-mix(in oklch, var(--ts-color-border-subtle) 80%, transparent);
		border-radius: var(--ts-radius-lg);
		overflow: clip;
	}

	.ts-product-card__shell {
		display: flex;
		flex-direction: column;
		text-decoration: none;
		color: inherit;
	}

	.ts-product-card__body {
		display: flex;
		flex-direction: column;
		gap: var(--ts-space-2);
		padding: var(--ts-space-3) var(--ts-space-3) var(--ts-space-2);
	}

	.ts-product-card__footer {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: var(--ts-space-3);
		padding: var(--ts-space-3);
	}
}
```

- [ ] **Step 4: Implement media CSS**

`src/css/components/product-archive/product-card-media.css`

```css
@layer components {
	.ts-product-card__media {
		aspect-ratio: var(--ts-product-card-ratio, 4 / 5);
		background: var(--ts-color-surface-raised);
	}

	.ts-product-card__media-frame {
		position: relative;
		width: 100%;
		height: 100%;
	}

	.ts-product-card__image {
		position: absolute;
		inset: 0;
		width: 100%;
		height: 100%;
		object-fit: cover;
		transition: opacity var(--ts-duration-normal) var(--ts-ease-standard);
	}

	.ts-product-card__image--secondary {
		opacity: 0;
	}

	.ts-product-card.is-media-active .ts-product-card__image--secondary {
		opacity: 1;
	}

	@media (prefers-reduced-motion: reduce) {
		.ts-product-card__image {
			transition: none;
		}
	}
}
```

- [ ] **Step 5: Implement swatches and actions CSS**

`src/css/components/product-archive/product-card-swatches.css`

```css
@layer components {
	.ts-product-card__swatches-row {
		display: flex;
		align-items: center;
		gap: var(--ts-space-2);
		padding-inline: var(--ts-space-3);
		padding-bottom: var(--ts-space-2);
	}

	.ts-archive-swatch {
		inline-size: 1.125rem;
		block-size: 1.125rem;
		border-radius: 999px;
		flex: 0 0 auto;
	}
}
```

`src/css/components/product-archive/product-card-actions.css`

```css
@layer components {
	.ts-product-card__actions {
		display: flex;
		flex-wrap: wrap;
		gap: var(--ts-space-2);
		opacity: 1;
		transform: none;
	}

	@media (hover: hover) and (pointer: fine) {
		.ts-product-card__actions {
			opacity: 0;
			transform: translateY(0.25rem);
			transition:
				opacity var(--ts-duration-normal) var(--ts-ease-standard),
				transform var(--ts-duration-normal) var(--ts-ease-standard);
		}

		.ts-product-card:hover .ts-product-card__actions,
		.ts-product-card:focus-within .ts-product-card__actions {
			opacity: 1;
			transform: translateY(0);
		}
	}
}
```

- [ ] **Step 6: Implement collection grid CSS and import entry**

`src/css/components/product-archive/collection-grid.css`

```css
@layer components {
	.products.is-pointer-fine .ts-product-card {
		transition: border-color var(--ts-duration-fast) var(--ts-ease-standard);
	}

	.products.is-touch .ts-product-card__footer {
		padding-bottom: var(--ts-space-4);
	}
}
```

Update `src/css/components/commerce/index.css`:

```css
@import '../product-archive/index.css';
@import './price-block.css';
@import './badge.css';
@import './rating.css';
@import './trust-label.css';
```

- [ ] **Step 7: Run frontend build**

Run: `npm run build`
Expected: CSS bundles compile successfully

- [ ] **Step 8: Commit**

```bash
git add src/css/components/product-archive src/css/components/commerce/index.css
git commit -m "feat: add archive collection css system"
```

### Task 7: Write Archive Experience Documentation

**Files:**
- Create: `docs/archive-experience/collection-rules.md`
- Create: `docs/archive-experience/product-card-ux.md`
- Create: `docs/archive-experience/mobile-grid.md`
- Create: `docs/archive-experience/archive-swatches.md`

- [ ] **Step 1: Write `collection-rules.md`**

Include:

```md
# Collection Rules

- WooCommerce loop remains native.
- Archive runtime is progressive enhancement only.
- Collection rhythm is media-first and spacing-led.
- Archive cards are not mini PDPs.
```

- [ ] **Step 2: Write `product-card-ux.md`**

Include:

```md
# Product Card UX

- Secondary image hover is restrained and optional.
- Actions reveal must remain usable without JavaScript.
- Heavy shadows and noisy motion are disallowed.
```

- [ ] **Step 3: Write `mobile-grid.md`**

Include:

```md
# Mobile Grid

- Mobile does not depend on hover.
- Actions remain visible or easily reachable.
- Swatch row must not cause layout shift.
```

- [ ] **Step 4: Write `archive-swatches.md`**

Include:

```md
# Archive Swatches

- Archive swatches are display-only.
- They may update local media preview.
- They do not own variation forms.
- They do not mutate archive-side price or stock state.
```

- [ ] **Step 5: Run docs review commands**

Run:

```bash
Get-Content docs/archive-experience/collection-rules.md
Get-Content docs/archive-experience/product-card-ux.md
Get-Content docs/archive-experience/mobile-grid.md
Get-Content docs/archive-experience/archive-swatches.md
```

Expected: each file exists and states responsibilities and unsupported behaviors clearly

- [ ] **Step 6: Commit**

```bash
git add docs/archive-experience
git commit -m "docs: add archive experience foundation docs"
```

### Task 8: End-To-End Verification

**Files:**
- Verify: `inc/woocommerce/adapters/product-card.php`
- Verify: `template-parts/components/product-card.php`
- Verify: `template-parts/components/archive/*`
- Verify: `src/ts/modules/archive/*`
- Verify: `src/css/components/product-archive/*`
- Verify: `docs/archive-experience/*`

- [ ] **Step 1: Run PHP lint over archive PHP files**

Run:

```bash
php -l inc/woocommerce/adapters/product-card.php
php -l template-parts/components/product-card.php
Get-ChildItem template-parts/components/archive/*.php | ForEach-Object { php -l $_.FullName }
```

Expected: all files lint cleanly

- [ ] **Step 2: Run frontend build**

Run: `npm run build`
Expected: production bundle completes successfully

- [ ] **Step 3: Manual archive behavior check**

Check on a WooCommerce archive page:

```text
1. Product cards render without JavaScript.
2. Secondary image appears only when available.
3. Hover/focus swaps media without jarring motion.
4. Display-only swatches update only the local card.
5. Simple products show usable actions.
6. Variable products do not fake direct variation add to cart.
7. Mobile viewport keeps actions reachable and swatch row stable.
```

- [ ] **Step 4: Final commit**

```bash
git add inc/woocommerce/adapters/product-card.php template-parts/components/product-card.php template-parts/components/archive src/ts/modules/archive src/ts/modules/register.ts src/css/components/product-archive src/css/components/commerce/index.css docs/archive-experience
git commit -m "feat: add premium archive experience foundation"
```

---

## Self-Review

### Spec coverage

- Architecture: covered by Tasks 1-5
- SSR components: covered by Task 2
- Runtime boundaries: covered by Tasks 3-5
- CSS system: covered by Task 6
- Documentation: covered by Task 7
- Unsupported behavior boundaries: enforced in Tasks 2, 4, 7, and 8

### Placeholder scan

- No `TODO` or `TBD`
- Every task lists exact files
- Every code-changing task includes concrete snippets
- Every verification step includes concrete commands

### Type consistency

- Runtime module names match across tasks: `mountProductCardHover`, `mountArchiveSwatches`, `mountCollectionGrid`
- CSS and SSR attributes align on `data-ts-secondary-image`, `data-ts-archive-swatches`, and `data-ts-archive-swatch`
- Grouped prop names are consistent: `media`, `swatches`, `actions`, `badges`, `collection`
