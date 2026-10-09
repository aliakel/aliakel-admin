(function ($) {
    var featherPaths = null;
    var featherLoading = null;

    function featherSprite() {
        return (window.LA && LA.featherSprite) || '';
    }

    function loadFeather() {
        if (featherPaths) {
            return $.Deferred().resolve(featherPaths).promise();
        }

        if (!featherLoading) {
            featherLoading = $.ajax({ url: featherSprite(), dataType: 'text' }).then(function (svg) {
                var paths = {};
                var match;
                var pattern = /<symbol id="([^"]+)"[^>]*>([\s\S]*?)<\/symbol>/g;

                while ((match = pattern.exec(svg))) {
                    paths[match[1]] = match[2];
                }

                featherPaths = paths;
                if (window.LA) {
                    LA.featherPaths = paths;
                }

                return paths;
            });
        }

        return featherLoading;
    }

    function paintFeather(node, name) {
        if (!node) {
            return;
        }

        node.setAttribute('data-feather', name);

        var apply = function (paths) {
            if (paths && paths[name]) {
                node.innerHTML = paths[name];
            }
        };

        if (featherPaths) {
            apply(featherPaths);
            return;
        }

        loadFeather().done(apply);
    }

    window.LA = window.LA || function () {};
    LA.feather = function (name, className) {
        var cls = className || 'feather-icon';
        var inner = (featherPaths && featherPaths[name]) || '';
        var svg = '<svg class="' + cls + '" data-feather="' + name + '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' + inner + '</svg>';

        if (!inner) {
            loadFeather().done(function (paths) {
                document.querySelectorAll('svg[data-feather="' + name + '"]').forEach(function (node) {
                    if (!node.innerHTML && paths[name]) {
                        node.innerHTML = paths[name];
                    }
                });
            });
        }

        return svg;
    };
    LA.toggleFeather = function (el, a, b) {
        var node = el && el.jquery ? el.get(0) : el;

        if (!node) {
            return;
        }

        paintFeather(node, node.getAttribute('data-feather') === a ? b : a);
    };
    LA.setFeather = paintFeather;

    function closeFeatherPickers(except) {
        $('[data-feather-picker]').each(function () {
            var $panel = $(this).find('.feather-picker-panel');

            if (!except || !$panel.is(except)) {
                $panel.attr('hidden', 'hidden');
                $(this).removeClass('is-open');
            }
        });
    }

    function initFeatherPickers(root) {
        $(root).find('[data-feather-picker]').each(function () {
            var $picker = $(this);

            if ($picker.data('featherReady')) {
                return;
            }

            $picker.data('featherReady', true);

            var $input = $picker.find('[data-feather-input]');
            var $search = $picker.find('[data-feather-search]');
            var $grid = $picker.find('.feather-picker-grid');
            var $panel = $picker.find('.feather-picker-panel');
            var $preview = $picker.find('.feather-picker-preview');

            function selected() {
                return $.trim($input.val());
            }

            function preview(name) {
                $preview.html(LA.feather(name || 'circle'));
            }

            function markSelected() {
                var name = selected();
                $grid.children('button').each(function () {
                    $(this).toggleClass('is-selected', $(this).attr('data-icon') === name);
                });
            }

            function renderGrid(paths) {
                var query = $.trim($search.val()).toLowerCase();
                var names = Object.keys(paths).sort();
                var html = '';

                names.forEach(function (name) {
                    if (query && name.indexOf(query) === -1) {
                        return;
                    }

                    html += '<button type="button" data-icon="' + name + '" title="' + name + '">' + LA.feather(name) + '</button>';
                });

                $grid.html(html || '<p class="px-1 py-2 text-sm text-slate-500">No icons</p>');
                markSelected();
            }

            function openPanel() {
                closeFeatherPickers($panel);
                $picker.addClass('is-open');
                $panel.removeAttr('hidden');
                loadFeather().done(function (paths) {
                    renderGrid(paths);
                });
            }

            $input.on('focus click', function (event) {
                event.stopPropagation();
                openPanel();
            });

            $preview.on('click', function (event) {
                event.stopPropagation();
                $input.trigger('focus');
            });

            $input.on('input', function () {
                preview(selected());
                markSelected();
            });

            $search.on('click', function (event) {
                event.stopPropagation();
            });

            $search.on('input', function () {
                if (featherPaths) {
                    renderGrid(featherPaths);
                }
            });

            $search.on('keydown', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    var exact = $grid.children('button[data-icon="' + $.trim($search.val()).toLowerCase() + '"]');

                    if (exact.length) {
                        exact.trigger('click');
                    }
                }
            });

            $grid.on('click', 'button[data-icon]', function (event) {
                event.preventDefault();
                event.stopPropagation();
                var name = $(this).attr('data-icon');
                $input.val(name).trigger('change');
                preview(name);
                markSelected();
                $panel.attr('hidden', 'hidden');
            });

            $picker.on('click', function (event) {
                event.stopPropagation();
            });

            preview(selected());
        });
    }

    function bootFeatherPickers() {
        loadFeather();
        initFeatherPickers(document);
    }

    function initRichtext(root) {
        if (typeof window.Quill === 'undefined') {
            return;
        }

        $(root).find('.la-richtext').each(function () {
            var $wrap = $(this);
            var holder = $wrap.children().get(0);
            var input = $wrap.next('textarea').get(0);

            if (!holder || !input || input.dataset.richtextReady) {
                return;
            }

            input.dataset.richtextReady = '1';

            var sourceMode = false;
            var source = document.createElement('textarea');
            source.className = 'la-richtext-source';
            source.setAttribute('spellcheck', 'false');
            source.setAttribute('aria-label', 'HTML source');
            $wrap.append(source);

            var normalizeHtml = function (html) {
                html = (html || '').replace(/&nbsp;/gi, ' ').replace(/\u00a0/g, ' ');
                html = html.replace(/>\s+</g, '><').trim();
                return (html === '<p></p>' || html === '<p><br></p>' || html === '') ? '' : html;
            };

            var syncFromQuill = function () {
                input.value = normalizeHtml(quill.root.innerHTML);
            };

            var syncFromSource = function () {
                input.value = normalizeHtml(source.value);
            };

            var quill = new Quill(holder, {
                theme: 'snow',
                modules: {
                    toolbar: {
                        container: [
                            [{ header: [1, 2, 3, false] }],
                            ['bold', 'italic', 'underline', 'strike'],
                            ['blockquote', 'code-block'],
                            [{ list: 'ordered' }, { list: 'bullet' }, { list: 'check' }],
                            [{ align: [] }],
                            ['link', 'image'],
                            ['clean'],
                            ['html']
                        ],
                        handlers: {
                            html: function () {
                                toggleSource();
                            },
                            image: function () {
                                if (sourceMode) {
                                    return;
                                }

                                var picker = document.createElement('input');
                                picker.type = 'file';
                                picker.accept = 'image/png,image/jpeg,image/gif,image/webp';
                                picker.addEventListener('change', function () {
                                    var file = picker.files && picker.files[0];
                                    if (!file) {
                                        return;
                                    }

                                    var body = new FormData();
                                    body.append('image', file);
                                    body.append('_token', window.LA.token);

                                    fetch($wrap.data('upload'), {
                                        method: 'POST',
                                        body: body,
                                        credentials: 'same-origin',
                                        headers: {
                                            'X-CSRF-TOKEN': window.LA.token,
                                            'X-Requested-With': 'XMLHttpRequest',
                                            'Accept': 'application/json'
                                        }
                                    }).then(function (response) {
                                        return response.json().then(function (json) {
                                            if (!response.ok) {
                                                throw new Error(json.message || 'Upload failed');
                                            }
                                            return json;
                                        });
                                    }).then(function (json) {
                                        var range = quill.getSelection(true);
                                        quill.insertEmbed(range.index, 'image', json.url, 'user');
                                        quill.setSelection(range.index + 1);
                                    }).catch(function (error) {
                                        window.alert(error.message || 'Upload failed');
                                    });
                                });
                                picker.click();
                            }
                        }
                    }
                }
            });

            var toolbar = quill.getModule('toolbar');
            var htmlBtn = toolbar.container.querySelector('button.ql-html');
            if (htmlBtn) {
                htmlBtn.setAttribute('type', 'button');
                htmlBtn.setAttribute('title', 'HTML source');
                htmlBtn.setAttribute('aria-label', 'HTML source');
            }

            var toggleSource = function () {
                if (sourceMode) {
                    quill.setContents([]);
                    quill.clipboard.dangerouslyPasteHTML(0, source.value || '');
                    syncFromQuill();
                    source.value = '';
                    $wrap.removeClass('is-source');
                    if (htmlBtn) {
                        htmlBtn.classList.remove('ql-active');
                    }
                    sourceMode = false;
                    return;
                }

                syncFromQuill();
                source.value = input.value || '';
                $wrap.addClass('is-source');
                if (htmlBtn) {
                    htmlBtn.classList.add('ql-active');
                }
                sourceMode = true;
                source.focus();
            };

            if (document.documentElement.getAttribute('dir') === 'rtl') {
                quill.format('direction', 'rtl');
                quill.format('align', 'right');
                holder.querySelector('.ql-editor').setAttribute('dir', 'rtl');
            }

            if (input.value) {
                quill.clipboard.dangerouslyPasteHTML(input.value);
            }

            var sync = function () {
                if (sourceMode) {
                    syncFromSource();
                    return;
                }
                syncFromQuill();
            };

            source.addEventListener('input', syncFromSource);
            quill.on('text-change', function () {
                if (!sourceMode) {
                    syncFromQuill();
                }
            });
            if (input.form) {
                input.form.addEventListener('submit', sync);
            }
        });
    }

    function bootRichtext() {
        initRichtext(document);
    }

    function markActiveMenu() {
        var path = (location.pathname || '/').replace(/\/+$/, '') || '/';
        var best = null;
        var bestLen = -1;

        $('.sidebar-menu li').removeClass('active');
        $('.sidebar-menu .treeview-menu').removeClass('menu-open');

        $('.sidebar-menu li > a').each(function () {
            var href = this.getAttribute('href');
            if (!href || href === '#') {
                return;
            }

            var url;
            try {
                url = new URL(href, location.origin);
            } catch (error) {
                return;
            }

            var itemPath = (url.pathname || '/').replace(/\/+$/, '') || '/';
            if (itemPath !== path && !(itemPath !== '/' && path.indexOf(itemPath + '/') === 0)) {
                return;
            }

            if (itemPath.length > bestLen) {
                best = this;
                bestLen = itemPath.length;
            }
        });

        if (!best) {
            return;
        }

        var $item = $(best).parent();
        $item.addClass('active');
        $item.parents('ul.treeview-menu').addClass('menu-open');
        $item.parents('li.treeview').addClass('active');
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', bootFeatherPickers);
        document.addEventListener('DOMContentLoaded', bootRichtext);
        document.addEventListener('DOMContentLoaded', markActiveMenu);
    } else {
        bootFeatherPickers();
        bootRichtext();
        markActiveMenu();
    }

    $(document).on('pjax:complete', function () {
        initFeatherPickers(document);
        initRichtext(document);
        markActiveMenu();
    });

    $(document).on('click', '[data-password-toggle]', function (event) {
        event.preventDefault();
        var $button = $(this);
        var $input = $button.closest('.input-group').find('input').first();

        if (!$input.length) {
            return;
        }

        var reveal = $input.attr('type') === 'password';
        $input.attr('type', reveal ? 'text' : 'password');
        $button.attr('aria-pressed', reveal ? 'true' : 'false');

        var icon = $button.find('[data-feather]').get(0);
        if (icon) {
            paintFeather(icon, reveal ? 'eye-off' : 'eye');
        }
    });

    $(document).on('click', function () {
        closeFeatherPickers();
    });

    $(document).on('keydown', function (event) {
        if (event.key === 'Escape') {
            closeFeatherPickers();
        }
    });

    function resetGridDropdown($dropdown) {
        $dropdown.children('.dropdown-menu').css({
            position: '',
            top: '',
            left: '',
            right: '',
            bottom: '',
            margin: '',
            zIndex: '',
            minWidth: '',
            visibility: ''
        });
    }

    function placeGridDropdown($dropdown) {
        var $menu = $dropdown.children('.dropdown-menu');
        var toggle = $dropdown.children('[data-toggle="dropdown"]').get(0);

        if (!$menu.length || !toggle) {
            return;
        }

        var menu = $menu.get(0);

        $menu.css({
            position: 'fixed',
            top: '0px',
            left: '0px',
            right: 'auto',
            bottom: 'auto',
            margin: '0',
            zIndex: 80,
            minWidth: '10rem',
            visibility: 'hidden'
        });

        var toggleRect = toggle.getBoundingClientRect();
        var menuRect = menu.getBoundingClientRect();
        var gap = 6;
        var inToolbar = $dropdown.closest('.grid-toolbar, .box-header').length;
        var footer = inToolbar ? null : $dropdown.closest('.grid-box').find('.border-t').last().get(0);
        var footerTop = footer ? footer.getBoundingClientRect().top : window.innerHeight;
        var limitBottom = Math.min(window.innerHeight, footerTop);
        var spaceBelow = limitBottom - toggleRect.bottom;
        var spaceAbove = toggleRect.top;
        var openUp = spaceBelow < menuRect.height + gap && spaceAbove > menuRect.height + gap;
        var top = openUp
            ? Math.max(8, toggleRect.top - menuRect.height - gap)
            : Math.min(window.innerHeight - menuRect.height - 8, toggleRect.bottom + gap);
        var rtl = document.documentElement.getAttribute('dir') === 'rtl';
        var left = rtl ? toggleRect.left : toggleRect.right - menuRect.width;

        left = Math.max(8, Math.min(left, window.innerWidth - menuRect.width - 8));

        $menu.css({
            top: top + 'px',
            left: left + 'px',
            visibility: 'visible'
        });
    }

    function closeDropdowns(except) {
        $('.open').each(function () {
            var $node = $(this);

            // Flatpickr shows its calendar with the same "open" class.
            // Only Bootstrap-style menus (a direct .dropdown-menu child) should close.
            if (!$node.children('.dropdown-menu').length) {
                return;
            }

            if (!except || !except.is(this)) {
                if ($node.hasClass('grid-dropdown-actions') || $node.closest('.grid-box').length) {
                    resetGridDropdown($node);
                }
                $node.removeClass('open');
            }
        });
    }

    $(document).on('click', '[data-sidebar-toggle]', function (event) {
        event.preventDefault();

        if (window.matchMedia('(max-width: 767px)').matches) {
            document.body.classList.toggle('sidebar-open');
            return;
        }

        document.body.classList.toggle('sidebar-collapsed');
    });

    $(document).on('click', '.sidebar-menu li.treeview > a', function (event) {
        var $link = $(this);
        var $submenu = $link.next('.treeview-menu');

        if (!$submenu.length) {
            return;
        }

        event.preventDefault();
        $link.parent().toggleClass('active');
        $submenu.toggleClass('menu-open');
    });

    $(document).on('click', '[data-widget="collapse"]', function (event) {
        event.preventDefault();
        var $box = $(this).closest('.box');
        $box.children('.box-body').toggle();
        LA.toggleFeather($(this).find('[data-feather]'), 'minus', 'plus');
    });

    $(document).on('click', '[data-widget="remove"]', function (event) {
        event.preventDefault();
        $(this).closest('.box').remove();
    });

    function repositionOpenMenus() {
        $('.open').each(function () {
            var $node = $(this);

            if (!$node.children('.dropdown-menu').length) {
                return;
            }

            if ($node.hasClass('grid-dropdown-actions') || $node.closest('.grid-box').length) {
                placeGridDropdown($node);
            }
        });
    }

    $(window).on('resize', repositionOpenMenus);

    document.addEventListener('scroll', repositionOpenMenus, true);

    $(document).on('click', '[data-toggle="dropdown"]', function (event) {
        event.preventDefault();
        event.stopImmediatePropagation();
        var $parent = $(this).parent();
        var open = $parent.hasClass('open');
        closeDropdowns();
        if (!open) {
            $parent.addClass('open');
            if ($parent.hasClass('grid-dropdown-actions') || $parent.closest('.grid-box').length) {
                placeGridDropdown($parent);
            }
        }
    });

    $(document).on('click', '.dropdown-menu', function (event) {
        event.stopPropagation();
    });

    $(document).on('click', function (event) {
        if ($(event.target).closest('[data-toggle="dropdown"], .dropdown-menu').length) {
            return;
        }

        closeDropdowns();
    });

    function showTab($link) {
        var selector = $link.attr('href');
        var $pane = selector ? $(selector) : $();

        $link.parent('li').addClass('active').siblings('li').removeClass('active');

        if ($pane.length) {
            $pane.addClass('active').siblings('.tab-pane').removeClass('active');
        }
    }

    $.fn.tab = function (action) {
        if (action === 'show') {
            return this.each(function () {
                showTab($(this));
            });
        }

        return this;
    };

    $(document).on('click', '[data-toggle="tab"]', function (event) {
        var href = $(this).attr('href') || '';

        if (href.charAt(0) === '#') {
            event.preventDefault();
            $(this).tab('show');
        }
    });

    function modalBackdrop(show) {
        var $backdrop = $('.modal-backdrop');

        if (show) {
            if (!$backdrop.length) {
                $backdrop = $('<div class="modal-backdrop in"></div>').appendTo(document.body);
            }
            $('body').addClass('modal-open');
            return;
        }

        if (!$('.modal.in').length) {
            $backdrop.remove();
            $('body').removeClass('modal-open');
        }
    }

    function showModal($modal) {
        $modal.addClass('in').attr('aria-hidden', 'false').show();
        modalBackdrop(true);
        $modal.trigger('shown.bs.modal');
    }

    function hideModal($modal) {
        $modal.removeClass('in').attr('aria-hidden', 'true').hide();
        modalBackdrop(false);
        $modal.trigger('hidden.bs.modal');
    }

    $.fn.modal = function (action) {
        return this.each(function () {
            var $modal = $(this);

            if (action === 'hide') {
                hideModal($modal);
            } else if (action === 'toggle') {
                $modal.hasClass('in') ? hideModal($modal) : showModal($modal);
            } else {
                showModal($modal);
            }
        });
    };

    $(document).on('click', '[data-toggle="modal"]', function (event) {
        var target = $(this).data('target') || $(this).attr('href');

        if (!target || target.charAt(0) !== '#') {
            return;
        }

        event.preventDefault();
        $(target).modal('show');
    });

    $(document).on('click', '[data-dismiss="modal"]', function (event) {
        event.preventDefault();
        $(this).closest('.modal').modal('hide');
    });

    $(document).on('click', '.modal', function (event) {
        if (event.target === this) {
            $(this).modal('hide');
        }
    });

    $(document).on('keydown', function (event) {
        if (event.key === 'Escape') {
            hideModal($('.modal.in').last());
        }
    });

    $(document).on('click', '[data-dismiss="alert"]', function () {
        $(this).closest('.alert, .callout').remove();
    });

    $(document).on('click', '[data-toggle="collapse"]', function (event) {
        var target = $(this).data('target') || $(this).attr('href');

        if (!target || String(target).charAt(0) !== '#') {
            return;
        }

        event.preventDefault();
        $(target).toggleClass('in');
    });

    $.fn.button = function (action) {
        return this.each(function () {
            var $button = $(this);

            if (action === 'loading') {
                if ($button.data('la-original') === undefined) {
                    $button.data('la-original', $button.html());
                }
                $button.prop('disabled', true).text($button.data('loading-text') || '...');
            } else if (action === 'reset') {
                $button.prop('disabled', false);

                if ($button.data('la-original') !== undefined) {
                    $button.html($button.data('la-original'));
                }
            }
        });
    };

    function Popover($element, options) {
        this.$element = $element;
        this.options = options || {};
        this.$tip = $();
    }

    Popover.prototype.show = function () {
        var content = '';

        if (typeof this.options.content === 'function') {
            content = this.options.content.call(this.$element[0]);
        } else if (this.options.content) {
            content = this.options.content;
        } else {
            content = this.$element.attr('data-content') || '';
        }

        this.hide();
        this.$tip = $('<div class="la-popover"></div>');

        if (this.options.html) {
            this.$tip.html(content);
        } else {
            this.$tip.text(content);
        }

        var container = this.options.container && this.options.container !== false
            ? $(this.options.container)
            : this.$element.parent();

        container.append(this.$tip);

        var placement = 'bottom';

        if (typeof this.options.placement === 'function') {
            placement = this.options.placement(this.$tip[0], this.$element[0]) || 'bottom';
        } else if (this.options.placement) {
            placement = this.options.placement;
        }

        var rect = this.$element[0].getBoundingClientRect();
        var top = window.scrollY + rect.bottom + 8;
        var left = window.scrollX + rect.left;

        if (placement === 'top') {
            top = window.scrollY + rect.top - this.$tip.outerHeight() - 8;
        } else if (placement === 'right') {
            top = window.scrollY + rect.top;
            left = window.scrollX + rect.right + 8;
        } else if (placement === 'left') {
            top = window.scrollY + rect.top;
            left = window.scrollX + rect.left - this.$tip.outerWidth() - 8;
        }

        this.$tip.css({ top: top, left: left });
        this.$element.trigger('shown.bs.popover');
    };

    Popover.prototype.hide = function () {
        if (this.$tip && this.$tip.length) {
            this.$tip.remove();
        }
        this.$tip = $();
        this.$element.trigger('hidden.bs.popover');
    };

    Popover.prototype.toggle = function () {
        if (this.$tip && this.$tip.length) {
            this.hide();
        } else {
            this.show();
        }
    };

    $.fn.popover = function (option) {
        return this.each(function () {
            var $element = $(this);
            var instance = $element.data('bs.popover');

            if (typeof option === 'string') {
                if (instance && typeof instance[option] === 'function') {
                    instance[option]();
                }
                return;
            }

            if (!instance) {
                instance = new Popover($element, option || {});
                $element.data('bs.popover', instance);
            }
        });
    };

    $.fn.bootstrapSwitch = function (options) {
        options = options || {};

        return this.each(function () {
            var $input = $(this);

            if ($input.data('la-switch')) {
                return;
            }

            $input.data('la-switch', true).addClass('sr-only').attr('tabindex', '-1');
            $input.wrap('<span class="bootstrap-switch la-switch" role="switch" tabindex="0"></span>');

            var $wrap = $input.parent();
            var $track = $('<span class="la-switch-track"><span class="la-switch-thumb"></span></span>');
            var $label = $('<span class="la-switch-label"></span>');
            $wrap.append($track, $label);

            if (options.size === 'mini' || options.size === 'small') {
                $wrap.addClass('la-switch-sm');
            }

            function paint() {
                var on = $input.prop('checked');
                $wrap.toggleClass('is-on', on).attr('aria-checked', on ? 'true' : 'false');
                $label.text(on ? (options.onText || 'ON') : (options.offText || 'OFF'));
            }

            paint();

            $wrap.on('click', function (event) {
                event.preventDefault();
                toggle();
            });

            $wrap.on('keydown', function (event) {
                if (event.key === ' ' || event.key === 'Enter') {
                    event.preventDefault();
                    toggle();
                }
            });

            function toggle() {
                var next = !$input.prop('checked');
                var result;

                if (typeof options.onSwitchChange === 'function') {
                    result = options.onSwitchChange.call($input[0], { target: $input[0] }, next);
                }

                if (result === false) {
                    return;
                }

                $input.prop('checked', next);
                paint();
            }
        });
    };

    $.fn.bootstrapNumber = function () {
        return this.each(function () {
            var $input = $(this);

            if ($input.data('la-number')) {
                return;
            }

            $input.data('la-number', true);
            var $down = $('<button type="button" class="btn btn-default la-number-btn" tabindex="-1">−</button>');
            var $up = $('<button type="button" class="btn btn-default la-number-btn" tabindex="-1">+</button>');
            $input.addClass('la-number-input').before($down).after($up);

            function change(direction) {
                var step = parseFloat($input.attr('step') || '1');
                var value = parseFloat($input.val());
                var min = $input.attr('min');
                var max = $input.attr('max');

                if (isNaN(value)) {
                    value = 0;
                }
                if (isNaN(step)) {
                    step = 1;
                }

                value = Math.round((value + (direction * step)) * 1000) / 1000;

                if (min !== undefined && value < parseFloat(min)) {
                    value = parseFloat(min);
                }
                if (max !== undefined && value > parseFloat(max)) {
                    value = parseFloat(max);
                }

                $input.val(value).trigger('change');
            }

            $down.on('click', function () { change(-1); });
            $up.on('click', function () { change(1); });
        });
    };

    function momentToFlatpickr(format) {
        return String(format || 'Y-m-d')
            .replace(/YYYY/g, 'Y')
            .replace(/YY/g, 'y')
            .replace(/MMMM/g, 'F')
            .replace(/MMM/g, 'M')
            .replace(/MM/g, 'm')
            .replace(/DD/g, 'd')
            .replace(/dd/g, 'd')
            .replace(/HH/g, 'H')
            .replace(/hh/g, 'h')
            .replace(/mm/g, 'i')
            .replace(/ss/g, 'S')
            .replace(/A/g, 'K');
    }

    function fakeMoment(date, format) {
        return {
            _date: date,
            toDate: function () { return date; },
            format: function (fmt) {
                return window.flatpickr.formatDate(date, momentToFlatpickr(fmt || format));
            }
        };
    }

    function normalizeDate(value) {
        if (!value) {
            return null;
        }
        if (value instanceof Date) {
            return value;
        }
        if (value._date) {
            return value._date;
        }
        if (typeof value.toDate === 'function') {
            return value.toDate();
        }
        return value;
    }

    $.fn.datetimepicker = function (options) {
        if (typeof options === 'string' || options === undefined) {
            var api = this.data('DateTimePicker');
            return api || this;
        }

        return this.each(function () {
            var $root = $(this);
            var input = this.tagName === 'INPUT' ? this : $root.find('input').get(0);

            if (!input || !window.flatpickr) {
                return;
            }

            if (input._flatpickr) {
                input._flatpickr.destroy();
            }

            var format = options.format || 'YYYY-MM-DD';
            var flatFormat = momentToFlatpickr(format);
            var hasTime = /[HhisaA]/.test(format) || /H|h|i|S|K/.test(flatFormat);
            var dateOnly = !/[Hh]/.test(format) && !/mm|ss/.test(format);
            var timeOnly = !/Y|M|D|d/.test(format);
            var locale = options.locale && window.flatpickr.l10ns[options.locale]
                ? window.flatpickr.l10ns[options.locale]
                : 'default';

            var fp = window.flatpickr(input, {
                dateFormat: flatFormat,
                enableTime: hasTime && !dateOnly || timeOnly,
                noCalendar: timeOnly,
                enableSeconds: /ss/.test(format),
                time_24hr: !/A|a/.test(format),
                inline: !!options.inline,
                clickOpens: options.allowInputToggle !== false,
                allowInput: true,
                defaultDate: options.date || input.value || null,
                locale: locale,
                disableMobile: true,
                onChange: function (dates) {
                    var date = dates[0] ? fakeMoment(dates[0], format) : null;
                    $root.trigger($.Event('dp.change', { date: date }));
                },
                onKeyDown: function (selectedDates, dateStr, instance, event) {
                    // allowInput skips flatpickr's own Escape handling while the input is focused.
                    if (event.key === 'Escape' || event.keyCode === 27) {
                        instance.close();
                    }
                }
            });

            $root.data('DateTimePicker', {
                minDate: function (value) {
                    fp.set('minDate', normalizeDate(value));
                    return this;
                },
                maxDate: function (value) {
                    fp.set('maxDate', normalizeDate(value));
                    return this;
                }
            });
        });
    };

    $.fn.colorpicker = function () {
        return this.each(function () {
            var $group = $(this);
            var $input = $group.is('input') ? $group : $group.find('input[type="text"], input:not([type="color"])').first();
            var $icon = $group.find('.input-group-addon i').first();

            if ($input.data('la-color')) {
                return;
            }

            $input.data('la-color', true);
            var $picker = $('<input type="color" class="la-color-input" aria-label="Color">');

            function paint(value) {
                var hex = String(value || '').trim();

                if (/^#([0-9a-f]{6})$/i.test(hex)) {
                    $picker.val(hex);
                    $icon.css('background-color', hex);
                }
            }

            $picker.on('input', function () {
                $input.val(this.value).trigger('change');
                $icon.css('background-color', this.value);
            });

            $input.on('input', function () {
                paint($input.val());
            });

            if ($icon.length) {
                $icon.before($picker);
            } else {
                $input.after($picker);
            }

            paint($input.val());
        });
    };

    function renderListbox($select, settings) {
        settings = settings || {};
        var $ui = $select.next('.la-listbox');

        if (!$ui.length) {
            $ui = $('<div class="la-listbox"></div>');
            $select.after($ui);
        }

        $select.addClass('la-listbox-source');
        $ui.empty();

        var height = settings.selectorMinimalHeight || 200;
        var $available = $('<select multiple class="la-listbox-pane"></select>').css('min-height', height);
        var $chosen = $('<select multiple class="la-listbox-pane"></select>').css('min-height', height);
        var $filterLeft = $('<input type="search" class="form-control la-listbox-filter">').attr('placeholder', settings.filterPlaceHolder || 'Filter');
        var $filterRight = $filterLeft.clone();

        function fill() {
            $available.empty();
            $chosen.empty();
            $select.find('option').each(function () {
                var selected = this.selected;
                var $option = $(this).clone();
                $option.prop('selected', false);
                (selected ? $chosen : $available).append($option);
            });
        }

        function apply(values, selected) {
            $select.find('option').each(function () {
                if (values.indexOf(this.value) !== -1) {
                    this.selected = selected;
                }
            });
            fill();
            $select.trigger('change');
        }

        function move($from, selected) {
            var values = $from.val() || [];
            apply(values, selected);
        }

        function filter($input, $pane) {
            var query = ($input.val() || '').toLowerCase();
            $pane.find('option').each(function () {
                $(this).toggle(!query || $(this).text().toLowerCase().indexOf(query) !== -1);
            });
        }

        function optionFromEvent(pane, event) {
            var target = event.target;

            if (target && target.tagName === 'OPTION' && pane.contains(target)) {
                return target;
            }

            var x = event.clientX;
            var y = event.clientY;
            var options = pane.options;
            var index;

            for (index = 0; index < options.length; index++) {
                var rect = options[index].getBoundingClientRect();

                if (rect.width && rect.height && x >= rect.left && x <= rect.right && y >= rect.top && y <= rect.bottom) {
                    return options[index];
                }
            }

            return null;
        }

        function bindClickMove($pane, selected) {
            $pane.on('click', function (event) {
                var option = optionFromEvent(this, event);

                if (!option || option.disabled || option.getAttribute('data-la-moved')) {
                    return;
                }

                option.setAttribute('data-la-moved', '1');
                event.preventDefault();
                event.stopImmediatePropagation();
                apply([option.value], selected);
            });
        }

        fill();
        $filterLeft.on('input', function () { filter($filterLeft, $available); });
        $filterRight.on('input', function () { filter($filterRight, $chosen); });
        bindClickMove($available, true);
        bindClickMove($chosen, false);

        var $controls = $('<div class="la-listbox-controls"></div>');
        var $allRight = $('<button type="button" class="btn btn-default btn-sm">≫</button>');
        var $right = $('<button type="button" class="btn btn-default btn-sm">›</button>');
        var $left = $('<button type="button" class="btn btn-default btn-sm">‹</button>');
        var $allLeft = $('<button type="button" class="btn btn-default btn-sm">≪</button>');

        $right.on('click', function () { move($available, true); });
        $left.on('click', function () { move($chosen, false); });
        $allRight.on('click', function () {
            apply($available.find('option').map(function () { return this.value; }).get(), true);
        });
        $allLeft.on('click', function () {
            apply($chosen.find('option').map(function () { return this.value; }).get(), false);
        });

        $controls.append($allRight, $right, $left, $allLeft);
        $ui.append(
            $('<div class="la-listbox-col"></div>').append($filterLeft, $available),
            $controls,
            $('<div class="la-listbox-col"></div>').append($filterRight, $chosen)
        );

        $select.data('la-listbox', {
            refresh: function () {
                renderListbox($select, settings);
            }
        });
    }

    $.fn.bootstrapDualListbox = function (option) {
        if (typeof option === 'string') {
            return this.each(function () {
                var api = $(this).data('la-listbox');
                if (api && typeof api[option] === 'function') {
                    api[option]();
                }
            });
        }

        return this.each(function () {
            renderListbox($(this), option || {});
        });
    };

    $.fn.fileinput = function (options) {
        options = options || {};

        return this.each(function () {
            var $input = $(this);

            if ($input.data('la-file')) {
                return;
            }

            $input.data('la-file', true);
            var previews = options.initialPreview || $input.data('initial-preview') || [];
            var configs = options.initialPreviewConfig || [];
            var actions = options.fileActionSettings || {};
            var canRemove = actions.showRemove || options.showRemove;
            var multiple = !!$input.prop('multiple');
            var allowDrop = options.dropZoneEnabled !== false;

            if (!$.isArray(previews)) {
                previews = previews ? [previews] : [];
            }

            var $box = $('<div class="la-file"></div>');
            var $list = $('<div class="la-file-list"></div>');
            var $zone = $('<label class="la-dropzone"></label>');
            var $hint = $('<span class="la-dropzone-hint"></span>').text(options.msgPlaceholder || 'Choose a file');
            var $icon = $('<span class="la-dropzone-icon"></span>');

            if (window.LA && typeof LA.feather === 'function') {
                $icon.html(LA.feather('upload'));
            }

            function isImageName(name) {
                return /\.(png|jpe?g|gif|webp|svg)(\?|$)/i.test(String(name || ''));
            }

            function sorted() {
                var stack = [];
                $list.children('[data-key]').each(function () {
                    stack.push({ key: $(this).data('key') });
                });
                $input.trigger('filesorted', { stack: stack });
            }

            function toggleInitial() {
                var hasNew = $input[0].files && $input[0].files.length;
                if (options.overwriteInitial) {
                    $list.find('[data-initial]').toggle(!hasNew);
                }
            }

            $.each(previews, function (index, url) {
                var config = configs[index] || {};
                var $item = $('<div class="la-file-item" data-initial="1"></div>')
                    .attr('data-key', config.key === undefined ? index : config.key);

                if (actions.showDrag) {
                    $item.attr('draggable', 'true');
                }

                if (config.type === 'image' || isImageName(url) || isImageName(config.caption)) {
                    $item.append($('<img class="la-file-thumb" alt="">').attr('src', url));
                }

                $item.append(
                    $('<a class="la-file-link" target="_blank"></a>')
                        .attr('href', config.downloadUrl || url)
                        .text(config.caption || url)
                );

                if (canRemove) {
                    $('<button type="button" class="la-file-remove" aria-label="Remove">&times;</button>')
                        .on('click', function (event) {
                            event.preventDefault();
                            event.stopPropagation();
                            var result = $input.triggerHandler('filebeforedelete');
                            var remove = function () {
                                if (options.deleteUrl) {
                                    $.post(options.deleteUrl, $.extend({}, options.deleteExtraData, { key: config.key }))
                                        .done(function () { $item.remove(); });
                                    return;
                                }
                                $item.remove();
                            };

                            if (result && typeof result.then === 'function') {
                                result.then(remove);
                            } else {
                                remove();
                            }
                        })
                        .appendTo($item);
                }

                $list.append($item);
            });

            if (actions.showDrag) {
                var dragged = null;
                $list.on('dragstart', '.la-file-item', function () { dragged = this; });
                $list.on('dragover', '.la-file-item', function (event) { event.preventDefault(); });
                $list.on('drop', '.la-file-item', function (event) {
                    event.preventDefault();
                    event.stopPropagation();
                    if (!dragged || dragged === this) {
                        return;
                    }
                    if ($(this).index() < $(dragged).index()) {
                        $(this).before(dragged);
                    } else {
                        $(this).after(dragged);
                    }
                    sorted();
                });
            }

            function syncLocal() {
                $list.find('[data-local]').each(function () {
                    var url = $(this).find('img').attr('src');
                    if (url && url.indexOf('blob:') === 0) {
                        URL.revokeObjectURL(url);
                    }
                }).remove();

                $.each($input[0].files || [], function (_, file) {
                    var $item = $('<div class="la-file-item" data-local="1"></div>');

                    if ((file.type && file.type.indexOf('image/') === 0) || isImageName(file.name)) {
                        $item.append($('<img class="la-file-thumb" alt="">').attr('src', URL.createObjectURL(file)));
                    }

                    $item.append($('<span class="la-file-link"></span>').text(file.name));
                    $('<button type="button" class="la-file-remove" aria-label="Remove">&times;</button>')
                        .on('click', function (event) {
                            event.preventDefault();
                            event.stopPropagation();
                            var transfer = new DataTransfer();
                            $.each($input[0].files || [], function (__, current) {
                                if (current !== file) {
                                    transfer.items.add(current);
                                }
                            });
                            $input[0].files = transfer.files;
                            syncLocal();
                        })
                        .appendTo($item);
                    $list.append($item);
                });

                toggleInitial();
            }

            $input.on('change', syncLocal);

            if (allowDrop) {
                $zone.on('dragenter dragover', function (event) {
                    event.preventDefault();
                    event.stopPropagation();
                    $zone.addClass('is-dragover');
                });
                $zone.on('dragleave drop', function (event) {
                    event.preventDefault();
                    event.stopPropagation();
                    $zone.removeClass('is-dragover');
                });
                $zone.on('drop', function (event) {
                    var dropped = event.originalEvent.dataTransfer && event.originalEvent.dataTransfer.files;
                    if (!dropped || !dropped.length) {
                        return;
                    }

                    var transfer = new DataTransfer();
                    if (multiple) {
                        $.each($input[0].files || [], function (_, file) { transfer.items.add(file); });
                        $.each(dropped, function (_, file) { transfer.items.add(file); });
                    } else {
                        transfer.items.add(dropped[0]);
                    }

                    $input[0].files = transfer.files;
                    $input.trigger('change');
                });
            }

            $zone.append($icon, $hint);
            $box.append($list, $zone);
            $input.addClass('la-file-input').before($box);
            $zone.append($input);
        });
    };

    if (!$.fn.tooltip) {
        $.fn.tooltip = function () {
            return this;
        };
    }
})(jQuery);
