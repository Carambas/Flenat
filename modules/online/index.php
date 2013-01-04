<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");
// ///////////////////////////////////////////////////////////////
// //
// //
// ///////////////////////////////////////////////////////////////
if ($user_id['grup'] == 1) $moder = true;

if ($config['onl_here'] != '0') {
    $time_online = time() - $config['onl_time'] * 60;
    $rand = rand(1, 20);
    if ($rand % 2 == 0) {
        $db->query("DELETE FROM `online` WHERE `time` < '$time_online'");
    } 

    $_IP = $db->safesql($_SERVER['REMOTE_ADDR']);
    $_UA = $db->safesql($_SERVER['HTTP_USER_AGENT']);
    $_PROXY = $db->safesql(getenv('HTTP_X_FORWARDED_FOR'));

    if (isset($_REQUEST['login']) and $_REQUEST['login'] == "submit") {
        if (PHP_VERSION < 5.2) {
            setcookie("module_online_w", "", 0, "/", DOMAIN . "; HttpOnly");
        } else {
            setcookie("module_online_w", "", 0, "/", DOMAIN, null, true);
        } 

        $key = md5("guest" . $_IP);
        $db->query("DELETE FROM `online` WHERE `key`='{$key}'");
    } 

    if (!isset($_COOKIE['module_online_w'])) {
        if ($login) {
            $os_arr = array('Windows NT 4.0' => 'Windows NT',
                'Windows NT 3.5' => 'Windows NT',
                'Windows NT 5.0' => 'Windows 2000',
                'Windows NT 5.1' => 'Windows XP',
                'Windows NT 5.2' => 'Windows XP x64 or Windows Server 2003',
                'Windows NT 6.0' => 'Windows Vista',
                'Windows NT 6.1' => 'Windows 7',
                'Windows CE' => 'Windows CE or Windows Mobile',
                'Windows Me' => 'Windows Me',
                'Windows 98' => 'Windows 98',
                'Windows 95 ' => 'Windows 95',
                'Linux' => 'Linux',
                'Lynx' => 'Linux',
                'Unix' => 'Linux',
                'Macintosh' => 'Macintosh',
                'PowerPC' => 'Macintosh',
                'OS/2' => 'OS/2',
                'BeOS' => 'BeOS');

            foreach($os_arr as $key => $value) {
                if (strstr(strtolower($_UA), strtolower($key))) {
                    $OS = $value;
                } 
            } 

            if (preg_match('/(Opera|Firefox|Safari|Flock|MSIE|K-Meleon|SeaMonkey|Camino|Firebird|Epiphany|Chrome|America Online Browser)[\/: ]([\d.]+)/', $_UA, $out)) {
                if ($out[1] == "MSIE") {
                    $out[1] = "Internet Explorer";
                } 
                $user_agent = $out[1] . " " . $out[2];
            } 

            if ($_PROXY != false) {
                $proxy = $_PROXY;
            } else {
                $proxy = "unknown";
            } 

            $key = md5($user_id['user_id'] . $_IP);

            $db->query("INSERT INTO `online` (`key`, `uid`, `uname`, `time`, `ip`, `user_agent`, `os`, `proxy`) VALUES ('{$key}', '{$user_id['user_id']}', '{$user_id['name']}', '$_TIME', '{$_IP}', '{$user_agent}', '$OS', '$proxy') ON DUPLICATE KEY UPDATE `key`=VALUES(`key`), `uid`=VALUES(`uid`), `uname`=VALUES(`uname`), `user_agent`=VALUES(`user_agent`), `OS`='$OS', `proxy`='$proxy', `time`='$_TIME'");
        } else {
            $robots = array('Mail.Ru' => "Mail Ru",
                'spider' => "spider Bot",
                'robot' => "robot Bot",
                'crawl' => "crawl Bot",
                'msiecrawler' => "MSIE Crawler",
                'spider17' => "yandex.ru",
                'spider17.yandex.ru' => "Вот",
                'twiceler' => "Cuil",
                'googlebot' => "Google Bot",
                'mediapartners-google' => "Google AdSense",
                'slurp@inktomi' => "Hot Bot",
                'archive_org' => "Archive.org Bot",
                'Ask Jeeves' => "Ask Jeeves Bot",
                'Lycos' => "Lycos Bot",
                'WhatUSeek' => "What You Seek Bot",
                'ia_archiver' => "IA.Archiver Bot",
                'GigaBlast' => "Gigablast Bot",
                'Yahoo!' => "Yahoo Bot",
                'Yahoo-MMCrawler' => "Yahoo-MMCrawler Bot",
                'TurtleScanner' => "TurtleScanner Bot",
                'TurnitinBot' => "TurnitinBot",
                'ZipppBot' => "ZipppBot",
                'oBot' => "oBot",
                'rambler' => "Rambler Bot",
                'Jetbot' => "Jet Bot",
                'NaverBot' => "Naver Bot",
                'libwww' => "Punto Bot",
                'aport' => "Aport Bot",
                'msnbot' => "MSN Bot",
                'MnoGoSearch' => "mnoGoSearch Bot",
                'booch' => "Booch Bot",
                'Openbot' => "Openfind Bot",
                'scooter' => "Altavista Bot",
                'WebCrawler' => "Fast Bot",
                'WebZIP' => "WebZIP Bot",
                'GetSmart' => "GetSmart Bot",
                'grub-client' => "GrubClient Bot",
                'Vampire' => "Net_Vampire Bot",
                'Rambler' => "Rambler Bot",
                'appie' => "Walhello appie",
                'architext' => "ArchitextSpider",
                'jeeves' => "AskJeeves",
                'bjaaland' => "Bjaaland",
                'ferret' => "Wild Ferret Web Hopper #1, #2, #3",
                'gulliver' => "Northern Light Gulliver",
                'harvest' => "Harvest",
                'htdig' => "ht://Dig",
                'linkwalker' => "LinkWalker",
                'lycos_' => "Lycos",
                'moget' => "moget",
                'muscatferret' => "Muscat Ferret",
                'myweb' => "Internet Shinchakubin",
                'nomad' => "Nomad",
                'scooter' => "Scooter",
                'slurp' => "Inktomi Slurp",
                'voyager' => "Voyager",
                'weblayers' => "weblayers",
                'antibot' => "Antibot",
                'digout4u' => "Digout4u",
                'echo' => "EchO!",
                'fast-webcrawler' => "Fast-Webcrawler",
                'ia_archiver' => "Alexa (IA Archiver)",
                'jennybot' => "JennyBot",
                'mercator' => "Mercator",
                'netcraft' => "Netcraft",
                'petersnews' => "Petersnews",
                'unlost_web_crawler' => "Unlost Web Crawler",
                'voila' => "Voila",
                'webbase' => "WebBase",
                'wisenutbot' => "WISENutbot",
                'fish' => "Fish search",
                'abcdatos' => "ABCdatos BotLink",
                'acme.spider' => "Acme.Spider",
                'ahoythehomepagefinder' => "Ahoy! The Homepage Finder",
                'alkaline' => "Alkaline",
                'anthill' => "Anthill",
                'arachnophilia' => "Arachnophilia",
                'arale' => "Arale",
                'araneo' => "Araneo",
                'aretha' => "Aretha",
                'ariadne' => "ARIADNE",
                'arks' => "arks",
                'aspider' => "ASpider (Associative Spider)",
                'atn.txt' => "ATN Worldwide",
                'atomz' => "Atomz.com Search Robot",
                'auresys' => "AURESYS",
                'backrub' => "BackRub",
                'bbot' => "BBot",
                'bigbrother' => "Big Brother",
                'blackwidow' => "BlackWidow",
                'blindekuh' => "Die Blinde Kuh",
                'bloodhound' => "Bloodhound",
                'borg-bot' => "Borg-Bot",
                'brightnet' => "bright.net caching robot",
                'bspider' => "BSpider",
                'cactvschemistryspider' => "CACTVS Chemistry Spider",
                'calif' => "Calif",
                'cassandra' => "Cassandra",
                'cgireader' => "Digimarc Marcspider/CGI",
                'checkbot' => "Checkbot",
                'christcrawler' => "ChristCrawler.com",
                'churl' => "churl",
                'cienciaficcion' => "cIeNcIaFiCcIoN.nEt",
                'collective' => "Collective",
                'combine' => "Combine System",
                'conceptbot' => "Conceptbot",
                'coolbot' => "CoolBot",
                'core' => "Web Core / Roots",
                'cosmos' => "XYLEME Robot",
                'cruiser' => "Internet Cruiser Robot",
                'cusco' => "Cusco",
                'cyberspyder' => "CyberSpyder Link Test",
                'desertrealm' => "Desert Realm Spider",
                'deweb' => "DeWeb© Katalog/Index",
                'dienstspider' => "DienstSpider",
                'digger' => "Digger",
                'diibot' => "Digital Integrity Robot",
                'direct_hit' => "Direct Hit Grabber",
                'dnabot' => "DNAbot",
                'download_express' => "DownLoad Express",
                'dragonbot' => "DragonBot",
                'dwcp' => "DWCP (Dridus' Web Cataloging Project)",
                'e-collector' => "e-collector",
                'ebiness' => "EbiNess",
                'elfinbot' => "ELFINBOT",
                'emacs' => "Emacs-w3 Search Engine",
                'emcspider' => "ananzi",
                'esther' => "Esther",
                'evliyacelebi' => "Evliya Celebi",
                'fastcrawler' => "FastCrawler",
                'fdse' => "Fluid Dynamics Search Engine robot",
                'felix' => "Felix IDE",
                'fetchrover' => "FetchRover",
                'fido' => "fido",
                'finnish' => "Hдmдhдkki",
                'fireball' => "KIT-Fireball",
                'fouineur' => "Fouineur",
                'francoroute' => "Robot Francoroute",
                'freecrawl' => "Freecrawl",
                'funnelweb' => "FunnelWeb",
                'gama' => "gammaSpider, FocusedCrawler",
                'gazz' => "gazz",
                'gcreep' => "GCreep",
                'getbot' => "GetBot",
                'geturl' => "GetURL",
                'golem' => "Golem",
                'grapnel' => "Grapnel/0.01 Experiment",
                'griffon' => "Griffon",
                'gromit' => "Gromit",
                'gulperbot' => "Gulper Bot",
                'hambot' => "HamBot",
                'havindex' => "havIndex",
                'hometown' => "Hometown Spider Pro",
                'htmlgobble' => "HTMLgobble",
                'hyperdecontextualizer' => "Hyper-Decontextualizer",
                'iajabot' => "iajaBot",
                'iconoclast' => "Popular Iconoclast",
                'ilse' => "Ingrid",
                'imagelock' => "Imagelock",
                'incywincy' => "IncyWincy",
                'informant' => "Informant",
                'infoseek' => "InfoSeek Robot 1.0",
                'infoseeksidewinder' => "Infoseek Sidewinder",
                'infospider' => "InfoSpiders",
                'inspectorwww' => "Inspector Web",
                'intelliagent' => "IntelliAgent",
                'irobot' => "I, Robot",
                'iron33' => "Iron33",
                'israelisearch' => "Israeli-search",
                'javabee' => "JavaBee",
                'jbot' => "JBot Java Web Robot",
                'jcrawler' => "JCrawler",
                'jobo' => "JoBo Java Web Robot",
                'jobot' => "Jobot",
                'joebot' => "JoeBot",
                'jubii' => "The Jubii Indexing Robot",
                'jumpstation' => "JumpStation",
                'kapsi' => "image.kapsi.net",
                'katipo' => "Katipo",
                'kilroy' => "Kilroy",
                'ko_yappo_robot' => "KO_Yappo_Robot",
                'labelgrabber.txt' => "LabelGrabber",
                'larbin' => "larbin",
                'legs' => "legs",
                'linkidator' => "Link Validator",
                'linkscan' => "LinkScan",
                'lockon' => "Lockon",
                'logo_gif' => "logo.gif Crawler",
                'macworm' => "Mac WWWWorm",
                'magpie' => "Magpie",
                'marvin' => "marvin/infoseek",
                'mattie' => "Mattie",
                'mediafox' => "MediaFox",
                'merzscope' => "MerzScope",
                'meshexplorer' => "NEC-MeshExplorer",
                'mindcrawler' => "MindCrawler",
                'mnogosearch' => "mnoGoSearch search engine software",
                'momspider' => "MOMspider",
                'monster' => "Monster",
                'motor' => "Motor",
                'muncher' => "Muncher",
                'mwdsearch' => "Mwd.Search",
                'ndspider' => "NDSpider",
                'nederland.zoek' => "Nederland.zoek",
                'netcarta' => "NetCarta WebMap Engine",
                'netmechanic' => "NetMechanic",
                'netscoop' => "NetScoop",
                'newscan-online' => "newscan-online",
                'nhse' => "NHSE Web Forager",
                'northstar' => "The NorthStar Robot",
                'nzexplorer' => "nzexplorer",
                'objectssearch' => "ObjectsSearch",
                'occam' => "Occam",
                'octopus' => "HKU WWW Octopus",
                'openfind' => "Openfind data gatherer",
                'orb_search' => "Orb Search",
                'packrat' => "Pack Rat",
                'pageboy' => "PageBoy",
                'parasite' => "ParaSite",
                'patric' => "Patric",
                'pegasus' => "pegasus",
                'perignator' => "The Peregrinator",
                'perlcrawler' => "PerlCrawler 1.0",
                'phantom' => "Phantom",
                'phpdig' => "PhpDig",
                'piltdownman' => "PiltdownMan",
                'pimptrain' => "Pimptrain.com's robot",
                'pioneer' => "Pioneer",
                'pitkow' => "html_analyzer",
                'pjspider' => "Portal Juice Spider",
                'plumtreewebaccessor' => "PlumtreeWebAccessor",
                'poppi' => "Poppi",
                'portalb' => "PortalB Spider",
                'psbot' => "psbot",
                'python' => "The Python Robot",
                'raven' => "Raven Search",
                'rbse' => "RBSE Spider",
                'resumerobot' => "Resume Robot",
                'rhcs' => "RoadHouse Crawling System",
                'road_runner' => "Road Runner: The ImageScape Robot",
                'robbie' => "Robbie the Robot",
                'robi' => "ComputingSite Robi/1.0",
                'robocrawl' => "RoboCrawl Spider",
                'robofox' => "RoboFox",
                'robozilla' => "Robozilla",
                'roverbot' => "Roverbot",
                'rules' => "RuLeS",
                'safetynetrobot' => "SafetyNet Robot",
                'search-info' => "Sleek",
                'search_au' => "Search.Aus-AU.COM",
                'searchprocess' => "SearchProcess",
                'senrigan' => "Senrigan",
                'sgscout' => "SG-Scout",
                'shaggy' => "ShagSeeker",
                'shaihulud' => "Shai'Hulud",
                'sift' => "Sift",
                'simbot' => "Simmany Robot Ver1.0",
                'site-valet' => "Site Valet",
                'sitetech' => "SiteTech-Rover",
                'skymob' => "Skymob.com",
                'slcrawler' => "SLCrawler",
                'smartspider' => "Smart Spider",
                'snooper' => "Snooper",
                'solbot' => "Solbot",
                'speedy' => "Speedy Spider",
                'spider_monkey' => "spider_monkey",
                'spiderbot' => "SpiderBot",
                'spiderline' => "Spiderline Crawler",
                'spiderman' => "SpiderMan",
                'spiderview' => "SpiderView™",
                'spry' => "Spry Wizard Robot",
                'ssearcher' => "Site Searcher",
                'suke' => "Suke",
                'suntek' => "suntek search engine",
                'sven' => "Sven",
                'tach_bw' => "TACH Black Widow",
                'tarantula' => "Tarantula",
                'tarspider' => "tarspider",
                'techbot' => "TechBOT",
                'templeton' => "Templeton",
                'titan' => "TITAN",
                'titin' => "TitIn",
                'tkwww' => "The TkWWW Robot",
                'tlspider' => "TLSpider",
                'ucsd' => "UCSD Crawl",
                'udmsearch' => "UdmSearch",
                'urlck' => "URL Check",
                'valkyrie' => "Valkyrie",
                'verticrawl' => "Verticrawl",
                'victoria' => "Victoria",
                'visionsearch' => "vision-search",
                'voidbot' => "void-bot",
                'vwbot' => "VWbot",
                'w3index' => "The NWI Robot",
                'w3m2' => "W3M2",
                'wallpaper' => "WallPaper (alias crawlpaper)",
                'wanderer' => "the World Wide Web Wanderer",
                'wapspider' => "w@pSpider by wap4.com",
                'webbandit' => "WebBandit Web Spider",
                'webcatcher' => "WebCatcher",
                'webcopy' => "WebCopy",
                'webfetcher' => "webfetcher",
                'webfoot' => "The Webfoot Robot",
                'webinator' => "Webinator",
                'weblinker' => "WebLinker",
                'webmirror' => "WebMirror",
                'webmoose' => "The Web Moose",
                'webquest' => "WebQuest",
                'webreader' => "Digimarc MarcSpider",
                'webreaper' => "WebReaper",
                'websnarf' => "Websnarf",
                'webspider' => "WebSpider",
                'webvac' => "WebVac",
                'webwalk' => "webwalk",
                'webwalker' => "WebWalker",
                'webwatch' => "WebWatch",
                'whatuseek' => "whatUseek Winona",
                'whowhere' => "WhoWhere Robot",
                'wired-digital' => "Wired Digital",
                'wmir' => "w3mir",
                'wolp' => "WebStolperer",
                'wombat' => "The Web Wombat",
                'worm' => "The World Wide Web Worm",
                'wwwc' => "WWWC Ver 0.2.5",
                'wz101' => "WebZinger",
                'xget' => "XGET",
                'awbot' => "AWBot",
                'baiduspider' => "BaiDuSpider",
                'bobby' => "Bobby",
                'boris' => "Boris",
                'bumblebee' => "Bumblebee (relevare.com)",
                'cscrawler' => "CsCrawler",
                'daviesbot' => "DaviesBot",
                'exactseek' => "ExactSeek Crawler",
                'ezresult' => "sEzresult",
                'gigabot' => "GigaBot",
                'gnodspider' => "sGNOD Spider",
                'grub' => "Grub.org",
                'henrythemiragorobot' => "Mirago",
                'holmes' => "Holmes",
                'internetseer' => "InternetSeer",
                'justview' => "JustView",
                'linkbot' => "LinkBot",
                'linkchecker' => "LinkChecker",
                'metager-linkchecker' => "MetaGer LinkChecker",
                'microsoft_url_control' => "Microsoft URL Control",
                'nagios' => "Nagios",
                'perman' => "Perman surfer",
                'pompos' => "Pompos",
                'rambler' => "StackRambler",
                'redalert' => "Red Alert",
                'shoutcast' => "Shoutcast Directory Service",
                'slysearch' => "SlySearch",
                'surveybot' => "SurveyBot",
                'turnitinbot' => "Turn It In",
                'turtle' => "Turtle",
                'turtlescanner' => "Turtle",
                'ultraseek' => "Ultraseek",
                'webclipping.com' => "WebClipping.com",
                'webcompass' => "webcompass",
                'wonderer' => "spider: Web Wombat Redback Spider",
                'yahoo-verticalcrawler' => "Yahoo Vertical Crawler",
                'zealbot' => "ZealBot",
                'zyborg' => "Zyborg",
                'BecomeBot' => "Become Bot",
                'Yandex' => "Yandex Bot",
                'StackRambler' => "Rambler Bot",
                'ask jeeves' => "Ask Jeeves Bot",
                'lycos' => "Lycos.com Bot",
                'whatuseek' => "What You Seek Bot",
                'ia_archiver' => "Archive.org Bot"
                );

            foreach($robots as $key => $value) {
                if (strstr(strtolower($_UA), strtolower($key))) {
                    $robot = $value;
                } 
            } 

            if ($robot != '') {
                $key = md5($robot . $_IP);
                $db->query("INSERT INTO `online` (`key`, `uid`, `uname`, `time`, `ip`, `user_agent`, `os`, `proxy`) VALUES ('{$key}', '0', 'robot', '$_TIME', '{$_IP}', '$robot', 'unknown', 'unknown') ON DUPLICATE KEY UPDATE `key`=VALUES(`key`), `time`=VALUES(`time`)");
            } else {
                $key = md5("guest" . $_IP);
                $db->query("INSERT INTO `online` (`key`, `uid`, `uname`, `time`, `ip`) VALUES ('{$key}', '0', 'guest', '$_TIME', '{$_IP}') ON DUPLICATE KEY UPDATE `uid`='0', `uname`='guest', `user_agent`='unknown', `OS`='unknown', `proxy`='unknown', `time`='$_TIME'");
            } 
        } 

        $expires = time() + ($config['onl_time'] * 60);

        if (PHP_VERSION < 5.2) {
            setcookie("module_online_w", "1", $expires, "/", DOMAIN . "; HttpOnly");
        } else {
            setcookie("module_online_w", "1", $expires, "/", DOMAIN, null, true);
        } 
    } 

    $sql = $db->query("SELECT * FROM online");
    $all = 0;
    $guests = 0;
    $robots_count = 0;
    $users_count = 0;
    while ($row = $db->get_row($sql)) {
        $all++;

        if ($row['uid'] == 0) {
            if ($row['uname'] == "guest") {
                $guests++;
            } 

            if ($row['uname'] == "robot") {
                if ($config['onl_limit_robots'] > $robots_count) {
                    $robots .= check_full($row['user_agent']) . ", ";
                    $robots_count++;
                } else {
                } 
            } 
        } else {
            if ($config['onl_limit_users'] > $users_count) {
                $tpl->load_tpl('online.tpl');

                if ($config['onl_visit'] == 1) {
                    if (date('d.m.Y') == date('d.m.Y', $row['time'])) {
                        $last_visit = "Сегодня, " . date("H:i:s", $row['time']);
                    } else {
                        $last_visit = date("d.m.Y, H:i:s", $row['time']);
                    } 
                    $tpl->set('[date]', '');
                    $tpl->set('{date}', check_full($last_visit));
                    $tpl->set('[/date]', '');
                } else {
                    $last_visit = '';
                    $tpl->set_block("'\\[date\\](.*?)\\[/date\\]'si", "");
                } 

                if ($config['onl_proxy'] == 1 and $row['ip'] != $row['proxy'] and $row['proxy'] != 'unknown') {
                    $proxy = "<br>Proxy: </b>" . check_full($row['proxy']);
                } else {
                    $proxy = '';
                } 
                if ($config['onl_ip'] == 1) {
                    $ip = " IP: </b>" . check_full($row['ip']) . "" . $proxy . "";
                    $tpl->set('[ip]', '');
                    $tpl->set('{ip}', $ip);
                    $tpl->set('[/ip]', '');
                } else {
                    $ip = '';
                    $tpl->set_block("'\\[ip\\](.*?)\\[/ip\\]'si", "");
                } 

                if ($config['onl_agent'] == 1) {
                    $agent = check_full($row['user_agent']);
                    $tpl->set('[agent]', '');
                    $tpl->set('{agent}', $agent);
                    $tpl->set('[/agent]', '');
                } else {
                    $agent = '';
                    $tpl->set_block("'\\[agent\\](.*?)\\[/agent\\]'si", "");
                } 

                $users_count++;

                $tpl->set_block("'\\[online\\](.*?)\\[/online\\]'si", "");
                $tpl->set('[users]', '');
                $tpl->set('[/users]', '');
                $tpl->set('{name}', '<a href="' . $PHP_SELF . '?do=profile&name=' . check_full($row['uname']) . '">' . check_full($row['uname']) . '</a>');
                $tpl->compile('onllist');
                $users = $tpl->result['onllist'];
            } 
        } 
    } 

    if ($users_count == 0) {
        $users = "";
    } else {
        $users = substr($users, 0, -2);
    } 
    if ($robots_count == 0) {
        $robots = " Нет";
    } else {
        $robots = substr($robots, 0, -2);
    } 
    if ($guests == 0) {
        $guests = " Нет";
    } 

    if ($on_online == 1) {
        $tpl->load_tpl('online.tpl');
        $tpl->set('[online]', '');
        $tpl->set('[/online]', '');
        $tpl->set('{guests}', $guests);
        $tpl->set_block("'\\[users\\](.*?)\\[/users\\]'si", "");
        $tpl->set('{users}', $users);
        $tpl->set('{robots}', $robots);
        $tpl->set('{users_count}', $users_count);
        $tpl->set('{robots_count}', $robots_count);
        $tpl->set('{all}', $all);
        $tpl->compile('content');
        $tpl->clear();
    } else {
        $tpl->copy_tpl = $all;
        $tpl->compile('online');
        $tpl->clear();
    } 
} 

?>