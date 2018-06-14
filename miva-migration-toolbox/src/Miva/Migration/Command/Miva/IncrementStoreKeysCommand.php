<?php

namespace Miva\Migration\Command\Miva;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
* IncrementStoreKeysCommand
*
* Set Proper Store Keys
*
* @author Gassan Idriss <gidriss@mivamerchant.com>
*/
class IncrementStoreKeysCommand extends BaseCommand
{

    /**
    * {@inheritDoc}
    */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('increment-miva-store-keys')
            ->setDescription('Increments All Store Keys To Avoid Future Clashes')
            ->addOption('live', null, InputOption::VALUE_OPTIONAL, 'Live Run', false)
        ;
    }


    /**
    * {@inheritDoc}
    */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        parent::interact($input, $output);

        $isLiveRun = $this->getHelper('dialog')->askConfirmation($output,'<question>Apply Changes To Database? (default: yes) </question>', true);

        $input->setOption('live', $isLiveRun);
    }

    /**
    * {@inheritDoc}
    */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $tableMap = array(
            'Affiliates' => array(
                'table' => 'Affiliates', 
                'pk' => 'id'
            ),
            'AffiliateEarnings' => array(
                'table' => 'AffiliateEarnings', 
                'pk' => 'id'
            ),
            'AffiliatePayouts' => array(
                'table' => 'AffiliatePayouts', 
                'pk' => 'id'
            ),
            'Categories' => array(
                'table' => 'Categories', 
                'pk' => 'id'
            ),
            'Products' => array(
                'table' => 'Products', 
                'pk' => 'id'
            ),
            'ProductVariants' => array(
                'table' => 'ProductVariants', 
                'pk' => 'variant_id'
            ),
            'Images' => array(
                'table' => 'Images', 
                'pk' => 'id'
            ),
            'ProductImages' => array(
                'table' => 'ProductImages', 
                'pk' => 'id'
            ),
            'ImageTypes' => array(
                'table' => 'ImageTypes', 
                'pk' => 'id'
            ),
            'GeneratedImages' => array(
                'table' => 'GeneratedImages', 
                'pk' => 'id'
            ),
            'ImageTypes' => array(
                'table' => 'ImageTypes', 
                'pk' => 'id'
            ),
            'Imports' => array(
                'table' => 'Imports', 
                'pk' => 'id'
            ),
            'ItemExtensions' => array(
                'table' => 'ItemExtensions', 
                'pk' => 'id'
            ),
            'Attributes' => array(
                'table' => 'Attributes', 
                'pk' => 'id'
            ),
            'Options' => array(
                'table' => 'Options', 
                'pk' => 'id'
            ),
            'Baskets' => array(
                'table' => 'Baskets', 
                'pk' => 'basket_id'
            ),
            'BasketItems' => array(
                'table' => 'BasketItems', 
                'pk' => 'line_id',
                'table_lookup' => 'OrderItems',
                'lookup_pk' => 'line_id',
            ),
            'BasketCharges' => array(
                'table' => 'BasketCharges', 
                'pk' => 'charge_id',
                'table_lookup' => 'OrderCharges',
                'lookup_pk' => 'charge_id',
            ),
            'Orders' => array(
                'table' => 'Orders', 
                'pk' => 'id'
            ),
            'OrderShipments' => array(
                'table' => 'OrderShipments', 
                'pk' => 'id'
            ),
            'OrderShipmentLabels' => array(
                'table' => 'OrderShipmentLabels', 
                'pk' => 'id'
            ),
            'OrderReturns' => array(
                'table' => 'OrderReturns', 
                'pk' => 'id'
            ),
            'OrderPayments' => array(
                'table' => 'OrderPayments', 
                'pk' => 'id'
            ),
            'Pages' => array(
                'table' => 'Pages', 
                'pk' => 'id'
            ),
            'PriceGroups' => array(
                'table' => 'PriceGroups', 
                'pk' => 'id'
            ),
            'Batches' => array(
                'table' => 'Batches', 
                'pk' => 'id'
            ),
            'ShipmentBatches' => array(
                'table' => 'ShipmentBatches', 
                'pk' => 'id'
            ),
            'SKINs' => array(
                'table' => 'SKIN', 
                'pk' => 'id'
            ),
            'Groups' => array(
                'table' => 'Groups', 
                'pk' => 'id'
            ),
            'TemplateBatchReports' => array(
                'table' => 'TemplateBatchReports', 
                'pk' => 'id'
            ),
            'TemplateOrderEmails' => array(
                'table' => 'TemplateOrderEmails', 
                'pk' => 'id'
            ),
            'METANames' => array(
                'table' => 'METANames', 
                'pk' => 'id'
            ),
            'ManagedTemplates' => array(
                'table' => 'ManagedTemplates', 
                'pk' => 'id'
            ),
            'ManagedTemplateVersions' => array(
                'table' => 'ManagedTemplateVersions', 
                'pk' => 'id'
            ),
            'Items' => array(
                'table' => 'Items', 
                'pk' => 'id'
            ),
            'Encryption' => array(
                'table' => 'Encryption', 
                'pk' => 'id'
            ),
            'Customers' => array(
                'table' => 'Customers', 
                'pk' => 'id'
            ),
            'Boxes' => array(
                'table' => 'Boxes', 
                'pk' => 'id'
            ),
            'AvailabilityGroups' => array(
                'table' => 'AvailabilityGroups', 
                'pk' => 'id'
            ),
            'AttributeTemplateAttrs' => array(
                'table' => 'AttributeTemplateAttrs', 
                'pk' => 'id'
            ),
            'AttributeTemplateOptions' => array(
                'table' => 'AttributeTemplateOptions', 
                'pk' => 'id'
            ),
            'AttributeTemplates' => array(
                'table' => 'AttributeTemplates', 
                'pk' => 'id'
            ),
            'CSSUI_Buttons' => array(
                'table' => 'CSSUI_Buttons', 
                'pk' => 'id'
            ),
            'CSSUI_CSS' => array(
                'table' => 'CSSUI_CSS', 
                'pk' => 'id'
            ),
            'CFM_Groups' => array(
                'table' => 'CFM_Groups', 
                'pk' => 'id'
            ),
            'CFM_FieldOptions' => array(
                'table' => 'CFM_FieldOptions', 
                'pk' => 'id'
            ),  
            'CFM_Fields' => array(
                'table' => 'CFM_CustFields', 
                'pk' => 'id',
                'table_lookup' => array('CFM_ProdFields','CFM_OrderFields','CFM_CatFields'),
                'lookup_pk' => 'id',
            ),   
            'Reports' => array(
                'table' => 'Reports', 
                'pk' => 'id'
            ),    

            'ReadyTheme_Images' => array(
                'table' => 'ReadyTheme_Images', 
                'pk' => 'id'
            ),  

            'ReadyTheme_Banners' => array(
                'table' => 'ReadyTheme_Banners', 
                'pk' => 'id'
            ),  

            'ReadyTheme_ContentSections' => array(
                'table' => 'ReadyTheme_ContentSections', 
                'pk' => 'id'
            ),  

            'ReadyTheme_NavigationSets' => array(
                'table' => 'ReadyTheme_NavigationSets', 
                'pk' => 'id'
            ),  

            'ReadyTheme_NavigationItems' => array(
                'table' => 'ReadyTheme_NavigationItems', 
                'pk' => 'id'
            ),  

            'ReadyTheme_NavigationItems_DisplayOrder' => array(
                'table' => 'ReadyTheme_NavigationItems_DisplayOrder', 
                'pk' => 'id'
            ),  

            'ReadyTheme_ProductListings' => array(
                'table' => 'ReadyTheme_ProductListings', 
                'pk' => 'id'
            ),  
            
            'Coupons' => array(
                'table' => 'Coupons', 
                'pk' => 'id'
            ), 
            
        );

        $storeKeys = $this->mivaQuery->getStoreKeys();
        $invalidKeys = array();
        foreach($storeKeys as $storeKey => $currentMaxValue){

            if(isset($tableMap[$storeKey])){
                $settings = $tableMap[$storeKey];

                try {
                    $shouldBeIncrement = $this->mivaQuery->currentIdAbstract($settings['table'], $settings['pk']);
                } catch(\Exception $e){
                    $this->writeLn($e->getMessage());
                    continue;
                }
                
                if(!$shouldBeIncrement){
                    $shouldBeIncrement = 0;
                }

                if(isset($settings['table_lookup'])){
                    if(!is_array($settings['table_lookup'])){
                        $settings['table_lookup'] = array($settings['table_lookup']);
                    }

                    foreach($settings['table_lookup'] as $tableToLookup){
                        $shouldBeIncrementlookup = $this->mivaQuery->currentIdAbstract($tableToLookup, $settings['lookup_pk']);
                        
                        if(!$shouldBeIncrementlookup){
                            $shouldBeIncrementlookup = 0;
                        }

                        if($shouldBeIncrementlookup > $shouldBeIncrement){
                            $shouldBeIncrement = $shouldBeIncrementlookup;
                        }
                    }
                    
                }

                if($shouldBeIncrement != $currentMaxValue && $shouldBeIncrement > $currentMaxValue) {
                    array_push($invalidKeys, $storeKey);
                }

                if(false === $input->getOption('live')) {
                    $this->writeLn(sprintf('Store Key %s From %s To %s', $storeKey, $currentMaxValue, $shouldBeIncrement));
                } else {
                    $this->mivaQuery->updateStoreKey($storeKey, $shouldBeIncrement);
                    $this->writeLn(sprintf('Updating Store Key %s From %s To %s', $storeKey, $currentMaxValue, $shouldBeIncrement));
                } 
            } else {
                $this->writeLn(sprintf('No Settings Found For %s', $storeKey));
            }
        }

        if(count($invalidKeys)) {
            echo PHP_EOL;
            foreach($invalidKeys as $invalidKey) {
                $this->writeLn(sprintf('Invalid Key For: %s', $invalidKey));
            }
        }

        $this->writeLn('Operation Completed.');
    }
}