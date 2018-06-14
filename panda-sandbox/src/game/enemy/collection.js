game.module('game.enemy.collection')
.require('game.utility.collection')
.body(function() {

    /**
     * EnemyCollection - A collection of Enemy NPC Characters
     */
    game.createClass('EnemyCollection', 'Collection', {

        /**
         *
         * @param array - an array of enemies
         */
        init: function(enemies) {
            this._super(enemies);
        },

        getByCode: function(code) {

        },

        getByName: function(name) {

        }
    });

});