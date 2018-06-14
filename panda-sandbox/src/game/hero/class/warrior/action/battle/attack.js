"use strict";
game.module('game.hero.class.warrior.action.battle.attack')
.require(
    'game.hero.action.battle.attack',
    'game.utility.audio_playlist',
    'game.battle.enemy_selection'
).body(function() {
    /**
     * WarriorAttackBattleAction - Warrior attack action
     */
    game.createClass('WarriorAttackBattleAction', 'AttackBattleAction', {
        prepare : function() {
            this.setState(this.states.PREPARING);

            game.battle.queueAction(this);
            this.enemy_selection = new game.BattleEnemySelection({

            });

            this.enemy_selection.render();
        },
        update : function() {
            if (typeof this.enemy_selection == 'undefined') {
                return;
            }

            if (this.enemy_selection.getSelection()) {
                this.setState(this.states.PREPARED);
                this.target = this.enemy_selection.getSelection();
                this.enemy_selection.remove();
            }
        },
        run: function(){
            this._super();
            var self = this;

            this.setState(this.states.RUNNING);

             var music = new game.AudioPlaylist(['sword-unsheathe', 'swing3', 'swing3', 'swing3']);

             var actor = game.battle.getCurrentActor();
             var target = this.target;

             var originalPosition = actor.body.position.clone();
             var tween = new game.Tween(actor.body.position).to(target.body.position, 1000).onComplete(function(){
                 var backTween = new game.Tween(actor.body.position);
                 backTween.to(originalPosition, 1000).onComplete(function(){
                    self.setState(self.states.COMPLETE);
                    game.battle.nextTurn();
                 }).start();
             }).start();
        }

    });

});