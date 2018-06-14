<script>

function RouteForm( form_id, events )
{
	this.events = events;

	if (typeof form_id == 'object') {
		return this.Initialize(form_id);
	}

	this.form_id = form_id;

	if (typeof document.getElementById(form_id) == undefined) {
		alert('Could Not Find Route Form');
		return false;
	}
	
	this.Initialize(form_id);
}

RouteForm.prototype.Initialize = function(form_id)
{
	var self = this;
	if (typeof form_id == 'object') {
		this.form = form_id;
	} else {
		this.form = document.getElementById(form_id);
	}
	
	this.form.addEventListener('submit', function(e) {
		return self.OnSubmit(e);
	});

	this.type_changers = this.form.querySelectorAll('input[name="route:type"]');

	for (i = 0; i < this.type_changers.length; i++) {
		this.type_changers[i].addEventListener('change', function(e) {
			self.TypeChange(e, this);
		});
	}


}
RouteForm.prototype.OnSubmit = function(event)
{
	if (typeof this.events.OnSubmit == 'function') {
		return this.events.OnSubmit(event, this);
	}
	return true;
}

RouteForm.prototype.TypeChange = function(event, input)
{
	blocks = this.form.querySelectorAll('.row-route-variant');

	for (i = 0; i < blocks.length; i++) {
		blocks[i].style.display = 'none';
		if (typeof input != undefined) {
			for (c = 0; c < blocks[i].classList.length; c++) {
				if (('row-' + input.value) == blocks[i].classList[c]) {
					blocks[i].style.display = 'block';
				}
			}
		}
	} 

	if (typeof this.events.OnTypeChange == 'function') {
		this.events.OnTypeChange(event, input, this);
	}
}


</script>