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


namespace lib\ORM\Rating;

class RatingManager
{
    private $objectProperty = [];
    private $ratingCache = [];

    public function setObjectProperty($objectProperty)
    {
        $this->objectProperty = $objectProperty;
    }

    public function getObjectProperty()
    {
        return $this->objectProperty;
    }

    public function getObjectType()
    {
        return isset($this->objectProperty['object_type']) ? $this->objectProperty['object_type'] : $this->objectProperty;
    }

    public function getRatingByUser($objectSid, $fieldSid, $userSid)
    {
        return \App()->DB->getSingleValue("select `rating` from `classifieds_rating` where `field_sid` = ?n and `object_sid` = ?n and `user_sid` = ?n and `object_type` = ?s", $fieldSid, $objectSid, $userSid, $this->getObjectType());
    }

    function &getRating($objectSid, $fieldSid)
    {
        if (isset($this->ratingCache[$objectSid . $fieldSid])) return $this->ratingCache[$objectSid . $fieldSid];
        $data = explode('|', $this->objectProperty['value']);
        if (empty($data)) {
            $res = $this->calculateRating($objectSid, $fieldSid);
        } else {
            $res = ['rating' => isset($data[0]) ? $data[0] : 0, 'count' => isset($data[1]) ? $data[1] : 0];
        }

        $this->ratingCache[$objectSid . $fieldSid] = $res;
        return $res;
    }

    public function calculateRating($objectSid, $fieldSid)
    {
        return \App()->DB->getSingleRow("select avg(`rating`) as `rating`, count(*) as `count` from `classifieds_rating` where `field_sid` = ?n and `object_sid` = ?n and `object_type` = ?s", $fieldSid, $objectSid, $this->getObjectType());
    }

    function addRating($objectSid, $fieldSid, $rating)
    {
        $userSid = \App()->UserManager->getCurrentUserSID();
        $this->setRating($objectSid, $fieldSid, $rating, $userSid);
    }

    function setRating($objectSid, $fieldSid, $rating, $userSid)
    {
        \App()->DB->query("delete from `classifieds_rating` where `field_sid` = ?n and `object_sid` = ?n and `user_sid` = ?n and `object_type` = ?s", $fieldSid, $objectSid, $userSid, $this->getObjectType());
        \App()->DB->query("insert into `classifieds_rating` (`rating`, field_sid, object_sid, user_sid, object_type) values (?f, ?n, ?n, ?n, ?s)", $rating, $fieldSid, $objectSid, $userSid, $this->getObjectType());

        $afterObjectRatedActions = new \core\ExtensionPoint('lib\ORM\Rating\IAfterObjectRated');
        foreach ($afterObjectRatedActions as $afterObjectRatedAction) {
            $afterObjectRatedAction->setRatingManager($this);
            $afterObjectRatedAction->setObjectSid($objectSid);
            $afterObjectRatedAction->setFieldSid($fieldSid);
            $afterObjectRatedAction->setUserSid($userSid);
            $afterObjectRatedAction->perform();
        }
    }

    function deleteRatingByObjectSID($objectSid)
    {
        \App()->DB->query("delete from `classifieds_rating` where `object_sid` = ?n  and `object_type` = ?s", $objectSid, $this->getObjectType());
    }

    public function createRating($objectSid, $fieldSid)
    {
        $rating = new Rating();
        $rating->setManager($this);
        $rating->setObjectSid($objectSid);
        $rating->setFieldSid($fieldSid);
        return $rating;
    }

}

?>
