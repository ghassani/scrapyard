"use strict";

game.module('game.hero.class.ninja.action.battle.tech')
.require(
    'game.hero.action.battle.tech',
    'game.utility.audio_playlist'
)
.body(function() {
    /**
     * NinjaTechBattleAction - Ninja tech action
     */
    game.createClass('NinjaTechBattleAction', 'TechBattleAction', {
        prepare : function() {
            this.setState(this.states.PREPARED);
        },
        update : function() {

        },
        run: function(){
            this._super();

            game.scene.large_notification_bar_text.setText('Not Yet Available');

            var music = new game.AudioPlaylist(['error']);

            this.setState(this.states.COMPLETE);

            game.battle.clearRunningAction();

            setTimeout(function(){
                game.scene.large_notification_bar_text.setText('');
            }, 750);
        }


    });

});