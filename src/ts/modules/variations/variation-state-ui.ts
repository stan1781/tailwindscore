/**
 * Variation availability / selection shell — reacts to WC jQuery events only.
 */
import { resolveVariationsFormFromHost } from './form-root';

type JQueryish = {
	(e: Element): JQueryCollection;
};

type JQueryCollection = {
	on(events: string, handler: (...args: unknown[]) => void): JQueryCollection;
	off(events?: string): JQueryCollection;
};

function getJQuery(): JQueryish | undefined {
	return typeof window !== 'undefined' ? (window as unknown as { jQuery?: JQueryish }).jQuery : undefined;
}

function setUiPhase(root: HTMLElement, phase: 'idle' | 'found' | 'reset' | 'hidden'): void {
	root.dataset.tsVariationUi = phase;
	root.classList.remove('ts-variation-runtime--found', 'ts-variation-runtime--reset', 'ts-variation-runtime--hidden');
	if (phase === 'found') {
		root.classList.add('ts-variation-runtime--found');
	}
	if (phase === 'reset') {
		root.classList.add('ts-variation-runtime--reset');
	}
	if (phase === 'hidden') {
		root.classList.add('ts-variation-runtime--hidden');
	}
	const wrap = root.querySelector<HTMLElement>('.single_variation_wrap');
	if (wrap) {
		wrap.classList.toggle('ts-variation-wrap--active', phase === 'found');
		wrap.classList.toggle('ts-variation-wrap--inactive', phase === 'hidden' || phase === 'reset');
	}
}

export type VariationStateUiHandles = {
	off(): void;
};

export function mountVariationStateUi(root: HTMLElement): VariationStateUiHandles {
	const $ = getJQuery();
	const form = resolveVariationsFormFromHost(root);
	if (!$ || !form) {
		return { off() {} };
	}

	const $form = $(form);

	const onFound = (): void => {
		setUiPhase(root, 'found');
	};

	const onReset = (): void => {
		setUiPhase(root, 'reset');
	};

	const onHide = (): void => {
		setUiPhase(root, 'hidden');
	};

	setUiPhase(root, 'idle');

	$form.on('found_variation.ts-variation-state-ui', onFound);
	$form.on('reset_data.ts-variation-state-ui', onReset);
	$form.on('hide_variation.ts-variation-state-ui', onHide);

	return {
		off(): void {
			$form.off('.ts-variation-state-ui');
			setUiPhase(root, 'idle');
		},
	};
}
