<?php 
    if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
    }

?>
<?php if (empty($data_saved) || (!empty($data_saved) && $data_saved === false )): ?>
    <div class="fpern-blocker">
        <div class="inner">
            <?= svg('info'); ?>
            <h3><?php _e('Proszę uzupełnij swój profil przed złożeniem wniosku!', 'pern') ?></h3>
            <a href="<?= get_permalink(ACCOUNT_DETAILS_PAGE_ID) ?>"><?php _e('Uzupełnij dane', 'pern') ?></a>
        </div>
    </div>
<?php endif ?>


<div class="container">
    <div class="row">
        <div class="col-12">
            
            <?php if (empty($user_accepted) || ( !empty($user_accepted) && $user_accepted == 'false' )) : ?>
                <div class="alert alert-danger"><?php _e('Twoje konto oczekuje na zatwierdzenie przez administrację', 'pern') ?></div>
            <?php endif ?>

            <?php
                if ($_GET['status'] === 'application-success') {
                    _e('<div class="alert alert-success"><strong>Dziękujemy!</strong> Wniosek został wysłany!</div>');
                } else if($_GET['status'] === 'draft-success') {
                    _e('<div class="alert alert-success"><strong>Dziękujemy!</strong> Wniosek został zapisany jako szkic!</div>');
                }
            ?>

            <?php if ( !empty($user_accepted) && $user_accepted == 'true' ) : ?>
                <div class="row">
                    <div class="col-12">
                        <h1><?php _e('Złóż wniosek o darowiznę', 'pern') ?></h1>
                        <a class="green-button button" href="<?php echo home_url().'/moje-konto/wniosek'?>"> <?php _e('Złóż wniosek', 'pern') ?></a>
                    </div>
                </div>

            <?php endif ?>

            <div class="row">
                <div class="col-12 mt-5 mb-3">
                    <h2 class="h1"><?php _e('Złożone wnioski', 'pern') ?></h2>
                </div>
            </div>

            <?php if (!empty($posts)) : ?>
                <table class="post-table">
                    <thead>
                        <tr>
                            <th><?php _e('Organizacja', 'pern') ?></th>
                            <th><?php _e('Numer wniosku', 'pern') ?></th>
                            <th><?php _e('Adres email', 'pern') ?></th>
                            <th><?php _e('Data złożenia', 'pern') ?></th>
                            <th><?php _e('Rodzaj organizacji', 'pern') ?></th>
                            <th><?php _e('KRS', 'pern') ?></th>
                            <th><?php _e('Status', 'pern') ?></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($posts as $post) : ?>
                            <?php
                            $see = $post['post_status']['value'] === 'draft' ? __('Edytuj', 'pern') : __('Zobacz', 'pern'); ?>

                            <tr>
                                <td><?= $post['project_name']; ?></td>
                                <td><?= $post['post_date']; ?></td>
                                <td><?= $post['last_update_date']; ?></td>
                                <td><?= $post['application_number']; ?></td>
                                <td><?= $post['amount_requested']; ?> PLN</td>
                                <td><span class="status <?= $post['post_status']['value']; ?>"><?= $post['post_status']['label']; ?></span></td>
                                <td><a href="<?= $post['post_link']; ?>"><?= $see ?></a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            <?php else : ?>
                <table class="post-table empty">
                    <thead>
                        <tr>
                            <th><?php _e('Organizacja', 'pern') ?></th>
                            <th><?php _e('Numer wniosku', 'pern') ?></th>
                            <th><?php _e('Adres email', 'pern') ?></th>
                            <th><?php _e('Data złożenia', 'pern') ?></th>
                            <th><?php _e('Rodzaj organizacji', 'pern') ?></th>
                            <th><?php _e('KRS', 'pern') ?></th>
                            <th><?php _e('Status', 'pern') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="empty-info"><?php _e('Brak złożonych wniosków', 'pern') ?></td>
                        </tr>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>