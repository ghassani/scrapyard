<?php

namespace Miva\Migration\Command\Zencart;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
* OrdersMigrateCommand
*
* Migrates Order Data From Zen Cart Database to Miva Merchant Database
*
* @author Gassan Idriss <gidriss@mivamerchant.com>
*/
class OrdersMigrateCommand extends BaseCommand
{

    const DEFAULT_LIMIT = 5000;
    const DEFAULT_OFFSET = 0;

    /**
    * {@inheritDoc}
    */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('migrate-zencart-orders')
            ->setDescription('Migrates Order Data From Zen Cart Database to Miva Merchant Database')
            ->addOption('limit', null, InputOption::VALUE_OPTIONAL, 'Orders Per Loop', static::DEFAULT_LIMIT)
            ->addOption('offset', null, InputOption::VALUE_OPTIONAL, 'Operation Starting Offset', static::DEFAULT_OFFSET)
            ->addOption('payment-module-code', null, InputOption::VALUE_OPTIONAL, 'Module to Assign Order Payments To', 'none')
            ->addOption('shipment-module-code', null, InputOption::VALUE_OPTIONAL, 'Module to Assign Order Shipments To', 'none')
            ->addOption('order-status', null, InputOption::VALUE_OPTIONAL, 'Order Status To Be Set To', 200)
            ->addOption('skip-existing', null, InputOption::VALUE_OPTIONAL, 'Skip Existing Orders', false)
        ;
    }
    
    /**
    * {@inheritDoc}
    */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        parent::interact($input, $output);

        $limit = $this->getHelper('dialog')->askAndValidate(
            $output,
            'How many orders to process per loop? (default: 5000)',
            function ($limit) {
                if (!is_numeric($limit) || $limit <= 0) {
                    throw new \RuntimeException(
                        'Limit must be greater than 0 and must be a valid number.'
                    );
                }

                return $limit;
            },
            false,
            static::DEFAULT_LIMIT
        );

        $offset = $this->getHelper('dialog')->askAndValidate(
            $output,
            'Default offset starts at 0. Enter a different starting offset: (default: 0)',
            function ($offset) {
                if (!is_numeric($offset)) {
                    throw new \RuntimeException(
                        'Offset must be greater than 0 and must be a valid number.'
                    );
                }

                return $offset;
            },
            false,
            static::DEFAULT_OFFSET
        );

        $paymentModules = $this->mivaQuery->getStoreModulesByFeature('payment');
        $paymentModuleChoices = array('none' => 'None');
        foreach($paymentModules as $pm){
            $paymentModuleChoices[$pm['code']] = $pm['name'].' '.($pm['active'] == 1 ? 'Active' : 'Inactive');
        }

        $paymentModule = $this->getHelper('dialog')->select(
            $output,
            '<question>What payment module to use as order payments? (default: none)</question>',
            $paymentModuleChoices,
            'none'            
        );

        $shippingModules = $this->mivaQuery->getStoreModulesByFeature('shipping');
        $shippingModuleChoices = array('none' => 'None');
        foreach($shippingModules as $sm){
            $shippingModuleChoices[$sm['code']] = $sm['name'].' '.($sm['active'] == 1 ? 'Active' : 'Inactive');
        }

        $shippingModule = $this->getHelper('dialog')->select(
            $output,
            '<question>What shipping module to use as order shipments? (default: none)</question>',
            $shippingModuleChoices,
            'none'            
        );

        $orderStatus = $this->getHelper('dialog')->select(
            $output,
            '<question>What status should imported orders be set to? (default: 200 - Shipped)</question>',
            array(
                0 => 'Pending',
                100 => 'Processing',
                200 => 'Shipped',
                300 => 'Cancelled',
                500 => 'RMA Issued',
                600 => 'Returned',
            ),
            200           
        );

        $skipExisting = $this->getHelper('dialog')->askConfirmation(
            $output,
            '<question>Skip existing orders?</question>',
            false
        );

        $input->setOption('limit', $limit);
        $input->setOption('offset', $offset);
        $input->setOption('payment-module-code', $paymentModule);
        $input->setOption('shipment-module-code', $shippingModule);
        $input->setOption('order-status', $orderStatus);
        $input->setOption('skip-existing', $skipExisting);
    }

    /**
    * {@inheritDoc}
    */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // get total orders
        $orderCount = $this->zenQuery->getOrderCount();
       
        $offset = $input->getOption('offset', static::DEFAULT_LIMIT);
        $limit = $input->getOption('limit', static::DEFAULT_OFFSET);        
        $paymentModuleCode = $input->getOption('payment-module-code', 'none');
        $shippingModuleCode = $input->getOption('shipment-module-code', 'none');
        $orderStatus = $input->getOption('order-status', 200);
        $skipExisting = $input->getOption('skip-existing', false);

        $paymentModule = $this->mivaQuery->getModuleByCode($paymentModuleCode);
        $shippingModule = $this->mivaQuery->getModuleByCode($shippingModuleCode);

        if($paymentModuleCode != 'none' && !$paymentModule) {
            $output->writeLn(sprintf('Module %s Not Found. Specify a valid module or select none.', $paymentModuleCode));
            return static::COMMAND_ERROR;
        }

        if($shippingModuleCode != 'none' && !$shippingModule) {
            $output->writeLn(sprintf('Module %s Not Found. Specify a valid module or select none.', $shippingModuleCode));
            return static::COMMAND_ERROR;
        }

        $products = $this->mivaQuery->getProducts(); // load our products we have already established in miva
        
        // loop over the set offset and limit to not run out of memory
        for($_offset = $offset; $_offset <= $orderCount; $_offset += $limit) {

             $orders = $this->zenQuery->getOrders($limit, $_offset);

             foreach($orders as $order) {
                
                $targetOrder = $this->mivaQuery->getOrder($order['id']);

                if($targetOrder){
                    if($skipExisting) {
                        $this->writeLn(sprintf('Skipping Existing Order %s', $order['id']));
                        continue;
                    }

                    $isNew = false;
                    $this->writeLn(sprintf('Updating Order %s', $order['id']));
                } else {
                    $isNew = true;
                    $targetOrder = array();
                    $this->writeLn(sprintf('Creating Order %s', $order['id']));
                }

                $orderDate = new \DateTime($order['order_date']);
                $shipName = split_name($order['ship_name']);
                $billName = split_name($order['bill_name']);

                $customer = $this->mivaQuery->getCustomer($order['cust_id']);

                if(!$customer){
                    $this->writeLn(sprintf('Customer ID %s Not Found', $order['cust_id']));
                }

                $targetOrder = array_merge($targetOrder, array(
                    'id'          => $order['id'],
                    'batch_id'    => 0,
                    'status'      => $orderStatus,
                    'pay_status'  => 0,
                    'stk_status'  => 0,
                    'dt_instock'  => null,
                    'orderdate'   => $orderDate->getTimestamp(),
                    'cust_id'     => $customer ? $customer['id'] : 0,
                    'ship_fname'  => $this->toUTF8($shipName['fname']),
                    'ship_lname'  => $this->toUTF8($shipName['lname']),
                    'ship_email'  => $this->toUTF8($order['ship_email']),
                    'ship_comp'   => $this->toUTF8($order['ship_comp']),
                    'ship_phone'  => $customer ? $this->toUTF8($customer['ship_phone']) : null,
                    'ship_fax'    => $customer ? $this->toUTF8($customer['ship_fax']) : null,
                    'ship_addr'   => $this->toUTF8($order['ship_addr']),
                    'ship_addr2'  => $this->toUTF8($order['ship_addr2']),
                    'ship_city'   => $this->toUTF8($order['ship_city']),
                    'ship_state'  => $this->toUTF8($order['ship_state']),
                    'ship_zip'    => $this->toUTF8($order['ship_zip']),
                    'ship_cntry'  => $this->toUTF8($order['ship_cntry']),
                    'bill_fname'  => $this->toUTF8($billName['fname']),
                    'bill_lname'  => $this->toUTF8($billName['lname']),
                    'bill_email'  => $this->toUTF8($order['bill_email']),
                    'bill_comp'   => $this->toUTF8($order['bill_comp']),
                    'bill_phone'  => $customer ? $this->toUTF8($customer['bill_phone']) : null,
                    'bill_fax'    => $customer ? $this->toUTF8($customer['bill_fax']) : null,
                    'bill_addr'   => $this->toUTF8($order['bill_addr']),
                    'bill_addr2'  => $this->toUTF8($order['bill_addr2']),
                    'bill_city'   => $this->toUTF8($order['bill_city']),
                    'bill_state'  => $this->toUTF8($order['bill_state']),
                    'bill_zip'    => $this->toUTF8($order['bill_zip']),
                    'bill_cntry'  => $this->toUTF8($order['bill_cntry']),
                    'ship_id'     => isset($shippingModule['id'])  ? $shippingModule['id'] : 0,
                    'ship_data'   => null,
                    'total'       => $order['order_total'],
                    'total_auth'  => $order['order_total'],
                    'total_capt'  => $order['order_total'],
                    'total_rfnd'  => 0,
                    'net_capt'    => $order['order_total'],
                    'pend_count'  => 0,
                    'bord_count'  => 0,
                ));

                
                try{

                    if($isNew){
                        $this->mivaQuery->insertOrder($targetOrder);
                    } else {
                       $this->mivaQuery->updateOrder($targetOrder);
                    }

                } catch(\Exception $e){
                    $this->writeLn($e->getMessage());
                    return static::COMMAND_ERROR;
                }

                // order shipment record
                $targetShipment = $this->mivaQuery->getOrderShipment($order['id']);

                if($targetShipment){
                    $isNew = false;
                    $this->writeLn(sprintf('Updating Shipment %s For Order %s', $order['id'], $order['id']));
                } else {
                    $isNew = true;
                    $targetShipment = array();
                    $this->writeLn(sprintf('Creating Shipment %s For Order %s', $order['id'], $order['id']));
                }
                
                $targetShipment = array_merge($targetShipment, array(
                    'id'         => $order['id'],
                    'batch_id'   => 0,
                    'code'       => $order['id'].'-1',
                    'order_id'   => $order['id'],
                    'status'     => 200,
                    'labelcount' => 0,
                    'ship_date'  => $orderDate->getTimestamp(),
                    'tracknum'   => null,
                    'tracktype'  => null,
                    'weight'     => 0.000,
                    'cost'       => 0.00,
                ));

                try{

                    if($isNew){
                        $this->mivaQuery->insertOrderShipment($targetShipment);
                    } else {
                       $this->mivaQuery->updateOrderShipment($targetShipment);
                    }

                } catch(\Exception $e){
                    $this->writeLn($e->getMessage());
                    return static::COMMAND_ERROR;
                }

                // order payment record
                $targetPayment = $this->mivaQuery->getOrderPayment($order['id']);

                if($targetPayment){
                    $isNew = false;
                    $this->writeLn(sprintf('Updating Payment %s For Order %s', $order['id'], $order['id']));
                } else {
                    $isNew = true;
                    $targetPayment = array();
                    $this->writeLn(sprintf('Creating Payment %s For Order %s', $order['id'], $order['id']));
                }
                
                $targetPayment = array_merge($targetPayment, array(
                    'id' => $order['id'],
                    'parent_id' => 0,
                    'order_id' => $order['id'],
                    'type' => 2,
                    'refnum' => $order['id'],
                    'amount' => $order['order_total'],
                    'available' => 0.00,
                    'dtstamp' => $orderDate->getTimestamp(),
                    'expires' => null,
                    'pay_id' => isset($paymentModule['id'])  ? $paymentModule['id'] : 0,
                    'pay_data' => miva_structure_serialize(array(
                        'cc_type' => $order['cc_type'],
                        //'cc_owner' => $order['cc_owner'],
                        'cc_number' => $order['cc_number'],
                        'cc_expires' => $order['cc_expires'],
                        'cc_cvv' => $order['cc_cvv'],
                    )),
                    'pay_secid' => 0,
                    'pay_seckey' => null,
                    'pay_secdat' => null,
                ));

                try{

                    if($isNew){
                        $this->mivaQuery->insertOrderPayment($targetPayment);
                    } else {
                       $this->mivaQuery->updateOrderPayment($targetPayment);
                    }

                } catch(\Exception $e){
                    $this->writeLn($e->getMessage());
                    return static::COMMAND_ERROR;
                }

                // handle items
                $items = $this->zenQuery->getOrderItems($order['id']);
                foreach($items as $item){
                    $product = isset($products[$item['product_id']]) ? $products[$item['product_id']] : false;

                    $targetItem = $this->mivaQuery->getOrderItem($item['line_id']);

                    if($targetItem){
                        $isNew = false;
                        $this->writeLn(sprintf('Updating Order Item %s For Order %s', $item['line_id'], $order['id']));
                    } else {
                        $isNew = true;
                        $targetItem = array();
                        $this->writeLn(sprintf('Creating Order Item %s For Order %s', $item['line_id'], $order['id']));
                    }

                    $targetItem = array_merge($targetItem, array(
                        'order_id'   => $order['id'],
                        'line_id'    => $item['line_id'],
                        'status'     => $orderStatus,
                        'shpmnt_id'  => $order['id'],
                        'rma_id'     => 0,
                        'dt_instock' => null,
                        'product_id' => $product ? $product['id'] : $item['product_id'],
                        'code'       => $product ? $product['code'] : $item['product_id'],
                        'name'       => $item['name'],
                        'price'      => $item['price'],
                        'weight'     => $product ? $product['weight'] : 0,
                        'taxable'    => $item['tax'] ? 1 : 0,
                        'upsold'     => 0,
                        'quantity'   => $item['quantity'],
                    ));

                    try{

                        if($isNew){
                            $this->mivaQuery->insertOrderItem($targetItem);
                        } else {
                           $this->mivaQuery->updateOrderItem($targetItem);
                        }
                    } catch(\Exception $e){
                        $this->writeLn($e->getMessage());
                        return static::COMMAND_ERROR;
                    }
                } 

                // handle charges
                $charges = $this->zenQuery->getOrderCharges($order['id']);
                foreach($charges as $charge){
                    // skip totals and subtotals
                    if(in_array($charge['type'], array('ot_total','ot_subtotal'))){
                        continue;
                    }

                    $targetCharge = $this->mivaQuery->getOrderCharge($charge['charge_id']);

                    if($targetCharge){
                        $isNew = false;
                        $this->writeLn(sprintf('Updating Order Charge %s For Order %s', $charge['charge_id'], $order['id']));
                    } else {
                        $isNew = true;
                        $targetCharge = array();
                        $this->writeLn(sprintf('Creating Order Charge %s For Order %s', $charge['charge_id'], $order['id']));
                    }

                    $chargeType = null;
                    if($charge['type'] == 'ot_coupon'){
                        $chargeType = 'COUPON';
                    } else if($charge['type'] == 'ot_shipping'){
                        $chargeType = 'SHIPPING';
                    } else if($charge['type'] == 'ot_table_discounts'){
                        $chargeType = 'TABLEDISCOUNT';
                    } else if($charge['type'] == 'ot_manufacturer_discount'){
                        $chargeType = 'MANUFACTURERDISCOUNT';
                    } else if($charge['type'] == 'ot_tax'){
                        $chargeType = 'TAX';  
                    }

                    $targetCharge = array_merge($targetCharge, array(
                        'order_id' => $order['id'],
                        'charge_id' => $charge['charge_id'],
                        'module_id' => isset($shippingModule['id'])  ? $shippingModule['id'] : 0,
                        'type' => $chargeType,
                        'descrip' => $charge['descrip'],
                        'amount' => $charge['amount'],
                        'disp_amt' => $charge['amount'],
                        'tax_exempt'=> 0,
                    ));

                    try{

                        if($isNew){
                            $this->mivaQuery->insertOrderCharge($targetCharge);
                        } else {
                           $this->mivaQuery->updateOrderCharge($targetCharge);
                        }
                    } catch(\Exception $e){
                        $this->writeLn($e->getMessage());
                        return static::COMMAND_ERROR;
                    }
                }   

            }
        }        

        $this->writeLn('Operation Completed.');

        return static::COMMAND_SUCCESS;
    }
}