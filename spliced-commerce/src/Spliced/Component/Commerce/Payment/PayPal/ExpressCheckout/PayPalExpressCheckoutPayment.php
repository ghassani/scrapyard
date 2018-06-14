<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Payment\PayPal\ExpressCheckout;

use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Spliced\Component\Commerce\Payment\Model\RemotelyProcessedPaymentProvider;
use Spliced\Component\Commerce\Payment\Model\RemotelyProcessedPaymentProviderInterface;
use Spliced\Component\Commerce\Payment\RemotelyProcessedPaymentErrorException;
use Spliced\Component\Commerce\Model\OrderInterface;
use Spliced\Component\PayPal\PayFlow\Client;
use Spliced\Component\Commerce\Helper\Order as OrderHelper;
use Spliced\Component\Commerce\Cart\CartManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use PayPal\CoreComponentTypes\BasicAmountType;
use PayPal\EBLBaseComponents\AddressType;
use PayPal\EBLBaseComponents\BillingAgreementDetailsType;
use PayPal\EBLBaseComponents\PaymentDetailsItemType;
use PayPal\EBLBaseComponents\PaymentDetailsType;
use PayPal\EBLBaseComponents\SetExpressCheckoutRequestDetailsType;
use PayPal\PayPalAPI\SetExpressCheckoutReq;
use PayPal\PayPalAPI\SetExpressCheckoutRequestType;
use PayPal\Service\PayPalAPIInterfaceServiceService;
use Symfony\Component\HttpFoundation\Request;
use PayPal\PayPalAPI\GetExpressCheckoutDetailsReq;
use PayPal\PayPalAPI\GetExpressCheckoutDetailsRequestType;
use PayPal\PayPalAPI\DoExpressCheckoutPaymentReq;
use PayPal\PayPalAPI\DoExpressCheckoutPaymentRequestType;
use PayPal\EBLBaseComponents\DoExpressCheckoutPaymentRequestDetailsType;

/**
 * PayPalExpressCheckoutPayment
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class PayPalExpressCheckoutPayment extends RemotelyProcessedPaymentProvider
{
 
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'paypal_express_checkout';
    }
    
    /**
     * {@inheritDoc}
     */
    public function process(OrderInterface $order)
    {
        throw new \RuntimeException('TODO');
    }
    
    /**
     * {@inheritDoc}
     */
    public function startRemotePayment(OrderInterface $order)
    {

        $paypal = new PayPalAPIInterfaceServiceService(array(
            'acct1.UserName' => $this->getOption($this->getOption('test_mode') ? 'test_username' : 'username'),
            'acct1.Password' => $this->getOption($this->getOption('test_mode') ? 'test_password' : 'password'),
            'acct1.Signature' => $this->getOption($this->getOption('test_mode') ? 'test_signature' : 'signature'),
            'mode' => $this->getOption('test_mode') ? 'sandbox' : 'live',
        ));
        
        $orderSubtotal = $this->getOrderHelper()->getOrderSubTotal($order, false);
        $orderTaxes = $this->getOrderHelper()->getOrderTax($order);
        $orderTotal = $this->getOrderHelper()->getOrderTotal($order);
        $orderShipping = $this->getOrderHelper()->getOrderShipping($order);
        

        $notifyUrl = sprintf('%s%s',
          $this->getConfigurationManager()->get('commerce.store.url_secure'),
          $this->getRouter()->generate('commerce_checkout_remote_update', array(
              'provider' => $this->getName(), 
              'order' => $order->getId()
          ))
        );
        
        $returnUrl = sprintf('%s%s',
            $this->getConfigurationManager()->get('commerce.store.url_secure'),
            $this->getRouter()->generate('commerce_checkout_remote_complete', array(
                'provider' => $this->getName(),
                'order' => $order->getId()
            ))
        );
        
        $cancelUrl = sprintf('%s%s',
            $this->getConfigurationManager()->get('commerce.store.url_secure'),
            $this->getRouter()->generate('commerce_checkout_remote_cancel', array(
                'provider' => $this->getName(),
                'order' => $order->getId()
            ))
        );
        //
        
        $address = new AddressType();
        if($order->hasAlternateShippingAddress()){
            $address->CityName = $order->getShippingCity();
            $address->Name = $order->getShippingName();
            $address->Street1 = $order->getShippingAddress();
            $address->Street2 = $order->getShippingAddress2();
            $address->StateOrProvince = $order->getShippingState();
            $address->PostalCode = $order->getShippingZipcode();
            $address->Country = $order->getShippingCountry();
            $address->Phone = $order->getShippingPhoneNumber();
        } else {
            $address->CityName = $order->getBillingCity();
            $address->Name = $order->getBillingFirstName().' '.$order->getBillingLastName();
            $address->Street1 = $order->getBillingAddress();
            $address->Street2 = $order->getBillingAddress2();
            $address->StateOrProvince = $order->getBillingState();
            $address->PostalCode = $order->getBillingZipcode();
            $address->Country = $order->getBillingCountry();
            $address->Phone = $order->getBillingPhoneNumber();
        }        

        $paymentDetails = new PaymentDetailsType();
        $paymentDetails->ShipToAddress = $address;
        $paymentDetails->PaymentAction = 'Sale';
        $paymentDetails->HandlingTotal = 0.00;
        $paymentDetails->InsuranceTotal = 0.00;
        $paymentDetails->ShippingTotal = number_format($orderShipping, 2, '.', '');
        $paymentDetails->NotifyURL = $notifyUrl;
        $paymentDetails->ItemTotal = new BasicAmountType('USD', number_format($orderSubtotal, 2, '.', ''));
        $paymentDetails->TaxTotal = new BasicAmountType('USD', number_format($orderTaxes, 2, '.', ''));
        $paymentDetails->OrderTotal = new BasicAmountType('USD', number_format($orderTotal, 2, '.', ''));
                
        
        foreach($order->getItems() as $item) {
            $itemDetails = new PaymentDetailsItemType();
            $itemDetails->Name = $item->getName();
            $itemDetails->Amount = new BasicAmountType('USD', number_format($item->getSalePrice(), 2, '.', ''));
            $itemDetails->Quantity = $item->getQuantity();
            $itemDetails->ItemCategory = 'Physical';
            $itemDetails->Tax = new BasicAmountType('USD', number_format($item->getTaxes(), 2, '.', ''));
            
            $paymentDetails->PaymentDetailsItem[] = $itemDetails;
        }

        $requestDetails = new SetExpressCheckoutRequestDetailsType();
        $requestDetails->PaymentDetails[] = $paymentDetails;
        $requestDetails->CancelURL = $cancelUrl;
        $requestDetails->ReturnURL = $returnUrl;
        $requestDetails->NoShipping = 0;
        $requestDetails->AddressOverride = 0; // to avoid address problems
        $requestDetails->ReqConfirmShipping = 0;
        $requestDetails->AllowNote = 1;
        
        $requestType = new SetExpressCheckoutRequestType();
        $requestType->SetExpressCheckoutRequestDetails = $requestDetails;
        
        /*
        // Billing agreement details
        $billingAgreementDetails = new BillingAgreementDetailsType($_REQUEST['billingType']);
        $billingAgreementDetails->BillingAgreementDescription = $_REQUEST['billingAgreementText'];
        $setECReqDetails->BillingAgreementDetails = array($billingAgreementDetails);
        */
        
        $request = new SetExpressCheckoutReq();
        $request->SetExpressCheckoutRequest  = $requestType;
        $response = $paypal->SetExpressCheckout($request);
        
        if($response->Ack =='Success') {
            $redirect = new RedirectResponse(sprintf('https://www.%spaypal.com/webscr?cmd=_express-checkout&token=%s',
                $this->getOption('test_mode') ? 'sandbox.' : '',
                $response->Token
            ));
            return $redirect;
        }
        
        $errors = array();
        foreach($response->Errors as $error) {
            $errors[] = $error->LongMessage;
        }
        // return the error string for display to user
        return implode(', ', $errors);        
    } 
    

    /**
     * {@inheritDoc}
     */
    public function cancelRemotePayment(OrderInterface $order, Request $request)
    {
        return; // nothing to do for this payment method, controller will redirect user to payment page
    }
    
    /**
     * {@inheritDoc}
     */
    public function completeRemotePayment(OrderInterface $order, Request $request)
    {
        $paypal = new PayPalAPIInterfaceServiceService(array(
            'acct1.UserName' => $this->getOption($this->getOption('test_mode') ? 'test_username' : 'username'),
            'acct1.Password' => $this->getOption($this->getOption('test_mode') ? 'test_password' : 'password'),
            'acct1.Signature' => $this->getOption($this->getOption('test_mode') ? 'test_signature' : 'signature'),
            'mode' => $this->getOption('test_mode') ? 'sandbox' : 'live',
        ));
        
        if(!$request->query->has('token')){
            throw new RemotelyProcessedPaymentErrorException('Error retrieving Token');
        }
        
        // first look up the payer info
        $getExpressCheckoutRequestType = new GetExpressCheckoutDetailsRequestType($request->query->get('token'));
        $getExpressCheckoutRequest = new GetExpressCheckoutDetailsReq();
        $getExpressCheckoutRequest->GetExpressCheckoutDetailsRequest = $getExpressCheckoutRequestType;
        $getExpressCheckoutResponse = $paypal->GetExpressCheckoutDetails($getExpressCheckoutRequest);

        // now lookup the payment info to complete the transaction
        $doExpressCheckoutPaymentDetailRequestType = new DoExpressCheckoutPaymentRequestDetailsType();
        $doExpressCheckoutPaymentDetailRequestType->PayerID = $getExpressCheckoutResponse->GetExpressCheckoutDetailsResponseDetails->PayerInfo->PayerID;
        $doExpressCheckoutPaymentDetailRequestType->Token = $getExpressCheckoutResponse->GetExpressCheckoutDetailsResponseDetails->Token;
        $doExpressCheckoutPaymentDetailRequestType->PaymentAction = 'Sale';
        
        $paymentDetails= new PaymentDetailsType();
        $paymentDetails->OrderTotal = number_format($this->getOrderHelper()->getOrderTotal($order),2,'.','');
        
        $doExpressCheckoutPaymentDetailRequestType->PaymentDetails[0] = $paymentDetails;
       
        $doExpressCheckoutPaymentRequestType = new DoExpressCheckoutPaymentRequestType();
        $doExpressCheckoutPaymentRequestType->DoExpressCheckoutPaymentRequestDetails = $doExpressCheckoutPaymentDetailRequestType;

        $doExpressCheckoutPaymentRequest = new DoExpressCheckoutPaymentReq();
        $doExpressCheckoutPaymentRequest->DoExpressCheckoutPaymentRequest = $doExpressCheckoutPaymentRequestType;
        
        $doExpressCheckoutResponse = $paypal->DoExpressCheckoutPayment($doExpressCheckoutPaymentRequest);
        
        // add a payment memo for the payment being received, for each payment record
        foreach($doExpressCheckoutResponse->DoExpressCheckoutPaymentResponseDetails->PaymentInfo as $paymentInfo){
            
            $newStatus = $this->getOption('checkout_complete_status');
            
            if(strtolower($paymentInfo->PaymentStatus) == 'pending'){
                $newStatus = OrderInterface::STATUS_PENDING;
            }
            
            $paymentMemo = $this->getConfigurationManager()->createEntity(
                    ConfigurationManager::OBJECT_CLASS_TAG_ORDER_PAYMENT_MEMO
            )->setCreatedBy('paypal')
            ->setMemo('PayPal Payment Received '.$paymentInfo->PaymentStatus)
            ->setMerchantTransactionId($paymentInfo->TransactionID)
            ->setAmountPaid($paymentInfo->GrossAmount->value)
            ->setMemoData(json_decode(json_encode($paymentInfo),true) /* to an array */)
            ->setPreviousStatus($order->getPayment()->getPaymentStatus())
            ->setChangedStatus($newStatus)
            ->setPayment($order->getPayment());
            
            $order->getPayment()->setPaymentStatus($newStatus);
            
            $order->getPayment()->addMemo($paymentMemo);
        }
        
        $order->setOrderStatus($newStatus != 'pending' ? $this->getOption('checkout_complete_status') : $newStatus);
    }
    
    /**
     * {@inheritDoc}
     */
    public function updateRemotePayment(OrderInterface $order, Request $request)
    {
        throw new \RuntimeException('TODO');
    }
     
    /**
     * {@inheritDoc}
     */
    public function getConfigPrefix()
    {
        return 'commerce.payment.paypal.express_checkout';
    }
    

    /**
     * {@inheritDoc}
     */
    public function getRequiredConfigurationFields()
    {
        $i = 0;
        return array_merge(parent::getRequiredConfigurationFields(),array(
            'username' => array(
                'type' => 'string',
                'value' => isset($this->defaultConfigurationValues['username']) ? $this->defaultConfigurationValues['username'] : null,
                'label' => 'API Username',
                'help' => '',
                'group' => 'Payment',
                'child_group' => 'PayPal Express',
                'position' => ++$i,
                'required' => false,
            ),
            'password' => array(
                'type' => 'encrypted',
                'value' => isset($this->defaultConfigurationValues['password']) ? $this->defaultConfigurationValues['password'] : null,
                'label' => 'API Password',
                'help' => '',
                'group' => 'Payment',
                'child_group' => 'PayPal Express',
                'position' => ++$i,
                'required' => false,
             ),   
             'signature' => array(
                 'type' => 'encrypted',
                 'value' => isset($this->defaultConfigurationValues['signature']) ? $this->defaultConfigurationValues['signature'] : null,
                 'label' => 'API Signature',
                 'help' => '',
                 'group' => 'Payment',
                 'child_group' => 'PayPal Express',
                 'position' => ++$i,
                 'required' => false,
             ),
             'test_username' => array(
                 'type' => 'string',
                 'value' => isset($this->defaultConfigurationValues['test_username']) ? $this->defaultConfigurationValues['test_username'] : null,
                 'label' => 'API Test Username',
                 'help' => '',
                 'group' => 'Payment',
                 'child_group' => 'PayPal Express',
                 'position' => ++$i,
                 'required' => false,
             ),
             'test_password' => array(
                 'type' => 'encrypted',
                 'value' => isset($this->defaultConfigurationValues['test_password']) ? $this->defaultConfigurationValues['test_password'] : null,
                 'label' => 'API Test Password',
                 'help' => '',
                 'group' => 'Payment',
                 'child_group' => 'PayPal Express',
                 'position' => ++$i,
                 'required' => false,
             ),
             'test_signature' => array(
                 'type' => 'encrypted',
                 'value' => isset($this->defaultConfigurationValues['test_signature']) ? $this->defaultConfigurationValues['test_signature'] : null,
                 'label' => 'API Test Signature',
                 'help' => '',
                 'group' => 'Payment',
                 'child_group' => 'PayPal Express',
                 'position' => ++$i,
                 'required' => false,
             ),
             'test_mode' => array(
                 'type' => 'boolean',
                 'value' => isset($this->defaultConfigurationValues['test_mode']) ? $this->defaultConfigurationValues['test_mode'] : null,
                 'label' => 'Test Mode',
                 'help' => '',
                 'group' => 'Payment',
                 'child_group' => 'PayPal Express',
                 'position' => ++$i,
                 'required' => false,
             ),
             // standard remotely processed payment fields
             'enabled' => array(
                 'type' => 'boolean',
                 'value' => isset($this->defaultConfigurationValues['enabled']) ? $this->defaultConfigurationValues['enabled'] : null,
                 'label' => 'Enabled',
                 'help' => '',
                 'group' => 'Payment',
                 'child_group' => 'PayPal Express',
                 'position' => ++$i,
                 'required' => false,
             ),
             'position' => array(
                 'type' => 'integer',
                 'value' => isset($this->defaultConfigurationValues['position']) ? $this->defaultConfigurationValues['position'] : null,
                 'label' => 'Position',
                 'help' => '',
                 'group' => 'Payment',
                 'child_group' => 'PayPal Express',
                 'position' => ++$i,
                 'required' => false,
             ),
             'checkout_complete_status' => array(
                 'type' => 'status',
                 'value' => isset($this->defaultConfigurationValues['checkout_complete_status']) ? $this->defaultConfigurationValues['checkout_complete_status'] : OrderInterface::STATUS_PROCESSING,
                 'label' => 'Order Status After Checkout Complete',
                 'help' => '',
                 'group' => 'Payment',
                 'child_group' => 'PayPal Express',
                 'position' => ++$i,
                 'required' => false,
             ),
             'label' => array(
                 'type' => 'string',
                 'value' => isset($this->defaultConfigurationValues['label']) ? $this->defaultConfigurationValues['label'] : null,
                 'label' => 'Label',
                 'help' => '',
                 'group' => 'Payment',
                 'child_group' => 'PayPal Express',
                 'position' => ++$i,
                 'required' => false,
             ),
             'label2' => array(
                 'type' => 'string',
                 'value' => isset($this->defaultConfigurationValues['label2']) ? $this->defaultConfigurationValues['label2'] : null,
                 'label' => 'Label 2',
                 'help' => '',
                 'group' => 'Payment',
                 'child_group' => 'PayPal Express',
                 'position' => ++$i,
                 'required' => false,
             ),
             'description' => array(
                 'type' => 'html',
                 'value' => isset($this->defaultConfigurationValues['description']) ? $this->defaultConfigurationValues['description'] : null,
                 'label' => 'Description',
                 'help' => '',
                 'group' => 'Payment',
                 'child_group' => 'PayPal Express',
                 'position' => ++$i,
                 'required' => false,
             ),
             'continue_to_button_image' => array(
                 'type' => 'url',
                 'value' => isset($this->defaultConfigurationValues['continue_to_button_image']) ? $this->defaultConfigurationValues['continue_to_button_image'] : null,
                 'label' => 'Continue To Image',
                 'help' => '',
                 'group' => 'Payment',
                 'child_group' => 'PayPal Express',
                 'position' => ++$i,
                 'required' => false,
             ),
             'continue_to_button_text' => array(
                 'type' => 'string',
                 'value' => isset($this->defaultConfigurationValues['continue_to_button_text']) ? $this->defaultConfigurationValues['continue_to_button_text'] : null,
                 'label' => 'Continue To Image',
                 'help' => '',
                 'group' => 'Payment',
                 'child_group' => 'PayPal Express',
                 'position' => ++$i,
                 'required' => false,
             ),
        ));
    }
}
