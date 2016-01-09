<?php

namespace Bacon\Bundle\LanguageBundle\Twig\Extension;

use \Twig_Extension;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\Bundle\DoctrineBundle\Registry;

/**
 * Class LanguageExtension
 * @package Bacon\Bundle\LanguageBundle\Twig\Extension
 * @author Adan Felipe Medeiros
 */
class LanguageExtension extends Twig_Extension
{

    /**
     * @var Router
     */
    private $router;

    /**
     * @var RequestStack
     */
    private $request;

    /**
     * @var Registry
     */
    private $doctrine;

    /**
     * LanguageExtension constructor.
     *
     * @param Router $router
     * @param RequestStack $request
     * @param Registry $doctrine
     */
    public function __construct(Router $router,RequestStack $request,Registry $doctrine)
    {
        $this->router   = $router;
        $this->request  = $request;
        $this->doctrine = $doctrine;
    }

    public function renderMenuLanguages()
    {
        $languages          =   $this->getDoctrine()->getRepository('BaconLanguageBundle:Language')->findAll();

        $htmlReturn = '';
        foreach ($languages as $lang) {
            $htmlReturn .= '<li><a href="' . $this->router->generate('locale_change', [ 'current' => $this->request->getCurrentRequest()->getLocale(), 'locale' => $lang->getAcron()]) . '"><span class="flag-icon flag-icon-'. $this->getAcronByLocale($lang->getLocale()) .'"></span>&nbsp;&nbsp;&nbsp;'. $lang->getName() .'</a></li>';
        }

        return $htmlReturn;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('bacon_menu_language_render', array($this, 'renderMenuLanguages'), array('is_safe' => array('html')))
        ];
    }

    /**
     * @return \Doctrine\Bundle\DoctrineBundle\Registry
     */
    public function getDoctrine()
    {
        return $this->doctrine;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bacon_languge_extension';
    }

    private function getAcronByLocale($locale)
    {
        $country = explode('_', $locale);
        return strtolower($country[1]);
    }
}
