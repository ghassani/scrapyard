game.module('game.hero.collection')
.require('game.utility.collection')
.body(function() {

    /**
     * HeroCollection - A collection of Heroes. Holds the playable characters available
     */
    game.createClass('HeroCollection', 'Collection', {

        /**
         *
         * @param array - an array of heroes
         */
        init: function(heroes) {
            this._super(heroes);
        },

        getById: function(id) {

        },

        getByName: function(name) {

        }
    });


});