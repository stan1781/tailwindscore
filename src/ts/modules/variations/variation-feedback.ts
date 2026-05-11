import { clearFeedbackValidation, setFeedbackValidation } from '../feedback';
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

function feedbackHost(root: HTMLElement): HTMLElement | null {
	return root.querySelector<HTMLElement>('[data-ts-variation-feedback]');
}

function availabilityBlock(root: HTMLElement): HTMLElement | null {
	return root.querySelector<HTMLElement>('.woocommerce-variation-availability');
}

function stockTone(stock: HTMLElement): 'success' | 'info' | 'error' {
	if (stock.classList.contains('out-of-stock') || stock.classList.contains('unavailable')) {
		return 'error';
	}
	if (stock.classList.contains('available-on-backorder') || stock.classList.contains('onbackorder')) {
		return 'info';
	}
	return 'success';
}

function syncAvailabilitySurface(root: HTMLElement): void {
	const availability = availabilityBlock(root);
	const stock = availability?.querySelector<HTMLElement>('.stock');
	if (!availability || !stock) {
		return;
	}

	availability.classList.remove('ts-feedback-notice--success', 'ts-feedback-notice--info', 'ts-feedback-notice--error');
	availability.classList.add('ts-feedback-notice', 'ts-variation-availability', `ts-feedback-notice--${stockTone(stock)}`);
}

export type VariationFeedbackHandles = {
	off(): void;
};

export function mountVariationFeedback(root: HTMLElement): VariationFeedbackHandles {
	const $ = getJQuery();
	const form = resolveVariationsFormFromHost(root);
	if (!$ || !form) {
		return { off() {} };
	}

	const host = feedbackHost(root);
	if (!host) {
		return { off() {} };
	}

	const $form = $(form);

	const onFound = (_event: unknown, variation?: Record<string, unknown>): void => {
		window.requestAnimationFrame(() => {
			syncAvailabilitySurface(root);
		});

		const isInStock = variation?.is_in_stock;
		const isPurchasable = variation?.is_purchasable;

		if (isInStock === false || isPurchasable === false) {
			setFeedbackValidation(
				host,
				host.dataset.feedbackUnavailableMessage ?? 'This option is currently unavailable. Choose another combination.',
				{
				title: host.dataset.feedbackValidationTitle ?? '',
				announce: false,
				},
			);
			return;
		}

		clearFeedbackValidation(host);
	};

	const onReset = (): void => {
		clearFeedbackValidation(host);
	};

	const onHide = (): void => {
		setFeedbackValidation(host, host.dataset.feedbackHiddenMessage ?? 'This combination is currently unavailable. Choose another option.', {
			title: host.dataset.feedbackValidationTitle ?? '',
			announce: false,
		});
		window.requestAnimationFrame(() => {
			syncAvailabilitySurface(root);
		});
	};

	window.requestAnimationFrame(() => {
		syncAvailabilitySurface(root);
	});

	$form.on('found_variation.ts-variation-feedback', onFound as never);
	$form.on('reset_data.ts-variation-feedback', onReset);
	$form.on('hide_variation.ts-variation-feedback', onHide);

	return {
		off(): void {
			$form.off('.ts-variation-feedback');
			clearFeedbackValidation(host);
		},
	};
}
