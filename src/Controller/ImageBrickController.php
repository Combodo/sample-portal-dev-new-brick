<?php

namespace Combodo\iTop\Portal\Controller;

use Combodo\iTop\Portal\Brick\BrickCollection;
use Combodo\iTop\Portal\Brick\BrickNotFoundException;
use Combodo\iTop\Portal\Helper\ScopeValidatorHelper;
use CoreException;
use DBObjectSearch;
use DBObjectSet;
use MissingQueryArgument;
use MySQLException;
use MySQLHasGoneAwayException;
use OQLException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UserRights;

class ImageBrickController extends BrickController
{
	/**
	 * Constructor where we can inject services.
	 *
	 * @param BrickCollection $oBrickCollection
	 * @param ScopeValidatorHelper $oScopeValidatorHelper
	 */
	public function __construct(
		protected BrickCollection $oBrickCollection,
		protected ScopeValidatorHelper $oScopeValidatorHelper
	) {

	}

	/**
	 * Method for the brick's page
	 *
	 * @param Request $oRequest
	 * @param string $sBrickId
	 *
	 * @return Response
	 * @throws BrickNotFoundException
	 */
	public function DisplayAction(Request $oRequest, $sBrickId)
	{
		// Retrieving brick instance
		$oBrick = $this->oBrickCollection->GetBrickById($sBrickId);

		// Do your logic here with DB calls, Objects manipulation, ...
		$sImageUrl = $oBrick->GetUrl();

		// Structure the data you will pass to the TWIG (HTML templating)
		$aData = ['sImageUrl' => $sImageUrl, 'oBrick' => $oBrick];

		// Finally, return a Response object
		return $this->render($oBrick->GetTemplatePath('page'), $aData);
	}

	/**
	 * Method for the brick's tile on home page
	 *
	 * @param Request $oRequest
	 * @param string $sBrickId
	 *
	 * @return Response
	 * @throws BrickNotFoundException
	 * @throws CoreException
	 * @throws MissingQueryArgument
	 * @throws MySQLException
	 * @throws MySQLHasGoneAwayException
	 * @throws OQLException
	 */
	public function TileAction(Request $oRequest, $sBrickId)
	{
		// Retrieving brick instance
		$oBrick = $this->oBrickCollection->GetBrickById($sBrickId);

		// Do your logic here with DB calls, Objects manipulation, ...
		// In this example we will display the brick image along with the current number of Tickets
		// - Retrieving brick property
		$sImageUrl = $oBrick->GetUrl();

		// - Preparing a OQL
		$oSearch = DBObjectSearch::FromOQL('SELECT Ticket');

		// - Using portal scope / security service to restrict the OQL results
		$oScopeQuery = $this->oScopeValidatorHelper->GetScopeFilterForProfiles(UserRights::ListProfiles(), 'Ticket', UR_ACTION_READ);
		if ($oScopeQuery !== null) {
			$oSearch = $oSearch->Intersect($oScopeQuery);
			// - Allowing all data if necessary
			if ($oScopeQuery->IsAllDataAllowed()) {
				$oSearch->AllowAllData();
			}
		}

		// - Preparing a set to fetch results
		$oSet = new DBObjectSet($oSearch);
		$iTicketCount = $oSet->Count();

		// Preparing data to pass to the templating service
		$aData = [
			'sImageUrl' => $sImageUrl,
			'sTimestamp' => date('d m Y'),
			'iTicketCount' => $iTicketCount,
			'oBrick' => $oBrick,
		];

		return $this->render($oBrick->GetTemplatePath('tile'), $aData);
	}
}
