<form role="search" method="get" action="<?= esc_url(home_url('/')) ?>">
    <label for="search-input" class="show-for-sr"><?= _x( 'Search for:', 'label', pf( 'textdomain' )); ?></label>
    <div class="input-group margin-0">
        <input class="input-group-field" type="search" id="search-input" value="<?= esc_attr( get_search_query() ) ?>" name="s" title="<?= esc_attr_x( 'Search for:', 'label', pf( 'textdomain' ) ); ?>">
        <div class="input-group-button">
            <button type="submit" class="button">
                <span class="show-for-sr"><?php _e( 'Search', 'submit button', pf( 'textdomain' ) ); ?></span>
                <span class="fas fa-search" aria-hidden="true"></span>
            </button>
        </div>
    </div>
</form>
