import { mountRegisteredModules } from './module-mounts';

function init(): void {
	document.documentElement.classList.add('ts-js');
	mountRegisteredModules();
}

export function runAppEntry(): void {
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init, { once: true });
		return;
	}

	init();
}
