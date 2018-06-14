<?php
/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\CmsBundle\Command\Site;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Spliced\Bundle\CmsBundle\Entity\Site;
use Symfony\Component\Console\Input\ArrayInput;

/**
 * DeployCommand
 *
 * Deploys a site into the CMS and bootstraps the skeleton. Should it already exist and the
 * vhost directory has not yet been deployed, then it will be created.
 */
class DeployCommand extends ContainerAwareCommand
{

    const DOMAIN_VALIDATION = '/^[a-zA-Z0-9][a-zA-Z0-9-_]{0,61}[a-zA-Z0-9]{0,1}\.([a-zA-Z]{1,6}|[a-zA-Z0-9-]{1,30}\.[a-zA-Z]{2,3})$/';

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('spliced:cms:deploy-site')
            ->setDescription('Deploys a site into the CMS and bootstraps the skeleton. Should it already exist and the vhost directory has not yet been deployed, then it will be created.')
            ->addOption('domain', null, InputOption::VALUE_OPTIONAL, 'Fully qualified domain or sub-domain name')
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $domain = $this->getDomain($input, $output);
        $input->setOption('domain', $domain);
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /*$config = array(
            'parameters' => array(
                'site_template_paths' => array(
                    '%kernel.root_dir%/Resources/SplicedCmsBundle/views/managed_templates' => 'spliced_cms_template',
                    '%kernel.root_dir%/../skeleton' => 'spliced_cms_skeleton'
                )
            )
        );
        file_put_contents($this->getContainer()->get('kernel')->getRootDir().'/../vhosts/test.yml', \Symfony\Component\Yaml\Yaml::dump($config, true));
        die();*/
        
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $domain = $this->getDomain($input, $output);
        
        if (empty($domain)) {
            $sites = $em->getRepository('SplicedCmsBundle:Site')->createQueryBuilder('s')
                ->select('s')
                ->getQuery()
                ->getResult();
        } else {
            $sites = $em->getRepository('SplicedCmsBundle:Site')->findByDomain($domain);
        }

        if (!count($sites)) {
            $isNew = true;
            $output->writeLn(sprintf('<info>Creating Site %s</info>', $domain));
            $sites[0] = new Site();
            $sites[0]->setDomain($domain)
                ->setIsActive(true);
        }

        foreach ($sites as $site) {
            $this->getContainer()->get('spliced_cms.site_host_manager')->installFilesystem($site);
            $this->getContainer()->get('spliced_cms.site_host_manager')->rebuildConfiguration($site);
            $this->getContainer()->get('spliced_cms.gallery_manager')->sync($site);
            
            $this->getApplication()->find('assets:install')->run(new ArrayInput(array(
                'command' => 'assets:install',
                '--symlink' => true,
                'target' => $site->getWebDir(),
            )), $output);
            
            $this->getApplication()->find('assetic:dump')->run(new ArrayInput(array(
                'command' => 'assetic:dump',
                'write_to' => $site->getWebDir(),
            )), $output);
        }

        $this->getContainer()->get('spliced_cms.site_host_manager')->rebuildGlobalConfiguration();
    }

    /**
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return mixed
     */
    protected function getDomain(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('domain') && preg_match(static::DOMAIN_VALIDATION, $input->getOption('domain'))) {
            return $input->getOption('domain');
        }
        return $this->getHelper('dialog')->askAndValidate(
            $output,
            'What is the fully qualified domain or sub-domain name? i.e. www.example.com, sub.example.com. Enter nothing to re-deploy all sites.',
            function ($domain) {
                if (!empty($domain) && !preg_match(static::DOMAIN_VALIDATION, $domain)) {
                    throw new \RuntimeException(
                        'Please enter a valid domain name'
                    );
                }
                return $domain;
            },
            false,
            null
        );
    }
}