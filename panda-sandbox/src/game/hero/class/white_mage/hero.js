"use strict";

game.module('game.hero.class.white_mage.hero')
.require(
    'game.hero.abstract',
    'game.hero.class.white_mage.action.battle.attack',
    'game.hero.class.white_mage.action.battle.tech',
    'game.hero.class.white_mage.action.battle.magic',
    'game.hero.class.white_mage.action.battle.defend',
    'game.hero.class.white_mage.action.battle.escape',
    'game.hero.class.white_mage.action.battle.magic.heal'
)
.body(function() {
    /**
     * White Mage - Playable Character
     */
    game.createClass('WhiteMageHero', 'AbstractHero', {
        default_texture : 'default_white_mage',
        level : 1,
        id : 'white_mage_1',
        name : 'White Mage',
        class : 'white_mage',

        body_size : {
            width: 72,
            height: 120
        },

        battle_actions : [
            new game.WhiteMageAttackBattleAction(),
            new game.WhiteMageTechBattleAction(),
            new game.WhiteMageMagicBattleAction(),
            new game.WhiteMageDefendBattleAction(),
            new game.WhiteMageEscapeBattleAction()
        ],

        magic : [
          new game.WhiteMageMagicHealBattleAction()
        ],

        init: function(texture, x, y, properties) {
            this._super(texture, x, y, properties);
        }
    });

});