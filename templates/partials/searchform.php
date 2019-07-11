<form role="search" method="get" action="<?php echo esc_url(home_url('/')) ?>">
    <label for="search-input" class="show-for-sr"><?php echo _x( 'Search for:', 'label', pf( 'textdomain' )); ?></label>
    <div class="input-group margin-0">
        <input class="input-group-field" type="search" id="search-input" value="<?php echo esc_attr( get_search_query() ) ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label', pf( 'textdomain' ) ); ?>">
        <div class="input-group-button">
            <button type="submit" class="button">
                <span class="show-for-sr"><?php _e( 'Search', 'submit button', pf( 'textdomain' ) ); ?></span>
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>
</form>
