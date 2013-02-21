<?php

/*
 * This file is part of phpDNSAdmin.
 * (c) 2012 Matthias Lohr - http://phpdnsadmin.sourceforge.net/
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
 * @subpackage ResourceRecords
 * @author Matthias Lohr <matthias@lohr.me>
 */

/**
 * @package phpDNSAdmin
 * @subpackage ResourceRecords
 * @author Matthias Lohr <matthias@lohr.me>
 */
class AfsdbRecord extends ResourceRecord {

	public static function listFields() {
		return array(
			'subtype'  => 'UInt16',
			'hostname' => 'Hostname'
		);
	}
}

?>