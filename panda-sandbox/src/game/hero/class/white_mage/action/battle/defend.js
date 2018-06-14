"use strict";

game.module('game.hero.class.white_mage.action.battle.defend')
.require(
    'game.hero.action.battle.defend',
    'game.utility.audio_playlist'
).body(function() {
    /**
     * WhiteMageDefendBattleAction - White Mage defend action
     */
    game.createClass('WhiteMageDefendBattleAction', 'DefendBattleAction', {
        prepare : function() {
            this.setState(this.states.PREPARED);
        },
        update : function() {

        },
        run: function(){
            this._super();
            var music = new game.AudioPlaylist(['error']);
            game.battle.clearRunningAction();
        }


    });

});