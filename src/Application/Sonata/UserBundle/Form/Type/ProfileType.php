<?php
/**
 * This file is part of the EspaceMembers project.
 *
 * (c) Stanislav Stepanenko <dsazztazz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * ProfileType
 *
 * @author Stepanenko Stanislav <dsazztazz@gmail.com>
 */
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
                    'label' => 'form.label_firstname',
                    'attr' => array('class' => 'pure-input-1-2'),
                    'required' => false,
                    'translation_domain' => 'SonataUserBundle'
                ))
                ->add('lastname', null, array(
                    'label' => 'form.label_lastname',
                    'attr' => array('class' => 'pure-input-1-2'),
                    'required' => false,
                    'translation_domain' => 'SonataUserBundle'
                ))
                ->add('avatar', 'sonata_media_type', array(
                        'provider' => 'sonata.media.provider.image',
                        'context' => 'avatar',
                        'required' => false,
                        'label' => 'form.label_avatar',
                        'translation_domain' => 'SonataUserBundle'
                    )
                )
                ->add('plainPassword', 'text', array(
                    'attr' => array('class' => 'pure-input-1-2'),
                    'required' => false,
                    'label' => 'form.label_plain_password',
                    'translation_domain' => 'SonataUserBundle'
                ))
                ->add('phone', null, array(
                    'label' => 'form.label_phone',
                    'attr' => array('class' => 'pure-input-1-2'),
                    'required' => false,
                    'translation_domain' => 'SonataUserBundle'
                ))
                ->add('address', null, array(
                    'label' => 'form.label_address',
                    'attr' => array('class' => 'pure-input-1-2'),
                    'required' => false,
                    'translation_domain' => 'SonataUserBundle'
                ))
                ->add('biography', 'textarea', array(
                    'label' => 'form.label_biography',
                    'attr' => array('class' => 'pure-input-1-2', 'rows' => '7'),
                    'required' => false,
                    'translation_domain' => 'SonataUserBundle'
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
                        'translation_domain' => 'SonataUserBundle'
                    ))
                    ->add('gender', null, array('label' => 'form.label_gender'))
                    ->add('is_teacher', null, array('label' => 'form.label_is_teacher'))
                    ->add('groups', 'entity', array(
                        'label' => 'form.label_groups',
                        'required' => false,
                        'class' => 'Application\Sonata\UserBundle\Entity\Group',
                        'expanded' => false,
                        'multiple' =>false,
                        'required' => true,
                        'translation_domain' => 'SonataUserBundle'
                    ))
                    ->add('email', 'email', array(
                        'label' => 'form.email',
                        'attr' => array('class' => 'pure-input-1-2'),
                        'required' => false,
                        'translation_domain' => 'SonataUserBundle'
                    ))
            ;
        }
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
