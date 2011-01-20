<?php get_header(); ?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
        <td valign="top" width="80%">

        <div id="content" class="narrowcolumn">
	<h2>Recipe List:</h2><br/>
	<?
        global $table_prefix, $wpdb;
        $table_name = $table_prefix . "beerxml";

        $newmethod = $_REQUEST['method'];
        if(isset($newmethod))
        {
                switch($newmethod)
                {
                        case 'asc';
                                $method = "desc";
                                break;
                        case 'desc';
                                $method = "asc";
                                break;
                        default:
                                $method = "desc";
                                break;
                }
        }
        else
                $method = "desc";

        $sort = $_REQUEST['sortby'];
        $query = "select id, name, date_brewed, brewer_name, o_gravity, ibu, type, alcohol, taste_rating, style from $table_name";
        if(isset($sort))
                $query = "$query order by $sort $method";
        else
                $query = "$query order by date_brewed $method";

        $results = $wpdb->get_results($query, ARRAY_A);
        $text = "";
        if(isset($results))
        {
                $pageid = get_option('bxml_page_id');
                $text .= "<table width=\"100%\">
                <tr>
                        <th align=\"center\"><a href=\"?a=bxml&sortby=name&method=$method\">Name</a></th>
                        <th align=\"center\"><a href=\"?a=bxml&sortby=style&method=$method\">Style</a></th>
                        <th align=\"center\"><a href=\"?a=bxml&sortby=brewer_name&method=$method\">Brewer</a></th>
                        <th align=\"center\"><a href=\"?a=bxml&sortby=o_gravity&method=$method\">O.G.</a></th>
                        <th align=\"center\"><a href=\"?a=bxml&sortby=ibu&method=$method\">IBUs</a></th>
                        <th align=\"center\"><a href=\"?a=bxml&sortby=taste_rating&method=$method\">Rating</a></th>
                        <th align=\"center\"><a href=\"?a=bxml&sortby=date_brewed&method=$method\">Date</a></th>
                        <th align=\"center\">XML</th>
                </tr>";
                foreach ($results as $row)
                {
                        $id = $row['id'];
                        $style = $row['style'];
                        $b_name = $row['brewer_name'];
                        $o_gravity = $row['o_gravity'];
                        $ibu = $row['ibu'];
                        $rating = $row['taste_rating'];
                        $name = $row['name'];

                        $date = explode("-", $row['date_brewed']);
                        $text .= "<tr>
                                <td valign=\"top\" align=\"center\"><a href=\"?a=bxml&id=$id\">$name</a>
                                </td>
                                <td valign=\"top\" align=\"center\">$style</td>
                                <td valign=\"top\" align=\"center\">$b_name</td>
                                <td valign=\"top\" align=\"center\">$o_gravity</td>
                                <td valign=\"top\" align=\"center\">$ibu</td>
                                <td valign=\"top\" align=\"center\">$rating</td>
                                <td valign=\"top\" align=\"center\">$date[1]/$date[2]/$date[0]</td>
                                <td valign=\"top\" align=\"center\"><a href=\"?a=bxml&id=$id&show_xml=true\" target=\"_new\"><img border=0 src=\"/wp-content/plugins/beerxml/images/XML.gif\"></img></a>
                                </td>
                        </tr>";
                }
                $text .= "</td>
                </tr>
                </table>";
        }
        else
        {
                $text = "No recipes in the database!";
        }
        $text .= bxml_tagline();
	print $text;
	?>
        </div>
        </td>
        <td valign="top">
                <?php get_sidebar(); ?>
        </td>
</tr></table>

<?php get_footer(); ?>
