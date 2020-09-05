/* Angelscript API Documentation -- v1.0 | Written by Johan "JonnyBoy0719" Ehrendahl */

$(document).ready(function() {
	setTimeout(function(){
		LoadASAPI();
	}, 45);
});

var json_data = [];		// Our json object
var asapi_title = "";
function LoadASAPI() {
	// Load our data.json
	$.getJSON( "lib/data.json", function( data ) {
		// Set our title
		asapi_title = data['doc_name'];
		document.title = asapi_title;
		
		// Override the logo, if we use a custom one
		var image = document.getElementById( "logo" );
		image.src = data['doc_logo'];
		
		// Create our navbar
		var navbar = [];
		navbar.push( "<li class=\"sidebar-header\">MAIN NAVIGATION</li>" );
		navbar.push( "<li><a href=\"#home\" rel=\"page\"><span>Home</span></a></li>" );
		
		// Add our categories
		$.each( data['categories'], function( categories, category_val ) {
				navbar.push( '<li><a href="#' + categories + '"><span>' + categories + '</span><i class="fa fa-angle-left pull-right"></i></a>' );
				navbar.push( '<ul class="sidebar-submenu">' );
				$.each( data['categories'][categories], function( category_data, category_data_val ) {
					navbar.push( '<li><a href="#cat=' + categories + '&amp;page=' + category_data + '" rel="page"><span>' + category_data + '</span><i class="fa fa-angle-left pull-right"></i></a>' );
					navbar.push( '<ul class="sidebar-submenu">' );
					$.each( data['categories'][categories][category_data]['functions'], function( item_data, item_data_val ) {
						var ItemKey = data['categories'][categories][category_data]['functions'][item_data];
						var PageNameSpace = '<span class="class">' + ItemKey['type'] + '</span> ';
						var eventtype = "";
						if ( ItemKey['eventtype'] )
						{
							eventtype = ItemKey['eventtype'] + "::";
							PageNameSpace = '<span class="class">' + ItemKey['eventtype'] + '::</span>';
						}
						else
						{
							if ( ItemKey['object'] )
							{
								PageNameSpace = '<span class="class">' + ItemKey['object'] + '@</span> ';
								if ( ItemKey['child'] )
									PageNameSpace = PageNameSpace + '<span class="class">' + ItemKey['child'] + '</span>.';
							}
							else if ( ItemKey['namespace'] || ItemKey['type'] && ItemKey['type'] == "namespace" )
							{
								var namespace = ItemKey['type'];
								if ( ItemKey['namespace'] )
									namespace = ItemKey['namespace'];
								PageNameSpace = '<span class="class">' + namespace + '</span>::';
							}
							else if ( ItemKey['child'] )
								PageNameSpace = PageNameSpace + '<span class="class">' + ItemKey['child'] + '</span>.';
						}
						
						var DisplayName = FunctionName = ItemKey['name'];
						var FunctionNameFake = '';
						var FunctionNameSpace = '';
						if ( ItemKey['namefake'] )
							DisplayName = FunctionNameFake = ItemKey['namefake'];
						if ( ItemKey['namespace'] )
							FunctionNameSpace = ItemKey['namespace'];
						
						navbar.push( '<li><a href="#cat=' + categories + '&amp;page=' + category_data + '&amp;function=' + ItemKey['name'] + '" rel="page"><span>' + PageNameSpace + DisplayName + '</span></a></li>' );
						
						// Add to our search
						json_data.push({
							"category": categories,
							"base": category_data,
							"name": FunctionName,
							"namefake": FunctionNameFake,
							"namespace": FunctionNameSpace,
							"desc": ItemKey['desc'],
							"type": ItemKey['type'],
							"eventtype": eventtype
						});
					});
					
					navbar.push( "</ul></li>" );
				});
				navbar.push( "</ul></li>" );
		});
		
		// Add our extra documentations
		if ( data['doc_extras'] )
		{
			navbar.push( '<li class="sidebar-header">OTHER DOCUMENTATION</li>' );
			$.each( data['doc_extras'], function( categories, category_val ) {
				navbar.push( '<li><a target="_blank" href="' + categories + '"><i class="fa fa-book"></i><span>' + data['doc_extras'][categories] + '</span></a></li>' );
			});
		}
		
		// Add it to our navigation
		var list = document.getElementById( "navbar" );
		list.innerHTML = navbar.join("");
		
		// Update our generation date
		var dateloc = document.getElementById( "dateloc" );
		var gen_date = data['doc_gen'];
		var date = new Date( gen_date * 1000 );
		
		// Grab our date info
		var month = date.getMonth();
		var day = date.getDate();
		var year = date.getFullYear();
		
		// Grab our month name
		const monthNames = ["January", "February", "March", "April", "May", "June",
		  "July", "August", "September", "October", "November", "December"
		];
		
		// Format our time
		var hours = date.getHours();
		var minutes = date.getMinutes();
		var ampm = hours >= 12 ? 'pm' : 'am';
		hours = hours % 12;
		hours = hours ? hours : 12; // the hour '0' should be '12'
		minutes = minutes < 10 ? '0'+minutes : minutes;
		var strTime = hours + ':' + minutes + ampm;
		
		// Print when we generated our API
		var txt = "Generated on " + monthNames[month] + " " + day + ", " + year + " | " + strTime;
		dateloc.appendChild( document.createTextNode( txt ) );
		
		$('head').append( '<meta name="type" content="article">' );
		$('head').append( '<meta name="url" content="https://jonnyboy0719.github.io/ContagionASDocs/">' );
		$('head').append( '<meta name="image" content="https://jonnyboy0719.github.io/ContagionASDocs/' + data['doc_logo'] + '">' );
	});
}