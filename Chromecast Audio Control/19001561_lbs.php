###[DEF]###
[name           = Chromecast Audio Control v2.0.0   ]


[e#01 option    = Enable            #init=1         ] Enable/Disable LBS.
[e#02 important = WebAPI IP                         ] URL for "cast-web-api".
[e#03 important = Chromecast IP                     ] URL for the Chromecast Audio device you want to control.
[e#04 option    = StreamType        #init=BUFFERED  ] Stream type to use for steaming. LIVE/BUFFERED/NONE.
[e#05 option    = Enable Log        #init=1         ] Enable logging.

[e#06 option    = AutoPlay          #init=1         ] Automatically start playback when the URL changes.

[e#07 option    = CondPlay_Stopped  #init=1         ] Decides if "Conditional Play" will start playback if nothing is playing.
[e#08 option    = CondPlay_Cloud    #init=0         ] Decides if "Conditional Play" will start playback if the current stream was started from e.g. Google Home.
[e#09 option    = CondPlay_Local    #init=0         ] Decides if "Conditional Play" will start playback if the current stream was started from e.g. this script.

[e#10 option    = CondStop_Stopped  #init=1         ] Decides if "Conditional Stop" will stop playback if nothing is playing.
[e#11 option    = CondStop_Cloud    #init=1         ] Decides if "Conditional Stop" will stop playback if the current stream was started from e.g. Google Home.
[e#12 option    = CondStop_Local    #init=1         ] Decides if "Conditional Stop" will stop playback if the current stream was started from e.g. this script.

[e#13 option    = Media Type        #init=audio/mp3 ] Media type. Usually "audio/mp3".
[e#14 trigger   = URL                               ] Playback URL.

[e#15 trigger   = Volume                            ] Volume control (absolute value, 0-255).

[e#16 trigger   = Conditional Play                  ] Conditionally start or stop playback of the currently selected "channel", depending on CondPlay_*.
[e#17 trigger   = Conditional Stop                  ] Conditionally stop playback of the currently selected "channel", depending on CondStop_*.
[e#18 trigger   = Play                              ] Unconditionally start or stop playback.
[e#19 trigger   = Stop                              ] Unconditionally stop playback.

[e#20 trigger   = Get Status                        ] Update status from Chromecast (Volume and Status).


[a#1            = Status                            ] Current status of chromecast device. 1 = Not available, 2 = Idle, 3 = Playback from "Cloud" (eg. Google Home), 4 = Playback from "local" (eg. this LBS).
[a#2            = Volume                            ] Current volume.
[a#3            = PlaybackStateChanged              ] Signals changes in the playback state.


[v#1          = 0] // "Initialized" bool. Used to prevent initialization of values (channel) to cause play on startup.


###[/DEF]###

###[HELP]###
Steuern eines Chromecasts (Audio) über EDOMI.

Achtung: Für diesen LBS wird eine laufende Instanz von "cast-web-api" (v0.3.0) (https://github.com/vervallsweg/cast-web-api) benötigt.
Ohne dieses externe Skript ist keine Steuerung des Chromecasts möglich.

Siehe auch: https://knx-user-forum.de/forum/projektforen/edomi/1229424-lbs-19001560-chromecast-audio-control

E1	(DPT1)	Steuert, ob Befehle verarbeitet werden.
E2	(URL)	URL, unter welcher die "cast-web-api" Instanz zu erreichen ist (Bspw. 192.168.178.30:3000).
E3	(URL)	URL des Chromecasts, der gesteuert werden soll (Bspw. 192.168.178.31).
E4	(Text)	Legt fest, welcher "Stream Type" verwendet werden soll. Mögliche Werte: "LIVE", "BUFFERED", "NONE".
E5	(DPT1)	Legt fest, ob der LBS erweiterte Logs schreiben soll. Fehler werden immer geloggt.

E6	(DPT1)	Legt fest, ob die Wiedergabe nach Änderung der URL gestartet wird.

E7	(DPT1)	Legt fest, ob "Conditional Play" im Zustand "Gestoppt" ausgeführt wird.
E8	(DPT1)	Legt fest, ob "Conditional Play" im Zustand "Von Cloud" ausgeführt wird.
E9	(DPT1)	Legt fest, ob "Conditional Play" im Zustand "Von Lokal" ausgeführt wird.

E10	(DPT1)	Legt fest, ob "Conditional Stop" im Zustand "Gestoppt" ausgeführt wird.
E11	(DPT1)	Legt fest, ob "Conditional Stop" im Zustand "Von Cloud" ausgeführt wird.
E12	(DPT1)	Legt fest, ob "Conditional Stop" im Zustand "Von Lokal" ausgeführt wird.

E13	(Text)	Der "Media Type" der verwendet werden soll. In der Regel ist hier "audio/mp3" zu verwenden.
E14	(URL)	URL des Streams (Bspw. http://beliebigeseite.de/stream.mp3).

E15	(DPT5)	Legt die Lautstärke der Wiedergabe fest. Die Lautstärke wird als Absolutwert (0-255) übergeben.

E16	(DPT1)	Startet oder beendet die Wiedergabe in Abhängigkeit von E7-9.
E17	(DPT1)	Beendet die Wiedergabe in Abhängigkeit von E10-12.
E18	(DPT1)	Startet oder beendet die Wiedergabe.
E19	(DPT1)	Beendet die Wiedergabe.
E20	(DPT1)	Fragt den aktuellen Status des Chromecast ab (Lautstärke und Status).

A1	(DPT17)	Aktueller Status der Wiedergabe (1=Nicht Verfügbar, 2=Gestoppt, 3=Von Cloud, 4=Von Lokal).
A2	(DPT5)	Aktuelle Lautstärke (0-255).
A3	(DPT5)	Sendet ein Telegramm, wenn ein Play (1) / Stop (0) Kommando ausgeführt wird.

###[/HELP]### 

###[LBS]###
<?
function LB_LBSID($id) {	
	if ($inputs = logic_getInputs($id)) {		
		$loggingEnabled = $inputs[5]['value'];
		LB_LBSID_writeLog($loggingEnabled, $id, 7, '------------');
		LB_LBSID_writeLog($loggingEnabled, $id, 7, 'Started.');
	
		if (logic_getVar($id, 1) == 0) { // check init var. if it is zero, we've been triggered by the lbs init. Don't react to prevent unwanted effects.
			LB_LBSID_writeLog($loggingEnabled, $id, 6, 'Not initialized yet. No Processing.');
			logic_setVar($id, 1, 1);
			return;
		}
		
		if ($inputs[1]['value'] == 0) {
			LB_LBSID_writeLog($loggingEnabled, $id, 6, 'Disabled. Stopping.');
			return;
		}
		
		logic_setInputsQueued($id, $inputs);
		logic_callExec(LBSID, $id);
	}
	else {
		LB_LBSID_writeLog(1, $id, 6, 'Could not retrieve inputs.');
	}
}

function LB_LBSID_writeLog($loggingEnabled, $id, $logLevel, $logMessage) {
	if ($loggingEnabled == 1) {
		writeToCustomLog('ChromecastLBS', $logLevel, '[' . $id . '] ' . $logMessage);
	}
}
?>
###[/LBS]###

###[EXEC]###
<?
require(dirname(__FILE__)."/../../../../main/include/php/incl_lbsexec.php");
set_time_limit(15);
set_error_handler("warning_handler", E_WARNING); // Set error handler, so errors are added to the LBS log instead of EDOMI error log.

sql_connect();

// constants
$in_apiIP = 2;
$in_deviceIP = 3;
$in_streamType = 4;
$in_logging = 5;
$in_autoplay = 6;
$in_condplay_stopped = 7;
$in_condplay_cloud = 8;
$in_condplay_local = 9;
$in_condstop_stopped = 10;
$in_condstop_cloud = 11;
$in_condstop_local = 12;
$in_mediaType = 13;
$in_url = 14;
$in_volume = 15;
$in_condplay = 16;
$in_condstop = 17;
$in_play = 18;
$in_stop = 19;
$in_status = 20;

$out_sessionId = 0; // not really out, but we rather use it to access the sessionId in the status variable.
$out_status = 1;
$out_volume = 2;
$out_playbackState = 3;

$state_noChange = 1;
$state_play = 1;
$state_stop = 0;

$statuscodes['status_na'] = 1;
$statuscodes['status_idle'] = 2;
$statuscodes['status_cloud'] = 3;
$statuscodes['status_local'] = 4;


$inputs = logic_getInputsQueued($id);
$loggingEnabled = $inputs[$in_logging]['value'];
$apiIP = $inputs[$in_apiIP]['value'];
$deviceIP = $inputs[$in_deviceIP]['value'];
$streamType = $inputs[$in_streamType]['value'];

$send = 0;
//$stateChange = $state_noChange;


// Get status from device.
writeLog($loggingEnabled, $id, 6, 'Requesting Status.');
$status = getChromecastStatus($apiIP, $deviceIP, $statuscodes);
$sessionId = $status[$out_sessionId];

writeLog($loggingEnabled, $id, 6, 'Initial Status: [' . $status[$out_status] . '], Volume: [' . $status[$out_volume] . '], sessionId: [' . $sessionId . ']');

// from here on, check which command triggered the execution, act accordingly.

if ($inputs[$in_url]['refresh'] == 1) {
	writeLog($loggingEnabled, $id, 6, 'Setting URL: ' . $inputs[$in_url]['value']);

	if ($inputs[$in_autoplay]['value'] == 1) {
		// Autoplay is active, we need to process this as if we would process a play command.
		writeLog($loggingEnabled, $id, 6, 'Trigger: URL.');
		
		$inputs[$in_play]['refresh'] = 1;
		$inputs[$in_play]['value'] = 1;
	}
}


if ($inputs[$in_volume]['refresh'] == 1) {
	writeLog($loggingEnabled, $id, 6, 'Trigger: Volume.');
	$send = 1;
	
	$volume = $inputs[$in_volume]['value'];
	writeLog($loggingEnabled, $id, 6, 'Setting Volume: ' . $volume);
    $status[$out_volume] = setVolume($apiIP, $deviceIP, $volume);
}


if ($inputs[$in_condplay]['refresh'] == 1) {
	if ($inputs[$in_condplay]['value'] == 1) {
		writeLog($loggingEnabled, $id, 7, 'Trigger: CondPlay. Current status: ' . $status[$out_status]);
		$send = 1;

		if ($status[$out_status] == $statuscodes['status_na'] ||
			$status[$out_status] == $statuscodes['status_idle']) {
			if ($inputs[$in_condplay_stopped]['value'] == 1) {
				writeLog($loggingEnabled, $id, 7, 'CondPlay matches: IDLE');
				$inputs[$in_play]['refresh'] = 1;
				$inputs[$in_play]['value'] = 1;
			}
		}
		else if ($status[$out_status] == $statuscodes['status_cloud']) {
			if ($inputs[$in_condplay_cloud]['value'] == 1) {
				writeLog($loggingEnabled, $id, 7, 'CondPlay matches: CLOUD');
				$inputs[$in_play]['refresh'] = 1;
				$inputs[$in_play]['value'] = 1;
			}
		}
		else if ($status[$out_status] == $statuscodes['status_local']) {
			if ($inputs[$in_condplay_local]['value'] == 1) {
				writeLog($loggingEnabled, $id, 7, 'CondPlay matches: LOCAL');
				$inputs[$in_play]['refresh'] = 1;
				$inputs[$in_play]['value'] = 1;
			}
		}
		else {
			writeLog($loggingEnabled, $id, 7, 'CondPlay: No match.');
		}
	}
	else if ($inputs[$in_condplay]['value'] == 0) {
		writeLog($loggingEnabled, $id, 7, 'Remapping CondPlay to CondStop');
		$inputs[$in_condstop]['refresh'] = 1;
		$inputs[$in_condstop]['value'] = 1;
	}
}


if ($inputs[$in_condstop]['refresh'] == 1) {
	if ($inputs[$in_condstop]['value'] == 1) {
		writeLog($loggingEnabled, $id, 6, 'Trigger: CondStop. Current status: ' . $status[$out_status]);
		$send = 1;
		
		if ($status[$out_status] == $statuscodes['status_na'] ||
			$status[$out_status] == $statuscodes['status_idle']) {
			if ($inputs[$in_condstop_stopped]['value'] == 1) {
				writeLog($loggingEnabled, $id, 7, 'CondStop matches: IDLE');
				$inputs[$in_play]['refresh'] = 1;
				$inputs[$in_play]['value'] = 1;
			}
		}
		else if ($status[$out_status] == $statuscodes['status_cloud']) {
			if ($inputs[$in_condstop_cloud]['value'] == 1) {
				writeLog($loggingEnabled, $id, 7, 'CondStop matches: CLOUD');
				$inputs[$in_play]['refresh'] = 1;
				$inputs[$in_play]['value'] = 1;
			}
		}
		else if ($status[$out_status] == $statuscodes['status_local']) {
			if ($inputs[$in_condstop_local]['value'] == 1) {
				writeLog($loggingEnabled, $id, 7, 'CondStop matches: LOCAL');
				$inputs[$in_play]['refresh'] = 1;
				$inputs[$in_play]['value'] = 1;
			}
		}
		else {
			writeLog($loggingEnabled, $id, 7, 'CondStop: No match.');
		}
	}
}


if ($inputs[$in_play]['refresh'] == 1) {
	if ($inputs[$in_play]['value'] == 1) {
		writeLog($loggingEnabled, $id, 6, 'Trigger: Play.');
		$send = 1;
		$stateChange = 1;
		
		$url = $inputs[$in_url]['value'];
		$type = $inputs[$in_mediaType]['value'];
		$volume = $inputs[$in_volume]['value'];
		
		if (isset($volume) && isset($type) && isset($url)){
			writeLog($loggingEnabled, $id, 6, 'Play: Starting playback. URL: [' . $url . '], Volume: [' . $volume . ']');
			$status[$out_volume] = setVolume($apiIP, $deviceIP, $volume);
			$status[$out_status] = startPlayback($apiIP, $deviceIP, $streamType, $type, $url, $statuscodes);
		}
		else {
			writeLog($loggingEnabled, $id, 3, 'Play: Vars not set?');
		}		
	}
	else if ($inputs[$in_play]['value'] == 0) {
		writeLog($loggingEnabled, $id, 7, 'Remapping Play to Stop');
		$inputs[$in_stop]['refresh'] = 1;
		$inputs[$in_stop]['value'] = 1;
	}
}


if ($inputs[$in_stop]['refresh'] == 1) {
	if ($inputs[$in_stop]['value'] == 1) {
		writeLog($loggingEnabled, $id, 6, 'Trigger: Stop.');
		writeLog($loggingEnabled, $id, 6, 'Stop: Sending stop command.');
		$send = 1;
		$stateChange = 0;
		
		if (isset($sessionId)) {
			$status[$out_status] = stopPlayback($apiIP, $deviceIP, $sessionId, $statuscodes);
		}
		else {
			$status[$out_status] = startPlayback($apiIP, $deviceIP, $streamType, 'null', 'null', $statuscodes);
		}
	}
}


if ($inputs[$in_status]['refresh'] == 1) {
	if ($inputs[$in_status]['value'] == 1) {
		writeLog($loggingEnabled, $id, 6, 'Trigger: Status.');
		$send = 1;
	}
}


// update status
if ($send == 1) {
	writeLog($loggingEnabled, $id, 6, 'Final Status: [' . $status[$out_status] . '], Volume: [' . $status[$out_volume] . '], sessionId: [' . $sessionId . ']');
	logic_setOutput($id, $out_status, $status[$out_status]);
	logic_setOutput($id, $out_volume, $status[$out_volume]);
}

if (isset($stateChange)){
	writeLog($loggingEnabled, $id, 6, 'Playback state changed. State: [' . $stateChange . ']');
	logic_setOutput($id, $out_playbackState, $stateChange);
}

// functions
function getChromecastStatus($apiIP, $deviceIP, $statuscodes) {
    $status_raw = file_get_contents('http://' . $apiIP . '/getDeviceStatus?address=' . $deviceIP);
    $status_content = json_decode($status_raw, true);

    $volume = round($status_content["status"]["volume"]["level"] * 255, 0, PHP_ROUND_HALF_DOWN);

    if (empty($status_content)) {
        $status = $statuscodes['status_na'];
    }
    else if (empty($status_content["status"]["applications"])) {
        $status = $statuscodes['status_idle'];
    }
    else if ($status_content["status"]["applications"]["0"]["launchedFromCloud"] == 1) {
        $status = $statuscodes['status_cloud'];
        $session = $status_content["status"]["applications"]["0"]["sessionId"];
    }
    else {
        $status = $statuscodes['status_local'];
        $session = $status_content["status"]["applications"]["0"]["sessionId"];
    }

    if (isset($session)){
        return array($session, $status, $volume);
    }

    return array(0, $status, $volume);
}

function setVolume($apiIP, $deviceIP, $volume) {
    $volume_raw = file_get_contents('http://' . $apiIP . '/setDeviceVolume?address=' . $deviceIP . '&volume=' . $volume / 255);
    $volume_content = json_decode($volume_raw, true);
    return round($volume_content["status"]["volume"]["level"] * 255, 0, PHP_ROUND_HALF_DOWN);
}

function startPlayback($apiIP, $deviceIP, $streamType, $type, $url, $statuscodes) {
    if (!isset($type) || !isset($url)){
        return $statuscodes['status_idle'];
    }

    $play_raw = file_get_contents('http://' . $apiIP . '/setMediaPlayback?address=' . $deviceIP . '&mediaType=' . $type . '&mediaUrl=' . $url . '&mediaStreamType=' . $streamType . '&mediaTitle=null&mediaSubtitle=null&mediaImageUrl=null');
    $play_content = json_decode($play_raw, true);

    if ($play_content["status"][0]["playerState"] == 'PLAYING'){
        return $statuscodes['status_local'];
    }

    return $statuscodes['status_na'];
}

function stopPlayback($apiIP, $deviceIP, $sessionId, $statuscodes) {
    $stop_raw = file_get_contents('http://' . $apiIP . '/setDevicePlaybackStop?address=' . $deviceIP . '&sessionId=' . $sessionId);
    //$stop_content = json_decode($stop_raw, true);

    return $statuscodes['status_idle'];
}

function writeLog($loggingEnabled, $id, $logLevel, $logMessage) {
	if ($loggingEnabled == 1) {
		writeToCustomLog('ChromecastLBS', $logLevel, '[' . $id . '] ' . $logMessage);
	}
}

function warning_handler($errno, $errstr) {
	writeToCustomLog('ChromecastLBS', 1, '[' . $elementid . '] Error during processing: [' . $errno . '] [' . $errstr . ']');
}

sql_disconnect();
?>
###[/EXEC]###
