"use strict";

game.module('game.battle.menu.abstract')
.body(function() {
    /**
     * AbstractMenu - An abstract menu for user selection
     */
    game.createClass('AbstractMenu', 'Class', {

        width : 250,

        height: 210,

        background : {
            color: 0x000000,
            opacity : 0.50
        },

        position : {
            x : 0,
            y : 0
        },

        /**
         * constructor
         *
         * @param object properties
         */
        init: function(properties) {
            game.merge(this, properties);
        },

        render: function() {

        },

        update: function() {

        },

        remove: function() {

        }

    });


});