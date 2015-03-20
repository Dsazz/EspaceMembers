<?php

namespace EspaceMembers\MainBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class EventAdmin extends Admin
{
    //public $supportsPreviewMode = true;
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
            ->add('title', null, array('label' => 'Title'))
            ->add('startDate', null, array('label' => 'Start date'))
            ->add('completionDate', null, array('label' => 'Completion date'))
            ->add('year', null, array('label' => 'Year'))
            ->add('description', null, array('label' => 'Description'))
            ->add('is_show', null, array('label' => 'Is show ?'))
            ->add('frontImage', 'sonata_type_admin', array('label' => 'Front image'))
            ->add('flayer', 'sonata_type_admin', array('label' => 'Flayer'))
            ->add('teachings', null, array('label' => 'Teachings'))
            ->add('category', null, array('label' => 'Category'))
            ->add('users', null, array('label' => 'Users'))
            ->add('groups', null, array('label' => 'Groups'))
            ->add('tags', null, array('label' => 'Tags'))
        ;
    }

    //public function validate(ErrorElement $errorElement, $object)
    //{
        //$errorElement
            //->with('flayer')
                //->assertFile(array(
                    //'maxSize' => '50M',
                    //'mimeTypes' => array(
                        //'application/pdf', 'application/pdf', 'application/x-pdf',
                        //'application/acrobat', 'applications/vnd.pdf', 'text/pdf', 'text/x-pdf'
                    //),
                    //'notFoundMessage' => 'The mime type of the file is invalid. Allowed mime types are PDF !',
                //))
            //->end()
            //->with('frontImage')
                //->assertFile(array(
                    //'maxSize' => '50M',
                    //'mimeTypes' => array(
                        //'image/pjpeg','image/jpeg','image/png',
                        //'image/x-png', 'image/gif'
                    //),
                    //'notFoundMessage' => "The mime type of the file is invalid. Allowed mime types are 'jpg', 'png', 'gif', 'jpeg' !",
                //))
            //->end()
            //;
    //}


    /**
     * Конфигурация формы редактирования записи
     * @param  \Sonata\AdminBundle\Form\FormMapper $formMapper
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title', null, array('label' => 'Title'))
            ->add('startDate', 'date', array(
                'label' => 'Start date',
                'format' => 'dd MMMM yyyy',
                'widget' => 'choice',
                'years' => range(date('Y'), date('Y')-70),
            ))
            ->add('completionDate', 'date', array(
                'label' => 'Completion date',
                'format' => 'dd MMMM yyyy',
                'widget' => 'choice',
                'years' => range(date('Y'), date('Y')-70),
            ))
            ->add('year', 'choice', array('choices' => $this->buildYearChoices()))
            ->add('description', 'textarea', array('attr' => array('class' => 'ckeditor')))
            ->add('is_show', null, array('label' => 'Is show ?', 'required' => false))
            ->add('flayer', 'sonata_type_model_list', array(
                'cascade_validation' => true,
                'btn_list' => false,
                'required' => false,
            ),
            array(
                'link_parameters' => array(
                    'provider' => 'sonata.media.provider.file',
                    'context'  => 'flayer',
                ),
                'cascade_validation' => true,
            ))
            ->add('frontImage', 'sonata_type_model_list', array(
                'cascade_validation' => true,
                'btn_list' => false,
                //'required' => false,
            ),
            array(
                'link_parameters' => array(
                    'cascade_validation' => true,
                    'provider' => 'sonata.media.provider.image',
                    'context'  => 'cover',
                ),
                'cascade_validation' => true,
            ))
            ->add('category', 'entity' , array(
                'label' => 'Category',
                'class' => 'EspaceMembers\MainBundle\Entity\Category',
                'expanded' => false,
                'multiple' =>false
            ))
            ->add('teachings', 'sonata_type_model',
                array(
                    'by_reference' => false,
                    'expanded' => false,
                    'multiple' => true,
                    'btn_add'  => false,
            ))
            //->add('users', 'sonata_type_model',
                //array(
                    //'by_reference' => false,
                    //'expanded' => false,
                    //'multiple' => true,
                    //'btn_add'  => false,
            //))
            ->add('groups', 'sonata_type_model',
                array(
                    'by_reference' => false,
                    'expanded' => false,
                    'multiple' => true,
                    'btn_add'  => false,
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

    public function buildYearChoices()
    {
        $distance = 5;
        $yearsBefore = date('Y', mktime(0, 0, 0, date("m"), date("d"), date("Y") ));
        $yearsAfter = date('Y', mktime(0, 0, 0, date("m"), date("d"), date("Y") + $distance));

        return array_combine(range($yearsBefore, $yearsAfter), range($yearsBefore, $yearsAfter));
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
            ->addIdentifier('title', null, array('label' => 'Title'))
            ->add('startDate', null, array('label' => 'Start date'))
            ->add('completionDate', null, array('label' => 'Completion date'))
            ->add('year', null, array('label' => 'Year'))
            ->add('description', null, array('label' => 'Description'))
            ->add('is_show', null, array('label' => 'Is show ?'))
            ->add('frontImage', 'sonata_type_admin', array('label' => 'Front image'))
            ->add('flayer', 'sonata_type_admin', array('label' => 'Flayer'))
            ->add('teachings', null, array('label' => 'Teachings'))
            ->add('category', null, array('label' => 'Category'))
            ->add('users', null, array('label' => 'Users'))
            ->add('groups', null, array('label' => 'Groups'))
            ->add('tags', null, array('label' => 'Tags'))
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
            ->add('title', null, array('label' => 'Title'));
    }

}
