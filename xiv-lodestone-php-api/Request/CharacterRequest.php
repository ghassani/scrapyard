<?php

namespace Spliced\Lodestone\Request;

use Spliced\Lodestone\Model\Character;
use Spliced\Lodestone\Model\CharacterAttributes;
use Spliced\Lodestone\Model\CharacterClasses;
use Spliced\Lodestone\Model\CharacterClass;
use Spliced\Lodestone\Model\CharacterEquipment;
use Spliced\Lodestone\Model\CharacterEquipmentItem;
use Spliced\Lodestone\Model\ClassJob;
use Spliced\Lodestone\Model\Minion;
use Spliced\Lodestone\Model\Mount;
use Spliced\Lodestone\Response\CharacterResponse;
use GuzzleHttp\Message\RequestInterface as GuzzleRequestInterface;
use Spliced\Lodestone\DomCrawler\Crawler;


class CharacterRequest implements RequestInterface
{
    protected $uri = '/character/%s';

    protected $method = 'GET';

    protected $id;

    public function __construct($id)
    {
        $this->id = $id;

        if (!$this->getId()) {
            throw new \UnexpectedValueException(sprintf('CharacterRequest requires at least a character id.'));
        }
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getUri()
    {
        return sprintf($this->uri, $this->getId());
    }

    public function buildRequest(GuzzleRequestInterface $request)
    {


    }

    public function buildResponse(Crawler $responseCrawler)
    {
        $response = new CharacterResponse();

        $classJobs = ClassJob::getClassJobs();
        $classJobThumbnailMap = ClassJob::$thumbnailMap;


        $id = $responseCrawler->filter('div.area_footer.player_name_txt h2 a')->extract('href', true);
        $name = $responseCrawler->filter('div.area_footer.player_name_txt h2 a')->extract('_text', true);
        $world = $responseCrawler->filter('div.area_footer.player_name_txt h2 span')->extract('_text', true);
        $raceTribeGender = explode('/', $responseCrawler->filter('.chara_profile_title')->extract('_text', true));
        $currentClassLevel = $responseCrawler->filter('#param_class_info_area .level')->first()->extract('_text', true);
        $currentClassThumbnail = $responseCrawler->filter('.ic_class_wh24_box img')->first()->extract('src', true);
        $currentClassTextThumbnail = $responseCrawler->filter('.ic_class_wh24_box img:last-child')->last()->extract('src', true);
        $currentClassImage = $responseCrawler->filter('.bg_chara_264 img')->extract('src', true);

        $attributes = $responseCrawler->filter('ul.param_list_attributes li, ul.param_list li, ul.param_list_elemental li');
        $mounts = $responseCrawler->filter('.minion_box')->first()->filter('a');
        $minions = $responseCrawler->filter('.minion_box')->last()->filter('a');
        $classRows = $responseCrawler->filter('table.class_list tr');
        $characterWorldData = $responseCrawler->filter('.chara_profile_box_info .txt_name')->extract('_text');
        $characterItemData = $responseCrawler->filter('#chara_img_area .ic_reflection_box');

        $nameday = $responseCrawler->filter('.chara_profile_box_info .txt .chara_profile_table dd')->first()->extract('_text', true);
        $guardian = $responseCrawler->filter('.chara_profile_box_info .txt .chara_profile_table dd')->last()->extract('_text', true);

        // lets build the response now
        $currentClass = null;
        foreach ($classJobThumbnailMap as $constantValue => $imageHash) {
            if (stripos($currentClassThumbnail, $imageHash) !== false) {
                $currentClass = $constantValue;
            }
        }

        $character = new Character();

        $character->setId(preg_replace('/[^0-9]/i', '', $id))
            ->setName($name)
            ->setWorld(str_replace(array('(',')'), null, $world))
            ->setCurrentClassLevel(preg_replace('/[^0-9]/', '', $currentClassLevel))
            ->setRace(trim($raceTribeGender[0]))
            ->setTribe(trim($raceTribeGender[1]))
            ->setGender(ord(trim($raceTribeGender[2])) == 226 ? 'Female' : 'Male')
            ->setThumbnail($currentClassThumbnail)
            ->setImage($currentClassImage)
            ->setGuardian($guardian)
            ->setNameday($nameday)
            ->setCityState(isset($characterWorldData[0]) ? $characterWorldData[0] : null)
            ->setFreeCompany(isset($characterWorldData[2]) ? $characterWorldData[2] : null)
            ->setGrandCompany(isset($characterWorldData[1]) ? @end(explode('/', $characterWorldData[1])) : null)
            ->setGrandCompanyRank(isset($characterWorldData[1]) ? @current(explode('/', $characterWorldData[1])) : null);


        if ($currentClass && isset($classJobs[$currentClass])) {
            $character->setCurrentClass($classJobs[$currentClass]);
        }

        $characterAttributes = new CharacterAttributes();

        // all except hp, mp, tp
        foreach ($attributes as $domNodeAttribute) {
            $this->extractAttribute(new Crawler($domNodeAttribute), $characterAttributes);
        }
        // hp, mp, tp
        $characterAttributes->set('hp', $responseCrawler->filter('#power_gauge li.hp')->extract('_text', true));
        $characterAttributes->set('mp', $responseCrawler->filter('#power_gauge li.mp')->extract('_text', true));
        $characterAttributes->set('tp', $responseCrawler->filter('#power_gauge li.tp')->extract('_text', true));

        $character->setAttributes($characterAttributes);

        foreach ($mounts as $mountLink) {
            $mountLink = new Crawler($mountLink);
            $mountName = $mountLink->extract('title', true);
            $mountThumbnail = $mountLink->filter('img')->extract('src', true);

            if ($mountName && $mountThumbnail) {
               $character->addMount(new Mount($mountName, $mountThumbnail));
            }
        }

        foreach ($minions as $minionLink) {
            $minionLink = new Crawler($minionLink);
            $minionName = $minionLink->extract('title', true);
            $minionThumbnail = $minionLink->filter('img')->extract('src', true);

            if ($minionName && $minionThumbnail) {
                $character->addMinion(new Minion($minionName, $minionThumbnail));
            }
        }

        $characterClasses = new CharacterClasses();

        $currentClass = null;
        $currentClassLevel = null;
        $currentClassProgress = null;
        foreach($classRows as $classRow) {
            $classRow = new Crawler($classRow);
            foreach ($classRow->filter('td') as $classColumn) {
                $classColumn = new Crawler($classColumn);
                $columnTextValue = $classColumn->extract('_text', true);
                if (empty($columnTextValue)) {
                    break;
                } else if( is_null($currentClass)) {
                    $currentClass = $columnTextValue;
                } else if( is_null($currentClassLevel)) {
                    $currentClassLevel = $columnTextValue;
                } else if( is_null($currentClassProgress)) {
                    $currentClassProgress = $columnTextValue;
                }

                if ($currentClass && $currentClassLevel && $currentClassProgress) {
                    $class = new CharacterClass($currentClass);
                    $class->setLevel($currentClassLevel)
                        ->setProgress($currentClassProgress);

                    $characterClasses->addClass($class);

                    $currentClass = null;
                    $currentClassLevel = null;
                    $currentClassProgress = null;
                }
            }

        }

        $character->setClasses($characterClasses);


        // items
        $characterEquipment = new CharacterEquipment();
        foreach($characterItemData as $item) {
            $item = new Crawler($item);

            $itemSlot = $item->filter('h3.category_name')->extract('_text', true);
            $_itemSlot = static::camelize(strtolower(str_replace(' ', '_', $itemSlot)));

            if (empty($_itemSlot)) {
                continue;
            }

            $characterEquipmentItem = new CharacterEquipmentItem();

            $itemId = @end(explode('/', preg_replace('/\/$/', '', $item->filter('.bt_db_item_detail a')->extract('href', true))));
            $itemName  = $item->filter('h2.item_name')->extract('_text', true);
            $classes   = $item->filter('span.class_ok')->extract('_text', true);
            $gearLevel = explode(' ', $item->filter('span.gear_level')->extract('_text', true));
            $itemLevel = $item->filter('.area_body_w400_gold div.pt3')->extract('_text', true);
            $bonuses = $item->filter('ul.basic_bonus li');
            $mediumThumbnail = $item->filter('.name_area img.ic_reflection')->extract('src', true);
            $isUnique = trim($item->filter('span.rare')->extract('_text', true)) == 'Unique' ? true : false;
            $isUntradable = trim($item->filter('span.ex_bind')->extract('_text', true)) == 'Untradable' ? true : false;
            $otherStats = explode('  ', trim($item->filter('.ml12 li')->extract('_text', true)));
            $craftedBy = implode('|', $item->filter('h2.player_name_brown')->extract('href','_text'));

            $attributeParameters = $item->filter('.popup_w412_body_gold .popup_w412_body_inner .parameter_name')->extract('_text');
            $attributeParameterValues = $item->filter('.popup_w412_body_gold .popup_w412_body_inner .parameter')->extract('_text');

            if(count($attributeParameters) == count($attributeParameterValues)) {
                $attributes = array_combine($attributeParameters, $attributeParameterValues);
            } else {
               // TODO log or throw error?
                $attributes = array();
            }

            $characterEquipmentItem->setId($itemId)
              ->setName($itemName)
              ->setClassRequirements(stripos($classes, 'All Classes') !== false ? array($classes) : explode(',', $classes))
              ->setRequiredLevel(preg_replace('/[^0-9]/i', '', $gearLevel))
              ->setItemLevel(preg_replace('/[^0-9]/i', '', $itemLevel))
              ->setMediumThumbnail($mediumThumbnail)
              ->setUnique($isUnique)
              ->setSellable(!$isUntradable)
              ->setCraftedBy($craftedBy);

            foreach ($otherStats as $os) {
                if (stripos($os,'Convertible') !== false && stripos($os,'Yes') !== false) {
                    $characterEquipmentItem->setConvertible(true);
                }
                if (stripos($os,'Projectable') !== false && stripos($os,'Yes') !== false) {
                    $characterEquipmentItem->setProjectable(true);
                }
                if (stripos($os,'Desynthesizable') !== false && stripos($os,'Yes') !== false) {
                    $characterEquipmentItem->setDesynthesizable(true);
                }
            }
            foreach ($bonuses as $bonusLi) {
                $bonusLi = new Crawler($bonusLi);
                $parts = explode(' ', $bonusLi->extract('_text', true));
                $characterEquipmentItem->setBonus(strtolower($parts[0]), preg_replace('/[^0-9]/i', '', $parts[1]));
            }

            foreach ($attributes as $attributeName => $attributeValue) {
                $characterEquipmentItem->setAttribute(strtolower(str_replace(' ', '_', $attributeName)), $attributeValue);
            }
            $characterEquipment->set($_itemSlot, $characterEquipmentItem);
        }

        $character->setEquipment($characterEquipment);

        file_put_contents('D:\Projects\LodestoneAPI\out.sphp', print_r((array) $character, true));

        return $response->setCharacter($character);
    }

    /**
     * @param null $id
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return null
     */
    public function getId()
    {
        return $this->id;
    }


    protected function extractAttribute(Crawler $li, CharacterAttributes $characterAttributes)
    {
        $isBaseAttribute = $li->filter('span.left,span.right')->count() ? false : true;
        $isElementAttribute = $li->filter('.clearfix')->count() ? true : false;

        if ($isElementAttribute) {
            $text = $li->extract('_text', true);
            $attributeLabel = preg_replace('/[^[A-Z\s]/i', '', $text);
            $attributeValue = preg_replace('/[^0-9]/', '', $text);
        } else if ($isBaseAttribute) {
            $attributeLabel = trim(str_replace('clearfix', '', $li->getNode(0)->getAttribute('class')));
            $attributeValue = $li->extract('_text', true);
        } else {
            $attributeLabel = $li->filter('span.left')->extract('_text', true);
            $attributeValue = $li->filter('span.right')->extract('_text', true);
        }

        switch($attributeLabel) {
            case 'str':
                $attributeLabel = 'strength';
                break;
            case 'dex':
                $attributeLabel = 'dexterity';
                break;
            case 'vit':
                $attributeLabel = 'vitality';
                break;
            case 'int':
                $attributeLabel = 'intelligence';
                break;
            case 'mnd':
                $attributeLabel = 'mind';
                break;
            case 'pie':
                $attributeLabel = 'piety';
                break;
        }

        if ($attributeLabel && $attributeValue) {
            $characterAttributes->set(strtolower(str_replace(' ', '_', $attributeLabel)), $attributeValue);
            return true;
        }

        return false;
    }

    public static function camelize($str) {
        $func = create_function('$c', 'return strtoupper($c[1]);');
        return preg_replace_callback('/_([a-z])/', $func, $str);
    }

}