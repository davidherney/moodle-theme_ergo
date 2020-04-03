define(['jquery', 'core/modal_factory'], function($, ModalFactory) {
  'use strict';

    return {
        init: function() {

            $('.banner-top').parents('.no-overflow').removeClass('no-overflow');

            var fn_effect = function () {
                var $this = $(this);
                var $parents = $this.parents('.box_resources');

                if ($parents.length == 0) {
                    return;
                }

                var $parent = $($parents[0]);

                $parent.find('.selected').removeClass('selected');
                $parent.find('[data-rel]').hide();
                $parent.find('[data-rel="' + $this.attr('data-type') + '"]').show();
                $this.addClass('selected');

            };

            $('.menucontroler').on('mouseover touchstart', fn_effect);

            $('.menucontroler').on('touchend', function() {
                var $node = $('[data-rel="' + $(this).attr('data-type') + '"]');
                $("html, body").animate({ scrollTop: $node.offset().top }, 500);
            });

            $('.attachedimages').each(function() {
                var $this = $(this);
                $this.find('br').remove();
                $this.find('img').first().addClass('active');

                var fnnextimage = function () {
                    var $active = $this.find('img.active');
                    var $next = $active.next('img');

                    $active.removeClass('active');

                    if ($next.length > 0) {
                        $next.addClass('active');
                    } else {
                        $this.find('img').first().addClass('active');
                    }
                };

                var fnprevimage = function () {
                    var $active = $this.find('img.active');
                    var $next = $active.prev('img');

                    $active.removeClass('active');

                    if ($next.length > 0) {
                        $next.addClass('active');
                    } else {
                        $this.find('img').last().addClass('active');
                    }
                };

                if ($this.find('img').length > 0) {
                    $this.find('img').on('click', fnnextimage);

                    var $controlbefore = $('<div class="slide-control" data-action="before">&#60;</div>');
                    $controlbefore.on('click', fnprevimage);
                    $this.append($controlbefore);

                    var $controlafter = $('<div class="slide-control" data-action="after">&#62;</div>');
                    $controlafter.on('click', fnnextimage);
                    $this.append($controlafter);

                } else {
                    $this.hide();
                }
           });

            $('completion.complete').parents('.completion-parent').addClass('complete');

            $('completion.incomplete').parents('.completion-parent').addClass('incomplete');

            $('[data-dhbg-toggle]').on('click', function() {
                var $this = $(this);
                var cssclass = $this.attr('data-dhbg-toggle');
                var target = $this.attr('data-target');

                $(target).toggleClass(cssclass);
            });


            $('.tepuy-transition').on('click', function(event) {
                event.preventDefault();

                var $this = $(this);
                var $show = $($this.attr('data-show'));
                var $hide = $($this.attr('data-hide'));
                var transition = $this.attr('data-transition');
                var duration = $this.attr('data-duration') ? parseInt($this.attr('data-duration')) : 400;

                if (transition) {
                    $hide.hide(duration, transition, function() {
                        $show.show(duration, transition);
                    });
                } else {
                    $hide.hide();
                    $show.show();
                }
            });

            // ==============================================================================================
            // Float Window
            // ==============================================================================================
            $('.tepuy-wf').each(function() {
                var $this = $(this);

                $this.wrapInner("<div class='tepuy-body'></div>");

                var style = '';
                if ($this.attr('data-property-width')) {
                    style += 'width:' + $this.attr('data-property-width') + ';';
                }

                if ($this.attr('data-property-height')) {
                    style += 'height:' + $this.attr('data-property-height') + ';';
                }

                var $close = $('<div class="tepuy-close">X</div>');
                $close.on('click', function() {
                    $this.hide({ effect: 'slide', direction: 'down' });
                });

                if (style != '') {
                    $this.attr('style', style);
                }

                $this.append($close);
                $this.hide();
            });

            $('.tepuy-wf-controller').on('click', function(){
                var $this = $(this);
                var w = $this.attr('data-property-width');
                var h = $this.attr('data-property-height');

                var $float_window = $($this.attr('data-property-content'));

                if (w) {
                    $float_window.css('width', w);
                }

                if (h) {
                    $float_window.css('height', h);
                }

                $float_window.show({ effect: 'slide', direction: 'down' });
            });

            // ==============================================================================================
            // Modal Window
            // ==============================================================================================
            $('.tepuy-w').each(function() {
                var $this = $(this);

                if ($this.parents('[data-fieldtype="editor"]') && $this.parents('[data-fieldtype="editor"]').length > 0) {
                    return;
                }

                $this.wrapInner("<div class='tepuy-body'></div>");

                if ($this.attr('data-tepuy-showentry') || $this.attr('data-tepuy-showconcept')) {

                    var searchparams = {};

                    if ($this.attr('data-tepuy-showentry')) {
                        searchparams.eid = $this.attr('data-tepuy-showentry');
                    } else if ($this.attr('data-tepuy-showconcept') && M.ergo && M.ergo.courseid) {
                        searchparams.concept = $this.attr('data-tepuy-showconcept');
                        searchparams.courseid = M.ergo.courseid;
                    }

                    $.get(M.cfg.wwwroot + '/mod/glossary/showentry_ajax.php',
                            searchparams,
                            function(data) {

                                if (data.entries && Object.keys(data.entries).length > 0) {
                                    var content = '';

                                    Object.keys(data.entries).forEach(function(item, index) {
                                        if (data.entries[item].definition) {
                                            content = data.entries[item].definition;
                                        }
                                    });
                                    $this.find('.tepuy-body').html(content);
                                }
                    }, 'json');

                } else if ($this.attr('data-tepuy-innerentry')) {

                    if ($this.find('a.glossary.autolink').length > 0) {
                        $.get($this.find('a.glossary.autolink').attr('href').replace('showentry.php', 'showentry_ajax.php'),
                                function(data) {
                                    if (data.entries && data.entries.length > 0) {
                                        $this.find('.tepuy-body').html(data.entries[0].definition);
                                    }
                        }, 'json');

                        $this.attr('title', $this.find('a.glossary.autolink').attr('title'));
                    }
                } else if ($this.attr('data-tepuy-innerautolink')) {

                    if ($this.find('a.autolink').length > 0) {
                        var url = $this.find('a.autolink').attr('href') + '&inpopup=true';
                        $this.find('a.autolink').hide();

                        var $iframe = $('<iframe></iframe>');
                        $iframe.attr('src', url);
                        $iframe.on('load', function() {
                            $iframe.contents().find('a:not([target])').attr('target', '_top');
                        });

                        $this.find('.tepuy-body').append($iframe);
                        $this.attr('title', $this.find('a.autolink').html());
                    }
                }

                $this.hide();
            });

            $('.tepuy-w-controller').on('click', function(e){
                e.preventDefault();

                var $this = $(this);

                if ($this.parents('[data-fieldtype="editor"]') && $this.parents('[data-fieldtype="editor"]').length > 0) {
                    return;
                }

                var dialogue = $this.data('dialogue');

                if (!dialogue) {

                    var w = $this.attr('data-property-width');
                    var h = $this.attr('data-property-height');

                    var $float_window = $($this.attr('data-tepuy-content') + ' .tepuy-body');

                    var properties = {
                        center: true,
                        modal: true,
                        visible: false,
                        draggable: false,
                        width: 'auto',
                        height: 'auto',
                        autofillheight: 'header',
                        bodyContent: $float_window
                    };

                    if (w) {
                        if (w.indexOf('%') >= 0) {
                            var window_w = $(window).width();
                            var tmp_w = Number(w.replace('%', ''));
                            if (!isNaN(tmp_w) && tmp_w > 0) {
                                w = tmp_w * window_w / 100;
                            }
                        }

                        properties.width = w;
                    }

                    if (h) {
                        if (h.indexOf('%') >= 0) {
                            var window_h = $(window).height();
                            var tmp_h = Number(h.replace('%', ''));
                            if (!isNaN(tmp_h) && tmp_h > 0) {
                                h = tmp_h * window_h / 100;
                            }
                        }

                        properties.height = h;
                    }

                    var dialogue = new M.core.dialogue(properties);
                    $this.data('dialogue', dialogue);
                }

                dialogue.show();
            });

            // ==============================================================================================
            // Open resources into modal
            // ==============================================================================================
            $('.tepuy-openinmodal').each(function() {
                var $this = $(this);

                if ($this.parents('[data-fieldtype="editor"]') && $this.parents('[data-fieldtype="editor"]').length > 0) {
                    return;
                }

                $this.find('a').each(function(event) {
                    this.removeAttribute('onclick');
                });

                $this.find('a').on('click', function(event) {
                    event.preventDefault();

                    var $link = $(this);

                    var dialogue = $link.data('dialogue');

                    if (!dialogue) {

                        var w = $this.attr('data-property-width');
                        var h = $this.attr('data-property-height');

                        var url = $link.attr('href') + '&inpopup=true';
                        var $iframe = $('<iframe class="tepuy-openinmodal-container"></iframe>');
                        $iframe.attr('src', url);
                        $iframe.on('load', function() {
                            $iframe.contents().find('a:not([target])').attr('target', '_top');
                        });


                        var el = $.fn['hide'];
                        $.fn['hide'] = function () {
                            this.trigger('hide');
                            return el.apply(this, arguments);
                        };

                        $iframe.on('hide', function() {
                            console.log('SE OCULTó');
                        });

                        var $float_window = $('<div></div>');

                        $float_window.append($iframe);

                        var properties = {
                            center: true,
                            modal: true,
                            visible: false,
                            draggable: true,
                            width: 'auto',
                            height: 'auto',
                            autofillheight: 'header',
                            bodyContent: $float_window
                        };

                        if (w) {
                            if (w.indexOf('%') >= 0) {
                                var window_w = $(window).width();
                                var tmp_w = Number(w.replace('%', ''));
                                if (!isNaN(tmp_w) && tmp_w > 0) {
                                    w = tmp_w * window_w / 100;
                                }
                            }

                            properties.width = w;
                        }

                        if (h) {
                            if (h.indexOf('%') >= 0) {
                                var window_h = $(window).height();
                                var tmp_h = Number(h.replace('%', ''));
                                if (!isNaN(tmp_h) && tmp_h > 0) {
                                    h = tmp_h * window_h / 100;
                                }
                            }

                            properties.height = h;
                        }

                        var dialogue = new M.core.dialogue(properties);
                        $link.data('dialogue', dialogue);
                        dialogue.after('visibleChange', function(e) {
                            if (e.attrName === 'visible') {
                                if (e.prevVal && !e.newVal) {
                                    $iframe.contents().find('video, audio').each(function(){
                                        this.pause();
                                    });
                                }
                            }
                        }, dialogue);
                    }

                    dialogue.show();

                });

            });

        }
    };
});
