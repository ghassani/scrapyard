"use strict";

game.module('game.hero.class.black_mage.action.battle.escape')
.require(
    'game.hero.action.battle.escape',
    'game.utility.audio_playlist'
).body(function() {
    /**
     * BlackMageEscapeBattleAction - White Mage escape action
     */
    game.createClass('BlackMageEscapeBattleAction', 'EscapeBattleAction', {
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