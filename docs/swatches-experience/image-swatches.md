# Image Swatches（图片类 Swatch）

## SSR

- **`swatch-image-stack.php`**：`type === image_stack`，包含缩略图 URL、`preview_url`（大图 hover）、可选 **color chip**（解析层带来的辅色条）。
- **`swatch-image.php`**：简化单列图片（兼容旧 item）。
- **`swatch-preview.php`**：每个 `swatch-group` 内嵌悬浮预览宿主。

## Runtime（`swatch-image-runtime.ts`）

由 **`tailwindscore-variation-runtime`** 调用 **`mountSwatchImagePresentation(container)`**：

- **Preload**：收集 `data-preview-url` / `data-thumb-url` 去重后 `new Image().src`。
- **Hover**：对 `[data-ts-swatch-type="image_stack"]` 绑定 `pointerenter` / `leave`，将预览图定位到按钮旁（`position: fixed`）。
- **选中 / 不可用**：仍由 **`swatch-sync.ts`** 根据原生 `<select>` 的 `disabled` 同步（与 Variation Runtime 一致）。

## 与 Gallery Runtime

预览与缩略图 URL 来自 **同一附件链**（variation / term），与 **`variation-image-sync`** 使用的 `image_id` 语义一致；主题 **不**在 Swatch 层二次请求 variation API。

## 不支持

- 在 Swatch 层单独维护一套「变体图」缓存或自定义 AJAX。
- React/Vue 浮层。
