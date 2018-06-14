game.module('game.utility.audio_playlist')
.body(function() {

    /**
     * http://vermeire.home.xs4all.nl/panda/fiddler.html
     */
    game.createClass('AudioPlaylist', 'Class', {
        playlist:[],
        currentTrackId: null,

        init: function(list) {
            this.play(list);
        },

        play: function(list){
            //stop and delete existing playlist
            this.stop();
            if(Object.prototype.toString.call( list ) === '[object Array]'){
                this.playlist = list;
            }
            else{
                this.playlist=[];
            }
            this.playNext();

        },

        playNext: function(){
            if(this.playlist.length>0){
                var currentTrack = this.playlist.shift();
                this.currentTrackId = game.audio.playSound(currentTrack, false, this.playNext.bind(this));
            }
            else{
                //last soundtrack.
                this.currentTrackId = null;
            }
        },

        stop: function(){
            //stop current sound if anything is playing
            if(this.currentTrackId!==null){
                game.audio.stopSound(this.currentTrackId, true);
            }
            this.playlist = [];
        }
    });

});