<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<div class="account-communication">
    <div class="container">
        <div class="row">
            <div class="col-lg-4" id="communication-searchbar">
            </div>
            <div class="col-lg-8" id="communication-title">
                <?php if (!empty($_POST['comment_content'])): ?>
                    <div class="alert alert-success">
                        <?php _e('Komentarz dodany'); ?>
                    </div>
                <?php endif ?>
                <?php if (!empty($_GET['wniosek'])): ?>
                    <h2 class="application_number"><?php echo $posts[0]['application_number'] ?></h2>
                <?php endif ?>
            </div>           
        </div>
        <div class="row">
            <div class="col-4" id="list-column">
                <?php if (!empty($posts)):
                    foreach ($posts as $post):
                    if ($last_author !== $current_user_id && get_post_meta($post['post_id'], 'new_comments', true)) {
                        $new_comment = true;
                    }  
                    $last_comment = $this->get_last_comment($post['post_id']); ?>
                    <a class="comment__wrapper <?php echo $post['post_id'] == $_GET['wniosek'] ? 'active' : '' ?><?php echo $new_comment ? 'new' : '' ?>" href="<?php get_permalink(ACCOUNT_COMMUNICATION_PAGE_ID) ?>?wniosek=<?php echo $post['post_id'] ?>">
                        <h3 class="comment__title"><?php echo $post['application_number']; ?></h3>
                        <?php if ($last_comment) : 
                            $dateTime = new DateTime($last_comment->comment_date);
                            setlocale(LC_TIME, 'pl_PL.utf8'); // Set the Polish locale
                            $formattedDateTime = strftime('%e %B %Y %H:%M', $dateTime->getTimestamp()); ?>
                            <div class="comment__newest">
                                <p class="comment__newest--text"><?php echo $last_comment->comment_content; ?></p>
                                <small class="comment__newest--date"><?php echo $formattedDateTime; ?></small>
                            </div>
                        <?php endif; ?>
                    </a>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="no-applications">
                        <?php _e('Brak złożonych wniosków','pern') ?>
                    </div> 
                <?php endif; ?>
            </div>

            <div class="col-8" id="communication-column">
                <div class="comments-container">
                    <?php if (!empty($comments)): ?>
                        <?php foreach ($comments as $comment) :
                            $dateTime = new DateTime($comment->comment_date);
                            setlocale(LC_TIME, 'pl_PL.utf8'); // Set the Polish locale
                            $formattedDateTime = strftime('%e %B %Y %H:%M', $dateTime->getTimestamp());

                            if (is_user_logged_in()) : ?>
                                <div class="comments-container__single <?php echo ($comment->user_id == $current_user_id) ? 'active' : ''; ?>">
                                    <small class="comments-container__single--author"><?php echo $comment->comment_author; ?></small>
                                    <div class="comments-container__single--comment">
                                        <?php echo $comment->comment_content; ?>
                                    </div>    
                                <small class="comments-container__single--date"><?php echo $formattedDateTime; ?></small>
                                </div>
                            <?php endif; ?>

                        <?php endforeach; ?>
                    <?php else: ?>
                            <h3><?php _e('Aby wyświetlić komunikator - wybierz odpowiedni wniosek.') ?></h3>
                    <?php endif ?>
                    <?php if (!empty($_GET['wniosek'])): ?>
                        <form id="comment-form" method="post">
                            <input type="hidden" name="post_id" value="<?php echo esc_attr($_GET['wniosek']); ?>">
                            <textarea name="comment_content"  placeholder="<?php _e('Wyślij wiadomość', 'pern') ?>"></textarea>
                            <input type="submit" value="<?php _e('Wyślij', 'pern') ?>">
                        </form>
                    <?php endif ?>

                </div>
            </div>
        </div>
    </div>
</div>
