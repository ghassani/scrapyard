"use strict";

game.module('game.hero.action.battle.defend')
.require('game.hero.action.battle.abstract')
.body(function() {
    /**
     * DefendBattleAction - General defend action
     */
    game.createClass('DefendBattleAction', 'AbstractHeroBattleAction', {
        name: 'Defend',
        id: 'defend'

    });

});