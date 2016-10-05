<?php
/**
 *
 *    Module: image_carousel v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: image_carousel-7.5.0-1
 *    Tag: tags/7.5.0-1@19785, 2016-06-17 13:19:31
 *
 *    This file is part of the 'image_carousel' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\image_carousel\lib\CarouselImage;

use lib\ORM\SearchEngine\Search;

class CarouselImageManager implements \core\IService
{
	public function createCarouselImage($data)
	{
		$carouselImage = new CarouselImage();
		$carouselImage->setDetails($this->createCarouselImageDetails($data));
        if (!empty($data['sid'])) {
            $carouselImage->setSID($data['sid']);
        }
		return $carouselImage;
	}

    public function getModel()
    {
        $carouselImage = new CarouselImage();
        $details = new CarouselImageDetails();
        $carouselImage->setDetails($details->getDetails([]));
        $carouselImage->addProperty(
            [
                'id' => 'display_order',
                'caption' => 'Display Order',
                'type' => 'integer',
                'save_into_db' => true,
            ]
        );
        return $carouselImage;
    }

	public function createCarouselImageDetails($data)
	{
		$details = new CarouselImageDetails();
		$details->setOrmObjectFactory(\App()->OrmObjectFactory);
		$details->buildPropertiesWithData($data);
		return $details;
	}

    public function getCollectionForTemplate($limit = 100)
    {
        $search = new Search();
        $search->setDB(\App()->DB);
        $search->setModelObject($this->getModel());
        $search->setObjectsPerPage($limit);
        $search->setRowMapper($this);
        $search->setSortingFields(['display_order' => 'ASC']);
        return \App()->ObjectToArrayAdapterFactory->getObjectToArrayWrapperCollectionDecorator($search->getFoundObjectCollection());
    }

    public function mapRowToObject($row)
    {
        return $this->createCarouselImage($row);
    }

	public function saveObject($carouselImage)
	{
		if (is_null($carouselImage->getSID()))
		{
			$carouselImage->addProperty(
                [
				'id' => 'display_order',
				'caption' => 'Display Order',
				'type' => 'integer',
				'value' => $this->getImagesCount() + 1,
				'save_into_db' => true,
                ]);
		}

		\App()->ObjectDBManager->saveObject($carouselImage);
	}

    public function getEnabledForTemplate($limit = 100)
    {
        $search = new Search();
        $search->setDB(\App()->DB);
        $search->setModelObject($this->getModel());
        $search->setObjectsPerPage($limit);
        $search->setRowMapper($this);
        $search->setSortingFields(['display_order' => 'ASC']);
        $search->setRequest(['disabled' => ['equal' => '0']]);
        return \App()->ObjectToArrayAdapterFactory->getObjectToArrayWrapperCollectionDecorator($search->getFoundObjectCollection());
    }

	public function changeImageStatusBySid($imageSid, $status)
	{
		if ($status == 'disabled')
		{
			\App()->DB->query("UPDATE `image_carousel_images` SET `disabled` = 1 WHERE `sid` = ?n", $imageSid);
		}
		elseif ($status == 'enabled')
		{
			\App()->DB->query("UPDATE `image_carousel_images` SET `disabled` = 0 WHERE `sid` = ?n", $imageSid);
		}
	}

	public function getImagesCount()
	{
		return \App()->DB->getSingleValue("SELECT COUNT(*) FROM `image_carousel_images`");
	}

	private function getImageFilenameBySid($imageSid)
	{
		return \App()->DB->getSingleValue("SELECT `image_filename` FROM `image_carousel_images` WHERE `sid` = ?n", $imageSid);
	}

	public function deleteImageBySid($imageSid)
	{
        $carouselImageInfo = $this->getImageInfoBySid($imageSid);
        $carouselImage = $this->createCarouselImage($carouselImageInfo);
        $carouselImage->setSID($imageSid);
        $carouselImage->getProperty('image')->type->delete();
		\App()->DB->query("DELETE FROM `image_carousel_images` WHERE `sid` = ?n", $imageSid);
	}

	public function getImageInfoBySid($imageSid)
	{
		$result = \App()->DB->query("SELECT * FROM `image_carousel_images` WHERE `sid` = ?n", $imageSid);
		return array_pop($result);
	}
}
