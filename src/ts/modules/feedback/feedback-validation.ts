import { announceFeedback } from './feedback-live-region';

type ValidationOptions = {
	title?: string;
	announce?: boolean;
};

function resolveValidationSurface(scope: HTMLElement): HTMLElement | null {
	return scope.querySelector<HTMLElement>('[data-feedback-validation]');
}

function setTargetInvalidState(target: HTMLElement, invalid: boolean): void {
	target.classList.toggle('is-feedback-invalid', invalid);
}

export function clearFeedbackValidation(scope: HTMLElement): void {
	const surface = resolveValidationSurface(scope);
	if (surface) {
		surface.hidden = true;
		const message = surface.querySelector<HTMLElement>('[data-feedback-validation-message]');
		if (message) {
			message.textContent = '';
		}
	}

	scope.querySelectorAll<HTMLElement>('.is-feedback-invalid').forEach((node) => {
		node.classList.remove('is-feedback-invalid');
	});
}

export function setFeedbackValidation(scope: HTMLElement, message: string, options: ValidationOptions = {}): void {
	const text = message.trim();
	if (!text) {
		clearFeedbackValidation(scope);
		return;
	}

	const surface = resolveValidationSurface(scope);
	if (surface) {
		const title = surface.querySelector<HTMLElement>('.ts-feedback-validation__title');
		const body = surface.querySelector<HTMLElement>('[data-feedback-validation-message]');

		if (title && options.title) {
			title.textContent = options.title;
		}
		if (body) {
			body.textContent = text;
		}

		surface.hidden = false;
	}

	if (options.announce !== false) {
		announceFeedback(text, 'assertive');
	}
}

export function mountValidation(root: HTMLElement): void {
	if (root.dataset.feedbackValidationMounted === '1') {
		return;
	}

	root.dataset.feedbackValidationMounted = '1';

	root.addEventListener(
		'invalid',
		(event) => {
			const target = event.target;
			if (!(target instanceof HTMLElement)) {
				return;
			}

			setTargetInvalidState(target, true);

			if (
				target instanceof HTMLInputElement ||
				target instanceof HTMLSelectElement ||
				target instanceof HTMLTextAreaElement
			) {
				setFeedbackValidation(root, target.validationMessage, {
					title: root.dataset.feedbackValidationTitle ?? '',
				});
			}
		},
		true,
	);

	root.addEventListener('input', (event) => {
		const target = event.target;
		if (!(target instanceof HTMLElement)) {
			return;
		}

		if (
			target instanceof HTMLInputElement ||
			target instanceof HTMLSelectElement ||
			target instanceof HTMLTextAreaElement
		) {
			setTargetInvalidState(target, !target.checkValidity());
			if (target.checkValidity()) {
				clearFeedbackValidation(root);
			}
		}
	});
}
