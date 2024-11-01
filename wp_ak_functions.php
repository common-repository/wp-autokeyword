<?php
/****************************************************************************/
// Register My JavaScript and WordPress Ajax File
/****************************************************************************/
function wpak_my_enqueue() {
    wp_enqueue_script( 'ajax-script', plugin_dir_url( __FILE__ ).'wp_ak_ajax.js', array('jquery') );
    wp_localize_script( 'ajax-script', 'my_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}
add_action( 'admin_enqueue_scripts', 'wpak_my_enqueue' );



/****************************************************************************/
// Get The POST Parameter, Sanitize then Validate
/****************************************************************************/
// Get The POST Parameter, Sanitize, Validate then Length
function wpak_postSanValLen($wpak_incomingParameter, $wpak_dataType, $wpak_len, $wpak_return) {
	if(!isset($wpak_incomingParameter)){	// Check If Incoming Parameter Exists
		$wpak_incomingParameter = "";
	} else {
		if(strstr($wpak_incomingParameter, "\n")) {
			$wpak_incomingParameter = sanitize_textarea_field($wpak_incomingParameter); 	// Sanitize TextArea Data
		} else {
			$wpak_incomingParameter = sanitize_text_field($wpak_incomingParameter); 	// Sanitize Input Data
		}
		$wpak_incomingParameter = (strlen($wpak_incomingParameter) > $wpak_len) ? substr($wpak_incomingParameter,0,$wpak_len) : $wpak_incomingParameter;	// Strip To Required Length
		
		if (ctype_alnum($wpak_incomingParameter) == true && $wpak_dataType == "alnum" ) {	// Check If Incoming Parameter Is alphanumeric character(s)
			$wpak_incomingParameter;
		} else if (ctype_alpha($wpak_incomingParameter) == true && $wpak_dataType == "alpha" ) {	// Check If Incoming Parameter Is alphabetic character(s)
			$wpak_incomingParameter;
		} else if (ctype_digit($wpak_incomingParameter) == true && $wpak_dataType == "digit" ) {	// Check If Incoming Parameter Is numeric character(s)
			$wpak_incomingParameter;
		} else if ($wpak_dataType == "allow" ) {	// Check If Incoming Parameter Is any character(s)
			$wpak_incomingParameter;
		} else {
			$wpak_incomingParameter = "";
		}
	}
	$wpak_incomingParameter = (strlen($wpak_incomingParameter) > 0) ? $wpak_incomingParameter : $wpak_return;	// Check If Its Empty Then Return User Desired Value
	return $wpak_incomingParameter;
}
	
	
	
/****************************************************************************/
// Suggest Keyword Main Function
/****************************************************************************/
function wpak_Keyword($wpak_SingleKeywordCount, $wpak_DoubleKeywordCount, $wpak_TripleKeywordCount, $wpak_ArticleContent){
	global $wpdb;	// Get All Database Details Of This WordPress
	$wpak_table_name = $wpdb->prefix . 'wpak_keywords';	// Get Table Names	
	
	$wpak_arrayKeyWords = array();	// Declearing 1D Array	

	// Data Cleaning In String
	$wpak_ArticleContent = str_replace(array("\\n\\r", "\\n", "\\r", "-", "_"), ' ', $wpak_ArticleContent);	// Replace Chracters With Space
	$wpak_ArticleContent = preg_replace("/[^a-zA-Z\s]/", "", $wpak_ArticleContent);	// Delete All Chracters Excep Alphabets
	$wpak_ArticleContent = preg_replace('/\s\s+/', ' ', $wpak_ArticleContent);	// Delete More Then One White-Space
	$wpak_ArticleContent = trim(preg_replace("/(^|\s+)(\S(\s+|$))+/", " ", $wpak_ArticleContent));	// Delete Single Chracter Word

	// Data Splitting In Array
	$wpak_splitter = " ";
	$wpak_arraySingleWords = array();	// Declearing 1D Array
	$wpak_arraySingleWords = explode($wpak_splitter, $wpak_ArticleContent);
	$wpak_arraySingleWords = array_map('strtolower', $wpak_arraySingleWords);
	
	/************************/
	//*** Single Keyword ***//	
	/************************/
	
	// Keyword Sorting
	$wpak_arraySingleWordsFrequency = array();	// Declearing 2D Array	
	$wpak_arraySingleWordsCount = array_count_values($wpak_arraySingleWords);
	arsort($wpak_arraySingleWordsCount);	// Sorting Array Value In DESC Order
	
	// Converting The Count 1D Array In 2D Array For Easy Accessing
	$i = 0;
	foreach ($wpak_arraySingleWordsCount  as $key => $value)  {
		$wpak_arraySingleWordsFrequency[$i][0] = $key;
		$wpak_arraySingleWordsFrequency[$i][1] = $value;
		$i++;
	}
		
	// Check If A Word Exists In Databse Or Not
	$i = 0;
	$x = 0;
	do {
		$wpak_checkKeyword = $wpak_arraySingleWordsFrequency[$x][0];	// Pick Up One Word		
		// Find Word In Database
		$wpak_result = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpak_table_name." WHERE WPAK_Keyword = '".$wpak_checkKeyword."'");
		if($wpak_result === false){
			echo "<div class='exe_error'>ERROR: ".$wpdb->last_error.". For More Info, Please Contact Us On <a href='mailto:info@exeideas.net?cc=support@exeideas.com&amp;subject=WPAK%20WordPress%20Plugin%20UPDATE&amp;body=Hello%20WPAK%20Admin%3B%0A%0A%3C!--%20Your%20Msg%20Here%20With%20All%20The%20Details%20Of%20Updates%20We%20Needs%20--%3E%0A%0AThanks%20and%20Regards.'>info@exeideas.net</a></div>";
			exit();
			die();
		} else {			
			if($wpak_result == 0) {
				// row not found, do stuff...
			} else {
				// do other stuff...
				$i++;	// Count One Keyword
				array_push($wpak_arrayKeyWords, $wpak_checkKeyword);
			}			
		}		
		$x++;
		// Break The Loop If All Words Are Checked
		if ($x >= sizeof($wpak_arraySingleWordsFrequency)){
			break;
		}
		// Break The Loop If Desired Keywords Are Found
		if ($i >= $wpak_SingleKeywordCount){
			break;
		}
	} while (true);
	
	/************************/
	//*** Double Keyword ***//	
	/************************/
	
	// Double Word Array Generation
	$wpak_arrayDoubleWords = array();	// Declearing 1D Array
	for ($i = 0; $i <= (sizeof($wpak_arraySingleWords))-2; $i++) {
		$wpak_arrayDoubleWords[$i] = $wpak_arraySingleWords[$i]. " ". $wpak_arraySingleWords[$i+1];
	}
	
	// Keyword Sorting
	$wpak_arrayDoubleWordsFrequency = array();	// Declearing 2D Array	
	$wpak_arrayDoubleWordsCount = array_count_values($wpak_arrayDoubleWords);
	arsort($wpak_arrayDoubleWordsCount);	// Sorting Array Value In DESC Order
	
	// Converting The Count 1D Array In 2D Array For Easy Accessing
	$i = 0;
	foreach ($wpak_arrayDoubleWordsCount  as $key => $value)  {
		$arrayDoubleWordsFrequency[$i][0] = $key;
		$arrayDoubleWordsFrequency[$i][1] = $value;
		$i++;
	}
		
	// Check If A Word Exists In Databse Or Not
	$i = 0;
	$x = 0;
	do {
		$wpak_checkKeyword = $wpak_arrayDoubleWordsFrequency[$x][0];	// Pick Up One Word		
		// Find Word In Database
		$wpak_result = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpak_table_name." WHERE WPAK_Keyword = '".$wpak_checkKeyword."'");
		if($wpak_result === false){
			echo "<div class='exe_error'>ERROR: ".$wpdb->last_error.". For More Info, Please Contact Us On <a href='mailto:info@exeideas.net?cc=support@exeideas.com&amp;subject=WPAK%20WordPress%20Plugin%20UPDATE&amp;body=Hello%20WPAK%20Admin%3B%0A%0A%3C!--%20Your%20Msg%20Here%20With%20All%20The%20Details%20Of%20Updates%20We%20Needs%20--%3E%0A%0AThanks%20and%20Regards.'>info@exeideas.net</a></div>";
			exit();
			die();
		} else {			
			if($wpak_result == 0) {
				// row not found, do stuff...
			} else {
				// do other stuff...
				$i++;	// Count One Keyword
				array_push($wpak_arrayKeyWords, $wpak_checkKeyword);
			}			
		}		
		$x++;
		// Break The Loop If All Words Are Checked
		if ($x >= sizeof($wpak_arrayDoubleWordsFrequency)){
			break;
		}
		// Break The Loop If Desired Keywords Are Found
		if ($i >= $wpak_DoubleKeywordCount){
			break;
		}
	} while (true);
	
	/************************/
	//*** Tripe Keyword ****//	
	/************************/
	
	// Triple Word Array Generation
	$wpak_arrayTripleWords = array();	// Declearing 1D Array
	for ($i = 0; $i <= (sizeof($wpak_arraySingleWords))-3; $i++) {
		$wpak_arrayTripleWords[$i] = $wpak_arraySingleWords[$i]. " ". $wpak_arraySingleWords[$i+1]. " ". $wpak_arraySingleWords[$i+2];
	}
	
	// Keyword Sorting
	$wpak_arrayTripleWordsFrequency = array();	// Declearing 2D Array	
	$wpak_arrayTripleWordsCount = array_count_values($wpak_arrayTripleWords);
	arsort($wpak_arrayTripleWordsCount);	// Sorting Array Value In DESC Order
	
	// Converting The Count 1D Array In 2D Array For Easy Accessing
	$i = 0;
	foreach ($wpak_arrayTripleWordsCount  as $key => $value)  {
		$wpak_arrayTripleWordsFrequency[$i][0] = $key;
		$wpak_arrayTripleWordsFrequency[$i][1] = $value;
		$i++;
	}
		
	// Check If A Word Exists In Databse Or Not
	$i = 0;
	$x = 0;
	do {
		$wpak_checkKeyword = $wpak_arrayTripleWordsFrequency[$x][0];	// Pick Up One Word		
		// Find Word In Database
		$wpak_result = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpak_table_name." WHERE WPAK_Keyword = '".$wpak_checkKeyword."'");
		if($wpak_result === false){
			echo "<div class='exe_error'>ERROR: ".$wpdb->last_error.". For More Info, Please Contact Us On <a href='mailto:info@exeideas.net?cc=support@exeideas.com&amp;subject=WPAK%20WordPress%20Plugin%20UPDATE&amp;body=Hello%20WPAK%20Admin%3B%0A%0A%3C!--%20Your%20Msg%20Here%20With%20All%20The%20Details%20Of%20Updates%20We%20Needs%20--%3E%0A%0AThanks%20and%20Regards.'>info@exeideas.net</a></div>";
			exit();
			die();
		} else {			
			if($wpak_result == 0) {
				// row not found, do stuff...
			} else {
				// do other stuff...
				$i++;	// Count One Keyword
				array_push($wpak_arrayKeyWords, $wpak_checkKeyword);
			}			
		}		
		$x++;
		// Break The Loop If All Words Are Checked
		if ($x >= sizeof($wpak_arrayTripleWordsFrequency)){
			break;
		}
		// Break The Loop If Desired Keywords Are Found
		if ($i >= $wpak_TripleKeywordCount){
			break;
		}
	} while (true);
	
	/***************************/
	//*** Return All Keywords ***//	
	/***************************/	
	$wpak_gussedKeywords = implode(",", $wpak_arrayKeyWords);
	return $wpak_gussedKeywords;	
}



/****************************************************************************/
// Keyword Selector Tool
/****************************************************************************/
/* Register This Function When This File Is Loaded To Call By WordPress AJAX */
add_action('wp_ajax_nopriv_wpakGuessKeyword', 'wpak_GuessKeyword');	// For Web Visitors
add_action('wp_ajax_wpakGuessKeyword', 'wpak_GuessKeyword');	// For Admin User

function wpak_GuessKeyword(){
	global $wpdb;	// Get All Database Details Of This WordPress
	$wpak_table_name = $wpdb->prefix . 'wpak_keywords';	// Get Table Names	
	
	$wpak_arrayKeyWords = array();	// Declearing 1D Array	
	
	// Get Incoming Data & Escape User Inputs For Security
	$wpak_ArticleContent = wpak_postSanValLen($_POST['wpak_ArticleContent'], "allow", 10000, "");
	$wpak_SingleKeywordCount = wpak_postSanValLen($_POST['wpak_SingleKeywordCount'], "digit", 2, 99);
	$wpak_DoubleKeywordCount = wpak_postSanValLen($_POST['wpak_DoubleKeywordCount'], "digit", 2, 99);
	$wpak_TripleKeywordCount = wpak_postSanValLen($_POST['wpak_TripleKeywordCount'], "digit", 2, 99);
	
	echo "<div class='exe_notice'>SUGGGESTED KEYWORDS: <b>";
		echo wpak_Keyword($wpak_SingleKeywordCount, $wpak_DoubleKeywordCount, $wpak_TripleKeywordCount, $wpak_ArticleContent);
	echo "</b></div>";
	
	echo "<div class='exe_success'>SUCCESS: All Keywords Are Suggested Successfully. For More Info, Please Contact Us On <a href='mailto:info@exeideas.net?cc=support@exeideas.com&amp;subject=WPAK%20WordPress%20Plugin%20UPDATE&amp;body=Hello%20WPAK%20Admin%3B%0A%0A%3C!--%20Your%20Msg%20Here%20With%20All%20The%20Details%20Of%20Updates%20We%20Needs%20--%3E%0A%0AThanks%20and%20Regards.'>info@exeideas.net</a></div>";	
	wp_die();	
}



/****************************************************************************/
// Bulk Post Keyword Adder
/****************************************************************************/
/* Register This Function When This File Is Loaded To Call By WordPress AJAX */
add_action('wp_ajax_nopriv_wpakBulkKeyword', 'wpak_BulkKeyword');	// For Web Visitors
add_action('wp_ajax_wpakBulkKeyword', 'wpak_BulkKeyword');	// For Admin User

function wpak_BulkKeyword(){
	global $wpdb;	// Get All Database Details Of This WordPress
	$wpak_table_name = $wpdb->prefix . 'wpak_keywords';	// Get Table Names
	
	//Collecting Info From Below Form
	$wpak_SingleKeywordCount = wpak_postSanValLen($_POST['wpak_SingleKeywordCount'], "digit", 2, 99);
	$wpak_DoubleKeywordCount = wpak_postSanValLen($_POST['wpak_DoubleKeywordCount'], "digit", 2, 99);
	$wpak_TripleKeywordCount = wpak_postSanValLen($_POST['wpak_TripleKeywordCount'], "digit", 2, 99);
	$wpak_StartFrom = wpak_postSanValLen($_POST['wpak_StartFrom'], "digit", 5, 0);
	$wpak_EndTo = wpak_postSanValLen($_POST['wpak_EndTo'], "digit", 5, 500);
	
	$wpak_custom_field = 'wp_ak_meta_keywords';	
	
	echo "<ul style='max-height:300px;overflow:auto;border:1px solid #bfbfbf;'>";
	echo "<div style='background: url(".plugin_dir_url( __FILE__ )."images/Notice-Icon.png) no-repeat #cbe0f4 5px 50%;padding:5px 30px;background-size:20px;border:2px solid #96b6d3;color:#286eae;'><b> Status Log...</b></div>";
	// Run Over The Loop Of Desired Posts
	$wpak_query = new WP_Query( array( 'posts_per_page' => $wpak_EndTo, 'offset' => $wpak_StartFrom, 'post_status' => 'publish' ) );
	// Start Looping All WP Posts
	while ($wpak_query -> have_posts()) : $wpak_query -> the_post();
		// Do Whatever We Want To Do With Every Posts
		
		// Get Each Post ID
		$wpak_wpPostID =  get_the_ID();
		/*	
			Check If MetaKey Exists
				Check If MetaValue Exists
					Show The MetaValue (If Empty Then Show Post Categories)
				If Not Then
					Update The Meta Value
			If Not Then
				Insert And Show The MetaKey/MetaValue
		*/	
		// Checking That Is Meta Key In Generated Or Not
		$wpak_checkMetaKey = $wpdb->get_results($wpdb->prepare("SELECT meta_key FROM $wpdb->postmeta WHERE post_id = '%d' and `meta_key` = '%s'", $wpak_wpPostID, $wpak_custom_field));
		if (!empty($wpak_checkMetaKey[0]->meta_key)) {
			//$wpak_MetaKeyAvail = $wpak_checkMetaKey[0]->meta_key;
			// Checking That Is Upper Meta Key Contain Meta Value
			$wpak_checkMetaValue = $wpdb->get_results($wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE post_id = '%d' and `meta_key` = '%s'", $wpak_wpPostID, $wpak_custom_field));
			if (!empty($wpak_checkMetaValue[0]->meta_value)){
				$wpak_MetaValueAvail = $wpak_checkMetaValue[0]->meta_value;
				// If There Are No Keyword Suggested By This Tool Then Show Post Categories
				echo "<li style='list-style-position:inside;margin-bottom:0px;'><div style='background: url(".plugin_dir_url( __FILE__ )."images/Warning-Icon.png) no-repeat #fff6bf 5px 50%;padding:5px 30px;background-size:20px;border:2px solid #ffd324;color:#817134;'>Meta Keywords Of <a href='".get_permalink( get_the_ID() )."' target='_blank'>";
				the_title();
				echo "\"</a> Are Already Generated In Your WordPress Post ID: <b>".$wpak_wpPostID."</b> As <b>".$wpak_MetaValueAvail."</b>.</div></li>"; 					
			} else {			
				//$wpak_MetaValueAvail = "";
				// Update The New Guessed Keywords 				
				$wpak_ArticleContent = get_the_content();	// Get The Post Content
				$wpak_keywordsToInsert = wpak_Keyword($wpak_SingleKeywordCount, $wpak_DoubleKeywordCount, $wpak_TripleKeywordCount, $wpak_ArticleContent);	// Get The Gussedd Keywords
				// Insert Meta Keyword Tag To Same URL Post
				if($wpdb->query($wpdb->prepare("UPDATE $wpdb->postmeta SET meta_value = '%s' WHERE post_id = '%d' AND meta_key = '%s' ",$wpak_keywordsToInsert,$wpak_wpPostID,$wpak_custom_field)) === FALSE)
				{
					// Query Is Not Successfull
					echo "<div class='exe_error'>ERROR: (Update Query) '".$wpdb->last_error."'. For More Info, Please Contact Us On <a href='mailto:info@exeideas.net?cc=support@exeideas.com&amp;subject=WPAK%20WordPress%20Plugin%20UPDATE&amp;body=Hello%20WPAK%20Admin%3B%0A%0A%3C!--%20Your%20Msg%20Here%20With%20All%20The%20Details%20Of%20Updates%20We%20Needs%20--%3E%0A%0AThanks%20and%20Regards.'>info@exeideas.net</a></div>";
				}
				else {
					// Query Is Successfull					
					if ($wpak_keywordsToInsert != "") {
						echo "<li style='list-style-position:inside;margin-bottom:0px;'><div style='background: url(".plugin_dir_url( __FILE__ )."images/Success-Icon.png) no-repeat #E6EFC2 5px 50%;padding:5px 30px;background-size:20px;border:2px solid #C6D880;color:#529214;'>Meta Keywords Of <a href='".get_permalink( get_the_ID() )."' target='_blank'>";
						the_title();
						echo "\"</a> Are Now Generated In Your WordPress Post ID: <b>".$wpak_wpPostID."</b> As <b>".$wpak_keywordsToInsert."</b>.</div></li>"; 
					} else {
						echo "<li style='list-style-position:inside;margin-bottom:0px;'><div style='background: url(".plugin_dir_url( __FILE__ )."images/Success-Icon.png) no-repeat #E6EFC2 5px 50%;padding:5px 30px;background-size:20px;border:2px solid #C6D880;color:#529214;'>Meta Keywords Of <a href='".get_permalink( get_the_ID() )."' target='_blank'>";
						the_title();
						echo "\"</a> Are Not Generated In Your WordPress Post ID: <b>".$wpak_wpPostID."</b> So Your Post Categories <b>";					
						$wpak_postCategories = get_the_category();
						foreach((array)$wpak_postCategories as $category) :
							echo $category->name . ',';
						endforeach;
						echo "</b> Will Be The Keywords</div></li>";
					}
				}	
				// Update The New Guessed Keywords 
			}	
			// Checking That Is Upper Meta Key Contain Meta Value
			
		} else {
			//$wpak_MetaKeyAvail = "";
			// Insert MetKey/MetaValue Or New Guessed Keywords 
			$wpak_ArticleContent = get_the_content();	// Get The Post Content
			$wpak_keywordsToInsert = wpak_Keyword($wpak_SingleKeywordCount, $wpak_DoubleKeywordCount, $wpak_TripleKeywordCount, $wpak_ArticleContent);	// Get The Gussedd Keywords		
			// Insert Meta Keyword Tag To Same URL Post
			if($wpdb->query($wpdb->prepare("UPDATE $wpdb->postmeta SET meta_value = '%s' WHERE post_id = '%d' AND meta_key = '%s' ",$wpak_keywordsToInsert,$wpak_wpPostID,$wpak_custom_field)) === FLASE)
			{
					// Query Is Not Successfull
					echo "<div class='exe_error'>ERROR: (Update Query) '".$wpdb->last_error."'. For More Info, Please Contact Us On <a href='mailto:info@exeideas.net?cc=support@exeideas.com&amp;subject=WPAK%20WordPress%20Plugin%20UPDATE&amp;body=Hello%20WPAK%20Admin%3B%0A%0A%3C!--%20Your%20Msg%20Here%20With%20All%20The%20Details%20Of%20Updates%20We%20Needs%20--%3E%0A%0AThanks%20and%20Regards.'>info@exeideas.net</a></div>";
			}
			else {
				$wpdb->query($wpdb->prepare("INSERT INTO $wpdb->postmeta (`meta_id`, `post_id`, `meta_key`, `meta_value`) VALUE ('XXXXXX', '%d', '%s', '%s')", $wpak_wpPostID, $wpak_custom_field, $keywordsToInsert ));
				// Confirming The Migration Of Meta Description
				echo "<li style='list-style-position:inside;margin-bottom:0px;'><div style='background: url(".plugin_dir_url( __FILE__ )."images/Success-Icon.png) no-repeat #E6EFC2 5px 50%;padding:5px 30px;background-size:20px;border:2px solid #C6D880;color:#529214;'>Meta Keywords Of <a href='".get_permalink( get_the_ID() )."' target='_blank'>";
				the_title();
				echo "\"</a> Are Now Generated In Your WordPress Post ID: <b>".$wpak_wpPostID."</b> As <b>".$wpak_keywordsToInsert."</b>.</div></li>";
			}
			// Insert MetKey/MetaValue Or New Guessed Keywords

		}
	
		// Do Whatever We Want To Do With Every Posts	
	endwhile;
	echo "</ul>";
	wp_reset_postdata();	// Restore Original Post Data In Global Variable.
	wp_die();	// Stop Shoing 0 In Ajax Return.
}



/****************************************************************************/
// Add New Keywords
/****************************************************************************/
/* Register This Function When This File Is Loaded To Call By WordPress AJAX */
add_action('wp_ajax_nopriv_wpakAddKeyword', 'wpak_AddKeyword');	// For Web Visitors
add_action('wp_ajax_wpakAddKeyword', 'wpak_AddKeyword');	// For Admin User

function wpak_AddKeyword(){
	global $wpdb;	// Get All Database Details Of This WordPress
	$wpak_table_name = $wpdb->prefix . 'wpak_keywords';	// Get Table Name
	
	// Get Incoming Data & Escape User Inputs For Security
	//$wpak_WPAK_Id = $_POST['wpak_WPAK_Id'];
	$wpak_KeywordList = wpak_postSanValLen($_POST['wpak_KeywordList'], "allow", 10000, "");
	//$wpak_WPAK_Frequency = $_POST['wpak_WPAK_Frequency'];
	//$wpak_WPAK_RegDate = $_POST['wpak_WPAK_RegDate'];

	// Data Splitting In Array
	$wpak_splitter = "\r\n";
	$wpak_arrayKeyword = explode($wpak_splitter, $wpak_KeywordList);
	
	echo "<ul style='max-height:300px;overflow:auto;border:1px solid #bfbfbf;'>";
	echo "<div style='background: url(".plugin_dir_url( __FILE__ )."images/Notice-Icon.png) no-repeat #cbe0f4 5px 50%;padding:5px 30px;background-size:20px;border:2px solid #96b6d3;color:#286eae;'><b> Status Log...</b></div>";

	foreach ($wpak_arrayKeyword as $wpak_keyword) {
		
		// Split Keyword And Frequency
		$wpak_KeyFre = preg_split("/-/", $wpak_keyword); 
		//$wpak_WPAK_Keyword = $wpak_KeyFre[0];
		//$wpak_WPAK_Frequency = $wpak_KeyFre[1];
		$wpak_WPAK_Keyword = wpak_postSanValLen(trim($wpak_KeyFre[0]), "allow", 100, "");
		$wpak_WPAK_Frequency = wpak_postSanValLen(trim($wpak_KeyFre[1]), "digit", 30, 0);
		
		// Check If The Duplicate $WPAK_Keyword Is Already Available
		$wpak_keywordFromDatabase = $wpdb->get_row("SELECT * FROM ".$wpak_table_name." WHERE WPAK_Keyword = '".$wpak_WPAK_Keyword."'");
		if($wpak_keywordFromDatabase === FALSE){
			echo "<div class='exe_error'>ERROR: (Duplicate Query) '".$wpdb->last_error."'. For More Info, Please Contact Us On <a href='mailto:info@exeideas.net?cc=support@exeideas.com&amp;subject=WPAK%20WordPress%20Plugin%20UPDATE&amp;body=Hello%20WPAK%20Admin%3B%0A%0A%3C!--%20Your%20Msg%20Here%20With%20All%20The%20Details%20Of%20Updates%20We%20Needs%20--%3E%0A%0AThanks%20and%20Regards.'>info@exeideas.net</a></div>";
			die();
			exit();
		} else {
			// Check If The Duplicate $WPAK_Keyword Is Already Available
			if ($wpak_keywordFromDatabase->WPAK_Keyword == $wpak_WPAK_Keyword) {
				echo "<li style='list-style-position:inside;margin-bottom:0px;'><div style='background: url(".plugin_dir_url( __FILE__ )."images/Warning-Icon.png) no-repeat #fbe3e4 5px 50%;padding:5px 30px;background-size:20px;border:2px solid #fbc2c4;color:#d12f19;'>Duplicate Keyword: <b>'".$wpak_WPAK_Keyword."'</b> Found.</div></li>";
			} else {		
				// Insert The Data
				$garb = $wpdb->insert($wpak_table_name, array(
                                'WPAK_Keyword' => $wpak_WPAK_Keyword,
                                'WPAK_Frequency' => $wpak_WPAK_Frequency
                                ),array(
                                '%s',
                                '%d')	// Upper Both Coloum Should Contain This Format
				);
				if($garb === FALSE){	
					echo "<div class='exe_error'>ERROR: (Insert Query) '".$wpdb->last_error."'. For More Info, Please Contact Us On <a href='mailto:info@exeideas.net?cc=support@exeideas.com&amp;subject=WPAK%20WordPress%20Plugin%20UPDATE&amp;body=Hello%20WPAK%20Admin%3B%0A%0A%3C!--%20Your%20Msg%20Here%20With%20All%20The%20Details%20Of%20Updates%20We%20Needs%20--%3E%0A%0AThanks%20and%20Regards.'>info@exeideas.net</a></div>";
					die();
					exit();
				} else {
					// Success
					echo "<li style='list-style-position:inside;margin-bottom:0px;'><div style='background: url(".plugin_dir_url( __FILE__ )."images/Success-Icon.png) no-repeat #E6EFC2 5px 50%;padding:5px 30px;background-size:20px;border:2px solid #C6D880;color:#529214;'>Keyword: <b>'".$wpak_WPAK_Keyword."'</b> With Frequency <b>'".$wpak_WPAK_Frequency."'</b> Is Addedd In Your Database.</div></li>";
				}
				//  Insert The Data
			}
		}
	}
	echo "</ul>";
	// Check If The Duplicate Data Is Already Available
	unset($wpak_WPAK_Keyword);
	unset($wpak_WPAK_Frequency);
	
	// Show The Whole SQL Table
	//viewTable($connection);
	echo "<div class='exe_success'>SUCCESS: All Upper Keywords Are Added Successfully. For More Info, Please Contact Us On <a href='mailto:info@exeideas.net?cc=support@exeideas.com&amp;subject=WPAK%20WordPress%20Plugin%20UPDATE&amp;body=Hello%20WPAK%20Admin%3B%0A%0A%3C!--%20Your%20Msg%20Here%20With%20All%20The%20Details%20Of%20Updates%20We%20Needs%20--%3E%0A%0AThanks%20and%20Regards.'>info@exeideas.net</a></div>";
	wp_die();	// Stop Shoing 0 In Ajax Return.
}



/****************************************************************************/
// View Full Table Data (Pagination)
/****************************************************************************/
/* Register This Function When This File Is Loaded To Call By WordPress AJAX */
add_action('wp_ajax_nopriv_wpakPaginationData', 'wpak_PaginationData');	// For Web Visitors
add_action('wp_ajax_wpakPaginationData', 'wpak_PaginationData');	// For Admin User

function wpak_PaginationData(){
	global $wpdb;	// Get All Database Details Of This WordPress
	$wpak_table_name = $wpdb->prefix . 'wpak_keywords';	// Get Table Name
	
	// Get The POST Parameter, Sanitize then Validate
	$wpak_KeywordToFind = wpak_postSanValLen($_POST['wpak_KeywordToFind'], "alnum", 100, "");
	$wpak_displayPage = wpak_postSanValLen($_POST['wpak_displayPage'], "digit", 5, 1);
	$wpak_resultsPerPage = wpak_postSanValLen($_POST['wpak_resultsPerPage'], "digit", 3, 20);
	
	
	$wpak_previous_page = $wpak_displayPage - 1;
	$wpak_next_page = $wpak_displayPage + 1;
	$wpak_adjacents = "2";
	//$wpak_displayPage = 1;	// Result Current Page Value
	//$wpak_resultsPerPage = 20;	// Result Per Page Value
	
	// First Count Total Results
	$wpak_countPagesQuery = $wpdb->get_var("SELECT COUNT(*) FROM $wpak_table_name WHERE WPAK_Keyword LIKE '%".$wpak_KeywordToFind."%'");	
	if($wpdb->last_error != ""){
		echo "<div class='error'>ERROR: '".$wpdb->last_error."'. For More Info, Please Contact Us On <a href='mailto:info@exeideas.net?cc=support@exeideas.com&amp;subject=WPAK%20WordPress%20Plugin%20UPDATE&amp;body=Hello%20WPAK%20Admin%3B%0A%0A%3C!--%20Your%20Msg%20Here%20With%20All%20The%20Details%20Of%20Updates%20We%20Needs%20--%3E%0A%0AThanks%20and%20Regards.'>info@exeideas.net</a></div>";
		exit();
		die();				
	} else {
		$wpak_countTotalRows = $wpak_countPagesQuery;
		echo "<p><label>Total Result:</label><input type='text' disabled value='".$wpak_countTotalRows."'/></p>";	// Total Results
	}

	// Then Make Pages Count
	$wpak_totalPages = ceil($wpak_countTotalRows/$wpak_resultsPerPage); // Calculate Total Pages With Results
	echo "<p><label>Total Pages:</label><input type='text' disabled value='".$wpak_totalPages."'/></p>";	// Total Results
	
	echo '<div class="clear"></div>';

	// Display Pagination
	echo '<div id="paginationPages" class="paginationPages">';
	echo "<a onclick='wpak_searchGet(1,".$wpak_resultsPerPage.");wpak_changeClass(this)' class='firstPage'>First Page</a> ";
	if ($wpak_totalPages <= 10){
		// Here we will all 10 pages
		for ($wpak_counter=1; $wpak_counter<=$wpak_totalPages; $wpak_counter++) {		
			echo "<a onclick='wpak_searchGet(".$wpak_counter.",".$wpak_resultsPerPage.");wpak_changeClass(this)'";
			if ($wpak_counter==$wpak_displayPage) echo " class='currentPage'";
			echo ">".$wpak_counter."</a> ";
		};
	} else {
		// Here we will show less pages
		//Starting 4 Pages
		if($wpak_displayPage <= 4) {
			for ($wpak_counter = 1; $wpak_counter < 8; $wpak_counter++){ 
				echo "<a onclick='wpak_searchGet(".$wpak_counter.",".$wpak_resultsPerPage.");wpak_changeClass(this)'";
				if ($wpak_counter==$wpak_displayPage) echo " class='currentPage'";
				echo ">".$wpak_counter."</a> ";
			}
			echo " <a class='morePage'>...</a>";
		} elseif($wpak_displayPage > 4 && $wpak_displayPage < $wpak_totalPages - 4) {
			echo "<a class='morePage'>...</a>";
			for ($wpak_counter = $wpak_displayPage - $wpak_adjacents;$wpak_counter <= $wpak_displayPage + $wpak_adjacents;$wpak_counter++) {
				echo "<a onclick='wpak_searchGet(".$wpak_counter.",".$wpak_resultsPerPage.");wpak_changeClass(this)'";
				if ($wpak_counter==$wpak_displayPage) echo " class='currentPage'";
				echo ">".$wpak_counter."</a> ";              
			}
			echo " <a class='morePage'>...</a>";
		} else {
			echo "<a class='morePage'>...</a>";
			for ($wpak_counter = $wpak_totalPages - 6;$wpak_counter <= $wpak_totalPages;$wpak_counter++) {
				echo "<a onclick='wpak_searchGet(".$wpak_counter.",".$wpak_resultsPerPage.");wpak_changeClass(this)'";
				if ($wpak_counter==$wpak_displayPage) echo " class='currentPage'";
				echo ">".$wpak_counter."</a> ";              
			}
		}
	}	 
	echo "<a onclick='wpak_searchGet(".$wpak_totalPages.",".$wpak_resultsPerPage.");wpak_changeClass(this)' class='lastPage'>Last Page</a>";
	echo '</div>';
	wp_die();	// Stop Shoing 0 In Ajax Return.	
}



/****************************************************************************/
// View Whole Table Data
/****************************************************************************/
/* Register This Function When This File Is Loaded To Call By WordPress AJAX */
add_action('wp_ajax_nopriv_wpakViewData', 'wpak_ViewData');	// For Web Visitors
add_action('wp_ajax_wpakViewData', 'wpak_ViewData');	// For Admin User

function wpak_ViewData(){
	global $wpdb;	// Get All Database Details Of This WordPress
	$wpak_table_name = $wpdb->prefix . 'wpak_keywords';	// Get Table Names	
	
	
	// User Value
	$wpak_KeywordToFind = wpak_postSanValLen($_POST['wpak_KeywordToFind'], "alnum", 100, "");
	$wpak_ResultsSort = wpak_postSanValLen($_POST['wpak_ResultsSort'], "alnum", 4, "DESC");
	$wpak_displayPage = wpak_postSanValLen($_POST['wpak_displayPage'], "digit", 5, 1);
	$wpak_resultsPerPage = wpak_postSanValLen($_POST['wpak_resultsPerPage'], "digit", 3, 20);
	
	
	// Default Value
	$wpak_start_from = ($wpak_displayPage-1) * $wpak_resultsPerPage;
	// View Whole Table
	echo '<table class="table">
		<tr>
		<th>WPAK_Id</th>
		<th>WPAK_Keyword <span class="tooltip" title="Double Click On Text To Edit Then Click Outside To Update"><span title="" class="dashicon dashicons dashicons-editor-help"></span></span></th>
		<th>WPAK_Frequency <span class="tooltip" title="Double Click On Text To Edit Then Click Outside To Update"><span title="" class="dashicon dashicons dashicons-editor-help"></span></span></th>
		<th>WPAK_RegDate</th>
		<th>Delete</th>
    </tr>';
	$wpak_result = $wpdb->get_results( "SELECT * FROM ".$wpak_table_name." WHERE WPAK_Keyword LIKE '%".$wpak_KeywordToFind."%' ORDER by WPAK_Id ".$wpak_ResultsSort." LIMIT ".$wpak_start_from.", ".$wpak_resultsPerPage);
	if($wpak_result === false){
		echo "<div class='exe_error'>ERROR: '".$wpdb->last_error."'. Please Contact Us On <a href='mailto:info@exeideas.net?cc=support@exeideas.com&amp;subject=WPAK%20WordPress%20Plugin%20UPDATE&amp;body=Hello%20WPAK%20Admin%3B%0A%0A%3C!--%20Your%20Msg%20Here%20With%20All%20The%20Details%20Of%20Updates%20We%20Needs%20--%3E%0A%0AThanks%20and%20Regards.'>info@exeideas.net</a></div>";
		exit();
		die();
	} else {		
		foreach ( $wpak_result as $print )   {
			echo "	
				<tr>
					<td>$print->WPAK_Id</td>					
					<td><input id='".$wpak_table_name.":WPAK_Keyword:".$print->WPAK_Id."' onchange='wpak_updateCell(this.id,false)' onfocusout='this.readOnly=true;this.style.boxShadow=\"none\";' ondblclick='this.readOnly=false;this.style.boxShadow=\"0 0 0 1px #007cba\";' readonly='true' type='text' value='".$print->WPAK_Keyword."'/></td>
					<td><input id='".$wpak_table_name.":WPAK_Frequency:".$print->WPAK_Id."' onchange='wpak_updateCell(this.id,true)' onfocusout='this.readOnly=true;this.style.boxShadow=\"none\";' ondblclick='this.readOnly=false;this.style.boxShadow=\"0 0 0 1px #007cba\";' readonly='true' type='text' value='".$print->WPAK_Frequency."'/></td>					
					<td>$print->WPAK_RegDate</td>					
					<td><a class='deleteButton' title='Delete This Row' onclick='wpak_deleteRow(this.parentElement.parentElement,\"".$wpak_table_name."\",".$print->WPAK_Id.")'>Delete</a></td>
				</tr>
			";
		}
	}
	echo "</table>";
	wp_die();	// Stop Shoing 0 In Ajax Return.
}



/****************************************************************************/
// Meta Keywords For SEO (Custom Field "wp_ak_meta_keywords")
/****************************************************************************/
function wpak_keywords(){
	global $post, $posts;
	$wpak_customMetaKey = "";
	// Get The Keywords From Post Custom Field
    if ( is_single() || is_page() ) : 
        if ( have_posts() ) : while ( have_posts() ) : the_post(); 
                $wpak_customMetaKey = get_post_meta($post->ID, 'wp_ak_meta_keywords', true);
            endwhile; 
        endif; 
    endif;
	// If Post Custom Field Is Blank Then Make Post Category List AS Keywords
	if ( $wpak_customMetaKey == "" ) : 
		if ( is_single() ) : 
			$posttags = get_the_category();
			foreach((array)$posttags as $category) :
				$wpak_customMetaKey .= $category->name . ',';
			endforeach;
		endif; 
	endif;
	wp_reset_postdata();	// Restore Original Post Data In Global Variable.
    return $wpak_customMetaKey;
}
// Add Meta Keywords Tag In Header (Custom Field "wp_ak_meta_keywords") //
function add_wpak_keywords() {
    echo '<meta name="keywords" content="'.wpak_keywords().'" />';
}
add_action('wp_head', 'add_wpak_keywords');



/****************************************************************************/
// Delete Any Row In Table View
/****************************************************************************/
/* Register This Function When This File Is Loaded To Call By WordPress AJAX */
add_action('wp_ajax_nopriv_wpakDeleteRow', 'wpak_DeleteRow');	// For Web Visitors
add_action('wp_ajax_wpakDeleteRow', 'wpak_DeleteRow');	// For Admin User

function wpak_DeleteRow(){
	global $wpdb;	// Get All Database Details Of This WordPress
	$wpak_table_name = $wpdb->prefix . 'wpak_keywords';	// Get Table Name
	
	$wpak_incomingTable = wpak_postSanValLen($_POST['wpak_incomingTable'], "alnum", 30, $wpak_table_name);
	$wpak_incomingID = wpak_postSanValLen($_POST['wpak_incomingID'], "digit", 11, 0);
		
	// Delete The Data
	$wpak_result = $wpdb->delete( $wpak_incomingTable, array('WPAK_Id' => $wpak_incomingID) );
	if($wpak_result === FALSE){
		echo "<div class='exe_error hideAfterSec'>ERROR: '".$wpdb->last_error."'. For More Info, Please Contact Us On <a href='mailto:info@exeideas.net?cc=support@exeideas.com&amp;subject=WPAK%20WordPress%20Plugin%20UPDATE&amp;body=Hello%20WPAK%20Admin%3B%0A%0A%3C!--%20Your%20Msg%20Here%20With%20All%20The%20Details%20Of%20Updates%20We%20Needs%20--%3E%0A%0AThanks%20and%20Regards.'>info@exeideas.net</a></div>";
		exit();
		die();
	} else {
		echo "<div class='exe_success hideAfterSec'>SUCCESS: Your Row Is Been Deleted. For More Info, Please Contact Us On <a href='mailto:info@exeideas.net?cc=support@exeideas.com&amp;subject=WPAK%20WordPress%20Plugin%20UPDATE&amp;body=Hello%20WPAK%20Admin%3B%0A%0A%3C!--%20Your%20Msg%20Here%20With%20All%20The%20Details%20Of%20Updates%20We%20Needs%20--%3E%0A%0AThanks%20and%20Regards.'>info@exeideas.net</a></div>";
	}
	wp_die();	// Stop Shoing 0 In Ajax Return.
}



/****************************************************************************/
// Update Any Cell In Table View
/****************************************************************************/
/* Register This Function When This File Is Loaded To Call By WordPress AJAX */
add_action('wp_ajax_nopriv_wpakUpdateCell', 'wpak_UpdateCell');	// For Web Visitors
add_action('wp_ajax_wpakUpdateCell', 'wpak_UpdateCell');	// For Admin User

function wpak_UpdateCell(){
	global $wpdb;	// Get All Database Details Of This WordPress
	$wpak_table_name = $wpdb->prefix . 'wpak_keywords';	// Get Table Name
		
	$wpak_incomingTable = wpak_postSanValLen($_POST['wpak_TableName'], "allow", 30, $wpak_table_name);
	$wpak_incomingColumnName = wpak_postSanValLen($_POST['wpak_ColumnName'], "allow", 30, "");
	$wpak_incomingColumnValue = wpak_postSanValLen($_POST['wpak_ColumnValue'], "alnum", 100, "");
	$wpak_Duplicate = wpak_postSanValLen($_POST['wpak_Duplicate'], "alnum", 10, "");
	$wpak_incomingID = wpak_postSanValLen($_POST['wpak_RowID'], "wpak_RowID", 11, 0);

		// Check If The Duplicate Cell Is Already Available
		$wpak_countFromDatabase = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpak_incomingTable." WHERE ".$wpak_incomingColumnName." = '".$wpak_incomingColumnValue."'");
		if($wpdb->last_error != ""){
			echo "<div class='exe_error hideAfterSec'>ERROR: (Duplicate Query) '".$wpdb->last_error."'. For More Info, Please Contact Us On <a href='mailto:info@exeideas.net?cc=support@exeideas.com&amp;subject=WPAK%20WordPress%20Plugin%20UPDATE&amp;body=Hello%20WPAK%20Admin%3B%0A%0A%3C!--%20Your%20Msg%20Here%20With%20All%20The%20Details%20Of%20Updates%20We%20Needs%20--%3E%0A%0AThanks%20and%20Regards.'>info@exeideas.net</a></div>";
			die();
			exit();
		} else {
			// Check If The Duplicate $WPAK_Keyword Is Already Available
			if ($wpak_countFromDatabase > 0 && $wpak_Duplicate == "false") {
				echo "<div class='exe_error hideAfterSec'>ERROR: Duplicate Data Found. For More Info, Please Contact Us On <a href='mailto:info@exeideas.net?cc=support@exeideas.com&amp;subject=WPAK%20WordPress%20Plugin%20UPDATE&amp;body=Hello%20WPAK%20Admin%3B%0A%0A%3C!--%20Your%20Msg%20Here%20With%20All%20The%20Details%20Of%20Updates%20We%20Needs%20--%3E%0A%0AThanks%20and%20Regards.'>info@exeideas.net</a></div>";
				die();
				exit();
			} else {		
				// Update The Data
				$garb = $wpdb->update( $wpak_incomingTable, array(''.$wpak_incomingColumnName.'' => ''.$wpak_incomingColumnValue.''), array('WPAK_Id' => $wpak_incomingID) );
				if($garb === FALSE){	
					echo "<div class='exe_error hideAfterSec'>ERROR: (Update Query) '".$wpdb->last_error."'. For More Info, Please Contact Us On <a href='mailto:info@exeideas.net?cc=support@exeideas.com&amp;subject=WPAK%20WordPress%20Plugin%20UPDATE&amp;body=Hello%20WPAK%20Admin%3B%0A%0A%3C!--%20Your%20Msg%20Here%20With%20All%20The%20Details%20Of%20Updates%20We%20Needs%20--%3E%0A%0AThanks%20and%20Regards.'>info@exeideas.net</a></div>";
					die();
					exit();
				} else {
					// Success
					echo "<div class='exe_success hideAfterSec'>SUCCESS: Your Cell Is Been Updated. For More Info, Please Contact Us On <a href='mailto:info@exeideas.net?cc=support@exeideas.com&amp;subject=WPAK%20WordPress%20Plugin%20UPDATE&amp;body=Hello%20WPAK%20Admin%3B%0A%0A%3C!--%20Your%20Msg%20Here%20With%20All%20The%20Details%20Of%20Updates%20We%20Needs%20--%3E%0A%0AThanks%20and%20Regards.'>info@exeideas.net</a></div>";
				}
				//  Update The Data
			}
		}	
	wp_die();	// Stop Shoing 0 In Ajax Return.
}
?>