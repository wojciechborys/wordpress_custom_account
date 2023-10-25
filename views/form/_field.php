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

    $has_related_fields = !empty($related_fields);
    $related_fields_class = $has_related_fields ? 'has-related-fields' : '';
?>

<div class="field-group <?php if ($is_conditional): ?>conditional-field <?= $related_fields_class ?> <?php endif; ?><?= !empty($field['errors']) ? 'has-error' : null; ?>field-type-<?= $field['type'] ?>" <?php if ($is_conditional): ?> data-related-fields="<?= implode(',', array_unique($related_fields)) ?>" <?php endif ?>>

    <?php if ($field['type'] == 'text' || $field['type'] == 'email' || $field['type'] == 'number') : ?>
        <input type="<?= $field['type'] ?>" name="<?= $field_name ?>" value="<?= $field_value ?>" placeholder="<?= $field_placeholder ?>" class="form-control" <?php if (!empty($field['maxlength'])) : ?> maxlength="<?= $field['maxlength'] ?>" <?php endif; ?> <?= $disabled ?>>
    <?php elseif ($field['type'] == 'select') : ?>
        <select class="form-control" name="<?= $field_name ?>" <?= $disabled ?>>
            <option value><?= $field_placeholder ?></option>
            <?php foreach ($field['choices'] as $key => $val) : ?>
                <option value="<?= esc_attr($key) ?>" <?= $field_value == $key ? 'selected' : null; ?>><?= esc_attr($val) ?></option>
            <?php endforeach; ?>
        </select>
    <?php elseif ($field['type'] == 'textarea') : ?>
        <textarea class="form-control" name="<?= $field_name ?>" placeholder="<?= $field_placeholder ?>" <?= $disabled ?>><?= $field_value ?></textarea>
    <?php elseif ($field['type'] == 'true_false') : ?>
        <div class="custom-control custom-checkbox">
            <input type="hidden" name="<?= $field_name ?>" value="0" />
            <input type="checkbox" class="custom-control-input" name="<?= $field_name ?>" id="<?= $field_name ?>" value="1" <?= $field_value == 1 ? 'checked' : null; ?> <?= $disabled ?> />
            <label class="custom-control-label" for="<?= $field_name ?>"><?= $field_placeholder ?></label>
        </div>
    <?php elseif ($field['type'] == 'file') : ?>
        <div class="file-input row">
            <div class="col-md-8">
                <p>
                    <?= $field_placeholder ?>
                    <?php if ($field_value && $field_name == 'Form[skan_krs]' || $field_value && $field_name == 'Form[skan_status]' ) : ?>
                        <span class="fileinfo"> <?= _e('Załączono plik', 'pern') ?> <a href="<?= wp_get_attachment_url($field_value) ?>" target="_blank">Zobacz</a></span>
                    <?php elseif($field_value): ?>
                        <span class="fileinfo"> <?= _e('Załączono plik', 'pern') ?> <a href="<?= $field_value ?>" target="_blank">Zobacz</a></span>
                    <?php endif; ?>
                </p>
            </div>

            <div class="col-md-4 file-input__list">
                <label for="<?= $field_name ?>">
                    <?= svg('filearrow') ?> <span><?= _e('Załącz', 'pern') ?></span>
                </label>
                
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

    <?php if (!empty($field['errors'])) : ?>
        <?php foreach ($field['errors'] as $message) : ?>
            <p class="field-error"><?= svg('warning') ?> <?= $message ?></p>
        <?php endforeach; ?>
    <?php endif; ?>

</div>
