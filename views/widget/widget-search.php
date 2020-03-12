<?php $view->style('search', 'search:assets/css/searchwidget.css') ?>
<div <?php if ($css_enabled) : ?> class="tm-search uk-hidden-small" <?php endif ?>>
</div>
<div class="uk-navbar-right">
    <div>
        <a class="uk-search-navbar-form" uk-search-icon href="#"></a>
        <div class="uk-drop" uk-drop="mode: click; pos: left-center; offset: 0">
            <form class="uk-search uk-search-navbar uk-width-1-1" id="search-<?php echo $widget->id; ?>" action="<?= $view->url('@search/submit') ?>" method="post" role="search" <?php if ($widget->position !== 'offcanvas') : ?>data-uk-search="{'source': '<?= $view->url('@search/site') ?>?tmpl=raw&type=json&itemid=<?php echo $widget->id; ?>&ordering=&searchphrase=all&searchword=', 'param': 'searchword', 'msgResultsHeader': '<?php echo __('Search Results'); ?>', 'msgMoreResults': '<?php echo __('More Results'); ?>', 'msgNoResults': '<?php echo __('No results found'); ?>', 'minLength': '<?php echo $triggering_chars; ?>','delay':'900', flipDropdown: 1}" <?php endif; ?>>
                <input class="uk-search-input" type="search" name="search[searchword]" placeholder="<?php echo __('search...'); ?>" maxlength="<?= $upper_limit; ?>" autofocus>
                <input type="hidden" name="search[task]" value="searchwidget">
                <?php $view->token()->get() ?>
            </form>
        </div>
    </div>
</div>
</div>