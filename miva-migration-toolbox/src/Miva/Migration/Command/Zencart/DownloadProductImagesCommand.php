<?php

namespace Miva\Migration\Command\Zencart;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
* DownloadProductImagesCommand
*
* Download All Product Images from Zen Server to Local Machine
*
* @author Gassan Idriss <gidriss@mivamerchant.com>
*/
class DownloadProductImagesCommand extends BaseCommand
{



    /**
    * {@inheritDoc}
    */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('download-zencart-product-images')
            ->setDescription('Download All Product Images from Zen Server to Local Machine')
            ->addOption('base-url', null, InputOption::VALUE_OPTIONAL, 'The Base URL To Pull URLS From.', 'http://www.mydomain.com/images')
            ->addOption('export-dir', null, InputOption::VALUE_OPTIONAL, 'The Path To Save Images To.', EXPORTS_DIR.'/images')

        ;
    }
    
    /**
    * {@inheritDoc}
    */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        parent::interact($input, $output);

        $baseUrl = $this->getHelper('dialog')->askAndValidate(
            $output,
            'Base URL To Pull Images From: (note: zen cart default is /images on the root of the domain)',
            function ($url) {
                if (empty($url)) {
                    throw new \RuntimeException(
                        'Please enter a URL'
                    );
                }
                return $url;
            },
            false
        );

        $exportDir = $this->getHelper('dialog')->askAndValidate(
            $output,
            sprintf('Where to save the images on the local filesystem?: (default: %s)', EXPORTS_DIR.'/images'),
            function ($path) {
                if (!is_dir($path)) {
                    throw new \RuntimeException(
                        'Path Does Not Exist'
                    );
                }
                return $path;
            },
            false,
            EXPORTS_DIR.'/images'
        );

        $input->setOption('base-url', $baseUrl);
        $input->setOption('export-dir', $exportDir);
    }

    /**
    * {@inheritDoc}
    */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $baseUrl = $input->getOption('base-url', null);
        $exportDir = $input->getOption('export-dir', null);

        $products = $this->zenQuery->getProducts();

        foreach($products as $product) {
            $imageUrl = $baseUrl.'/'.$product['image'];
            $output->writeLn('Downloading '.$imageUrl);
            $image = file_get_contents($imageUrl);
            file_put_contents($exportDir.'/'.$product['image'], $image);
        }

        $this->writeLn('Operation Completed.');
    }
}