$( document ).ready(function()
{
	function calc_plan_fields()
	{
		var n = Number($("#vehicle_select").val());
		var k = Number($("#plan_extended").text());
	
		var plan_price = Number($("#plan_price").text());
		var plan_extended_price = (n-1)*k;

		var total = plan_price + plan_extended_price;

		$("#plan_extended_price").text(plan_extended_price.toFixed(2));
		$("#total").text(total.toFixed(2));
		
		$("#amount").val(total);
	}
	calc_plan_fields();

	$("#vehicle_select").change(function()
	{
		calc_plan_fields();
	});
});