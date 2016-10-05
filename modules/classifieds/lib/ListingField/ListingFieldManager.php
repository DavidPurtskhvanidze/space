<?php
/**
 *
 *    Module: classifieds v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: classifieds-7.5.0-1
 *    Tag: tags/7.5.0-1@19773, 2016-06-17 13:19:01
 *
 *    This file is part of the 'classifieds' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\classifieds\lib\ListingField;

class ListingFieldManager implements \core\IService
{
    /**
     * @var ListingFieldDBManager
     */
    private $dbManager = null;
    private $typesManager = null;

    public function init()
    {
        $this->dbManager = new \modules\classifieds\lib\ListingField\ListingFieldDBManager();
        $this->typesManager = new \lib\ORM\Types\TypesManager();
    }

    public function getDetailsMetadata($fieldType)
    {
        $extra_details_info = $this->typesManager->getExtraDetailsByFieldType($fieldType);
        return array_merge(ListingFieldDetails::$common_details_info, $extra_details_info);
    }

    public function createListingField($listing_field_info, $category_sid)
    {
        $type = isset($listing_field_info['type']) ? $listing_field_info['type'] : null;
        $field = new ListingField();
        $field->setDetails($this->createListingFieldDetails($type));
        $field->setCategorySID($category_sid);
        $field->incorporateData($listing_field_info);
        if (isset($listing_field_info['sid'])) {
            $field->setSID($listing_field_info['sid']);
        }
        return $field;
    }

    private function createListingFieldDetails($fieldType)
    {
        $details = new ListingFieldDetails();
        $details->setDetailsInfo($this->getDetailsMetadata($fieldType));
        $details->setOrmObjectFactory(\App()->OrmObjectFactory);
        $details->buildProperties();
        return $details;
    }

    function getCommonListingFieldsInfo()
    {
        return $this->getListingFieldsInfoByCategory(0);
    }

    function saveListingField($listing_field)
    {
        $cache_id = 'getListingFieldsInfoByCategory_' . $listing_field->getCategorySID();
        \App()->MemoryCache->reset($cache_id);
        \App()->MemoryCache->reset('cache for classifieds_listing_fields');
        \App()->MemoryCache->reset('cache for classifieds_listing_field_list');
        \App()->MemoryCache->reset('cache for classifieds_listing_field_tree');
        \App()->MemoryCache->reset('DB_Q_SELECT * FROM classifieds_listing_fields');
        return $this->dbManager->saveListingField($listing_field);
    }

    function getInfoBySID($listing_field_sid)
    {
        return $this->dbManager->getListingFieldInfoBySID($listing_field_sid);
    }

    function deleteListingFieldBySID($listing_field_sid)
    {
        $field = $this->getFieldBySID($listing_field_sid);

        $result = $this->dbManager->deleteListingFieldBySID($listing_field_sid);

        $columDefinition = $this->getColumnDefinitionForField($field);
        if (!is_null($columDefinition))
            $result &= $this->dbManager->deleteColumnFromListingTable($field->getId());

        return (bool)$result;
    }

    function getListingFieldsInfoByCategory($category_sid)
    {
        $cache_id = 'getListingFieldsInfoByCategory_' . $category_sid;
        if (\App()->MemoryCache->exists($cache_id)) {
            $fields_info = \App()->MemoryCache->get($cache_id);
        } else {
            $fields_info = $this->dbManager->getListingFieldsInfoByCategory($category_sid);
            \App()->MemoryCache->set($cache_id, $fields_info);
        }
        return $fields_info;
    }

    function deleteListingFieldsByCategorySID($categorySid)
    {
        $fields = \App()->DB->query("SELECT `sid` FROM `classifieds_listing_fields` WHERE `category_sid` = ?n", $categorySid);
        foreach ($fields as $field) {
            $this->deleteListingFieldBySID($field['sid']);
        }
    }

    function getFieldBySID($listing_field_sid)
    {
        $listing_field_info = $this->dbManager->getListingFieldInfoBySID($listing_field_sid);
        if (empty($listing_field_info)) {
            return null;
        } else {
            $listing_field = $this->createListingField($listing_field_info, $listing_field_info['category_sid']);
            $listing_field->setSID($listing_field_sid);
            return $listing_field;
        }
    }

    function getListingFieldIDBySID($listing_field_sid)
    {
        $listing_field_info = $this->getInfoBySID($listing_field_sid);
        if (empty($listing_field_info)) return null;
        return $listing_field_info['id'];
    }

    function getTreeValuesByParentSID($field_sid, $parent_sid)
    {
        return \App()->ListingFieldTreeManager->getTreeValuesByParentSID($field_sid, $parent_sid);
    }

    function addTreeItemToBeginByParentSID($field_sid, $parent_sid, $tree_item_value)
    {
        return \App()->ListingFieldTreeManager->addTreeItemToBeginByParentSID($field_sid, $parent_sid, $tree_item_value);
    }

    function addTreeItemToEndByParentSID($field_sid, $parent_sid, $tree_item_value)
    {
        return \App()->ListingFieldTreeManager->addTreeItemToEndByParentSID($field_sid, $parent_sid, $tree_item_value);
    }

    function addTreeItemAfterByParentSID($field_sid, $parent_sid, $tree_item_value, $after_tree_item_sid)
    {
        return \App()->ListingFieldTreeManager->addTreeItemAfterByParentSID($field_sid, $parent_sid, $tree_item_value, $after_tree_item_sid);
    }

    function deleteTreeItemBySID($item_sid)
    {
        return \App()->ListingFieldTreeManager->deleteTreeItemBySID($item_sid);
    }

    function deleteTreeItemsBySIDs($fieldSID, array $itemSIDs, $nodeSID)
    {
        return \App()->ListingFieldTreeManager->deleteTreeItemsBySIDs($fieldSID, $itemSIDs, $nodeSID);
    }

    function moveUpTreeItem($item_sid)
    {
        return \App()->ListingFieldTreeManager->moveUpTreeItem($item_sid);
    }

    function moveDownTreeItem($item_sid)
    {
        return \App()->ListingFieldTreeManager->moveDownTreeItem($item_sid);
    }

    function sortTreeItemsAscending($field_sid, $node_sid)
    {
        $tree_values = \App()->ListingFieldTreeManager->getTreeValuesByParentSID($field_sid, $node_sid);
        asort($tree_values);
        return \App()->ListingFieldTreeManager->reorderTreeItems($field_sid, array_keys($tree_values), $node_sid);
    }

    function sortTreeItemsDescending($field_sid, $node_sid)
    {
        $tree_values = \App()->ListingFieldTreeManager->getTreeValuesByParentSID($field_sid, $node_sid);
        arsort($tree_values);
        return \App()->ListingFieldTreeManager->reorderTreeItems($field_sid, array_keys($tree_values), $node_sid);
    }

    function getTreeItemInfoBySID($item_sid)
    {
        return \App()->ListingFieldTreeManager->getTreeItemInfoBySID($item_sid);
    }

    function updateTreeItemBySID($item_sid, $tree_item_value)
    {
        return \App()->ListingFieldTreeManager->updateTreeItemBySID($item_sid, $tree_item_value);
    }

    function getTreeNodePath($node_sid)
    {
        return \App()->ListingFieldTreeManager->getTreeNodePath($node_sid);
    }

    function getFieldsInfoByType($type)
    {
        $type_fields = \App()->DB->query("SELECT * FROM `classifieds_listing_fields` WHERE `type` =?s", $type);
        return $type_fields;
    }

    function getListingFields()
    {
        return \App()->DB->query('SELECT * FROM `classifieds_listing_fields`');
    }

    public function updateColumnForField($originalField, $newField)
    {
        $columDefinition = $this->getColumnDefinitionForField($newField);
        if (is_null($columDefinition)) return;
        $originalName = $originalField->getPropertyValue('id');
        \App()->DB->query("ALTER IGNORE TABLE `classifieds_listings` CHANGE COLUMN `$originalName` $columDefinition");
    }

    public function addColumnToListingTableForField($field)
    {
        $columDefinition = $this->getColumnDefinitionForField($field);
        if (is_null($columDefinition)) return;
        \App()->DB->query("ALTER TABLE `classifieds_listings` ADD COLUMN $columDefinition");
    }

    public function dropTableColumnForField($field)
    {
        $columDefinition = $this->getColumnDefinitionForField($field);
        if (is_null($columDefinition)) return;
        \App()->DB->query("ALTER TABLE `classifieds_listings` DROP COLUMN `" . $field->getId() . "`");
    }

    public function getColumnDefinitionForField($field)
    {
        $property_data = array(
            'type' => $field->getFieldType(),
            'id' => $field->getPropertyValue('id'),
            'caption' => null,
            'value' => null
        );
        $extraInfo = $this->typesManager->getExtraDetailsByFieldType($field->getFieldType());
        foreach ($extraInfo as $extraField) {
            $name = $extraField['id'];
            if ($field->propertyIsSet($name)) $value = $field->getPropertyValue($name);
            $property_data[$extraField['id']] = is_null($value) ? $extraField['value'] : $value;
        }
        return \App()->OrmObjectFactory->createObjectProperty($property_data)->getColumnDefinition();
    }

    public function getFieldSidById($id)
    {
        return \App()->DB->getSingleValue("SELECT sid FROM `classifieds_listing_fields` WHERE id = ?s", $id);
    }

    public function getListingFieldsByRequest($request, $categorySidForOrdering)
    {
        return \App()->ObjectToArrayAdapterFactory->getObjectToArrayWrapperCollectionDecorator($this->getSearch($request, $categorySidForOrdering)->getFoundObjectCollection());
    }

    public function getListingFieldSidsByRequest($request, $categorySidForOrdering)
    {
        return $this->getSearch($request, $categorySidForOrdering)->getFoundObjectSidCollection();
    }

    private function getSearch($request, $categorySid)
    {
        $search = new \lib\ORM\SearchEngine\Search();
        $search->setRequest($request);
        $search->setDB(\App()->DB);
        $search->setCriterionFactory(\App()->SearchCriterionFactory);
        $search->setRowMapper(new ListingFieldToRowMapperAdapter($this));
        $model = $this->getModelObject();
        $model->addProperty(
            array
            (
                'id' => 'order',
                'caption' => 'Order',
                'type' => 'integer',
                'table_name' => 'classifieds_listing_fields_order',
                'join_condition' => array
                (
                    array('key_column' => 'sid', 'foriegn_column' => 'field_sid'),
                    array('foriegn_column' => 'category_sid', 'value' => $categorySid)
                ),
            ));

        $model->addProperty(
            array
            (
                'id' => 'sid',
                'caption' => 'Sid',
                'type' => 'integer',
            ));
        $search->setModelObject($model);
        $search->setPage(1);
        $search->setObjectsPerPage(100000);
        $search->setSortingFields(array('order' => 'ASC'));
        return $search;
    }

    private function getModelObject()
    {
        static $model = null;
        if (is_null($model)) {
            $model = $this->createListingField([], null);
        }
        return $model;
    }


    private function incrementOrders($categorySid, $newOrder, $curOrder)
    {
        \App()->DB->query("UPDATE `classifieds_listing_fields_order`
                               SET `order` = `order` + 1
                               WHERE `category_sid` = ?n
                                AND (`order` BETWEEN ?n AND ?n)", $categorySid, $newOrder, $curOrder);
    }

    private function decrementOrders($categorySid, $newOrder, $curOrder)
    {
        \App()->DB->query("UPDATE `classifieds_listing_fields_order`
                               SET `order` = `order` - 1
                               WHERE `category_sid` = ?n
                               AND (`order` BETWEEN ?n AND ?n)", $categorySid, $curOrder, $newOrder);

    }

    public function changeFieldOrderForCategory($categorySid, $field)
    {
        $nextItemSid = (integer)$field['nextItemSid'];
        $prevItemSid = (integer)$field['prevItemSid'];
        $itemSid = (integer)$field['sid'];

        $siblingsItems = \App()->DB->query(
            "SELECT * FROM `classifieds_listing_fields_order` WHERE `field_sid` IN (?l) AND `category_sid` = ?n",
            [$itemSid, $nextItemSid, $prevItemSid], $categorySid
        );

        $nextItem = array_filter($siblingsItems, function ($item) use ($nextItemSid) {
            return $item['field_sid'] == $nextItemSid;
        });
        $prevItem = array_filter($siblingsItems, function ($item) use ($prevItemSid) {
            return $item['field_sid'] == $prevItemSid;
        });
        $item = array_filter($siblingsItems, function ($item) use ($itemSid) {
            return $item['field_sid'] == $itemSid;
        });

        $nextItem = array_pop($nextItem);
        $prevItem = array_pop($prevItem);
        $item = array_pop($item);

        if ($prevItemSid == 0) { //е�?ли �?амый верх
            $newOrder = $nextItem['order'];
            $this->incrementOrders($categorySid, $newOrder, $item['order']);
        } elseif ($nextItemSid == 0) {//е�?ли �?амый низ
            $newOrder = $prevItem['order'] + 1;
            $this->decrementOrders($categorySid, $prevItem['order'], $item['order']);
        } elseif ($item['order'] > $nextItem['order']) { //был перемещен вверх
            $newOrder = $nextItem['order'];
            $this->incrementOrders($categorySid, $newOrder, $item['order']);
        } else {//был перемещен низ
            $newOrder = $prevItem['order'];
            $this->decrementOrders($categorySid, $newOrder, $item['order']);
        }

        \App()->DB->query("UPDATE `classifieds_listing_fields_order` SET `order` = ?n WHERE `field_sid` = ?n", $newOrder, $itemSid);
    }

    public function setFieldsOrderForCategory($categorySid, $newOrder)
    {
        $values = [];
        $order = 1;
        foreach ($newOrder as $fieldSid) {
            $values[] = "({$categorySid}, {$fieldSid}, {$order})";
            $order++;
        }
        \App()->DB->query("DELETE FROM `classifieds_listing_fields_order` WHERE `category_sid` = ?n", $categorySid);
        \App()->DB->query("INSERT INTO `classifieds_listing_fields_order`(`category_sid`, `field_sid`, `order`) VALUES" . join(", ", $values));
    }

    public function getFieldSidsOrderedForCategory($categorySid)
    {
        return \App()->DB->column("SELECT `field_sid` FROM `classifieds_listing_fields_order` WHERE `category_sid` = ?n ORDER BY `order`", $categorySid);
    }

    public function insertOrdersIfNOtExits($categorySid)
    {
        $fieldsOrder = $this->getFieldSidsOrderedForCategory($categorySid);
        if (empty($fieldsOrder))
        {
            $inheritanceBranchCategorySids = \App()->CategoryManager->getCategoryParentTreeBySID($categorySid);
            $request['category_sid']['in'] = $inheritanceBranchCategorySids;
            $fields = \App()->ListingFieldManager->getListingFieldsByRequest($request, $categorySid);
            $values = [];
            $order = 1;
            foreach($fields as $field) {
                $values[] = "({$categorySid}, {$field['sid']}, {$order})";
                $order++;
            }
            \App()->DB->query("INSERT INTO `classifieds_listing_fields_order`(`category_sid`, `field_sid`, `order`) VALUES" . join(", ", $values));
        }
    }

    /**
     * @param \modules\classifieds\lib\Category\Category $category
     */
    public function copyFieldsOrderFromParent($category)
    {
        $parentCategoryFieldSidsOrdered = \App()->ListingFieldManager->getFieldSidsOrderedForCategory($category->getPropertyValue('parent'));
        if (!empty($parentCategoryFieldSidsOrdered)) {
            \App()->ListingFieldManager->setFieldsOrderForCategory($category->getSID(), $parentCategoryFieldSidsOrdered);
        }
    }

    /**
     * @param ListingField $listingField
     */
    public function addListingFieldToOrderTable($listingField)
    {
        $categoryBranchSids = \App()->CategoryManager->getCategoryBranchSids($listingField->getCategorySID());

        // getting the current max orders of the category branch from DB
        $categoriesMaxOrder = \App()->DB->query("SELECT  `category_sid` , MAX(`order`) AS  `max_order`
		FROM  `classifieds_listing_fields_order`
		WHERE `category_sid` IN (?l)
		GROUP BY  `category_sid` ", $categoryBranchSids);
        $maxOrdersByCategory = [];
        foreach ($categoriesMaxOrder as $categoryMaxOrder) {
            $maxOrdersByCategory[$categoryMaxOrder['category_sid']] = $categoryMaxOrder['max_order'];
        }

        // filling max order with 0 for the categories which are not in the `classifieds_listing_fields_order` table
        $emptyOrdersByCategory = array_combine($categoryBranchSids, array_fill(0, count($categoryBranchSids), 0));
        $maxOrdersByCategory = $maxOrdersByCategory + $emptyOrdersByCategory;

        // defining orders for the field based on category
        $values = [];
        foreach ($maxOrdersByCategory as $categorySid => $maxOrder) {
            $order = $maxOrder + 1;
            $values[] = "({$categorySid}, {$listingField->getSID()}, {$order})";
        }
        \App()->DB->queryNoReplace("INSERT INTO `classifieds_listing_fields_order`(`category_sid`, `field_sid`, `order`) VALUES" . join(", ", $values));
    }

    public function prepareBulkListingFields($fieldsParams)
    {
        $count = count($fieldsParams['id']);
        $fields = [];
        $arr = [];
        for ($i = 0; $i < $count; $i++) {
            $arr[$i]['id'] = $fieldsParams['id'][$i];
            $arr[$i]['caption'] = $fieldsParams['caption'][$i];
            $arr[$i]['type'] = $fieldsParams['type'][$i];
            $arr[$i]['is_required'] = $fieldsParams['is_required'][$i];

            $fields[$i] = \App()->ListingFieldManager->createListingField($arr[$i], $fieldsParams['category_sid']);
            $fields[$i]->deleteProperty('category_sid');
        }

        return $fields;
    }

    public function saveListingFieldsBunch($listingFields)
    {
        $count = count($listingFields);
        for ($i = 0; $i < $count; $i++) {
            \App()->ListingFieldManager->addColumnToListingTableForField($listingFields[$i]);
            \App()->ListingFieldManager->saveListingField($listingFields[$i]);
            \App()->ListingFieldManager->addListingFieldToOrderTable($listingFields[$i]);
        }
    }

    public function getListingFieldTypes()
    {
        foreach (ListingFieldDetails::$common_details_info as $fieldInfo) {
            if ($fieldInfo['id'] == 'type') {
                return $fieldInfo['list_values'];
            }
        }
        return [];
    }
}
