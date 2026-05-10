# Attribute metadata（词条元数据结构）

所有字段均为 **展示用**，**不修改** WooCommerce variation 核心数据结构。

## Term meta 键名（`pa_*` 全局属性）

| Meta key | 类型 | 说明 |
| --- | --- | --- |
| `tailwindscore_swatch_variation_image` | int | **优先级 1** — 专用 variation swatch 附件 ID |
| `tailwindscore_swatch_image` | int | **优先级 3** — 词条纹理 / 图案缩略图 |
| `tailwindscore_swatch_color` | string | 色值（建议 `#rrggbb`） |
| `tailwindscore_swatch_color_secondary` | string | 双色 second chip |
| `tailwindscore_swatch_display` | string | 单词条覆盖：`text` \| `color` \| `image` \| `auto` |

注册位置：`inc/woocommerce/swatches/attribute-term-meta.php`（`show_in_rest` + `manage_woocommerce`）。

## 推荐：按属性二选一展示（Filter）

**`tailwindscore/swatches/image_attributes`** — 返回需「**变体/term 图优先，无图则色块**」的属性名数组；**未列入**的属性前台为 **纯文本 Swatch**。

```php
add_filter(
	'tailwindscore/swatches/image_attributes',
	static function (): array {
		return array( 'pa_color', 'pa_finish' );
	}
);
```

## Presentation Map（精细覆盖、可选）

使用 filter：**`tailwindscore/swatches/presentation_map`** 可覆盖单个属性（**优先级高于** `image_attributes`），例如：

```php
add_filter( 'tailwindscore/swatches/presentation_map', function () {
	return array(
		'pa_color' => array(
			'type'     => 'image',
			'fallback' => 'color',
		),
		'pa_size'  => array(
			'type' => 'text',
		),
	);
} );
```

单属性微调：**`tailwindscore/swatches/presentation_config`**（在 map + image_attributes 合并之后执行）。

## 预览图尺寸

Filter：**`tailwindscore/swatches/preview_image_size`**，默认 `woocommerce_single`。

## 不支持

- 在 meta 中存储自定义 variation JSON 或价格。
- 通过选项表全局覆盖 WC 核心 attribute schema。
