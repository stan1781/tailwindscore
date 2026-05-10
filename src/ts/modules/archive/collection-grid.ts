export function mountCollectionGrid(root: HTMLElement): () => void {
	const grid = root.closest<HTMLElement>('.products') ?? root.querySelector<HTMLElement>('.products') ?? root;
	const mediaQuery = window.matchMedia('(pointer: fine)');

	const sync = (): void => {
		grid.classList.toggle('is-pointer-fine', mediaQuery.matches);
		grid.classList.toggle('is-touch', !mediaQuery.matches);
	};

	sync();
	mediaQuery.addEventListener('change', sync);

	return () => {
		mediaQuery.removeEventListener('change', sync);
		grid.classList.remove('is-pointer-fine');
		grid.classList.remove('is-touch');
	};
}
