<?php

namespace Swader\Diffbot\Traits;

/**
 * Trait StandardApi
 * @package Swader\Diffbot\Traits
 * @property $fieldSettings array
 */
trait StandardApi {


    /**
     * Makes the API call return the links field, common to all standard API types
     * @param $bool
     * @return $this
     */
    public function setLinks($bool)
    {
        $this->fieldSettings['links'] = (bool)$bool;
        return $this;
    }

    /**
     * Makes the API call return the meta field, common to all standard API types
     * @param $bool
     * @return $this
     */
    public function setMeta($bool)
    {
        $this->fieldSettings['meta'] = (bool)$bool;
        return $this;
    }

    /**
     * Makes the API call return the querystring field, common to all standard API types
     * @param $bool
     * @return $this
     */
    public function setQuerystring($bool)
    {
        $this->fieldSettings['querystring'] = (bool)$bool;
        return $this;
    }

    /**
     * Makes the API call return the breadcrumb field, common to all standard API types
     * @param $bool
     * @return $this
     */
    public function setBreadcrumb($bool)
    {
        $this->fieldSettings['breadcrumb'] = (bool)$bool;
        return $this;
    }
}