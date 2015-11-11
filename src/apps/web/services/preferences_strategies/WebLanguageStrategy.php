<?php
namespace EuroMillions\web\services\preferences_strategies;

use EuroMillions\web\interfaces\ILanguageStrategy;
use EuroMillions\shareconfig\interfaces\IRequest;
use EuroMillions\shareconfig\interfaces\ISession;

class WebLanguageStrategy implements ILanguageStrategy
{
    private $session;
    private $request;

    const LANGUAGE_VAR = 'EM_language';

    public function __construct(ISession $session, IRequest $request)
    {
        $this->session = $session;
        $this->request = $request;
    }

    public function get()
    {
        if ($this->session->has(self::LANGUAGE_VAR)) {
            $language = $this->session->get(self::LANGUAGE_VAR);
        } else {
//EMTD reactivate language detection when we need it.
//            $language = $this->request->getBestLanguage();
//            $has_hyphen = strpos($language, '-');
//            if ($has_hyphen !== false) {
//                $language = substr($language, 0, $has_hyphen);
//            }
//            if (!$language) {
                $language = 'en';
//            }
            $this->session->set(self::LANGUAGE_VAR, $language);
        }
        return $language;
    }

    public function set($language)
    {
        $this->session->set(self::LANGUAGE_VAR, $language);
    }
}