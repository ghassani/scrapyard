game.module('game.assets')
.body(function() {
    game.addAsset('images/warrior/default.png',     'default_hero');
    game.addAsset('images/warrior/default.png',     'default_warrior');
    game.addAsset('images/ninja/default.png',       'default_ninja');
    game.addAsset('images/white_mage/default.png',  'default_white_mage');
    game.addAsset('images/black_mage/default.png',  'default_black_mage');

    game.addAsset('images/enemy/default.png', 'default_enemy');
    game.addAsset('images/enemy/george.png',  'default_george');

    game.addAsset('images/npc/default.png', 'default_npc');

    game.addAsset('images/icons/selection_mouseover.png', 'selection_mouseover_icon');


    game.addAsset('fonts/Arial.fnt');

    game.addAudio('audio/kingdom-cassettes-wont-listen.m4a', 'battle-boss');
    game.addAudio('audio/tropic-strike.m4a', 'intro');

    game.addAudio('audio/ALERT_Error.wav', 'error');
    game.addAudio('audio/spell.wav', 'spell');
    game.addAudio('audio/magic1.wav', 'magic1');
    game.addAudio('audio/swing.wav', 'swing');
    game.addAudio('audio/swing2.wav', 'swing2');
    game.addAudio('audio/swing3.wav', 'swing3');
    game.addAudio('audio/sword-unsheathe.wav', 'sword-unsheathe');

});