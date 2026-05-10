export type MountFn = (root: HTMLElement) => void;

const registry = new Map<string, MountFn>();
const mountedModules = new WeakMap<HTMLElement, Set<string>>();

export function registerModule(name: string, mount: MountFn): void {
	registry.set(name, mount);
}

export function mountRegisteredModulesIn(root: ParentNode = document): void {
	root.querySelectorAll<HTMLElement>('[data-ts-module]').forEach((el) => {
		const name = el.dataset.tsModule;
		if (!name) {
			return;
		}

		const mount = registry.get(name);
		if (!mount) {
			return;
		}

		const existing = mountedModules.get(el);
		if (existing?.has(name)) {
			return;
		}

		mount(el);

		const next = existing ?? new Set<string>();
		next.add(name);
		mountedModules.set(el, next);
	});
}

export function mountRegisteredModules(): void {
	mountRegisteredModulesIn(document);
}
