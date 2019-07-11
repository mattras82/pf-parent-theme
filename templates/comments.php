<?php if ( post_password_required() ) return; ?>

<div id="comments" class="comments-area">
    <?php if ( have_comments() ) : ?>
        <h2 class="comments-title">
            <?php
            $comments_number = get_comments_number();
            printf(
                _nx(
                    '%1$s Reply to &ldquo;%2$s&rdquo;',
                    '%1$s Replies to &ldquo;%2$s&rdquo;',
                    $comments_number,
                    'comments title',
                    pf('textdomain')
                ),
                number_format_i18n( $comments_number ),
                get_the_title()
            );
            ?>
        </h2>
        <ol class="comment-list no-bullet">
            <?php wp_list_comments([
                'style'       => 'ol',
                'short_ping'  => true,
                'reply_text'  => __( 'Reply', pf('textdomain')),
            ]); ?>
        </ol>
        <?php the_comments_pagination( [
            'prev_text' => '<span class="previous comments-pagination-button">' . __( 'Previous', pf('textdomain') ) . '</span>',
            'next_text' => '<span class="next comments-pagination-button">' . __( 'Next', pf('textdomain') ) . '</span>',
        ]); ?>
    <?php endif; ?>
    <?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
        <p class="no-comments"><?php _e( 'Comments are closed.', pf('textdomain') ); ?></p>
    <?php endif; ?>
    <?php comment_form([
      'class_submit' => 'button primary'
    ]); ?>
</div>
