"use strict";

game.module('game.battle.menu.hero_action')
.require('game.battle.menu.abstract')
.body(function() {
    /**
     * HeroActionMenu - A hero's main action menu
     */
    game.createClass('HeroActionMenu', 'AbstractMenu', {

        hero: null,

        parent: new game.Graphics().beginFill(0x000000, 0.5).drawRect(0, 0, 250, 210).endFill(),

        list: new game.Graphics().beginFill(0x000000, 0).drawRect(0, 0, 250, 210).endFill(),

        position: {
            x : 10,
            y : 550
        },

        /**
         * constructor
         *
         * @param object properties
         */
        init: function(hero, properties) {
            this._super(properties);
            this.hero = hero;
        },

        render: function() {
            var self = this;
            this.hero.getBattleActions().forEach(function(action, i){
                var box = new game.Graphics().beginFill(0x000000, 0).drawRect(0, 0, 250, 25).endFill();

                box.text = new game.Text(action.getName(), { font: '18px Arial', fill: "#ffffff" });

                box.position.set(10, 10 + (i * 25));
                box.hitArea = new game.PIXI.Rectangle(0, 0, 250, 25);
                box.interactive = true;

                box.action = action;

                box.click = function(){
                    if (!game.battle.isActionRunning()) {
                        game.battle.queueAction(this.action);
                        game.logger.log('Queueing Action. Count: ' + game.battle.action_queue.length);
                    } else {
                        var music = new game.AudioPlaylist(['error']);
                    }

                };

                box.mouseover = function(){
                    box.text.setStyle({ font: '18px Arial', fill: "#000000" });
                };

                box.mouseout = function(){
                    box.text.setStyle({ font: '18px Arial', fill: "#ffffff" });
                };

                box.addChild(box.text);

                self.list.addChild(box);
            });

            this.parent.addChild(this.list);
            this.parent.position.set(this.position.x, this.position.y);
            this.parent.addTo(game.scene.stage);
            game.scene.addObject(this);
        },

        update: function() {
            this._super();
            if(this.hero.id != game.battle.getCurrentActor().id) {
                this.remove();
            }
        },

        remove: function() {
            this._super();
            this.list.children.forEach(function(box){
                box.children.forEach(function(text){
                    text.remove();
                });
                box.remove();
            });
            this.list.remove();
            this.parent.remove();
            game.scene.removeObject(this);
            game.scene.hero_action_menu = null;
        }

    });


});