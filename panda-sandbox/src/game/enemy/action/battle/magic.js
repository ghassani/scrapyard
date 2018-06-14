"use strict";

game.module('game.enemy.action.battle.magic')
.require('game.enemy.action.battle.abstract')
.body(function() {
    /**
     * MagicBattleAction - General magic action
     */
    game.createClass('MagicEnemyBattleAction', 'AbstractEnemyBattleAction', {
        name: 'Magic',
        id: 'magic',

    });

});