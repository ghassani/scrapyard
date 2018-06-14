<?php
/*
* This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceBundle\Command\Configuration;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Spliced\Bundle\CommerceBundle\Command\BaseCommand;
use Spliced\Bundle\CommerceBundle\Entity;

/**
 * ConfigurableServicesUpdateCommand
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ConfigurableServicesUpdateCommand extends BaseCommand
{
    /**
     * {@inheritDoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

    }

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
        ->setName('smc:configuration-update')
        ->setDescription('Updates Configuration Database');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $serviceIds = $this->getContainer()->get('commerce.configuration')->getConfigurableServiceIds();
        
        foreach($serviceIds as $serviceId){
            $this->writeLine(sprintf('Processing: %s', $serviceId));
            $this->getContainer()->get('commerce.configuration')->processConfigurableService($this->getContainer()->get($serviceId));
            $this->writeLine(sprintf('Complete: %s', $serviceId));
        }
        
    }
}