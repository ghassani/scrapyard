"use strict";

game.module('game.hero.class.warrior.action.battle.defend')
.require(
    'game.hero.action.battle.defend',
    'game.utility.audio_playlist'
)
.body(function() {
    /**
     * WarriorDefendBattleAction - Warrior defend action
     */
    game.createClass('WarriorDefendBattleAction', 'DefendBattleAction', {
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