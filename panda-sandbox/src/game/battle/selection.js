"use strict";

game.module('game.battle.selection')
    .body(function() {
        /**
         * BattleSelection - Draws selection options for a character action, either main or sub actions
         */
        game.createClass('BattleSelection', 'Class', {

            hero : null,

            wrapper : null,

            width : 250,

            height: 210,

            background : {
                color: 0x000000,
                opacity : 0.50
            },

            position : {
                x : 0,
                y : 0
            },

            /**
             * constructor
             *
             * @param array selections
             * @param object properties
             */
            init: function(hero, properties) {
                game.merge(this, properties);
                this.hero = hero;
                this.wrapper = new game.Graphics().beginFill(this.background.color, this.background.opacity).drawRect(0, 0, this.width, this.height).endFill();
                this.list    = new game.Graphics().beginFill(0x000000, 0).drawRect(0, 0, this.width, this.height).endFill();
            },

            build: function() {
                this.wrapper.position.set(this.position.x, this.position.y);
                this.list.position.set(0, 0);

                this.wrapper.addChild(this.list);
                game.scene.stage.addChild(this.wrapper);
                game.scene.addObject(this);

                this.buildSelections();
            },

            buildSelections : function() {
                this.hero.getBattleActions().forEach(function(action, i){
                    var action_box = new game.Graphics().beginFill(0x000000, 0).drawRect(0, 0, 250, 25).endFill();
                    var action_text = new game.Text(action.getName(), { font: '18px Arial', fill: "#ffffff" });

                    action_box.position.set(10, 10 + (i * 25));
                    action_box.hitArea = new game.PIXI.Rectangle(0, 0, 250, 25);
                    action_box.interactive = true;

                    action_box.action = action;

                    action_box.click = function(){
                        this.action.run();
                    };

                    action_box.mouseover = function(){
                        action_text.setStyle({ font: '18px Arial', fill: "#000000" });
                    };

                    action_box.mouseout = function(){
                        action_text.setStyle({ font: '18px Arial', fill: "#ffffff" });
                    };

                    action_box.addChild(action_text);

                    game.scene.action_menu.list.addChild(action_box);

                });
            },

            update: function() {

            },

            remove: function() {
                game.scene.removeObject(this);
                game.scene.stage.removeChild(this.list);
                game.scene.stage.removeChild(this.wrapper);
            },


            setSelections : function(selections) {
                this.selections = selections;
            },

            getSelections : function() {
                return this.selections;
            }


        });


    });