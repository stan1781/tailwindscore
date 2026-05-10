import EmblaCarousel, { type EmblaCarouselType } from 'embla-carousel';

function thumbsAxis(): 'x' | 'y' {
	return window.matchMedia('(min-width: 768px)').matches ? 'y' : 'x';
}

/**
 * Thumbnail strip — synced via callers; axis switches desktop (vertical) vs mobile (horizontal).
 */
export function createGalleryThumbs(viewport: HTMLElement): EmblaCarouselType {
	return EmblaCarousel(viewport, {
		align: 'start',
		axis: thumbsAxis(),
		containScroll: 'trimSnaps',
		dragFree: false,
	});
}

export function reinitGalleryThumbs(viewport: HTMLElement, prev?: EmblaCarouselType): EmblaCarouselType {
	prev?.destroy();
	return createGalleryThumbs(viewport);
}
