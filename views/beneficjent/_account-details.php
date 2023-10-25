<?php 
    if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
    }
?>
<div class="container">
    <div class="row">
        <div class="col-12">
            <form class="fpern-forms" method="POST" enctype="multipart/form-data">

                <?php if($form_updated) : ?>
                    <div class="alert alert-success">
                        <strong>Dziękujemy!</strong> Dane zostały poprawnie zapisane do bazy.
                    </div>
                <?php endif; ?>

                <?php if(!empty($errors)) : ?>
                    <div class="alert alert-danger"><strong>Uwaga!</strong> Formularz zawiera błędy. Proszę poprawić oznaczone pola.</div>
                <?php endif; ?>

                <input type="hidden" name="fpern_action" value="fpern_save_beneficjent">
                <div class="group">
                    <h3>Szczegóły konta</h3>
                    <p><span class="bluey">Nazwa:</span> <?=$user_name?></p>
                    <p><span class="bluey">Adres e-mail:</span> <?=$user_email?></p>
                </div>

                <div class="group">

                    <h3>Dane Organizacji</h3>
                    <p class="hint">Uzupełnij poniższe dane przed złożeniem wniosku o darowiznę</p>

                    <div class="row">

                        <?php $displayed_fields = []; ?>
                        
                        <?php foreach($organisation_fields as $i => $field) : ?>

                            <?php if(in_array($i, $displayed_fields)) continue; ?>
                            
                            <div class="col-md-6 field">
                                <?=$this->render('form/_field', [
                                    'field' => $field
                                ]); ?>
                                <?php $displayed_fields[] = $i; ?>

                                <?php if($i == 'strona_www') : ?>

                                    <p>*Pola obowiązkowe</p>

                                    <?php if(isset($organisation_fields['skan_krs'])) : ?>
                                        <?=$this->render('form/_field', [
                                            'field' => $organisation_fields['skan_krs']
                                        ]); ?>
                                        <?php $displayed_fields[] = 'skan_krs'; ?>
                                    <?php endif; ?>

                                    <?php if(isset($organisation_fields['skan_status'])) : ?>
                                        <?=$this->render('form/_field', [
                                            'field' => $organisation_fields['skan_status']
                                        ]); ?>
                                        <?php $displayed_fields[] = 'skan_status'; ?>
                                    <?php endif; ?>


                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="group">

                    <h3>Dane kontaktowe</h3>
                    <p class="hint">Uzupełnij poniższe dane przed złożeniem wniosku o darowiznę</p>

                    <div class="row">
                        <?php foreach($contact_fields as $field) : ?>
                            <div class="col-md-6 field">
                                <?=$this->render('form/_field', [
                                    'field' => $field
                                ]); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="group">   

                    <h3>Adres do korespondencji </h3>
                    <p class="hint">Uzupełnij adres do korespondencji</p>

                    <div class="row">
                        <?php foreach($correspondence_fields as $field) : ?>
                            <div class="col-md-6 field">
                                <?=$this->render('form/_field', [
                                    'field' => $field
                                ]); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                </div>

                <div class="group">   

                    <h3>Osoba do kontaktu </h3>
                    <p class="hint">Uzupełnij dane kontaktowe osoby upoważnionej do składania  wniosku o darowiznę</p>

                    <div class="row">
                        <?php foreach($contact_person_fields as $field) : ?>
                            <div class="col-md-6 field">
                                <?=$this->render('form/_field', [
                                    'field' => $field
                                ]); ?>
                            </div>
                            <div class="col-md-6"></div>
                        <?php endforeach; ?>
                    </div>

                </div>

                <div class="group">
                    <div class="row">
                        <?php foreach($consent_fields as $field) :?>
                            <div class="col-md-6 field">
                            <?=$this->render('form/_field', [
                                    'field' => $field
                                ]); ?>
                            </div>
                        <?php endforeach; ?>
                        <div class="col-md-6 submit-container">
                            <button type="submit">Aktualizuj dane</button>
                        </div>
                    </div>
                </div>   

            </form>

            <script>
                $('.file-upload-input').on('change', function() {
                    var fileName = $(this).val().split('\\').pop();
                    $(this).siblings('label').find('span').text('');
                    $(this).siblings('.fileinfo').text('Załączono plik: ' + fileName);
                });
            </script>
        </div>
    </div>
</div>   
