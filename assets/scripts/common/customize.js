

addAction(INIT, function() {
    var selectiveRefresh;
    try {
        selectiveRefresh = wp.customize.selectiveRefresh;
    } catch (e) {
        return;
    }
    selectiveRefresh.partial.each( function(partial) {
        partial.deferred.ready.done(function() {
            $(partial.params.selector).removeAttr('title');
        });
    });
});