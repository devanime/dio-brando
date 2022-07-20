const importAll = (r) => r.keys().forEach(r);
importAll(require.context('../images', true));
import {
    $, addAction, INIT,
} from '@devanime/estarossa.util';
addAction(INIT, function() {
    var selectiveRefresh;
    try {
        selectiveRefresh = window.wp.customize.selectiveRefresh;
    } catch (e) {
        return;
    }
    selectiveRefresh.partial.each( function(partial) {
        partial.deferred.ready.done(function() {
            $(partial.params.selector).removeAttr('title');
        });
    });
});
