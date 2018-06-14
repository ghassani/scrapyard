"use strict";

game.module('game.hero.class.white_mage.action.battle.magic')
.require(
    'game.hero.action.battle.magic',
    'game.utility.audio_playlist',
    'game.battle.menu.hero_magic_action',
    'game.battle.hero_selection'
).body(function() {
    /**
     * WhiteMageMagicBattleAction - White Mages magic action
     */
    game.createClass('WhiteMageMagicBattleAction', 'MagicBattleAction', {

        spell : null,

        spell_selection : null,

        target : null,

        target_selection : null,

        prepare : function() {
            this.setState(this.states.PREPARING);

            this.spell_selection = new game.HeroMagicActionMenu(game.battle.getCurrentActor(), {
                parent_action : this
            });

            this.spell_selection.render();
        },
        update : function() {
            if (this.spell != null && this.target == null && this.target_selection == null) {
                this.target_selection = new game.BattleHeroSelection({

                });

                this.target_selection.render();
            }


            if (this.spell != null && this.target != null) {
                this.setState(this.states.PREPARED);

                this.spell_selection.remove();
                this.target_selection.remove();
            }
        },
        run: function(){
            this._super();
            this.setState(this.states.RUNNING);
            this.spell.run();
        }


    });

});