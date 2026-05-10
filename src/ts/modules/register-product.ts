/**
 * Register product-page TS modules.
 */
import { registerModule } from '../bootstrap-registry';
import { mount as mountArchiveRuntime } from './archive';
import { mount as mountCommerceAddToCart } from './commerce/add-to-cart';
import { mount as mountCommerceQuantity } from './commerce/quantity';
import { mount as mountProductGallery } from './gallery';
import { mount as mountVariationRuntime } from './variations';

registerModule('commerce-add-to-cart', mountCommerceAddToCart);
registerModule('commerce-quantity', mountCommerceQuantity);
registerModule('tailwindscore-archive-runtime', mountArchiveRuntime);
registerModule('tailwindscore-product-gallery', mountProductGallery);
registerModule('tailwindscore-variation-runtime', mountVariationRuntime);
