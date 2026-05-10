export type FeedbackAnnouncementTone = 'polite' | 'assertive';

const LIVE_REGION_SELECTOR = '[data-feedback-live-region]';

function createLiveRegion(): HTMLElement {
	const host = document.createElement('div');
	host.className = 'ts-feedback-region ts-feedback-region--live';
	host.setAttribute('data-ts-module', 'feedback-runtime');
	host.setAttribute('data-feedback-role', 'live-region');
	host.setAttribute('aria-live', 'polite');
	host.setAttribute('role', 'status');

	const inner = document.createElement('span');
	inner.className = 'screen-reader-text';
	inner.setAttribute('data-feedback-live-region', '');

	host.append(inner);
	document.body.append(host);

	return host;
}

function resolveLiveRegionHost(): HTMLElement {
	return document.querySelector<HTMLElement>('[data-feedback-role="live-region"]') ?? createLiveRegion();
}

export function announceFeedback(message: string, tone: FeedbackAnnouncementTone = 'polite'): void {
	const text = message.trim();
	if (!text) {
		return;
	}

	const host = resolveLiveRegionHost();
	const region = host.querySelector<HTMLElement>(LIVE_REGION_SELECTOR);
	if (!region) {
		return;
	}

	host.setAttribute('aria-live', tone);
	host.setAttribute('role', tone === 'assertive' ? 'alert' : 'status');
	region.textContent = '';

	window.requestAnimationFrame(() => {
		region.textContent = text;
	});
}

export function mountLiveRegion(root: HTMLElement): void {
	if (root.dataset.feedbackEnhanced === '1') {
		return;
	}

	root.dataset.feedbackEnhanced = '1';
	root.setAttribute('aria-live', root.getAttribute('aria-live') || 'polite');
	root.setAttribute('role', root.getAttribute('role') || 'status');
}
