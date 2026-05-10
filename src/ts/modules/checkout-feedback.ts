import { clearFeedbackValidation, setFeedbackBusyState, setFeedbackValidation } from './feedback';

type JQueryish = {
	(target: string | Element | Document): JQueryCollection;
};

type JQueryCollection = {
	on(events: string, handler: (...args: unknown[]) => void): JQueryCollection;
	off(events?: string): JQueryCollection;
};

function getJQuery(): JQueryish | undefined {
	return typeof window !== 'undefined' ? (window as unknown as { jQuery?: JQueryish }).jQuery : undefined;
}

function cleanLabel(text: string): string {
	return text.replace(/\s+/g, ' ').replace(/\*$/, '').trim();
}

function resolveControl(row: HTMLElement): HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement | null {
	return row.querySelector<HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement>(
		'input:not([type="hidden"]):not([type="radio"]):not([type="checkbox"]), select, textarea, input[type="checkbox"], input[type="radio"]',
	);
}

function resolveMessageNode(row: HTMLElement): HTMLElement {
	let node = row.querySelector<HTMLElement>('[data-feedback-field-message]');
	if (node) {
		return node;
	}

	node = document.createElement('p');
	node.className = 'ts-feedback-field-message';
	node.setAttribute('data-feedback-field-message', '');
	node.hidden = true;
	row.append(node);
	return node;
}

function resolveFieldKey(row: HTMLElement, control: HTMLElement | null): string {
	if (control instanceof HTMLElement && control.dataset.feedbackField) {
		return control.dataset.feedbackField;
	}

	if (control instanceof HTMLInputElement || control instanceof HTMLSelectElement || control instanceof HTMLTextAreaElement) {
		return control.name.replace(/\[\]$/, '').trim();
	}

	return row.id.replace(/^billing_|^shipping_/, '');
}

function resolveFieldLabel(row: HTMLElement, control: HTMLElement | null): string {
	if (control instanceof HTMLElement && control.dataset.feedbackLabel) {
		return cleanLabel(control.dataset.feedbackLabel);
	}

	const label = row.querySelector('label');
	if (label?.textContent) {
		return cleanLabel(label.textContent);
	}

	if (control instanceof HTMLInputElement || control instanceof HTMLSelectElement || control instanceof HTMLTextAreaElement) {
		return cleanLabel(control.getAttribute('aria-label') ?? control.name);
	}

	return 'this field';
}

function isSelectLike(control: HTMLElement | null): boolean {
	return control instanceof HTMLSelectElement;
}

function requiredMessage(key: string, label: string, control: HTMLElement | null): string {
	const normalized = key.toLowerCase();

	if (normalized.includes('email')) {
		return 'Enter your email address.';
	}
	if (normalized.includes('phone')) {
		return 'Enter your phone number.';
	}
	if (normalized.includes('first_name')) {
		return 'Enter your first name.';
	}
	if (normalized.includes('last_name')) {
		return 'Enter your last name.';
	}
	if (normalized.includes('address_1')) {
		return 'Enter your street address.';
	}
	if (normalized.includes('address_2')) {
		return 'Enter your apartment, suite, or unit if needed.';
	}
	if (normalized.includes('city')) {
		return 'Enter your city.';
	}
	if (normalized.includes('state')) {
		return 'Select your state or region.';
	}
	if (normalized.includes('country')) {
		return 'Select your country or region.';
	}
	if (normalized.includes('postcode') || normalized.includes('postal')) {
		return 'Enter your postal code.';
	}
	if (normalized.includes('terms')) {
		return 'Confirm the required checkbox to continue.';
	}

	return `${isSelectLike(control) ? 'Select' : 'Enter'} ${label.toLowerCase()}.`;
}

function invalidMessage(key: string, label: string): string {
	const normalized = key.toLowerCase();

	if (normalized.includes('email')) {
		return 'Enter a valid email address.';
	}
	if (normalized.includes('phone')) {
		return 'Enter a valid phone number.';
	}
	if (normalized.includes('postcode') || normalized.includes('postal')) {
		return 'Enter a valid postal code.';
	}

	return `Review ${label.toLowerCase()}.`;
}

function setRowMessage(row: HTMLElement, message: string): void {
	const node = resolveMessageNode(row);
	node.textContent = message;
	node.hidden = message.trim() === '';
	row.classList.toggle('is-feedback-invalid', message.trim() !== '');
}

function clearRowMessage(row: HTMLElement): void {
	setRowMessage(row, '');
}

function syncRow(row: HTMLElement): boolean {
	const control = resolveControl(row);
	if (!control) {
		return false;
	}

	const key = resolveFieldKey(row, control);
	const label = resolveFieldLabel(row, control);
	const rowMarkedInvalid = row.classList.contains('woocommerce-invalid');
	const nativeInvalid =
		control instanceof HTMLInputElement || control instanceof HTMLSelectElement || control instanceof HTMLTextAreaElement
			? !control.checkValidity()
			: false;
	const invalid = rowMarkedInvalid || nativeInvalid;

	if (!invalid) {
		clearRowMessage(row);
		return false;
	}

	let message = '';
	if ((control instanceof HTMLInputElement || control instanceof HTMLSelectElement || control instanceof HTMLTextAreaElement) && control.validity.valueMissing) {
		message = requiredMessage(key, label, control);
	} else if (
		(control instanceof HTMLInputElement || control instanceof HTMLSelectElement || control instanceof HTMLTextAreaElement) &&
		control.validationMessage.trim()
	) {
		message = invalidMessage(key, label);
	} else {
		message = invalidMessage(key, label);
	}

	setRowMessage(row, message);
	return true;
}

function syncAllRows(scope: HTMLElement): boolean {
	const rows = Array.from(scope.querySelectorAll<HTMLElement>('.form-row'));
	return rows.map((row) => syncRow(row)).some(Boolean);
}

export function mount(root: HTMLElement): void {
	if (root.dataset.checkoutFeedbackMounted === '1') {
		return;
	}

	root.dataset.checkoutFeedbackMounted = '1';

	const form = root.querySelector<HTMLFormElement>('form.checkout');
	if (!form) {
		return;
	}

	const syncSummary = (): void => {
		const hasInvalid = syncAllRows(form);
		if (hasInvalid) {
			setFeedbackValidation(root, root.dataset.feedbackValidationSummary ?? 'Please review the highlighted checkout details.', {
				title: root.dataset.feedbackValidationTitle ?? '',
				announce: false,
			});
		} else {
			clearFeedbackValidation(root);
		}
	};

	form.addEventListener(
		'invalid',
		() => {
			window.requestAnimationFrame(syncSummary);
		},
		true,
	);

	form.addEventListener('input', () => {
		syncSummary();
	});

	form.addEventListener('change', () => {
		syncSummary();
	});

	form.addEventListener('submit', () => {
		window.requestAnimationFrame(syncSummary);
	});

	const observer = new MutationObserver(() => {
		syncSummary();
	});

	observer.observe(form, {
		subtree: true,
		attributes: true,
		attributeFilter: ['class', 'aria-invalid'],
	});

	const $ = getJQuery();
	if ($) {
		const $body = $(document.body);
		$body.on('update_checkout.ts-checkout-feedback', () => {
			setFeedbackBusyState(root, true, {
				message: root.dataset.feedbackLoadingMessage ?? 'Updating checkout',
				announce: false,
			});
		});
		$body.on('updated_checkout.ts-checkout-feedback', () => {
			setFeedbackBusyState(root, false);
			syncSummary();
		});
		$body.on('checkout_error.ts-checkout-feedback', () => {
			setFeedbackBusyState(root, false);
			syncSummary();
		});
	}
}
