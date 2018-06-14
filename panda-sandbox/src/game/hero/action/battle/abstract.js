"use strict";

game.module('game.hero.action.battle.abstract')
.require('game.battle.action')
.body(function() {
    /**
     * AbstractHeroBattleAction - Abstract Hero Battle Action Class
     */
    game.createClass('AbstractHeroBattleAction', 'BattleAction', {
        name: 'BattleAction',
        id: 'battle_action_id'
    });

});