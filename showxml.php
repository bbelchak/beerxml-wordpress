<?
        global $table_prefix, $wpdb;
        $table_name = $table_prefix . "beerxml";

        $query = "select beerxml from $table_name where id = ".$_REQUEST['id'];
        $xml = $wpdb->get_col($query);
	header("Content-type: text/xml");
	print $xml[0];
?>
