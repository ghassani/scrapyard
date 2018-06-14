"use strict";
game.module('game.hero.class.white_mage.action.battle.magic.heal')
.require(
'game.battle.action',
'game.utility.audio_playlist'
)
.body(function() {
    /**
     * WhiteMageAttackBattleAction - Warrior attack action
     */
    game.createClass('WhiteMageMagicHealBattleAction', 'BattleAction', {
        name: 'Heal',
        description : 'Heals a hero for a small amount of HP',
        id: 'heal',

        prepare : function() {
            this.setState(this.states.PREPARING);

            game.battle.queueAction(this);
            this.hero_selection = new game.BattleHeroSelection({

            });

            this.hero_selection.render();
        },
        update : function() {
            if (typeof this.hero_selection == 'undefined') {
                return;
            }

            if (this.hero_selection.getSelection()) {
                this.setState(this.states.PREPARED);
                this.target = this.hero_selection.getSelection();
                this.hero_selection.remove();
            }
        },
        run: function(){
            this._super();
            var self = this;

            this.setState(this.states.RUNNING);

            var actor = game.battle.getCurrentActor();
            var target = this.target;

            console.log('HEAL NIGGA');

            self.setState(self.states.COMPLETE);
            game.battle.current_action.setState(self.states.COMPLETE);
            game.battle.nextTurn();
        }

    });

});