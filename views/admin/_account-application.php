<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$disabled = true;
?>
<div class="container">
    <div class="row">
        <div class="col-12">
            <form class="fpern-forms" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="fpern_action" value="fpern_save_application">

                <div class="group text-inputs">
                    <a class="download_pdf"><?php _e('Eksportuj do PDF', 'pern') ?></a>

                    <h3><?php _e('1. Dane Wnioskodawcy', 'pern') ?></h3>
                    
                    <div class="row">

                        <?php $displayed_fields = []; ?>

                        <?php foreach ($organisation_fields as $i => $field) : ?>

                            <?php if (in_array($i, $displayed_fields)) continue; ?>

                            <div class="col-12 field">
                                <?= $this->render('form/_text', [
                                    'field' => $field,
                                    'disabled' => true,
                                ]); ?>
                                <?php $displayed_fields[] = $i; ?>

                                <?php if ($i == 'strona_www') : ?>

                                    <?php if (isset($organisation_fields['skan_krs'])) : ?>
                                        <?= $this->render('form/_text', [
                                            'field' => $organisation_fields['skan_krs'],
                                            'disabled' => true,
                                        ]); ?>
                                        <?php $displayed_fields[] = 'skan_krs'; ?>
                                    <?php endif; ?>

                                    <?php if (isset($organisation_fields['skan_status'])) : ?>
                                        <?= $this->render('form/_text', [
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

                <div class="row text-inputs">
                    <div class="col-12 mt-4">
                        <h3><?php _e('2. Informacja O Dotychczasowej Współpracy', 'pern') ?></h3>
                    </div>
                    <?php $first = true; ?>
                    <?php foreach ($coop_fields as $field) : ?>
                        <div class="field col-12">
                            <?= $this->render('form/_text', [
                                'field' => $field,
                                'disabled' => $disabled,

                                ]); ?>
                        </div>
                        <?php $first = false; ?>
                    <?php endforeach; ?>
                </div>

                <div class="row text-inputs">
                    <?php $first = true; ?>
                    <?php foreach ($billing_fields as $field) : ?>
                        <div class="field col-12">
                            <?= $this->render('form/_text', [
                                'field' => $field,
                                'disabled' => $disabled,
                            ]); ?>
                        </div>
                        <?php $first = false; ?>
                    <?php endforeach; ?>
                </div>

                <div class="group text-inputs">
                    <div class="row">
                        <div class="col-12 mt-4">
                            <h3><?php _e('3. Informacja o celu, na jaki mają zostać przeznaczone środki finansowe', 'perm') ?></h3>
                            <div class="row">
                                <div class="col-12">
                                    <?php foreach ($info_fields as $field) : ?>
                                        <div class="row">
                                            <div class="field col-12">
                                                <?= $this->render('form/_text', [
                                                    'field' => $field,
                                                    'disabled' => $disabled,
                                                ]); ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <div class="col-12 mt-5">
                                    <h4><?php _e('STRUKTURA KOSZTÓW', 'pern') ?></h4>
                                    <?php foreach ($data_fields as $field) : ?>
                                        <div class="row">
                                            <div class="field col-12 currency">
                                                <?= $this->render('form/_text', [
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

                <div class="group text-inputs">
                    <div class="row">
                        <div class="col-12 mt-4">
                            <h3><?php _e('4. Dane Kontaktowe Osoby Odpowiedzialnej za wniosek', 'pern') ?></h3>
                        </div>

                        <?php foreach ($contact_person_fields as $field) : ?>
                            <div class="col-12 field">
                                <?= $this->render('form/_text', [
                                    'field' => $field,
                                    'disabled' => $disabled,
                                ]); ?>
                            </div>
                        <?php endforeach; ?>
                        <div class="col-12">
                            <p class="hint"><?php _e('*Jeśli osobą wskazaną do kontaktu jest osoba, która nie jest upoważniona do reprezentowania podmiotu wnioskującego – do wniosku należy załączyć podpisany załącznik Nr 2 do Zasad udzielania wsparcia w formie darowizny przez Fundację Grupy PERN', 'pern') ?></p>
                        </div>
                    </div>
                </div>

                <div class="group text-inputs">
                    <div class="row">
                        <div class="col-12">
                            <h4><?php _e('Oświadczenia', 'pern') ?></h4>
                            <?php foreach ($consent_fields as $field) : ?>
                                <div class="col-12 field">
                                    <?= $this->render('form/_text', [
                                        'field' => $field,
                                        'disabled' => $disabled,
                                    ]); ?>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="col-12 mt-5">
                            <h4><?php _e('Dokumenty do załączenia', 'pern') ?></h4>
                            <?php foreach ($document_fields as $field) : ?>
                                <div class="col-12 field">
                                    <?= $this->render('form/_text', [
                                        'field' => $field,
                                        'disabled' => $disabled,
                                    ]); ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <p class="user-status <?php echo $post_status['value'] ?>"><?php print sprintf(__('Obecny status wniosku: %s'), $post_status['label']) ?></p>
                        </div>
                    </div>
         
                    <?php if($post_status['value'] !== 'draft'): ?>
                        <div class="row">
                            <div class="col-12 submit-container">
                                <?php if($post_status['value'] === 'accepted' || $post_status['value'] === 'publish'): ?>
                                    <button class="reject" type="submit" name="fpern_action" value="fpern_reject_application"><?php _e('Odrzuć wniosek', 'pern') ?></button>
                                <?php endif ?>
                                <?php if($post_status['value'] === 'rejected' || $post_status['value'] === 'publish'): ?>
                                    <button class="accept" type="submit" name="fpern_action" value="fpern_accept_application"><?php _e('Zaakceptuj wniosek', 'pern') ?></button>
                                <?php endif ?>
                            </div>
                        </div>
                    <?php endif ?>
                    <a class="download_pdf"><?php _e('Eksportuj do PDF', 'pern') ?></a>

                </div>
            </form>
        </div>
    </div>
</div>
