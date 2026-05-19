# Template Copy Audit

Date: 2026-05-19
Scope: Template-layer customer-visible copy only
Standard: Strict base-template ownership

## Scoped File Families

- `template-parts/account/**`
- `template-parts/search/**`
- `template-parts/cart-surface/**`
- `template-parts/checkout/**`
- `template-parts/woocommerce/archive-discovery.php`
- `template-parts/components/**`
- `template-parts/feedback/**`
- `template-parts/site/**`
- `template-parts/sections/**`
- `woocommerce/myaccount/**`
- `woocommerce/checkout/**`
- `woocommerce/archive-product.php`
- `woocommerce/content-product.php`
- `woocommerce/content-single-product.php`
- `woocommerce/single-product.php`
- `woocommerce/single-product/*`
- `woocommerce/single-product-reviews.php`
- `inc/woocommerce/account.php`
- `inc/woocommerce/search.php`
- `inc/woocommerce/feedback.php`

## Approved Surface Split

- `account`: `template-parts/account/**`, `woocommerce/myaccount/**`, `inc/woocommerce/account.php`
- `search`: `template-parts/search/**`, `inc/woocommerce/search.php`
- `cart`: `template-parts/cart-surface/**`
- `checkout`: `template-parts/checkout/**`, `woocommerce/checkout/**`
- `archive`: `template-parts/woocommerce/archive-discovery.php`, `woocommerce/archive-product.php`, `woocommerce/content-product.php`
- `reviews`: `woocommerce/single-product-reviews.php`
- `cross-surface components`: `template-parts/components/**`, `template-parts/feedback/**`, `template-parts/site/**`, `template-parts/sections/**`, `woocommerce/content-single-product.php`, `woocommerce/single-product.php`, `woocommerce/single-product/*`, `inc/woocommerce/feedback.php`

## Classification Legend

- `keep`: required for function, accessibility, or minimum comprehension
- `remove`: non-essential copy that should not live in the base template layer
- `lift`: copy that may still be useful, but should move out of the base template layer

## Surfaces

### Account

| File | Context | Current text or token | Classification | Reason |
| --- | --- | --- | --- | --- |
| `template-parts/account/account-layout.php` | account shell eyebrow | `$args['eyebrow']` | `remove` | The account surface remains understandable from the page title and active navigation without an extra eyebrow label. |
| `template-parts/account/account-layout.php` | account shell title | `$args['title']` | `keep` | Each account endpoint still needs a primary heading so the current surface is identifiable. |
| `template-parts/account/account-layout.php` | account shell intro | `$args['intro']` | `remove` | The supporting overview sentence is explanatory copy rather than required task guidance. |
| `woocommerce/myaccount/form-login.php` | auth eyebrow | `Customer account` | `remove` | The sign-in surface is already identifiable from its primary heading and form structure. |
| `woocommerce/myaccount/form-login.php` | auth title | `Sign in` | `keep` | The sign-in surface needs a primary heading. |
| `woocommerce/myaccount/form-login.php` | sign-in intro paragraph | `$account_intro` | `remove` | The login form fields and submit action are already clear without reassurance copy above them. |
| `woocommerce/myaccount/form-login.php` | returning-customer section title | `Returning customer` | `keep` | The sign-in panel needs a visible section heading. |
| `woocommerce/myaccount/form-login.php` | username field label | `Email or username` | `keep` | The sign-in identifier field needs a visible label. |
| `woocommerce/myaccount/form-login.php` | password field label | `Password` | `keep` | The sign-in password field needs a visible label. |
| `woocommerce/myaccount/form-login.php` | remember-me checkbox label | `$account_copy['login_remember_label']` | `keep` | The checkbox needs its visible label to communicate the state change. |
| `woocommerce/myaccount/form-login.php` | sign-in submit label | `Sign in` | `keep` | The sign-in form needs a clear primary action label. |
| `woocommerce/myaccount/form-login.php` | forgot-password action label | `Forgot password?` | `keep` | The sign-in form needs a clear recovery action. |
| `woocommerce/myaccount/form-login.php` | registration section title | `Create account` | `keep` | The registration panel needs a visible section heading. |
| `woocommerce/myaccount/form-login.php` | username field label | `Username` | `keep` | The registration username field needs a visible label when rendered. |
| `woocommerce/myaccount/form-login.php` | email field label | `Email address` | `keep` | The registration email field needs a visible label. |
| `woocommerce/myaccount/form-login.php` | registration password field label | `Password` | `keep` | The registration password field needs a visible label when rendered. |
| `woocommerce/myaccount/form-login.php` | registration support paragraph | `$support_message` | `remove` | The generated-password explanation is supportive copy that does not belong in the base template layer. |
| `woocommerce/myaccount/form-login.php` | registration submit label | `Create account` | `keep` | The registration form needs a clear primary action label. |
| `woocommerce/myaccount/form-edit-account.php` | form header intro paragraph | `$account_copy['edit_account_intro']` | `remove` | The profile form remains usable from its field labels and save action without an introductory sentence. |
| `woocommerce/myaccount/form-edit-account.php` | save action label | `Save changes` | `keep` | The primary submit action needs an explicit label. |
| `woocommerce/myaccount/form-edit-address.php` | address form intro paragraph | `$address_guidance` | `remove` | The address form does not require checkout-oriented coaching copy to remain understandable. |
| `woocommerce/myaccount/form-edit-address.php` | address list section title | `Billing address` | `keep` | The address list needs a visible label for the billing destination. |
| `woocommerce/myaccount/form-edit-address.php` | address list section title | `Shipping address` | `keep` | The address list needs a visible label for the shipping destination when that destination exists. |
| `woocommerce/myaccount/form-edit-address.php` | address-card action label | `Edit address` | `keep` | The saved-address action needs a visible label. |
| `woocommerce/myaccount/form-edit-address.php` | address-card action label | `Add address` | `keep` | The empty-address action needs a visible label. |
| `woocommerce/myaccount/form-edit-address.php` | address form title | `wc_edit_address_i18n( $load_address )` | `keep` | The edit-address form needs a visible heading for the current address type. |
| `woocommerce/myaccount/form-edit-address.php` | save-address action label | `Save address` | `keep` | The address form submit action needs a visible label. |
| `woocommerce/myaccount/form-lost-password.php` | recovery caption paragraph | `$recovery['caption']` | `remove` | The reset flow is already explained by the heading, field label, and submit action. |
| `woocommerce/myaccount/form-lost-password.php` | auth eyebrow | `Account access` | `remove` | The recovery surface is already identifiable from its main heading and form content. |
| `woocommerce/myaccount/form-lost-password.php` | auth title | `Reset password` | `keep` | The recovery form needs a primary heading. |
| `woocommerce/myaccount/form-lost-password.php` | auth intro | `$recovery['intro']` | `remove` | The supporting reset explanation is non-essential guidance copy. |
| `woocommerce/myaccount/form-lost-password.php` | user identifier field label | `Email or username` | `keep` | The recovery field needs a visible label. |
| `woocommerce/myaccount/form-lost-password.php` | submit button label | `Send reset link` | `keep` | The form needs a clear primary action label. |
| `woocommerce/myaccount/form-lost-password.php` | return action label | `Back to sign in` | `keep` | The recovery form needs a clear route back to sign-in. |
| `woocommerce/myaccount/form-reset-password.php` | reset support paragraph | `$recovery['support']` | `remove` | This is optional reassurance rather than minimum task-critical copy. |
| `woocommerce/myaccount/form-reset-password.php` | auth eyebrow | `Account access` | `remove` | The reset surface is already identifiable from its main heading and field labels. |
| `woocommerce/myaccount/form-reset-password.php` | auth title | `Choose a new password` | `keep` | The reset form needs a primary heading. |
| `woocommerce/myaccount/form-reset-password.php` | auth intro | `$recovery['reset']` | `remove` | The supporting reset explanation is non-essential guidance copy. |
| `woocommerce/myaccount/form-reset-password.php` | new-password field label | `$account_copy['reset_new_password_label']` | `keep` | The new-password field needs a visible label. |
| `woocommerce/myaccount/form-reset-password.php` | confirm-password field label | `$account_copy['reset_confirm_label']` | `keep` | The confirm-password field needs a visible label. |
| `woocommerce/myaccount/form-reset-password.php` | submit button label | `$account_copy['reset_submit_label']` | `keep` | The reset form needs a clear primary action label. |
| `woocommerce/myaccount/dashboard.php` | dashboard intro paragraph | `$dashboard_message` | `remove` | The account overview cards already establish the available destinations without an extra overview sentence. |
| `woocommerce/myaccount/dashboard.php` | overview card title | `$item['title']` | `keep` | Each dashboard card needs a visible title to identify its destination. |
| `woocommerce/myaccount/dashboard.php` | overview card copy | `$item['copy']` | `lift` | The descriptive card body is optional orientation copy that can move above the base template layer. |
| `woocommerce/myaccount/dashboard.php` | overview card CTA | `$open_label` | `keep` | The dashboard card action needs a visible label. |
| `woocommerce/myaccount/orders.php` | orders panel aria label | `Orders` | `keep` | The orders region needs an accessible label for the panel. |
| `woocommerce/myaccount/orders.php` | orders pagination aria label | `Orders pagination` | `keep` | The pagination navigation needs an accessible label. |
| `woocommerce/myaccount/orders.php` | pagination previous label | `Previous` | `keep` | The pagination control needs a visible action label. |
| `woocommerce/myaccount/orders.php` | pagination next label | `Next` | `keep` | The pagination control needs a visible action label. |
| `woocommerce/myaccount/orders.php` | empty-orders action label | `$browse_label` | `keep` | The empty state needs a named action to return the customer to shopping. |
| `woocommerce/myaccount/downloads.php` | download card title | `$download['download_name'] ?? $download['product_name']` | `keep` | Each downloadable item needs a visible title to identify the asset. |
| `woocommerce/myaccount/downloads.php` | downloads remaining label | `%s remaining` | `keep` | Remaining-download status is task-critical information for the customer. |
| `woocommerce/myaccount/downloads.php` | unlimited fallback label | `Unlimited` | `keep` | The customer needs an explicit unlimited-download status when no cap applies. |
| `woocommerce/myaccount/downloads.php` | access expiry label | `Available until %s` | `keep` | Expiry status is task-critical download information. |
| `woocommerce/myaccount/downloads.php` | download action label | `Download` | `keep` | The download card needs a clear primary action label. |
| `template-parts/account/account-navigation.php` | nav toggle label | `Browse account` | `keep` | The collapsed account-nav trigger needs a visible label. |
| `template-parts/account/account-navigation.php` | nav panel aria label | `Account navigation` | `keep` | The account navigation region needs an accessible label. |
| `template-parts/account/account-navigation.php` | endpoint link labels | `$label` | `keep` | Each navigation destination needs a visible label. |
| `template-parts/account/account-empty.php` | empty-state eyebrow slot | `$copy['eyebrow']` | `remove` | The account empty state does not need extra framing beyond its title, message, and action. |
| `template-parts/account/account-empty.php` | empty-state title slot | `$copy['title']` | `keep` | The account empty state needs a primary heading. |
| `template-parts/account/account-empty.php` | empty-state message slot | `$copy['message']` | `keep` | The account empty state needs explicit status text explaining the missing content. |
| `template-parts/account/address-card.php` | address card title | `$args['title']` | `keep` | Each saved-address card needs a visible heading. |
| `template-parts/account/address-card.php` | empty-address fallback copy | `$account_copy['address_empty_message']` | `keep` | The card needs explicit status text when no address has been saved. |
| `template-parts/account/address-card.php` | address card action label | `$args['action_label']` | `keep` | The card action needs a visible label to add or edit the address. |
| `template-parts/account/order-card.php` | order eyebrow | `Order #%s` | `keep` | The order card needs the order identifier to orient the customer within order history. |
| `template-parts/account/order-card.php` | order meta fallback | `Recent order` | `keep` | The card needs a status fallback when the order date is unavailable. |
| `template-parts/account/order-card.php` | item-count unit labels | `item` / `items` | `keep` | The order summary needs visible quantity units for the item count. |
| `template-parts/account/order-card.php` | quick-detail toggle label | `$quick_detail` | `keep` | The expandable details control needs a visible label. |
| `template-parts/account/order-detail.php` | items section heading | `$copy['items_heading']` | `keep` | The order detail item list needs a visible heading. |
| `template-parts/account/order-detail.php` | quantity line label | `$copy['quantity_format']` | `keep` | Each line item needs an explicit quantity label. |
| `template-parts/account/order-detail.php` | delivery section heading | `$copy['delivery_heading']` | `keep` | The delivery detail block needs a visible heading. |
| `template-parts/account/order-detail.php` | shipping-method label | `$copy['shipping_method_label']` | `keep` | The shipping method row needs a visible label. |
| `template-parts/account/order-detail.php` | no-shipping-method fallback | `$copy['no_shipping_method']` | `keep` | The customer needs a clear fallback when shipping is not required. |
| `template-parts/account/order-detail.php` | shipping-address label | `$copy['shipping_address_label']` | `keep` | The shipping address row needs a visible label. |
| `template-parts/account/order-detail.php` | no-shipping-address fallback | `$copy['no_shipping_address']` | `keep` | The detail block needs explicit fallback text when no shipping address exists. |
| `template-parts/account/order-detail.php` | payment section heading | `$copy['payment_heading']` | `keep` | The payment detail block needs a visible heading. |
| `template-parts/account/order-detail.php` | payment-method label | `$copy['payment_method_label']` | `keep` | The payment-method row needs a visible label. |
| `template-parts/account/order-detail.php` | pending-payment fallback | `$copy['payment_method_pending']` | `keep` | The customer needs a clear fallback when payment confirmation is pending. |
| `template-parts/account/order-detail.php` | billing-address label | `$copy['billing_address_label']` | `keep` | The billing-address row needs a visible label. |
| `template-parts/account/order-detail.php` | no-billing-address fallback | `$copy['no_billing_address']` | `keep` | The detail block needs explicit fallback text when no billing address exists. |
| `woocommerce/myaccount/view-order.php` | back-to-orders action label | `$account_copy['view_order_back_label']` | `keep` | The return action needs a clear label to move back to the orders index. |

### Search

| File | Context | Current text or token | Classification | Reason |
| --- | --- | --- | --- | --- |
| `template-parts/search/search-overlay.php` | overlay eyebrow | `$copy['eyebrow']` | `remove` | The dialog already has a titled search surface, so the eyebrow is decorative framing. |
| `template-parts/search/search-overlay.php` | overlay title | `$copy['title']` | `keep` | The search dialog needs a visible heading for orientation and dialog labeling. |
| `template-parts/search/search-overlay.php` | close button screen-reader label | `Close search` | `keep` | The icon-only dialog close control needs assistive text. |
| `template-parts/search/search-overlay.php` | field screen-reader label | `Search products` | `keep` | The search input needs an accessible label independent of placeholder text. |
| `template-parts/search/search-overlay.php` | search input placeholder | `$copy['overlay_placeholder']` | `keep` | The placeholder provides the minimum cue for what can be searched in the field. |
| `template-parts/search/search-overlay.php` | loading-state eyebrow | `$loading_copy['eyebrow']` | `remove` | The loading state does not need extra framing beyond its primary status copy. |
| `template-parts/search/search-overlay.php` | loading-state title | `$loading_copy['title']` | `keep` | The loading state needs a primary status heading. |
| `template-parts/search/search-overlay.php` | loading-state message | `$loading_copy['message']` | `keep` | The loading state needs an explanatory status message. |
| `template-parts/search/search-overlay.php` | unavailable-state eyebrow | `$unavailable_copy['eyebrow']` | `remove` | The unavailable state does not need extra framing beyond its title, message, and action. |
| `template-parts/search/search-overlay.php` | unavailable-state title | `$unavailable_copy['title']` | `keep` | The unavailable state needs a primary heading. |
| `template-parts/search/search-overlay.php` | unavailable-state message | `$unavailable_copy['message']` | `keep` | The unavailable state needs an explanatory status message. |
| `template-parts/search/search-overlay.php` | unavailable retry button | `Try again` | `keep` | The recovery action needs an explicit label when search results cannot load. |
| `template-parts/search/default-state.php` | default-state eyebrow | `$copy['eyebrow']` | `lift` | Discovery framing may still be useful, but it should be owned by a higher content layer instead of the base template. |
| `template-parts/search/default-state.php` | default-state title | `$copy['default_state_title']` | `lift` | Discovery framing may still be useful, but it should be owned by a higher content layer instead of the base template. |
| `template-parts/search/default-state.php` | suggested-searches heading | `$copy['suggested_searches_heading']` | `lift` | This is optional merchandising structure for a discovery surface, not a required control label. |
| `template-parts/search/default-state.php` | suggested-search chip label | `$item['label']` | `keep` | Each suggested search chip needs a visible label. |
| `template-parts/search/default-state.php` | browse-collections heading | `$copy['browse_collections_heading']` | `lift` | Collection-framing copy belongs to a higher discovery-content layer if retained. |
| `template-parts/search/default-state.php` | featured-collection chip label | `$item['label']` | `keep` | Each featured collection chip needs a visible label. |
| `template-parts/search/default-state.php` | recent-searches heading | `$copy['recent_searches_heading']` | `lift` | Recent-search framing is optional discovery copy rather than a required control label. |
| `template-parts/search/default-state.php` | recent-searches guidance paragraph | `$copy['recent_searches_guidance']` | `remove` | The recent-searches chip list remains understandable without a supporting guidance sentence. |
| `template-parts/search/predictive-results.php` | product-result group heading | `Pieces` | `keep` | The results column needs a compact heading to distinguish product matches from discovery links. |
| `template-parts/search/predictive-results.php` | product-result title | `$item['title']` | `keep` | Each predictive product result needs a visible title. |
| `template-parts/search/predictive-results.php` | product-result meta type | `$item['type']` | `keep` | The predictive product fallback metadata needs a visible label when price is absent. |
| `template-parts/search/predictive-results.php` | discovery group heading | `Collections` | `lift` | This is discovery framing that can move above the base template layer if the experience keeps it. |
| `template-parts/search/predictive-results.php` | editorial section label | `Browse collections` | `lift` | This is discovery framing that can move above the template layer if the experience keeps it. |
| `template-parts/search/predictive-results.php` | collection-result type | `$item['type']` | `keep` | Each collection result needs visible metadata to identify the match type. |
| `template-parts/search/predictive-results.php` | collection-result title | `$item['title']` | `keep` | Each collection result needs a visible title. |
| `template-parts/search/predictive-results.php` | category count label | `_n( '%s item', '%s items', ... )` | `keep` | Category result counts are task-critical metadata for interpreting collection matches. |
| `template-parts/search/predictive-results.php` | suggestion section label | `Suggested paths` | `lift` | The label is optional discovery copy rather than a required functional control label. |
| `template-parts/search/predictive-results.php` | suggestion-result label | `$item['label']` | `keep` | Each suggested path link needs a visible label. |
| `template-parts/search/predictive-results.php` | empty-state eyebrow | `$empty_copy['eyebrow']` | `remove` | The empty predictive state does not need extra framing beyond its title and action. |
| `template-parts/search/predictive-results.php` | empty-state title | `$empty_copy['title']` | `keep` | The empty predictive state needs a primary heading. |
| `template-parts/search/predictive-results.php` | empty-state guidance message | `$search_copy['predictive_empty_message']` | `remove` | The empty state can rely on its title and action without an extra coaching sentence. |
| `template-parts/search/predictive-results.php` | fallback results action label | `See all results` | `keep` | The empty-state action needs a clear path to full search results. |

### Cart

| File | Context | Current text or token | Classification | Reason |
| --- | --- | --- | --- | --- |
| `template-parts/cart-surface/cart-drawer.php` | cart drawer eyebrow | `Bag` | `remove` | The drawer already carries a primary title, so the eyebrow is non-essential framing. |
| `template-parts/cart-surface/cart-drawer.php` | cart drawer title | `Cart` | `keep` | The drawer needs a primary heading so the surface is clearly identified. |
| `template-parts/cart-surface/cart-drawer.php` | close-drawer screen-reader label | `Close cart` | `keep` | The icon-only drawer close control needs assistive text. |
| `template-parts/cart-surface/cart-trigger.php` | trigger label | `$args['label']` | `keep` | The cart trigger needs a visible or screen-reader label to describe its action. |
| `template-parts/cart-surface/cart-drawer.php` | cart loading status | `$copy['loading_message']` | `keep` | Loading text is task-critical status copy while cart mutations are in progress. |
| `template-parts/cart-surface/cart-line-item.php` | item category eyebrow | `$args['category']` | `lift` | Category framing is optional merchandising context rather than required cart function copy. |
| `template-parts/cart-surface/cart-line-item.php` | item title | `$args['title']` | `keep` | Each cart line item needs a visible product title. |
| `template-parts/cart-surface/cart-line-item.php` | quantity label | `Quantity` | `keep` | The quantity control needs a visible label. |
| `template-parts/cart-surface/cart-line-item.php` | decrease-quantity aria label | `Decrease quantity` | `keep` | The decrement control needs assistive text because it is icon/text-minimal. |
| `template-parts/cart-surface/cart-line-item.php` | increase-quantity aria label | `Increase quantity` | `keep` | The increment control needs assistive text because it is icon/text-minimal. |
| `template-parts/cart-surface/cart-line-item.php` | subtotal label | `$copy['line_item_subtotal_label']` | `keep` | The per-line subtotal needs a visible label. |
| `template-parts/cart-surface/cart-line-item.php` | remove action label | `Remove` | `keep` | The line-item removal action needs a visible label. |
| `template-parts/cart-surface/cart-empty.php` | empty-cart eyebrow slot | `$copy['eyebrow']` | `remove` | The empty-cart state does not need extra framing beyond its title, message, and action. |
| `template-parts/cart-surface/cart-empty.php` | empty-cart title slot | `$copy['title']` | `keep` | The empty-cart state needs a primary heading. |
| `template-parts/cart-surface/cart-empty.php` | empty-cart message slot | `$copy['message']` | `keep` | The empty-cart state needs explicit status text explaining why no items are shown. |
| `template-parts/cart-surface/cart-empty.php` | empty-cart action label | `$copy['action']` | `keep` | The empty state needs an explicit action label to return the user to shopping. |
| `template-parts/cart-surface/cart-summary.php` | subtotal label | `$summary_copy['subtotal_label']` | `keep` | The summary row needs a visible label to identify the amount being shown. |
| `template-parts/cart-surface/cart-summary.php` | summary note | `$summary_copy['summary_note']` | `remove` | Any supplemental note is explanatory copy that is not required for checkout progression. |
| `template-parts/cart-surface/cart-summary.php` | checkout action label | `$summary_copy['checkout_label']` | `keep` | The primary next-step action needs a clear label. |
| `template-parts/cart-surface/cart-summary.php` | view-cart action label | `$summary_copy['view_cart_label']` | `keep` | The secondary navigation action needs a visible label. |

### Checkout

| File | Context | Current text or token | Classification | Reason |
| --- | --- | --- | --- | --- |
| `template-parts/checkout/checkout-layout.php` | checkout eyebrow | `$copy['eyebrow']` | `remove` | The checkout surface remains clear from the main heading and form structure without an eyebrow. |
| `template-parts/checkout/checkout-layout.php` | checkout shell title | `$copy['layout_title'] ?? $copy['title']` | `keep` | The checkout surface needs a primary heading. |
| `template-parts/checkout/checkout-layout.php` | loading status | `$copy['loading_message']` | `keep` | Refreshing-order status text is task-critical while totals and payment state update. |
| `template-parts/checkout/checkout-layout.php` | support region aria label | `Checkout guidance` | `keep` | The support-item cluster needs an accessible label. |
| `template-parts/checkout/checkout-layout.php` | support-item list | `$copy['support_items']` | `remove` | Supplemental reassurance items are optional merchandising/support copy rather than base-template essentials. |
| `template-parts/checkout/checkout-layout.php` | order-review heading | `Order review` | `keep` | The aside needs a heading to distinguish the order summary from the customer detail forms. |
| `template-parts/checkout/checkout-layout.php` | order-review intro paragraph | `$copy['review_intro']` | `remove` | The summary panel remains understandable without a supporting explanatory sentence. |
| `template-parts/checkout/checkout-empty.php` | unavailable-state eyebrow | `$copy['eyebrow'] ?? __( 'Purchase flow', 'tailwindscore' )` | `remove` | The unavailable checkout surface does not need extra framing beyond its title and action. |
| `template-parts/checkout/checkout-empty.php` | unavailable-state title | `$headline` | `keep` | The unavailable checkout surface needs a primary heading. |
| `template-parts/checkout/checkout-empty.php` | unavailable-state intro | `$message` | `remove` | The supporting unavailable-checkout explanation is non-essential guidance copy. |
| `template-parts/checkout/checkout-empty.php` | unavailable-state CTA | `$surface['empty_cta_label']` | `keep` | The unavailable checkout surface needs a clear action back to cart flow. |
| `template-parts/checkout/checkout-summary.php` | summary heading | `$surface_copy['summary_heading']` | `keep` | The order-summary block needs a visible heading inside the review table surface. |
| `template-parts/checkout/checkout-summary.php` | summary note | `$surface_copy['summary_note']` | `remove` | Supplemental checkout guidance is not required inside the base summary template. |
| `template-parts/checkout/checkout-summary.php` | product column header | `Product` | `keep` | The review table needs a visible product column header. |
| `template-parts/checkout/checkout-summary.php` | subtotal column header | `$surface_copy['summary_subtotal']` | `keep` | The review table needs a visible amount column header. |
| `template-parts/checkout/checkout-summary.php` | quantity line label | `Quantity %d` | `keep` | Each checkout line item needs an explicit quantity label. |
| `template-parts/checkout/checkout-summary.php` | subtotal footer row label | `$surface_copy['summary_subtotal']` | `keep` | The totals footer needs a visible subtotal row label. |
| `template-parts/checkout/checkout-summary.php` | shipping row label | `Shipping` | `keep` | The totals table needs a visible shipping row label. |
| `template-parts/checkout/checkout-summary.php` | total row label | `Total` | `keep` | The totals table needs a visible final total label. |
| `template-parts/checkout/checkout-payment.php` | payment intro paragraph | `$copy['payment_intro']` | `remove` | Available methods and the place-order action already explain the payment step. |
| `template-parts/checkout/checkout-payment.php` | unavailable-payment notice | `$copy['payment_unavailable_message']` | `keep` | This notice is task-critical when no gateway can be offered for the current checkout state. |
| `template-parts/checkout/checkout-payment.php` | billing-required notice | `$copy['payment_billing_required_message']` | `keep` | The user needs this message to understand why payment methods are unavailable. |
| `template-parts/checkout/checkout-payment.php` | no-payment-needed message | `$copy['payment_not_needed_message']` | `keep` | The absence of a payment step still needs an explicit status explanation. |
| `template-parts/checkout/checkout-payment.php` | noscript update message | `$copy['noscript_update_message']` | `keep` | This message is functional guidance for a degraded checkout path. |
| `template-parts/checkout/checkout-payment.php` | update-totals action label | `$copy['update_totals_label']` | `keep` | The noscript path needs a named action to refresh totals. |
| `template-parts/checkout/checkout-payment.php` | place-order submit label | `$order_button_text` / `Place order` | `keep` | The checkout form needs a clear final submit label. |

### Archive

| File | Context | Current text or token | Classification | Reason |
| --- | --- | --- | --- | --- |
| `template-parts/woocommerce/archive-discovery.php` | archive eyebrow | `Search results` / `Collection` | `remove` | The archive title already identifies the surface, so the eyebrow is extra framing copy. |
| `template-parts/woocommerce/archive-discovery.php` | archive description block | `$archive_description` | `lift` | Discovery and merchandising description may still be valuable, but it should be owned above the base template layer. |
| `template-parts/woocommerce/archive-discovery.php` | sort-control label | `Sort by` | `keep` | The ordering control needs a visible label for comprehension and scanning. |
| `woocommerce/content-product.php` | product card title | `$title` | `keep` | Each archive product card needs a visible title. |
| `woocommerce/content-product.php` | swatch button aria label | `$label` | `keep` | Variant swatches need an accessible label to identify the option being selected. |

### Reviews

| File | Context | Current text or token | Classification | Reason |
| --- | --- | --- | --- | --- |
| `woocommerce/single-product-reviews.php` | reviews section title | `$review_copy['title']` | `keep` | The review region needs a primary heading to identify the content block. |
| `woocommerce/single-product-reviews.php` | reviews intro paragraph | `$review_copy['intro']` | `lift` | Review framing may remain useful, but it should move out of the base template if retained. |
| `woocommerce/single-product-reviews.php` | review count label | `_n( '%s review', '%s reviews', ... )` | `keep` | The review summary needs a visible count label. |
| `woocommerce/single-product-reviews.php` | pagination aria label | `$review_copy['pagination_label']` | `keep` | Pagination controls need an accessible label to describe the navigation region. |
| `woocommerce/single-product-reviews.php` | no-comments empty-state eyebrow | `$empty_copy['eyebrow']` | `remove` | The no-comments empty state does not need extra framing beyond its title and message. |
| `woocommerce/single-product-reviews.php` | no-comments empty-state title | `$empty_copy['title']` | `keep` | The no-comments empty state needs a primary heading. |
| `woocommerce/single-product-reviews.php` | no-comments empty-state message | `$empty_copy['message']` | `keep` | The no-comments empty state needs explicit status text. |
| `woocommerce/single-product-reviews.php` | gated-access eyebrow | `$review_copy['access_eyebrow']` | `remove` | The access empty state does not need an additional eyebrow to communicate the restriction. |
| `woocommerce/single-product-reviews.php` | gated-access title | `$review_copy['access_title']` | `keep` | The gated review state needs a primary heading. |
| `woocommerce/single-product-reviews.php` | gated-access message | `$review_copy['access_message']` | `keep` | The user needs the restriction message to understand why review submission is unavailable. |
| `woocommerce/single-product-reviews.php` | gated-access sign-in button | `$review_copy['access_sign_in_label']` | `keep` | The recovery path needs a clear action label. |

### Cross-Surface Components

| File | Context | Current text or token | Classification | Reason |
| --- | --- | --- | --- | --- |
| `template-parts/site/utility-bar.php` | utility-bar aria label | `Store announcement` | `keep` | The announcement region needs an accessible label to describe the banner content. |
| `template-parts/site/utility-bar.php` | fallback announcement copy | `tailwindscore_site_shell_announcement_text()` | `lift` | Announcement content is site-level messaging that should be owned by a higher content layer. |
| `template-parts/site/header.php` | mobile-menu screen-reader label | `Open menu` | `keep` | The icon-only menu trigger needs screen-reader text to remain operable. |
| `template-parts/site/header.php` | brand label | `tailwindscore_site_shell_brand_label()` | `keep` | The site header needs visible brand text for primary orientation. |
| `template-parts/site/header.php` | utilities region aria label | `Store utilities` | `keep` | The utility link cluster needs an accessible region label. |
| `template-parts/site/header.php` | utility item label | `$item['label']` | `keep` | Each emitted utility destination needs a visible or passed-through control label. |
| `template-parts/site/mobile-navigation.php` | drawer aria label | `Mobile navigation` | `keep` | The dialog-like navigation drawer needs an accessible surface label. |
| `template-parts/site/mobile-navigation.php` | close-menu screen-reader label | `Close menu` | `keep` | The icon-only close button needs explicit assistive text. |
| `template-parts/site/navigation.php` | navigation aria label | `Primary navigation` | `keep` | The shared site navigation wrapper needs an accessible label for the rendered menu. |
| `template-parts/site/footer.php` | footer section heading | `Commerce` | `keep` | The footer commerce group needs a visible heading. |
| `template-parts/site/footer.php` | footer section nav label | `Commerce footer links` | `keep` | The footer commerce navigation needs an accessible label. |
| `template-parts/site/footer.php` | footer brand summary | `tailwindscore_site_shell_footer_summary()` | `lift` | Footer brand messaging is site-level copy that should be owned above the base template layer. |
| `template-parts/site/footer.php` | footer section heading | `Support` | `keep` | The footer support group needs a visible heading. |
| `template-parts/site/footer.php` | footer section nav label | `Support footer links` | `keep` | The footer support navigation needs an accessible label. |
| `template-parts/site/footer.php` | footer support summary | `tailwindscore_site_shell_support_message()` | `lift` | Footer support messaging is site-level copy that should be owned above the base template layer. |
| `template-parts/site/footer.php` | footer section heading | `Editorial` | `keep` | The footer editorial group needs a visible heading. |
| `template-parts/site/footer.php` | footer section nav label | `Editorial footer links` | `keep` | The footer editorial navigation needs an accessible label. |
| `template-parts/site/footer.php` | footer section heading | `Newsletter` | `keep` | The newsletter slot needs a visible heading when rendered. |
| `template-parts/site/footer.php` | footer legal text | `tailwindscore_site_shell_footer_legal_text()` | `lift` | Footer legal copy is site-level messaging that should be owned above the base template layer. |
| `template-parts/site/footer.php` | footer social nav label | `Social links` | `keep` | The social navigation needs an accessible label. |
| `template-parts/search/search-trigger.php` | search trigger label | `Search` | `keep` | The global search trigger needs a visible or screen-reader label to describe its action. |
| `template-parts/components/input.php` | input label slot | `$label` | `keep` | The shared input component emits this label directly when present. |
| `template-parts/components/input.php` | input placeholder | `$placeholder` | `keep` | The shared input component emits placeholder guidance directly when provided. |
| `template-parts/components/input.php` | input help slot | `$help` | `lift` | Help copy may be useful, but it should be owned by the calling surface rather than the base component layer. |
| `template-parts/components/input.php` | input error slot | `$error` | `keep` | The shared input component emits validation error text directly and it is task-critical. |
| `template-parts/components/select.php` | select label slot | `$label` | `keep` | The shared select component emits this label directly when present. |
| `template-parts/components/select.php` | select placeholder option | `$placeholder` | `keep` | The shared select component emits this placeholder option directly when provided. |
| `template-parts/components/select.php` | select option label | `$opt_label` | `keep` | The shared select component emits option labels directly and they are required for choice comprehension. |
| `template-parts/components/select.php` | select help slot | `$help` | `lift` | Help copy may be useful, but it should be owned by the calling surface rather than the base component layer. |
| `template-parts/components/select.php` | select error slot | `$error` | `keep` | The shared select component emits validation error text directly and it is task-critical. |
| `template-parts/components/badge.php` | badge label slot | `$label` | `keep` | The shared badge component emits this status or promo label directly. |
| `template-parts/components/button.php` | icon-only fallback aria label | `Button` | `keep` | The shared button component needs fallback assistive text when rendered icon-only without a label. |
| `template-parts/components/price.php` | WooCommerce price HTML | `$price_html` | `keep` | The shared price component emits this primary price content directly when provided. |
| `template-parts/components/price.php` | price suffix HTML | `$suffix_html` | `lift` | Supplemental suffix copy may be useful, but it should be owned by the calling surface rather than the base component layer. |
| `template-parts/components/price.php` | price unit HTML | `$unit_html` | `lift` | Unit copy may be useful, but it should be owned by the calling surface rather than the base component layer. |
| `template-parts/components/commerce/add-to-cart-button.php` | add-to-cart loading status | `Adding to bag` | `keep` | Add-to-cart interactions need status copy while the request is in flight. |
| `template-parts/components/commerce/notice.php` | notice-region scope label | `Store notices` | `keep` | The commerce notice host emits an accessible label for the notice region. |
| `template-parts/components/commerce/quantity.php` | decrease-quantity aria label | `Decrease quantity` | `keep` | The shared quantity decrement control needs assistive text because it is icon-only. |
| `template-parts/components/commerce/quantity.php` | increase-quantity aria label | `Increase quantity` | `keep` | The shared quantity increment control needs assistive text because it is icon-only. |
| `template-parts/components/swatches/swatch-group.php` | swatch-group aria label | `$aria_label` | `keep` | The swatch radiogroup needs an accessible label. |
| `template-parts/components/swatches/swatch-group.php` | swatch option aria label | `$label !== '' ? $label : $value` | `keep` | Each swatch option needs an accessible label. |
| `template-parts/components/swatches/swatch-group.php` | color swatch caption | `$label` | `keep` | The color swatch emits a visible caption when a label is present. |
| `template-parts/components/swatches/swatch-group.php` | image-stack swatch caption | `$label` | `keep` | The image-stack swatch emits a visible caption when a label is present. |
| `template-parts/components/swatches/swatch-group.php` | image swatch caption | `$label` | `keep` | The image swatch emits a visible caption when a label is present. |
| `template-parts/components/swatches/swatch-group.php` | text swatch label | `$label !== '' ? $label : $value` | `keep` | The text swatch emits its visible option label directly. |
| `template-parts/feedback/empty-state.php` | empty-state eyebrow slot | `$args['eyebrow']` | `keep` | The shared empty-state template emits this slot directly, so it remains part of template-visible copy inventory. |
| `template-parts/feedback/empty-state.php` | empty-state title slot | `$args['title']` | `keep` | The shared empty-state template emits this slot directly, so it remains part of template-visible copy inventory. |
| `template-parts/feedback/empty-state.php` | empty-state message slot | `$args['message']` | `keep` | The shared empty-state template emits this slot directly, so it remains part of template-visible copy inventory. |
| `template-parts/feedback/loading.php` | generic loading fallback | `Updating` | `keep` | The shared loading component needs a default status message when no override is supplied. |
| `template-parts/feedback/notice.php` | notice-region scope label | `Store notices` | `keep` | The shared feedback region needs an accessible label for server and client notice content. |
| `template-parts/feedback/toast.php` | toast-region scope label | `Store feedback` | `keep` | The shared toast host needs an accessible label for live feedback. |
| `template-parts/feedback/validation.php` | validation title slot | `$args['title']` | `keep` | The shared validation surface emits this title slot directly when present. |
| `template-parts/feedback/validation.php` | validation message slot | `$args['message']` | `keep` | The shared validation surface emits this message slot directly. |
| `woocommerce/single-product/product-image.php` | thumbnail button aria label | `Go to image %d` | `keep` | Each gallery thumbnail button needs assistive text identifying the destination slide. |
| `woocommerce/single-product/product-image.php` | previous-image aria label | `Previous image` | `keep` | The gallery previous control needs assistive text because it is icon-only. |
| `woocommerce/single-product/product-image.php` | next-image aria label | `Next image` | `keep` | The gallery next control needs assistive text because it is icon-only. |
