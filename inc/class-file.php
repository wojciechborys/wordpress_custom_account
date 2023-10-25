<?php

namespace fpern;

class File
{
    public function upload($name, $path)
    {
        $extension = pathinfo($name, PATHINFO_EXTENSION);
        $filename = uniqid() . md5($name) . '.' . $extension;

        $allowed_extensions = ['pdf', 'xls', 'xlsx'];

        if (!in_array($extension, $allowed_extensions)) {
            return false; // File extension not allowed
        }

        $wp_upload = wp_upload_bits($filename, null, file_get_contents($path));
        if (empty($wp_upload['error'])) {
            $file_path = $wp_upload['file'];
            $file_name = basename($file_path);
            $file_type = wp_check_filetype($file_name, null);
            $attachment_title = sanitize_file_name(pathinfo($file_name, PATHINFO_FILENAME));
            $wp_upload_dir = wp_upload_dir();
            $post_info = [
                'guid'           => $wp_upload_dir['url'] . '/' . $file_name,
                'post_mime_type' => $file_type['type'],
                'post_title'     => $attachment_title,
                'post_content'   => '',
                'post_status'    => 'inherit',
            ];
            $attach_id = wp_insert_attachment($post_info, $file_path);

            return $attach_id;
        }

        return false;
    }
} ?>