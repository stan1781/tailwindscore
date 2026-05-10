export type CartSurfaceResponse = {
	drawer_html: string;
	badge_html: string;
	subtotal_html: string;
	count: number;
};

export async function requestCartSurface(endpoint: string, init?: RequestInit): Promise<CartSurfaceResponse> {
	const response = await fetch(endpoint, {
		credentials: 'same-origin',
		headers: {
			Accept: 'application/json',
			...(init?.headers ?? {}),
		},
		...init,
	});

	if (!response.ok) {
		throw new Error(`Cart surface request failed: ${response.status}`);
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
