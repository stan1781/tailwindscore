import { announceFeedback } from './feedback-live-region';

type BusyOptions = {
	message?: string;
	announce?: boolean;
};

function resolveLoadingNode(scope: HTMLElement): HTMLElement | null {
	return scope.querySelector<HTMLElement>('[data-feedback-loading]');
}

export function setFeedbackBusyState(scope: HTMLElement, busy: boolean, options: BusyOptions = {}): void {
	scope.dataset.feedbackBusy = busy ? 'true' : 'false';

	if (busy) {
		scope.setAttribute('aria-busy', 'true');
	} else {
		scope.removeAttribute('aria-busy');
	}

	const loading = resolveLoadingNode(scope);
	if (loading) {
		const messageNode = loading.querySelector<HTMLElement>('[data-feedback-loading-message]');
		if (messageNode && options.message) {
			messageNode.textContent = options.message;
		}
		loading.hidden = !busy;
	}

	if (busy && options.announce !== false && options.message) {
		announceFeedback(options.message, 'polite');
	}
}

export function mountLoading(root: HTMLElement): void {
	if (root.dataset.feedbackLoadingMounted === '1') {
		return;
	}

	root.dataset.feedbackLoadingMounted = '1';

	if (root.getAttribute('aria-busy') === 'true') {
		setFeedbackBusyState(root, true);
	}
}
