"use strict";

game.module('game.enemy.action.battle.defend')
.require('game.enemy.action.battle.abstract')
.body(function() {
    /**
     * DefendBattleAction - General defend action
     */
    game.createClass('DefendEnemyBattleAction', 'AbstractEnemyBattleAction', {
        name: 'Defend',
        id: 'defend'
    });

});