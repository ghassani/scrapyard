"use strict";

game.module('game.hero.action.battle.magic')
.require('game.hero.action.battle.abstract')
.body(function() {
    /**
     * MagicBattleAction - General magic action
     */
    game.createClass('MagicBattleAction', 'AbstractHeroBattleAction', {
        name: 'Magic',
        id: 'magic'
    });

});