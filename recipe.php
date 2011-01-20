<?php 
	get_header(); 

	global $wpdb, $table_prefix;
		$table_name = $table_prefix . "beerxml";
                if(isset($_REQUEST['id']))
                        $id = $_REQUEST['id'];
                $query = "select beerxml from $table_name where id = $id";
                $results = $wpdb->get_results($query, ARRAY_A);
?>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
        <td valign="top" width="80%">

        <div id="content" class="narrowcolumn" style="margin-top: 5px;">
	<?
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
                        '/_xsl' => file_get_contents("./wp-content/plugins/beerxml/xml/beerxml.xsl"));
                $content = "";
                $content =  xslt_process($xslt, 'arg:/_xml', 'arg:/_xsl', NULL, $arguments) . "". bxml_tagline();
                xslt_free($xslt);	
		print $content;
	?>
        </div>
        </td>
        <td valign="top">
                <?php get_sidebar(); ?>
        </td>
</tr></table>

<?php get_footer(); ?>
