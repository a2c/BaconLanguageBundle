<?php
/**
 * Created by PhpStorm.
 * User: adan
 * Date: 04/11/15
 * Time: 17:42
 */

namespace Bacon\Bundle\LanguageBundle\Controller\Backend;

use Bacon\Bundle\CoreBundle\Controller\AdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Language controller.
 */
class LocaleController extends AdminController
{
    /**
     * @Route("/change/locale/{current}/{locale}/",name="locale_change")
     * @Method("GET")
     */
    public function setLocaleAction(Request $request,$current,$locale)
    {
        $request->getSession()->set('_locale', $locale);

        $referer = str_replace($current,$locale,$request->headers->get('referer'));

        return new RedirectResponse($referer);

    }
}
