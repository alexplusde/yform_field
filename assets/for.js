/**
 *  This file is part of the REDAXO-AddOn "yform_fields".
 *
 *  @author      FriendsOfREDAXO @ GitHub <https://github.com/FriendsOfREDAXO/focuspoint>
 *  @version     0.1
 *  @copyright   FriendsOfREDAXO <https://friendsofredaxo.github.io/>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 *
 *  ------------------------------------------------------------------------------------------------
 *
 *  all JS-code and data is capsulated in an object called forjs.
 *
 *  - try to re-use code whenever it is possible
 *  - if dedicated data and functions are necessary feel free to establish an internal sub-object
 *      within forjs
 *  - label any data-object or function with the assosiated php-element (value/action/validate)
 *      to ease maintenance (first line of the comment: "Scope: ....")
 */

var forjs = {

    /*  Scope:  common
     *
     *  Validates the  target: does ist exist?
     *  Good for turning an ID-string into a node.
     *
     *  @param  HTMLElement|string  either an HTMIElement(DOM-Node) or a HTML-ID
     *  @return HTMLElement|null
     */
    asNode: function( target ) {
    	if( target instanceof HTMLElement) return target;
    	return document.getElementById( target);
    },

    /*  Scope:  common
     *
     *  Checks whether an input is given (not empty) and valid
     *
     *  @param  HTMLElement
     *  @param  string  the alert-message in case of invalid field-content
     *  @return boolean
     */
    checkValidity: function( target, errormsg='' ) {
        if( target && target.value && target.reportValidity() ) return true;
        if( errormsg ) alert( errormsg );
        return false;
    },

    /*  Scope:  rex_yform_value_for_extern
     *
     *  Activated by clicking on a link-button prepending a textfield with a link-content (URL).
     *  Validate the field-content and if valid: open the link in a new browser-Windows
     *
     *  @param  string  a HTML-ID referencing the input-element with the URL
     *  @param  string|null  the alert-message in case of invalid field-content
    */
    openlink: function ( targetName, errormsg='' ){
        let target = this.asNode( targetName );
        if( !this.checkValidity( target, errormsg ) ) return;
        window.open(target.value);
    },

    /*  Scope:  rex_yform_value_for_extern
     *
     *  Activated by clicking on a Mail-button prepending a textfield with a mail-address-content.
     *  Validate the field-content and if valid: open a mail-client-window
     *
     *  @param  string  a HTML-ID referencing the input-Element with the URL
     *  @param  string|null  the alert-message in case of invalid field-content
     */
    openmail: function ( targetName, errormsg='' ){
        let target = this.asNode( targetName );
        if( !this.checkValidity( target, errormsg ) ) return;
        document.location='mailto:'+target.value;
    },


// End of object forjs
}
