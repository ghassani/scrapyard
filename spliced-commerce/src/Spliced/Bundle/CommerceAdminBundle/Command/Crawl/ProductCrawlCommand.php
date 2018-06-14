<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceAdminBundle\Command\Crawl;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\BrowserKit\Client as BaseClient;
use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Http\Exception\CurlException;
use Spliced\Bundle\CommerceAdminBundle\Command\BaseCommand;
use Symfony\Component\BrowserKit\Request;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Http\Exception\ServerErrorResponseException;

/**
 * ProductCrawlCommand
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductCrawlCommand extends BaseCommand
{
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

        $this->em = $this->getContainer()->get('doctrine')->getEntityManager();
        $this->client = new GuzzleClient('', array(GuzzleClient::DISABLE_REDIRECTS => true));
        
        $this->client->setUserAgent('SplicedCommerceBot/1.0');
    }

    protected function configure()
    {
        parent::configure();

        $this
        ->setName('smc:product-crawl')
        ->setDescription('Crawls products looking for errors');
        
    }

    
    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
      
        $products = $this->getContainer()->get('commerce.product.repository')
          ->createQueryBuilder('product')
          ->select('product, route')
          ->leftJoin('product.route', 'route')
          ->orderBy('product.sku', 'ASC')
          ->getQuery()
          ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
          ->getResult();
        
        $this->writeLine(sprintf('Loaded %s Products for Crawl Testing', count($products)));
        
        if(!is_dir($this->kernel->getRootDir().'/../../reports')){
            mkdir($this->kernel->getRootDir().'/../../reports');
        }
        
        $fh = fopen($this->kernel->getRootDir().'/../../reports/product-crawl-report-'.time().'.csv', 'a+');
        
        foreach($products as $product){
            $currentRow = array(
                'id' => $product->getId(),
                'name' => $product->getName(),
                'sku' => $product->getSku(),
                'has_route' => 'Unchecked',
                'view_ok' => 'Unchecked',
                'add_ok' => 'Unchecked',
            );
            
            
            if($product->getRoute()){
                $currentRow['has_route'] = 'Yes';
                $viewUrl = sprintf('%s%s', 
                    $this->getContainer()->get('commerce.configuration')->get('commerce.store.url'),
                    $product->getRoute()->getRequestPath()
                );
            } else {
                $currentRow['has_route'] = 'No';
                 $viewUrl = sprintf('%s/%s', 
                 $this->getContainer()->get('commerce.configuration')->get('commerce.store.url'),
                 $product->getUrlSlug()
                );
            }

            if($currentRow['has_route'] == 'Yes'){
                $viewRequest = $this->client->createRequest(
                    'GET',
                    $viewUrl,
                    array('Referer' => $this->getContainer()->get('commerce.configuration')->get('commerce.store.url')),
                    null
                );
                
                // Let BrowserKit handle redirects
                $responseOk = false;
                try {
                    $response = $viewRequest->send();
                    
                    $responseOk = true;
                } catch (CurlException $e) {            
                    
                    $response = $e->getResponse();
                    $errorMessage = $e->getMessage();
                    
                } catch (BadResponseException $e) {
                    $response = $e->getResponse();
                    $errorMessage = $e->getMessage();
                    
                } catch(ClientErrorResponseException $e){
                    $response = $e->getResponse();
                    $errorMessage = $e->getMessage();
                    
                } catch(ServerErrorResponseException $e){
                    $response = $e->getResponse();
                    $errorMessage = $e->getMessage();
                }
                
                if($responseOk === true){
                    if($response->getStatusCode() == 200){
                        $currentRow['view_ok'] = 'Yes';
                    } else {
                        $currentRow['view_ok'] = 'No - '.$response->getStatusCode();
                    }
                    
                    
                    if($currentRow['view_ok'] == 'Yes'){
                        $addToCartRequest = $this->client->createRequest(
                            'POST',
                            $this->getContainer()->get('commerce.configuration')->get('commerce.store.url').'/cart/add',
                            array('X-Requested-With' => 'XMLHttpRequest'),
                            array(
                                'id' => $product->getId(),
                                'quantity' => 1, 
                            )
                        );
                        
                        $responseOk = false;
                        try {
                            $response = $addToCartRequest->send();
                        
                            $responseOk = true;
                        } catch (CurlException $e) {
                            $response = $e->getResponse();
                            $errorMessage = $e->getMessage();
                        } catch (BadResponseException $e) {
                            $response = $e->getResponse();
                            $errorMessage = $e->getMessage();
                        } catch(ServerErrorResponseException $e){
                            $response = $e->getResponse();
                            $errorMessage = $e->getMessage();
                        } catch(ClientErrorResponseException $e){
                            $response = $e->getResponse();
                            $errorMessage = $e->getMessage();
                        }
                        
                        //die($response->getBody(true));
                        
                        if($responseOk === true){
                            if($response->getStatusCode() == 200){
                                $currentRow['add_ok'] = 'Yes';
                            } else {
                                $currentRow['add_ok'] = 'No - '.$response->getStatusCode();
                            }
                        } else {
                            $currentRow['add_error'] = $errorMessage;
                        }
                    }
                } else {
                    $currentRow['view_error'] = $errorMessage;
                }
            }
            
            $this->writeLine(sprintf('Product %s - Route: %s View: %s Add: %s  | %s %s',
                $product->getSku(),    
                $currentRow['has_route'],
                $currentRow['view_ok'],
                $currentRow['add_ok'],
                isset($currentRow['view_error']) ? $currentRow['view_error'] : null,
                isset($currentRow['add_error']) ? $currentRow['add_error'] : null
            ));
            
            fputcsv($fh, $currentRow);
        }
    }
}
