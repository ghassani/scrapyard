game.module('game.utility.log')
.body(function() {
    /**
     * LogWriter - For Debugging
     */
    game.createClass('LogWriter', {

        log : function(msg) {
            console.log(msg);
        },
        debug : function(msg) {
            //if (game.debug.enabled == true) {
            var e = new Error();
            console.debug(msg);
            console.error(e.stack);

            // }
        }

    });

});