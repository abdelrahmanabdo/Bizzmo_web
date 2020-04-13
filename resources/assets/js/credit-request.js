var colm1 = $(".incomestatementitemy1")
var colm2 = $(".incomestatementitemy2")
var colm3 = $(".incomestatementitemy3")

colm1.on("change", function () {
	var calcs = $(".incomestatementitemy1_calc")
	updateProfits(colm1, calcs);
})
colm2.on("change", function () {
	var calcs = $(".incomestatementitemy2_calc")
	updateProfits(colm2, calcs);
})
colm3.on("change", function () {
	var calcs = $(".incomestatementitemy3_calc")
	updateProfits(colm3, calcs);
})

var colb1 = $(".balancesheetitemy1")
var colb2 = $(".balancesheetitemy2")
var colb3 = $(".balancesheetitemy3")

colb1.on("change", function () {
	var calcs = $(".balancesheetitemy1")
	updateSheet(1, calcs);
})
colb2.on("change", function () {
	var calcs = $(".balancesheetitemy2")
	updateSheet(2, calcs);
})
colb3.on("change", function () {
	var calcs = $(".balancesheetitemy3")
	updateSheet(3, calcs);
})

function updateSheet(col, calcs) {
	var total = 0.00;
	calcs.each(function(index, value) {
		if (isNumber($(value).val().trim())) {
			total = total + parseFloat($(value).val());
		}		
	});
	switch (col) {
		case 1:
			$('#balancesheetitemy1balance').text(total.toFixed(2));
			$('#balancesheetitemy1value').val(total.toFixed(2));
			break;
		case 2:
			$('#balancesheetitemy2balance').text(total.toFixed(2));
			$('#balancesheetitemy2value').val(total.toFixed(2));
			break;
		case 3:
			$('#balancesheetitemy3balance').text(total.toFixed(2));
			$('#balancesheetitemy3value').val(total.toFixed(2));
			break;
	}
}

function updateProfits(values, calcs) {
	var grossProfit = $(calcs.get(0))
	var grossProfitPercentage = $(calcs.get(1))
	var operatingProfit = $(calcs.get(2))
	var operatingProfitPercentage = $(calcs.get(3))
	var netProfit = $(calcs.get(4))
	var netProfitPercentage = $(calcs.get(5))

	// Gross Profits 
	var revenues = 0.00;
	if ($(values.get(0)).val().trim() != '') {	  
		revenues = parseFloat($(values.get(0)).val().replace(/,/g, ''))
	}
	var costs = 0.00;
	if ($(values.get(1)).val().trim() != '') { 	  
		costs = parseFloat($(values.get(1)).val().replace(/,/g, ''))
	}
	var grossProfitValue = revenues + costs
	var grossProfitPercentageValue = 100 * (grossProfitValue / revenues)
	if (!isNaN(grossProfitValue)) {
		grossProfit.val(parseFloat(grossProfitValue).toFixed(2))
		grossProfitPercentage.val(parseFloat(grossProfitPercentageValue).toFixed(2))
		grossProfit.parent().find('span:first').text(parseFloat(grossProfitValue).toFixed(2));
		grossProfitPercentage.parent().find('span:first').text(parseFloat(grossProfitPercentageValue).toFixed(2));
	} else {
		grossProfit.val("")
		grossProfitPercentage.val("")
		grossProfit.parent().find('span:first').text("");
		grossProfitPercentage.parent().find('span:first').text("");
	}

	// Operational Profits
	var salesExpanses = 0.00;
	if ($(values.get(2)).val().trim() != '') {
		salesExpanses = parseFloat($(values.get(2)).val().replace(/,/g, ''))
	}
	var operatingProfitValue = salesExpanses + grossProfitValue
	var operatingProfitPercentageValue = 100 * (operatingProfitValue / revenues)
	if (!isNaN(operatingProfitValue)) {
		operatingProfit.val(parseFloat(operatingProfitValue).toFixed(2))
		operatingProfitPercentage.val(parseFloat(operatingProfitPercentageValue).toFixed(2))
		operatingProfit.parent().find('span:first').text(parseFloat(operatingProfitValue).toFixed(2));
		operatingProfitPercentage.parent().find('span:first').text(parseFloat(operatingProfitPercentageValue).toFixed(2));
	} else {
		operatingProfit.val("")
		operatingProfitPercentage.val("")
		operatingProfit.parent().find('span:first').text("");
		operatingProfitPercentage.parent().find('span:first').text("");
	}

	// Net Profits
	var creditinterest = 0.00;
	if ($(values.get(3)).val().trim() != '') {
		creditinterest = parseFloat($(values.get(3)).val().replace(/,/g, ''))
	}
	var debitinterest = 0.00;
	if ($(values.get(4)).val().trim() != '') {
		debitinterest = parseFloat($(values.get(4)).val().replace(/,/g, ''))
	}
	var otherIncome = 0.00;
	if ($(values.get(5)).val().trim() != '') {
		otherIncome = parseFloat($(values.get(5)).val().replace(/,/g, ''))
	}
	var otherExpanses = 0.00;
	if ($(values.get(6)).val().trim() != '') {
		otherExpanses = parseFloat($(values.get(6)).val().replace(/,/g, ''))
	}
	var netProfitValue = creditinterest + debitinterest + otherIncome + otherExpanses + operatingProfitValue
	var netProfitPercentageValue = 100 * netProfitValue / revenues
	if (!isNaN(netProfitValue)) {
	netProfit.val(netProfitValue)
	netProfitPercentage.val(parseFloat(netProfitPercentageValue).toFixed(2))
		netProfit.parent().find('span:first').text(parseFloat(netProfitValue).toFixed(2));
		netProfitPercentage.parent().find('span:first').text(parseFloat(netProfitPercentageValue).toFixed(2));
	} else {
	netProfit.val("")
	netProfitPercentage.val("")
		netProfit.parent().find('span:first').text("");
		netProfitPercentage.parent().find('span:first').text("");
	}
}