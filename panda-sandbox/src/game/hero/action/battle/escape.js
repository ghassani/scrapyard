"use strict";

game.module('game.hero.action.battle.escape')
.require('game.hero.action.battle.abstract')
.body(function() {
    /**
     * EscapeBattleAction - Generalescape action
     */
    game.createClass('EscapeBattleAction', 'AbstractHeroBattleAction', {
        name: 'Escape',
        id: 'escape'
    });

});