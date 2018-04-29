<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DeleteMultipleController
 * @package Positibe\Bundle\CoreBundle\Controller
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class DeleteMultipleController extends Controller
{
    /**
     * There is not any configure route to this action, be security reason create your on route
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteAction(Request $request)
    {
        $resources = $request->get('form_delete_multiple')['ids'];

        $manager = $this->getDoctrine()->getManager();
        $repository = $manager->getRepository($request->get('form_delete_multiple')['model']);
        foreach (explode(',', $resources) as $id) {
            if ($resource = $repository->find($id)) {
                $manager->remove($resource);
            }
        }
        $manager->flush();

        return new RedirectResponse($request->server->get('HTTP_REFERER'), 302);
    }
}