<?php

namespace ContactBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue as RecaptchaTrue;

class ContactType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('name', TextType::class, array('label_attr' => array('class' => 'col-md-4'), 'attr' => array('class' => 'col-md-8')))
                ->add('first_name', TextType::class, array('label_attr' => array('class' => 'col-md-4'), 'attr' => array('class' => 'col-md-8')))
                ->add('company', TextType::class, array('required' => false, 'label_attr' => array('class' => 'col-md-4'), 'attr' => array('class' => 'col-md-8')))
                ->add('email', EmailType::class, array('label_attr' => array('class' => 'col-md-4'), 'attr' => array('class' => 'col-md-8')))
                ->add('phone_number', NumberType::class, array('required' => false, 'label_attr' => array('class' => 'col-md-4'), 'attr' => array('class' => 'col-md-8')))
                ->add('message', TextareaType::class, array('label_attr' => array('class' => 'col-md-4'), 'attr' => array('class' => 'col-md-8', 'placeholder' => 'Type your message here')))
                ->add('send_copy', CheckboxType::class, array('required' => false, 'label_attr' => array('class' => 'col-md-4')))
                ->add('recaptcha', EWZRecaptchaType::class, array('label' => false, 'mapped' => false, 'attr' => array('options' => array('theme' => 'dark', 'type'  => 'image', 'size'  => 'normal')), 'constraints' => array(new RecaptchaTrue())))
                ->add('send', SubmitType::class, array('attr' => array('class' => 'btn btn-success')));
    }
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array('data_class'=>'ContactBundle\Entity\Contact'));
    }
    public function getName() {
        return 'contactbundle_contact';
    }

}
