"use strict";

game.module('game.hero.action.battle.tech')
.require('game.hero.action.battle.abstract')
.body(function() {
    /**
     * TechBattleAction - General tech action
     */
    game.createClass('TechBattleAction', 'AbstractHeroBattleAction', {
        name: 'Tech',
        id: 'tech'
    });

});