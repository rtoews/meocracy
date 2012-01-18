  	$(function() {
   		$('input.datepicker').datepicker({
			beforeShow: customRange,
	    		changeMonth: true,
	    		changeYear: true,
	    		showOn: 'both',
	    		buttonImage: '/images/datepicker/ico.calendar.gif',
	    		buttonImageOnly: true,
	    		onSelect: function(dateText, picker) {
	                var field_prefix = get_field_prefix($(this).attr('id'));
	                var dateTextSplit = dateText.split(/\//);
	
	                $('#' + field_prefix + '-month').val(dateTextSplit[0]);
	                $('#' + field_prefix + '-day').val(dateTextSplit[1]);
	                $('#' + field_prefix + '-year').val(dateTextSplit[2]);
	            }
   		});
  	});

    function get_field_prefix(id)
    {
        var field_prefix = id.split(/-/)[0];
        if (field_prefix == 'last')
        {
            field_prefix = 'last-updated';
        }
        return field_prefix; 
    }

	function customRange(input)
	{
		
	    var min = new Date(2008, 11 - 1, 1); //Set the absolute min date
	    var dateMax = null; //allow future dates in calendar
	    //var dateMax = new Date(); //do not allow future dates in calendar
	    
	    var dateMin = null;

		if(input.id == "start-date-year") {
		    var selectedEndDay = $("#end-date-day").val();
		    var selectedEndMonth = $("#end-date-month").val();
               var selectedEndYear = $("#end-date-year").val();

	        if (selectedEndYear != '') {
                dateMin = min;
	           dateMax = new Date(selectedEndYear, selectedEndMonth - 1, selectedEndDay - 1);

	        }
	        else {
	            //dateMax = null;
	        }
		}
		else if(input.id == "end-date-year") {
            var selectedStartDay = $("#start-date-day").val();
		   var selectedStartMonth = $("#start-date-month").val();
            var selectedStartYear = $("#start-date-year").val();

	        if (selectedStartYear != '') {
	           dateMin = new Date(selectedStartYear, selectedStartMonth - 1, parseInt(selectedStartDay) + 1);      
	        }
	        else {
	            dateMin = min;
	        }
	    }
	    return {
	        minDate: dateMin,
	        maxDate: dateMax
	    };
	}

	//If either of the two non-datepicker date fields are selected in any way
	//display the datepicker
	$(document).ready(function() {
		$('.datepicker-field').focus(function() {
            var field_prefix = get_field_prefix($(this).attr('id'));
            var datepicker_field_id = '#' + field_prefix + '-year';
            $(datepicker_field_id).datepicker("show");
        });
    });
