#Contact Form


This repository holds a Contact Form built with Symfony 2 with field validations including Recaptcha 2 from Google. After submitted will send an email to the admin with the client data and message. The form also allows the client to send a copy of the message to his email.



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

To configure database rename the file `.env.example` in the root directory to `.env`, then add your database parameters on this file.