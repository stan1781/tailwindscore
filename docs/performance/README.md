# Performance（性能）

本目录预留 **TailwindScore 性能策略与测量记录**。实施 Phase 1 后在此补充可执行清单。

## 1. 原则（与架构一致）

- **SSR First**：首屏不依赖大型客户端 bundle。
- **按需加载**：checkout 不加载 archive-only 模块；WC 页面条件 enqueue。
- **CSS**：避免巨型全局 sheet；利用 Tailwind 构建剔除未用样式。
- **JS**：拆分入口、defer、避免阻塞解析。
- **图片**：`srcset/sizes`、延迟加载、合适格式（AVIF/WebP 视主机）。
- **缓存**：静态资产长缓存 + 文件名哈希；购物车/结账遵循 WC 缓存排除。

## 2. 测量工具（推荐）

- Query Monitor（开发）
- Lighthouse / Web Vitals（CI 或手动）
- Server-Timing（若主机支持）

## 3. 待办文档（后续新增）

- `budgets.md`：LCP/CLS/INP 与 bundle 大小预算
- `woocommerce-notes.md`：fragments、AJAX cart 开销与插件相互作用

当前暂无实现代码；完成构建链后与真实度量数据一并写入。
