"use strict";

game.module('game.enemy.action.battle.tech')
.require('game.enemy.action.battle.abstract')
.body(function() {
    /**
     * TechBattleAction - General tech action
     */
    game.createClass('TechEnemyBattleAction', 'AbstractEnemyBattleAction', {
        name: 'Tech',
        id: 'tech'
    });

});