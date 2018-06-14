"use strict";

game.module('game.hero.class.black_mage.action.battle.tech')
.require(
    'game.hero.action.battle.tech',
    'game.utility.audio_playlist'
)
.body(function() {
    /**
     * BlackMageTechBattleAction - Black Mage tech action
     */
    game.createClass('BlackMageTechBattleAction', 'TechBattleAction', {
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