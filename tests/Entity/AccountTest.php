<?php

namespace Swader\Diffbot\Test\Entity;

use Swader\Diffbot\Entity\Account;
use Swader\Diffbot\Entity\EntityIterator;
use Swader\Diffbot\Test\ResponseProvider;

class AccountTest extends ResponseProvider
{
    protected static $staticFiles = [
        'Account/demo/15-11-08-31d.json',
    ];

    protected function ei($file)
    {
        $acc = new Account(
            json_decode(
                parent::prepareResponsesStatic()[$file]->getBody(), true
            )
        );

        return new EntityIterator([$acc],
            parent::prepareResponsesStatic()[$file]);
    }


    public function statusProvider()
    {
        return [
            ['Account/demo/15-11-08-31d.json', 'inactive'],
        ];
    }

    /**
     * @param $file
     * @param $value1
     * @dataProvider statusProvider
     */
    public function testStatus($file, $value1)
    {
        /**
         * @var int $i
         * @var Account $entity
         */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($value1, $entity->getStatus());
            if ($value1 === 'inactive') {
                $this->assertFalse($entity->isActive());
            }
        }
    }

    public function invoicesProvider()
    {
        return [
            [
                'Account/demo/15-11-08-31d.json',
                array(
                    0 => array(
                        'date' => '2012-05-01',
                        'totalCalls' => 0,
                        'totalAmount' => 20,
                        'plan' => 'starter',
                        'overageAmount' => 0,
                        'status' => 'paid',
                    ),
                    1 => array(
                        'date' => '2012-04-01',
                        'totalCalls' => 0,
                        'totalAmount' => 20,
                        'plan' => 'starter',
                        'overageAmount' => 0,
                        'status' => 'paid',
                    ),
                    2 => array(
                        'date' => '2012-03-01',
                        'totalCalls' => 0,
                        'totalAmount' => 20,
                        'plan' => 'starter',
                        'overageAmount' => 0,
                        'status' => 'paid',
                    ),
                    3 => array(
                        'date' => '2012-02-01',
                        'totalCalls' => 0,
                        'totalAmount' => 20,
                        'plan' => 'starter',
                        'overageAmount' => 0,
                        'status' => 'unpaid',
                    ),
                ),
                [5, 4, 3, 2]
            ],
        ];
    }

    /**
     * @param $file
     * @param $value1
     * @param $value2
     * @dataProvider invoicesProvider
     */
    public function testInvoices($file, $value1, $value2)
    {
        /**
         * @var int $i
         * @var Account $entity
         */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($value1, $entity->getInvoices());
            //dump($entity->getInvoices());
            if (class_exists('\Carbon\Carbon')) {
                $value1 = $entity->getInvoices();
                foreach ($value1 as $j => $invoiceArray) {
                    $this->assertEquals($value2[$j], $invoiceArray['date']->month);
                }
            }
        }
    }

    public function apiCallsProvider()
    {
        return [
            [
                'Account/demo/15-11-08-31d.json',
                array(
                    0 => array('date' => '2015-11-08', 'calls' => 0,),
                    1 => array('date' => '2015-11-07', 'calls' => 0,),
                    2 => array('date' => '2015-11-06', 'calls' => 0,),
                    3 => array('date' => '2015-11-05', 'calls' => 100,),
                    4 => array('date' => '2015-11-04', 'calls' => 0,),
                    5 => array('date' => '2015-11-03', 'calls' => 0,),
                    6 => array('date' => '2015-11-02', 'calls' => 0,),
                    7 => array('date' => '2015-11-01', 'calls' => 0,),
                    8 => array('date' => '2015-10-31', 'calls' => 0,),
                    9 => array('date' => '2015-10-30', 'calls' => 0,),
                    10 => array('date' => '2015-10-29', 'calls' => 5,),
                    11 => array('date' => '2015-10-28', 'calls' => 0,),
                    12 => array('date' => '2015-10-27', 'calls' => 10,),
                    13 => array('date' => '2015-10-26', 'calls' => 0,),
                    14 => array('date' => '2015-10-25', 'calls' => 0,),
                    15 => array('date' => '2015-10-24', 'calls' => 0,),
                    16 => array('date' => '2015-10-23', 'calls' => 0,),
                    17 => array('date' => '2015-10-22', 'calls' => 0,),
                    18 => array('date' => '2015-10-21', 'calls' => 0,),
                    19 => array('date' => '2015-10-20', 'calls' => 0,),
                    20 => array('date' => '2015-10-19', 'calls' => 0,),
                    21 => array('date' => '2015-10-18', 'calls' => 0,),
                    22 => array('date' => '2015-10-17', 'calls' => 0,),
                    23 => array('date' => '2015-10-16', 'calls' => 0,),
                    24 => array('date' => '2015-10-15', 'calls' => 0,),
                    25 => array('date' => '2015-10-14', 'calls' => 0,),
                    26 => array('date' => '2015-10-13', 'calls' => 0,),
                    27 => array('date' => '2015-10-12', 'calls' => 0,),
                    28 => array('date' => '2015-10-11', 'calls' => 0,),
                    29 => array('date' => '2015-10-10', 'calls' => 0,),
                    30 => array('date' => '2015-10-09', 'calls' => 0,),
                )
            ],
        ];
    }

    /**
     * @param $file
     * @param $value1
     * @dataProvider apiCallsProvider
     */
    public function testApiCalls($file, $value1)
    {
        /**
         * @var int $i
         * @var Account $entity
         */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($value1, $entity->getApiCalls());
        }
    }

    public function lastBillingProvider()
    {
        return [
            ['Account/demo/15-11-08-31d.json', '2012-05-01', 2012],
        ];
    }

    /**
     * @param $file
     * @param $value1
     * @param $value2
     * @dataProvider lastBillingProvider
     */
    public function testLastBilling($file, $value1, $value2)
    {
        /**
         * @var int $i
         * @var Account $entity
         */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($value1, (string)$entity->getLastBilling());
            if (class_exists('\Carbon\Carbon')) {
                $this->assertEquals($value2, $entity->getLastBilling()->year);
            }
        }

    }

    public function basicsProvider()
    {
        return [
            [
                'Account/demo/15-11-08-31d.json',
                'Demo',
                'diffbot',
                'comments@diffbot.com'
            ],
        ];
    }

    /**
     * @param $file
     * @param $value1
     * @param $value2
     * @param $value3
     * @dataProvider basicsProvider
     */
    public function testName($file, $value1, $value2, $value3)
    {
        /**
         * @var int $i
         * @var Account $entity
         */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($value1, $entity->getName());
        }
    }

    /**
     * @param $file
     * @param $value1
     * @param $value2
     * @param $value3
     * @dataProvider basicsProvider
     */
    public function testPlan($file, $value1, $value2, $value3)
    {
        /**
         * @var int $i
         * @var Account $entity
         */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($value2, $entity->getPlan());
        }
    }

    /**
     * @param $file
     * @param $value1
     * @param $value2
     * @param $value3
     * @dataProvider basicsProvider
     */
    public function testEmail($file, $value1, $value2, $value3)
    {
        /**
         * @var int $i
         * @var Account $entity
         */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($value3, $entity->getEmail());
        }
    }

}
