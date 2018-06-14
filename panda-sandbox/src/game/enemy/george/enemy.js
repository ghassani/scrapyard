"use strict";

game.module('game.enemy.george.enemy')
.require(
    'game.enemy.abstract',
    'game.enemy.george.action.battle.attack'
)
.body(function() {
    /**
     * GeorgeEnemy - George Boss Enemy NPC Character
     */
    game.createClass('GeorgeEnemy', 'AbstractEnemy', {
        default_texture : 'default_george',
        level : 1,
        class : '',
        isBoss : true,
        battle_state : {
            max_hp  : 100,
            hp      : 100,
            max_mp  : 10,
            mp      : 10,
            bonuses : [],
            conditions : []
        },
        stats : {
            strength:      1,
            intelligence:  1,
            dexterity:     1,
            vitality:      1
        },
        battle_actions : [
          new game.GeorgeAttackEnemyBattleAction()
        ],

        /**
         * var sprite - Holds a game.Sprite object
         */
        sprite : null,

        /**
         *
         * @param string sprite_image
         * @param float startX
         * @param float startY
         * @param object settings
         */
        init: function(sprite_image, startX, startY, settings) {
            this.body = new game.Body({
                position: {x: startX, y: startY},
                velocityLimit: {x: 250, y: 250},
                velocity: {x: 0, y: 0 },
                collisionGroup: 1,
                collideAgainst: 0,
                mass: 0
            });

            this.body.addShape(new game.Rectangle(221, 258));
            this._super(sprite_image, startX, startY, settings);
        },

        /**
         *
         * @returns {*}
         */
        getStats: function()
        {
            return this.stats;
        },
        /**
         *
         * @returns {*}
         */
        getBattleState : function()
        {
            return this.battle_state;
        },

        /**
         *
         * @returns {number}
         */
        getLevel : function()
        {
            return this.level;
        },

        /**
         *
         * @returns {string}
         */
        getClass : function() {
            return this.class;
        }

    });


});