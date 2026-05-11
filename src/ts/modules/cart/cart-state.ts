export type CartSurfaceResponse = {
	drawer_html: string;
	badge_html: string;
	subtotal_html: string;
	count: number;
};

type TailwindScoreConfig = {
	restNonce?: string;
};

function resolveRestNonce(): string {
	if (typeof window === 'undefined') {
		return '';
	}

	const config = (window as typeof window & { tailwindscoreConfig?: TailwindScoreConfig }).tailwindscoreConfig;
	return typeof config?.restNonce === 'string' ? config.restNonce : '';
}

export class CartSurfaceRequestError extends Error {
	status: number;
	responseMessage: string;

	constructor(status: number, responseMessage: string) {
		super(responseMessage || `Cart surface request failed: ${status}`);
		this.name = 'CartSurfaceRequestError';
		this.status = status;
		this.responseMessage = responseMessage;
	}
}

export async function requestCartSurface(endpoint: string, init?: RequestInit): Promise<CartSurfaceResponse> {
	const restNonce = resolveRestNonce();

	const response = await fetch(endpoint, {
		credentials: 'same-origin',
		cache: 'no-store',
		headers: {
			Accept: 'application/json',
			...(restNonce ? { 'X-WP-Nonce': restNonce } : {}),
			...(init?.headers ?? {}),
		},
		...init,
	});

	if (!response.ok) {
		let responseMessage = '';

		try {
			const payload = (await response.json()) as { message?: unknown };
			if (typeof payload.message === 'string') {
				responseMessage = payload.message;
			} else if (Array.isArray(payload.message)) {
				responseMessage = payload.message
					.map((entry) => {
						if (typeof entry === 'string') {
							return entry;
						}
						if (entry && typeof entry === 'object' && 'notice' in entry && typeof entry.notice === 'string') {
							return entry.notice;
						}
						return '';
					})
					.filter(Boolean)
					.join(' ');
			}
		} catch {
			responseMessage = '';
		}

		throw new CartSurfaceRequestError(response.status, responseMessage);
	}

	return (await response.json()) as CartSurfaceResponse;
}

export function applyCartDrawerHtml(drawer: HTMLElement, drawerHtml: string): void {
	const doc = new DOMParser().parseFromString(drawerHtml, 'text/html');
	const nextBody = doc.querySelector<HTMLElement>('[data-cart-surface-root]');
	const currentBody = drawer.querySelector<HTMLElement>('[data-cart-surface-root]');
	if (currentBody && nextBody) {
		currentBody.replaceWith(nextBody);
	}
}

export function applyCartBadgeHtml(badgeHtml: string): void {
	document.querySelectorAll<HTMLElement>('[data-cart-count]').forEach((currentBadge) => {
		const doc = new DOMParser().parseFromString(badgeHtml, 'text/html');
		const nextBadge = doc.querySelector<HTMLElement>('[data-cart-count]');
		if (nextBadge) {
			currentBadge.replaceWith(nextBadge);
		}
	});
}

export function applyCartFragments(drawer: HTMLElement, payload: CartSurfaceResponse): void {
	applyCartDrawerHtml(drawer, payload.drawer_html);
	applyCartBadgeHtml(payload.badge_html);
}
