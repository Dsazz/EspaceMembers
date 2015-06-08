<?php
/**
 * This file is part of the EspaceMembers project.
 *
 * (c) Stanislav Stepanenko <dsazztazz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EspaceMembers\MainBundle\Admin;

use Sonata\UserBundle\Admin\Model\GroupAdmin as BaseGroupAdmin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * GroupAdmin
 *
 * @author Stepanenko Stanislav <dsazztazz@gmail.com>
 */
class GroupAdmin extends BaseGroupAdmin
{
    /*
     * Конфигурация отображения записи
     *
     * @param  \Sonata\AdminBundle\Show\ShowMapper $showMapper
     * @return void
    */
    protected function configureShowField(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('name')
            ->add('roles', 'choice', array(
                'choices' => $this->getExistingRoles(),
                'multiple' => true,
            ))
        ;
    }

    /**
     * Конфигурация формы редактирования записи
     * @param  \Sonata\AdminBundle\Form\FormMapper $formMapper
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name')
            ->add('roles', 'choice', array(
                'choices' => $this->getExistingRoles(),
                'expanded' => true,
                'multiple' => true,
                'mapped' => true,
            ));
    }

    /**
     * Конфигурация списка записей
     *
     * @param  \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     * @return void
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('name')
            ->add('roles', 'choice', array(
                'choices' => $this->getExistingRoles(),
                'multiple' => true,
            ))
        ;
    }

    /**
     * Поля, по которым производится поиск в списке записей
     *
     * @param  \Sonata\AdminBundle\Datagrid\DatagridMapper $datagridMapper
     * @return void
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name');
    }

    public function getExistingRoles()
    {
        $roleHierarchy = $this->getConfigurationPool()->getContainer()->getParameter('security.role_hierarchy.roles');
        $roles = array_keys($roleHierarchy);

        foreach ($roles as $role) {
            $theRoles[$role] = $role;
        }

        return $theRoles;
    }
}
