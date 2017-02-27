$(function () {
    $(document).on('rex:ready', function() {
		// init quick nav
		$('#qsearch').keyup(function(){	
			var current_query = $('#qsearch').val();
			if (current_query !== "") {
				$(".quicknavi.list-group li").hide();
				$(".quicknavi.list-group li").each(function(){
					var current_keyword = $(this).text();
					 var upercase = current_query.substr(0,1).toUpperCase() + current_query.substr(1);
					if ((current_keyword.indexOf(current_query) >=0) ||  (current_keyword.indexOf(upercase) >=0)) {
					$(this).show();    	 	
					};
				
				});    	
			} else {
				$(".quicknavi.list-group li").show();
			};
		});

		// meta info panel starts collapse
		//$('#rex-js-main-sidebar .panel .panel-heading').removeClass('collapsed');
		//$('#rex-js-main-sidebar .panel .panel-collapse').addClass('in');

		/*$('body').on('click', '.addme', function(e) {
			alert("!");
		});*/

		// panel toggler
		$('select.rexx-panel-toggler').each(function() {
			var options = $(this).find('option');

			$(this).on('change', function() {
				var values = $.map(options ,function(option) {
					$('[data-rexx-panel="' + option.value + '"]').hide();
				});

				$('[data-rexx-panel="' + this.value + '"]').show();
			}); 

			$(this).change();
		});
    });
});	

