<?php

namespace EspaceMembers\MainBundle\Admin;

use Sonata\UserBundle\Admin\Model\UserAdmin as BaseUserAdmin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Validator\ErrorElement;

class UserAdmin extends BaseUserAdmin
{
    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->with('General')
                ->add('firstname')
                ->add('lastname')
                ->add('email')
            ->end()
            ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->tab('User')
                ->with('General')
                    ->add('firstname', null, array('label' => 'First name', 'required' => true))
                    ->add('lastname', null, array('label' => 'Last name', 'required' => true))
                    ->add('avatar', 'sonata_type_model_list',
                        array(
                            'label' => 'Avatar',
                            'btn_list' => false
                        ),
                        array(
                            'link_parameters' => array('context' => 'avatar')
                        )
                    )
                    ->add('plainPassword', 'text', array(
                        'required' => (!$this->getSubject() || is_null($this->getSubject()->getId()))
                    ))
                    ->add('phone', null, array('label' => 'Phone'))
                    ->add('address', null, array('label' => 'Address'))
                    ->add('biography', 'textarea', array(
                        'label' => 'Description',
                        'attr' => array('class' => 'ckeditor'),
                        'required' => false,
                    ))
                    ->add('events', 'sonata_type_model', array(
                        'label' => 'Events',
                        'by_reference' => false,
                        'expanded' => false,
                        'multiple' => true,
                        'btn_add'  => false,
                        'required' => false,
                    ))
                    ->add('teachings', 'sonata_type_model', array(
                        'label' => 'Teachings',
                        'by_reference' => false,
                        'expanded' => false,
                        'multiple' => true,
                        'btn_add'  => false,
                        'required' => false,
                    ))
                ->end()
        ;

        if ($this->getSubject() && !$this->getSubject()->hasRole('ROLE_SUPER_ADMIN')) {
            $formMapper
                    ->with('Administrator')
                        ->add('dateOfBirth', 'birthday')
                        ->add('gender')
                        ->add('is_teacher', null, array('label' => 'Is teacher ?'))
                        ->add('groups', 'entity', array(
                            'label' => 'Group',
                            'required' => false,
                            'class' => 'EspaceMembers\MainBundle\Entity\Group',
                            'expanded' => false,
                            'multiple' =>false,
                            'required' => true,
                        ))
                    ->end()
                ->end()
                ->tab('Security')
                    ->with('Status')
                        ->add('email')
                        ->add('roles','choice',array(
                            'choices'  => $this->getExistingRoles(),
                            'expanded' => true,
                            'multiple' => true,
                        ))
                        ->add('locked', null, array('required' => false))
                        ->add('enabled', null, array('required' => false))
                    ->end()
                ->end()
            ;
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $filterMapper)
    {
        $filterMapper
            ->add('id')
            ->add('firstname', null, array('label' => 'First name'))
            ->add('lastname', null, array('label' => 'Last name'))
            ->add('locked')
            ->add('email')
            ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id', null, array('label' => 'User ID'))
            ->add('firstname', null, array('label' => 'First name'))
            ->add('lastname', null, array('label' => 'Last name'))
            ->add('email')
            ->add('dateOfBirth', null, array('label' => 'birthday'))
            ->add('gender', null, array('label' => 'gender'))
            ->add('roles', 'choice', array(
                'label' => 'Roles',
                'choices' => $this->getExistingRoles(),
                'multiple' => true,
            ))
            ->add('createdAt')
            ;

        if ($this->isGranted('ROLE_ALLOWED_TO_SWITCH')) {
            $listMapper
                ->add('is_teacher', null, array('editable' => true, 'label' => 'Teacher'))
                ->add('enabled', null, array('editable' => true))
                ->add('locked', null, array('editable' => true))
            ;
        } else {
            $listMapper
                ->add('is_teacher', null, array('editable' => false, 'label' => 'Teacher'))
                ->add('enabled', null, array('editable' => false))
                ->add('locked', null, array('editable' => false))
            ;
        }
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
