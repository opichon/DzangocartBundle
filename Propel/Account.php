<?php

namespace Dzangocart\Bundle\DzangocartBundle\Propel;

use FOS\UserBundle\Propel\UserQuery;

use Dzangocart\Bundle\DzangocartBundle\Propel\om\BaseAccount;

class Account extends BaseAccount
{
    public function getUser()
    {
        return UserQuery::create()->filterByAccount($this)->findOne();
    }

    public function toCustomerDataArray()
    {
        $data = array(
            'given_names'  => $this->getGivenNames(),
            'surname'      => $this->getSurname(),
            'gender'       => $this->getGender(),
            'email'        => $this->getEmail(),
            'company_name' => $this->getOrganization(),
            'vat_id'       => $this->getVatId(),
            'address1'     => $this->getLine1(),
            'address2'     => $this->getLine2(),
            'city'         => $this->getCity(),
            'zip'          => $this->getZip(),
            'country'      => $this->getCountry(),
            'telephone'    => $this->getTelephone(),
            'corporate'    => $this->getOrganization() ? true : false
        );

        if (!$this->isNew()) {
            $data['code'] = $this->getId();
        }

        return $data;
    }
}
