<?php
/**
 *
 *    iLister v.7.5.0, (c) WorksForWeb 2005 - 2016
 *
 *    Package: iLister-7.5.0
 *    Tag: modular/projects/classifieds/tags/7.5.0@19890, 2016-06-17 13:38:22
 *
 *    This file is a part of iLister software
 *    and is protected by international copyright laws
 *
 */


namespace lib\ORM;

class ObjectsReplacer
{
	var $tableName;
	var $orderColumn = 'order';
	var $parentName;
	var $parentValue;
	var $parentNodeName;
	var $parentNodeValue;
	var $newOrder;

	public function setTableName($tableName)
	{
		$this->tableName = $tableName;
	}

	public function setParentName($parentName)
	{
		$this->parentName = $parentName;
	}

	public function setParentValue($parentValue)
	{
		$this->parentValue = $parentValue;
	}

	public function setParentNodeValue($parentNodeValue)
	{
		$this->parentNodeValue = $parentNodeValue;
	}

	public function setNewOrder($newOrder)
	{
		$this->newOrder = $newOrder;
	}

	function update()
	{
        $nextItemSid = (integer)$this->newOrder['nextItemSid'];
        $prevItemSid = (integer)$this->newOrder['prevItemSid'];
        $itemSid = (integer)$this->newOrder['sid'];

        $siblingsItems = \App()->DB->query(
            "SELECT * FROM `{$this->tableName}` WHERE `sid` IN (?l)",
            [$itemSid, $nextItemSid, $prevItemSid]
        );
        $nextItem = array_filter($siblingsItems, function ($item) use ($nextItemSid) {
            return $item['sid'] == $nextItemSid;
        });
        $prevItem = array_filter($siblingsItems, function ($item) use ($prevItemSid) {
            return $item['sid'] == $prevItemSid;
        });
        $item = array_filter($siblingsItems, function ($item) use ($itemSid) {
            return $item['sid'] == $itemSid;
        });

        $nextItem = array_pop($nextItem);
        $prevItem = array_pop($prevItem);
        $item = array_pop($item);
        $curOrder = $item[$this->orderColumn];

        if ($prevItemSid == 0) { //е�?ли �?амый верх
            $newOrder = $nextItem[$this->orderColumn];
            $this->incrementOrders($newOrder, $curOrder);
        } elseif ($nextItemSid == 0) {//е�?ли �?амый низ
            $newOrder = $prevItem[$this->orderColumn] + 1;
            $this->decrementOrders($prevItem[$this->orderColumn], $curOrder);
        } elseif ($curOrder > $nextItem[$this->orderColumn]) { //был перемещен вверх
            $newOrder = $nextItem[$this->orderColumn];
            $this->incrementOrders($newOrder, $curOrder);
        } else {//был перемещен низ
            $newOrder = $prevItem[$this->orderColumn];
            $this->decrementOrders($newOrder, $curOrder);
        }

        \App()->DB->query("UPDATE `$this->tableName` SET `{$this->orderColumn}` = ?n WHERE `sid` = ?n", $newOrder, $itemSid);
	}


    private function incrementOrders($newOrder, $curOrder)
    {
        if (is_null($this->parentNodeName))
        {
            \App()->DB->query("UPDATE `{$this->tableName}`
                               SET `{$this->orderColumn}` = `{$this->orderColumn}` + 1
                               WHERE `$this->parentName` = ?n
                                AND (`{$this->orderColumn}` BETWEEN ?n AND ?n)", $this->parentValue, $newOrder, $curOrder);
        } else {
            \App()->DB->query("UPDATE `{$this->tableName}`
                               SET `{$this->orderColumn}` = `{$this->orderColumn}` + 1
                               WHERE
                               `$this->parentName` = ?n
                               AND `{$this->parentNodeName}` = ?n
                               AND (`{$this->orderColumn}` BETWEEN ?n AND ?n)", $this->parentValue, $this->parentNodeValue, $newOrder, $curOrder);
        }
    }

    private function decrementOrders($newOrder, $curOrder)
    {
        if (is_null($this->parentNodeName))
        {
            \App()->DB->query("UPDATE `{$this->tableName}`
                               SET `{$this->orderColumn}` = `{$this->orderColumn}` - 1
                               WHERE `$this->parentName` = ?n
                               AND (`{$this->orderColumn}` BETWEEN ?n AND ?n)", $this->parentValue, $curOrder, $newOrder);
        } else {
            \App()->DB->query("UPDATE `{$this->tableName}`
                               SET `{$this->orderColumn}` = `{$this->orderColumn}` - 1
                               WHERE
                               `$this->parentName` = ?n
                               AND `{$this->parentNodeName}` = ?n
                               AND (`{$this->orderColumn}` BETWEEN ?n AND ?n)", $this->parentValue,$this->parentNodeValue, $curOrder, $newOrder);
        }
    }
}
