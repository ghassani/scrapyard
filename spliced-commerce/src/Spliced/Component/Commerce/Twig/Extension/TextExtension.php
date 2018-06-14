<?php
/**
 * @TODO Multiple Curriencies
 */
namespace Spliced\Component\Commerce\Twig\Extension;

class TextExtension extends \Twig_Extension
{

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            'stripTags' => new \Twig_Filter_Method($this, 'strip_tags'),
            'slugify' => new \Twig_Filter_Method($this, 'slugify'),
        );
    }
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'phone_format' => new \Twig_Function_Method($this, 'phone_format'),
        );
    }

    /**
     * 
     */
    public function slugify($text)
    {
      // replace non letter or digits by -
      $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
    
      // trim
      $text = trim($text, '-');
    
      // transliterate
      $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    
      // lowercase
      $text = strtolower($text);
    
      // remove unwanted characters
      $text = preg_replace('~[^-\w]+~', '', $text);
    
      if (empty($text))
      {
        return null;
      }
    
      return $text;
    }

    /**
     * phone_format
     */
    public function phone_format($number)
    {
        return preg_replace('~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~', '($1) $2-$3', $number); 
    }
    
    /**
     * strip_tags
     * @param string $text
     */
    public function strip_tags($text)
    {
        return strip_tags($text);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'commerce_text';
    }

}
