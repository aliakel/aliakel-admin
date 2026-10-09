<?php

namespace AliAkel\Admin\Icons;

class Feather
{
    /**
     * @var array<string, string>|null
     */
    protected static $paths;

    /**
     * @var array<string, string>
     */
    protected static $modifiers = [
        'fa-fw'    => 'feather-fw',
        'fa-lg'    => 'feather-lg',
        'fa-2x'    => 'feather-lg',
        'fa-3x'    => 'feather-3x',
        'fa-4x'    => 'feather-3x',
        'fa-5x'    => 'feather-3x',
        'fa-spin'  => 'feather-spin',
        'fa-pulse' => 'feather-spin',
    ];

    /**
     * Legacy Font Awesome names (without the fa- prefix) that do not match a Feather name.
     *
     * @var array<string, string>
     */
    protected static $aliases = [
        'bars'                    => 'menu',
        'navicon'                 => 'menu',
        'reorder'                 => 'menu',
        'tasks'                   => 'check-square',
        'ban'                     => 'slash',
        'history'                 => 'clock',
        'dashboard'               => 'home',
        'tachometer'              => 'activity',
        'pencil'                  => 'edit',
        'pencil-square'           => 'edit-3',
        'warning'                 => 'alert-triangle',
        'exclamation-triangle'    => 'alert-triangle',
        'exclamation-circle'      => 'alert-circle',
        'exclamation'             => 'alert-circle',
        'info-circle'             => 'info',
        'question-circle'         => 'help-circle',
        'question'                => 'help-circle',
        'times'                   => 'x',
        'times-circle'            => 'x-circle',
        'times-rectangle'         => 'x-square',
        'window-close'            => 'x-square',
        'remove'                  => 'x',
        'close'                   => 'x',
        'angle-left'              => 'chevron-left',
        'angle-right'             => 'chevron-right',
        'angle-up'                => 'chevron-up',
        'angle-down'              => 'chevron-down',
        'angle-double-left'       => 'chevrons-left',
        'angle-double-right'      => 'chevrons-right',
        'angle-double-up'         => 'chevrons-up',
        'angle-double-down'       => 'chevrons-down',
        'caret-up'                => 'chevron-up',
        'caret-down'              => 'chevron-down',
        'caret-left'              => 'chevron-left',
        'caret-right'             => 'chevron-right',
        'caret-square-down'       => 'chevron-down',
        'caret-square-up'         => 'chevron-up',
        'caret-square-left'       => 'chevron-left',
        'caret-square-right'      => 'chevron-right',
        'arrow-circle-right'      => 'arrow-right-circle',
        'arrow-circle-left'       => 'arrow-left-circle',
        'arrow-circle-up'         => 'arrow-up-circle',
        'arrow-circle-down'       => 'arrow-down-circle',
        'chevron-circle-left'     => 'arrow-left-circle',
        'chevron-circle-right'    => 'arrow-right-circle',
        'chevron-circle-up'       => 'arrow-up-circle',
        'chevron-circle-down'     => 'arrow-down-circle',
        'eye-slash'               => 'eye-off',
        'cloud-download'          => 'download-cloud',
        'cloud-upload'            => 'upload-cloud',
        'folder-open'             => 'folder',
        'internet-explorer'       => 'globe',
        'laptop'                  => 'monitor',
        'desktop'                 => 'monitor',
        'envelope'                => 'mail',
        'envelope-open'           => 'mail',
        'refresh'                 => 'refresh-cw',
        'spinner'                 => 'loader',
        'circle-o-notch'          => 'loader',
        'undo'                    => 'rotate-ccw',
        'rotate-left'             => 'rotate-ccw',
        'rotate-right'            => 'rotate-cw',
        'ellipsis-v'              => 'more-vertical',
        'ellipsis-h'              => 'more-horizontal',
        'arrows-alt'              => 'maximize',
        'arrows'                  => 'move',
        'arrows-v'                => 'more-vertical',
        'arrows-h'                => 'more-horizontal',
        'table'                   => 'grid',
        'qrcode'                  => 'grid',
        'th'                      => 'grid',
        'th-large'                => 'grid',
        'th-list'                 => 'list',
        'clone'                   => 'copy',
        'files'                   => 'copy',
        'sort'                    => 'chevrons-up',
        'sort-asc'                => 'arrow-up',
        'sort-desc'               => 'arrow-down',
        'sort-amount-asc'         => 'arrow-up',
        'sort-amount-desc'        => 'arrow-down',
        'sort-alpha-asc'          => 'arrow-up',
        'sort-alpha-desc'         => 'arrow-down',
        'sort-numeric-asc'        => 'arrow-up',
        'sort-numeric-desc'       => 'arrow-down',
        'cog'                     => 'settings',
        'cogs'                    => 'settings',
        'gear'                    => 'settings',
        'gears'                   => 'settings',
        'wrench'                  => 'tool',
        'map-marker'              => 'map-pin',
        'picture'                 => 'image',
        'photo'                   => 'image',
        'mobile'                  => 'smartphone',
        'mobile-phone'            => 'smartphone',
        'sign-out'                => 'log-out',
        'sign-in'                 => 'log-in',
        'power-off'               => 'power',
        'chain'                   => 'link',
        'chain-broken'            => 'link-2',
        'unlink'                  => 'link-2',
        'group'                   => 'users',
        'user-times'              => 'user-x',
        'user-secret'             => 'eye-off',
        'user-circle'             => 'user',
        'comment'                 => 'message-square',
        'comments'                => 'message-circle',
        'commenting'              => 'message-square',
        'money'                   => 'dollar-sign',
        'dollar'                  => 'dollar-sign',
        'usd'                     => 'dollar-sign',
        'eur'                     => 'dollar-sign',
        'gbp'                     => 'dollar-sign',
        'print'                   => 'printer',
        'floppy'                  => 'save',
        'life-ring'               => 'life-buoy',
        'life-bouy'               => 'life-buoy',
        'life-saver'              => 'life-buoy',
        'support'                 => 'life-buoy',
        'toggle-on'               => 'toggle-right',
        'toggle-off'              => 'toggle-left',
        'search-plus'             => 'zoom-in',
        'search-minus'            => 'zoom-out',
        'expand'                  => 'maximize',
        'compress'                => 'minimize',
        'reply'                   => 'corner-up-left',
        'mail-reply'              => 'corner-up-left',
        'mail-forward'            => 'corner-up-right',
        'long-arrow-up'           => 'arrow-up',
        'long-arrow-down'         => 'arrow-down',
        'long-arrow-left'         => 'arrow-left',
        'long-arrow-right'        => 'arrow-right',
        'step-backward'           => 'skip-back',
        'step-forward'            => 'skip-forward',
        'fast-backward'           => 'rewind',
        'volume-up'               => 'volume-2',
        'volume-down'             => 'volume-1',
        'volume-off'              => 'volume-x',
        'microphone'              => 'mic',
        'microphone-slash'        => 'mic-off',
        'video-camera'            => 'video',
        'hdd'                     => 'hard-drive',
        'random'                  => 'shuffle',
        'retweet'                 => 'repeat',
        'exchange'                => 'repeat',
        'share-alt'               => 'share-2',
        'paper-plane'             => 'send',
        'at'                      => 'at-sign',
        'hashtag'                 => 'hash',
        'television'              => 'tv',
        'area-chart'              => 'bar-chart-2',
        'line-chart'              => 'trending-up',
        'signal'                  => 'bar-chart-2',
        'bolt'                    => 'zap',
        'flash'                   => 'zap',
        'plug'                    => 'zap',
        'fire'                    => 'zap',
        'leaf'                    => 'feather',
        'bug'                     => 'alert-octagon',
        'cube'                    => 'box',
        'cubes'                   => 'layers',
        'sitemap'                 => 'git-branch',
        'code-fork'               => 'git-branch',
        'trophy'                  => 'award',
        'certificate'             => 'award',
        'shopping-basket'         => 'shopping-bag',
        'cart-plus'               => 'shopping-cart',
        'cart-arrow-down'         => 'shopping-cart',
        'heartbeat'               => 'activity',
        'medkit'                  => 'plus-square',
        'car'                     => 'truck',
        'taxi'                    => 'truck',
        'cab'                     => 'truck',
        'bus'                     => 'truck',
        'train'                   => 'truck',
        'subway'                  => 'truck',
        'automobile'              => 'truck',
        'building'                => 'home',
        'bank'                    => 'home',
        'institution'             => 'home',
        'university'              => 'book',
        'graduation-cap'          => 'book',
        'legal'                   => 'tool',
        'gavel'                   => 'tool',
        'balance-scale'           => 'sliders',
        'lightbulb'               => 'zap',
        'puzzle-piece'            => 'grid',
        'magic'                   => 'star',
        'rocket'                  => 'navigation-2',
        'plane'                   => 'navigation-2',
        'fighter-jet'             => 'navigation-2',
        'location-arrow'          => 'navigation',
        'crosshairs'              => 'crosshair',
        'bell-slash'              => 'bell-off',
        'sticky-note'             => 'file-text',
        'newspaper'               => 'file-text',
        'hourglass'               => 'clock',
        'hourglass-start'         => 'clock',
        'hourglass-half'          => 'clock',
        'hourglass-end'           => 'clock',
        'hourglass-1'             => 'clock',
        'hourglass-2'             => 'clock',
        'hourglass-3'             => 'clock',
        'calculator'              => 'hash',
        'tty'                     => 'phone',
        'fax'                     => 'printer',
        'language'                => 'globe',
        'child'                   => 'user',
        'male'                    => 'user',
        'female'                  => 'user',
        'id-card'                 => 'credit-card',
        'id-badge'                => 'award',
        'address-card'            => 'user',
        'vcard'                   => 'user',
        'drivers-license'         => 'credit-card',
        'bed'                     => 'moon',
        'hotel'                   => 'home',
        'cutlery'                 => 'coffee',
        'beer'                    => 'coffee',
        'glass'                   => 'coffee',
        'birthday-cake'           => 'gift',
        'diamond'                 => 'award',
        'ship'                    => 'anchor',
        'bicycle'                 => 'circle',
        'motorcycle'              => 'circle',
        'paw'                     => 'feather',
        'tree'                    => 'git-branch',
        'paint-brush'             => 'pen-tool',
        'eyedropper'              => 'droplet',
        'tint'                    => 'droplet',
        'adjust'                  => 'sliders',
        'cut'                     => 'scissors',
        'paste'                   => 'clipboard',
        'font'                    => 'type',
        'header'                  => 'type',
        'paragraph'               => 'align-left',
        'strikethrough'           => 'minus',
        'quote-left'              => 'message-square',
        'quote-right'             => 'message-square',
        'list-alt'                => 'list',
        'list-ul'                 => 'list',
        'list-ol'                 => 'list',
        'level-up'                => 'corner-left-up',
        'level-down'              => 'corner-right-down',
        'toggle-down'             => 'chevron-down',
        'toggle-up'               => 'chevron-up',
        'file-image'              => 'image',
        'file-photo'              => 'image',
        'file-picture'            => 'image',
        'file-archive'            => 'archive',
        'file-zip'                => 'archive',
        'file-audio'              => 'music',
        'file-sound'              => 'music',
        'file-video'              => 'video',
        'file-movie'              => 'video',
        'file-code'               => 'code',
        'file-pdf'                => 'file-text',
        'file-word'               => 'file-text',
        'file-excel'              => 'file-text',
        'file-powerpoint'         => 'file-text',
        'android'                 => 'smartphone',
        'apple'                   => 'smartphone',
        'windows'                 => 'monitor',
        'linux'                   => 'terminal',
        'firefox'                 => 'globe',
        'safari'                  => 'compass',
        'edge'                    => 'globe',
        'opera'                   => 'globe',
        'html5'                   => 'code',
        'css3'                    => 'code',
        'bitbucket'               => 'git-branch',
        'stack-overflow'          => 'layers',
        'dropbox'                 => 'box',
        'skype'                   => 'phone',
        'whatsapp'                => 'phone',
        'telegram'                => 'send',
        'wechat'                  => 'message-circle',
        'weixin'                  => 'message-circle',
        'reddit'                  => 'message-circle',
        'pinterest'               => 'map-pin',
        'spotify'                 => 'music',
        'soundcloud'              => 'music',
        'vimeo'                   => 'video',
        'snapchat'                => 'camera',
        'google'                  => 'globe',
        'paypal'                  => 'credit-card',
        'credit-card-alt'         => 'credit-card',
        'cc-visa'                 => 'credit-card',
        'cc-mastercard'           => 'credit-card',
        'cc-amex'                 => 'credit-card',
        'cc-paypal'               => 'credit-card',
        'cc-stripe'               => 'credit-card',
        'youtube-play'            => 'youtube',
        'youtube-square'          => 'youtube',
        'facebook-official'       => 'facebook',
        'facebook-square'         => 'facebook',
        'linkedin-square'         => 'linkedin',
        'wordpress'               => 'globe',
        'drupal'                  => 'globe',
        'joomla'                  => 'globe',
        'battery-full'            => 'battery',
        'battery-empty'           => 'battery',
        'battery-half'            => 'battery',
        'battery-quarter'         => 'battery',
        'battery-three-quarters'  => 'battery',
        'microchip'               => 'cpu',
        'snowflake'               => 'cloud-snow',
        'shower'                  => 'droplet',
        'bath'                    => 'droplet',
        'podcast'                 => 'radio',
        'window-maximize'         => 'maximize',
        'window-minimize'         => 'minimize',
        'window-restore'          => 'copy',
        'circle-thin'             => 'circle',
        'dot-circle'              => 'disc',
        'star-half'               => 'star',
        'hand-pointer'            => 'mouse-pointer',
        'keyboard'                => 'type',
        'camera-retro'            => 'camera',
        'barcode'                 => 'align-justify',
        'share-square'            => 'share',
        'external-link-square'    => 'external-link',
        'phone-square'            => 'phone',
        'rss-square'              => 'rss',
        'stop'                    => 'square',
        'backward'                => 'rewind',
        'forward'                 => 'fast-forward',
        'eject'                   => 'log-out',
        'font-awesome'            => 'feather',
    ];

    public static function render($name = '', $class = ''): string
    {
        $icon = self::canonical((string) $name);
        $classes = self::extraClasses((string) $name, (string) $class);

        if ($icon === '') {
            return '';
        }

        $paint = self::exists($icon) ? $icon : 'circle';
        $inner = self::paths()[$paint] ?? '';
        $classAttr = trim('feather-icon '.$classes);

        return '<svg class="'.htmlspecialchars($classAttr, ENT_QUOTES, 'UTF-8').'" data-feather="'.htmlspecialchars($paint, ENT_QUOTES, 'UTF-8').'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'.$inner.'</svg>';
    }

    /**
     * Feather name for a stored value. Unknown names are returned unchanged.
     */
    public static function canonical(string $name): string
    {
        $token = strtolower(self::iconToken($name));

        if ($token === '') {
            return '';
        }

        if (strpos($token, 'fa-') === 0) {
            $token = substr($token, 3);
        }

        if (self::exists($token)) {
            return $token;
        }

        $stripped = (string) preg_replace('/-o$/', '', $token);

        if (self::exists($stripped)) {
            return $stripped;
        }

        if (isset(self::$aliases[$token])) {
            return self::$aliases[$token];
        }

        if (isset(self::$aliases[$stripped])) {
            return self::$aliases[$stripped];
        }

        if (strpos($stripped, 'file-') === 0) {
            return self::$aliases[$stripped] ?? 'file';
        }

        return $token;
    }

    public static function exists(string $name): bool
    {
        return isset(self::paths()[$name]);
    }

    /**
     * @return array<int, string>
     */
    public static function names(): array
    {
        $names = array_keys(self::paths());
        sort($names);

        return $names;
    }

    public static function spriteUrl(): string
    {
        return admin_asset('vendor/laravel-admin/feather/feather-sprite.svg');
    }

    /**
     * @return array<string, string>
     */
    protected static function paths(): array
    {
        if (self::$paths !== null) {
            return self::$paths;
        }

        $svg = (string) file_get_contents(dirname(__DIR__, 2).'/resources/assets/feather/feather-sprite.svg');
        preg_match_all('#<symbol id="([^"]+)"[^>]*>(.*?)</symbol>#s', $svg, $matches, PREG_SET_ORDER);

        $paths = [];

        foreach ($matches as $match) {
            $paths[$match[1]] = trim($match[2]);
        }

        return self::$paths = $paths;
    }

    protected static function iconToken(string $name): string
    {
        foreach (preg_split('/\s+/', trim($name), -1, PREG_SPLIT_NO_EMPTY) as $token) {
            if ($token === 'fa' || $token === 'icon' || isset(self::$modifiers[$token]) || self::isUtility($token)) {
                continue;
            }

            return $token;
        }

        return '';
    }

    protected static function extraClasses(string $name, string $class): string
    {
        $classes = [];
        $iconTaken = false;

        foreach (preg_split('/\s+/', trim($name.' '.$class), -1, PREG_SPLIT_NO_EMPTY) as $token) {
            if ($token === 'fa' || $token === 'icon') {
                continue;
            }

            if (isset(self::$modifiers[$token])) {
                $classes[] = self::$modifiers[$token];
                continue;
            }

            if (!$iconTaken && !self::isUtility($token)) {
                $iconTaken = true;
                continue;
            }

            if (strpos($token, 'fa-') === 0) {
                continue;
            }

            $classes[] = $token;
        }

        return implode(' ', array_unique($classes));
    }

    protected static function isUtility(string $token): bool
    {
        return (bool) preg_match('/^(?:w-|h-|min-|max-|text-|bg-|border|ms-|me-|ps-|pe-|px-|py-|p-|m-|hidden|inline|block|flex|grid|items-|justify-|rounded|sr-only|pull-|la-|ext-|gap-|space-|truncate|font-|leading-|tracking-|uppercase|opacity-|cursor-|overflow-|object-|shrink|grow|z-|absolute|relative|fixed|sticky|clearfix|hidden-xs)/', $token);
    }
}
