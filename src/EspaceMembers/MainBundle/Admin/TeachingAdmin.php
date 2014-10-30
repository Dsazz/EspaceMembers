<?php

namespace EspaceMembers\MainBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Symfony\Component\Validator\Constraints as Assert;

use EspaceMembers\MainBundle\Entity\Teaching;
use EspaceMembers\MainBundle\Form\Type\TagType;
use Knp\Menu\ItemInterface as MenuItemInterface;

class TeachingAdmin extends Admin
{
    /*
     * Конфигурация отображения записи
     *
     * @param \Sonata\AdminBundle\Show\ShowMapper $showMapper
     * @return void
    */
    protected function configureShowField(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id', null, array('label' => 'ID'))
            ->add('title', null, array('label' => 'Title'))
            ->add('serial', null, array('label' => 'Serial number'))
            ->add('date', null, array('label' => 'Date presentation'))
            ->add('dayNumber', null, array('label' => 'Day number'))
            ->add('dayTime', null, array('label' => 'Time of day'))
            ->add('event', null, array('label' => 'Event'))
            ->add('users', null, array('label' => 'Owners'))
            ->add('tags', null, array('label' => 'Tags'))
            ->add('voies', null, array('label' => 'Voies'))
            ->add('lesson', 'sonata_type_admin', array('label' => 'Lesson'))
            ->add('resume', null, array('label' => 'Resume'))
            ->add('technical_comment', null, array('label' => 'Technical comment'))
        ;
    }

    //public function validate(ErrorElement $errorElement, $object)
    //{
        //$errorElement
            //->with('lesson')
                //->assertFile(array(
                    //'maxSize' => '700M',
                    //'mimeTypes' => array(
                        //'video/mp4', 'video/x-flv', 'audio/mpeg',
                        //'application/x-troff-msvideo', 'video/avi',
                        //'applicationvideo/msvideo', 'video/x-msvideo'
                    //),
                    //'notFoundMessage' => "The mime type of the file is invalid. Allowed mime types are '.mp4', '.flv', '.mp3'",
                //))
            //->end()
            //;
    //}

    /**
     * Конфигурация формы редактирования записи
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title', null, array('label' => 'Title'))
            ->add('serial', 'integer', array(
                'label' => 'Serial number',
                //'data' => 0, // default value
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
                'label' => 'Date',
                'format' => 'dd MMMM yyyy',
                'widget' => 'choice',
                'years' => range(date('Y'), date('Y')-70),
            ))
            ->add('dayNumber', 'integer', array(
                'label' => 'Day number',
                //'data' => 0, // default value
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
            ->add('dayTime', null, array('label' => 'Time of day'))
            ->add('lesson', 'sonata_type_model_list', array('btn_list' => false,), array(
                'link_parameters' => array(
                    'provider' => 'sonata.media.provider.file',
                    'context'  => 'lesson',
                    //'cascade_validation' => true
                )
            ))
            ->add('is_show', null, array('label' => 'Is show ?','required' => false))
            ->add('resume', 'textarea', array('attr' => array('class' => 'ckeditor'), 'required' => false,))
            ->add('technical_comment', 'textarea', array('attr' => array('class' => 'ckeditor'), 'required' => false,))
            //->add('event', 'entity' , array(
                //'label' => 'Event',
                //'required' => false,
                //'class' => 'EspaceMembers\MainBundle\Entity\Event',
                //'expanded' => false,
                //'multiple' =>false
            //))
            //->add('users', 'sonata_type_model', array(
                //'by_reference' => false,
                //'expanded' => false,
                //'multiple' => true,
                //'btn_add'  => false,
            //))
            ->add('voies', 'sonata_type_model',
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
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     * @return void
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('title', null, array('label' => 'Title'))
            ->add('serial', null, array('label' => 'Serial number'))
            ->add('date', null, array('label' => 'Date presentation'))
            ->add('dayNumber', null, array('label' => 'Day number'))
            ->add('dayTime', null, array('label' => 'Time of day'))
            ->add('event', null, array('label' => 'Event'))
            ->add('users', null, array('label' => 'Owners'))
            ->add('tags', null, array('label' => 'Tags'))
            ->add('voies', null, array('label' => 'Voies'))
            ->add('lesson', null, array('label' => 'Lesson'))
            ->add('resume', null, array('label' => 'Resume'))
            ->add('technical_comment', null, array('label' => 'Technical comment'))
        ;
    }

    /**
     * Поля, по которым производится поиск в списке записей
     *
     * @param \Sonata\AdminBundle\Datagrid\DatagridMapper $datagridMapper
     * @return void
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title', null, array('label' => 'Title'));
    }

}
