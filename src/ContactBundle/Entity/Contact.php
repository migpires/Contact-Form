<?php

namespace ContactBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Contact {
  /**
   * @Assert\NotBlank()
   * @Assert\Length(min=2, max=50)
   */
  public $name;
  /**
   * @Assert\NotBlank()
   * @Assert\Length(min=2, max=50)
   */
  public $first_name;

  public $company;
  /**
   * @Assert\NotBlank()
   * @Assert\Email(
   *     message = "The email '{{ value }}' is not a valid email.",
   *     checkMX = true
   * )
   */
  public $email;

  public $phone_number;
  /**
   * @Assert\NotBlank()
   * @Assert\Length(min=2, max=250)
   */
  public $message;

  public $send_copy;

}
