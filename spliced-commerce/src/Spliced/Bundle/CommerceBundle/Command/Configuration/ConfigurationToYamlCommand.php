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
use Symfony\Component\Yaml;

/**
 * ConfigurationToYamlCommand
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ConfigurationToYamlCommand extends BaseCommand
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
        ->setName('smc:configuration-yaml-dump')
        ->setDescription('Dumps all configuration to specified file in YAML format');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dm = $this->getContainer()->get('commerce.document_manager');
        
        $config = $dm->getRepository('SplicedCommerceBundle:Configuration')
          ->createQueryBuilder()
          ->hydrate(false)
          ->sort('key', 'asc')
          ->getQuery()
          ->execute()
          ->toArray();
        
        $return = array();
        foreach($config as $c){
            foreach($c as $k => $v) {
                if(in_array($k, array('_id','createdAt','updatedAt'))){
                    unset($c[$k]);
                }
            }
            if(!preg_match('/^commerce\.(payment|shipping)\./', $c['key'])){
                $return[$c['key']] = $c;
            }
            
        }
        file_put_contents('/var/www/vhosts/someguyswireless.com/commerce/src/Spliced/Component/Commerce/Resources/config/core_configuration.yml', Yaml\Yaml::dump($return));
        
    }
}