<?php

namespace ContactBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue as RecaptchaTrue;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ContactController extends Controller
{
    public function indexAction(Request $request)
    {
        //Add parameters for Recaptcha
        $theme = $this->container->getParameter('recaptcha_theme');
        $type = $this->container->getParameter('recaptcha_type');
        $size = $this->container->getParameter('recaptcha_size');

        //Build form
        $form = $this->createFormBuilder()
            ->add('name', TextType::class, array('label_attr' => array('class' => 'col-md-4'), 'attr' => array('class' => 'col-md-8')))
            ->add('first_name', TextType::class, array('label_attr' => array('class' => 'col-md-4'), 'attr' => array('class' => 'col-md-8')))
            ->add('company', TextType::class, array('required' => false, 'label_attr' => array('class' => 'col-md-4'), 'attr' => array('class' => 'col-md-8')))
            ->add('email', EmailType::class, array('label_attr' => array('class' => 'col-md-4'), 'attr' => array('class' => 'col-md-8')))
            ->add('phone_number', NumberType::class, array('required' => false, 'label_attr' => array('class' => 'col-md-4'), 'attr' => array('class' => 'col-md-8')))
            ->add('message', TextareaType::class, array('label_attr' => array('class' => 'col-md-4'), 'attr' => array('class' => 'col-md-8', 'placeholder' => 'Type your message here')))
            ->add('send_a_copy_to_my_email', CheckboxType::class, array('required' => false, 'label_attr' => array('class' => 'col-md-4')))
            ->add('recaptcha', EWZRecaptchaType::class, array('label' => false, 'mapped' => false, 'attr' => array('options' => array('theme' => $theme, 'type'  => $type, 'size'  => $size)), 'constraints' => array(new RecaptchaTrue())))
            ->add('send', SubmitType::class, array('attr' => array('class' => 'btn btn-success')))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //Add form submitted data to variables
            $name = $form->get('name')->getData();
            $first_name = $form->get('first_name')->getData();
            $company = $form->get('company')->getData();
            $email = $form->get('email')->getData();
            $phone_number = $form->get('phone_number')->getData();
            $message = $form->get('message')->getData();
            $send_a_copy_to_my_email = $form->get('send_a_copy_to_my_email')->getData();
            $recaptcha = $form->get('recaptcha')->getData();

            //Add parameters for Swiftmailer
            $contact_name = $this->container->getParameter('mailer_name');
            $subject = $this->container->getParameter('mailer_subject');
            $to = $this->container->getParameter('mailer_to');

            //Send mail to admin
            $mail = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom(array($email => $name))
                ->setTo($to)
                ->setBody($this->renderView('ContactBundle:Contact:mail.html.twig',array('name' => $name, 'company' => $company, 'email' => $email, 'phone_number' => $phone_number, 'message' => $message)), 'text/html');

            $this->get('mailer')->send($mail);

            //Send mail to client
            if($send_a_copy_to_my_email !== false) {

              //Translate client subject
              $copy_subject = $this->get('translator')->trans('Your Message Copy');

              $mail = \Swift_Message::newInstance()
                  ->setSubject($copy_subject)
                  ->setFrom(array($email => $contact_name))
                  ->setTo($email)
                  ->setBody($this->renderView('ContactBundle:Contact:mail_client.html.twig',array('first_name' => $first_name, 'contact_name' => $contact_name, 'message' => $message)), 'text/html');

              $this->get('mailer')->send($mail);
            }

            //Translate success message
            $success = $this->get('translator')->trans('Thank you very much for your request!');

            //Add message to Flashbag
            $request->getSession()->getFlashBag()->add('success', $success);

            $lang = $request->getLocale();

            //Redirect to Contact page
            return $this->redirect($this->generateUrl('contact_lang', array('_locale' => $lang)));
        }

        //Render form
        return $this->render('ContactBundle:Contact:index.html.twig', array(
            'form'=>$form->CreateView()
        ));
    }
}
