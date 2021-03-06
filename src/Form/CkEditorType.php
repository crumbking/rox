<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CkEditorType extends TextAreaType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            // check if data is only <p>&nbsp;</p> and if so trim to empty string
            $data = $event->getData();
            if ('<p>&nbsp;</p>' === $data) {
                $event->setData('');
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);
        $view->vars = array_replace($view->vars, [
            'async' => $options['async'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'attr' => [
                    'class' => 'editor mb-1',
                ],
                'async' => false,
                'placeholder' => '',
                'error_bubbling' => false,
            ])
            ->addAllowedTypes('async', 'bool')
            ->addAllowedTypes('placeholder', 'string');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ckeditor';
    }
}
