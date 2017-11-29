<?php

namespace Combodo\Formation\Brick;


use Combodo\iTop\DesignElement;

class ImageBrick extends \Combodo\iTop\Portal\Brick\PortalBrick
{
    const DEFAULT_PAGE_TEMPLATE_PATH = 'sample-portal-dev-new-brick/layout.html.twig';
    const DEFAULT_TILE_TEMPLATE_PATH = 'sample-portal-dev-new-brick/tile.html.twig';
    const DEFAULT_TILE_CONTROLLER_ACTION = 'Combodo\\Formation\\Controller\\ImageBrickController::TileAction';

    protected $sUrl;

    static $sRouteName = 'p_image_brick';

    public function __construct()
    {
        parent::__construct();
        $this->sUrl = '';
    }

    public function LoadFromXml(DesignElement $oMDElement)
    {
        parent::LoadFromXml($oMDElement);

        // Checking specific elements
        foreach ($oMDElement->GetNodes('./*') as $oBrickSubNode)
        {
            switch ($oBrickSubNode->nodeName)
            {
                case 'url':
                    $sUrl = $oBrickSubNode->GetText();
                    $this->sUrl = $sUrl;
                    break;
            }
        }

        return $this;
    }

    public function GetUrl()
    {
        return $this->sUrl;
    }

}