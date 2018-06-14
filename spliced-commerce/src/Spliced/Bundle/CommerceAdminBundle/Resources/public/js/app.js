/**
 * General Application Javascript Events and Functions
 */

if(window.location.href.match(/backend_dev\.php/)){
	var XHR_BASE_URL = '/backend_dev.php';
} else {
	var XHR_BASE_URL = '';
}
var blockUiMessage = '<h1>Just a moment...</h1>'; 

$(document).ready(function(){
	
	
	$('a[data-toggle=tooltip]').tooltip();
	
		/**
		 * Batch Price Update Calc
		 */
		$('a.product-price-batch-update-calc').click(function(e){
			e.preventDefault();
			var $source = $(this).attr('data-source'),
				$target = $(this).attr('data-target'),
				$calc = +$(this).attr('data-calc'),
				$round = $("#calc-option-round").is(':checked'),
				$sourcePrice = parseFloat($("#"+$source).val()),
				$targetPrice = parseFloat($("#"+$target).val());
			
			$calc = $calc/100;

			$addToPrice = $sourcePrice * $calc;
			$newPrice = $sourcePrice + $addToPrice;
			
			if($round){
				$newPrice = Math.ceil($newPrice) - .01;
			}
			
			$("#"+$target).val($newPrice);
		});
		
		/**
		 * Batch Price Update Calc - Batch
		 */
		$('a.product-price-batch-update-calc-batch').click(function(e){
			e.preventDefault();
			
			$calc = $(this).attr('data-calc');
			$("a.product-price-batch-update-calc[data-calc=\""+$calc+"\"]").each(function(i, e){
				$(e).click();
				var $source = $(this).attr('data-source'),
				$target = $(this).attr('data-target'),
				$calc = +$(this).attr('data-calc'),
				$round = $("#calc-option-round").is(':checked'),
				$sourcePrice = parseFloat($("#"+$source).val()),
				$targetPrice = parseFloat($("#"+$target).val());
			
				$calc = $calc/100;
	
				$addToPrice = $sourcePrice * $calc;
				$newPrice = $sourcePrice + $addToPrice;
				
				if($round){
					$newPrice = Math.ceil($newPrice) - .01;
				}
				
				$("#"+$target).val($newPrice);
			});
		});
		
		
		/**
		 *  List Batch Action Default Change Event
		 */
		$('select.list-batch-action').change(function(e){
			if($(this).val().toLowerCase() == 'delete'){
				$('.list-batch-action-button').removeClass('btn-primary').addClass('btn-danger');
			} else {
				$('.list-batch-action-button').removeClass('btn-danger').addClass('btn-primary');
			}
		});
		
		
		/**
		 * Check All Checkboxes in table
		 */
		$('input.check-all[type=checkbox]').live('change', function(e){
			
			$target = $(this).attr('data-target');
			
			if(!$target || $target == undefined){
				console.log('No Target Set For All Checkbox Selection');
				$(this).removeAttr('checked');
				return;
			}
				
			if($(this).is(':checked')){
				$($target).find('input[type=checkbox]').attr('checked','checked');
			} else {
				$($target).find('input[type=checkbox]').removeAttr('checked');
			}
		});
		
});
