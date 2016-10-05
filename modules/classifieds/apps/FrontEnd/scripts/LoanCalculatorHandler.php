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


namespace modules\classifieds\apps\FrontEnd\scripts;

class LoanCalculatorHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Loan Calculator';
	protected $moduleName = 'classifieds';
	protected $functionName = 'loan_calculator';
	protected $rawOutput = true;

	public function respond()
	{
		$amount = 50000;

		if (isset ($_REQUEST['listing_id']))
		{
			$listing = \App()->ListingManager->getObjectBySID($_REQUEST['listing_id']);
			if (!empty($listing) && $listing->propertyIsSet('Price'))
			{
				$amount = $listing->getPropertyValue('Price');
			}
		}

		$template_processor = \App()->getTemplateProcessor();
		$template_processor->assign('amount', str_replace(\App()->I18N->getContext()->getThousandsSeparator(), "", \App()->I18N->getFloat($amount)));
		$template_processor->assign('decimalSeparator', \App()->I18N->getContext()->getDecimalPoint());
		$template_processor->display('loan_calculator.tpl');
	}
}
