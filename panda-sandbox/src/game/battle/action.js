"use strict";

game.module('game.battle.action')
.body(function() {
    /**
     * BattleAction - Battle Action Class
     */
    game.createClass('BattleAction', {
        name: 'BattleAction',
        id: 'battle_action_id',
        description : 'Action Description',

        states : {
            INITIALIZED : 0,
            PREPARING   : 1,
            PREPARED    : 2,
            RUNNING     : 3,
            COMPLETE    : 4
        },

        state : 0,

        /**
         *
         */
        init: function(properties) {
            game.merge(this, properties);

            this.state = this.states.INITIALIZED;
        },

        getName: function() {
            return this.name;
        },

        getId: function() {
            return this.id;
        },

        update : function() {
            // update function to determine ready
            // when ready, it will be run
            //this.ready = true;
            //game.battle.queueAction(this);
        },
        run: function(){

        },

        isRunning : function() {
            return this.state == this.states.RUNNING;
        },

        isPreparing : function() {
            return this.state == this.states.PREPARING;
        },

        isPrepared : function() {
            return this.state == this.states.PREPARED;
        },

        isComplete : function() {
            return this.state == this.states.COMPLETE;
        },

        isInitialized : function() {
            return this.state == this.states.INITIALIZED;
        },

        setState : function(state) {
            game.logger.log('Updating Action State To : ' + state);
            this.state = state;
            return this;
        },
        getState : function() {
            return this.state;
        },
        reset : function() {
            this.setState(this.states.INITIALIZED);
        }

    });

});