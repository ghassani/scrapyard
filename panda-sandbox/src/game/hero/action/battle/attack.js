"use strict";

game.module('game.hero.action.battle.attack')
.require('game.hero.action.battle.abstract')
.require('game.utility.audio_playlist')
.body(function() {
    /**
     * AttackBattleAction - General fight action
     */
    game.createClass('AttackBattleAction', 'AbstractHeroBattleAction', {
        name: 'Attack',
        id: 'attack'


    });

});