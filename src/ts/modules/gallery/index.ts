import { createGallerySlider } from './gallery-slider';
import { createGalleryThumbs } from './gallery-thumbs';
import { createGalleryLightbox } from './gallery-lightbox';
import { bindGalleryVariationSync } from './gallery-variation-sync';
import { exposeGalleryScrollToIndex, clearGalleryScrollApi } from './gallery-api';

function queryViewport(container: HTMLElement | null, selector: string): HTMLElement | null {
	if (!container) {
		return null;
	}
	return container.querySelector<HTMLElement>(selector);
}

function setThumbActive(thumbsRoot: HTMLElement | null, index: number): void {
	if (!thumbsRoot) {
		return;
	}
	const buttons = thumbsRoot.querySelectorAll<HTMLButtonElement>('.ts-gallery__thumb-btn');
	buttons.forEach((btn, i) => {
		const on = i === index;
		btn.classList.toggle('is-active', on);
		btn.setAttribute('aria-current', on ? 'true' : 'false');
	});
}

function bindThumbButtons(syncSelection: (index: number) => void, thumbsRoot: HTMLElement | null): () => void {
	if (!thumbsRoot) {
		return () => {};
	}
	const ac = new AbortController();
	thumbsRoot.addEventListener(
		'click',
		(e) => {
			const btn = (e.target as HTMLElement | null)?.closest?.('button.ts-gallery__thumb-btn');
			if (!btn) {
				return;
			}
			const li = btn.closest('li[data-thumb-index]');
			const raw = li?.getAttribute('data-thumb-index');
			const idx = raw !== null ? Number(raw) : NaN;
			if (Number.isFinite(idx) && idx >= 0) {
				syncSelection(idx);
			}
		},
		{ signal: ac.signal, passive: true },
	);
	return () => ac.abort();
}

export function mount(root: HTMLElement): void {
	const mainViewport = queryViewport(root, '.ts-gallery__main-viewport');
	if (!mainViewport) {
		return;
	}

	const main = createGallerySlider(mainViewport);

	const thumbsViewport = queryViewport(root, '.ts-gallery__thumbs-viewport');
	const thumbs = thumbsViewport ? createGalleryThumbs(thumbsViewport) : null;

	const thumbsShell = root.querySelector<HTMLElement>('.ts-gallery__thumbs-shell');

	let syncing = false;

	const syncSelection = (index: number): void => {
		syncing = true;
		main.scrollTo(index);
		if (thumbs && thumbs.selectedScrollSnap() !== index) {
			thumbs.scrollTo(index);
		}
		setThumbActive(thumbsShell, index);
		syncing = false;
	};

	main.on('select', () => {
		if (syncing) {
			return;
		}
		const i = main.selectedScrollSnap();
		if (thumbs && thumbs.selectedScrollSnap() !== i) {
			syncing = true;
			thumbs.scrollTo(i);
			syncing = false;
		}
		setThumbActive(thumbsShell, i);
	});

	if (thumbs) {
		thumbs.on('select', () => {
			if (syncing) {
				return;
			}
			const i = thumbs.selectedScrollSnap();
			if (main.selectedScrollSnap() !== i) {
				syncing = true;
				main.scrollTo(i);
				syncing = false;
			}
			setThumbActive(thumbsShell, i);
		});
	}

	const offThumbClick = bindThumbButtons(syncSelection, thumbsShell);

	const lightbox = createGalleryLightbox(root);
	lightbox.init();

	const variation = bindGalleryVariationSync(root, syncSelection);

	exposeGalleryScrollToIndex(root, syncSelection);

	const onPrev = (): void => {
		main.scrollPrev();
	};
	const onNext = (): void => {
		main.scrollNext();
	};

	root.querySelector<HTMLButtonElement>('[data-gallery-nav="prev"]')?.addEventListener('click', onPrev);
	root.querySelector<HTMLButtonElement>('[data-gallery-nav="next"]')?.addEventListener('click', onNext);

	setThumbActive(thumbsShell, 0);

	const cleanup = (): void => {
		clearGalleryScrollApi(root);
		offThumbClick();
		variation.off();
		lightbox.destroy();
		main.destroy();
		thumbs?.destroy();
	};

	window.addEventListener(
		'beforeunload',
		() => {
			cleanup();
		},
		{ once: true },
	);
}
