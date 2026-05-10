const SCROLL_KEY = '__tailwindscoreGalleryScrollToIndex';

export type GalleryScrollApi = (index: number) => void;

export function exposeGalleryScrollToIndex(root: HTMLElement, scrollToIndex: GalleryScrollApi): void {
	(root as unknown as Record<string, GalleryScrollApi | undefined>)[SCROLL_KEY] = scrollToIndex;
}

export function clearGalleryScrollApi(root: HTMLElement): void {
	delete (root as unknown as Record<string, unknown>)[SCROLL_KEY];
}

export function scrollGalleryToIndex(galleryRoot: HTMLElement | null | undefined, index: number): boolean {
	if (!galleryRoot || index < 0) {
		return false;
	}
	const fn = (galleryRoot as unknown as Record<string, unknown>)[SCROLL_KEY];
	if (typeof fn === 'function') {
		(fn as GalleryScrollApi)(index);
		return true;
	}
	return false;
}
