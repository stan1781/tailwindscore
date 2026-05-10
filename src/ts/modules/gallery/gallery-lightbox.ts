import PhotoSwipeLightbox from 'photoswipe/lightbox';
import 'photoswipe/style.css';

export type GalleryLightboxApi = {
	init(): void;
	destroy(): void;
};

/**
 * PhotoSwipe 5 lightbox — parses anchors inside the gallery root.
 */
export function createGalleryLightbox(root: HTMLElement): GalleryLightboxApi {
	const lightbox = new PhotoSwipeLightbox({
		gallery: root,
		children: 'a.ts-gallery__lightbox-link',
		pswpModule: () => import('photoswipe'),
	});

	return {
		init(): void {
			lightbox.init();
		},
		destroy(): void {
			lightbox.destroy();
		},
	};
}
