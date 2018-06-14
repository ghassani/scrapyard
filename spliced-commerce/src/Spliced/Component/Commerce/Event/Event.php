<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Event;

use Symfony\Component\EventDispatcher\Event as BaseEvent;

/**
 * Event
 * 
 * This class wraps all event tags as constant members
 * for easy access through out the application.
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
abstract class Event extends BaseEvent
{

    /**
     * Cart Event Constants
     */
    const EVENT_CART_ITEM_ADD      = 'commerce.cart.add_item';
    const EVENT_CART_ITEM_REMOVE = 'commerce.cart.remove_item';
    const EVENT_CART_UPDATE      = 'commerce.cart.update';
    
    /**
     * Checkout Event Constants
     */
    const EVENT_CHECKOUT_START         = 'commerce.checkout.start';
    const EVENT_REMOTELY_PROCESSED_CHECKOUT_START         = 'commerce.remotely_processed.checkout.start';
    const EVENT_CHECKOUT_COMPLETE     = 'commerce.checkout.complete';
    const EVENT_CHECKOUT_SUCCESS     = 'commerce.checkout.success';
    const EVENT_CHECKOUT_COMPLETE_ON_DECLINE     = 'commerce.checkout.complete_declined';
    const EVENT_CHECKOUT_PROCESS_STEP = 'commerce.checkout.process_step';
    const EVENT_CHECKOUT_MOVE_STEP = 'commerce.checkout.move_step';
    const EVENT_CHECKOUT_PAYMENT_METHOD_CHANGE = 'commerce.checkout.payment.change';
    const EVENT_CHECKOUT_FINALIZE = 'commerce.checkout.finalize';
    const EVENT_CHECKOUT_PAYMENT_ERROR = 'commerce.checkout.payment.error';
    
    const EVENT_CONFIGURATION_UPDATE = 'commerce.configuration.update';
    
    /**
     * Product Event Constants
     */
    const EVENT_PRODUCT_VIEW     = 'commerce.product.view';
    const EVENT_PRODUCT_SAVE     = 'commerce.product.save';
    const EVENT_PRODUCT_UPDATE   = 'commerce.product.update';
    const EVENT_PRODUCT_DELETE   = 'commerce.product.delete';
    const EVENT_PRODUCT_CATEGORY_ADD      = 'commerce.product.category.add';
    const EVENT_PRODUCT_CATEGORY_DELETE   = 'commerce.product.category.delete';
    const EVENT_PRODUCT_IMAGE_ADD      = 'commerce.product.image.add';
    const EVENT_PRODUCT_IMAGE_DELETE   = 'commerce.product.image.delete';
        
    /**
     * Product Attribute Option Event Constants
     */
    const EVENT_PRODUCT_ATTRIBUTE_OPTION_SAVE     = 'commerce.product.attribute_option.save';
    const EVENT_PRODUCT_ATTRIBUTE_OPTION_UPDATE   = 'commerce.product.attribute_option.update';
    const EVENT_PRODUCT_ATTRIBUTE_OPTION_DELETE   = 'commerce.product.attribute_option.delete';
    const EVENT_PRODUCT_ATTRIBUTE_OPTION_VALUE_DELETE   = 'commerce.product.attribute_option.value.delete';
    
    /**
     * Product Specification Option Event Constants
     */
    const EVENT_PRODUCT_SPECIFICATION_OPTION_SAVE     = 'commerce.product.specification_option.save';
    const EVENT_PRODUCT_SPECIFICATION_OPTION_UPDATE   = 'commerce.product.specification_option.update';
    const EVENT_PRODUCT_SPECIFICATION_OPTION_DELETE   = 'commerce.product.specification_option.delete';
    const EVENT_PRODUCT_SPECIFICATION_OPTION_VALUE_DELETE   = 'commerce.product.specification_option.value.delete';
    
    /**
     * Product Specification Option Event Constants
     */
    const EVENT_CUSTOMER_REGISTRATION_START      = 'commerce.security.registration_start';
    const EVENT_CUSTOMER_REGISTRATION_COMPLETE   = 'commerce.security.registration_complete';
    
    const EVENT_SECURITY_LOGIN_FAILURE                      = 'commerce.security.login_failure';
    const EVENT_SECURITY_LOGIN_FORCE_PASSWORD_RESET_REQUEST = 'commerce.security.login.force_password_reset_request';
    const EVENT_SECURITY_LOGIN_FORCE_PASSWORD_RESET         = 'commerce.security.login.force_password_reset';
    const EVENT_SECURITY_LOGIN_PASSWORD_RESET_REQUEST       = 'commerce.security.login.password_reset_request';
    const EVENT_SECURITY_LOGIN_PASSWORD_RESET               = 'commerce.security.login.password_reset';
    
    const EVENT_SECURITY_GOOGLE_LOGIN                = 'commerce.security.login.google';
    const EVENT_SECURITY_GOOGLE_LOGIN_CREATE_USER    = 'commerce.security.login.google.create_user';
    
    const EVENT_SECURITY_FACEBOOK_LOGIN                = 'commerce.security.login.facebook';
    const EVENT_SECURITY_FACEBOOK_LOGIN_CREATE_USER    = 'commerce.security.login.facebook.create_user';

    const EVENT_SECURITY_TWITTER_LOGIN                = 'commerce.security.login.twitter';
    const EVENT_SECURITY_TWITTER_LOGIN_CREATE_USER    = 'commerce.security.login.twitter.create_user';

    const EVENT_SECURITY_PAYPAL_LOGIN                = 'commerce.security.login.paypal';
    const EVENT_SECURITY_PAYPAL_LOGIN_CREATE_USER    = 'commerce.security.login.paypal.create_user';

    /** RESERVED */
    const EVENT_SECURITY_YAHOO_LOGIN                = 'commerce.security.login.paypal';
    const EVENT_SECURITY_YAHOO_LOGIN_CREATE_USER    = 'commerce.security.login.paypal.create_user';
    
    const EVENT_SEARCH = 'commerce.search';
    
    const EVENT_CONTACT_SUBMISION = 'commerce.contact.submission';

    const EVENT_ORDER_UPDATE          = 'commerce.order.update';
    const EVENT_ORDER_SHIPPED         = 'commerce.order.shipped';
    const EVENT_ORDER_SHIPMENT_UPDATE = 'commerce.order.shipment.update';
    const EVENT_ORDER_PAYMENT_UPDATE  = 'commerce.order.payment.update';
    const EVENT_ORDER_RETURN          = 'commerce.order.return';
    const EVENT_ORDER_CANCEL          = 'commerce.order.cancel';
    const EVENT_ORDER_INCOMPLETE_FOLLOWUP = 'commerce.order.incomplete_followup';
    
    const EVENT_CATEGORY_SAVE = 'commerce.category.save';
    const EVENT_CATEGORY_UPDATE = 'commerce.category.update';
    const EVENT_CATEGORY_DELETE = 'commerce.category.delete';
    
    const EVENT_CONTENT_PAGE_SAVE     = 'commerce.content_page.save';
    const EVENT_CONTENT_PAGE_UPDATE   = 'commerce.content_page.update';
    const EVENT_CONTENT_PAGE_DELETE   = 'commerce.content_page.delete';
}
