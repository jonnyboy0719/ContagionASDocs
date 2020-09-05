<?php
	function LoadJson( $type )
	{
		// Grab our json file
		$Json_File = "../lib/functions.json";
		// Read our json file
		$jsonFunction = json_decode( file_get_contents($Json_File), true );
		// Make sure it exist!
		if ( empty( $jsonFunction ) )
			die("{$Json_File} not found!");
		
		if ( empty( $jsonFunction[$type] ) )
			return array();
		
		$JsonOutput = array();
		while( $arg = current($jsonFunction[$type]) )
		{
			if ( $arg == 'null' )
				$arg = '';
			
			$JsonOutput[] = array(
				'value' => key($jsonFunction[$type]),
				'arg' => $arg
			);
			next($jsonFunction[$type]);
		}
		return $JsonOutput;
	}
	
	function LoadScripts()
	{
?>
		<script>
			var pagehash = '';
			$(document).ready(function() {
				var path = $(location).attr('hash');
				var location1 = path.split('#')[1];
				
				// Set location
				pagehash = location1;
				
				setTimeout(function(){
					ShouldReloadPage();
				}, 25);
				
				$('pre code').each(function(i, block) {
					hljs.highlightBlock(block);
				})
			});

			function ShouldReloadPage() {
				var path = $(location).attr('hash');
				var location1 = path.split('#')[1];
				
				if ( pagehash != location1 )
					LoadPage();
				else
				{
					setTimeout(function(){
						ShouldReloadPage();
					}, 25);
				}
			}

			function LoadPage() {
				var path = $(location).attr('hash');
				var location1 = path.split('#')[1];
				
				pagehash = location1;
				
				if (window.location.hash === '#home' || window.location.hash === '')
					$('.basecontent').load("pages/home.php");
				else
					$('.basecontent').load('pages/reader.php?' + location1);
			}
		</script>
<?php
	}
	
	function GetFunction( $input )
	{
		foreach( LoadJson( 'functions' ) as $Function )
		{
			if ( $Function['value'] == $input )
				return $Function['arg'];
		}
		foreach( LoadJson( 'functions_classes' ) as $Function )
		{
			if ( $Function['value'] == $input )
				return $Function['arg'];
		}
		// Invalid type, return nothing
		return '';
	}
	
	function GetDescription( $input )
	{
		if ( empty( $input ) || $input == 'null' )
			return 'No description available.';
		return $input;
	}
	
	function GetDescription_Type( $input )
	{
		foreach( LoadJson( 'list_types' ) as $Function )
		{
			if ( $Function['value'] == $input )
				return '<span class="value">' . $Function['arg'] . '</span>';
		}
		return '<span class="value">' . $input . '</span>';
	}
	
	function LoadFunctions( $array, $category, $page )
	{
		$FoundResult = false;
		foreach( LoadJson( 'list_types' ) as $Function )
		{
			$FoundResult = false;
			foreach( $array as $JsonDataKey )
			{
				if ( $Function['value'] == $JsonDataKey['type'] )
					$FoundResult = true;
			}
			
			if ( $FoundResult == false )
				continue;
			
			echo '<div class="table-responsive">
			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th width="30%">' . $Function['arg'] .'</th>
						<th>Description</th>
					</tr>
				</thead>
				<tbody>';
				foreach( $array as $JsonDataKey )
				{
					if ( $Function['value'] == $JsonDataKey['type'] )
					{
						$functiontype = '<span class="function">' . $JsonDataKey['eventtype'] . '</span><span class="class">::</span>' . $JsonDataKey['name'];
						
						if ( empty( $JsonDataKey['eventtype'] ) || $JsonDataKey['eventtype'] == "" )
							$functiontype = $JsonDataKey['name'];
						
						echo'<tr>
							<td><a href="#cat=' . $category . '&amp;page=' . $page . '&amp;function=' . $JsonDataKey['name'] . '" rel="page">' . $functiontype . '</a></td>
							<td>' . GetDescription( $JsonDataKey['desc'] ) . '</td>
						</tr>';
					}
				}
			echo'</tbody>
			</table>
		</div>';
		}
	}
	
	function HasError( $input, $error_type, $error_value )
	{
		if ( empty( $input ) )
		{
			echo '<h1 class="basecontent-header">Error Code: 404</h1>';
			if ( $error_value != "" )
			{
				echo '<div class="box-callout warning">
					<i class="fa fa-info-circle"></i>
					<b style="font-size: 18px;line-height: 0;">Warning</b>
					<p>The ' . $error_type . ' "<b>' . $error_value . '</b>" does not exist.</p>
				</div>';
			}
			else
			{
				echo '<div class="box-callout warning">
					<i class="fa fa-info-circle"></i>
					<b style="font-size: 18px;line-height: 0;">Warning</b>
					<p>This ' . $error_type . ' does not exist.</p>
				</div>';
			}
			// Load the scripts
			LoadScripts();
			return true;
		}
		
		return false;
	}
	
	function RestrictNotify( $input )
	{
		$value = "";
		
		if ( $input == "map" )
			$value = '<span class="value">map plugins</span>!';
		elseif ( $input == "plugin" )
			$value = '<span class="value">server plugins</span>!';
		else
		{
			$chars = preg_split('/=/', $input, -1, PREG_SPLIT_OFFSET_CAPTURE);
			if ( count($chars) == 2 )
				$value = '<span class="value">' . $chars[0][0] . ' ' . $chars[1][0] . '</span>!';
			else
				return "";
		}
		
		// Setup the rest
		$output = '<div class="box-callout restrict">';
		$output = $output .'<i class="fa fa-exclamation-triangle" style="margin-right: 5px;"></i>';
		$output = $output . 'This is only available for ' . $value;
		$output = $output . '</div>';
		
		return $output;
	}
	
	function FindClassPage( $array, $input, $type, $key = 'name' )
	{
		foreach( array_keys($array['categories']) as $Categories )
			foreach( array_keys($array['categories'][$Categories]) as $CategoryData )
				foreach($array['categories'] as $data_key)
				{
					if ( $CategoryData == $input )
						return '<a href="#cat=' . $Categories . '&amp;page=' . $CategoryData . '" rel="page"><span class="type">' . $CategoryData . $type . '</span></a>';
					if( !empty($data_key[$CategoryData]['functions']) )
						foreach( $data_key[$CategoryData]['functions'] as $ItemKey )
						{
							if( empty($CategoryData) ) continue;
							if( empty($input) ) continue;
							if( empty($ItemKey[$key]) ) continue;
							if ( $ItemKey[$key] == $input )
								return '<a href="#cat=' . $Categories . '&amp;page=' . $CategoryData . '&amp;function=' . $ItemKey['name'] .'" rel="page"><span class="type">' . $ItemKey['name'] . $type . '</span></a>';
						}
				}
		return '<span class="type">' . $input . $type . '</span>';
	}
	
	function ReplaceSpecialKey( $array, $preg, $input, $type )
	{
		if ( preg_match( $preg, $input, $matches ) )
			return preg_replace( $preg, FindClassPage( $array, $matches[1], $type ), $input );
		return $input;
	}
	
	function FormatInputs( $preg_input, $input, $type )
	{
		// Try to replace the I/O
		$preg = '/%' . $preg_input . '_(.*?)%/m';
		if ( preg_match( $preg, $input, $matches ) )
		{
			$output = preg_replace( '/%' . $preg_input . '_(.*?)%/m', ' &amp;' . $matches[1] . '</span>', $input );
			return '<span class="type">' . $type . $output;
		}
		
		// Try normal replace
		return str_replace( '%' . $preg_input . '%','<span class="type">' . $type . '</span>', $input );
	}
	
	function SpecialFormatInputs( $array, $preg_input, $input, $type )
	{
		// Try to replace the I/O
		$preg = '/%' . $preg_input . '_(.*?)%/m';
		if ( preg_match( $preg, $input, $matches ) )
			return preg_replace( '/%' . $preg_input . '_(.*?)%/m', FindClassPage( $array, $preg_input, ' &amp;' . $matches[1], 'page' ), $input );
		$preg = '/%' . $preg_input . '%/m';
		if ( preg_match( $preg, $input, $matches ) )
			return preg_replace( $preg, FindClassPage( $array, $type, '', 'page' ), $input );
		return $input;
	}
	
	function FormatArgument( $array, $input )
	{
		// Input
		$output = $input;
		
		// Check if it's a function
		foreach( LoadJson( 'functions' ) as $Function )
			$output = FormatInputs( $Function['value'], $output, $Function['arg'] );
		
		// Check if it's a class
		foreach( LoadJson( 'classes' ) as $Function )
			$output = ReplaceSpecialKey( $array, '/%' . $Function['value'] . '_(.*?)%/m', $output, $Function['arg'] );
		
		// Check if it's a special class
		foreach( LoadJson( 'functions_classes' ) as $Function )
			$output = SpecialFormatInputs( $array, $Function['value'], $output, $Function['arg'] );
		
		// Normal output
		return $output;
	}
	
	function BoxType( $input )
	{
		foreach( LoadJson( 'boxtype' ) as $Function )
		{
			if ( $Function['value'] == $input )
				return $Function['arg'];
		}
		
		return "Information";
	}
	
	function ReturnType( $input )
	{
		foreach( LoadJson( 'return' ) as $Function )
		{
			if ( $Function['value'] == $input )
				return $Function['arg'];
		}
		
		return $input;
	}
?>