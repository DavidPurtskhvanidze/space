<?php
/**
 *
 *    Module: users v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: users-7.5.0-1
 *    Tag: tags/7.5.0-1@19887, 2016-06-17 13:25:03
 *
 *    This file is part of the 'users' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\users\lib\UserProfileField;

class UserProfileFieldManager extends \lib\ORM\ObjectManager implements \core\IService
{
    /**
     * @var UserProfileFieldDBManager
     */
    protected $dbManager;

    /**
     * @var \lib\ORM\Types\TypesManager
     */
    private $typesManager = null;

    public function init()
    {
        $this->dbManager = new \modules\users\lib\UserProfileField\UserProfileFieldDBManager();
        $this->typesManager = new \lib\ORM\Types\TypesManager();
    }

    var $fields_info = array();

    public function createUserProfileField($userProfileFieldInfo, $fieldType)
    {
        $userProfileField = new UserProfileField();
        $userProfileField->setDetails($this->createUserProfileFieldDetails($userProfileFieldInfo, $fieldType));
        $userProfileField->field_type = $fieldType;
        if (isset($userProfileFieldInfo['sid'])) $userProfileField->setSid($userProfileFieldInfo['sid']);
        if (isset($userProfileFieldInfo['order'])) $userProfileField->setOrder($userProfileFieldInfo['order']);
        return $userProfileField;
    }

    private function createUserProfileFieldDetails($userProfileFieldInfo, $fieldType)
    {
        $details = new UserProfileFieldDetails();
        $details->setExtraDetailsInfo($this->getExtraDetailsByFieldType($fieldType));
        $details->setOrmObjectFactory(\App()->OrmObjectFactory);
        $details->buildPropertiesWithData($userProfileFieldInfo);
        return $details;
    }

    private function getExtraDetailsByFieldType($fieldType)
    {
        return $this->typesManager->getExtraDetailsByFieldType($fieldType);
    }

    function getFieldsInfoByUserGroupSID($user_group_sid)
    {
        if (isset($this->fields_info[$user_group_sid])) {
            return $this->fields_info[$user_group_sid];
        } else {
            $this->fields_info[$user_group_sid] = $this->dbManager->getFieldsInfoByUserGroupSID($user_group_sid);

            return $this->fields_info[$user_group_sid];
        }
    }

    function getInfoBySID($user_profile_field_sid)
    {

        return $this->dbManager->getUserProfileFieldInfoBySID($user_profile_field_sid);

    }

    function saveUserProfileField($field)
    {
        return $this->dbManager->saveUserProfileField($field);
    }

    function deleteUserProfileFieldBySID($user_profile_field_sid)
    {
        $this->dbManager->deleteUserProfileFieldInfo($user_profile_field_sid);
    }

    function getUserProfileFieldIDBySID($user_profile_field_sid)
    {
        $user_profile_field_info = $this->getInfoBySID($user_profile_field_sid);

        if (empty($user_profile_field_info)) {
            return null;
        } else {
            return $user_profile_field_info['id'];
        }
    }

    function getFieldBySID($user_profile_field_sid)
    {
        $user_profile_field_info = $this->dbManager->getUserProfileFieldInfoBySID($user_profile_field_sid);

        if (empty($user_profile_field_info)) {
            return null;
        } else {
            $user_profile_field = $this->createUserProfileField($user_profile_field_info, $user_profile_field_info['type']);
            $user_profile_field->setUserGroupSID($user_profile_field_info['user_group_sid']);

            return $user_profile_field;
        }
    }

    function fieldExists($id)
    {
        return $this->dbManager->fieldExists($id);
    }

    /**
     * @param UserProfileField $originalField
     * @param UserProfileField $newField
     * @return void
     */
    public function updateColumnForField($originalField, $newField)
    {
        $originalName = $originalField->getPropertyValue('id');
        if ($originalField->getFieldType() == 'picture')
        {
            //todo:: this is shit code. I have not found another solution
            $newFieldName = $newField->getPropertyValue('id');
            $operations = "CHANGE {$originalName} {$newFieldName} varchar(250) DEFAULT NULL, "
                . "CHANGE {$originalName}_file_name {$newFieldName}_file_name varchar(250) DEFAULT NULL, "
                . "CHANGE {$originalName}_content_type {$newFieldName}_content_type varchar(15) DEFAULT NULL, "
                . "CHANGE {$originalName}_file_size {$newFieldName}_file_size INT(11)DEFAULT NULL";

            \App()->DB->query("ALTER IGNORE TABLE `users_users` {$operations}");
            return;
        }

        $columnDefinition = $this->getColumnDefinitionForField($newField);
        if (is_null($columnDefinition)) return;
        \App()->DB->query("ALTER IGNORE TABLE `users_users` CHANGE COLUMN `$originalName` $columnDefinition");
    }

    public function addColumnToTableForField($field)
    {
        $columDefinition = $this->getColumnDefinitionForField($field);
        if (is_null($columDefinition)) return;
        \App()->DB->query("ALTER TABLE `users_users` ADD COLUMN $columDefinition");
    }

    public function dropTableColumnForField($field)
    {
        $columDefinition = $this->getColumnDefinitionForField($field);
        if (is_null($columDefinition)) return;

        $property = $this->getProperty($field);
        if (!$property->type->hasMultipleColumns())
        {
            \App()->DB->query("ALTER TABLE `users_users` DROP COLUMN `" . $field->getId() . "`");
        }
        else
        {
            $columns = $property->type->getColumnsList();
            $columns = implode(', DROP ', $columns);
            \App()->DB->query('ALTER TABLE `users_users` DROP ' . $columns);
        }
    }

    /**
     * @param UserProfileField $field
     * @return null|string
     */
    private function getColumnDefinitionForField($field)
    {
        $property = $this->getProperty($field);
        return $property->getColumnDefinition();
    }

    private function getProperty($field)
    {
        $property_data = array
        (
            'type' => $field->getFieldType(),
            'id' => $field->getPropertyValue('id'),
            'caption' => null,
            'value' => null
        );
        $extraInfo = $this->typesManager->getExtraDetailsByFieldType($field->getFieldType());
        foreach ($extraInfo as $extraField) {
            $name = $extraField['id'];
            $value = $field->getPropertyValue($name);
            if (is_null($value) && isset($extraField['value'])) {
                $value = $extraField['value'];
            }
            $property_data[$extraField['id']] = $value;
        }
        return \App()->OrmObjectFactory->createObjectProperty($property_data);
    }

    public function getFieldsSidByUserGroupSid($userGroupSid)
    {
        return array_map(function ($row) {
            return $row['sid'];
        }, \App()->DB->query("SELECT `sid` FROM `users_profile_fields` WHERE `user_group_sid` = ?n", $userGroupSid));
    }

    function getTreeValuesByParentSID($field_sid, $parent_sid)
    {
        return \App()->UserProfileFieldTreeManager->getTreeValuesByParentSID($field_sid, $parent_sid);
    }

    function addTreeItemToBeginByParentSID($field_sid, $parent_sid, $tree_item_value)
    {
        return \App()->UserProfileFieldTreeManager->addTreeItemToBeginByParentSID($field_sid, $parent_sid, $tree_item_value);
    }

    function addTreeItemToEndByParentSID($field_sid, $parent_sid, $tree_item_value)
    {
        return \App()->UserProfileFieldTreeManager->addTreeItemToEndByParentSID($field_sid, $parent_sid, $tree_item_value);
    }

    function addTreeItemAfterByParentSID($field_sid, $parent_sid, $tree_item_value, $after_tree_item_sid)
    {
        return \App()->UserProfileFieldTreeManager->addTreeItemAfterByParentSID($field_sid, $parent_sid, $tree_item_value, $after_tree_item_sid);
    }

    function deleteTreeItemBySID($item_sid)
    {
        return \App()->UserProfileFieldTreeManager->deleteTreeItemBySID($item_sid);
    }

    function deleteTreeItemsBySIDs($fieldSID, array $itemSIDs, $nodeSID)
    {
        return \App()->UserProfileFieldTreeManager->deleteTreeItemsBySIDs($fieldSID, $itemSIDs, $nodeSID);
    }

    function moveUpTreeItem($item_sid)
    {
        return \App()->UserProfileFieldTreeManager->moveUpTreeItem($item_sid);
    }

    function moveDownTreeItem($item_sid)
    {
        return \App()->UserProfileFieldTreeManager->moveDownTreeItem($item_sid);
    }

    function sortTreeItemsAscending($field_sid, $node_sid)
    {
        $tree_values = \App()->UserProfileFieldTreeManager->getTreeValuesByParentSID($field_sid, $node_sid);
        asort($tree_values);
        return \App()->UserProfileFieldTreeManager->reorderTreeItems($field_sid, array_keys($tree_values), $node_sid);
    }

    function sortTreeItemsDescending($field_sid, $node_sid)
    {
        $tree_values = \App()->UserProfileFieldTreeManager->getTreeValuesByParentSID($field_sid, $node_sid);
        arsort($tree_values);
        return \App()->UserProfileFieldTreeManager->reorderTreeItems($field_sid, array_keys($tree_values), $node_sid);
    }

    function getTreeItemInfoBySID($item_sid)
    {
        return \App()->UserProfileFieldTreeManager->getTreeItemInfoBySID($item_sid);
    }

    function updateTreeItemBySID($item_sid, $tree_item_value)
    {
        return \App()->UserProfileFieldTreeManager->updateTreeItemBySID($item_sid, $tree_item_value);
    }

    function getTreeNodePath($node_sid)
    {
        return \App()->UserProfileFieldTreeManager->getTreeNodePath($node_sid);
    }

    public function getUserGroupSidByFieldSid($fieldSid)
    {
        return \App()->DB->getSingleValue("SELECT `user_group_sid` FROM `users_profile_fields` WHERE `sid` = ?n", $fieldSid);
    }
}
