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
                <div class="col-12 mt-5 mb-3">
                    <div class="meta">
                        <h2 class="h1"><?php _e('Złożone wnioski Beneficjentów', 'pern') ?></h2>
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

            <?php if (!empty($posts)) : ?>
                <table class="post-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all"></th>
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
                                <td><input type="checkbox" class="export-checkbox"></input></td>
                                <td><?= $post['project_name']; ?></td>
                                <td><a href="<?= $post['post_link']; ?>"><?= $post['application_number']; ?></a></td>
                                <td><?= $post['project_email']; ?></td>
                                <td><?= $post['post_date']; ?></td>
                                <td><?= $post['org_type']['label']; ?></td>
                                <td><?= $post['krs']; ?></td>
                                <td><span class="status <?= $post['post_status']['value']; ?>"><?= $post['post_status']['label']; ?></span></td>
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