<?php
	// Functions
	require_once 'lib/functions.php';
	// Markdown
	require_once 'Michelf/MarkdownExtra.inc.php';
	// Get Markdown class
	use Michelf\MarkdownExtra;
	
	// No error reporting
//	error_reporting(0);
	
	// Grab our json file
	$Json_File = "../lib/data.json";
	// Read our json file
	$jsonInfo = json_decode( file_get_contents($Json_File), true );
	// Make sure it exist!
	if ( empty( $jsonInfo ) )
		die("{$Json_File} not found!");
	
	if ( !empty( $_GET['page'] ) )
		$Get_Page = $_GET['page'];
	else
		$Get_Page = "";
	
	if ( !empty( $_GET['function'] ) )
		$Get_Function = $_GET['function'];
	else
		$Get_Function = "";
	
	if ( !empty( $_GET['cat'] ) )
		$Get_Category = $_GET['cat'];
	else
		$Get_Category = "";
	
	if ( empty( $jsonInfo['categories'][$Get_Category] ) )
		if ( HasError( "", "Category", $Get_Category ) )
			return;
	
	$JsonPageData = array();
	$JsonData = array();
	$JsonArgs = array();
	
	foreach( $jsonInfo['categories'][$Get_Category] as $JsonDataKey )
	{
		if ( empty( $jsonInfo['categories'][$Get_Category][$Get_Page] ) )
			if ( HasError( "", "Page", $Get_Page ) )
				return;
		
		// Our json info
		$JsonPageData = $jsonInfo['categories'][$Get_Category][$Get_Page];
		
		foreach( $jsonInfo['categories'][$Get_Category][$Get_Page]['functions'] as $JsonDataKeySub )
		{
			if ( $Get_Function == $JsonDataKeySub['name'] )
			{
				// Our json info
				$JsonData = $JsonDataKeySub;
				break;
			}
		}
		break;
	}
	
	if ( $Get_Function != "" && HasError( $JsonData, "Function", $Get_Function ) )
		return;
	
	// Args
	if ( !empty( $JsonData['args'] ) )
	{
		while( $arg = current($JsonData['args']) )
		{
			$JsonArgs[] = array(
				'arg' => FormatArgument($jsonInfo, key($JsonData['args'])),
				'desc' => GetDescription( $arg )
			);
			next($JsonData['args']);
		}
	}
	
	if ( $JsonData )
	{
		$FunctionName = $JsonData['name'];
		if ( !empty($JsonData['namefake']) )
			$Get_Function = $FunctionName = $JsonData['namefake'];
	}
	
	echo '<ol class="breadcrumb">
		<li>' . $Get_Category . '</li>';
		if ( $Get_Function != "" )
			echo '<li><a href="#cat=' . $Get_Category . '&page=' . $Get_Page . '">' . $Get_Page .'</a></li>
			<li class="active">' . $Get_Function . '</li>';
		else
			echo '<li class="active">' . $Get_Page .'</li>';
	echo '</ol>';
	
if ( $Get_Function != "" )
{
	if ( $JsonData['restrict'] )
		echo RestrictNotify( $JsonData['restrict'] );
?>
<h1 class="basecontent-header"><?php echo $FunctionName; ?></h1>
<div>
	<h3>Description</h3>
	<p style="padding-left: 15px;"><?php echo $JsonData['desc']; ?></p>
</div>

<?php
if ( !empty( $JsonData['infobox_enable'] ) && $JsonData['infobox_enable'] == "true" )
{
	$desc = $JsonData['infobox_desc'];
	// Override it
	if ( $JsonData['infobox_type'] == "custom" )
		$desc = "This requires [Engine.EnableCustomSettings][EnableCustomSettings] to be enabled.";
	
	echo '<div class="box-callout ' . $JsonData['infobox_type'] . '">
		<i class="fa fa-info-circle"></i>
		<b style="font-size: 18px;line-height: 0;">' . BoxType( $JsonData['infobox_type'] ) .'</b>
		<p>' . MarkdownExtra::defaultTransform( GetDescription( $desc ) ) . '</p>
	</div>';
}
?>

<?php if ( $JsonData['type'] != "enum" ) { ?>
<pre>
<?php
// Our type
$AngelscriptOutput = '';
$AngelscriptFunction = $FunctionName;
$AngelscriptArgs = '';
$BaseFunction = '';
// By default, we use the page name
$FunctionAPIName = $Get_Page;
// What kind of type is this? (void, int, etc)
$BaseFunctionVariable = $JsonData['type'];

// If it's not a global value
if ( $JsonData['global'] == "false" || $JsonData['global'] == "" || !$JsonData['global'] )
{
	if ( empty($JsonData['namespace']) )
	{
		if ( !empty($JsonData['child']) )
			$AngelscriptOutput = $AngelscriptOutput . '<span class="class">' . $JsonData['child'] . '</span>.';
		else
			$AngelscriptOutput = $AngelscriptOutput . '<span class="class">' . $FunctionAPIName . '</span>';
	}
	
	if ( !empty($JsonData['base']) )
		$BaseFunctionVariable = $JsonData['base'];
	
	if ( $JsonPageData['type'] == "object" )
		$AngelscriptOutput = $AngelscriptOutput . '@ ';
	elseif ( $JsonPageData['type'] == "class" )
	{
		if ( !empty($JsonData['child']) )
			$AngelscriptOutput = '<span class="class">' . $JsonData['child'] . '</span>.';
		elseif ( !empty($JsonData['object']) )
			$AngelscriptOutput = '<span class="class">' . $JsonData['object'] . '@</span> <span class="class">' . $FunctionAPIName . '</span> ';
		else
			$AngelscriptOutput = '';
	}
	elseif ( $JsonPageData['type'] == "namespace" || $JsonData['type'] == "namespace" || !empty($JsonData['namespace']) )
	{
		if ( !empty($JsonData['namespace']) )
			$AngelscriptOutput = $AngelscriptOutput .'<span class="class">' . $JsonData['namespace'] . '</span>::';
		else
			$AngelscriptOutput = $AngelscriptOutput . '::';
		
		if ( !empty($JsonData['object']) )
			$AngelscriptOutput = '<span class="class">' . $JsonData['object'] . '@</span> ' . $AngelscriptOutput;
	}
	elseif ( $JsonData['type'] == "event" )
		$AngelscriptOutput = '<span class="class">HookReturnCode</span> ';
	else
	{
		if ( !empty($JsonData['object']) )
		{
			$AngelscriptOutput = '<span class="class">' . $JsonData['object'] . '@</span> ';
			if ( !empty($JsonData['child']) )
				$AngelscriptOutput = $AngelscriptOutput . '<span class="function">' . $JsonData['child'] . '</span>.';
		}
		elseif ( !empty($JsonData['ref']) )
			$AngelscriptOutput = '<span class="class">' . $JsonData['ref'] . '</span>.';
		elseif ( !empty($JsonData['child']) )
			$AngelscriptOutput = '<span class="class">' . $JsonData['child'] . '</span>.';
		elseif ( $JsonData['type'] == "class" )
			$AngelscriptOutput = '';
		else
			$AngelscriptOutput = $AngelscriptOutput . '.';
	}
}
else
{
	if ( $JsonData['type'] == "object" )
		$AngelscriptOutput = '<span class="class">' . $JsonData['object'] . '@</span> ';
}

// Args
if ( !empty( $JsonData['args'] ) )
{
	$AngelscriptArgs = '(';
	$argcount = 0;
	foreach( $JsonArgs as $JsonArg )
	{
		if ( $argcount > 0 )
			$AngelscriptArgs = $AngelscriptArgs . ', ';
		$AngelscriptArgs = $AngelscriptArgs . $JsonArg['arg'];
		$argcount++;
	}
	$AngelscriptArgs = $AngelscriptArgs . ')';
}
else
{
	if ( $JsonData['type'] == "void" || $JsonData['type'] == "event" )
		$AngelscriptArgs = '()';
	elseif ( !empty( $JsonData['isfunc'] ) && $JsonData['isfunc'] )
		$AngelscriptArgs = '()';
}

if ( $JsonData['type'] != "event" )
{
	if ( !empty( $JsonData['classfunction'] ) && $JsonData['classfunction'] )
		$AngelscriptArgs = $AngelscriptArgs . '<br>{<br>}';
	else
		$AngelscriptArgs = $AngelscriptArgs . ';';
}
else
{
	// Shot it for events/hooks
	$AngelscriptArgs = $AngelscriptArgs . '<br>{<br>	<span class="class">return</span> <span class="mono" style="color:#f5f568;">HOOK_CONTINUE</span>;<br>}';
}

$BaseFunctionCheck = GetFunction( $BaseFunctionVariable );
if ( !empty($BaseFunctionCheck) )
	$BaseFunction = '<span class="function">' . $BaseFunctionCheck . '</span> ';
$AngelscriptOutput = $BaseFunction . $AngelscriptOutput . $AngelscriptFunction . $AngelscriptArgs;

echo $AngelscriptOutput;
?>
</pre>

<?php
	if ( !empty( $JsonData['args'] ) )
	{
		echo '<h2 class="basecontent-header">Parameters</h2>';
		foreach( $JsonArgs as $JsonArg )
		{
			echo'<dl>
			<dt class="mono">' . $JsonArg['arg'] . '</dt>
			<dd><p>' . GetDescription( $JsonArg['desc'] ) . '</p></dd>
			</dl>';
		}
	}
	
	if ( !empty( $JsonData['desc_md'] ) && $JsonData['desc_md'] == "true" )
	{
		$filename = $FunctionName;
		if ( !empty( $JsonData['desc_file'] ) )
			$filename = $JsonData['desc_file'];
		$file = '../lib/markdown/' . strtolower( $filename ) . '.md';
		if ( file_exists( $file ) )
			echo MarkdownExtra::defaultTransform( file_get_contents( $file ) ) . '<br>';;
	}
	
	// Return value
	if ( !empty( $JsonData['return'] ) && $JsonData['return'] )
		echo '<div class="box-callout return">
			<i class="fa fa-info-circle"></i>
			<b style="font-size: 18px;line-height: 0;">Return</b>
			<p>' . ReturnType( $JsonData['return'] ) . '</p>
		</div>';
	
	if ( $JsonData['type'] == "event" )
	{
		echo '<br><h2 class="basecontent-header">Hook Register</h2>';
		echo '<pre><span class="class">Events</span>::<span class="type">' . $JsonData['eventtype'] . '</span>::'. $FunctionName . '.Hook( <span class="function">@' . $FunctionName . '</span> );</pre>';
		echo '<br><h2 class="basecontent-header">Hook Returns</h2>';
		echo '<pre><span class="mono" style="color:#f5f568;">HOOK_CONTINUE</span> <span class="mono" style="color:#099b00;font-style: italic;">// Continues the function</span></pre>';
		echo '<pre><span class="mono" style="color:#f5f568;">HOOK_HANDLED</span> <span class="mono" style="color:#099b00;font-style: italic;">// Stops the function from continuing. This may also stop other plugins using the same function.</span></pre>';
	}
}
else
{
	if ( !empty( $JsonData['desc_md'] ) && $JsonData['desc_md'] == "true" )
	{
		$filename = $FunctionName;
		if ( !empty( $JsonData['desc_file'] ) )
			$filename = $JsonData['desc_file'];
		$file = '../lib/markdown/' . strtolower( $filename ) . '.md';
		if ( file_exists( $file ) )
			echo MarkdownExtra::defaultTransform( file_get_contents( $file ) ) . '<br>';
	}
?>
<div class="table-responsive">
	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th>Value</th>
				<th>Description</th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach( $JsonArgs as $JsonArg )
			{
				echo'<tr>
					<td><span class="class">' . $JsonArg['arg'] . '</span></td>
					<td>' . GetDescription( $JsonArg['desc'] ) . '</td>
				</tr>';
			}
		?>
		</tbody>
	</table>
</div>
<?php
}

}
else
{
	if ( !empty( $JsonPageData['baseclass'] ) )
		echo '<div class="box-callout">
			<i class="fa fa-info-circle"></i>
			<b style="font-size: 18px;line-height: 0;">This is a child of <a href="#cat=' . $Get_Category . '&page=' . $JsonPageData['baseclass'] . '" rel="page">' . $JsonPageData['baseclass'] . '</a></b>
			<p>All functions from the parent applies here as well.</p>
		</div>';
?>

<h1 class="basecontent-header"><?php echo $Get_Page; ?></h1>

<?php
	echo '<div>
		<h3>Description</h3>
		<p style="padding-left: 15px;">' . $JsonPageData['desc'] . '</p>
	</div>';
	
	if ( !empty( $JsonPageData['desc_md'] ) && $JsonPageData['desc_md'] == "true" )
	{
		$FunctionName = $JsonPageData['name'];
		if ( !empty($JsonPageData['namefake']) )
			$FunctionName = $JsonPageData['namefake'];
		$file = '../lib/markdown/' . strtolower( $FunctionName ) . '.md';
		if ( file_exists( $file ) )
			echo MarkdownExtra::defaultTransform( file_get_contents( $file ) );
	}
	
if ( !empty( $JsonPageData['infobox_enable'] ) && $JsonPageData['infobox_enable'] )
	echo '<div class="box-callout ' . $JsonPageData['infobox_type'] . '">
		<i class="fa fa-info-circle"></i>
		<b style="font-size: 18px;line-height: 0;">' . BoxType( $JsonPageData['infobox_type'] ) .'</b>
		<p>' . MarkdownExtra::defaultTransform( $JsonPageData['infobox_desc'] ) . '</p>
	</div>';

if ( !empty( $JsonPageData['functions'] ) )
	LoadFunctions( $JsonPageData['functions'], $Get_Category, $Get_Page );

}
// Load the scripts
LoadScripts();
?>
