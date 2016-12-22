<?php

namespace hipanel\menus;

use hiqdev\yii2\cart\widgets\CartTeaser;
use hiqdev\yii2\language\menus\LanguageMenu;
use hiqdev\yii2\reminder\widgets\ReminderTop;

class NavbarMenu extends \hiqdev\yii2\menus\Menu
{
    public function items()
    {
        return [
            [
                'label' => LanguageMenu::widget(),
                'encode' => false,
                'options' => [
                    'class' => 'dropdown language-menu',
                ],
            ],
            [
                'label' => ReminderTop::widget(),
                'encode' => false,
                'options' => [
                    'id' => 'reminders',
                    'class' => 'dropdown notifications-menu reminders',
                ],
            ],
            [
                'label' => CartTeaser::widget(),
                'encode' => false,
                'options' => [
                    'class' => 'dropdown notifications-menu notifications-cart'
                ],
            ],
            [
                'label' => UserMenu::widget(),
                'encode' => false,
                'options' => [
                    'class' => 'dropdown user user-menu'
                ]
            ],
            [
                'label' => '<a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>',
                'encode' => false,
            ],
        ];
    }
}
