"use strict";

game.module('game.enemy.george.action.battle.attack')
.require('game.enemy.action.battle.attack')
.require('game.utility.audio_playlist')
.body(function() {
    /**
     * AttackBattleAction - General fight action
     */
    game.createClass('GeorgeAttackEnemyBattleAction', 'AttackEnemyBattleAction', {
        name: 'Attack',
        code: 'attack',

        prepare : function() {
            this.setState(this.states.PREPARED);
        },
        update : function() {

        },

        run: function(){
            this._super();
            var self = this;
            this.setState(this.states.RUNNING);
            var music = new game.AudioPlaylist(['sword-unsheathe', 'swing3', 'swing3', 'swing3', 'swing3']);

            var actor = game.battle.getCurrentActor();
            var target = game.battle.getRandomHero();

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