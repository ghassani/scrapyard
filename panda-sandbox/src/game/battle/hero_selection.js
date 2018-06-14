"use strict";

game.module('game.battle.hero_selection')
.body(function() {

    /**
     * BattleHeroSelection
     */
    game.createClass('BattleHeroSelection', 'Class', {

        selection : null,

        init : function(properties) {
            game.merge(this, properties);

            game.logger.log('New BattleHeroSelection');

        },

        render : function() {
            var self = this;


            game.scene.large_notification_bar_text.setText('Select a Hero');

            game.battle.heroes.forEach(function(hero, i){
                if (typeof hero.sprite.click == 'function') {
                    hero.sprite.old_click = hero.sprite.click;
                }

                if (typeof hero.sprite.mouseover == 'function') {
                    hero.sprite.old_mouseover = hero.sprite.mouseover;
                }

                if (typeof hero.sprite.mouseout == 'function') {
                    hero.sprite.old_mouseout = hero.sprite.mouseout;
                }

                hero.sprite.click = function(e) {
                    game.scene.large_notification_bar_text.setText('');
                    game.battle.current_action.target = this.hero;
                    self.setSelection(hero);
                }

                hero.sprite.mouseover = function(e) {
                    if (typeof this.old_mouseover == 'function') {
                        this.old_mouseover(e);
                    }
                    hero.sprite.target_icon = new game.Sprite('selection_mouseover_icon', hero.sprite.position.x + (hero.sprite.width / 2), hero.sprite.position.y - 25);
                    hero.sprite.target_icon.addTo(game.scene.stage);
                }

                hero.sprite.mouseout = function(e) {
                    if (typeof this.old_mouseout == 'function') {
                        this.old_mouseout(e);
                    }
                    hero.sprite.target_icon.remove();
                }
            });

            game.scene.addObject(this);
        },

        getSelection : function() {
            return this.selection;
        },

        setSelection : function(selection) {
            this.selection = selection;
        },

        remove : function() {
            game.logger.log('Removing Hero Selection');

            game.scene.removeObject(this);

            game.battle.enemies.forEach(function(hero, i) {
                if (typeof hero.sprite.old_click == 'function') {
                    game.logger.log('Restoring Original Click Event');
                    hero.sprite.click = hero.sprite.old_click;
                    hero.sprite.old_click = null;
                }

                if (typeof hero.sprite.old_mouseover == 'function') {
                    game.logger.log('Restoring Original Mouseover Event');
                    hero.sprite.mouseover = hero.sprite.old_mouseover;
                    hero.sprite.old_mouseover = null;
                }

                if (typeof hero.sprite.old_mouseout == 'function') {
                    game.logger.log('Restoring Original Mouseout Event');
                    hero.sprite.mouseout = hero.sprite.old_mouseout;
                    hero.sprite.old_mouseout = null;
                }

                if (typeof hero.sprite.target_icon != 'undefined') {
                    hero.sprite.target_icon.remove();
                }
            });
        },

        update : function() {

            if (this.getSelection() != null) {
                game.logger.log('Hero Selected. Removing Object');
                this.remove();
            } else {
                game.logger.log('Awaiting Hero Selection');
            }
        }
    });


});