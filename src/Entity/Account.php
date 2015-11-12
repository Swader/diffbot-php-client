<?php

namespace Swader\Diffbot\Entity;

use Swader\Diffbot\Abstracts\Entity;

class Account extends Entity
{
    /** @var bool */
    protected $carbonated = false;

    public function __construct(array $data)
    {
        parent::__construct($data);
        if (class_exists('\Carbon\Carbon')) {
            $this->carbonated = true;
            $format = 'o-m-d';
            \Carbon\Carbon::setToStringFormat($format);

        }
        $this->getInvoices();
    }

    /**
     * Returns false if token is "inactive", true otherwise
     * @return bool
     */
    public function isActive()
    {
        return !($this->data['status'] === 'inactive');
    }

    /**
     * Returns an array of arrays where each child array has two elements:
     * a date (Carbon instance if Carbon is installed), and an integer
     * indicating the number of API calls made.
     *
     * @return array
     */
    public function getApiCalls()
    {
        return $this->getOrDefault('apiCalls', []);
    }

    /**
     * Returns all invoices issued to the token holder, ever.
     *
     * Every invoice will be an array with "status", a date (Carbon instance if
     * Carbon is installed), totalCalls (int), totalAmount (int), plan,
     * and overageAmount (int).
     *
     * @return null | array
     */
    public function getInvoices()
    {
        $invoices = $this->getOrDefault('invoices');
        if ($invoices && $this->carbonated && !isset($this->data['invoices-carbonated'])) {
            foreach ($invoices as &$inv) {
                $inv['date'] = new \Carbon\Carbon($inv['date'], 'GMT');
            }
            $this->data['invoices'] = $invoices;
            $this->data['invoices-carbonated'] = true;
        }

        return $this->data['invoices'];
    }

    /**
     * Date of last billing.
     * Null if not available, instance of Carbon if Carbon is installed.
     * YYYY-MM-DD string otherwise.
     *
     * @return \Carbon\Carbon | string | null
     */
    public function getLastBilling()
    {
        $lastBilling = $this->getOrDefault('lastBilling');

        return ($lastBilling && $this->carbonated) ?
            new \Carbon\Carbon($lastBilling, 'GMT') :
            $lastBilling;
    }

    /**
     * @return string Name of the token owner
     */
    public function getName()
    {
        return $this->getOrDefault('name');
    }

    /**
     * Type of plan, i.e., custom, diffbot, starter...
     *
     * @return string
     */
    public function getPlan()
    {
        return $this->getOrDefault('plan');
    }

    /**
     * Email registered to token
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->getOrDefault('email');
    }

    /**
     * Returns the current status of the token (i.e. inactive, active...)
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->getOrDefault('status');
    }
}