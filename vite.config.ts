import path from 'path';
import { fileURLToPath } from 'url';
import { defineConfig } from 'vite';

const themeRoot = fileURLToPath(new URL('.', import.meta.url));

export default defineConfig({
	root: themeRoot,
	base: '',
	publicDir: false,
	build: {
		outDir: 'dist',
		emptyOutDir: true,
		manifest: true,
		sourcemap: true,
		rollupOptions: {
			input: {
				base: path.resolve(themeRoot, 'src/ts/bootstrap.ts'),
				product: path.resolve(themeRoot, 'src/ts/entries/product.ts'),
				archive: path.resolve(themeRoot, 'src/ts/entries/archive.ts'),
				checkout: path.resolve(themeRoot, 'src/ts/entries/checkout.ts'),
				account: path.resolve(themeRoot, 'src/ts/entries/account.ts'),
				'base-style': path.resolve(themeRoot, 'src/css/entries/base.css'),
				'product-style': path.resolve(themeRoot, 'src/css/entries/product.css'),
				'archive-style': path.resolve(themeRoot, 'src/css/entries/archive.css'),
				'checkout-style': path.resolve(themeRoot, 'src/css/entries/checkout.css'),
				'account-style': path.resolve(themeRoot, 'src/css/entries/account.css'),
			},
			output: {
				entryFileNames: 'assets/[name]-[hash].js',
				chunkFileNames: 'assets/[name]-[hash].js',
				assetFileNames: 'assets/[name]-[hash][extname]',
			},
		},
	},
	server: {
		// 使用 127.0.0.1 避免 Windows 上 localhost 解析到 IPv4 而 Vite 只监听 IPv6(::1) 导致浏览器打不开
		host: '127.0.0.1',
		port: 5173,
		strictPort: true,
		cors: true,
		hmr: {
			host: '127.0.0.1',
			protocol: 'ws',
		},
	},
	resolve: {
		alias: {
			'@': path.resolve(themeRoot, 'src/ts'),
		},
	},
});
