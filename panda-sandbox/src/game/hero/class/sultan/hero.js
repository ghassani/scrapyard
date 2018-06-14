"use strict";

game.module('game.hero.class.sultan.hero')
    .require('game.hero.abstract')
    .body(function() {
        /**
         * Sultan - Playable Character
         */
        game.createClass('SultanHero', 'AbstractHero', {
            default_texture : 'default_ninja',
            level : 1,
            id : 'sultan_1',
            name : 'Sultan',
            class : 'sultan',
            body_size : {
                width: 32,
                height: 111
            },
            init: function(texture, x, y, properties) {
                this._super(texture, x, y, properties);
            }
        });

    });