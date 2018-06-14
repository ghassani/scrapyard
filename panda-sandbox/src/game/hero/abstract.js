"use strict";

game.module('game.hero.abstract')
.require(
    'game.hero.action.battle.attack',
    'game.hero.action.battle.tech',
    'game.hero.action.battle.magic',
    'game.hero.action.battle.defend',
    'game.hero.action.battle.escape'
).body(function() {
    /**
     * AbstractHero - Abstract Playable Character
     */
    game.createClass('AbstractHero', {
        id : 'hero', /* a unique id for this object instance */
        name : 'Hero',
        level : 1,
        inParty : false,
        class : '',
        default_texture : 'default_hero',

        /**
         * @see game.Sprite
         */
        texture_properties : {
            interactive : true
        },

        battle_state : {
            max_hp  : 100,
            hp      : 100,
            max_mp  : 10,
            mp      : 10,
            bonuses : [],
            conditions : []
        },
        /**
         *
         */
        battle_actions : [
            new game.AttackBattleAction(),
            new game.TechBattleAction(),
            new game.MagicBattleAction(),
            new game.DefendBattleAction(),
            new game.EscapeBattleAction()
        ],
        magic : [

        ],
        techs : [

        ],
        stats : {
            strength:      1,
            intelligence:  1,
            dexterity:     1,
            vitality:      1
        },

        /**
         * var sprite - Holds a game.Sprite object
         */
        sprite : null,
        /**
         * var body - Holds the body
         */
        body : null,

        /**
         * Size of body
         */
        body_size : {
            width: 50,
            height: 100
        },

        init: function(texture, x, y, properties) {
            game.merge(this, properties);

            this.actor_type = game.actor_types.HERO;

            if (this.sprite == null) {
                if (typeof texture != 'string' || !texture.length) {
                    texture = this.default_texture;
                }
                this.sprite = new game.Sprite(texture, x, y, this.texture_properties);
            }

            if (this.body == null) {
                this.body = new game.Body({
                    position: {x: this.sprite.position.x, y: this.sprite.position.y},
                    velocityLimit: {x: 250, y: 250},
                    velocity: {x: 0, y: 0 },
                    collisionGroup: 1,
                    collideAgainst: 0,
                    mass: 0
                });

                this.body.addShape(new game.Rectangle(this.body_size.width, this.body_size.height));
            }
        },
        /**
         * on scene update
         */
        update: function() {
            //console.log(this.getId(), this.body.position, this.sprite.position);
            this.sprite.position.set(this.body.position.x, this.body.position.y);
        },

        remove: function() {
            game.scene.removeObject(this);
            game.scene.world.removeBody(this.body);
            game.scene.stage.removeChild(this.sprite);
        },

        /**
         *
         * @returns {*}
         */
        getBattleActions : function() {
            return this.battle_actions;
        },

        /**
         *
         * @returns {*}
         */
        getStats: function() {
            return this.stats;
        },

        /**
         *
         * @returns {*}
         */
        getBattleState : function() {
            return this.battle_state;
        },

        /**
         *
         * @returns {number}
         */
        getLevel : function() {
            return this.level;
        },

        /**
         *
         * @returns {string}
         */
        getName : function() {
            return this.name;
        },

        /**
         *
         * @returns {string}
         */
        getId : function() {
            return this.id;
        },

        /**
         *
         * @returns {string}
         */
        getClass : function() {
            return this.class;
        },

        /**
         *
         * @param stage
         */
        addTo: function(stage) {
            this.sprite.addTo(stage);
        },
        /**
         * setPosition
         *
         * Position the body or other elements
         *
         * @param x
         * @param y
         */
        setPosition: function(x, y) {
            this.body.position.set(x, y);
        },

        setVelocity: function(x, y) {
            this.body.velocity.set(x, y);
        },

        setAnchor: function(x, y) {
            this.sprite.anchor.set(x, y);
        },

        getMagic : function() {
            return this.magic;
        },

        getTechs : function() {
            return this.techs;
        }
    });


});