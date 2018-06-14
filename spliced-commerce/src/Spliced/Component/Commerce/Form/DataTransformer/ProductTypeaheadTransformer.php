<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Spliced\Component\Commerce\Repository\ProductRepositoryInterface;
use Doctrine\ORM\NoResultException;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ProductTypeaheadTransformer
 *
 * @author Gassan Idriss <ghassani@gmail.com>
 */
class ProductTypeaheadTransformer implements  DataTransformerInterface
{
    /**
     * Constructor
     * 
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }  
    
    /**
     * getProductRepository
     * 
     * @return ProductRepository
     */
     protected function getProductRepository()
     {
         return $this->productRepository;
     }
    /**
     * Transforms an ProductInterface into a Typeahead String
     *
     * @param  ProductInterface|null $product
     * @return string
     */
    public function transform($product)
    {
        
        if (null === $product) {
            return $product;
        }
        
        return sprintf('%s:%s - %s', $product->getId(), $product->getName(), $product->getSku());
    }

    /**
     * Transforms a string to a ProductInterface
     *
     * @param  string $productText
     *
     * @return ProductInterface|null
     *
     * @throws TransformationFailedException if not found
     */
    public function reverseTransform($productText)
    {
        
        if (!$productText) {
            return null;
        }
        
        preg_match('/^[0-9A-Za-z]{1,}:/', $productText, $match, PREG_OFFSET_CAPTURE);
        
        if(isset($match[0][0])){
            $productId = str_replace(':', '', $match[0][0]);
            try{
                $product = $this->getProductRepository()->findOneById($productId);
                return $product;
                
            }catch(NoResultException $e){
                throw new TransformationFailedException(sprintf(
                   'Product Could Not Be Found With ID %s',
                   $productId
                ));
            }
        }
        
        throw new TransformationFailedException(sprintf(
           'Product ID Could Not Be Found Within Typeahead Text',
           $productText
        ));
        
        
    }
}        