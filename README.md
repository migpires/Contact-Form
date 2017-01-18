#Contact Form


This repository holds a Contact Form built with Symfony 2 with field validations including Recaptcha 2 from Google. After submitted will send an email to the admin with the client data and message. The form also allows the client to send a copy of the message to his email. It also has a language switcher from english to german.



##Install & Run

	
Open the application directory in console and run the following commands:


###Packages

```
composer update
```


###Run

```
php app/console server:run
```


##Parameters

To add the necessary parameters rename the file `parameters.yml.dist` in the directory `app/config` to `parameters.yml`, then add the following parameters on this file:

* mailer_name: `Contact's Form name`
* mailer_subject: `Email to admin subject`
* mailer_copy_subject: `Email to client subject`
* mailer_to: `Admin's email`
* locale: `en`
* recaptcha_theme: `Recaptcha theme`
* recaptcha_type: `Recaptcha type`
* recaptcha_size: `Recaptcha size`
* recaptcha_site_key: `Recaptcha site key`
* recaptcha_secret_key: `Recaptcha secret key`

Note: To configure Swiftmailer personally I recommend to use [STMP service from Gmail](http://symfony.com/doc/current/email/gmail.html).

##Recaptcha

To obtain the Recaptcha site key and secret key you need to [register your domain on Recaptcha site](https://www.google.com/recaptcha/admin#list).

Note: If you're using localhost you can add your localhost address.

