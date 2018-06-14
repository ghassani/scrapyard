"use strict";

game.module('game.hero.class.black_mage.action.battle.magic')
.require(
    'game.hero.action.battle.magic',
    'game.utility.audio_playlist'
)
.body(function() {
    /**
     * BlackMageMagicBattleAction - Black Mages magic action
     */
    game.createClass('BlackMageMagicBattleAction', 'MagicBattleAction', {
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