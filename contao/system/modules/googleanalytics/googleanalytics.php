<?php 

/**
 * PHP version 5
 * @copyright  Jan Theofel 2011-2012, ETES GmbH 2010
 * @author     Jan Theofel <jan@theofel.de>
 * @package    googleanalytics
 * @license    LGPL
 * @filesource
 */

class googleanalytics extends Frontend 
{
		
  // hook for parseFrontendTemplate 
  public function addGoogleAnalytics($strContent, $strTemplate)
  {	
    if (!$GLOBALS['googleanalytics_included']) 
    {
      $GLOBALS['googleanalytics_included'] = 'true';
      global $objPage;     
      $root_details = $this->Database->prepare("SELECT * FROM tl_page WHERE id=?")->limit(1)->execute($objPage->rootId);
      if($root_details->numRows AND $root_details->ga_analyticsid != '')
      {
        $ga  = "<script type=\"text/javascript\"><!--\n";

        // add JS code for event tracking (also needed for external link tracking)
        if($root_details->ga_externaltracking || $root_details->ga_eventtracking || $root_details->ga_addlinktracking)
        {
          $ga .= 'function gaTrackEvent(c,a){try{_gat._getTracker("'.$root_details->ga_analyticsid.'")._trackEvent(c,a);} catch(e) {}}';
        }

	    // add JS code for external tracking
        if($root_details->ga_externaltracking)
        {
          $ga .= "function gaTrackLink(link, category, action, newwindow) {"
               .   "gaTrackEvent(category, action);"
               .   "if(newwindow){setTimeout('window.open(\"' + link.href + '\");', 100);}else{setTimeout('document.location=\"' + link.href + '\"',100);}"
               . "}";
	    }

        $ga .= "var _gaq = _gaq || [];"
             . "_gaq.push(['_setAccount', '" . $root_details->ga_analyticsid . "']);";

	    // add JS code for anonymize the IP Address
        if($root_details->ga_anonymizeip)
        {
          $ga .= "_gaq.push(['_gat._anonymizeIp']);";
        }

	    // add JS code to enable page load measurement
        if($root_details->ga_measurespeed)
        {
          $ga .= "_gaq.push(['_trackPageLoadTime']);";
        }

	    // add JS code fpr domain name
        if($root_details->ga_setdomainname)
        {
          $ga .= "_gaq.push(['_setDomainName', '" . $root_details->ga_setdomainname . "']);";
        }
        
        // add JS to "define" bounce rate by sending an event (hack, see http://padicode.com/blog/analytics/the-real-bounce-rate/)
        if($root_details->ga_bounceseconds > 0)
        {
			$ga .= "setTimeout('_gaq.push([\\'_trackEvent\\', \\'NoBounce\\', \\'Over " . $root_details->ga_bounceseconds . " seconds\\'])', " . $root_details->ga_bounceseconds * 1000 . ");";
        }

        $ga .= "_gaq.push(['_trackPageview']);"
             .   "(function() {"
             .     "var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;"
             .     "ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';"
             .     "var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);"
             .   "})();";

        if($root_details->ga_addlinktracking)
        {
	  $ga .=  'window.addEvent(\'domready\',(function()' .
		'{' .
			// maybe someone else has already set an gatAction.
			'if(typeof gatTrackCategory==\'undefined\')'.
				'var gatTrackCategory=\''. $root_details->ga_titlelinktracking .'\';' .
			'$$(\'a\').each(function(e)' .
			'{	var h=e.getAttribute(\'href\');' . "\n" .
				'if((h!=null) && h.test(/http/i) && ((typeof e.onclick==\'undefined\') || (!(e.onclick+\'\').test(/gaTrack/i))))' .
				'{' .
					'var oc=e.getAttribute(\'onclick\');if(oc!=null && String(oc).test(/window.open/i))' .
						'e.setAttribute(\'target\', \'_blank\');' .
					'if(e.onclick != \'\') ' .
					'{' .
 						'e.setAttribute(\'onclick\', \'gaTrackEvent(\\\'' . $root_details->ga_titlelinktracking . '\\\'	, \\\'\' + h + \'\\\');\' + e.getAttribute(\'onclick\'));' .
					'}else{' .
						'e.onclick=null; e.setAttribute(\'onclick\', \'\');' .
						'e.addEvent(\'click\',function(){' . "\n" .
							'gaTrackEvent(gatTrackCategory, h);' . "\n" .
							'(function(){' .
								'if(e.target==\'_blank\'){' .
									'window.open(h)'.
								'}else{' .
									'document.location=h;' .
								'}' .
							'}).delay(100);' .
							'return false;' .
						'});' .
					'}' .
				'}' .
			'});' .
		'}));';
        }

        $ga .=  "\n// -->\n</script>\n";

        $GLOBALS['TL_HEAD'][] = $ga;
      }
    }
    return $strContent;
  }
  
	// function to add Googles TOS text with an insert tag
	// using switch to add other functions later
	public function gaInsertTag($strTag)
	{
	    $arrTag = explode('::', $strTag);

    	if (!$arrTag[0] == 'ga') return false;

    	switch($arrTag[1])
    	{
			// Insert article count
			case 'privacytext':
				return $GLOBALS['TL_LANG']['MSC']['gaprivacytext'];
				break;
		}

		return false;
	}
  
}
 
?>
