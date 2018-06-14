"use strict";

game.module('game.hero.class.white_mage.action.battle.escape')
.require(
    'game.hero.action.battle.escape',
    'game.utility.audio_playlist'
).body(function() {
    /**
     * WhiteMageEscapeBattleAction - White Mage escape action
     */
    game.createClass('WhiteMageEscapeBattleAction', 'EscapeBattleAction', {
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