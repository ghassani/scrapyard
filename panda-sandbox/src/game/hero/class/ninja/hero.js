"use strict";

game.module('game.hero.class.ninja.hero')
.require(
    'game.hero.abstract',
    'game.hero.class.ninja.action.battle.attack',
    'game.hero.class.ninja.action.battle.tech',
    'game.hero.class.ninja.action.battle.defend',
    'game.hero.class.ninja.action.battle.escape'
)
.body(function() {
    /**
     * Ninja - Playable Character
     */
    game.createClass('NinjaHero', 'AbstractHero', {
        default_texture : 'default_ninja',
        level : 1,
        id : 'ninja_1',
        name : 'Ninja',
        class : 'ninja',
        body_size : {
            width: 99,
            height: 106
        },
        battle_actions : [
            new game.NinjaAttackBattleAction(),
            new game.NinjaTechBattleAction(),
            new game.NinjaDefendBattleAction(),
            new game.NinjaEscapeBattleAction()
        ],
        init: function(texture, x, y, properties) {
            this._super(texture, x, y, properties);
        }
    });

});