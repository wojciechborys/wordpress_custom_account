<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$disabled = (empty($post_status) || $post_status['value'] === 'draft' ) ? false : true;

?>
<div class="container">
    <div class="row">
        <div class="col-12">
            <form class="fpern-forms" method="POST" enctype="multipart/form-data">
                <?php if (!empty($data_saved)) : ?>
                    <div class="alert alert-success">
                        <?php
                            if ($_POST['fpern_action'] == 'fpern_save_application') {
                                _e('<strong>Dziękujemy!</strong> Wniosek został wysłany!');
                            } else {
                                _e('<strong>Dziękujemy!</strong> Wniosek został zapisany jako szkic!');
                            }
                        ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($errors)) : ?>
                    <div class="alert alert-danger"><strong>Uwaga!</strong> Formularz zawiera błędy. Proszę poprawić oznaczone pola.</div>
                <?php endif; ?>

                <input type="hidden" name="fpern_action" value="fpern_save_application">

                <div class="group">

                    <h3><?php _e('1. Dane Wnioskodawcy', 'pern') ?></h3>
                    <p class="hint"><?php _e('[Wszystkie pola są obowiązkowe]') ?></p>
                    <?php $link = get_permalink(ACCOUNT_PARENT) ?>
                    <p class="hint"><?php printf(__('Dane organizacji możesz zmienić %s', 'pern'), __('<a href="'.$link .'twoje-dane">Tutaj</a>', 'pern')); ?></p>
                    <div class="row">

                        <?php $displayed_fields = []; ?>

                        <?php foreach ($organisation_fields as $i => $field) : ?>

                            <?php if (in_array($i, $displayed_fields)) continue; ?>

                            <div class="col-md-6 field">
                                <?= $this->render('form/_field', [
                                    'field' => $field,
                                    'disabled' => true,
                                ]); ?>
                                <?php $displayed_fields[] = $i; ?>

                                <?php if ($i == 'strona_www') : ?>

                                    <?php if (isset($organisation_fields['skan_krs'])) : ?>
                                        <?= $this->render('form/_field', [
                                            'field' => $organisation_fields['skan_krs'],
                                            'disabled' => true,
                                        ]); ?>
                                        <?php $displayed_fields[] = 'skan_krs'; ?>
                                    <?php endif; ?>

                                    <?php if (isset($organisation_fields['skan_status'])) : ?>
                                        <?= $this->render('form/_field', [
                                            'field' => $organisation_fields['skan_status'],                                            'disabled' => true,
                                            'disabled' => true,
                                        ]); ?>
                                        <?php $displayed_fields[] = 'skan_status'; ?>
                                    <?php endif; ?>


                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <h3><?php _e('2. Informacja O Dotychczasowej Współpracy', 'pern') ?></h3>
                    </div>
                    <?php $first = true; ?>
                    <?php foreach ($coop_fields as $field) : ?>
                        <div class="field <?= $first ? 'col-12' : 'col-md-3' ?>">
                            <?= $this->render('form/_field', [
                                'field' => $field,
                                'disabled' => $disabled,
                            ]); ?>
                        </div>
                        <?php $first = false; ?>
                    <?php endforeach; ?>
                </div>

                <div class="row">
                    <?php $first = true; ?>
                    <?php foreach ($billing_fields as $field) : ?>
                        <div class="field <?= $first ? 'col-12' : 'col-md-3' ?>">
                            <?= $this->render('form/_field', [
                                'field' => $field,
                                'disabled' => $disabled,
                            ]); ?>
                        </div>
                        <?php $first = false; ?>
                    <?php endforeach; ?>
                </div>

                <div class="group">
                    <div class="row">
                        <div class="col-12">
                            <h3><?php _e('3. Informacja o celu, na jaki mają zostać przeznaczone środki finansowe', 'perm') ?></h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <?php foreach ($info_fields as $field) : ?>
                                        <div class="row">
                                            <div class="field col-12">
                                                <?= $this->render('form/_field', [
                                                    'field' => $field,
                                                    'disabled' => $disabled,
                                                ]); ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <div class="col-md-6">
                                    <h4><?php _e('STRUKTURA KOSZTÓW', 'pern') ?></h4>
                                    <?php foreach ($data_fields as $field) : ?>
                                        <div class="row">
                                            <div class="field col-12 currency">
                                                <?= $this->render('form/_field', [
                                                    'field' => $field,
                                                    'disabled' => $disabled,
                                                ]); ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="group">
                    <div class="row">
                        <h3><?php _e('4. Dane Kontaktowe Osoby Odpowiedzialnej za wniosek', 'pern') ?></h3>
                        <?php foreach ($contact_person_fields as $field) : ?>
                            <div class="col-md-6 field">
                                <?= $this->render('form/_field', [
                                    'field' => $field,
                                    'disabled' => $disabled,
                                ]); ?>
                            </div>
                        <?php endforeach; ?>
                        <div class="col-md-6">
                            <p class="hint"><?php _e('*Jeśli osobą wskazaną do kontaktu jest osoba, która nie jest upoważniona do reprezentowania podmiotu wnioskującego – do wniosku należy załączyć podpisany załącznik Nr 2 do Zasad udzielania wsparcia w formie darowizny przez Fundację Grupy PERN', 'pern') ?></p>
                        </div>
                    </div>
                </div>

                <div class="group">
                    <div class="row">
                        <div class="col-md-6">
                            <h4><?php _e('Oświadczenia', 'pern') ?></h4>
                            <?php foreach ($consent_fields as $field) : ?>
                                <div class="col-12 field">
                                    <?= $this->render('form/_field', [
                                        'field' => $field,
                                        'disabled' => $disabled,
                                    ]); ?>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="col-md-6">
                            <h4><?php _e('Dokumenty do załączenia', 'pern') ?></h4>
                            <?php foreach ($document_fields as $field) : ?>
                                <div class="col-12 field">
                                    <?= $this->render('form/_field', [
                                        'field' => $field,
                                        'disabled' => $disabled,
                                    ]); ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <?php if(!$disabled): ?>
                        <div class="row">
                            <div class="col-12 submit-container">
                                <button class="draft" type="submit" name="fpern_action" value="fpern_save_draft"><?php _e('Zapisz wniosek', 'pern') ?></button>
                                <button type="submit" name="fpern_action" value="fpern_save_application"><?php _e('Wyślij wniosek', 'pern') ?></button>
                            </div>
                        </div>
                    <?php endif ?>

                </div>

            </form>

            <script>
                $(document).change(function () {
                    $('.show-fields-checkbox').each(function () {
                        var fieldsContainer = $('.conditional-fields');
                        if ($(this).is(':checked')) {
                            $(fieldsContainer).show();
                        } else {
                            $(fieldsContainer).hide();
                        }
                    });
                });
                $(document).ready(function () {
                    $('.show-fields-checkbox').each(function () {
                        var fieldsContainer = $('.conditional-fields');
                        if ($(this).is(':checked')) {
                            $(fieldsContainer).show();
                        } else {
                            $(fieldsContainer).hide();
                        }
                    });
                });

                $(document).ready(function () {
                    $('input[type="radio"].custom-control-input, input[type="checkbox"].custom-control-input').unbind().click(function () {
                        var fieldName = $(this).attr('name');
                        var isChecked = $(this).is(':checked');
                        if (isChecked && $(this).attr('id').indexOf('true') !== -1) {
                            newField = fieldName.replace('Form[', '').replace(']', '');
                            console.log(newField);
                            $('.conditional-field[data-related-fields="' + newField + '"]').each(function () {
                                $(this).show();
                            })
                        } else {
                            $('.conditional-field[data-related-fields="' + newField + '"]').each(function () {
                                $(this).hide();
                            })
                        }
                    });
                });

                $('.file-upload-input').on('change', function() {
                    var fileName = $(this).val().split('\\').pop();
                    $(this).siblings('label').find('span').text('');
                    $(this).siblings('.fileinfo').text('Załączono plik: ' + fileName);
                });

                
            </script>
        </div>
    </div>
</div>
