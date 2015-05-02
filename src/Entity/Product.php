<?php

namespace Swader\Diffbot\Entity;

use Swader\Diffbot\Abstracts\Entity;
use Swader\Diffbot\Traits\StandardEntity;

class Product extends Entity
{
    use StandardEntity;

    /** @var Discussion */
    protected $discussion = null;

    public function __construct(array $data)
    {
        parent::__construct($data);
        if (isset($this->data['discussion'])) {
            $this->discussion = new Discussion($this->data['discussion']);
        }
    }

    /**
     * Should always return "product"
     * @return string
     */
    public function getType()
    {
        return $this->data['type'];
    }

    /**
     * Text description, if available, of the product
     * @return string
     */
    public function getText()
    {
        return $this->data['text'];
    }

    /**
     * Regular or original price of the product, if available.
     * If not available, offerPrice is returned instead.
     *
     * @return string
     */
    public function getRegularPrice()
    {
        return (isset($this->data['regularPrice']))
            ? $this->data['regularPrice'] : $this->data['offerPrice'];
    }

    /**
     * regularPrice separated into its constituent parts: amount, symbol, and full text.
     *
     * @return array
     */
    public function getRegularPriceDetails()
    {
        return (isset($this->data['regularPriceDetails']))
            ? $this->data['regularPriceDetails'] : $this->getOfferPriceDetails();
    }

    /**
     * Returns the shipping amount, if available. If not available, returns null.
     * @return string|null
     */
    public function getShippingAmount()
    {
        return (isset($this->data['shippingAmount'])) ? $this->data['shippingAmount'] : null;
    }

    /**
     * Discount or amount saved off the regular price.
     * Recommended to use getSaveAmountDetails instead
     * @see getSaveAmountDetails()
     * @return string|null
     */
    public function getSaveAmount()
    {
        return (isset($this->data['saveAmount'])) ? $this->data['saveAmount'] : null;
    }

    /**
     * saveAmount separated into its constituent parts: amount, symbol, full text,
     * and whether or not it is a percentage value.
     * @return array
     */
    public function getSaveAmountDetails()
    {
        return (isset($this->data['saveAmountDetails'])) ? $this->data['saveAmountDetails'] : [];
    }

    /**
     * Diffbot-determined unique product ID. If upc, isbn, mpn or sku are identified on the page,
     * productId will select from these values in the above order.
     * @return string|null
     */
    public function getProductId()
    {
        return (isset($this->data['productId'])) ? $this->data['productId'] : null;
    }

    /**
     * Universal Product Code (UPC/EAN), if available.
     * @return string|null
     */
    public function getUpc()
    {
        return (isset($this->data['upc'])) ? $this->data['upc'] : null;
    }

    /**
     * Manufacturer's Product Number.
     * @return string|null
     */
    public function getMpn()
    {
        return (isset($this->data['mpn'])) ? $this->data['mpn'] : null;
    }

    /**
     * International Standard Book Number (ISBN), if available.
     * @return string|null
     */
    public function getIsbn()
    {
        return (isset($this->data['isbn'])) ? $this->data['isbn'] : null;
    }

    /**
     * If a specifications table or similar data is available on the product page,
     * individual specifications will be returned in the specs object as name/value pairs.
     * Names will be normalized to lowercase with spaces replaced by underscores, e.g. display_resolution.
     *
     * @return array
     */
    public function getSpecs()
    {
        return (isset($this->data['specs'])) ? $this->data['specs'] : [];
    }

    /**
     * Returns an array of images found in the page's content.
     *
     * Note that this (tries) to ignore content-unrelated images like ads arounds the page, etc.
     * The format of the array will be:
     *
     * [
     *  {
     *      "height": 808,
     *      "diffbotUri": "image|3|-543943368",
     *      "naturalHeight": 808,
     *      "width": 717,
     *      "primary": true,
     *      "naturalWidth": 717,
     *      "url": "https://example.com/image1.png"
     *  },
     *  {
     *      "height": 506,
     *      "diffbotUri": "image|3|-844014913",
     *      "naturalHeight": 506,
     *      "width": 715,
     *      "naturalWidth": 715,
     *      "url": "https://example.com/image1.jpeg"
     *  }
     * ]
     *
     * @return array
     */
    public function getImages()
    {
        return (isset($this->data['images'])) ? $this->data['images'] : [];
    }

    /**
     * Country of origin as identified by UPC/ISBN. Null if not present.
     * @return string|null
     */
    public function getPrefixCode()
    {
        return (isset($this->data['prefixCode'])) ? $this->data['prefixCode'] : null;
    }

    /**
     * If available, two-character ISO country code where the product was produced. Null if not present.
     * @return string|null
     */
    public function getProductOrigin()
    {
        return (isset($this->data['productOrigin'])) ? $this->data['productOrigin'] : null;
    }

    /**
     * If the product is available in a range of prices, the minimum and maximum values will be returned.
     * The lowest price will also be returned as the offerPrice.
     * @return array|null
     */
    public function getPriceRange()
    {
        return (isset($this->data['priceRange'])) ? $this->data['priceRange'] : null;
    }

    /**
     * If the product is available with quantity-based discounts, all identifiable price points will be returned.
     * The lowest price will also be returned as the offerPrice.
     *
     * @return array|null
     */
    public function getQuantityPrices()
    {
        return (isset($this->data['quantityPrices'])) ? $this->data['quantityPrices'] : null;
    }

    /**
     * Checks if the product has been determined available. Null if it wasn't determined.
     * @return bool|null
     */
    public function isAvailable()
    {
        return (isset($this->data['availability'])) ? (bool)$this->data['availability'] : null;
    }

    /**
     * Offer or actual/final price of the product.
     * @return string
     */
    public function getOfferPrice()
    {
        return $this->data['offerPrice'];
    }

    /**
     * Size(s) available, if identified on the page.
     * @return null|array
     */
    public function getSize()
    {
        return (isset($this->data['size'])) ? $this->data['size'] : null;
    }

    /**
     * Returns array of product color options.
     * @return null|array
     */
    public function getColors()
    {
        return (isset($this->data['colors'])) ? $this->data['colors'] : null;
    }

    /**
     * Returns the brand, as determined by Diffbot
     * @return string
     */
    public function getBrand()
    {
        return $this->data['brand'];
    }

    /**
     * Returns Stock Keeping Unit -- store/vendor inventory number or identifier.
     * @return string
     */
    public function getSku()
    {
        return (isset($this->data['sku'])) ? $this->data['sku'] : null;
    }

    /**
     * offerPrice separated into its constituent parts: amount, symbol, and full text.
     * @return array
     */
    public function getOfferPriceDetails()
    {
        return $this->data['offerPriceDetails'];
    }

    /**
     * Returns the Discussion entity - comments of the product
     * @return Discussion
     */
    public function getDiscussion()
    {
        return $this->discussion;
    }

}