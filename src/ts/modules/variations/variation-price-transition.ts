/**
 * Cosmetic transitions when WC replaces variation price / availability HTML.
 * Does not compute prices — observes DOM updates only.
 */
import { resolveVariationsFormFromHost } from './form-root';

function raf2(fn: () => void): void {
	requestAnimationFrame(() => {
		requestAnimationFrame(fn);
	});
}

export type VariationPriceTransitionHandles = {
	off(): void;
};

export function mountVariationPriceTransition(root: HTMLElement): VariationPriceTransitionHandles {
	const form = resolveVariationsFormFromHost(root);
	if (!form) {
		return { off() {} };
	}

	const wrap = form.querySelector<HTMLElement>('.single_variation_wrap');
	if (!wrap) {
		return { off() {} };
	}

	let rafId = 0;
	const markUpdating = (): void => {
		cancelAnimationFrame(rafId);
		wrap.classList.add('is-ts-variation-price-updating');
		rafId = requestAnimationFrame(() => {
			raf2(() => {
				wrap.classList.remove('is-ts-variation-price-updating');
			});
		});
	};

	const observer = new MutationObserver((records) => {
		for (const r of records) {
			if (r.type === 'childList') {
				markUpdating();
				return;
			}
		}
	});

	const priceEl = wrap.querySelector<HTMLElement>('.woocommerce-variation-price');
	const availEl = wrap.querySelector<HTMLElement>('.woocommerce-variation-availability');
	for (const el of [priceEl, availEl]) {
		if (el) {
			observer.observe(el, { childList: true, subtree: true, characterData: true });
		}
	}

	return {
		off(): void {
			cancelAnimationFrame(rafId);
			observer.disconnect();
		},
	};
}
