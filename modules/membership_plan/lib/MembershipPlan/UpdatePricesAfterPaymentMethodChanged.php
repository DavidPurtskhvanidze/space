<?php
/**
 *
 *    Module: membership_plan v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: membership_plan-7.5.0-1
 *    Tag: tags/7.5.0-1@19798, 2016-06-17 13:20:05
 *
 *    This file is part of the 'membership_plan' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\membership_plan\lib\MembershipPlan;

class UpdatePricesAfterPaymentMethodChanged implements \modules\payment_system\lib\IAfterPaymentMethodChangedAction
{
	private $packagePriceProperties;

	public function perform()
	{
		$this->definePackagePriceProperties();
		$this->updatePricesInMembershipPlans();
		$this->updatePricesInMembershipPlanPackages();
		$this->updatePricesInListingPackages();
		$this->updatePricesInContractPackages();
	}

	private function updatePricesInContractPackages()
	{
		$priceProperties = $this->getPackagePriceProperties();

		$contractPackagesInfo = \App()->ContractPackagesManager->getAllPackagesInfo();
		$paymentMethod = \App()->PaymentSystemManager->getCurrentPaymentMethod();
		foreach ($contractPackagesInfo as $packageInfo)
		{
			$needToUpdatePackage = false;
			foreach ($priceProperties as $propertyId)
			{
				$price = isset($packageInfo[$propertyId]) ? $packageInfo[$propertyId] : null;
				if ($price > 0)
				{
					$convertedPrice = $paymentMethod->convertPrice($price);
					if ($price != $convertedPrice)
					{
						$packageInfo[$propertyId] = $convertedPrice;
						$needToUpdatePackage = true;
					}
				}
			}
			if ($needToUpdatePackage)
			{
				\App()->ContractPackagesManager->updateContractPackageExtraInfo($packageInfo);
			}
		}
	}

	private function updatePricesInMembershipPlans()
	{
		$paidMembershipPlanSids = \App()->MembershipPlanManager->getSearch(array('price' => array('more' => 0)))->getFoundObjectSidCollection();
		$paymentMethod = \App()->PaymentSystemManager->getCurrentPaymentMethod();
		foreach ($paidMembershipPlanSids as $membershipPlanSid)
		{
			$membershipPlan = \App()->MembershipPlanManager->getMembershipPlanForEditingBySID($membershipPlanSid);
			$price = $membershipPlan->getPropertyValue('price');
			$convertedPrice = $paymentMethod->convertPrice($price);
			if ($price != $convertedPrice)
			{
				$membershipPlan->setPropertyValue('price', $convertedPrice);
				\App()->MembershipPlanManager->saveMembershipPlan($membershipPlan);
			}
		}
	}

	private function updatePricesInListingPackages()
	{
		$listingPackagesInfo = \App()->ListingPackageManager->getAllPackagesInfo();
		$priceProperties = $this->getPackagePriceProperties();
		$paymentMethod = \App()->PaymentSystemManager->getCurrentPaymentMethod();
		foreach ($listingPackagesInfo as $packageInfo)
		{
			$needToUpdatePackage = false;
			foreach ($priceProperties as $propertyId)
			{
				$price = isset($packageInfo[$propertyId]) ? $packageInfo[$propertyId] : null;
				if ($price > 0)
				{
					$convertedPrice = $paymentMethod->convertPrice($price);
					if ($price != $convertedPrice)
					{
						$packageInfo[$propertyId] = $convertedPrice;
						$needToUpdatePackage = true;
					}
				}
			}
			if ($needToUpdatePackage)
			{
				\App()->ListingPackageManager->updatePackage($packageInfo['listing_sid'], $packageInfo);
			}
		}
	}

	private function updatePricesInMembershipPlanPackages()
	{
		$packages = \App()->PackageManager->getAllPackagesIterator();
		$packagePriceProperties = $this->getPackagePriceProperties();

		$paymentMethod = \App()->PaymentSystemManager->getCurrentPaymentMethod();
		/**
		 * @var \modules\membership_plan\lib\Package\Package $package
		 */
		foreach ($packages as $package)
		{
			$needToUpdatePackage = false;
			foreach ($packagePriceProperties as $propertyId)
			{
				$price = $package->getPropertyValue($propertyId);
				if ($price > 0)
				{
					$convertedPrice = $paymentMethod->convertPrice($price);
					if ($price != $convertedPrice)
					{
						$package->setPropertyValue($propertyId, $convertedPrice);
						$needToUpdatePackage = true;
					}
				}
			}
			if ($needToUpdatePackage)
			{
				\App()->PackageManager->savePackage($package);
			}
		}
	}

	private function definePackagePriceProperties()
	{
		$internalPriceType = 'transaction_money';
		$package = \App()->PackageManager->createPackage(null, 'ListingPackage', null, array());

		$priceProperties = array();
		$packageProperties = $package->getDetails()->getProperties();

		/**
		 * @var \lib\ORM\ObjectProperty $property
		 */
		foreach ($packageProperties as $property)
		{
			if ($property->getType() == $internalPriceType)
			{
				$priceProperties[] = $property->getID();
			}
		}
		$this->packagePriceProperties = $priceProperties;
	}

	private function getPackagePriceProperties()
	{
		return $this->packagePriceProperties;
	}
}
