"use strict";

game.module('game.hero.class.black_mage.hero')
.require(
    'game.hero.abstract',
    'game.hero.class.black_mage.action.battle.attack',
    'game.hero.class.black_mage.action.battle.tech',
    'game.hero.class.black_mage.action.battle.magic',
    'game.hero.class.black_mage.action.battle.defend',
    'game.hero.class.black_mage.action.battle.escape'
)
.body(function() {
    /**
     * Black Mage - Warrior Playable Character
     */
    game.createClass('BlackMageHero', 'AbstractHero', {
        default_texture : 'default_black_mage',
        level : 1,
        id : 'black_mage_1',
        name : 'Black Mage',
        class : 'black_mage',

        body_size : {
            width: 60,
            height: 119
        },

        battle_actions : [
            new game.BlackMageAttackBattleAction(),
            new game.BlackMageTechBattleAction(),
            new game.BlackMageMagicBattleAction(),
            new game.BlackMageDefendBattleAction(),
            new game.BlackMageEscapeBattleAction()
        ],

        init: function(texture, x, y, properties) {
            this._super(texture, x, y, properties);
        }
    });

});