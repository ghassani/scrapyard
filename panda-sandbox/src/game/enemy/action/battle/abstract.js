"use strict";

game.module('game.enemy.action.battle.abstract')
.require('game.battle.action')
.body(function() {
    /**
     * AbstractEnemyBattleAction - Abstract Enemy Battle Action Class
     */
    game.createClass('AbstractEnemyBattleAction', 'BattleAction', {
        name: 'EnemyBattleAction',
        id: 'enemy_battle_action_id'
    });

});