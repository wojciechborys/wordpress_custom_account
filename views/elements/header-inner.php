

<?php 
$user = \fpern\Helpers::getCurrentUser();
$user_name = ($current_user->user_firstname) ? $current_user->user_firstname : $current_user->user_login;
$has_admin_role = in_array('administrator', $user->roles);
if($has_admin_role):
    if(get_field('menu_photo-admin', 'option')): ?>
        <aside class="top-hero admin" style="background-image: url(<?php echo get_field('menu_photo-admin', 'option')['sizes']['large'] ?> )">
            <div class="container">
                <div class="row" >
                    <div class="col-12">
                        <div class="top-hero__menu">
                            <h1><?php printf(__('Witaj, %s'), $user_name); ?></h1>
                            <?php
                            wp_nav_menu([
                                'theme_location' => 'main_dashboard-admin',
                                'container' => '',
                                'items_wrap' => '<ul id="%1$s" class="%2$s navbar-nav children_1_ul">%3$s</ul>',
                                'walker' => new Hyper_Walker(),
                            ]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
    <?php endif; ?>
<?php else: ?>
    <?php if(get_field('menu_photo', 'option')): ?>
        <aside class="top-hero client" style="background-image: url(<?php echo get_field('menu_photo', 'option')['sizes']['large'] ?> )">
            <div class="container">
                <div class="row" >
                    <div class="col-12">
                        <div class="top-hero__menu">
                            <h1><?php printf(__('Witaj, %s'), $user_name); ?></h1>
                            <?php
                            wp_nav_menu([
                                'theme_location' => 'main_dashboard',
                                'container' => '',
                                'items_wrap' => '<ul id="%1$s" class="%2$s navbar-nav children_1_ul">%3$s</ul>',
                                'walker' => new Hyper_Walker(),
                            ]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
    <?php endif; ?>
<?php endif;


