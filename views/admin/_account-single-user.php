<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$disabled = true; //disable all fields
?>
<div class="container">
    <div class="row">
        <div class="col-12">
            <form class="fpern-forms" method="POST" enctype="multipart/form-data">
                <?php if (!empty($data_saved)) : ?>
                    <div class="alert alert-success">
                        <?php
                            if ($_POST['fpern_action'] == 'fpern_accept_user') {
                                _e('<strong>Dziękujemy!</strong> Uzytkownik został zaakceptowany');
                            } else {
                                _e('<strong>Dziękujemy!</strong> Użytkownik został odrzucony');
                            }
                        ?>
                    </div>
                <?php endif; ?>
           
                <input type="hidden" name="fpern_action" value="fpern_accept_user">
                <div class="group client-info">
                    <h2 class="h3"><?php _e('Dane konta', 'pern') ?></h2>
                    <div class="row">
                        <div class="col-12">
                            <span class="client-info__title"><?= __('Nazwa użytkownika: ', 'pern') ?></span>
                            <span class="client-info__value"><?= $user_name ?></span>
                        </div>
                        <div class="col-12">
                            <span class="client-info__title"><?= __('Email: ', 'pern') ?></span>
                            <span class="client-info__value"><?= $user_email  ?></span>
                        </div>
                    </div>
                </div>

                <div class="group text-inputs">
                    <h2 class="h3"><?php _e('Dane organizacji', 'pern') ?></h2>
                    <div class="row">
                        <?php $displayed_fields = []; ?>
                        <?php foreach ($organisation_fields as $i => $field) : ?> 
                            <?php if ($field['name'] === 'skan_krs' || $field['name'] === 'skan_status') continue; ?>                                                     
                            <div class="col-12 field">
                                <?= $this->render('form/_text', [
                                    'field' => $field,
                                    'disabled' => true,
                                ]); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="group text-inputs">
                    <h2 class="h3"><?php _e('Dane kontaktowe', 'pern') ?></h2>

                    <div class="row">
                        <?php foreach ($contact_data as $field) : ?>
                            <div class="col-12 field">
                                <?= $this->render('form/_text', [
                                    'field' => $field,
                                    'disabled' => $disabled,
                                ]); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="group text-inputs">
                    <h2 class="h3"><?php _e('Przesłane dokumenty', 'pern') ?></h2>

                    <div class="row">
                       <?php if(isset($organisation_fields['skan_krs'])) : ?>
                            <?=$this->render('form/_text', [
                                'field' => $organisation_fields['skan_krs']
                            ]); ?>
                        <?php endif; ?>

                        <?php if(isset($organisation_fields['skan_status'])) : ?>
                            <?=$this->render('form/_text', [
                                'field' => $organisation_fields['skan_status']
                            ]); ?>
                        <?php endif; ?>
                    </div>
                </div>
                

                <div class="group">
                    <p class="user-status <?php echo $user_accepted ?>">
                        <?php
                        if ($user_accepted === 'false') {
                            echo __('Status: odrzucony');
                        } elseif ($user_accepted === 'true') {
                            echo __('Status: zaakceptowany');
                        } else {
                            echo __('Status: W trakcie');
                        }
                        ?>
                    </p>           

                    <div class="row">
                        <div class="col-12 submit-container">
                            <?php if($user_accepted === 'true' || $user_accepted === 'pending'): ?>
                                <button class="reject" class="draft" type="submit" name="fpern_action" value="fpern_reject_user"><?php _e('Odrzuć', 'pern') ?></button>
                            <?php endif ?>

                            <?php if($user_accepted === 'false' || $user_accepted === 'pending'): ?>
                                <button class="accept" type="submit" name="fpern_action" value="fpern_accept_user"><?php _e('Zaakceptuj', 'pern') ?></button>
                            <?php endif ?>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
