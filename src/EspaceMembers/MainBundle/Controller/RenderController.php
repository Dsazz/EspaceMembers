<?php
/**
 * This file is part of the EspaceMembers project.
 *
 * (c) Stanislav Stepanenko <dsazztazz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EspaceMembers\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * RenderController
 *
 * @author Stepanenko Stanislav <dsazztazz@gmail.com>
 */
class RenderController extends Controller
{
    public function rightBlockAction($currentPath)
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('EspaceMembersMainBundle:Template\right-block:index.html.twig', array(
            'chronologies' => $em->getRepository('EspaceMembersMainBundle:Event')->getYears(),
            'categories'   => $em->getRepository('EspaceMembersMainBundle:Category')->getNames(),
            'directions'   => $em->getRepository('EspaceMembersMainBundle:Direction')->getNames(),
            'teachers'     => $em->getRepository('ApplicationSonataUserBundle:User')->findTeachers(),
            'tags'         => $em->getRepository('EspaceMembersMainBundle:Tag')->getNames(),
            'currentPath'  => $currentPath
        ));
    }
}
