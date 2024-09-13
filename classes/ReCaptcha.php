<?php

namespace SpAnjaan\Forms\Classes;

use SpAnjaan\Forms\Classes\BackendHelpers;
use SpAnjaan\Forms\Models\Settings;
use Winter\Translate\Classes\Translator;

trait ReCaptcha {

    /**
     * @var Winter\Translate\Classes\Translator Translator object.
     */
    protected $translator;

    /**
     * @var string The active locale code.
     */
    public $activeLocale;

    public function init() {
        if (BackendHelpers::isTranslatePlugin()) {
            $this->translator = Translator::instance();
        }
    }

    private function isReCaptchaEnabled() {
        return ($this->property('recaptcha_enabled') && Settings::get('recaptcha_site_key') != '' && Settings::get('recaptcha_secret_key') != '');
    }

    private function isReCaptchaMisconfigured() {
        return ($this->property('recaptcha_enabled') && (Settings::get('recaptcha_site_key') == '' || Settings::get('recaptcha_secret_key') == ''));
    }

    private function loadReCaptcha() {
        $this->addJs('https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit', ['defer' => true]);
        $this->addJs('assets/js/recaptcha.js');
    }
}
