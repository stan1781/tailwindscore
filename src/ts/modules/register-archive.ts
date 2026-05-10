/**
 * Register archive-page TS modules.
 */
import { registerModule } from '../bootstrap-registry';
import { mount as mountArchiveRuntime } from './archive';

registerModule('tailwindscore-archive-runtime', mountArchiveRuntime);
