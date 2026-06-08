import { createReduxStore } from '@wordpress/data';
import { reducer } from './reducer';
import * as selectors from './selectors';
import * as actions from './actions';
import { STORE_NAME } from './constants';

export const storeConfig = createReduxStore(STORE_NAME, {
    reducer,
    selectors,
    actions,
});
