<?php 

namespace fpern;

trait Render_Trait
{
    public function render($template, $variables = []){        
        $path = get_template_directory().'/framework/views/'.$template.'.php';
        ob_start();
        extract($variables);
        if (isset($variables['disabled']) && $variables['disabled'] === true) {
            $disabled = 'disabled';
        } else {
            $disabled = ''; // Empty string if 'disabled' flag is not set or set to false
        }
        require($path);
        return ob_get_clean();      
    }
}