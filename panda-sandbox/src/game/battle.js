game.module('game.battle')
.require('game.battle.menu.hero_action')
.body(function() {

    game.createScene('Battle', {

        backgroundColor: 0x7ec0ee,
        hero_action_menu: null,
        notification_bar_wrapper: new game.Graphics().beginFill(0x3F6077, 1.0).drawRect(0, 0, 1000, 45).endFill(),
        notification_bar_text: new game.Text('', {font: '28px Arial', fill: "#ffffff"}),

        large_notification_bar_wrapper: new game.Graphics().beginFill(0x000000, 0.00).drawRect(0, 0, 500, 45).endFill(),
        large_notification_bar_text: new game.Text('Battle Start!', {font: '38px Arial', fill: "#ffffff"}),

        hero_list: new game.Graphics().beginFill(0x3F6077, 1.0).drawRect(0, 0, 720, 210).endFill(),

        init: function () {
            this.world = new game.World(0, 0);
            game.state = game.states.BATTLE;

            this.initializeAudio();
            this.renderInterface();
            this.renderHeroList();
            this.renderEnemies();
            this.renderActors();
        },

        /**
         * initializeAudio
         */
        initializeAudio: function () {
            this.music = game.audio.playMusic('battle-boss');
        },

        /**
         * renderInterface
         */
        renderInterface: function () {
            this.notification_bar_wrapper.position.set(10, 10);
            this.notification_bar_text.position.set(5, 5);
            this.notification_bar_wrapper.addChild(this.notification_bar_text);

            this.large_notification_bar_wrapper.position.set(500, 150);
            this.large_notification_bar_wrapper.addChild(this.large_notification_bar_text);

            this.hero_list.position.set(270, 550);

            this.stage.addChild(this.notification_bar_wrapper);
            this.stage.addChild(this.large_notification_bar_wrapper);
            this.stage.addChild(this.hero_list);

            setTimeout(function () {
                game.scene.large_notification_bar_text.setText('Get Em!!');
                setTimeout(function () {
                    game.scene.large_notification_bar_text.setText('');
                }, 1000);
            }, 2000);

        },

        /**
         * renderHeroList
         */
        renderHeroList: function () {

            game.battle.heroes.forEach(function (hero, i) {

                hero.hero_status_box = new game.Graphics().beginFill(0x000000, 0).drawRect(0, 0, 720, 25).endFill();

                hero.hero_status_box_name = new game.Graphics().beginFill(0xFFFFFF, 0).drawRect(0, 0, 120, 25).endFill();

                if (game.battle.current_actor != null && game.battle.current_actor.actor_type == game.actor_types.HERO && game.battle.current_actor.id == hero.id) {
                    hero.hero_status_box_name_text = new game.Text(hero.getName(), {
                        font: '18px Arial',
                        fill: "#000000"
                    });
                } else {
                    hero.hero_status_box_name_text = new game.Text(hero.getName(), {
                        font: '18px Arial',
                        fill: "#ffffff"
                    });
                }

                hero.hero_status_box_hp = new game.Graphics().beginFill(0x000000, 0).drawRect(0, 0, 250, 210).endFill();
                hero.hero_status_box_hp_text = new game.Text(hero.getBattleState().hp + '/' + hero.getBattleState().max_hp, {
                    font: '18px Arial',
                    fill: "#ffffff"
                });

                hero.hero_status_box_mp = new game.Graphics().beginFill(0x000000, 0).drawRect(0, 0, 250, 210).endFill();
                hero.hero_status_box_mp_text = new game.Text(hero.getBattleState().mp + '/' + hero.getBattleState().max_mp, {
                    font: '18px Arial',
                    fill: "#ffffff"
                });
                //720, 210

                hero.hero_status_box.position.set(10, 10 + (i * 25));

                hero.hero_status_box_hp.position.set(150, 0);
                hero.hero_status_box_mp.position.set(300, 0);

                hero.hero_status_box.addChild(hero.hero_status_box_name);
                hero.hero_status_box_name.addChild(hero.hero_status_box_name_text);

                hero.hero_status_box.addChild(hero.hero_status_box_hp);
                hero.hero_status_box_hp.addChild(hero.hero_status_box_hp_text);

                hero.hero_status_box.addChild(hero.hero_status_box_mp);
                hero.hero_status_box_mp.addChild(hero.hero_status_box_mp_text);

                game.scene.hero_list.addChild(hero.hero_status_box);

            });
        },

        renderActors: function () {
            game.battle.heroes.forEach(function (hero, i) {
                var startX = 923;
                var startY = (100 + (i * 100));

                hero.setPosition(startX, startY);
                //hero.setAnchor(startX, startY);

                game.scene.world.addBody(hero.body);

                game.scene.addObject(hero);

                hero.sprite.mouseover = function () {
                    game.scene.notification_bar_text.setText(hero.getName());
                };

                hero.sprite.mouseout = function () {
                    game.scene.notification_bar_text.setText('');
                };

                hero.addTo(game.scene.stage);

            });
        },

        renderEnemies: function () {
            game.battle.enemies.forEach(function (enemy, i) {

                var startX = 50 + (i * 150);
                var startY = 50 + (i * 150);

                enemy.setPosition(startX, startY);
                //enemy.setAnchor(startX, startY);

                game.scene.world.addBody(enemy.body);

                game.scene.addObject(enemy);

                enemy.sprite.mouseover = function () {
                    game.scene.notification_bar_text.setText(enemy.getName());
                };

                enemy.sprite.mouseout = function () {
                    game.scene.notification_bar_text.setText('');
                };

                enemy.addTo(game.scene.stage);

            });
        },

        keydown: function (key) {

        },

        keyup: function (key) {

        },

        /*mousedown: function (e) {
            console.log(e);
        },
*/
        mouseup : function (e) {
            console.log(e.swipeX, e.swipeY);
        },

        remove: function() {
            game.scene.removeObject(this);
        },

        update: function() {
            this._super();

            if (game.battle.enemies.length == 0 ) {
                game.logger.log('Enemies Defeated, Switching Back To Main');
                game.system.setScene('Main');
            }

            if (this.hero_action_menu == null && game.battle.getCurrentActor().actor_type == game.actor_types.HERO) {
                game.logger.log('Creating Action Menu Selection for New Actor');

                this.hero_action_menu = new game.HeroActionMenu(game.battle.getCurrentActor(), {
                    position : {
                        x : 10,
                        y : 550
                    }
                });

                setTimeout(function(){
                    game.scene.hero_action_menu.render();
                }, 100);
            }

            if (game.battle.getCurrentActor().actor_type == game.actor_types.ENEMY && null == game.battle.current_action) {
                game.logger.log('Queueing Enemy AI Action');
                var actions = game.battle.getCurrentActor().getBattleActions();
                game.battle.queueAction(actions[0]);

            }

            if (game.battle.action_queue.length && game.battle.current_action == null) {
                game.logger.log('Preparing New Action');
                game.battle.setCurrentAction( game.battle.action_queue.shift());
                game.battle.current_action.prepare();
                return;
            }

            if (game.battle.current_action != null && true == game.battle.isActionComplete()) {
                game.logger.log('Clearing Completed Action');
                game.battle.clearRunningAction();
                return;
            }

            if (game.battle.current_action != null && true == game.battle.isActionPreparing()) {
                game.battle.current_action.update();
                return;
            }

            if (game.battle.current_action != null && true == game.battle.isActionPrepared()) {
                game.battle.current_action.run();
                return;
            }

        }
    });

});
