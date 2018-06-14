"use strict";

game.module('game.hero.class.white_mage.action.battle.tech')
.require(
    'game.hero.action.battle.tech',
    'game.utility.audio_playlist'
).body(function() {
    /**
     * WhiteMageTechBattleAction - White Mage tech action
     */
    game.createClass('WhiteMageTechBattleAction', 'TechBattleAction', {
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