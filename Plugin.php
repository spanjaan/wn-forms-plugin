<?php

namespace SpAnjaan\Forms;

use Lang;
use Validator;

use SpAnjaan\Forms\Classes\BackendHelpers;
use SpAnjaan\Forms\Models\Settings;
use SpAnjaan\Forms\Models\Record;

use Backend\Facades\Backend;
use System\Classes\PluginBase;
use System\Classes\SettingsManager;


class Plugin extends PluginBase
{
    public function pluginDetails() {
        return [
            'name' => __('Magic forms'),
            'description' => __('Create easy ajax forms in wintercms'),
            'author' => 'SpAnjaan',
            'icon' => 'icon-bolt',
            'homepage' => 'https://github.com/spanjaan/wn-forms-plugin'
        ];
    }

    public function registerNavigation() {

        // Add menu item for all records
        $menu = [
            'forms' => [
                'label' => __('Forms'),
                'icon' => 'icon-bolt',
                'url' => BackendHelpers::getBackendURL(['spanjaan.forms.access_records' => 'spanjaan/forms/records', 'spanjaan.forms.access_exports' => 'spanjaan/forms/exports'], 'spanjaan.forms.access_records'),
                'permissions' => ['spanjaan.forms.*'],
                'counter' => Record::getUnread(),
                'counterLabel' => __('Unread messages'),
                'sideMenu' => [
                    'records' => [
                        'label' => __('All records'),
                        'icon' => 'icon-database',
                        'url' => Backend::url('spanjaan/forms/records'),
                        'permissions' => ['spanjaan.forms.access_records'],
                        'counter' => Record::getUnread(),
                        'counterLabel' => __('Unread messages'),
                    ],
                ]
            ]
        ];
        
        // Add menu item for each groups
        $groups = Record::all()->pluck('group')->unique();
        foreach($groups as $group) {
            $slug = str_slug($group);
            $menu['forms']['sideMenu'][$slug] = [
                'label' => $group,
                'icon' => 'icon-database',
                'url' => Backend::url('spanjaan/forms/records?group='.$group),
                'permissions' => ['spanjaan.forms.access_records'],
                'counter' => Record::getUnread($group),
                'counterLabel' => __('Unread messages'),
            ];
        }

        // Add menu item to export datas
        $menu['forms']['sideMenu']['exports'] = [
            'label' => __('Export'),
            'icon' => 'icon-download',
            'url' => Backend::url('spanjaan/forms/exports'),
            'permissions' => ['spanjaan.forms.access_exports']
        ];
        return $menu;
    }

    public function registerSettings() {
        return [
            'config' => [
                'label' => __('Magic forms'),
                'description' => __('Configure magic forms parameters'),
                'category' => SettingsManager::CATEGORY_CMS,
                'icon' => 'icon-bolt',
                'class' => 'SpAnjaan\Forms\Models\Settings',
                'permissions' => ['spanjaan.forms.access_settings'],
                'order' => 500
            ]
        ];
    }

    public function registerPermissions() {
        return [
            'spanjaan.forms.access_settings' => ['tab' => __('Magic forms'), 'label' => __('Access settings')],
            'spanjaan.forms.access_records' => ['tab' => __('Magic forms'), 'label' => __('Access records')],
            'spanjaan.forms.access_exports' => ['tab' => __('Magic forms'), 'label' => __('Can export records')],
            'spanjaan.forms.gdpr_cleanup' => ['tab' => __('Magic forms'), 'label' => __('Gdpr cleanup')],
        ];
    }

    public function registerComponents() {
        return [
            'SpAnjaan\Forms\Components\UploadForm' => 'uploadForm',
        ];
    }

    public function registerMailTemplates() {
        return [
            'spanjaan.forms::mail.notification',
            'spanjaan.forms::mail.autoresponse',
        ];
    }

    public function registerSchedule($schedule) {
        $schedule->call(function () {
            MagicForm::gdprClean();
        })->daily();
    }
    
}
