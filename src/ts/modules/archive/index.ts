import { mountArchiveSwatches } from './archive-swatches';
import { mountCollectionGrid } from './collection-grid';
import { mountProductCardHover } from './product-card-hover';

const initializedGrids = new WeakMap<HTMLElement, () => void>();

export function mount(root: HTMLElement): void {
	const card = root.matches('.ts-product-card') ? root : root.querySelector<HTMLElement>('.ts-product-card');
	if (!card) {
		return;
	}

	const grid = card.closest<HTMLElement>('.products');
	if (grid && !initializedGrids.has(grid)) {
		initializedGrids.set(grid, mountCollectionGrid(grid));
	}

	const cleanups: Array<() => void> = [];
	cleanups.push(mountProductCardHover(card));
	cleanups.push(mountArchiveSwatches(card));

	window.addEventListener(
		'beforeunload',
		() => {
			cleanups.forEach((fn) => fn());
		},
		{ once: true },
	);
}
