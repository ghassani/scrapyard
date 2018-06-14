"use strict";
game.module('game.hero.class.thief.hero')
    .require('game.hero.abstract')
    .body(function() {
        /**
         * Tief - Playable Character
         */
        game.createClass('ThiefHero', 'AbstractHero', {
            default_texture : 'default_warrior',
            level : 1,
            id : 'theif_1',
            name : 'Theif',
            class : 'theif',
            body_size : {
                width: 32,
                height: 111
            },

            init: function(texture, x, y, properties) {
                this._super(texture, x, y, properties);
            }
        });

    });