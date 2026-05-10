# Variation ↔ gallery sync

## Goal

Mirror WooCommerce’s expectations for DOM and events **without** custom variation matching logic. Variation rules remain in `woocommerce/assets/js/frontend/add-to-cart-variation.js`.

## DOM contracts

The gallery keeps:

- `.product .images` — gallery root (same element as `woocommerce-product-gallery` classes).
- First `.woocommerce-product-gallery__image` contains `.wp-post-image` and `<a>` — targets for `wc_variations_image_update`.
- `ul.flex-control-nav` with `li > img` — first thumbnail img is updated when WC swaps variation imagery onto slide 0.

Thumb interaction uses `<button><img>` but WC continues to target `li img` for `flexslider-click` and attribute sync.

## Events listened to

| Event | Source | TailwindScore behaviour |
| --- | --- | --- |
| `found_variation` | `.variations_form` | If `variation.image_id` matches a `[data-attachment-id]` slide, Embla scrolls to that index (main + thumbs). |
| `reset_image` | `.variations_form` | Scroll to index `0` after WC resets the main image. |
| `woocommerce_gallery_reset_slide_position` | `.images` | Scroll to index `0` (fires when WC detects image id change via `wc_maybe_trigger_slide_position_reset`). |
| `flexslider-click` | `img` inside `.flex-control-nav` | WC triggers this when a gallery thumbnail matches the variation image — sync Embla index. |

## Not handled here

- **`hide_variation`** — WooCommerce does not reset gallery images solely from this event; no scroll action is applied (avoid fighting transient UI states).

## Testing checklist

1. Simple product — swipe / thumbs / lightbox.
2. Variable product — pick attributes so `found_variation` fires; image swaps and carousel index align when the variation image exists as a gallery attachment.
3. Variation with image **outside** gallery — WC replaces first slide pixels only; carousel may remain on a non-first slide until reset — same as classic WC gallery without Flexslider.
