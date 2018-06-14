"use strict";

game.module('game.hero.class.warrior.action.battle.escape')
.require(
    'game.hero.action.battle.escape',
    'game.utility.audio_playlist'
)
.body(function() {
    /**
     * WarriorEscapeBattleAction - Warrior escape action
     */
    game.createClass('WarriorEscapeBattleAction', 'EscapeBattleAction', {
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