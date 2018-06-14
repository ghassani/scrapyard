/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

"use strict";

function SplicedCmsEditor ( element, options ) {

    if (typeof ace == 'undefined') {
        console.log('SplicedCmsEditor Depends on ACE Editor');
        return false;
    }

    this.element = element;

    this.settings = $.extend( {}, {
        ace_base_path : '/vendor/ace-builds/src-min/',
        revert_selector : '.template_version_revert',
        default_mode : 'twig',
        default_theme : 'github',
        modes : [
            'twig',
            'html',
            'php',
            'javascript',
            'python',
            'perl',
            'ruby',
            'css',
            'xml',
            'json',
        ],
        themes : [

        ]
    }, options );

    this.init();
}

$.extend(SplicedCmsEditor.prototype, SplicedCmsBaseClass.prototype, {
    /*
     *
     *
     */
    init: function () {
        var self = this;

        ace.config.set('basePath', this.settings.ace_base_path);

        this.languageTools  = ace.require("ace/ext/language_tools");
        this.themelist      = ace.require("ace/ext/themelist");
        this.modelist       = ace.require("ace/ext/modelist");
        this.beautify       = ace.require("ace/ext/beautify");
        this.statusbar      = ace.require("ace/ext/statusbar");
        this.settingsMenu   = ace.require('ace/ext/settings_menu');

        this.editor                 = ace.edit(this.element.id);
        this.editor.originalValue   = this.editor.getValue();
        this.loadedRevision         = false;
        this.editorContentValue     = $('#' + this.element.id.replace(/_ace$/, ''));

        this.statusbar = new this.statusbar.StatusBar(
            this.editor,
            document.getElementById("ace-editor-status-bar")
        );

        this.settingsMenu.init(this.editor);

        this.editor.setOptions({
            enableBasicAutocompletion: true,
            enableSnippets: true,
            enableLiveAutocompletion: true
        });

        // Populate the theme list
        if(this.themelist && $('ul.ace-editor-themes').length){
            $(this.themelist.themes).each(function(index, theme){
                var $li = $('<li><a href="javascript:;" ace-action="set-theme" data-theme="' + theme.name + '">' + theme.caption + '</a></li>');
                $('ul.ace-editor-themes').append($li);
            });
        }

        // Populate the mode list
        if(this.modelist && $('ul.ace-editor-modes').length){
            $.each(this.modelist.modesByName, function(index, mode){
                if($.inArray(mode.name.toUpperCase(), self.settings.modes) > -1){
                    var $li = $('<li><a href="javascript:;" ace-action="set-mode" data-mode="' + mode.name + '">' + mode.caption + '</a></li>');
                    $('ul.ace-editor-modes').append($li);
                }
            });
        }

        // register event handlers
        this.editor.on('change', function(e){
            if (!self.editor.loadedRevision) {
                self.editor.originalValue = self.editor.getValue();
            }
            self.editorContentValue.val(self.editor.getValue());
        });

        if(this.settings.revert_selector.length && $(this.settings.revert_selector).length) {
            $(this.settings.revert_selector).bind('change', function(e){
                self.revisionChange(e, this);
            });
        }

        $('a[ace-action="toggle-fullscreen"]').bind('click', function(e){
            e.preventDefault();
            self.toggleFullScreen();
        });

        $('a[ace-action="show-settings"]').bind('click', function(e){
            e.preventDefault();
            self.editor.showSettingsMenu();
        });

        $('a[ace-action="set-mode"]').bind('click', function(e){
           e.preventDefault();
           if($(this).data('mode')) {
               self.editor.getSession().setMode("ace/mode/" + $(this).data('mode') );
           }
        });

        $('a[ace-action="set-theme"]').bind('click', function(e){
            e.preventDefault();
            if($(this).data('theme')) {
                self.editor.setTheme("ace/theme/" + $(this).data('theme') );
            }
        });

        $('a[ace-action="insert-content-block"]').on('click', function(e){
            e.preventDefault();
            self.insertContentBlock(e, this);
        });

        $('a[ace-action="insert-menu"]').on('click', function(e){
            e.preventDefault();
            self.insertMenu(e, this);
        });

        $('a[ace-action="insert-link"]').on('click', function(e){
            e.preventDefault();
            self.insertLink(e, this);
        });

        $('a[ace-action="insert-image"]').on('click', function(e){
            e.preventDefault();
            self.insertImage(e, this);
        });

        $('a[ace-action="insert-video"]').on('click', function(e){
            e.preventDefault();
            self.insertVideo(e, this);
        });

        // apply default editor settings
        this.editor.getSession().setMode("ace/mode/" + this.settings.default_mode.toLowerCase());
        this.editor.setTheme("ace/theme/" + this.settings.default_theme.toLowerCase());

        // async ajax all insertable content
        if ($('ul.content-block-list').length) {
            this.loadAvailableContentBlocks();
        }
        if ($('ul.menu-list').length) {
            this.loadAvailableMenus();
        }

        // initialize tooltips
        $('.ace-editor-toolbar a.dropdown-toggle').each(function(i,e){
           if (e.title) {
               $(e).tooltip({
                   container: 'body'
               });
           }
        });

        this.registerCommands();

    },

    getEditor : function(){
        return this.editor;
    },

    /*
     *
     *
     */
    registerCommands : function()
    {
        var self = this;

        self.editor.commands.addCommands([{
            name: "Show Settings Menu",
            bindKey: {win: "Ctrl-q", mac: "Command-q"},
            exec: function(editor) {
                editor.showSettingsMenu();
            },
            readOnly: true
        },{
            name: "Toggle Fullscreen",
            bindKey: "F11",
            exec: function(editor) {
                self.toggleFullScreen();
            },
            readOnly: true
        },{
            name: "On Escape",
            bindKey: "ESC",
            exec: function(editor) {
                if (self.isFullscreen()) {
                    self.toggleFullScreen();
                }
            },
            readOnly: true
        }]);

        //this.editor.commands.addCommands(this.beautify.commands);
    },

    isFullscreen : function() {
        return $('body').hasClass('full-screen-editor');
    },

    /*
     *
     */
    toggleFullScreen : function() {
        if (this.isFullscreen()) {
            $('body').removeClass('full-screen-editor');
            $(this.editor.container).removeClass('full-screen');
            $('a[ace-action="toggle-fullscreen"]').text('Enter Fullscreen');
        } else {
           $('body').addClass('full-screen-editor');
           $(this.editor.container).addClass('full-screen');
           $('a[ace-action="toggle-fullscreen"]').text('Exit Fullscreen');
        }
        this.editor.resize();
    },

    /*
     *
     */
    revisionChange : function(event, input)
    {
        var self = this;

        if(!$(input).val()) {
            self.editor.setValue(self.editor.originalValue);
            return;
        }

        $.ajax({
            url : '/dev.php/admin/json/template_version/load/' + $(input).val(),
            method : 'GET',
            dataType : 'json',
            beforeSend : function() {
                self.setLoading();
            },
            complete : function(response) {
                self.clearLoading();
            },
            success : function(response) {
                if (response.success) {
                    self.editor.loadedRevision = true;
                    self.editor.setValue(decodeURI(response.data.content));
                } else {
                    alert(response.message);
                }
            }
        });
    },
    
    /*
     *
     *
     */
    loadAvailableContentBlocks : function()
    {
        var self = this;

        $.ajax({
            url : '/dev.php/admin/json/content_block/load',
            method : 'GET',
            dataType : 'json',
            error : function() {

            },
            success : function(response) {
                $(response.data).each(function(i, e){
                    var $li = $('<li><a href="javascript:;" ace-action="insert-content-block" data-key="'+ this.blockKey +'" data-id="'+ this.id +'">'+ this.blockKey +' - ' + this.name + '</a></li>');
                    $li.find('a[ace-action="insert-content-block"]').on('click', function(e){
                        self.insertContentBlock(e, this);
                    });
                    $('ul.content-block-list').append($li);
                });
            }
        });
    },
    
    /*
     *
     *
     */
    loadAvailableMenus : function()
    {
        var self = this;

        $.ajax({
            url : '/dev.php/admin/json/menu/load',
            method : 'GET',
            dataType : 'json',
            error : function() {

            },
            success : function(response) {
                $(response.data).each(function(i, e){
                    var $li = $('<li><a href="javascript:;" ace-action="insert-menu" data-key="'+ this.menuKey +'" data-id="'+ this.id +'">'+ this.menuKey +' - ' + this.name + '</a></li>');
                    $li.find('a[ace-action="insert-menu"]').on('click', function(e){
                        self.insertMenu(e, this);
                    });
                    $('ul.menu-list').append($li);
                });
            }
        });
    },

    /*
     *
     *
     */
    insertContentBlock : function(event, a){
        this.editor.insert('{{ render_content_block(\''+ $(a).data('key') +'\') }}');
    },

    /*
    *
    *
    */
    insertMenu : function(event, a){
        this.editor.insert('{{ render_menu(\''+ $(a).data('key') +'\') }}');
    },

    /*
    *
    *
    */
    insertLink : function(event, el){
        var self = this;

        $.ajax({
            url : '/dev.php/admin/editor/insert-link',
            method : 'GET',
            dataType : 'html',
            beforeSend : function() {
                self.setLoading();
            },
            complete : function() {
                self.clearLoading();
            },
            error : function() {

            },
            success : function(response) {
                var $modal = $(response).modal({
                    backdrop: 'static',
                    keyboard: false
                });

                $modal.find('#insert-link').on('click', function(e){
                    var url    = $modal.find('#editor_insert_link_url').length ? $modal.find('#editor_insert_link_url').val() : null;
                    var page   = $modal.find('#editor_insert_link_page').length ? $modal.find('#editor_insert_link_page').val() : null;
                    var title  = $modal.find('#editor_insert_link_title').length ? $modal.find('#editor_insert_link_title').val() : null;
                    var anchor = $modal.find('#editor_insert_link_anchor').length ? $modal.find('#editor_insert_link_anchor').val() : null;
                    var cssClass = $modal.find('#editor_insert_link_class').length ? $modal.find('#editor_insert_link_class').val() : null;

                    if (!url && !page){
                        $modal.find('#editor_insert_link_page').focus();
                        alert('Select a Page or Enter a URL');
                        return;
                    }

                    var html = '<a href="';

                    if (typeof url != 'undefined' && url.length) {
                        html = html.concat(url + '"');
                    } else if (typeof page != 'undefined' && page.length) {
                        html = html.concat('{{ path(\''+ page +'\') }}"');
                    }

                    if (title) {
                        html = html.concat(' title="'+ title +'"');
                    }

                    if (cssClass) {
                        html = html.concat(' class="'+ cssClass +'"');
                    }

                    html = html.concat('>' + anchor + '</a>');

                    self.editor.insert(html);
                    $modal.modal('toggle');
                    $modal.remove();
                });

            }
        });
    },

    /*
    *
    *
    */
    insertImage : function(event, el){
        var self = this;

        $.ajax({
            url : '/dev.php/admin/editor/insert-image',
            method : 'GET',
            dataType : 'html',
            beforeSend : function() {
                self.setLoading();
            },
            complete : function() {
                self.clearLoading();
            },
            success : function(response) {
                var $modal = $(response).modal({
                    backdrop: 'static',
                    keyboard: false
                });
            }
        });
    },

    /*
    *
    *
    */
    insertVideo : function(event, el){
        var self = this;

        $.ajax({
            url : '/dev.php/admin/editor/insert-video',
            method : 'GET',
            dataType : 'html',
            beforeSend : function() {
                self.setLoading();
            },
            complete : function() {
                self.clearLoading();
            },
            success : function(response) {
                var $modal = $(response).modal({
                    backdrop: 'static',
                    keyboard: false
                });

                var $submitButton = $modal.find('#btn-insert-video-continue');
                var $url = $modal.find('#editor_insert_video_url');
                var $codeBlock = $modal.find('#insert-video-details-code');


                $submitButton.on('click', function(e){
                    if ($url.val() && $submitButton.data('state') == 'invalid') {
                        $url.trigger('change');
                    } else if($submitButton.data('state') == 'valid' && $codeBlock.val()) {
                        self.editor.insert($codeBlock.val());
                        $modal.modal('toggle');
                        $modal.remove();
                    }
                });

                $url.on('change', function(e){
                    var url_expression     = new RegExp(/[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/gi);
                    var youtube_expression = new RegExp(/^((http|https)\:\/\/)?(www\.youtube\.com|youtu\.?be)\/.+$/gi);
                    var vimeo_expression   = new RegExp(/^((http|https)\:\/\/)?(www\.vimeo\.com|vimeo\.com)\/.+$/gi);
                    var youtube_id_expression = new RegExp(/v=[0-9A-Za-z]{11}/gi);
                    var vimeo_id_expression   = new RegExp(/\/\d{5,10}/gi);

                    var value = $(this).val();
                    var source = false;
                    var video_id = false;

                    if (value.match(url_expression)) {

                        if (value.match(youtube_expression)) {
                            source = 'youtube';
                            var matches = value.match(youtube_id_expression);
                            if(matches.length) {
                                video_id = matches[0].replace('v=', '');
                            }

                        } else if (value.match(vimeo_expression)) {
                            source = 'vimeo';
                            var matches = value.match(vimeo_id_expression);
                            if(matches.length) {
                                video_id = matches[0].replace('/', '');
                            }

                        }

                    } else {
                        alert('Enter a URL To the Video');
                        return;
                    }

                    if (!source && !video_id) {
                        alert('Could Not Determine Source. Possibly malformed URL or Video Provider is not supported.');
                        return;
                    }

                    if ( $modal.find('#insert-video-details').is(':visible')) {
                        $modal.find('#insert-video-details').fadeOut('fast', function(){
                            $modal.find('#insert-video-details-image').empty();
                            $modal.find('#insert-video-details-title').text('');
                            $modal.find('#insert-video-details-url').empty();
                            $modal.find('#insert-video-details-data').empty();
                            $codeBlock.val('');
                            $submitButton.attr('data-state', 'invalid').text('Continue');
                        });
                    }

                    if (source == 'youtube') {

                        $.ajax({
                            url : 'http://gdata.youtube.com/feeds/api/videos/'+video_id+'?v=2&alt=json',
                            method : 'GET',
                            dataType : 'json',
                            beforeSend : function() {
                                self.setLoading();
                            },
                            complete : function() {
                                self.clearLoading();
                            },
                            success : function(response) {
                                console.log(response);
                                if (typeof response.entry != 'undefined') {

                                    var video = response.entry;

                                    var thumbnail = new Image();
                                    thumbnail.src = video.media$group.media$thumbnail[1].url;
                                    thumbnail.width = 200;
                                    $modal.find('#insert-video-details-image').empty().append($(thumbnail));
                                    $modal.find('#insert-video-details-title').text(video.title.$t);
                                    $modal.find('#insert-video-details-url').empty().append($('<a href="'+ video.link[0].href +'" title="'+ video.title.$t +'" target="_blank">' + video.link[0].href + '</a>'));

                                    var $data = $('<ul class="list-unstyled"></ul>');

                                    $data.append($('<li><strong>User:</strong> <a href="+video.author[0].uri.$t+" target="_blank">'+ video.author[0].name.$t +'</a></li>'));
                                    $data.append($('<li><strong>Uploaded:</strong> ' + video.published.$t +'</li>'));

                                    $modal.find('#insert-video-details-data').empty().append($data);

                                   $codeBlock.val('<iframe width="560" height="315" src="https://www.youtube.com/embed/'+ video_id +'" frameborder="0" allowfullscreen></iframe>');

                                    $modal.find('#insert-video-details').fadeIn('fast');

                                    $submitButton.attr('data-state', 'valid').text('Insert');
                                }

                            }
                        });

                    } else if (source == 'vimeo') {
                        $.ajax({
                            url : 'https://vimeo.com/api/v2/video/'+video_id+'.json',
                            method : 'GET',
                            dataType : 'json',
                            beforeSend : function() {
                                self.setLoading();
                            },
                            complete : function() {
                                self.clearLoading();
                            },
                            success : function(response) {
                                //console.log(response);
                                if (response.length) {
                                    var video = response[0];
                                    var thumbnail = new Image(200, 150);
                                    thumbnail.src = video.thumbnail_medium;
                                    $modal.find('#insert-video-details-image').empty().append($(thumbnail));
                                    $modal.find('#insert-video-details-title').text(video.title);
                                    $modal.find('#insert-video-details-url').empty().append($('<a href="'+ video.url +'" title="'+ video.title +'" target="_blank">' + video.url + '</a>'));

                                    var $data = $('<ul class="list-unstyled"></ul>');

                                    $data.append($('<li><strong>User:</strong> <a href="' + video.user_url + '" target="_blank">'+ video.user_name +'</a></li>'));
                                    $data.append($('<li><strong>Uploaded:</strong> ' + video.upload_date +'</li>'));

                                    $modal.find('#insert-video-details-data').empty().append($data);
                                    $modal.find('#insert-video-details-code').val('<iframe src="//player.vimeo.com/video/' + video_id + '?color=f42334&title=0&byline=0&portrait=0" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');

                                    $modal.find('#insert-video-details').fadeIn('fast');

                                    $submitButton.attr('data-state', 'valid').text('Insert');
                                }

                            }
                        });
                    }

                });


            }
        });
    }

});
