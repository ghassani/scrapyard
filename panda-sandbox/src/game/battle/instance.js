"use strict";

game.module('game.battle.instance')
.require('game.utility.audio_playlist')
.body(function() {
    /**
     * BattleInstance - Handles actions related to battle
     */
    game.createClass('BattleInstance', 'Class', {

        state : 0,

        heroes : [],

        enemies : [],

        current_actor : null,

        current_action : null,

        turn_queue : new Array(),

        action_queue : new Array(),

        /**
         * The states of the current battle
         */
        states : {
            NONE: 0,
            ACTION_SELECTION: 1, // waiting for a user action selection, idle
            SUB_ACTION_SELECTION: 2, // waiting for a sub action selection (from tech, or magic), idle
            ENEMY_TARGET_SELECTION: 3, // awaiting enemy selection from action or other, idle
            HERO_TARGET_SELECTION: 4, // awaiting enemy selection from action or other, idle
            ACTION_PROCESSING: 5, // currently processing an action
            ENEMY_ACTION_PROCESSING: 6 // currently processing an enemy action
        },

        /**
         * constructor
         *
         * @param Heros heros
         * @param Enemies enemies
         * @param object properties
         */
        init: function(heroes, enemies, properties) {
            game.merge(this, properties);

            this.heroes  = heroes;
            this.enemies = enemies;
            this.state   = this.states.NONE;
            this.nextTurn();

        },


        /**
         * nextTurn
         *
         * initiate the next actors turn, ending the current actors turn
         */
        nextTurn: function() {
            if (!this.turn_queue.length){
                this.buildTurnQueue();
            }

            this.setCurrentActor(this.turn_queue.shift());

            this.clearRunningAction();
            this.action_queue = new Array();

            return this;
        },

        /**
         * buildTurnQueue
         *
         * construct the order in which actors will get to act
         */
        buildTurnQueue: function() {
            var self = this;

            this.heroes.forEach(function(hero, i){
                self.turn_queue.push(hero);
            });

            this.enemies.forEach(function(enemy, i){
                self.turn_queue.push(enemy);
            });
        },

        /**
         * queueAction
         *
         * @param action
         */
        queueAction: function(action) {
            this.action_queue.push(action);
        },

        /**
         *
         * @returns {boolean}
         */
        isActionRunning : function() {
            if (this.current_action == null) {
                return false;
            }

            return this.current_action.isRunning();
        },

        /**
         *
         * @returns {boolean}
         */
        isActionPreparing : function() {
            if (this.current_action == null) {
                return false;
            }

            return this.current_action.isPreparing();
        },

        /**
         *
         * @returns {*}
         */
        isActionPrepared : function() {
            if (this.current_action == null) {
                return false;
            }

            return this.current_action.isPrepared();
        },

        /**
         *
         * @returns {boolean}
         */
        isActionComplete : function() {
            if (this.current_action == null) {
                return true;
            }

            return this.current_action.isComplete();
        },

        /**
         *
         * @returns {boolean}
         */
        isActionInitialized : function() {
            if (this.current_action == null) {
                return false;
            }

            return this.current_action.isInitialized();
        },

        /**
         *
         * @param action
         * @returns {BattleInstance}
         */
        setCurrentAction : function(action) {
            this.current_action = action;
            return this;
        },

        /**
         *
         * @returns {BattleInstance}
         */
        clearRunningAction : function() {

            if (this.current_action != null) {
                this.current_action.reset();
            }
            this.current_action = null;
            return this;
        },

        /**
         *
         * @returns {*}
         */
        getRandomEnemy : function() {
            var enemies = this.enemies.all();
            var randomIndex = this.getRandomNumber(0, enemies.length - 1);
            return enemies[randomIndex];
        },

        /**
         *
         * @returns {*}
         */
        getRandomHero : function() {
            var heroes = this.heroes.all();
            var randomIndex = this.getRandomNumber(0, heroes.length - 1);
            return heroes[randomIndex];
        },

        /**
         *
         * @param min
         * @param max
         * @returns {number}
         */
        getRandomNumber : function(min, max) {
            min = parseInt(min);
            max = parseInt(max);
            return Math.floor(Math.random() * (max - min + 1)) + min;
        },

        /**
         * setCurrentActor
         *
         * Sets the current actor
         *
         * @param actor
         */
        setCurrentActor: function(actor) {
            this.current_actor = actor;
            this.heroes.forEach(function(hero, i){
               if (actor.actor_type == game.actor_types.HERO && hero.id == actor.id) {
                   if (typeof hero.hero_status_box_name_text != 'undefined') {
                       hero.hero_status_box_name_text.setStyle({ font: '18px Arial', fill: "#000000" });
                   }
               } else {
                   if (typeof hero.hero_status_box_name_text != 'undefined') {
                       hero.hero_status_box_name_text.setStyle({ font: '18px Arial', fill: "#FFFFFF" });
                   }
               }
            });

            return this;
        },

        /**
         * getCurrentActor
         *
         * Gets the current actor
         *
         * @returns Hero or Enemy
         */
        getCurrentActor: function() {
            return this.current_actor;
        }
    });


});