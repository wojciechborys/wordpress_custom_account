<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<?php
    $field_name = 'Form[' . esc_attr($field['name']) . ']';
    $field_value = esc_attr($field['value']);
    $field_placeholder = esc_attr($field['placeholder']);
    $required = $field['required'];
    $is_conditional = $field['conditional_logic'];
    $related_fields = $field['related_fields'];
    if ($required) $field_placeholder .= '*';
?>


<div class="field-group field-type-<?= $field['type'] ?>">

    <?php if ($field['type'] == 'text' || $field['type'] == 'email' || $field['type'] == 'number' || $field['type'] == 'textarea' || $field['type'] == 'select') : ?>
        <p class="field-group__title"><?= $field['placeholder'] ?></p><p class="field-group__value"><?= $field_value ?></p>
    <?php elseif ($field['type'] == 'true_false') : ?>
        <p class="field-group__title"><?= $field['placeholder'] ?></p><p class="field-group__value"><?= $field_value == 1 ? __('Tak', 'pern') : __('Nie', 'pern'); ?></p>
    <?php elseif ($field['type'] == 'file') : ?>
        <div class="file-input admin">
            <p class="field-group__title">
                <?= $field_placeholder ?>
                <?php if ($field_value && $field_name == 'Form[skan_krs]' || $field_value && $field_name == 'Form[skan_status]')   : ?>
                    <span class="fileinfo"> <?= _e('Załączono plik', 'pern') ?> <a href="<?= wp_get_attachment_url($field_value) ?>" target="_blank">Zobacz</a></span>
                    <?php elseif( $field_value ): ?>
                        <span class="fileinfo"> <?= _e('Załączono plik', 'pern') ?> <a href="<?= $field_value ?>" target="_blank">Zobacz</a></span>
                <?php endif; ?>
            </p>
            <div class="file-input__list">
                <input class="file-upload-input" type="file" name="<?= $field_name ?>" id="<?= $field_name ?>" <?= $disabled ?>>
                <p class="fileinfo"></p>
            </div>
        </div>
    <?php elseif ($field['type'] == 'radio' || $field['type'] == 'checkbox') : ?>
        <label class="custom-control__label" for="<?= $field_name ?>"><?= $field['placeholder'] ?></label>

        <?php foreach ($field['choices'] as $key => $val) : ?>
            <div class="custom-control custom-<?= $field['type'] ?>">
                <input type="<?= $field['type'] ?>" class="custom-control-input" name="<?= $field_name ?>" id="<?= $field_name . '_' . $key ?>" value="<?= esc_attr($key) ?>" <?= in_array($key, (array)$field_value) ? 'checked' : null; ?> <?= $disabled ?>>
                <label class="custom-control-label" for="<?= $field_name . '_' . $key ?>"><?= esc_attr($val) ?></label>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>