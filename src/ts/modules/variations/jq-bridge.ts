/**
 * jQuery → CustomEvent bridge (WooCommerce remains source of truth).
 */
import { emit } from '../../utils/events';
import { resolveVariationsFormFromHost } from './form-root';

type JQueryish = {
	(e: string | Element | Document): JQueryCollection;
};

type JQueryCollection = {
	on(events: string, handler: (...args: unknown[]) => void): JQueryCollection;
	off(events?: string): JQueryCollection;
	length: number;
	[index: number]: HTMLElement;
};

function getJQuery(): JQueryish | undefined {
	return typeof window !== 'undefined' ? (window as unknown as { jQuery?: JQueryish }).jQuery : undefined;
}

function productIdFromForm(form: HTMLElement): number {
	const fromData = form.getAttribute('data-product_id');
	if (fromData) {
		const n = parseInt(fromData, 10);
		if (Number.isFinite(n)) {
			return n;
		}
	}
	const jq = (window as unknown as { jQuery?: (sel: unknown) => { data: (key: string) => unknown } }).jQuery;
	if (jq) {
		const id = jq(form).data('product_id');
		if (typeof id === 'number' && Number.isFinite(id)) {
			return id;
		}
		if (typeof id === 'string') {
			const n = parseInt(id, 10);
			if (Number.isFinite(n)) {
				return n;
			}
		}
	}
	return 0;
}

export type JqBridgeHandles = {
	off(): void;
};

export function bindVariationJqBridge(root: HTMLElement): JqBridgeHandles {
	const $ = getJQuery();
	const form = resolveVariationsFormFromHost(root);
	if (!$ || !form) {
		return { off() {} };
	}

	const productId = productIdFromForm(form);
	const $form = $(form);

	const emitChange = (variation: Record<string, unknown> | null): void => {
		emit(document, 'ts:variation:changed', {
			variation,
			productId,
			form,
		});
	};

	const onFound = (_e: unknown, variation?: Record<string, unknown>): void => {
		emitChange(variation ?? null);
	};

	const onReset = (): void => {
		emitChange(null);
	};

	const onHide = (): void => {
		emitChange(null);
	};

	$form.on('found_variation.ts-variation-runtime', onFound as never);
	$form.on('reset_data.ts-variation-runtime', onReset);
	$form.on('hide_variation.ts-variation-runtime', onHide);

	return {
		off(): void {
			$form.off('.ts-variation-runtime');
		},
	};
}
