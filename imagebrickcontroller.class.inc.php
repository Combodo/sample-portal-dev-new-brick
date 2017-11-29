<?php

namespace Combodo\Formation\Controller;


use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UserRights;
use DBObjectSearch;
use DBObjectSet;
use Combodo\iTop\Portal\Helper\ApplicationHelper;

class ImageBrickController extends \Combodo\iTop\Portal\Controller\AbstractController
{
    /**
     * Method for the brick's page
     *
     * @param Request $oRequest
     * @param Application $oApp
     * @param $sBrickId
     *
     * @return Response
     */
    public function DisplayAction(Request $oRequest, Application $oApp, $sBrickId)
    {
        // Retrieving brick instance
        $oBrick = ApplicationHelper::GetLoadedBrickFromId($oApp, $sBrickId);

        // Do your logic here with DB calls, Objects manipulation, ...
        $sImageUrl = $oBrick->GetUrl();

        // Structure the data you will pass to the TWIG (HTML templating)
        $aData = array('sImageUrl' => $sImageUrl);

        // Finally, return a Response object
        return $oApp['twig']->render($oBrick->GetPageTemplatePath(), $aData);
    }

    /**
     * Method for the brick's tile on home page
     *
     * @param Request $oRequest
     * @param Application $oApp
     * @param $sBrickId
     *
     * @return Response
     */
    public function TileAction(Request $oRequest, Application $oApp, $sBrickId)
    {
        // Retrieving brick instance
        $oBrick = ApplicationHelper::GetLoadedBrickFromId($oApp, $sBrickId);

        // Do your logic here with DB calls, Objects manipulation, ...
        // In this example we will display the brick image along with the current number of Tickets
        // - Retrieving brick property
        $sImageUrl = $oBrick->GetUrl();

        // - Preparing a OQL
        $oSearch = DBObjectSearch::FromOQL('SELECT Ticket');

        // - Using portal scope / security service to restrict the OQL results
        $oScopeQuery = $oApp['scope_validator']->GetScopeFilterForProfiles(UserRights::ListProfiles(), 'Ticket', UR_ACTION_READ);
        if ($oScopeQuery !== null)
        {
            $oSearch = $oSearch->Intersect($oScopeQuery);
            // - Allowing all data if necessary
            if ($oScopeQuery->IsAllDataAllowed())
            {
                $oSearch->AllowAllData();
            }
        }

        // - Preparing a set to fetch results
        $oSet = new DBObjectSet($oSearch);
        $iTicketCount = $oSet->Count();

        // Preparing data to pass to the templating service
        $aData = array(
            'sImageUrl' => $sImageUrl,
            'sTimestamp' => date('d m Y'),
            'iTicketCount' => $iTicketCount,
            'brick' => $oBrick,
        );

        return $oApp['twig']->render($oBrick->GetTileTemplatePath(), $aData);
    }
}