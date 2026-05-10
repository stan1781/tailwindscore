# Commerce Icons

## Scope

Phase one includes the following commerce-facing icons:

- `cart`
- `bag`
- `search`
- `user`
- `heart`
- `filter`

## Core Icons

- `menu`
- `close`
- `chevron-down`
- `chevron-right`
- `plus`
- `minus`

## Social Icons

- `instagram`
- `x`
- `youtube`

## API

```php
tailwindscore_icon( 'bag' );
tailwindscore_icon(
	'search',
	array(
		'size'       => 20,
		'class'      => 'ts-icon--utility',
		'decorative' => false,
		'aria_label' => __( 'Search', 'tailwindscore' ),
	)
);
```

## Usage Notes

- Use `bag` or `cart` according to the surface language.
- Use `user` for account-related navigation.
- Use `chevron-right` for directional controls and rotate in CSS when needed.
- Keep social icons quiet and monochrome.
