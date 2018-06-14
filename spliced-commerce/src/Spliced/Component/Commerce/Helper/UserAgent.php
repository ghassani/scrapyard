<?php 
namespace Spliced\Component\Commerce\Helper;


class UserAgent{
    
    // source: http://www.monperrus.net/martin/list+of+robot+user+agents
    public static $botUserAgents = array ( 
    // note that this is meant to be used in a case-insensitive setup
    
    /**** THE BIG THREE ********/
        'googlebot\/',          /* Google see http://www.google.com/bot.html              */
        'Googlebot-Mobile',
        'Googlebot-Image',
        'bingbot',            /* Microsoft Bing, see http://www.bing.com/bingbot.htm   */
        'slurp',              /* Yahoo, see http://help.yahoo.com/help/us/ysearch/slurp */
    
            /**** Home grown ********/
        'java',
        'wget',
        'curl',
        'Commons-HttpClient',
        'Python-urllib',
        'libwww',
        'httpunit',
        'nutch',
        'phpcrawl',           /* added 2012-09/17, see http://phpcrawl.cuab.de/ */
    
            /** The others */
        'msnbot',             /* see http://search.msn.com/msnbot.htm   */
        'Adidxbot',           /* see http://onlinehelp.microsoft.com/en-us/bing/hh204496.aspx */
        'blekkobot',          /* see http://blekko.com/about/blekkobot */
        'teoma',
        'ia_archiver',
        'GingerCrawler',
        'webmon ',            /* the space is required so as not to match webmoney */
        'httrack',
        'webcrawler',
        'FAST-WebCrawler',
        'FAST Enterprise Crawler',
        'convera',
        'biglotron',
        'grub.org',
        'UsineNouvelleCrawler',
        'antibot',
        'netresearchserver',
        'speedy',
        'fluffy',
        'jyxobot',
        'bibnum.bnf',
        'findlink',
        'exabot',
        'gigabot',
        'msrbot',
        'seekbot',
        'ngbot',
        'panscient',
        'yacybot',
        'AISearchBot',
        'IOI',
        'ips-agent',
        'tagoobot',
        'MJ12bot',
        'dotbot',
        'woriobot',
        'yanga',
        'buzzbot',
        'mlbot',
        'yandex',
        'purebot',            /* added 2010/01/19  */
        'Linguee Bot',        /* added 2010/01/26, see http://www.linguee.com/bot */
        'Voyager',            /* added 2010/02/01, see http://www.kosmix.com/crawler.html */
        'CyberPatrol',        /* added 2010/02/11, see http://www.cyberpatrol.com/cyberpatrolcrawler.asp */
        'voilabot',           /* added 2010/05/18 */
        'baiduspider',        /* added 2010/07/15, see http://www.baidu.jp/spider/ */
        'citeseerxbot',       /* added 2010/07/17 */
        'spbot',              /* added 2010/07/31, see http://www.seoprofiler.com/bot */
        'twengabot',          /* added 2010/08/03, see http://www.twenga.com/bot.html */
        'postrank',           /* added 2010/08/03, see http://www.postrank.com */
        'turnitinbot',        /* added 2010/09/26, see http://www.turnitin.com */
        'scribdbot',          /* added 2010/09/28, see http://www.scribd.com */
        'page2rss',           /* added 2010/10/07, see http://www.page2rss.com */
        'sitebot',            /* added 2010/12/15, see http://www.sitebot.org */
        'linkdex',            /* added 2011/01/06, see http://www.linkdex.com */
        'ezooms',             /* added 2011/04/27, see http://www.phpbb.com/community/viewtopic.php?f=64&t=935605&start=450#p12948289 */
        'dotbot',             /* added 2011/04/27 */
        'mail\\.ru',          /* added 2011/04/27 */
        'discobot',           /* added 2011/05/03, see http://discoveryengine.com/discobot.html */
        'heritrix',           /* added 2011/06/21, see http://crawler.archive.org/ */
        'findthatfile',       /* added 2011/06/21, see http://www.findthatfile.com/ */
        'europarchive.org',   /* added 2011/06/21, see  http://www.europarchive.org/ */
        'NerdByNature.Bot',   /* added 2011/07/12, see http://www.nerdbynature.net/bot*/
        'sistrix crawler',    /* added 2011/08/02 */
        'ahrefsbot',          /* added 2011/08/28 */
        'Aboundex',           /* added 2011/09/28, see http://www.aboundex.com/crawler/ */
        'domaincrawler',      /* added 2011/10/21 */
        'wbsearchbot',        /* added 2011/12/21, see http://www.warebay.com/bot.html */
        'summify',            /* added 2012/01/04, see http://summify.com */
        'ccbot',              /* added 2012/02/05, see http://www.commoncrawl.org/bot.html */
        'edisterbot',         /* added 2012/02/25 */
        'seznambot',          /* added 2012/03/14 */
        'ec2linkfinder',      /* added 2012/03/22 */
        'gslfbot',            /* added 2012/04/03 */
        'aihitbot',           /* added 2012/04/16 */
        'intelium_bot',       /* added 2012/05/07 */
        'facebookexternalhit',/* added 2012/05/07 */
        'yeti',               /* added 2012/05/07 */
        'RetrevoPageAnalyzer',/* added 2012/05/07 */
        'lb-spider',          /* added 2012/05/07 */
        'sogou',              /* added 2012/05/13, see http://www.sogou.com/docs/help/webmasters.htm#07 */
        'lssbot',             /* added 2012/05/15 */
        'careerbot',          /* added 2012/05/23, see http://www.career-x.de/bot.html */
        'wotbox',             /* added 2012/06/12, see http://www.wotbox.com */
        'wocbot',             /* added 2012/07/25, see http://www.wocodi.com/crawler */
        'ichiro',             /* added 2012/08/28, see http://help.goo.ne.jp/help/article/1142 */
        'DuckDuckBot',        /* added 2012/09/19, see http://duckduckgo.com/duckduckbot.html */
        'lssrocketcrawler',   /* added 2012/09/24 */
        'drupact',            /* added 2012/09/27, see http://www.arocom.de/drupact */
        'webcompanycrawler',  /* added 2012/10/03 */
        'acoonbot',           /* added 2012/10/07, see http://www.acoon.de/robot.asp */
        'openindexspider',    /* added 2012/10/26, see http://www.openindex.io/en/webmasters/spider.html */
        'gnam gnam spider',   /* added 2012/10/31 */
        'web-archive-net.com.bot', /* added 2012/11/12*/
        'backlinkcrawler',    /* added 2013/01/04 */
        'coccoc',             /* added 2013/01/04, see http://help.coccoc.vn/ */
        'integromedb',        /* added 2013/01/10, see http://www.integromedb.org/Crawler */
        'content crawler spider',/* added 2013/01/11 */
        'toplistbot',         /* added 2013/02/05 */
        'seokicks-robot',     /* added 2013/02/25 */
        'it2media-domain-crawler',      /* added 2013/03/12 */
        'ip-web-crawler.com', /* added 2013/03/22 */
        'siteexplorer.info',  /* added 2013/05/01 */
        'elisabot',            /* added 2013/06/27 */
        'ProductAdsBot', // amazon
        'FatBot', // the find
        'shopwiki', //
        'addthis\.com',
        'unisterbot',
        'splicedcommercebot',
    );
     
    /**
     * isBot
     * 
     * @param string $userAgent
     * 
     * @return bool $userAgent
     */
    public static function isBot($userAgent)
    {
        foreach(self::$botUserAgents as $userAgentRegexp) {
            $expression = sprintf('/%s/i', $userAgentRegexp);
            if(preg_match($expression, $userAgent)){
                return true;
            }
        }
        return false;
    }
    
    /**
     * Parses a user agent string into its important parts
     *
     * @author Jesse G. Donat <donatj@gmail.com>
     * @link https://github.com/donatj/PhpUserAgent
     * @link http://donatstudios.com/PHP-Parser-HTTP_USER_AGENT
     * @param string $u_agent
     * @return array an array with browser, version and platform keys
     */
    public static function parseUserAgent( $u_agent = null ) {
        if(is_null($u_agent) && isset($_SERVER['HTTP_USER_AGENT'])) $u_agent = $_SERVER['HTTP_USER_AGENT'];
    
        $empty = array(
            'platform' => null,
            'browser' => null,
            'version' => null,
        );
    
        $data = $empty;
    
        if(!$u_agent) return $data;
    
        if( preg_match('/\((.*?)\)/im', $u_agent, $parent_matches) ) {
    
            preg_match_all('/(?P<platform>Android|CrOS|iPhone|iPad|Linux|Macintosh|Windows(\ Phone\ OS)?|Silk|linux-gnu|BlackBerry|PlayBook|Nintendo\ (WiiU?|3DS)|Xbox)
(?:\ [^;]*)?
(?:;|$)/imx', $parent_matches[1], $result, PREG_PATTERN_ORDER);
    
            $priority = array('Android', 'Xbox');
            $result['platform'] = array_unique($result['platform']);
            if( count($result['platform']) > 1 ) {
                if( $keys = array_intersect($priority, $result['platform']) ) {
                    $data['platform'] = reset($keys);
                }else{
                    $data['platform'] = $result['platform'][0];
                }
            }elseif(isset($result['platform'][0])){
                $data['platform'] = $result['platform'][0];
            }
        }
    
        if( $data['platform'] == 'linux-gnu' ) { $data['platform'] = 'Linux'; }
        if( $data['platform'] == 'CrOS' ) { $data['platform'] = 'Chrome OS'; }
    
        preg_match_all('%(?P<browser>Camino|Kindle(\ Fire\ Build)?|Firefox|Safari|MSIE|AppleWebKit|Chrome|IEMobile|Opera|OPR|Silk|Lynx|Version|Wget|curl|NintendoBrowser|Trident|Mozilla|PLAYSTATION\ (\d|Vita)+)
(?:;?)
(?:(?:[/ ])(?P<version>[0-9A-Z.]+)|/(?:[A-Z]*))%ix',
    $u_agent, $result, PREG_PATTERN_ORDER);
        //echo '<pre>';print_r($result);die();
        $key = 0;
    
        // If nothing matched, return null (to avoid undefined index errors)
        if (!isset($result['browser'][0]) || !isset($result['version'][0])) {
            return $empty;
        }
        
        foreach($result['browser'] as $key => $name){
            if($name == 'Trident'){
                $data['trident'] =  $result['version'][$key];
                continue;
            } else if($name == 'Mozilla'){
                $data['mozilla'] =  $result['version'][$key];
                continue;
            }
            $data['browser'] = $result['browser'][$key];
            $data['version'] = $result['version'][$key];
        }
        
        
        
            
        if( $key = array_search( 'Playstation Vita', $result['browser'] ) !== false ) {
            $data['platform'] = 'PlayStation Vita';
            $data['browser'] = 'Browser';
        }elseif( ($key = array_search( 'Kindle Fire Build', $result['browser'] )) !== false || ($key = array_search( 'Silk', $result['browser'] )) !== false ) {
            $data['browser'] = $result['browser'][$key] == 'Silk' ? 'Silk' : 'Kindle';
            $data['platform'] = 'Kindle Fire';
            if( !($data['version'] = $result['version'][$key]) || !is_numeric($data['version'][0]) ) {
                $data['version'] = $result['version'][array_search( 'Version', $result['browser'] )];
            }
        }elseif( ($key = array_search( 'NintendoBrowser', $result['browser'] )) !== false || $data['platform'] == 'Nintendo 3DS' ) {
            $data['browser'] = 'NintendoBrowser';
            $data['version'] = $result['version'][$key];
        }elseif( ($key = array_search( 'Kindle', $result['browser'] )) !== false ) {
            $data['browser'] = $result['browser'][$key];
            $data['platform'] = 'Kindle';
            $data['version'] = $result['version'][$key];
        }elseif( ($key = array_search( 'OPR', $result['browser'] )) !== false || ($key = array_search( 'Opera', $result['browser'] )) !== false ) {
            $data['browser'] = 'Opera';
            $data['version'] = $result['version'][$key];
            if( ($key = array_search( 'Version', $result['browser'] )) !== false ) { $data['version'] = $result['version'][$key]; }
        }elseif( $result['browser'][0] == 'AppleWebKit' ) {
            if( ( $data['platform'] == 'Android' && !($key = 0) ) || $key = array_search( 'Chrome', $result['browser'] ) ) {
                $data['browser'] = 'Chrome';
                if( ($vkey = array_search( 'Version', $result['browser'] )) !== false ) { $key = $vkey; }
            }elseif( $data['platform'] == 'BlackBerry' || $data['platform'] == 'PlayBook' ) {
                $data['browser'] = 'BlackBerry Browser';
                if( ($vkey = array_search( 'Version', $result['browser'] )) !== false ) { $key = $vkey; }
            }elseif( $key = array_search( 'Safari', $result['browser'] ) ) {
                $data['browser'] = 'Safari';
                if( ($vkey = array_search( 'Version', $result['browser'] )) !== false ) { $key = $vkey; }
            }
    
            $data['version'] = $result['version'][$key];
        }elseif( $result['browser'][0] == 'MSIE' ){
            if( $key = array_search( 'IEMobile', $result['browser'] ) ) {
                $data['browser'] = 'IEMobile';
            }else{
                $data['browser'] = 'MSIE';
                $key = 0;
            }
            $data['version'] = $result['version'][$key];
        }elseif( $key = array_search( 'PLAYSTATION 3', $result['browser'] ) !== false ) {
            $data['platform'] = 'PlayStation 3';
            $data['browser'] = 'NetFront';
        }
        
        
        
        if(
            $data['browser'] == 'MSIE' && 
            $data['version'] == '7.0' && 
            isset($data['trident']) && 
            $data['trident'] == '5.0' &&
            isset($data['mozilla']) && $data['mozilla'] == '4.0'){
            
            $data['browser'] = 'MSIE (7.0 Compatibility)';
            $data['version'] = '9.0';
        }  else if(
            $data['browser'] == 'MSIE' && 
            $data['version'] == '7.0' && 
            isset($data['trident']) && 
            $data['trident'] == '4.0' /*&&
            isset($data['mozilla']) && $data['mozilla'] == '4.0'*/ ){
            
            $data['browser'] = 'MSIE (7.0 Compatibility)';
            $data['version'] = '8.0';
            
        }
        
        return $data;
    
    }
}