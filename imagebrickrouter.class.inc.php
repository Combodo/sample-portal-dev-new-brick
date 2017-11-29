<?php

// Copyright (C) 2010-2017 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>

namespace Combodo\Formation\Router;

use Combodo\iTop\Portal\Router\AbstractRouter;
use Silex\Application;

class ImageBrickRouter extends AbstractRouter
{
	static $aRoutes = array(
		array('pattern' => '/image/{sBrickId}',
			'callback' => 'Combodo\\Formation\\Controller\\ImageBrickController::DisplayAction',
			'bind' => 'p_image_brick',
			'values' => array()
		),
	);

}
