"use strict";

game.module('game.enemy.action.battle.escape')
.require('game.enemy.action.battle.abstract')
.body(function() {
    /**
     * EscapeBattleAction - Generalescape action
     */
    game.createClass('EscapeEnemyBattleAction', 'AbstractEnemyBattleAction', {
        name: 'Escape',
        id: 'escape'
    });

});