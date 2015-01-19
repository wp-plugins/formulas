/* formulas.js */

jQuery(document).ready( function() {
	
		jQuery('.formulas-cr .formulas-btn').click( function(e) {
			
			var crForm = jQuery(this).parent();
			var bore = jQuery('.formulas-cr-bore', crForm).val();
			var stroke = jQuery('.formulas-cr-stroke', crForm).val();
			var chamber = jQuery('.formulas-cr-chamber', crForm).val();
			var gt = jQuery('.formulas-cr-gt', crForm).val();
			var gbd = jQuery('.formulas-cr-gbd', crForm).val();
			var piston = jQuery('.formulas-cr-piston', crForm).val();
			var dh = jQuery('.formulas-cr-dh', crForm).val();
			var numerator = (Math.PI/4)* Math.pow( parseFloat( bore ),2) * parseFloat( stroke );
			var denominator = (parseFloat( chamber ) *  0.061024) + formulas_vol(gbd , gt) + formulas_vol( bore, dh) + (parseFloat( piston ) *  0.061024);
			
			jQuery('.formulas-result .formulas-result-1', crForm).html( (Math.round( ( 1+ ( numerator/denominator) )*100 )/100 ) + ":1" );
			jQuery('.formulas-result', crForm).show();
		});
		
		jQuery('.formulas-cr .formulas-input').change( function(e) {
			// Any time there is a change, we need to clear the compression ratio
			var crForm = jQuery(this).closest('form');
			jQuery('.formulas-result', crForm).hide();
		});
		
		
		jQuery('.formulas-ed .formulas-btn').click( function(e) {
			
			var crForm = jQuery(this).parent();
			var bore = jQuery('.formulas-ed-bore', crForm).val();
			var stroke = jQuery('.formulas-ed-stroke', crForm).val();
			var cylinders = jQuery('.formulas-ed-cylinders', crForm).val();
			var displacement = (Math.PI/4)* Math.pow( parseFloat( bore ),2) * parseFloat( stroke ) * parseFloat( cylinders );
			jQuery('.formulas-result .formulas-result-1', crForm).html( Math.round( displacement) );
			jQuery('.formulas-result', crForm).show();
		});
		
		jQuery('.formulas-ed .formulas-input').change( function(e) {
			// Any time there is a change, we need to clear the compression ratio
			var crForm = jQuery(this).closest('form');
			jQuery('.formulas-result', crForm).hide();
		});
		
});

function formulas_vol(diameter, height)
{
	return Math.PI * Math.pow(( parseFloat(diameter) /2),2) * parseFloat(height);
}