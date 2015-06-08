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

use Sonata\UserBundle\Admin\Model\UserAdmin as BaseUserAdmin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Validator\ErrorElement;

/**
 * UserAdmin
 *
 * @author Stepanenko Stanislav <dsazztazz@gmail.com>
 */
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
                    ->add('firstname')
                    ->add('lastname')
                    ->add('avatar', 'sonata_type_model_list',
                        array(
                            'btn_list' => false
                        ),
                        array(
                            'link_parameters' => array('context' => 'avatar')
                        )
                    )
                    ->add('plainPassword', 'text', array(
                        'required' => (!$this->getSubject() || is_null($this->getSubject()->getId()))
                    ))
                    ->add('phone')
                    ->add('address')
                    ->add('biography', 'textarea', array(
                        'attr' => array('class' => 'ckeditor'),
                        'required' => false,
                    ))
                    ->add('events', 'sonata_type_model', array(
                        'by_reference' => false,
                        'expanded' => false,
                        'multiple' => true,
                        'btn_add'  => false,
                        'required' => false,
                    ))
                    ->add('teachings', 'sonata_type_model', array(
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
                        ->add('is_teacher')
                        ->add('groups', 'entity', array(
                            'required' => false,
                            'class' => 'Application\Sonata\UserBundle\Entity\Group',
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
                        ), array('translation_domain' => 'SonataUserBundle'))
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
            ->add('firstname')
            ->add('lastname')
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
            ->addIdentifier('id')
            ->add('firstname')
            ->add('lastname')
            ->add('email')
            ->add('dateOfBirth')
            ->add('gender')
            ->add('roles', 'choice', array(
                'choices' => $this->getExistingRoles(),
                'multiple' => true,
            ), array('translation_domain' => 'SonataUserBundle'))
            ->add('createdAt')
            ;

        if ($this->isGranted('ROLE_ALLOWED_TO_SWITCH')) {
            $listMapper
                ->add('is_teacher', null, array('editable' => true))
                ->add('enabled', null, array('editable' => true))
                ->add('locked', null, array('editable' => true))
            ;
        } else {
            $listMapper
                ->add('is_teacher', null, array('editable' => false))
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
