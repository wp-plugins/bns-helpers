/**
 * BNS Helpers JavaScript / jQuery Scripts
 *
 * @package     BNS_Helpers
 * @since       0.1
 * @author      Edward Caissie <edward.caissie@gmail.com>
 * @copyright   Copyright (c) 2015, Edward Caissie
 *
 * This file is part of BNS Helpers plugin
 *
 * Copyright 2015  Edward Caissie  (email : edward.caissie@gmail.com)
 *
 * BNS Helpers is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, version 2, as published by the
 * Free Software Foundation.
 *
 * You may NOT assume that you can use any other version of the GPL.
 *
 * BNS Helpers is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to:
 *
 *      Free Software Foundation, Inc.
 *      51 Franklin St, Fifth Floor
 *      Boston, MA  02110-1301  USA
 *
 * @version    0.2
 * @date       January 2015
 * Added "Tool Tip Scripts"
 */

jQuery( document ).ready( function ( $ ) {
	/** Note: $() will work as an alias for jQuery() inside of this function */

	/** Tool Tip Scripts */
	/** passed from `wp_localize_script` */
	var tool_tip_text;

	$( 'sup.bns-tool-tip' ).tooltip( {
		content     : tool_tip_text,
		tooltipClass: 'bns-tool-tip-content'
	} );

} );