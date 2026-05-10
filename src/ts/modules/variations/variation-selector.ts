/**
 * Attribute UI — focus, keyboard, row state; no variation matching / pricing logic.
 */
import { delegate } from '../../utils/events';

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

import { resolveVariationsFormFromHost } from './form-root';

function syncRowStates(form: HTMLFormElement): void {
	const rows = form.querySelectorAll<HTMLTableRowElement>('table.variations tr');
	rows.forEach((row) => {
		const select = row.querySelector<HTMLSelectElement>('select');
		if (!select) {
			return;
		}
		const hasValue = select.value !== '';
		row.classList.toggle('ts-variation-row--selected', hasValue);
		row.classList.toggle('ts-variation-row--empty', !hasValue);
	});
}

function bindWcVariationDomRefresh(form: HTMLFormElement, onRefresh: () => void): () => void {
	const $ = getJQuery();
	if (!$) {
		return () => {};
	}
	const $form = $(form);
	$form.on('update_variation_values.ts-variation-selector', onRefresh);
	$form.on('check_variations.ts-variation-selector', onRefresh);
	return () => {
		$form.off('.ts-variation-selector');
	};
}

export type VariationSelectorHandles = {
	off(): void;
};

export function mountVariationSelector(root: HTMLElement): VariationSelectorHandles {
	const form = resolveVariationsFormFromHost(root);
	if (!form) {
		return { off() {} };
	}

	const refresh = (): void => syncRowStates(form);
	refresh();

	const unDelegates: Array<() => void> = [];

	unDelegates.push(
		delegate(form, 'change', 'table.variations select', () => {
			refresh();
		}),
	);

	unDelegates.push(
		delegate(form, 'keydown', 'table.variations select', (event) => {
			if (event.key === 'Escape') {
				(event.target as HTMLSelectElement).blur();
			}
		}),
	);

	unDelegates.push(bindWcVariationDomRefresh(form, refresh));

	return {
		off(): void {
			unDelegates.forEach((u) => u());
		},
	};
}
