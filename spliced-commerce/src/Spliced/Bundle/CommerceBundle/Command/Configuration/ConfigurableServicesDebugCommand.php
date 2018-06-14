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
 * ConfigurableServicesDebugCommand
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ConfigurableServicesDebugCommand extends BaseCommand
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
        ->setName('smc:configuration-debug-services')
        ->setDescription('Lists all configurable services and if they exist or not');
    } 

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        foreach($this->getContainer()->get('commerce.configuration')->getConfigurableServiceIds() as $serviceId){
            
            $this->writeLine(sprintf('Service: %s | %s', $serviceId, $this->getContainer()->has($serviceId) ? 'Ok' : 'Doesnt Exist'));
        }
    }
}