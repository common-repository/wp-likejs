<?php  
/*
Plugin Name: WP LikeJS
Plugin URI: http://like-js.de/
Description: WP LikeJS includes an eyecatching Facebook Like Box on your website. You'll get more Fans with LikeJS than with an usual Like Button and it locks better!
Version: 1.0.1
Author: Dennis Niedernh&ouml;fer und Merlin Roth
Author URI: http://like-js.de/
License: GPL2
*/

//head Bereich
add_action('wp_head', 'likeJS_head');
$likejs_url = get_option('likejs_url'); 
if(!empty($likejs_url)){
	function likeJS_head() { 
?>
		<link rel="stylesheet" type="text/css" href="<?php bloginfo(wpurl); ?>/wp-content/plugins/wp-likejs/wp-likejs.css" />
        <?php $likejs_backlink = get_option('likejs_backlink'); if($likejs_backlink == "true"){ $likejsboxheight = "305px"; }else{ $likejsboxheight = "292px"; } ?>
		<style type="text/css">
			#likejsbox{ height:<?php echo $likejsboxheight; ?>; }
		</style>
        <?php wp_enqueue_script( 'jquery'); ?>
		<script>
			// LIKEJS JAVASCRIPT 
            function closeLikeJSBox(){
                jQuery('#likejsbox').hide( 250 );
                jQuery('#relikejsbox').show( 250 );
                setCookie("fbsave",666,30);
            }
            function reLikeJSBox(){
                jQuery('#relikejsbox').hide( 0 );
                jQuery('#likejsbox').addClass('fixed');
                jQuery('#likejsbox').fadeIn( 250 );
                setCookie("fbsave",667,30);
            }
            function setCookie(c_name,value,exdays)
            {
            var exdate=new Date();
            exdate.setDate(exdate.getDate() + exdays);
            var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
            document.cookie=c_name + "=" + c_value;
            }
            function getCookie(c_name)
            {
            var i,x,y,ARRcookies=document.cookie.split(";");
            for (i=0;i<ARRcookies.length;i++)
            {
              x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
              y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
              x=x.replace(/^\s+|\s+$/g,"");
              if (x==c_name)
                {
                return unescape(y);
                }
              }
            }
        </script>
<?php 
}
      //Footbereich
	 add_action('wp_footer', 'likeJS_foot');
function likeJS_foot() {
?>
 <!-- LIKEJS HTML -->
        <div id="likejsbox">
            <div class="header"><?php $likejs_headline = get_option('likejs_headline'); if(!empty($likejs_headline)){ echo $likejs_headline; }else{ echo "Follow us on Facebook"; } ?><span id="close" onClick="closeLikeJSBox();"><img src="<?php bloginfo('wpurl'); ?>/wp-content/plugins/wp-likejs/img/close.png" alt="schliessen" /></span></div>
            <iframe src="http://www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2F<?php $likejs_url = get_option('likejs_url'); echo $likejs_url; ?>&amp;width=296&amp;height=258&amp;colorscheme=light&amp;show_faces=true&amp;border_color&amp;stream=false&amp;header=false" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:296px; height:258px;" allowTransparency="true"></iframe>
        <?php $likejs_backlink = get_option('likejs_backlink'); if($likejs_backlink == "true"){ ?>
        	<span class="likejsbacklink">Powered by <a href="http://www.likejs.de" target="_blank">LikeJS</a></span>
        <?php } ?>
        </div>
        <div id="relikejsbox" onClick="reLikeJSBox();">
        <img src="<?php bloginfo('wpurl'); ?>/wp-content/plugins/wp-likejs/img/relikejsbox.jpg" alt="oeffnen" />
        </div>                                      
        <script>
            jQuery(document).ready(function($) {
                var fbsave=getCookie("fbsave");
                    if(fbsave != 666){
                            var fblog = 0;
                            var top = $('#likejsbox').offset().top + <?php $likejs_px = get_option('likejs_px'); if(!empty($likejs_px)){ echo $likejs_px; }else{ echo "1000"; } ?>;
                            $(window).scroll(function() {
                                if(fblog == 0){
                                    if($(this).scrollTop() > top) {
                                        $('#likejsbox').addClass('fixed');
                                        $('#likejsbox').fadeIn( 250 );
                                        fblog++;
                                    }
                                }
                            });
                    }else{
                        $('#relikejsbox').show( 0 );	
                    }
            });
        </script>

<?php  
	}
}
$likejs_options = array (
  array(
  	"name" => "<strong>What's your Facebook URL slug?</strong>",
  	"id" => "likejs_url",
  	"default" => ""),

  array(
  	"name" => "<strong>What's the headline of the LikeJS Box?</strong>",
  	"id" => "likejs_headline",
  	"default" => "Follow us on Facebook"),

  array(
  	"name" => "<strong>How much px should be scrollable bevor the LikeJS Box is displayed?</strong>",
  	"id" => "likejs_px",
  	"default" => "1000"),

  array(
  	"name" => "<strong>Place a backlink to the LikeJS project homepage?</strong>",
  	"id" => "likejs_backlink",
  	"default" => ""),
);
function likejs_options() {
  global $likejs_options;
  if ('theme_save'== $_REQUEST['action'] ) {
    foreach ($likejs_options as $value) {
     if( !isset( $_REQUEST[ $value['id'] ] ) ) {  } else { update_option( $value['id'], stripslashes($_REQUEST[ $value['id']])); } }
     if(stristr($_SERVER['REQUEST_URI'],'&saved=true')) {
     $location = $_SERVER['REQUEST_URI'];
    } else {
     $location = $_SERVER['REQUEST_URI'] . "&saved=true";
    }
  } else if('theme_reset' == $_REQUEST['action'] ) {

    foreach ($likejs_options as $value) {
     delete_option( $value['id'] );
     $location = $_SERVER['REQUEST_URI'] . "&reset=true";
    }
  }
  add_options_page('LikeJS', 'LikeJS', 10, 'likejs_settings', 'likejs_admin');
}
function likejs_admin() {
    global $likejs_options;
?>
<div class="wrap">
  <h2 class="alignleft">WP LikeJS Settings</h2>
  <br clear="all" />
  <?php
  	if ( $_REQUEST['saved'] ) {
  ?>
  		<div class="updated fade"><p><strong>Saved</strong></p></div>
  <?php } ?>
<form method="post" id="myForm">
<div id="poststuff" class="metabox-holder">
 <!-- BEGIN Configuration -->
 <div class="stuffbox">
  <h3>Configure the LikeJS Box</h3>
  <div class="inside" style="width:750px;float:left;">
    <table class="form-table" style="margin-bottom:20px;">
    <?php
     foreach ($likejs_options as $value) {
      switch ( $value['id'] ) {

        case "likejs_url": ?>
        <tr>
        <th scope="row"><?php echo $value['name']; ?></th>
        <td>
         <input name="<?php echo $value['id']; ?>"
         		id="<?php echo $value['id']; ?>" type="text"
            style="width:200px;"
         		value="<?php get_option($value['id'])?printf(get_option($value['id'])): printf($value['default']) ?>" />
        </td>
        <td style="font-style:italic;color:#666;">
        	When your URL is <strong>facebook.com/yourPage</strong> you have to enter "<strong>yourPage</strong>".
        </td>
        </tr>

      <?php break;

        case "likejs_headline": ?>
        <tr>
        <th scope="row"><?php echo $value['name']; ?></th>
        <td>
         <input name="<?php echo $value['id']; ?>"
         		id="<?php echo $value['id']; ?>" type="text"
            style="width:200px;"
         		value="<?php get_option($value['id'])?printf(get_option($value['id'])): printf($value['default']) ?>" />
        </td>
        <td style="font-style:italic;color:#666;">
        	Not more than 40 letters.
        </td>
        </tr>

      <?php break;

        case "likejs_px": ?>
        <tr>
        <th scope="row"><?php echo $value['name']; ?></th>
        <td>
         <input name="<?php echo $value['id']; ?>"
         		id="<?php echo $value['id']; ?>" type="text"
            style="width:200px;"
         		value="<?php get_option($value['id'])?printf(get_option($value['id'])): printf($value['default']) ?>" />
        </td>
        <td style="font-style:italic;color:#666;">
        	Enter not less than 500 and not more than 2000 px.
        </td>
        </tr>

      <?php break;

        case "likejs_backlink": ?>
        <tr>
        <th scope="row"><?php echo $value['name']; ?></th>
        <td>
            <input type="radio" name="<?php echo $value['id']; ?>" value="true" <?php if (get_option($value['id']) == "true"){ echo 'checked="checked"'; } ?> /> Yes<br>
            <input type="radio" name="<?php echo $value['id']; ?>" value="false" <?php if (get_option($value['id']) == "false"){ echo 'checked="checked"'; } ?>> No
        </td>
        <td style="font-style:italic;color:#666;">
        	This will place a small Backlink into the LikeJS Box.
        </td>
        </tr>

      <?php break;

		}
	}
	?>
   </table>
<input name="theme_save" type="submit" class="button-primary" value="Save settings" />
<input type="hidden" name="action" value="theme_save" />
</form>
<form method="post">
<input name="theme_reset" type="submit" class="button-primary" value="Reset" />
<input type="hidden" name="action" value="theme_reset" />
</form>
  </div>
  <div style="float:left;width:260px;">
      <div style="text-align:center;padding:10px;background:#FFEAEA;margin:20px;">
        <h4>Support the Project LikeJS</h4>
        <p>We would be glad if you honor our work with a donation.</p>
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
            <input type="hidden" name="cmd" value="_s-xclick">
            <input type="hidden" name="hosted_button_id" value="VWXSLBCPX6NBS">
            <input type="image" src="https://www.paypalobjects.com/de_DE/DE/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="Jetzt einfach, schnell und sicher online bezahlen – mit PayPal.">
            <img alt="" border="0" src="https://www.paypalobjects.com/de_DE/i/scr/pixel.gif" width="1" height="1">
        </form>
      </div>
      <div style="text-align:center;padding:10px;background:#DEFCDC;margin:20px;">
        <h4>Help us develop</h4>
        <p>On our website you find a feedback form, please mail us if you got some interesting ideas.</p>
      	<p><strong><a href="http://like-js.de/feedback/" target="_blank">Go to feedback form</a></strong></p>
      </div>
  </div>
  <div style="clear:both;"></div>
 </div>
</div>
</div>
<?php
}
add_action('admin_menu', 'likejs_options');  


//Done! If you read all this then feel free to contact us, maybe you are interested in helping us ;)                    


/*  Copyright 2012 Dennis Niedernhoefer, Merlin Roth
 
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.
 
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
 
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
?>