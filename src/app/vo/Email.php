<?php
namespace EuroMillions\vo;

class Email extends ValueObject
{
    private $email;

    public function __construct($email)
    {
        $this->assertNotEmpty($email);
        $filtered_email = $this->getFilteredEmail($email);

        $this->email = $filtered_email;
    }

    /**
     * @param $email
     * @return mixed
     */
    private function getFilteredEmail($email)
    {
        $filtered_email = filter_var($email, FILTER_VALIDATE_EMAIL);

        if ($filtered_email === false) {
            throw new \InvalidArgumentException('Invalid email address');
        }
        return $filtered_email;
    }

    public function email()
    {
        return $this->email;
    }
}