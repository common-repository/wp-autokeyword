<?php
/*
Plugin Name: WP AutoKeyword
Plugin URI: http://www.exeideas.com/WP-AutoKeyword
Description: <strong>WP AutoKeyword</strong> Is An Automatic OnClick WordPress Posts Keywords Suggester, Generator And Adder With Your Added High CPM Trending Related Keywords. So Go To <strong><a href="tools.php?page=WP-AutoKeyword" title="WP AutoKeyword Setting">Setting Page</a></strong> To Generate Keywords For Your Blog Posts.
Author: EXEIdeas International
Author URI: http://www.exeideas.net/
Version: 1.0
*/



/****************************************************************************/
// Creating Table In The Datatbase On Activation
/****************************************************************************/
function wpak_CreateTable(){
	global $wpdb;
  	$version = get_option( 'my_plugin_version', '1.0' );
	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'wpak_keywords';
	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		WPAK_Id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
		WPAK_Keyword VARCHAR(100) NOT NULL UNIQUE,
		WPAK_Frequency VARCHAR(30) NOT NULL,
		WPAK_RegDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
	) $charset_collate";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );	
}
// Create The Table On Plugin Activation
register_activation_hook( __FILE__, 'wpak_CreateTable' );
/****************************************************************************/



/*____________Include Files_____________*/
include('wp_ak_functions.php');
/*____________Include Files_____________*/



/*____________WP AutoKeyword Plugin Admin/Script_____________*/
function wpak_autoKeyword_admin() {
/***********************************************************************
// CSS Codes
***********************************************************************/
echo '
<style type="text/css">
	/* WordPress CSS Remover
	----------------------------------------------- */
	.wp-core-ui input[type=reset], .wp-core-ui input[type=reset]:active, .wp-core-ui input[type=reset]:focus, .wp-core-ui input[type=reset]:hover {padding:5px 15px;background:#f3f5f6;border-color:#007cba;box-shadow:0 0 0 1px #007cba;}
	
	/* Default
	----------------------------------------------- */
	hr {border:0;height:1px;background-image:-webkit-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));background-image:-moz-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));background-image:-ms-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));background-image:-o-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));}
	.clear {clear:both;}
	table {border-collapse:inherit;}
	.boxShaow {background:#FFFFFF;box-shadow:0px 2px 3px #ccccc8;-webkit-box-shadow:0px 2px 3px #ccccc8;-moz-box-shadow:0px 2px 3px #ccccc8;-ms-box-shadow:0px 2px 3px #ccccc8;-o-box-shadow:0px 2px 3px #ccccc8;border:1px solid #B7B7B7;padding:0px 0px 5px 0px;width:100%;}
	.exe_success{background:url('.plugin_dir_url( __FILE__ ).'images/Success-Icon.png) no-repeat #e6efc2 5px 50%;padding:1em;padding-left:3.5em;border:2px solid #c6d880;color:#529214;font-size:16px;}
	.exe_error{background:url('.plugin_dir_url( __FILE__ ).'images/Error-Icon.png) no-repeat #fbe3e4 5px 50%;padding:1em;padding-left:3.5em;border:2px solid #fbc2c4;color:#d12f19;font-size:16px;}
	.exe_warning{background:url('.plugin_dir_url( __FILE__ ).'images/Warning-Icon.png) no-repeat #fff6bf 5px 50%;padding:1em;padding-left:3.5em;border:2px solid #ffd324;color:#817134;font-size:16px;}
	.exe_notice{background:url('.plugin_dir_url( __FILE__ ).'images/Notice-Icon.png) no-repeat #cbe0f4 5px 50%;padding:1em;padding-left:3.5em;border:2px solid #96b6d3;color:#286eae;font-size:16px;}
	.topBar {position:fixed;top:0;left:0;width:calc(100% - 5em);}
	.hideAfterSec {
  		-webkit-animation: seconds 5s forwards;
  		-webkit-animation-iteration-count: 1;
  		-webkit-animation-delay: 5s;
  		animation: seconds 5s forwards;
  		animation-iteration-count: 1;
  		animation-delay: 5s;
  		position: relative;
	}
	@-webkit-keyframes seconds {
  		0% {opacity:1;}
  		10% {opacity:0.9;}
  		20% {opacity:0.8;}
  		30% {opacity:0.7;}
  		40% {opacity:0.6;}
  		50% {opacity:0.5;}
  		60% {opacity:0.4;}
  		70% {opacity:0.3;}
  		80% {opacity:0.2;}
 		90% {opacity:0.1;}
		100% {opacity:0;left:-9999px; position:absolute;}
	}
	@keyframes seconds {
 		0% {opacity:1;}
 		10% {opacity:0.9;}
  		20% {opacity:0.8;}
  		30% {opacity:0.7;}
  		40% {opacity:0.6;}
  		50% {opacity:0.5;}
  		60% {opacity:0.4;}
  		70% {opacity:0.3;}
  		80% {opacity:0.2;}
  		90% {opacity:0.1;}
  		100% {opacity:0;left:-9999px; position:absolute;} 
	}
	.lds-heart {
		display: inline-block;
		position: relative;
		width: 80px;
		height: 80px;
		transform: rotate(45deg);
		transform-origin: 40px 40px;

	}
	.lds-heart div {
		top: 32px;
		left: 32px;
		position: absolute;
		width: 32px;
		height: 32px;
		background: #ff0000;
		animation: lds-heart 1.2s infinite cubic-bezier(0.215, 0.61, 0.355, 1);
	}
	.lds-heart div:after, .lds-heart div:before {
		content: " ";
		position: absolute;
		display: block;
		width: 32px;
		height: 32px;
		background: #ff0000;
	}
	.lds-heart div:before {
		left: -24px;
		border-radius: 50% 0 0 50%;
	}
	.lds-heart div:after {
		top: -24px;
		border-radius: 50% 50% 0 0;
	}
	@keyframes lds-heart {
		0% {transform: scale(0.95);}
		5% {transform: scale(1.1);}
		39% {transform: scale(0.85);}
		45% {transform: scale(1);}
		60% {transform: scale(0.95);}
		100% {transform: scale(0.9);}
	}

	/* Row Col
	----------------------------------------------- */
	.row {display:flex;width:100%;clear:both;}
	.col23 {width:calc(66.66% - 20px);float:left;margin:10px 10px 0 10px;}
	.col13 {width:calc(33.33% - 20px);float:left;margin:10px 10px 0 10px;}
	.col1 {width:calc(100% - 20px);float:left;margin:10px 10px 0 10px;}
	.col2 {width:calc(50% - 20px);float:left;margin:10px 10px 0 10px;}
	.col3 {width:calc(33.33% - 20px);float:left;margin:10px 10px 0 10px;}
	.col4 {width:calc(25% - 20px);float:left;margin:10px 10px 0 10px;}
	
	/* Body Wrap
	----------------------------------------------- */	
	.exe_wpak_plugin {margin:10px 20px 0 2px}
	.exe_wpak_plugin .head-wrap h1.title {font-size:23px;font-weight:400;margin:0;padding:9px 0 4px 0;line-height:1.3}
	.exe_wpak_plugin .head-wrap h1.title .title-count {font-size:10px;padding:2px 8px;opacity:.7}
		
	/* Header
	----------------------------------------------- */
	h1.title .title-count {font-size:10px;padding:2px 8px;opacity:.7}
	.top-sharebar {position:absolute;right:0;top:24px;padding-right:20px}
	.top-sharebar>* {vertical-align:middle;margin-left:10px;float:right}
	.share-text {font-size:10px;text-align:right;line-height:1.5;margin-right:5px;color:#838383}
	.share-btn {background:#333;color:#fff;text-decoration:none;padding:2px 10px;border-radius:2em;font-size:12px;line-height:2em}
	.share-btn:hover {opacity:.5;color:#fff}.share-btn:active,.share-btn:focus{color:#fff}
	.share-btn .dashicons {font-size:14px;margin:5px 2px 0 0;height:14px}
	.share-btn.twitter {background-color:#2196f3}
	.share-btn.googleplus {background-color:#dd4b39}
	.share-btn.rate-btn .dashicons {color:#ff9800}
	@media (min-width:320px) and (max-width:480px){
		.top-sharebar {display:none}
	}
	
	/* Tool Tip
	----------------------------------------------- */
	.tooltip {display:inline;position:relative;cursor:help}
	.tooltip:hover:after {background:#333;background:rgba(0,0,0,.8);border-radius:5px;bottom:26px;color:#fff;content:attr(title);left:-200px;padding:5px 15px;position:absolute;z-index:9998;width:210px;font-size:12px;line-height:1.6}
	.tooltip:hover:before {border:solid;border-color:#333 transparent;border-width:6px 6px 0 6px;bottom:20px;content:"";left:50%;position:absolute;z-index:9999}
	
	/* Tabbed Content Panel
	----------------------------------------------- */
	.tabs {display:flex;flex-wrap:wrap;margin:20px 0;}
	.tabs label.tabButton {z-index:1;order:1;display:block;cursor:pointer;transition:background ease 0.2s;float:left;border:1px solid #ccc;border-bottom:none;margin:0 0 -1px 5px;padding:5px 10px;font-size:14px;line-height:1.71428571;font-weight:600;background:#e5e5e5;text-decoration:none;white-space:nowrap;}
	.tabs label.tabButton:nth-of-type(1) {margin: 0 0 -1px 0;}
	.tabs .tab {order:99;/*flex-grow:1;*/width:calc(100% - 42px);display:none;padding:20px;border-top:1px solid #b7b7b7;}
	.tabs input[type="radio"] {display:none;}
	.tabs input[type="radio"]:checked + label.tabButton {color:#00709e;border-bottom: 1px solid #FFFFFF;background:#FFFFFF;}
	.tabs input[type="radio"]:checked + label.tabButton + .tab {display:block;}
	.tabs h2.tabHeading {font-size:1.5em;margin:1em 0 0 0;}
	.tabs p.tabDesc {text-align:justify;}
	@media screen and (max-width: 1000px) {
	  .tabs .tab, .tabs label.tabButton {order:initial;margin:0;}
	  .tabs label.tabButton {width:calc(100% - 0px);}
	  .tabs label.tabButton:nth-of-type(1) {margin:0;}
	}	
	
	/* Form
	----------------------------------------------- */
	form label {width:250px;display:inline-block;font-size:13px;font-weight:bold;line-height:30px;}
	form p {display:block;margin:5px 0;width:100%;}
	form ol {display:block;margin:10px 50px;font-size:15px;}
	form h2 {display:block;margin:0px 0 20px 0;font-size:30px;line-height:40px;text-transform:uppercase;border-bottom:1px solid #9A9A9A;}
	form input[type="text"], form input[type="date"], form input[type="number"], form input[type="time"], form input[type="checkbox"], form input[type="password"], form input[type="email"], form textarea  {width:calc(100% - 0px);border:1px solid #D8D8D8;background:#FFFFFF;-moz-border-radius:3px;-webkit-border-radius:3px;border-radius:3px;}
	input:read-only {background-color:#EFEFEF;}
	form select {width:calc(100% - 0px);padding:10px;border:1px solid #D8D8D8;background:#FFFFFF;-moz-border-radius:3px;-webkit-border-radius:3px;border-radius:3px;}
	form input[type="reset"] {padding:0 10px!important;margin-left:10px!important;box-shadow:none!important;border: 1px solid #007cba!important;}
	@media screen and (max-width: 640px) {
	  form label {width:auto;line-height:inherit;}
	}
	
	/* Pagination
	----------------------------------------------- */
	.pagination {display:block;text-align:center;}
	.pagination p.inlineSearch {float:left;display:inline;width:auto;margin:13px 0;}
	.pagination p.inlineSearch input[type="text"]{width:150px;border:1px solid #878787;}
	.pagination p {float:right;padding-bottom:5px;border-bottom:4px double #DDD;margin-right:10px;}
	.pagination p label {margin-right:10px;display:inline-block;font-size:15px;}
	.pagination p input[type="text"]{display:inline-block;width:50px;border:1px solid #878787;text-align:center;}
	.pagination p select{display:inline-block;width:73px;min-height:30px;}
	#showPaginationData {}
	.pagination .paginationPages {display:block;margin-top:15px;}
	.pagination .paginationPages a {cursor:pointer;border:1px solid #DDD;padding:5px;text-decoration:none;color:#898989;font-size:12px;width:18px;display:inline-block;height:18px;text-align:center;}
	.pagination .paginationPages a:hover {background:#EFEFEF;}
	.pagination .paginationPages a.firstPage, .pagination .paginationPages a.lastPage {background:#EFEFEF;width:68px;height:18px;border:1px solid #a0a0a0;}
	.pagination .paginationPages a.currentPage {border:1px solid #a0a0a0;padding:10px;text-decoration:none;color:#898989;font-size:12px;background:#EFEFEF;}
	.pagination .paginationPages a.currentPage:hover {background:#EFEFEF;}
	.pagination .paginationPages a.morePage {margin-right:5px;}
	
	/* Table
	----------------------------------------------- */
	.table {display:table;margin:10px 0;border:1px solid #D3D3D3;border-collapse:collapse;border-spacing:0;width:100%;overflow:scroll;}
	.table th {color:#424242;background:#E8E8E8;border-bottom:2px solid #D3D3D3;border-right:1px solid #D3D3D3;font-size:14px;font-weight:100;padding:10px;text-align:left;text-shadow:0 1px 1px rgba(0, 0, 0, 0.1);vertical-align:middle;}
	.table th:last-child {border-right:none;}
	.table td {padding:5px;text-align:left;vertical-align:middle;font-weight:300;font-size:14px;text-shadow:-1px -1px 1px rgba(0, 0, 0, 0.1);border-right:1px solid #C1C3D1;}
	.table td:last-child {border-right:0px;}
	.table tr {border-top:1px solid #C1C3D1;border-bottom:1px solid #C1C3D1;color:#000000;font-size:16px;font-weight:normal;text-shadow:0 1px 1px rgba(256, 256, 256, 0.1);cursor:pointer;}
	.table tr select {width:100%;border:0;background:none;}
	.table tr:hover td {color:#000000;}
	.table tr:hover td a, .table tr:hover td input[type="text"], .table tr:hover td select {color:#000000;}
	.table tr:hover td select option {color:#000000;}
	.table tr:first-child {border-top:none;}
	.table tr:last-child {border-bottom:none;} 
	.table tr:nth-child(odd) {background:#F9F9F9;}
	.table tr:hover {background:#ECEFF1;}
	.table a {color:#33AADD;font-weight:bold;}
	.table a.cancelButton {color:#FFFFFF;padding:5px;background:#008888;display:block;text-decoration:none;cursor:pointer;text-align:center;}
	.table a.updateButton {color:#FFFFFF;padding:5px;background:#008888;display:block;text-decoration:none;cursor:pointer;text-align:center;}
	.table a.deleteButton {color:#FFFFFF;padding:5px;background:#CC0000;display:block;text-decoration:none;cursor:pointer;text-align:center;}
	.table a.editButton {color:#FFFFFF;padding:5px;background:#008888;display:block;text-decoration:none;cursor:pointer;text-align:center;}
	.table a.cancelButton:hover, .table a.updateButton:hover, .table a.deleteButton:hover, .table a.deleteButton:hover {color:#000000;}
	.table tr td input[type="text"] {font-weight:300;font-size:14px;text-shadow:-1px -1px 1px rgba(0, 0, 0, 0.1);border:0;background:none;outline:none;width:100%;}
	.table tr td input[type="text"]:focus {box-shadow:none;}

	/* Buy Me A Coddee
	----------------------------------------------- */
	.coffee-box {padding:15px 5px 15px 15px;border:1px solid #4caf50;padding-left:15%;background:url('.plugin_dir_url( __FILE__ ).'images/coffee.svg) no-repeat;border-radius:3px;background-position:30px center;margin:30px 0 15px 0;background-size:84px}
	.coffee-box .coffee-amt {width:100%;padding:5px;height:auto;font-size:1.5em}
	.coffee-amt-wrap {float:right;margin:0 30px}
	.coffee-heading {color:#23282d;font-size:1.3em;margin:1em 0;display:block;font-weight:600}
	@media screen and (max-width: 1000px) {
		.coffee-box {padding:10px!important;background:none!important;}
		.coffee-amt-wrap {margin:0 10px 10px 10px;}
	}	
</style>
';



/***********************************************************************
// HTML Codes
***********************************************************************/
echo '
<div class="exe_wpak_plugin">
    <!-- Header Start -->
    <div class="head-wrap">
        <h1 class="title">WP AutoKeyword<span class="title-count">1.0</span></h1>
        <div>An Automatic OnClick WordPress Posts Keywords Suggester, Generator And Adder With Your Added High CPM Trending Related Keywords By <a href="http://www.exeideas.net" title="Lets Your Mind Rock" target="_blank">EXEIdeas International</a>.</div>
        <hr>
        <div class="top-sharebar">
            <a class="share-btn rate-btn" href="https://wordpress.org/support/plugin/wp-autokeyword/reviews/?filter=5#new-post" target="_blank" title="Please rate 5 stars if you like WP AutoKeyword"><span class="dashicons dashicons-star-filled"></span> Rate 5 stars</a>
            <a class="share-btn twitter" href="https://twitter.com/intent/tweet?text=Checkout+WP+AutoKeyword+v0.1%2C+An+Automatic+OnClick+WordPress+Posts+Keywords+Suggester+Generator+And+Adder+With+Your+Added+High+CPM+Trending+Related+Keywords+By+EXEIdeas.&amp;tw_p=tweetbutton&amp;url=https://wordpress.org/plugins/wp-autokeyword/&amp;via=exeideas" target="_blank"><span class="dashicons dashicons-twitter"></span> Tweet about WP AutoKeyword Plugin</a>
        </div>
    </div>
    <!-- Header End -->
    <!-- Body Start -->
    <div class="tabs">
        <!-- Tab 1 ------------------------------------------------->
        <input type="radio" name="tabs" id="tabone" checked="checked">
        <label class="tabButton" for="tabone"><span class="dashicons dashicons-admin-post" style="padding-top: 2px;"></span> Keyword Selector</label>
        <div class="tab boxShaow">
            <!-- Panel Box Content -->
            <h2 class="tabHeading">Keyword Selector Tool:</h2>
            <p class="tabDesc">Do you want to extract the Keywords from your any article manually then here you are to do this.</p>
            <hr/>
            <form id="wpak_keywordSelector">
                <div class="row">
                    <div class="col3">
                        <p><label for="">Single Keywords Count: <span class="tooltip" title="Enter How Many Keywords You Want. 10 Is Best."><span title="" class="dashicon dashicons dashicons-editor-help"></span></span></label><input name="wpak_SingleKeywordCount" type="text" maxlength="2" oninput="this.value=this.value.replace(/[^0-9]/g,\'\');" required="" placeholder="Enter Any No From 0-99" /></p>
                    </div>
                    <div class="col3">
                        <p><label for="">Double Keywords Count: <span class="tooltip" title="Enter How Many Keywords You Want. 10 Is Best."><span title="" class="dashicon dashicons dashicons-editor-help"></span></span></label><input name="wpak_DoubleKeywordCount" type="text" maxlength="2" oninput="this.value=this.value.replace(/[^0-9]/g,\'\');" required="" placeholder="Enter Any No From 0-99" /></p>
                    </div>
                    <div class="col3">
                        <p><label for="">Triple Keywords Count: <span class="tooltip" title="Enter How Many Keywords You Want. 10 Is Best."><span title="" class="dashicon dashicons dashicons-editor-help"></span></span></label><input name="wpak_TripleKeywordCount" type="text" maxlength="2" oninput="this.value=this.value.replace(/[^0-9]/g,\'\');" required="" placeholder="Enter Any No From 0-99" /></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col1">
                        <p><label for="">Article/Content: <span class="tooltip" title="You Can Add Full HTML Markup Or Plain Text Also."><span title="" class="dashicon dashicons dashicons-editor-help"></span></span></label><textarea rows="10" name="wpak_ArticleContent" maxlength="10000" required="" size="500" placeholder="Enter Your Full Article Or Content Here"></textarea></p>
                    </div>
                </div>
                <p>
                    <input type="submit" class="button button-primary" value="Suggest The Keywords" />
                    <input type="reset" class="button" value="Reset" />
                </p>
                <center>
                    <div id="showAjax_wpak_keywordSelector"></div>
                </center>
            </form>
            <!-- Panel Box Content -->
        </div>

        <!-- Tab 2 ------------------------------------------------->
        <input type="radio" name="tabs" id="tabtwo">
        <label class="tabButton" for="tabtwo"><span class="dashicons dashicons-admin-page" style="padding-top: 2px;"></span> Bulk Post Keyword Adder</label>
        <div class="tab boxShaow">
            <!-- Panel Box Content -->
            <h2 class="tabHeading">Bulk Post Keyword Adder:</h2>
            <p class="tabDesc">An automatic WordPress bulk posts keywords generator and adder tool on your all published posts.</p>
            <hr/>
            <form id="wpak_bulkKeywordSelector">
                <div class="row">
                    <div class="col3">
                        <p><label for="">Single Keywords Count: <span class="tooltip" title="Enter How Many Keywords You Want. 10 Is Best."><span title="" class="dashicon dashicons dashicons-editor-help"></span></span></label><input name="wpak_SingleKeywordCount" type="text" maxlength="2" oninput="this.value=this.value.replace(/[^0-9]/g,\'\');" required="" placeholder="Enter Any No From 0-99" /></p>
                    </div>
                    <div class="col3">
                        <p><label for="">Double Keywords Count: <span class="tooltip" title="Enter How Many Keywords You Want. 10 Is Best."><span title="" class="dashicon dashicons dashicons-editor-help"></span></span></label><input name="wpak_DoubleKeywordCount" type="text" maxlength="2" oninput="this.value=this.value.replace(/[^0-9]/g,\'\');" required="" placeholder="Enter Any No From 0-99" /></p>
                    </div>
                    <div class="col3">
                        <p><label for="">Triple Keywords Count: <span class="tooltip" title="Enter How Many Keywords You Want. 10 Is Best."><span title="" class="dashicon dashicons dashicons-editor-help"></span></span></label><input name="wpak_TripleKeywordCount" type="text" maxlength="2" oninput="this.value=this.value.replace(/[^0-9]/g,\'\');" required="" placeholder="Enter Any No From 0-99" /></p>
                    </div>
                </div>
				<div class="row">
                    <div class="col2">
                        <p><label for="">Starting Post From: <span class="tooltip" title="Enter From Where You Want To Start."><span title="" class="dashicon dashicons dashicons-editor-help"></span></span></label><input name="wpak_StartFrom" type="text" maxlength="5" oninput="this.value=this.value.replace(/[^0-9]/g,\'\');" required="" placeholder="Enter Any No From 0-99999" /></p>
                    </div>
                    <div class="col2">
                        <p><label for="">Ending Post To: <span class="tooltip" title="Enter From Where You Want To End."><span title="" class="dashicon dashicons dashicons-editor-help"></span></span></label><input name="wpak_EndTo" type="text" maxlength="5" oninput="this.value=this.value.replace(/[^0-9]/g,\'\');" required="" placeholder="Enter Any No From 0-99999" /></p>
                    </div>
                </div>				
				<div class="row">
                    <div class="col1">
                        <p><cite><b>Note:</b> Run this tool for min no of post at a time to avoid server load like 0-100, 101-200, 201-300,...</cite></p>
                    </div>
                </div>
				<br/>
                <p>
                    <input type="submit" class="button button-primary" value="Suggest And Add The Keywords" />
                    <input type="reset" class="button" value="Reset" />
                </p>
                <center>
                    <div id="showAjax_wpak_bulkKeywordSelector"></div>
                </center>
            </form>
            <!-- Panel Box Content -->
        </div>

        <!-- Tab 3 ------------------------------------------------->
        <input type="radio" name="tabs" id="tabthree">
        <label class="tabButton" for="tabthree"><span class="dashicons dashicons-screenoptions" style="padding-top: 2px;"></span> Keywords Database</label>
        <div class="tab boxShaow">
            <!-- Pagination on Top -->
            <h2 class="tabHeading">All Saved Keywords Database:</h2>
            <p class="tabDesc">View, Search, Edit, Delete already added keywords from your database to maintain your keywords list as you want.</p>
            <hr/>
            <div class="pagination">
				<p class="inlineSearch"><input type="text" id="wpak_KeywordToFind" placeholder="Enter Any Keyword" maxlength="100" value=""/><input type="button" onClick="wpak_searchGet(1,20)" class="button button-primary" value="Search"/></p>
				</form>
				<p><label for="">Results / Page:</label><input type="text" name="wpak_ResultsPerPage" onkeyup="wpak_getPagination(this.value,1)" value="20"></p>
                <p>
                    <label for="">Result Sort:</label>
                    <select name="wpak_ResultsSort" id="wpak_ResultsSort" required="">
                        <option value="DESC">DESC</option>
                        <option value="ASC">ASC</option>
                    </select>
                </p>
                <div id="wpak_showPaginationData"></div>
            </div>
            <!-- Pagination on Top -->
            <!-- Panel Box Content -->
            <center>
				<div id="showAjax_wpak_keywordDatabase_Status"></div>
                <div id="showAjax_wpak_keywordDatabase"></div>
            </center>
            <!-- Panel Box Content -->
        </div>

        <!-- Tab 4 ------------------------------------------------->
        <input type="radio" name="tabs" id="tabfour">
        <label class="tabButton" for="tabfour"><span class="dashicons dashicons-tag" style="padding-top: 2px;"></span> Save New Keywords</label>
        <div class="tab boxShaow">
            <!-- Panel Box Content -->
            <h2 class="tabHeading">Save New Keywords:</h2>
            <p class="tabDesc">Save new related keyword with their frequency in your database to let the tool detect whenever it will match it in your article.</p>
            <hr/>
            <form id="wpak_saveKeywords">
                <div class="row">
                    <div class="col1">
                        <p><label for="">Keyword-Frequency List: <span class="tooltip" title="Use Trending Keywords With Format As \'Keyword Here - FrequencyNo\'."><span title="" class="dashicon dashicons dashicons-editor-help"></span></span></label><textarea rows="10" name="wpak_KeywordList" maxlength="10000" required="" size="500" placeholder="Enter One Keyword Per Line With Frequency No"></textarea></p>
                    </div>
                </div>
				<div class="row">
                    <div class="col1">
                        <p><cite><b>Note:</b> Keyword should not be more then 100 chracter and frequency should not more then 30 chracters. <a href="https://newtextdocument.com/19e01ffcf2" rel="nofollow" target="_blank" title="Marketing Keywords List">Click here to view DEMO file</a>...</cite></p>
                    </div>
                </div>
                <p>
                    <input type="submit" class="button button-primary" value="Save The Keywords" />
                    <input type="reset" class="button" value="Reset" />
                </p>
                <center>
                    <div id="showAjax_wpak_saveKeywords"></div>
                </center>
            </form>
            <!-- Panel Box Content -->
        </div>

        <!-- Tab 5 ------------------------------------------------->
        <input type="radio" name="tabs" id="tabfive">
        <label class="tabButton" for="tabfive"><span class="dashicons dashicons-editor-help" style="padding-top: 2px;"></span> Help</label>
        <div class="tab boxShaow">
            <!-- Panel Box Content -->
            <h2 class="tabHeading">FAQ & Help:</h2>
            <p class="tabDesc">Do you need help with this plugin? Here are some FAQ for you.</p>
            <hr/>
            <p></p>
			
            <p></p>
            <li><strong>How this plugin works?</strong></li>
            <p></p>
            <p>This plugin run over on your all or your desired selected range of published posts in one click and detect most used keywords in your artcle then match it with high frequency of your saved keywords then add them into your post kerword meta tag.</p>

            <p></p>
            <li><strong>Is this plugin compatible with any themes?</strong></li>
            <p></p>
            <p>Yes, this plugin is compatible with any theme using <code>wp_head();</code> in theme <code>header.php</code>.</p>

            <p></p>
            <li><strong>Can we use it for a manually entered content to find our trending keywords into it?</strong></li>
            <p></p>
            <p>Yes, you can use this tool for manual input of your desired content too easily.</p>

            <p></p>
            <li><strong>Will it work on pages too?</strong></li>
            <p></p>
            <p>No, It will not work on pages or any thing except published posts.</p>

            <p></p>
            <li><strong>Can I add my own desired or my blog related keywords that I want to use?</strong></li>
            <p></p>
            <p>Yes, you have full right to use your own keywords data list then this tool will detect that keywords from your post and contents.</p>

            <p></p>
            <li><strong>Can I View/Search/Delete/Update already added keywords from my keyword list?</strong></li>
            <p></p>
            <p>Yes, you have full right to view, search, delete and update your already added keywords from the list easily.</p>

            </p>
            <p></p>
            <li><strong>Can I edit/delete keywords for any post after being selected by this tool?</strong></li>
            <p></p>
            <p>Yes, it is possible. Just go to that post and scroll down to see Custom Fields section and edit/delete <code>wp_ak_meta_keywords</code> field there. It will not touch that post even you run this tool after editing keywords for that post.</p>

            <br>

            <h2>My Other WordPress Plugin</h2>
            <p></p>
            <hr>
            <p></p>
            <p><strong>Like this plugin? Check out my other WordPress plugin:</strong></p>
            <li><strong><a href="https://www.exeideas.com/All-In-One-Blogger-Importer" target="_blank">All In One Blogger Importer</a></strong> - It a All In One Blogger To WordPress Importer that import your full Blogger blog to a WordPress blog without loosing anything. We have some recommended plugins so in this that you have to use to import your Blogger blog. Once your Blogger blog imported, Remove all of our plugins that is for importing only and enjoy your WordPress blog. It will import Blogger Posts, Blogger Labels, Blogger Comments, Blogger Images, Blogger Permalinks, Blogger Meta Description and make same Permalink Structure as Blogger have.</li>            
            <br>
            <!-- Panel Box Content -->
        </div>

    </div>
    <!-- Body End -->
    <!-- Footer Start -->
    <div class="coffee-box">
        <div class="coffee-amt-wrap" style="display:none;">
            <p>
                <select id="buy-me-a-coffee-value" onchange="document.getElementById(\'buy-me-a-coffee\').href=\'https://www.paypal.me/XXXXXX/\'+this.value" class="coffee-amt">
                    <option value="5usd">$5</option>
                    <option value="6usd">$6</option>
                    <option value="7usd">$7</option>
                    <option value="8usd">$8</option>
                    <option value="9usd">$9</option>
                    <option value="10usd" selected="selected">$10</option>
                    <option value="11usd">$11</option>
                    <option value="12usd">$12</option>
                    <option value="13usd">$13</option>
                    <option value="14usd">$14</option>
                    <option value="15usd">$15</option>
                    <option value="">Custom</option>
                </select>
            </p>
            <a id="buy-me-a-coffee" class="button button-primary buy-coffee-btn" style="margin-left: 2px;" href="https://www.paypal.me/XXXXXX/10usd" data-link="https://www.paypal.me/XXXXXX/" target="_blank">Buy me a coffee!</a>
        </div>
        <span class="coffee-heading">Buy me a coffee!</span>
        <p style="text-align: justify;">Thank you for using <strong>WP AutoKeyword v1.0</strong>. If you found the plugin useful buy me a coffee! Your donation will motivate and make me happy for all the efforts. You can donate via Payoneer after contacting us.
		<br/>
		Now if you have any problem during using upper tools, feel free to contact us on <a href="http://www.exeideas.net" rel="nofollow" target="_blank" title="EXEIdeas International">EXEIdeas International</a> so we will solve out there as soon as possible.</p>
        <p style="text-align: justify; font-size: 12px; font-style: italic;">Developed with <span style="color:#e25555;">♥</span> by <a href="http://www.exeideas.net/" target="_blank" title="EXEIdeas International" style="font-weight: 500;">EXEIdeas International</a> | <a href="mailto:info@exeideas.net" target="_blank" style="font-weight: 500;">Hire Us</a> | <a href="https://github.com/exeideas" target="_blank" style="font-weight: 500;">GitHub</a> | <a href="https://wordpress.org/support/plugin/wp-autokeyword" target="_blank" style="font-weight: 500;">Support</a> | <a href="https://translate.wordpress.org/projects/wp-plugins/wp-autokeyword" target="_blank" style="font-weight: 500;">Translate</a> | <a href="https://wordpress.org/support/plugin/wp-autokeyword/reviews/?rate=5#new-post" target="_blank" style="font-weight: 500;">Rate it</a> (<span style="color:#ffa000;">★★★★★</span>) on WordPress.org, if you like this plugin.</p>
    </div>
    <!-- Footer End -->
</div>
';



}
/*__________________________________________________________________*/



/*____________WP AutoKeyword Plugin Option_____________*/
//Adding "WP AutoKeyword" Menu To WordPress -> Tools
function wpak_autoKeyword() {
	//  add_management_page( $page_title, $menu_title, $capability, $menu_slug, $function);                  Menu Under Tools
    add_management_page("WP AutoKeyword By EXEIdeas", "WP AutoKeyword", 'activate_plugins', "WP-AutoKeyword", "wpak_autoKeyword_admin");
}
add_action('admin_menu', 'wpak_autoKeyword');
/*__________________________________________________________________*/
?>