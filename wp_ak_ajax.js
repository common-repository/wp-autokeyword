/****************************************************************************/
// Load Whole JavaScript On Load.
/****************************************************************************/
jQuery(document).ready(function() {

	// ******************************
	// Keyword Selector
	// ******************************
	jQuery('#wpak_keywordSelector').on("submit",function(e) {
	e.preventDefault();	// To Stop Submit Button Default Action
	var answer = confirm('Are You Sure Want To Suggest Keyword For This Article?');
    if (answer){
		var incomingData = jQuery('#wpak_keywordSelector').serializeArray();
		incomingData.push({name: 'action', value: 'wpakGuessKeyword'});
			jQuery('#showAjax_wpak_keywordSelector').html('<div class="lds-heart"><div></div></div>');	// Loading Animation
			jQuery.ajax({
				type: 'post',
				url: my_ajax_object.ajax_url,
				data: incomingData,
				success: function(response) { 
					jQuery('#showAjax_wpak_keywordSelector').html(response);
				},
				error: function(response) { 
					jQuery('#showAjax_wpak_keywordSelector').html('<div class="exe_error">Error: Cant Run SelectKeyword Function. Please Contact Us On <a href="mailto:info@exeideas.net?cc=support@exeideas.com&amp;subject=WPAK%20WordPress%20Plugin%20UPDATE&amp;body=Hello%20WPAK%20Admin%3B%0A%0A%3C!--%20Your%20Msg%20Here%20With%20All%20The%20Details%20Of%20Updates%20We%20Needs%20--%3E%0A%0AThanks%20and%20Regards.>info@exeideas.net</a></div>');
				},
			});		
	}
	return false; // For Not To Reload Page
	});


	
	// ******************************
	// Bulk Keyword Selector
	// ******************************
	jQuery('#wpak_bulkKeywordSelector').on("submit",function(e) {
	e.preventDefault();	// To Stop Submit Button Default Action
	var answer = confirm('Are You Sure Want To Suggest And Add Keyword In All Published Posts?');
    if (answer){
		var incomingData = jQuery('#wpak_bulkKeywordSelector').serializeArray();
		incomingData.push({name: 'action', value: 'wpakBulkKeyword'});
			jQuery('#showAjax_wpak_bulkKeywordSelector').html('<div class="lds-heart"><div></div></div>');	// Loading Animation
			jQuery.ajax({
				type: 'post',
				url: my_ajax_object.ajax_url,
				data: incomingData,
				success: function(response) { 
					jQuery('#showAjax_wpak_bulkKeywordSelector').html(response);
				},
				error: function(response) { 
					jQuery('#showAjax_wpak_bulkKeywordSelector').html('<div class="exe_error">Error: Cant Run BulkKeywordSelect Function. Please Contact Us On <a href="mailto:info@exeideas.net?cc=support@exeideas.com&amp;subject=WPAK%20WordPress%20Plugin%20UPDATE&amp;body=Hello%20WPAK%20Admin%3B%0A%0A%3C!--%20Your%20Msg%20Here%20With%20All%20The%20Details%20Of%20Updates%20We%20Needs%20--%3E%0A%0AThanks%20and%20Regards.>info@exeideas.net</a></div>');
				},
			});
	}
	return false; // For Not To Reload Page
	});



	// ******************************
	// Save Keywords
	// ******************************
	jQuery('#wpak_saveKeywords').on("submit",function(e) {
	e.preventDefault();	// To Stop Submit Button Default Action
	var answer = confirm('Are You Sure Want To Save These Keywords In Your Database?');
    if (answer){
		var incomingData = jQuery('#wpak_saveKeywords').serializeArray();
		incomingData.push({name: 'action', value: 'wpakAddKeyword'});
			jQuery('#showAjax_wpak_saveKeywords').html('<div class="lds-heart"><div></div></div>');	// Loading Animation
			jQuery.ajax({
				type: 'post',
				url: my_ajax_object.ajax_url,
				data: incomingData,
				success: function(response) { 
					jQuery('#showAjax_wpak_saveKeywords').html(response);
					// Run Function On Page Load
					wpak_getPagination(20,1);
    				wpak_searchGet(1,20);
				},
				error: function(response) { 
					jQuery('#showAjax_wpak_saveKeywords').html('<div class="exe_error">Error: Cant Run AddKeyword Function. Please Contact Us On <a href="mailto:info@exeideas.net?cc=support@exeideas.com&amp;subject=WPAK%20WordPress%20Plugin%20UPDATE&amp;body=Hello%20WPAK%20Admin%3B%0A%0A%3C!--%20Your%20Msg%20Here%20With%20All%20The%20Details%20Of%20Updates%20We%20Needs%20--%3E%0A%0AThanks%20and%20Regards.>info@exeideas.net</a></div>');
				},
			});		
	}
	return false; // For Not To Reload Page
	});
	
	// ******************************
	// Run Function On Page Load
	// ******************************	
	wpak_getPagination(20,1);
    wpak_searchGet(1,20);

});



// ******************************
// Search/View The Data And Retrive The Updated Table
// ******************************
function wpak_searchGet(wpak_displayPage,wpak_resultsPerPage) {
	wpak_getPagination(wpak_resultsPerPage,wpak_displayPage);	// Update Pagination
	// Get Input Tags With Checking //		
	if (document.getElementById("wpak_ResultsSort") != null) {
		 var wpak_ResultsSort = document.getElementById("wpak_ResultsSort").options[document.getElementById("wpak_ResultsSort").selectedIndex].value;
	}
	var wpak_KeywordToFind = ((document.getElementById("wpak_KeywordToFind")||{}).value)||"";
	// Get Input Tags With Checking //	
	var incomingData = {action:"wpakViewData", wpak_displayPage:wpak_displayPage, wpak_resultsPerPage:wpak_resultsPerPage, wpak_ResultsSort:wpak_ResultsSort, wpak_KeywordToFind:wpak_KeywordToFind};
		jQuery('#showAjax_wpak_keywordDatabase').html('<div class="lds-heart"><div></div></div>');	// Loading Animation
		jQuery.ajax({
			type: 'post',
			url: my_ajax_object.ajax_url,
			data: incomingData,
			success: function(response) { 
				jQuery('#showAjax_wpak_keywordDatabase').html(response);
			},
			error: function(response) { 
				jQuery('#showAjax_wpak_keywordDatabase').html('<div class="exe_error">Error: Cant Run SearchGet Function. Please Contact Us On <a href="mailto:info@exeideas.net?cc=support@exeideas.com&amp;subject=WPAK%20WordPress%20Plugin%20UPDATE&amp;body=Hello%20WPAK%20Admin%3B%0A%0A%3C!--%20Your%20Msg%20Here%20With%20All%20The%20Details%20Of%20Updates%20We%20Needs%20--%3E%0A%0AThanks%20and%20Regards.>info@exeideas.net</a></div>');
			},
		});
}


// ******************************
// Table Pagination 
// ******************************
function wpak_getPagination(wpak_resultsPerPage,wpak_displayPage) {
	var wpak_KeywordToFind = ((document.getElementById("wpak_KeywordToFind")||{}).value)||"";
	var incomingData = {action:"wpakPaginationData", wpak_displayPage:wpak_displayPage, wpak_resultsPerPage:wpak_resultsPerPage, wpak_KeywordToFind:wpak_KeywordToFind};
		jQuery('#wpak_showPaginationData').html('<div class="lds-heart"><div></div></div>');	// Loading Animation
		jQuery.ajax({
			type: 'post',
			url: my_ajax_object.ajax_url,
			data: incomingData,
			success: function(response) { 
				jQuery('#wpak_showPaginationData').html(response);
			},
			error: function(response) { 
				jQuery('#wpak_showPaginationData').html('<div class="exe_error">Error: Cant Run Pagination Function. Please Contact Us On <a href="mailto:info@exeideas.net?cc=support@exeideas.com&amp;subject=WPAK%20WordPress%20Plugin%20UPDATE&amp;body=Hello%20WPAK%20Admin%3B%0A%0A%3C!--%20Your%20Msg%20Here%20With%20All%20The%20Details%20Of%20Updates%20We%20Needs%20--%3E%0A%0AThanks%20and%20Regards.>info@exeideas.net</a></div>');
			},
		});
}
function wpak_changeClass(thisLink) {
	if (document.getElementById("paginationPages") != null) {
		var allLinks = document.getElementById("paginationPages").getElementsByTagName("a");		
		for (i = 0; i < allLinks.length; i++) {
			allLinks[i].classList.remove('currentPage')
		}
		thisLink.classList.add('currentPage');
	}
}



// ******************************
// Update The Table Coloum
// ******************************
function wpak_updateCell(wpak_incomingID,wpak_duplicate) {
	// Confirmation To Delete A Contact
    var answer = confirm('Are You Sure Want To Update This Record?');
    if (answer){
		// Get Parameters From The Incoming Variable
		var wpak_duplicate = wpak_duplicate;
		var wpak_incomingID = document.getElementById(wpak_incomingID);
		var wpak_TableName = wpak_incomingID.attributes["ID"].value.split(":")[0];
		var wpak_ColumnName = wpak_incomingID.attributes["ID"].value.split(":")[1];
		var wpak_RowID = wpak_incomingID.attributes["ID"].value.split(":")[2];		
		var wpak_ColumnValue =  wpak_incomingID.value || wpak_incomingID.options[wpak_incomingID.selectedIndex].value;		
		var incomingData = {action:"wpakUpdateCell", wpak_TableName:wpak_TableName, wpak_ColumnName:wpak_ColumnName, wpak_RowID:wpak_RowID, wpak_ColumnValue:wpak_ColumnValue, wpak_Duplicate:wpak_duplicate};	
			jQuery('#showAjax_wpak_keywordDatabase_Status').html('<div class="lds-heart"><div></div></div>');	// Loading Animation	
			jQuery.ajax({
				type: 'post',
				url: my_ajax_object.ajax_url,
				data: incomingData,
				success: function(response) {
					jQuery('#showAjax_wpak_keywordDatabase_Status').html(response);
				},
				error: function(response) { 
					jQuery('#showAjax_wpak_keywordDatabase_Status').html('<div class="exe_error">Error: Cant Run UpdateColoum Function. Please Contact Us On <a href="mailto:info@exeideas.net?cc=support@exeideas.com&amp;subject=WPAK%20WordPress%20Plugin%20UPDATE&amp;body=Hello%20WPAK%20Admin%3B%0A%0A%3C!--%20Your%20Msg%20Here%20With%20All%20The%20Details%20Of%20Updates%20We%20Needs%20--%3E%0A%0AThanks%20and%20Regards.>info@exeideas.net</a></div>');
				},
			});
	}
	return false; // For Not To Reload Page		
}



// ******************************
// Delete The Table Row
// ******************************
function wpak_deleteRow(wpak_incomingRow, wpak_incomingTable, wpak_incomingID) {
	// Confirmation To Delete A Contact
    var answer = confirm('Are You Sure Want To Delete This Record?');
    if (answer){
		var incomingData = {action:"wpakDeleteRow", wpak_incomingTable:wpak_incomingTable, wpak_incomingID:wpak_incomingID};
			jQuery('#showAjax_wpak_keywordDatabase_Status').html('<div class="lds-heart"><div></div></div>');	// Loading Animation
			jQuery.ajax({
				type: 'post',
				url: my_ajax_object.ajax_url,
				data: incomingData,
				success: function(response) {
					incomingRow.remove();	// Remove That Row In Real Time
					jQuery('#showAjax_wpak_keywordDatabase_Status').html(response);
				},
				error: function(response) { 
					jQuery('#showAjax_wpak_keywordDatabase_Status').html('<div class="exe_error">Error: Cant Run DeleteRow Function. Please Contact Us On <a href="mailto:info@exeideas.net?cc=support@exeideas.com&amp;subject=WPAK%20WordPress%20Plugin%20UPDATE&amp;body=Hello%20WPAK%20Admin%3B%0A%0A%3C!--%20Your%20Msg%20Here%20With%20All%20The%20Details%20Of%20Updates%20We%20Needs%20--%3E%0A%0AThanks%20and%20Regards.>info@exeideas.net</a></div>');
				},
			});
	}
	return false; // For Not To Reload Page		
}