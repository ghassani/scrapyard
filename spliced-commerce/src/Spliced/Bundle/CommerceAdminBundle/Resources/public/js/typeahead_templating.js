var TypeaheadTemplateEngine = {
    compile: function(template) {
        return {
            render: function(context) {
                return template.replace(/\{\{(\w+)\}\}/g,function(match, p1) {
			         return jQuery('<div/>').text(context[p1] || '').html();
			    });
            }
        };
    }
};