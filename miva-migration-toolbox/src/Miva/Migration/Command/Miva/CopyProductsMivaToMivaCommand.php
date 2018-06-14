<?php

namespace Miva\Migration\Command\Miva;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Miva\Migration\Database\MivaQuery;

/**
* CopyProductsMivaToMivaCommand
*
* Copy Products From One MM Database to Another
*
* @author Gassan Idriss <gidriss@mivamerchant.com>
*/
class CopyProductsMivaToMivaCommand extends BaseCommand
{

    /**
    * {@inheritDoc}
    */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('copy-products-miva-to-miva')
            ->setDescription('Copy Products From One MM Database to Another')
            ->addOption('source-connection', null, InputOption::VALUE_OPTIONAL, 'Database Connection Name to use as the source database.', 'miva');

        ;
    }


    /**
    * {@inheritDoc}
    */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        parent::interact($input, $output);
        
        $connections = $this->getServiceContainer()->get('database_manager')->getAvailableConnections();

        $targetConnection = $this->getHelper('dialog')->select(
            $output,
            '<question>What database connection belongs to the Source Miva Merchant installation? (default: miva)</question>',
            array_combine($connections, $connections),
            'miva'            
        );

        $input->setOption('source-connection', $targetConnection);

        $this->sourceDB = $this->getServiceContainer()->get('database_manager')->getConnection($input->getOption('source-connection', 'miva'));
        $this->sourceMivaQuery = new MivaQuery($this->sourceDB);
    }

    /**
    * {@inheritDoc}
    */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        if (!isset($this->sourceMivaQuery)) {
            $this->sourceDB = $this->getServiceContainer()->get('database_manager')->getConnection($input->getOption('source-connection', 'miva'));
            $this->sourceMivaQuery = new MivaQuery($this->sourceDB);
        }
        
        if (!isset($this->mivaQuery)) {
            $this->targetDB = $this->getServiceContainer()->get('database_manager')->getConnection($input->getOption('target-connection', 'miva'));
            $this->mivaQuery = new MivaQuery($this->targetDB);
        }

        $sourceProductCount = $this->sourceMivaQuery->getProductCount();
        $targetProductCount = $this->mivaQuery->getProductCount();

        
        $targetProductIdNextIncrement = $this->mivaQuery->currentIdAbstract('Products', 'id');
        
        $sourceCustomFuelds = $this->sourceMivaQuery->getProductCustomFields();
        $targetCustomFields = $this->mivaQuery->getProductCustomFields();

        $totalPerLoop = 5000;
        $currentOffset = 0;
        
        while($currentOffset <= $sourceProductCount) {
            
            $products = $this->sourceMivaQuery->getProductsOffset($currentOffset, $totalPerLoop);
            
            $currentProduct = 1;
            foreach ($products as $product) {
                
            
            
                $targetProduct = $this->mivaQuery->getProductByCode($product['code']);

                if (!$targetProduct) {
                    $isNewProduct = true;
                    $targetProduct = array();
                    $this->writeLn(sprintf('%s/%s - Creating: %s - %s', $currentOffset+$currentProduct, $sourceProductCount, $product['code'], $product['name']));
                } else {
                    $this->writeLn(sprintf('%s/%s - Updating: %s - %s', $currentOffset+$currentProduct, $sourceProductCount, $product['code'], $product['name']));
                    $isNewProduct = false;
                }

                $_product = array_merge($product, $targetProduct);
                
                if ($isNewProduct) {
                    $_product['id'] = ++$targetProductIdNextIncrement;
                }
                
                try{
    
                    if($isNewProduct){
                        $this->mivaQuery->insertProduct($targetProduct);
                        
                    } else {
                       $this->mivaQuery->updateProduct($targetProduct);
                    }
                } catch(\Exception $e){
                    $this->writeLn($e->getMessage());
                    return static::COMMAND_ERROR;
                }
                
                // now custom fields

                
                
                
                $currentProduct++;
            }
            
            $currentOffset += $totalPerLoop;
        }
        
        var_dump($sourceProductCount);die();
        
        
        
        $this->writeLn('Operation Completed.');
    }
}