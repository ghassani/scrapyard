"use strict";

game.module('game.enemy.action.battle.attack')
.require('game.enemy.action.battle.abstract')
.require('game.utility.audio_playlist')
.body(function() {
    /**
     * AttackBattleAction - General fight action
     */
    game.createClass('AttackEnemyBattleAction', 'AbstractEnemyBattleAction', {
        name: 'Attack',
        id: 'attack'
    });

});