<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace Application\Sonata\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProfileType extends AbstractType
{
    private $class;
    private $securityContext;
    private $container;

    /**
     * @param string $class The User class name
     */
    public function __construct($class, SecurityContext $securityContext, $container)
    {
        $this->class = $class;
        $this->securityContext = $securityContext;
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->securityContext->getToken()->getUser();
        $builder
                ->add('firstname', null, array(
                    'label' => 'First name',
                    'attr' => array('class' => 'pure-input-1-2'),
                    'required' => false))
                ->add('lastname', null, array(
                    'label' => 'Last name',
                    'attr' => array('class' => 'pure-input-1-2'),
                    'required' => false))
                ->add('avatar', 'sonata_media_type', array(
                        'provider' => 'sonata.media.provider.image',
                        'context' => 'avatar',
                        'required' => false,
                    )
                )
                ->add('plainPassword', 'text', array(
                    'attr' => array('class' => 'pure-input-1-2'),
                    'required' => false
                ))
                ->add('phone', null, array(
                    'label' => 'Phone',
                    'attr' => array('class' => 'pure-input-1-2'),
                    'required' => false,
                ))
                ->add('address', null, array(
                    'label' => 'Address',
                    'attr' => array('class' => 'pure-input-1-2'),
                    'required' => false,
                ))
                ->add('biography', 'textarea', array(
                    'label' => 'Description',
                    'attr' => array('class' => 'pure-input-1-2', 'rows' => '7'),
                    'required' => false,
                ))
        ;

        if ($user && $user->hasRole('ROLE_SUPER_ADMIN')) {
            $builder
                    ->add('dateOfBirth', 'date', array(
                        'label'  => 'Birthday',
                        'format' => 'dd MMMM yyyy',
                        'widget' => 'choice',
                        'years'  => range(date('Y'), date('Y')-70),
                        'required' => false,
                        'attr' => array('class' => 'fix-birthday'),
                        'empty_value' => false,
                    ))
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
                    ->add('email', 'email', array(
                        'label' => 'Email',
                        'attr' => array('class' => 'pure-input-1-2'),
                        'required' => false,
                    ))
                    ->add('roles','choice',array(
                        'choices'  => $this->getExistingRoles(),
                        'expanded' => true,
                        'multiple' => true,
                        'required' => false,
                        'attr' => array('class' => 'fix-role')
                    ))
            ;
        }
    }

    public function getExistingRoles()
    {
        $roleHierarchy = $this->container->getParameter('security.role_hierarchy.roles');
        $roles = array_keys($roleHierarchy);

        foreach ($roles as $role) {
            $theRoles[$role] = $role;
        }

        return $theRoles;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'espacemembers_user_profile';
    }
}
