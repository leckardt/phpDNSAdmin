<?php

/*
 * This file is part of phpDNSAdmin.
 * (c) 2010 Matthias Lohr - http://phpdnsadmin.sourceforge.net/
 *
 * phpDNSAdmin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * phpDNSAdmin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with phpDNSAdmin. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * @package phpDNSAdmin
 * @subpackage Routers
 * @author Matthias Lohr <mail@matthias-lohr.net>
 */

/**
 * @package phpDNSAdmin
 * @subpackage Routers
 * @author Matthias Lohr <mail@matthias-lohr.net>
 */
class ServerRouter extends RequestRouter {

	/** @var ZoneModule according zone module */
	private $zoneModule = null;

	public function __construct(ZoneModule $zoneModule) {
		$this->zoneModule = $zoneModule;
	}

	public function __default() {
		return $this->zones();
	}

	public function rrtypes() {
		$features = $this->zoneModule->getFeatures();
		$result = new stdClass();
		$result->rrtypes = array();
		foreach (array_values(array_intersect(ResourceRecord::listTypes(), $features['rrtypes'])) as $value) {
			$className = ResourceRecord::getClassByType($value);
			$rrtype = new stdClass();
			$rrtype->type = $value;
			$rrtype->fields = call_user_func(array($className, 'listFields'));

			$result->rrtypes[] = $rrtype;
		}
		return $result;
	}

	public function views() {
		$result = new stdClass();
		if ($this->zoneModule->hasViews()) {
			$result->success = true;
			$result->views = $this->zoneModule->listViews();
		}
		else {
			$result->success = false;
		}
		return $result;
	}

	public function zones($zonename = null) {
		if ($zonename === null) {
			// list zones
			$result = new stdClass();
			$result->zones = array();
			foreach ($this->zoneModule->listZones() as $zone) {
				$tmpzone = new stdClass();
				$tmpzone->id = $zone->getName();
				$tmpzone->name = $zone->getName();
				// check for views
				if ($this->zoneModule->hasViews()) {
					$tmpzone->views = $this->zoneModule->listViews();
				}
				$result->zones[$zone->getName()] = $tmpzone;
				ksort($result->zones,SORT_STRING);
			}
			return $result;
		}
		else {
			$zone = new Zone($zonename, $this->zoneModule);
			if ($this->endOfTracking()) {
				if ($this->getRequestType() === 'PUT') {
					$result = new stdClass();
					$result->success = $this->zoneModule->zoneCreate($zone);
					return $result;
				}
				elseif ($this->getRequestType() === 'DELETE') {
					$this->zoneModule->zoneDelete($zone);
					$result = new stdClass();
					$result->success = true;
					return $result;
				}
				else {
					return new stdClass();
				}
			}
			else {
				$zone = new Zone($zonename, $this->zoneModule);
				$zoneRouter = new ZoneRouter($zone);
				return $zoneRouter->track($this->routingPath);
			}
		}
	}
}

?>