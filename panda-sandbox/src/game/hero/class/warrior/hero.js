"use strict";
game.module('game.hero.class.warrior.hero')
.require(
    'game.hero.abstract',
    'game.hero.class.warrior.action.battle.attack',
    'game.hero.class.warrior.action.battle.tech',
    'game.hero.class.warrior.action.battle.defend',
    'game.hero.class.warrior.action.battle.escape'
)
.body(function() {
    /**
     * Warrior - Playable Character
     */
    game.createClass('WarriorHero', 'AbstractHero', {
        id : 'warrior_1',
        name : 'Warrior',
        level : 1,
        class : 'warrior',
        default_texture : 'default_warrior',

        body_size : {
            width: 69,
            height: 104
        },

        battle_actions : [
            new game.WarriorAttackBattleAction(),
            new game.WarriorTechBattleAction(),
            new game.WarriorDefendBattleAction(),
            new game.WarriorEscapeBattleAction()
        ],

        init: function(texture, x, y, properties) {
            this._super(texture, x, y, properties);
            var self = this;
        }
    });

});