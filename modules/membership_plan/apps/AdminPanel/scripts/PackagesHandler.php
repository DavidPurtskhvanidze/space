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


namespace modules\membership_plan\apps\AdminPanel\scripts;

class PackagesHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'membership_plan';
	protected $functionName = 'packages';

	private $requestReflector;
	private $message = null;
	private $error = null;

	public function respond()
	{
		$this->requestReflector = \App()->ObjectMother->createRequestReflector();

		$this->mapActionToMethod(
			array(
				'delete'				=> array($this, 'deletePackage'),
				'aplly_to_listings'		=> array($this, 'applyToListings'),
				'aplly_to_subdomains'		=> array($this, 'applyToSubDomains'),
				'aplly_to_contracts'	=> array($this, 'applyToContracts'),
			)
		);

		$uri = $this->requestReflector->get('return_uri');
		$uri .= $this->getReturnBackUriParams($uri);
		throw new \lib\Http\RedirectException(\App()->SystemSettings['SiteUrl'].$uri);
	}

	private function mapActionToMethod($map)
	{
		$action = $this->requestReflector->get('action');
		$packageSid = $this->requestReflector->get('package_sid');

		if (is_null($action) || !isset($map[$action]) || is_null($packageSid))
		{
			return;
		}

		$callback = $map[$action];
		call_user_func_array($callback, array($packageSid));
	}
	private function getReturnBackUriParams($uri)
	{
		$params = array();
		if ($this->error)
		{
			$params[] = "error={$this->error}";
		}

		if (!empty($params))
		{
			return (false !== strpos($uri, '?')) ? ('&' . implode('&', $params)) : ('?' . implode('&', $params));
		}

		return '';
	}

	private function deletePackage($packageSid)
	{
		$canPerform = true;
		$validators = new \core\ExtensionPoint('modules\membership_plan\apps\AdminPanel\IDeleteListingPackageValidator');
		foreach ($validators as $validator)
		{
			$validator->setListingPackage($packageSid);
			$canPerform &= $validator->isValid();
		}
		if ($canPerform)
		{
			\App()->PackageManager->deletePackageBySID($packageSid);
			\App()->SuccessMessages->addMessage('PACKAGE_DELETED');
		}
	}

	private function applyToListings($packageSid)
	{
		$action = \App()->ObjectMother->createApplyPackageChangesToListingsAction(\App()->PackageManager->getPackageBySID($packageSid));
        $action->perform();
		\App()->SuccessMessages->addMessage('PACKAGE_APPLIED_TO_LISTINGS');

		$afterApplyActions = new \core\ExtensionPoint('modules\membership_plan\IAfterApplyPackageToListings');
        foreach ($afterApplyActions as $action)
        {
            $action->setPackageSid($packageSid);
            $action->perform();
        }
	}

	private function applyToSubDomains($packageSid)
	{
		$action = \App()->ObjectMother->createApplyPackageChangesToSubDomainAction(\App()->PackageManager->getPackageBySID($packageSid));
        $action->perform();
		\App()->SuccessMessages->addMessage('PACKAGE_APPLIED_TO_SUBDOMAINS');

		$afterApplyActions = new \core\ExtensionPoint('modules\membership_plan\IAfterApplyPackageToSubDomains');
        foreach ($afterApplyActions as $action)
        {
            $action->setPackageSid($packageSid);
            $action->perform();
        }
	}

	private function applyToContracts($packageSid)
	{
		$action = \App()->ObjectMother->createApplyPackageChangesToContractsAction(\App()->PackageManager->getPackageBySID($packageSid));
		$action->perform();
		\App()->SuccessMessages->addMessage('PACKAGE_APPLIED_TO_CONTRACTS');

		$afterApplyActions = new \core\ExtensionPoint('modules\membership_plan\IAfterApplyPackageToContracts');
		foreach ($afterApplyActions as $action)
		{
			$action->setPackageSid($packageSid);
			$action->perform();
		}
	}
}
