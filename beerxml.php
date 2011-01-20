<?php
/*
Plugin Name: BeerXML
Plugin URI: http://www.belchak.com/beerxml-plugin
Description: This plugin will allow users to upload and manage BeerXML files.
Version: 0.11
Author: Ben Belchak
Author URI: http://www.belchak.com
*/

/*  Copyright 2005  Ben Belchak  (email : hekman@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
include_once('xml.php');

function bxml_install()
{
	global $table_prefix, $wpdb;
	$table_name = $table_prefix . "beerxml";
	
	if($wpdb->get_var("show tables like '$table_name'") != $table_name) 
	{
		$sql = "CREATE TABLE $table_name (
			  `id` int(11) NOT NULL auto_increment,
			  `name` varchar(50) NOT NULL default '',
			  `date_brewed` date NOT NULL default '0000-00-00',
			  `brewer_name` varchar(50) NOT NULL default '',
			  `o_gravity` varchar(10) NOT NULL default '',
			  `ibu` varchar(10) NOT NULL default '0',
			  `type` varchar(20) NOT NULL default '',
			  `alcohol` varchar(5) NOT NULL default '',
			  `taste_rating` decimal(3,2) NOT NULL default '0.00',
			  `style` varchar(50) NOT NULL default '',
			  `beerxml` blob NOT NULL,
			  PRIMARY KEY  (`id`)
			) TYPE=MyISAM;";
		require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
		dbDelta($sql);
	}
	add_option('bxml_display_tagline', '1');
	add_option('bxml_user_level', '3');
	add_option('bxml_user_level', '8');
	add_option('bxml_upload_dir', "/tmp/");
}

function bxml_parse()
{
	$uploadDir = get_option("bxml_upload_dir");
	$uploadFile = $uploadDir . $_FILES['userfile']['name'];	
	if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadFile))
	{
		$data = XML_unserialize(file_get_contents($uploadFile));
		foreach ($data['RECIPES']['RECIPE'] as $key=>$value) 
		{		
			$recipe['style'] = $data['RECIPES']['RECIPE']['STYLE']['NAME'];
			switch($key)
			{
				case 'NAME':
					$recipe['name'] = $value;
					break;
				case 'DATE':
					$recipe['date'] = $value;
					break;
				case 'TYPE':
					$recipe['type'] = $value;
					break;
				case 'BREWER':
					$recipe['brewer'] = $value;
					break;
				case 'DISPLAY_OG':
					$recipe['og'] = $value;
					break;
				case 'TASTE_RATING':
					$recipe['taste_rating'] = $value;
					break;
				case 'ABV':
					$recipe['abv'] = $value;
					break;
				case 'IBU':
					$recipe['ibu'] = $value;
					break;
				default:
					break;		
			}	
		}
		if(isset($_POST['action']) && $_POST['action'] == 'insert')
			insertRecipe($recipe, XML_serialize($data));		
		elseif (isset($_POST['action']) && $_POST['action'] == 'update')
		{
			$recipe['id'] = $_POST['id'];
			updateRecipe($recipe, XML_serialize($data));
		}
	    print "File is valid, and was successfully uploaded.";
	    
	} 
	else 
	{ 
		print "Possible file upload attack! Here's some debugging info: "; 
		print_r($_FILES); 
	}  
}

function bxml_options_panel()
{
	add_option('bxml_display_tagline', '1');
	add_option('bxml_user_level', '3');
	add_option('bxml_user_level', '8');
	add_option('bxml_upload_dir', "/tmp/");
	  if (isset($_POST['info_update'])) {
	    ?><div class="updated"><p><strong><?php 
		if(isset($_POST['user_level']) && isset($_POST['user_level_del']))
		{
			update_option('bxml_user_level', $_POST['user_level']);
			_e('User Level option updated!<br>', 'Localization name');
			update_option('bxml_display_tagline', $_POST['tagline']);
			update_option('bxml_user_level_del', $_POST['user_level_del']);
			update_option('bxml_upload_dir', $_POST['upload_dir']);
		}
		else
		{
			_e('Please input a user level and try again.');
		}

	    ?></strong></p></div><?php
		} ?>
	<div class=wrap>
	  <form method="post">
	    <h2>BeerXML Options</h2>
	     <fieldset name="set1">
			User level of users able to delete recipes: <input type="text" name="user_level_del" value="<? print get_option('bxml_user_level_del'); ?>" />
			<br/>
			User level of users able to upload and modify recipes: <input type="text" name="user_level" value="<? print get_option('bxml_user_level'); ?>" />
			<br/>
			Temp directory to upload files to (make sure this is writable by your web server user): <input type="text" name="upload_dir" value="<? print get_option('bxml_upload_dir'); ?>" />
			<br/>
			<input type="checkbox" name="tagline" id="tagline" value="1" <?php checked('1', get_settings('bxml_display_tagline')); ?>>Display credit tagline?
			<br/>
	     </fieldset>
	<div class="submit">
	  <input type="submit" name="info_update" value="<?php
	    _e('Update options', 'Localization name')
		?> »" /></div>
	  </form>
	 </div><?php
}

function bxml_add_pages()
{
	add_management_page('BeerXML', 'BeerXML', get_option('bxml_user_level'), basename(__FILE__), 'bxml_manage_page');	
	add_options_page('BeerXML', 'BeerXML Options', 8, basename(__FILE__), 'bxml_options_panel');
}

function bxml_manage_page()
{	
	global $table_prefix, $wpdb, $userdata;
	$table_name = $table_prefix . "beerxml";	
	get_currentuserinfo();

	if(isset($_POST['action']) && ($_POST['action'] == 'update' || $_POST['action'] == 'insert'))
	{
		if(!isset($_POST['upload_status']) && $_POST['upload_status'] != 'uploaded')
		{
			?>
			<form enctype="multipart/form-data" method="post">
			    <input type="hidden" name="MAX_FILE_SIZE" value="100000" />
			    <input type="hidden" name="action" value="update" />
			    <input type="hidden" name="upload_status" value="uploaded" />
			    <input type="hidden" name="id" value="<? print $_POST['id']; ?>" />
			    Choose a BeerXML file to upload: <input name="userfile" type="file" />
			    <input type="submit" value="Upload File" />
			</form>
			<?
		}
		else 
		{
			bxml_parse();
		}
		return;
	}	
	elseif (isset($_POST['action']) && $_POST['action'] == 'delete')
	{
		if($userdata->user_level >= get_option('bxml_user_level_del'))
		{
			if(isset($_POST['confirmed']) && $_POST['confirmed'] == 'true')
				deleteRecipe($_POST['id']);
			else 
			{
				?>
				<form method="post">
					<input type="hidden" name="action" value="delete" />
					<input type="hidden" name="id" value="<? print $_POST['id']; ?>" />
					<input type="hidden" name="confirmed" value="true" />
					<button type="submit">Really Delete!</button>
				</form>
				<?
				return;
			}
		}
		else
		{
			echo "You do not have enough permissions to do this.";
		}
	}
				
	$sort = $_REQUEST['sortby'];
	$query = "select id, name, date_brewed, brewer_name, o_gravity, ibu, type, alcohol, taste_rating, style from $table_name";
	$query = "$query order by date_brewed desc";
		
	$results = $wpdb->get_results($query, ARRAY_A); 
	
	if(isset($results))
	{
		?>
		<table width="100%">
		<tr>
			<th>Name</th>
			<th>Style</th>
			<th>Brewer Name</th>
			<th>O.G.</th>
			<th>IBUs</th>
			<th>Type</th>
			<th>Alcohol Content</th>
			<th>Taste Rating</th>
			<th>Date Brewed</th>
			<th>Actions</th> 
		</tr>
		<?php
		foreach ($results as $row) 
		{
		?>	
			<tr>
				<td align="center"><? print $row['name']; ?></td>
				<td align="center"><? print $row['style']; ?></td>
				<td align="center"><? print $row['brewer_name']; ?></td>
				<td align="center"><? print $row['o_gravity']; ?></td>
				<td align="center"><? print $row['ibu']; ?></td>
				<td align="center"><? print $row['type']; ?></td>
				<td align="center"><? print $row['alcohol']; ?></td>
				<td align="center"><? print $row['taste_rating']; ?></td>
				<td align="center"><? print $row['date_brewed']; ?></td>
				<td align="center">
					<form method="POST">
						<input type="hidden" name="id" value="<? print $row['id']; ?>" />
						<button name="action" value="update" type="submit">Update</button>
						<?if($userdata->user_level >= get_option('bxml_user_level_del')) { ?><button type="submit" name="action" value="delete">Delete</button><? } ?>
					</form>
				</td>
			</tr>
			<?	
		}
	}
	else 
	{
		print "No recipes in the database!";
	}
		?>
	<tr>
	<td colspan="10">
		<form enctype="multipart/form-data" method="post">
		    <input type="hidden" name="MAX_FILE_SIZE" value="100000" />
		    <input type="hidden" name="action" value="insert" />
		    <input type="hidden" name="upload_status" value="uploaded" />
		    Choose a BeerXML file to upload: <input name="userfile" type="file" />
		    <button type="submit">Upload File</button>
		</form>
	</td>
	</tr>
	</table>	
	<?
	print bxml_tagline();
}

function bxml_tagline()
{
	if(get_option('bxml_display_tagline') == 1)
		return "<br/><font size=-2>Powered by <a href=\"http://www.belchak.com/beerxml-plugin\">WordPress BeerXML Plugin</a> by <a href=\"http://www.belchak.com\">Ben Belchak</a></font>";
}

function deleteRecipe($id)
{
	global $table_prefix, $wpdb;
	$table_name = $table_prefix . "beerxml";
		
	$query = "delete from $table_name where id='$id';";
	$wpdb->query($query);
}

function bxml_list_recipes($content)
{
	global $table_prefix, $wpdb;
	$table_name = $table_prefix . "beerxml";
	
	if((isset($_REQUEST['action']) && $_REQUEST['action'] == 'show_recipe') || preg_match("|{recipe:\d+}|", $content, $matches))
	{
		if(isset($_REQUEST['id']))
			$id = $_REQUEST['id'];
		else
		{
			$text = $matches[0];
			$exploded = explode(":", $text);
			$exploded = explode("}", $exploded[1]);
			$id = $exploded[0];
		}
		$query = "select beerxml from $table_name where id = $id";
		$results = $wpdb->get_results($query, ARRAY_A); 
		if(sizeof($results) > 0)
		{
			foreach($results as $row)
			{
				$xml = $row['beerxml'];
			}
		}
		else
		{
			print "Recipe id <b>$id</b> was not found in the database.";
			return;
		}
		$xslt = xslt_create();
		
		//$xmlFile = "../xml/$beer.xml";
		//$xsltFile = "../xml/beerxml.xsl";
		
		$arguments = array(
			'/_xml' => $xml,
			'/_xsl' => file_get_contents("./wp-content/plugins/beerxml/xml/recipe_small.xsl"));
		$text =  xslt_process($xslt, 'arg:/_xml', 'arg:/_xsl', NULL, $arguments) . "". bxml_tagline();
		xslt_free($xslt);
		return preg_replace("|{recipe:\d+}|", $text, $content);
	}
	return $content;
}

function insertRecipe($recipe, $xml)
{
	global $table_prefix, $wpdb;
	$table_name = $table_prefix . "beerxml";
	$date_array = explode("/", $recipe['date']);
	$month = $date_array[0];
	$day = $date_array[1];
	$year = $date_array[2];
	
	$name = addslashes($recipe['name']);
	$sqlxml = addslashes($xml);
	$sqltype = addslashes($recipe['type']);
	$sqlbrewer = addslashes($recipe['brewer']);
	$sqlog = addslashes($recipe['og']);
	$sqltaste = addslashes($recipe['taste_rating']);
	$sqlabv = addslashes($recipe['abv']);	
	$sqlibu = addslashes($recipe['ibu']);
	$sqldate = $year ."-". $month . "-". $day;
	$sqlstyle = addslashes($recipe['style']);
	
	$query = "INSERT INTO $table_name ( `id` , `name` , `date_brewed` , `beerxml` , `type`, `brewer_name`, `o_gravity`, `taste_rating`, `alcohol`, `ibu`, `style`) ";
	$query .="VALUES ('','$name', '$sqldate', '$sqlxml', '$sqltype', '$sqlbrewer', '$sqlog', '$sqltaste', '$sqlabv', '$sqlibu', '$sqlstyle')";	
	$wpdb->query($query);
}

function updateRecipe($recipe, $xml)
{
	global $table_prefix, $wpdb;
	$table_name = $table_prefix . "beerxml";	
	
	$date_array = explode("/", $recipe['date']);
	$month = $date_array[0];
	$day = $date_array[1];
	$year = $date_array[2];
	
	$name = addslashes($recipe['name']);
	$sqlxml = addslashes($xml);
	$sqltype = addslashes($recipe['type']);
	$sqlbrewer = addslashes($recipe['brewer']);
	$sqlog = addslashes($recipe['og']);
	$sqltaste = addslashes($recipe['taste_rating']);
	$sqlabv = addslashes($recipe['abv']);	
	$sqlibu = addslashes($recipe['ibu']);
	$sqldate = $year ."-". $month . "-". $day;
	$sqlstyle = addslashes($recipe['style']);
	$id = $recipe['id'];
	
	$query = "update $table_name set `name`='$name', `date_brewed`='$sqldate', ";
	$query .= "`beerxml`='$sqlxml', `type`='$sqltype', `brewer_name`='$sqlbrewer', ";
	$query .= "`o_gravity`='$sqlog', `taste_rating`='$sqltaste', `alcohol`='$sqlabv', ";
	$query .= "`ibu`='$sqlibu', `style`='$sqlstyle' where id='$id'";
	
	$wpdb->query($query);
}

function bxml_outside_init()
{
	global $_GET;
	if($_REQUEST['a']=="bxml" && isset($_REQUEST['id']) && !isset($_REQUEST['show_xml']))
	{
		require_once(ABSPATH."wp-content/plugins/beerxml/recipe.php");
		exit;
	}
	else if($_REQUEST['a']=="bxml" && isset($_REQUEST['show_xml']))
	{
		require_once(ABSPATH."wp-content/plugins/beerxml/showxml.php");
		exit;
	}
	else if($_REQUEST['a']=="bxml")
	{
		require_once(ABSPATH."wp-content/plugins/beerxml/recipes.php");
		exit;
	}
}

function bxml_add_into_pages($content)
{
	if($content == '')
		return "<li class=\"pagenav\"><h2><a href=\"/?a=bxml\">".__("Beer Recipes")."</a></h2></li>";
	else
	{
		if(eregi("</ul></li>", $content))
			return str_replace("</ul></li>", "", $content)."<li class=\"page_item\"><a href=\"/?a=bxml\">".__("Beer Recipes")."</a></li></ul></li>";
	}
}

// Add hooks
add_action('activate_beerxml/beerxml.php','bxml_install');
add_action('admin_menu', 'bxml_add_pages');
add_filter('the_content', 'bxml_list_recipes', 1);
add_action('template_redirect', 'bxml_outside_init');
add_filter('wp_list_pages', 'bxml_add_into_pages');
?>
