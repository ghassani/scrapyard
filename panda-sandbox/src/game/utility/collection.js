game.module('game.utility.collection')
    .body(function() {
        /**
         * Collection - A collection of objects with helper methods
         */
        game.createClass('Collection', {

            objects : [],

            /**
             *
             * @param array - an array of objects
             */
            init: function(objects) {
                if (typeof objects == 'array') {
                    this.objects = objects;
                }
            },

            all : function() {
                return this.objects;
            },

            add: function(object) {
                this.objects.push(object);
            },

            get: function(index)
            {
                return typeof this.objects[index] != 'undefined' ? this.objects[index] : false;
            },

            count : function() {
                return this.objects.length;
            },

            forEach: function(callback) {
                return this.objects.forEach(callback);
            }

        });

    });