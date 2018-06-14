<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceAdminBundle\Command\User;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\NoResultException;
use Spliced\Bundle\CommerceAdminBundle\Command\BaseCommand;

/**
 * CreateAdminUserCommand
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CreateAdminUserCommand extends BaseCommand
{
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);
    }

    protected function configure()
    {
        parent::configure();

        $this
        ->setName('smc:create-admin-user')
        ->setDescription('Create an Administrative User');
        
    }

    
    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
      $username = readline('Enter Username: ');
      $password = readline('Enter Password: ');
      
      $manager = $this->getContainer()->get('commerce.user.manager');
      
      $user = $manager->loadUserByUsername($username);
      
      if (!$user) {
      	$user_class = $manager->getClass();
      	
      	if(!class_exists($user_class)){
      		$this->writeLine(sprintf('Error. Class %s Does Not Exist', $user_class));
      	}
      	
      	$user = new $user_class();
      	
      	$user->setUsername($username)
      	  ->setPlainPassword($password)
      	  ->setEnabled(true);
      	
      	$manager->create($user);
      	
      	$this->writeLine(sprintf('User %s Created With Password %s', $username, $password));
      } else {
      	$this->writeLine('User Already Exists');
      }
      
            
    }
}
