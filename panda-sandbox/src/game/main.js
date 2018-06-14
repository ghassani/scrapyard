game.module('game.main')
.require(
    'engine.core',
    'game.assets',
    'game.hero.class.warrior.hero',
    'game.hero.class.ninja.hero',
    'game.hero.class.white_mage.hero',
    'game.hero.class.black_mage.hero',
    'game.hero.class.sultan.hero',
    'game.hero.class.thief.hero',
    'game.enemy.george.enemy',
    'game.battle',
    'game.battle.instance',
    'game.hero.collection',
    'game.enemy.collection',
    'game.utility.log'
)
.body(function() {

    game.createScene('Main', {
        backgroundColor: 0xcbbebe,

        init: function() {
            this.world = new game.World(0, 0);
            var self = this;

            game.states = {
                MENU       : 0,
                WORLD      : 1,
                TOWN       : 2,
                DUNGEON    : 3,
                BATTLE  : 4
            };

            game.actor_types = {
                HERO  : 1,
                NPC   : 2,
                ENEMY : 3
            };

            game.state = game.states.MENU;

            game.battle = false;

            game.heroes = new game.HeroCollection();

            game.heroes.add(new game.WarriorHero  ('', 0, 0, { name : 'Warrior', level: 5, inParty : true }));
            game.heroes.add(new game.NinjaHero    ('', 0, 0, { name : 'Ninja', level: 4, inParty : true }));
            game.heroes.add(new game.WhiteMageHero('', 0, 0, { name : 'White Mage', level: 3, inParty : true }));
            game.heroes.add(new game.BlackMageHero('', 0, 0, { name : 'Black Mage', level: 6, inParty : true }));
            game.heroes.add(new game.ThiefHero    ('', 0, 0, { name : 'Thief', level: 8, inParty : false }));
            game.heroes.add(new game.SultanHero   ('', 0, 0, { name : 'Sultan', level: 8, inParty : false }));


            game.logger = new game.LogWriter();

            this.music = game.audio.playMusic('intro');

            this.start = new game.Graphics()
                .beginFill(0xFFFFFF, 1)
                .drawRect(0, 0, 150, 75)
                .endFill();

            this.start.position.set((game.system.width/2), (game.system.height/2));
            this.start.hitArea = new game.PIXI.Rectangle(0, 0, 150, 75);
            this.start.interactive = true;

            this.start.click = function(e){
                var heroes  = new game.HeroCollection();
                var enemies = new game.EnemyCollection();

                game.heroes.forEach(function(hero, i){
                    if (hero.inParty == true) {
                        heroes.add(hero);
                    }
                });

                enemies.add(new game.GeorgeEnemy(null, 0, 0, { 'name' : 'Georgeee!', level : 10 }));
                enemies.add(new game.GeorgeEnemy(null, 0, 0, { 'name' : 'Georgeee\'s Bitch', level : 3 }));

                game.battle =  new game.BattleInstance(heroes, enemies);
            };

            this.start_text = new game.Text('Start!', {
                font : '18px Arial', fill: '#000000'
            });

            this.start_text.position.set((150/2), (75/2));

            this.start.addChild(game.scene.start_text);

            this.start.addTo(game.scene.stage);

            game.heroes.forEach(function(hero, i){

                hero.setPosition(923, (100 + (i * 100)));
                hero.addTo(game.scene.stage);
                game.scene.addObject(hero);
                game.scene.world.addBody(hero.body);
            });

        },

        update: function() {
            this._super();

            if (game.battle instanceof game.BattleInstance ) {
                game.system.setScene('Battle');
            }
        },

        remove: function() {
            game.scene.removeObject(this);
        }
    });

});
