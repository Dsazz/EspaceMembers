<?php

namespace EspaceMembers\MainBundle\Admin;

use Sonata\UserBundle\Admin\Model\GroupAdmin as BaseGroupAdmin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

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
            ->add('id', null, array('label' => 'ID'))
            ->add('name', null, array('label' => 'Name'))
            ->add('roles', 'choice', array(
                'label' => 'Roles',
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
            ->add('name', null, array('label' => 'Name'))
            ->add('roles', 'choice', array(
                'choices' => $this->getExistingRoles(),
                'label' => 'Roles',
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
            ->addIdentifier('name', null, array('label' => 'Name'))
            //->add('roles', null, array('label' => 'Role'))
            ->add('roles', 'choice', array(
                'label' => 'Roles',
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
            ->add('name', null, array('label' => 'Name'));
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
