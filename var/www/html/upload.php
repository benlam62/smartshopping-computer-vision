<head>
	<meta charset="UTF-8" />
	<title>Find My Clock</title>
	<link rel="stylesheet" href="style.css">
</head>
<style>
.resizeimg{
	max-width: 1000px;
	width: 100%;
	height: auto;
}
.resizeimg2{
	max-width: 300px;
	width: 100%;
	height: auto;
}
</style>
<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

	function getFilename($str) {
		 $i = strrpos($str,".");
		 if (!$i) { return ""; }
		 $filename = substr($str,0,$i);
		 return $filename;
	} 

	$target_path = "./upload/";

	$target_path = $target_path . basename( $_FILES['uploadedfile']['name']);

	if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
	    //echo "The file ".  basename( $_FILES['uploadedfile']['name']). 
	    //" has been uploaded<br>";
	} else{
	    echo "There was an error uploading the file, please try again!<br>";
	}

	$imageID = getFilename($_FILES['uploadedfile']['name']);
	//echo("$imageID <br>");
        $json = file_get_contents("http://localhost:8080/up?s={$imageID}");
	//$json = '{"object":"3"}';
	$data = json_decode($json, true);
	$foundclass = explode(" ", $data['object']);
	//var_dump($data);
	$classname = array (
		array(1, 'Ring-Bell-Clock'),
		array(2, 'Matchbox-Clock'),
		array(3, 'Digital-Clock-w-Thermometer'));

	$classobject = array (
		array(1, 1, "c1-o1.jpg", "http://item.taobao.com/item.htm?id=520397393718&ali_refid=a3_420841_1006:1108242385:N:%E9%97%B9%E9%92%9F+%E8%A1%A8:e6d719ba90f19d4f5119a232337a3004&ali_trackid=1_e6d719ba90f19d4f5119a232337a3004&spm=a231k.7717563.1998539463.153.VWxYii"),
		array(1, 2, "c1-o2.jpg", "http://item.taobao.com/item.htm?spm=a230r.1.14.289.7tP71m&id=15640385792&ns=1&abbucket=1#detail"),
		array(1, 3, "c1-o3.jpg", "http://item.taobao.com/item.htm?id=25678392016&ali_refid=a3_420841_1006:1104582043:N:%E5%B0%8F%E9%97%B9%E9%92%9F:ea807e6053e511ad2a827833cbdd542d&ali_trackid=1_ea807e6053e511ad2a827833cbdd542d&spm=a231k.7717563.1998539463.256.VWxYii"),
		array(2, 1, "c2-o1.jpg", "http://item.taobao.com/item.htm?id=43733462040&ali_refid=a3_420841_1006:1104561527:N:%E9%97%B9%E9%92%9F+%E5%B0%8F:bf478c4b79b1e3d0eae478e996a6b3b5&ali_trackid=1_bf478c4b79b1e3d0eae478e996a6b3b5&spm=a231k.7717563.1998539463.1086.VWxYii"),		
		array(2, 2, "c2-o2.jpg", "http://item.taobao.com/item.htm?id=9736054802&ali_refid=a3_420841_1006:1103427928:N:%E9%97%B9%E9%92%9F%E5%B0%8F:67fed0576215ee1278e2b8a51a5b940d&ali_trackid=1_67fed0576215ee1278e2b8a51a5b940d&spm=a231k.7717563.1998539463.153.1rE3Rv"),
		array(2, 3, "c2-o3.jpg", "http://item.taobao.com/item.htm?id=18301880021&ali_refid=a3_420841_1006:1102228631:N:%E5%B0%8F%E9%97%B9%E9%92%9F:d3c4c008924f1a198a33baccbd76c2ee&ali_trackid=1_d3c4c008924f1a198a33baccbd76c2ee&spm=a231k.7717563.1998539463.171.3ErtCT"),
		array(3, 1, "c3-o1.jpg", "http://detail.tmall.com/item.htm?id=44043830135&ali_refid=a3_420841_1006:1109722987:N:%E9%9D%99%E9%9F%B3%E9%92%9F:3db0295e418f5ccb76b95dfc403d5407&ali_trackid=1_3db0295e418f5ccb76b95dfc403d5407&spm=a231k.7717563.1998539463.16.Kg334y"),
		array(3, 2, "c3-o2.jpg", "http://item.taobao.com/item.htm?id=22441267923&ali_refid=a3_420841_1006:1104815013:N:%E9%9D%99%E9%9F%B3%E7%94%B5%E5%AD%90%E9%97%B9%E9%92%9F:3d4e232b6995a5ff8b80827c3c6150c2&ali_trackid=1_3d4e232b6995a5ff8b80827c3c6150c2&spm=a231k.7717563.1998539463.744.Kg334y"),
		array(3, 3, "c3-o3.jpg", "http://item.taobao.com/item.htm?id=43468505243&ali_refid=a3_420841_1006:1102344778:N:led%E7%94%B5%E5%AD%90%E9%92%9F%E5%A4%9C%E5%85%89%E9%97%B9%E9%92%9F:4083a7541dba18d3c076142e55b58b9f&ali_trackid=1_4083a7541dba18d3c076142e55b58b9f&spm=a231k.7717563.1998539463.368.Kg334y"));

	$foundclass_count = count($foundclass);	
	$classname_count = count($classname);
	$classobject_count = count($classobject);
	$foundclass_text = '';
	
	$foundclass_count = count($foundclass);	
	$classname_count = count($classname);
	$classobject_count = count($classobject);
	$foundclass_text = '';
	
	for ($i=0; $i<$foundclass_count; $i++) {
		for ($j=0; $j<$classname_count; $j++) {
			if ($foundclass[$i] == $classname[$j][0]) {
				if ($foundclass_text == '') {
					$foundclass_text = $classname[$j][1];
				}
				else {
					$foundclass_text = $foundclass_text . ' & ' . $classname[$j][1];
				}
				break;
			}
		}
	}
	if ($foundclass[0] != 0) {
		echo "<h1>Find the " . $foundclass_text . "</h1><br>";
	}
	else {
		echo "<h1>Nothing is found</h1><br>";
	}

	echo "<img src=\"segments/" . $imageID . ".jpg\" class=\"resizeimg\"><br><br>";

	if ($foundclass[0] != 0) {
		echo "<h2> Similar clocks from the online shop</h2><br>";
		for ($i=0; $i<$foundclass_count; $i++) {
			for ($j=0; $j<$classobject_count; $j++) {
			    if ($foundclass[$i] == $classobject[$j][0]) {
				echo "<img src=\"clockimg/" . $classobject[$j][2] . "\" class=\"resizeimg2\">";
				echo "<a href=\"". $classobject[$j][3] . "\"> Details</a><br><br>";
			    }
			}
		}
	}
?>
