<script>

function RouteList( table, events )
{
	this.events = events;

	if (typeof table == 'object') {
		return this.Initialize(table);
	}

	this.table = table;

	if (typeof document.getElementById(table) == undefined) {
		alert('Could Not Find Route List');
		return false;
	}
	
	this.Initialize(table);
}

RouteList.prototype.Initialize = function(table)
{
	var self = this;
	if (typeof table == 'object') {
		this.table = table;
	} else {
		this.table = document.getElementById(table);
	}

	this.select_all = document.getElementById('select-all-routes');

	if (this.select_all) {
		this.select_all.addEventListener('change', function(e){
			self.SetAllCheckboxes(e, this);
		});
	}

}


RouteList.prototype.SetAllCheckboxes = function(event, input)
{
	var checkboxes = this.table.querySelectorAll('input[type="checkbox"]');

	for(var i =0; i < checkboxes.length; i++) {
		if (checkboxes[i].id == 'select-all-routes') {
			continue;
		}
		if (input.checked) {
			checkboxes[i].checked = 'checked';
		} else {
			checkboxes[i].checked = '';
		}
	}
}



</script>