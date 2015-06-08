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

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TeachingAdmin
 *
 * @author Stepanenko Stanislav <dsazztazz@gmail.com>
 */
class TeachingAdmin extends Admin
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
            ->add('title')
            ->add('serial')
            ->add('date')
            ->add('dayNumber')
            ->add('dayTime')
            ->add('event')
            ->add('users')
            ->add('tags')
            ->add('directions')
            ->add('lesson')
            ->add('resume')
            ->add('technical_comment')
        ;
    }

    public function validate(ErrorElement $errorElement, $object)
    {
        $errorElement
            ->with('lesson')
            ->assertNotNull(array())
            ->assertNotBlank()
            ->assertFile(array('maxSize' => '3000000'))
        ->end();
    }

    /**
     * Конфигурация формы редактирования записи
     * @param  \Sonata\AdminBundle\Form\FormMapper $formMapper
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title')
            ->add('serial', 'integer', array(
                'precision' => 0, // disallow floats
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Type('integer'),
                    new Assert\Regex(array(
                        'pattern' => '/^[0-9]\d*$/',
                        'message' => 'Please use only positive numbers.'
                        )
                    ),
                    new Assert\Length(array('max' => 2))
                )
            ))
            ->add('date', 'date', array(
                'format' => 'dd MMMM yyyy',
                'widget' => 'choice',
                'years' => range(date('Y'), date('Y')-70),
            ))
            ->add('dayNumber', 'integer', array(
                'precision' => 0, // disallow floats
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Type('integer'),
                    new Assert\Regex(array(
                        'pattern' => '/^[0-9]\d*$/',
                        'message' => 'Please use only positive numbers.'
                        )
                    ),
                    new Assert\Length(array('max' => 2))
                )
            ))
            ->add('dayTime')
            ->add('lesson', 'sonata_type_model_list',
                array(
                    'btn_list' => false,
                    'cascade_validation' => true,
                ),
                array(
                    'link_parameters' => array(
                        'provider' => 'sonata.media.provider.file',
                        'context'  => 'lesson',
                    ),
                    'cascade_validation' => true,
                )
            )
            ->add('is_show', null, array('required' => false))
            ->add('resume', 'textarea', array('attr' => array('class' => 'ckeditor'), 'required' => false,))
            ->add('technical_comment', 'textarea', array('attr' => array('class' => 'ckeditor'), 'required' => false,))
            ->add('event', 'entity' , array(
                'required' => false,
                'class' => 'EspaceMembers\MainBundle\Entity\Event',
                'expanded' => false,
                'multiple' =>false
            ))
            ->add('users', 'sonata_type_model', array(
                'by_reference' => false,
                'expanded' => false,
                'multiple' => true,
                'btn_add'  => false,
            ))
            ->add('directions', 'sonata_type_model',
                array(
                    'by_reference' => false,
                    'expanded' => false,
                    'multiple' => true,
                    'btn_add'  => false,
                    'required' => false,
            ))
            ->add('tags', 'sonata_type_model',
                array(
                    'by_reference' => false,
                    'expanded' => false,
                    'multiple' => true,
                    'required' => false,
            ))
        ;
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
            ->addIdentifier('title')
            ->add('serial')
            ->add('date')
            ->add('dayNumber')
            ->add('dayTime')
            ->add('event')
            ->add('users')
            ->add('tags')
            ->add('directions')
            ->add('lesson')
            ->add('resume')
            ->add('technical_comment')
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
            ->add('title');
    }

}
