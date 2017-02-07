<?php

namespace ContactBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use ContactBundle\Entity\Contact;
use ContactBundle\Form\ContactType;

class ContactController extends Controller
{
    public function indexAction(Request $request)
    {
        $contact = new Contact();
        $form = $this->createForm(new ContactType, $contact, array(
            'method'=>'POST'
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //Add form submitted data to variables
            $name = $form->get('name')->getData();
            $first_name = $form->get('first_name')->getData();
            $company = $form->get('company')->getData();
            $email = $form->get('email')->getData();
            $phone_number = $form->get('phone_number')->getData();
            $message = $form->get('message')->getData();
            $send_a_copy_to_my_email = $form->get('send_copy')->getData();
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
            $success = $this->get('translator')->trans('Message successfully sent!');

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
