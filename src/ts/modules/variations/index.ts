import { bindVariationJqBridge } from './jq-bridge';
import { mountVariationSelector } from './variation-selector';
import { mountVariationStateUi } from './variation-state-ui';
import { mountVariationFeedback } from './variation-feedback';
import { mountVariationPriceTransition } from './variation-price-transition';
import { mountVariationImageSync } from './variation-image-sync';
import { mountSwatchGroups, mountSwatchImagePresentation } from '../swatches';

/**
 * Variable product UX runtime — WC variation core + forms unchanged.
 */
export function mount(root: HTMLElement): void {
	if (!root.querySelector('form.variations_form')) {
		return;
	}

	const cleanups: Array<() => void> = [];

	const jq = bindVariationJqBridge(root);
	cleanups.push(() => jq.off());

	const sel = mountVariationSelector(root);
	cleanups.push(() => sel.off());

	const swatches = mountSwatchGroups(root);
	cleanups.push(swatches);

	const presentation = mountSwatchImagePresentation(root);
	cleanups.push(presentation);

	const ui = mountVariationStateUi(root);
	cleanups.push(() => ui.off());

	const feedback = mountVariationFeedback(root);
	cleanups.push(() => feedback.off());

	const price = mountVariationPriceTransition(root);
	cleanups.push(() => price.off());

	const img = mountVariationImageSync(root);
	cleanups.push(() => img.off());

	window.addEventListener(
		'beforeunload',
		() => {
			cleanups.forEach((fn) => fn());
		},
		{ once: true },
	);
}
