<?php

namespace Miva\Migration\Database\Miva;

trait InventoryTrait
{

    public function getInventorySettings()
    {
        return $this->getConnection()
            ->createQueryBuilder()
            ->select('*')
            ->from($this->tablePrefix . 'InventorySettings')
            ->execute()
            ->fetch();
    }


    public function getProductInventorySettings()
    {
        return  $this->getConnection()
            ->createQueryBuilder()
            ->select('*')
            ->from($this->tablePrefix . 'InventoryProductSettings')
            ->execute()
            ->fetchAll();
    }


    public function getProductInventoryCounts()
    {
        return $this->getConnection()
            ->createQueryBuilder()
            ->select('*')
            ->from($this->tablePrefix . 'InventoryProductCounts')
            ->execute()
            ->fetchAll();
    }

    public function getProductInventorySetting($productId = null)
    {
        return $this->getConnection()
            ->createQueryBuilder()
            ->select('*')
            ->from($this->tablePrefix . 'InventoryProductSettings')
            ->where('product_id = :productId')
            ->setParameter('productId', $productId)
            ->execute()
            ->fetch();
    }

    public function getProductInventoryCount($productId)
    {
        return $this->getConnection()
            ->createQueryBuilder()
            ->select('*')
            ->from($this->tablePrefix . 'InventoryProductCounts')
            ->where('product_id = :productId')
            ->setParameter('productId', $productId)
            ->execute()
            ->fetch();
    }

    public function insertInventorySettings(array $inventorySettings)
    {
        return $this->getConnection()->insert(
            $this->tablePrefix.'InventorySettings',
            $inventorySettings
        );
    }

    public function updateInventorySettings(array $inventorySettings)
    {
        throw new \Exception('Not Implemented Yet');
    }


    public function insertProductInventorySetting(array $inventorySetting)
    {
        return $this->getConnection()->insert(
            $this->tablePrefix.'InventoryProductSettings',
            $inventorySetting
        );
    }

    public function updateProductInventorySetting(array $inventorySettings)
    {
        return $this->getConnection()->update(
            $this->tablePrefix.'InventoryProductSettings',
            $inventorySettings,
            array(
                'product_id' => $inventorySettings['product_id'],
            )
        );
    }

    public function insertProductInventoryCount(array $inventoryCount)
    {
        return $this->getConnection()->insert(
            $this->tablePrefix.'InventoryProductCounts',
            $inventoryCount
        );
    }

    public function updateProductInventoryCount(array $inventoryCount)
    {
        return $this->getConnection()->update(
            $this->tablePrefix.'InventoryProductCounts',
            $inventoryCount,
            array(
                'product_id' => $inventoryCount['product_id'],
            )
        );
    }
}