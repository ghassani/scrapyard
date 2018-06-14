<?php

namespace Miva\Migration\Command\Miva;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Miva\Provisioning\Builder\Builder;
use Miva\Provisioning\Builder\Fragment;
use Symfony\Component\Finder\Finder;

/**
* LegacyImagesToAdditionalImagesXmlCommand
*
* Migrate Legacy Main Images to Additional Main Image Type via XML Provisioning
*
* @author Gassan Idriss <gidriss@mivamerchant.com>
*/
class LegacyImagesToAdditionalImagesXmlCommand extends BaseCommand
{

    /**
    * {@inheritDoc}
    */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('legacy-images-to-additional-xml')
            ->addOption('store-code', null, InputOption::VALUE_REQUIRED, 'The target Miva Merchant Store Code to Generate XML For', null)
            ->addOption('out-file', null, InputOption::VALUE_REQUIRED, 'Optionally specify a filename to export as. Defaults to prostores_customers.xml', 'prostores_customers.xml')
            ->setDescription('Migrate Legacy Main Images to Additional Main Image Type via XML Provisioning')
        ;
    }


    /**
    * {@inheritDoc}
    */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        parent::interact($input, $output);
        
        // get all export directory files
        $finder = new Finder();

        $importDir = new \SplFileInfo(IMPORTS_DIR);
        $exportDir = new \SplFileInfo(EXPORTS_DIR);


        $storeCode = $this->getHelper('dialog')->askAndValidate(
            $output,
            '<question>What is the Miva Merchant Store Code?:</question>',
            function ($storeCode) {
                return $storeCode;
            },
            false,
            ''
        );
        $input->setOption('store-code', $storeCode);

        $outFile = $this->getHelper('dialog')->askAndValidate(
            $output,
            sprintf('<question>What file name to export the XML to? File will be dumped in %s:</question>', $exportDir->getRealPath()),
            function ($fileName) {
                if(!$fileName) {
                  return 'prostores_customers.xml';
                }
                return $fileName;
            },
            false,
            'prostores_customers.xml'
        );

        $input->setOption('out-file', $outFile);
    }

    /**
    * {@inheritDoc}
    */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $storeCode   = $input->getOption('store-code');
        $outFile     = sprintf('%s/%s', EXPORTS_DIR, $input->getOption('out-file'));
        
        $builder = new Builder($storeCode);
        
        $productCount = $this->mivaQuery->getProductCount();
              
        $totalPerLoop = 5000;
        $currentOffset = 0;
        
        $addedImages = array();
        
        while($currentOffset <= $productCount) {
            
            $products = $this->mivaQuery->getProductsOffset($currentOffset, $totalPerLoop);
            
            $currentProduct = 1;
            foreach ($products as $product) {

                if (strlen(trim($product['image']))) {
                    
                   if (preg_match('/\/$/', $product['image'])){
                       continue;
                   }
                   
                   if (preg_match('/^http/', $product['image'])) {
                       $filename = @end(explode('/', $product['image']));
                       
                       if (!file_exists(EXPORTS_DIR.'/images/'.$filename)) {
                           $image = file_get_contents($product['image']);
                           if ($image) {                           
                               $this->writeLn(sprintf('Downloaded Image To: %s', $filename));
                               file_put_contents(EXPORTS_DIR.'/images/'.$filename, $image);
                           }
                       }
                       
                   }
                   
                    $this->writeLn(sprintf('%s/%s - %s - %s', $currentOffset+$currentProduct, $productCount, $product['code'], $product['image']));
                    
                    if(!in_array($product['image'], $addedImages)) {
                        $imageAdd = new Fragment\ImageAdd();
        
                        $imageAdd->setFilePath($product['image']);
            
                        $builder->addFragmentToStore($imageAdd, $storeCode);
                        
                        $addedImages[] = $product['image'];
                    }
                    
        
                    $productImageAdd = new Fragment\ProductImageAdd();
        
                    $productImageAdd->setProductCode($product['code'])
                      ->setFilePath($product['image'])->setTypeCode('main');
          
        
                    $builder->addFragmentToStore($productImageAdd, $storeCode);                    
                }
                
                $currentProduct++;
            }
            
            $currentOffset += $totalPerLoop;
        }
        

        file_put_contents($outFile, $builder->toXml(true));

        if (file_exists($outFile)) {
            $outFile = new \SplFileInfo($outFile);
            $this->writeLn(sprintf('XML Exported To %s', $outFile->getRealPath()));
        } else {
            $this->writeLn('Error Trying To Save Export');
        }  
        
        $this->writeLn('Operation Completed.');
    }
}