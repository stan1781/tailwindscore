export function queryAll<T extends HTMLElement = HTMLElement>(
	root: Document | HTMLElement,
	selector: string,
): T[] {
	return Array.from(root.querySelectorAll<T>(selector));
}

export function closestElement<T extends HTMLElement = HTMLElement>(
	start: Element | null,
	selector: string,
): T | null {
	const el = start?.closest<T>(selector);
	return el ?? null;
}
