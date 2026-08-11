<?php

namespace Combodo\iTop\Portal\Brick;

use Combodo\iTop\DesignElement;
use Combodo\iTop\Portal\Service\TemplatesProvider\TemplateDefinitionDto;
use Combodo\iTop\Portal\Service\TemplatesProvider\TemplatesRegister;

class ImageBrick extends PortalBrick
{
	public const DEFAULT_TILE_CONTROLLER_ACTION = 'Combodo\\iTop\\Portal\\Controller\\ImageBrickController::TileAction';

	public static $sRouteName = 'p_image_brick';

	public const DEFAULT_WIDTH = "500px";

	protected string $sUrl = '';

	public static function RegisterTemplates(TemplatesRegister $oTemplatesRegister): void
	{
		parent::RegisterTemplates($oTemplatesRegister);
		$oTemplatesRegister->RegisterTemplates(
			self::class,
			TemplateDefinitionDto::Create('page', 'sample-portal-dev-new-brick/templates/Brick/layout.html.twig'),
			TemplateDefinitionDto::Create('tile', 'sample-portal-dev-new-brick/templates/Brick/tile.html.twig'),
		);
	}

	public function LoadFromXml(DesignElement $oMDElement)
	{
		parent::LoadFromXml($oMDElement);

		// Checking specific elements
		foreach ($oMDElement->GetNodes('./*') as $oBrickSubNode) {
			switch ($oBrickSubNode->nodeName) {
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
