<?php

namespace Miva\Migration\Command\Zencart;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
* CategoriesMigrateCommand
*
* Migrates Category Data From Zen Cart Database to Miva Merchant Database
*
* @author Gassan Idriss <gidriss@mivamerchant.com>
*/
class CategoriesMigrateCommand extends BaseCommand
{

    /**
    * {@inheritDoc}
    */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('migrate-zencart-categories')
            ->setDescription('Migrates Category Data From Zen Cart Database to Miva Merchant Database')
            ->addOption('include-inactive', null, InputOption::VALUE_OPTIONAL, 'Include inactive categories in Zen. Defaults to false.', false)
            ->addOption('base-target-image-path', null, InputOption::VALUE_OPTIONAL, 'Path Prefix for Images to Import into Full Sized Image and Additional Images.', 'graphics/00000001/')
            ->addOption('category-descriptions-to-hdft', null, InputOption::VALUE_OPTIONAL, 'Move Category Descriptions to Category HDFT', false)
        ;
    }
    
    /**
    * {@inheritDoc}
    */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        parent::interact($input, $output);
        
        $importInactive = $this->getHelper('dialog')->askConfirmation($output,'<question>Import Inactive Zen Categories? (default: no) </question>',false);

        $baseTargetImagePath = $this->getHelper('dialog')->askAndValidate(
            $output,
            '<question>What is the base path (from mm5 root) to the category images folder? (default: graphics/00000001/) </question>',
            function ($path) {
                return $path;
            },
            false,
            'graphics/00000001/'
        );

        $descriptionsToHdft = $this->getHelper('dialog')->askConfirmation($output,'<question>Do you want to move category descriptions to category hdft header field? (default: no) </question>',false);

        if($descriptionsToHdft) {
            $baseTargetImagePath = $this->getHelper('dialog')->askAndValidate(
                $output,
                '<info>Moving category descriptions to HDFT header will require that Miva Merchant compiles the templates on the filesystem. The easiest way to do this is to export the Category HDFT to flat file once this operation is complete and appending any new characters to the fields and re-importing. Please acknowledge that you know that if this is not done the store category pages may not function correctly by entering yes.</info>',
                function ($response) {
                    if (empty($response) || strtoupper($response[0]) != 'Y') {
                        throw new \RuntimeException(
                            'Please acknowledge that you know that if this is not done the store category pages may not function correctly by entering yes.'
                        );
                    }
                    return $response;
                },
                false,
                ''
            );

            $descriptionsToHdft = $this->getHelper('dialog')->askConfirmation($output,'',false);

        }

        $input->setOption('include-inactive', $importInactive);
        $input->setOption('base-target-image-path', $baseTargetImagePath); 
        $input->setOption('category-descriptions-to-hdft', $descriptionsToHdft);  
    }

    /**
    * {@inheritDoc}
    */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $categories = $this->zenQuery->getCategories();
        $baseTargetImagePath = $input->getOption('base-target-image-path');

        $metaKeywordName = $this->mivaQuery->getMetaName('keywords');
        $metaDescriptionName = $this->mivaQuery->getMetaName('description');

        $existingCodes = array();
        foreach($categories as $k => $category) {

            $categoryCode = codeify($category['name']);
 
            if(in_array($categoryCode, $existingCodes)){
                $categoryCode = $categoryCode . '-' . $category['id'];
            }
            
            if(!$input->getOption('include-inactive') && !$category['active']){
                continue;
            }

            $targetCategory = $this->mivaQuery->getCategoryById($category['id']);

            if($targetCategory) {
                $isNew = false;
                $this->writeLn(sprintf('Updating Category With ID %s And Code %s', $category['id'], $categoryCode));
            } else {
                $isNew = true;
                $targetCategory = array();
                $this->writeLn(sprintf('Creating Category With ID %s And Code %s', $category['id'], $categoryCode));
            }

            $targetCategory = array_merge($targetCategory, array(
                'id' => $category['id'], 
                'parent_id' => $category['parent_id'], 
                'agrpcount' => 0, 
                'disp_order' => $category['disp_order'] ? $category['disp_order'] : $k, 
                'page_id' => 0, 
                'code' => $categoryCode, 
                'name' => $this->toUTF8($category['name']),  
                'active' => $category['active'], 
            ));


            try{

                if($isNew){
                    $this->mivaQuery->insertCategory($targetCategory);
                } else {
                   $this->mivaQuery->updateCategory($targetCategory);
                }
            } catch(\Exception $e){
                $this->writeLn($e->getMessage());
                return static::COMMAND_ERROR;
            }

            // meta data
            $categoryKeywordsMeta = $this->mivaQuery->getCategoryMeta($category['id'], $metaKeywordName['id']);

            if(!$categoryKeywordsMeta){
                $this->mivaQuery->insertCategoryMeta($category['id'], $metaKeywordName['id'], $this->toUTF8($category['meta_keywords']));
                $this->writeLn(sprintf('Updated Category Meta Keywords With ID %s', $category['id']));
            } else {
                $this->mivaQuery->updateCategoryMeta($category['id'], $metaKeywordName['id'], $this->toUTF8($category['meta_keywords']));
                $this->writeLn(sprintf('Updated Category Meta Keywords With ID %s', $category['id']));
            }

            $categoryDescriptionMeta = $this->mivaQuery->getCategoryMeta($category['id'], $metaDescriptionName['id']);
            if(!$categoryDescriptionMeta){
                $this->mivaQuery->insertCategoryMeta($category['id'], $metaDescriptionName['id'], $this->toUTF8($category['meta_description']));
                $this->writeLn(sprintf('Updated Category Meta Description With ID %s', $category['id']));
            } else {
                $this->mivaQuery->updateCategoryMeta($category['id'], $metaDescriptionName['id'], $this->toUTF8($category['meta_description']));
                $this->writeLn(sprintf('Updated Category Meta Description With ID %s', $category['id']));
            }

            // image
            $categoryTitleImage = $this->mivaQuery->getCategoryTitleImage($category['id']);
            
            if(!$categoryTitleImage){
                $categoryTitleImage = array(
                    'cat_id' => $category['id'],
                    'image' => !empty($category['image']) ? $baseTargetImagePath.$category['image'] : null,
                );
                $this->mivaQuery->insertCategoryTitleImage($categoryTitleImage);
                $this->writeLn(sprintf('Updated Category Title Image With ID %s', $category['id']));
            } else {
                $categoryTitleImage['image'] = !empty($category['image']) ? $baseTargetImagePath.$category['image'] : null;
                $this->mivaQuery->updateCategoryTitleImage($categoryTitleImage);
                $this->writeLn(sprintf('Updated Category Title Image With ID %s', $category['id']));
            }

            // category descriptions as headers 
            // NOTE: these will need to be re-compiled within miva 
            if ($input->getOption('category-descriptions-to-hdft')) {
                $existingCategoryHdft = $this->mivaQuery->getCategoryHdft($category['id']);

                if(!$existingCategoryHdft) {
                    $headerManagedTemplateId = $this->mivaQuery->nextIdAbstract('ManagedTemplates', 'id', true);
                    $headerManagedTemplateVersionId = $this->mivaQuery->nextIdAbstract('ManagedTemplateVersions', 'id', true);

                    $categoryHdftHeaderManagedTemplateVersion = array(
                        'id'        => $headerManagedTemplateVersionId,
                        'templ_id'  => $headerManagedTemplateId,
                        'immutable' => 1,
                        'dtstamp'   => time(),
                        'notes'     => 'Original - Zen Import',
                        'source'    => $category['description'],
                        'settings'  => ''
                    );

                    $categoryHdftHeaderManagedTemplate = array(
                        'id'          => $headerManagedTemplateId,
                        'current_id'  => $headerManagedTemplateVersionId,
                        'filename'    => 'c'.$category['id'].'-header.mvc'
                    );

                    $footerManagedTemplateId = $headerManagedTemplateId + 1;
                    $footerManagedTemplateVersionId = $headerManagedTemplateVersionId + 1;

                    $categoryHdftFooterManagedTemplateVersion = array(
                        'id'        => $footerManagedTemplateVersionId,
                        'templ_id'  => $footerManagedTemplateId,
                        'immutable' => 1,
                        'dtstamp'   => time(),
                        'notes'     => 'Original - Zen Import',
                        'source'    => '&nbsp;',
                        'settings'  => ''
                    ); 

                    $categoryHdftFooterManagedTemplate = array(
                        'id'         => $footerManagedTemplateId,
                        'current_id' => $footerManagedTemplateVersionId,
                        'filename'   => 'c'.$category['id'].'-footer.mvc'
                    );

                    $existingCategoryHdft = array(
                        'cat_id'    => $category['id'],
                        'header_id' => $headerManagedTemplateId,
                        'footer_id' => $footerManagedTemplateId
                    );

                    $this->mivaQuery->insertManagedTemplate($categoryHdftHeaderManagedTemplate);
                    $this->mivaQuery->insertManagedTemplate($categoryHdftFooterManagedTemplate);
                    $this->mivaQuery->insertManagedTemplateVersion($categoryHdftHeaderManagedTemplateVersion);
                    $this->mivaQuery->insertManagedTemplateVersion($categoryHdftFooterManagedTemplateVersion);
                    $this->mivaQuery->insertCategoryHdft($existingCategoryHdft);

                }
            }

            array_push($existingCodes, $categoryCode);
        }

        $this->writeLn('Operation Completed.');
    }
}