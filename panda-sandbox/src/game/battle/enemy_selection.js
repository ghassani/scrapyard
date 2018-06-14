"use strict";

game.module('game.battle.enemy_selection')
.body(function() {

        /**
         * BattleEnemySelection
         */
        game.createClass('BattleEnemySelection', 'Class', {

            selection : null,

            init : function(properties) {
                game.merge(this, properties);

                game.logger.log('New BattleEnemySelection');

            },

            render : function() {
                var self = this;


                game.scene.large_notification_bar_text.setText('Select an Enemy');

                game.battle.enemies.forEach(function(enemy, i){
                    if (typeof enemy.sprite.click == 'function') {
                        enemy.sprite.old_click = enemy.sprite.click;
                    }

                    if (typeof enemy.sprite.mouseover == 'function') {
                        enemy.sprite.old_mouseover = enemy.sprite.mouseover;
                    }

                    if (typeof enemy.sprite.mouseout == 'function') {
                        enemy.sprite.old_mouseout = enemy.sprite.mouseout;
                    }

                    enemy.sprite.click = function(e) {
                        game.scene.large_notification_bar_text.setText('');
                        self.setSelection(enemy);
                    }

                    enemy.sprite.mouseover = function(e) {
                        if (typeof this.old_mouseover == 'function') {
                            this.old_mouseover(e);
                        }
                        enemy.sprite.target_icon = new game.Sprite('selection_mouseover_icon', enemy.sprite.position.x + (enemy.sprite.width / 2), enemy.sprite.position.y - 25);
                        enemy.sprite.target_icon.addTo(game.scene.stage);
                    }

                    enemy.sprite.mouseout = function(e) {
                        if (typeof this.old_mouseout == 'function') {
                            this.old_mouseout(e);
                        }
                        enemy.sprite.target_icon.remove();
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
                game.logger.log('Removing Enemy Selection');

                game.scene.removeObject(this);

                game.battle.enemies.forEach(function(enemy, i) {
                    if (typeof enemy.sprite.old_click == 'function') {
                        game.logger.log('Restoring Original Click Event');
                        enemy.sprite.click = enemy.sprite.old_click;
                        enemy.sprite.old_click = null;
                    }

                    if (typeof enemy.sprite.old_mouseover == 'function') {
                        game.logger.log('Restoring Original Mouseover Event');
                        enemy.sprite.mouseover = enemy.sprite.old_mouseover;
                        enemy.sprite.old_mouseover = null;
                    }

                    if (typeof enemy.sprite.old_mouseout == 'function') {
                        game.logger.log('Restoring Original Mouseout Event');
                        enemy.sprite.mouseout = enemy.sprite.old_mouseout;
                        enemy.sprite.old_mouseout = null;
                    }

                    if (typeof enemy.sprite.target_icon != 'undefined') {
                        enemy.sprite.target_icon.remove();
                    }
                });
            },

            update : function() {

                if (this.getSelection() != null) {
                    game.logger.log('Enemy Selected. Removing Object');
                    this.remove();
                } else {
                    game.logger.log('Awaiting Enemy Selection');
                }
            }
        });


    });