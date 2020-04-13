<?php




$xml = file_get_contents('./pickup.xml', true);
//echo $xml;
$url = "https://xmlpitest-ea.dhl.com/XMLShippingServlet";




$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);

// For xml, change the content-type.
curl_setopt ($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));

curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // ask for results to be returned

// Send to remote and return data to caller.
$result = curl_exec($ch);
curl_close($ch);
var_dump( $result);


die;


//setting the curl parameters.
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
// Following line is compulsary to add as it is:
curl_setopt($ch, CURLOPT_POSTFIELDS, "xmlRequest=" . $xml);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
$data = curl_exec($ch);
curl_close($ch);

//convert the XML result into array
$array_data = json_decode(json_encode(simplexml_load_string($data)), true);

print_r('<pre>');
print_r($array_data);
print_r('</pre>');

die;




	$dom = new DOMDocument();
		$dom->encoding = 'utf-8';
		$dom->xmlVersion = '1.0';
		$dom->formatOutput = true;
	$xml_file_name = 'movies_list.xml';
		$root = $dom->createElement('Movies');
		$movie_node = $dom->createElement('movie');
		$attr_movie_id = new DOMAttr('movie_id', '5467');
		$movie_node->setAttributeNode($attr_movie_id);
	$child_node_title = $dom->createElement('Title', 'The Campaign');
		$movie_node->appendChild($child_node_title);
		$child_node_year = $dom->createElement('Year', 2012);
		$movie_node->appendChild($child_node_year);
	$child_node_genre = $dom->createElement('Genre', 'The Campaign');
		$movie_node->appendChild($child_node_genre);
		$child_node_ratings = $dom->createElement('Ratings', 6.2);
		$movie_node->appendChild($child_node_ratings);
		$root->appendChild($movie_node);
		$dom->appendChild($root);
	$dom->save($xml_file_name);
	echo "$xml_file_name has been successfully created";
?>