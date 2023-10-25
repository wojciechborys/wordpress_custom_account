<?php 
    if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
    }

?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <?php
                if ($_GET['status'] === 'application-success') {
                    _e('<div class="alert alert-success"><strong>Dziękujemy!</strong> Wniosek został wysłany!</div>');
                } else if($_GET['status'] === 'draft-success') {
                    _e('<div class="alert alert-success"><strong>Dziękujemy!</strong> Wniosek został zapisany jako szkic!</div>');
                }
            ?>
            <div class="row">
                <div class="col-lg-4">
                    <div class="row">
                        <div class="col-12">
                            <h3 class="mb-3"><?php _e('Beneficjenci', 'pern') ?></h3>
                            <table class="post-table mb-4">
                                <thead>
                                    <tr>
                                        <th><?php _e('Organizacja', 'pern') ?></th>
                                        <th><?php _e('Email', 'pern') ?></th>
                                    </tr>
                                </thead>
                                <?php if (!empty($users)) : ?>
                                    <tbody>
                                        <?php foreach ($users as $user) : ?>
                                            <tr>
                                                <td><a href="<?= $user['post_link']; ?>"><?= $user['project_name']; ?></a></td>
                                                <td class="email"><?= $user['project_email']; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                <?php else : ?>
                                    <tbody>
                                        <tr>
                                            <td>Brak złożonych wniosków</td>
                                        </tr>
                                    </tbody>
                                <?php endif; ?>

                            </table>
                            <a class="button button-border-green" href="<?php echo get_permalink(47797) ?>"><?php _e('Zobacz beneficjentów', 'pern') ?></a>  

                        </div>
                    </div>

                    <div class="row account-communication dashboard">
                        <div class="col-12" id="list-column">
                            <h3><?php _e('Wiadomości', 'pern') ?></h3>

                            <?php if (!empty($comments)):
                                foreach ($comments as $comment):
                                    
                                    $comment_object = get_comment($comment->comment_ID);
                                    $post_title = get_the_title($comment_object->comment_post_ID);
                                    
                                    if ($last_author !== $current_user_id && get_post_meta($comment->comment_post_ID, 'new_comments', true)) {
                                        $new_comment = true;
                                    }  
                                    $last_comment = $this->get_last_comment($comment->comment_post_ID); ?>
                                    <a class="comment__wrapper">
                                        <h3 class="comment__title"><?php echo get_the_title($comment->comment_post_ID) ?></h3>
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
                    </div> 
                </div>

                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-12">
                            <div class="meta mb-3">
                                <h3 class="mb-0"><?php _e('Wnioski', 'pern') ?></h3>
                                <?php if (!empty($posts)) : ?>
                                    <div class="meta__utils">
                                        <button class="meta__export" id="export-btn"><?php _e('Eksportuj do CSV') ?></button>
                                        <div class="meta__sort">
                                            <label class="meta__sort--title" for="sort-option"><?php _e('Sortuj', 'pern') ?></label>
                                            <select class="meta__sort--select" id="sort-option">
                                                <option value="date"><?php _e('Po dacie', 'pern') ?></option>
                                                <option value="title"><?php _e('Po nazwie projektu (A-Z)', 'pern') ?></option>
                                                <option value="title_desc"><?php _e('Po nazwie projektu (Z-A)', 'pern') ?></option>
                                            </select>
                                        </div>
                                    </div>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>

                    <table class="post-table mb-4" id="projects">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all"></th>
                                <th><?php _e('Nazwa projektu', 'pern') ?></th>
                                <th><?php _e('Data złożenia', 'pern') ?></th>
                                <th><?php _e('Numer wniosku', 'pern') ?></th>
                                <th><?php _e('Wnioskowana kwota', 'pern') ?></th>
                                <th><?php _e('Status', 'pern') ?></th>
                            </tr>
                        </thead>
                        <?php if (!empty($posts)) : ?>
                            <tbody>
                                <?php foreach ($posts as $post) : ?>
                                    <?php
                                    $see = $post['post_status']['value'] === 'draft' ? __('Edytuj', 'pern') : __('Zobacz', 'pern'); ?>

                                    <tr>
                                        <td><input type="checkbox" class="export-checkbox"></input></td>
                                        <td><?= $post['project_name']; ?></td>
                                        <td><?= $post['post_date']; ?></td>
                                        <td><a href="<?= $post['post_link']; ?>"><?= $post['application_number']; ?></a></td>
                                        <td><?= $post['post_amount']; ?></td>
                                        <td><span class="status <?= $post['post_status']['value']; ?>"><?= $post['post_status']['label']; ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        <?php else : ?>
                            <tbody>
                                <tr>
                                    <td>Brak złożonych wniosków</td>
                                </tr>
                            </tbody>
                        <?php endif; ?>
                    </table>
                    <a class="button button-border-green float-right" href="<?php echo get_permalink(47799) ?>"><?php _e('Zobacz wnioski', 'pern') ?></a>  

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('export-btn').addEventListener('click', function () {
            exportToCSV();
        });

        function exportToCSV() {
            const checkboxes = document.querySelectorAll('.export-checkbox:checked');
            if (checkboxes.length === 0) {
                alert('Zaznacz conajmniej jeden rząd.');
                return;
            }

            const rows = Array.from(checkboxes).map((checkbox) => {
                const row = checkbox.parentElement.parentElement;
                return Array.from(row.cells).slice(1).map(cell => cell.innerText);
            });

            const csvContent = 'data:text/csv;charset=utf-8,' + rows.map(e => e.join(',')).join('\n');
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement('a');
            link.setAttribute('href', encodedUri);
            link.setAttribute('download', 'export.csv');
            document.body.appendChild(link);
            link.click();
        }

        // Function to handle the select all checkbox
        document.getElementById('select-all').addEventListener('change', function () {
            const checkboxes = document.querySelectorAll('.export-checkbox');
            checkboxes.forEach((checkbox) => {
                checkbox.checked = this.checked;
            });
        });

        document.getElementById('sort-option').addEventListener('change', function () {
            const selectedOption = this.value;
            updateURLWithParam('sort', selectedOption);
        });

        // Function to update the URL with the parameter and reload the page (to sort posts)
        function updateURLWithParam(paramName, paramValue) {
            const url = new URL(window.location.href);
            url.searchParams.set(paramName, paramValue);
            window.location.href = url.toString();
        }
    });

   
</script>