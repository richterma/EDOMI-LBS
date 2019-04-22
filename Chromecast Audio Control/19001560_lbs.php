###[DEF]###
[name           = Chromecast Audio Control v1.0.0   ]


[e#01 option    = Enable            #init=1         ] Enable/Disable LBS.
[e#02 important = WebAPI IP                         ] URL for "cast-web-api".
[e#03 important = Chromecast IP                     ] URL for the Chromecast Audio device you want to control.
[e#04 option    = StreamType        #init=BUFFERED  ] Stream type to use for steaming. LIVE/BUFFERED/NONE.
[e#05 option    = Enable Log        #init=1         ] Enable logging.

[e#06 option    = Reserved          #init=0         ] Reserved.
[e#07 option    = Reserved          #init=0         ] Reserved.
[e#08 option    = Reserved          #init=0         ] Reserved.
[e#09 option    = Reserved          #init=0         ] Reserved.
[e#10 option    = Reserved          #init=0         ] Reserved.

[e#11 important = URL 1                             ] URL for channel 1.
[e#12 option    = Media Type 1      #init=audio/mp3 ] Media type for channel 1. Usually "audio/mp3".
[e#13 option    = Default Volume 1  #init=30        ] Default volume for channel 1.
[e#14 important = URL 2                             ] URL for channel 2.
[e#15 option    = Media Type 2      #init=audio/mp3 ] Media type for channel 2. Usually "audio/mp3".
[e#16 option    = Default Volume 2  #init=30        ] Default volume for channel 2.
[e#17 important = URL 3                             ] URL for channel 3.
[e#18 option    = Media Type 3      #init=audio/mp3 ] Media type for channel 3. Usually "audio/mp3".
[e#19 option    = Default Volume 3  #init=30        ] Default volume for channel 3.
[e#20 important = URL 4                             ] URL for channel 4.
[e#21 option    = Media Type 4      #init=audio/mp3 ] Media type for channel 4. Usually "audio/mp3".
[e#22 option    = Default Volume 4  #init=30        ] Default volume for channel 4.
[e#23 important = URL 5                             ] URL for channel 5.
[e#24 option    = Media Type 5      #init=audio/mp3 ] Media type for channel 5. Usually "audio/mp3".
[e#25 option    = Default Volume 5  #init=30        ] Default volume for channel 5.

[e#26 option    = Reserved          #init=0         ] Reserved.
[e#27 option    = Reserved          #init=0         ] Reserved.
[e#28 option    = Reserved          #init=0         ] Reserved.
[e#29 option    = Reserved          #init=0         ] Reserved.
[e#30 option    = Reserved          #init=0         ] Reserved.

[e#31 option    = AutoPlay          #init=1         ] Automatically start playback when changing channels.

[e#32 option    = CondPlay_Stopped  #init=1         ] Decides if "Conditional Play" will start playback if nothing is playing.
[e#33 option    = CondPlay_Cloud    #init=0         ] Decides if "Conditional Play" will start playback if the current stream was started from e.g. Google Home.
[e#34 option    = CondPlay_Local    #init=0         ] Decides if "Conditional Play" will start playback if the current stream was started from e.g. this script.

[e#35 option    = CondStop_Stopped  #init=1         ] Decides if "Conditional Stop" will start playback if nothing is playing.
[e#36 option    = CondStop_Cloud    #init=1         ] Decides if "Conditional Stop" will start playback if the current stream was started from e.g. Google Home.
[e#37 option    = CondStop_Local    #init=1         ] Decides if "Conditional Stop" will start playback if the current stream was started from e.g. this script.

[e#38 option    = Reserved          #init=0         ] Reserved.
[e#39 option    = Reserved          #init=0         ] Reserved.

[e#40 trigger   = Select Channel                    ] Channel selection. Depending on "AutoPlay", playback might start / change to the new channel directly.
[e#41 trigger   = Volume                            ] Volume control (absolute value, 0-255).
[e#42 trigger   = Conditional Play                  ] Conditionally start playback of the currently selected "channel", depending on CondPlay_*.
[e#43 trigger   = Play                              ] Unconditionally start playback of the currently selected "channel".
[e#44 trigger   = Conditional Stop                  ] Conditionally stop playback, depending on CondStop_*.
[e#45 trigger   = Stop                              ] Stop playback.
[e#46 trigger   = Get Status                        ] Update status of Chromecast (Volume and Status).


[a#1            = Volume                            ] Current volume.
[a#2            = Status                            ] Current status of chromecast device. 1 = Not available, 2 = Idle, 3 = Playback from "Cloud" (eg. Google Home), 4 = Playback from "local" (eg. this script).
[a#3            = Channel                           ] Currently selected channel.

[v#1        = 0] // EXEC Mode, 1 = Reserved, 2 = Volume, 3 = CondPlay, 4 = Play, 5 = CondStop, 6 = Stop, 7 = Status
[v#2        = 0] // Session ID
[v#3        = 0] // Initialized. Used to prevent initialization of values (channel) to cause play on startup.


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

E11	(URL)	URL für Stream 1 (Bspw. http://beliebigeseite.de/stream.mp3).
E12	(Text)	Der "Media Type" der für Stream 1 verwendet werden soll. In der Regel ist hier "audio/mp3" zu verwenden.
E13	(0-100)	Lautstärke, welche zum Start von Stream 1 gesetzt wird.
E14 		Eingänge 14 bis 25 analog zu 11-13.

E31	(DPT1)	Legt fest, ob die Wiedergabe eines Senders nach dem Senderwechsel sofort gestartet wird.

E32	(DPT1)	Legt fest, ob "Conditional Play" im Zustand "Gestoppt" ausgeführt wird.
E33	(DPT1)	Legt fest, ob "Conditional Play" im Zustand "Von Cloud" ausgeführt wird.
E34	(DPT1)	Legt fest, ob "Conditional Play" im Zustand "Von Lokal" ausgeführt wird.

E35	(DPT1)	Legt fest, ob "Conditional Stop" im Zustand "Gestoppt" ausgeführt wird.
E36	(DPT1)	Legt fest, ob "Conditional Stop" im Zustand "Von Cloud" ausgeführt wird.
E37	(DPT1)	Legt fest, ob "Conditional Stop" im Zustand "Von Lokal" ausgeführt wird.

E40	(DPT17)	Wählt einen Sender aus (1-5).
E41	(DPT5)	Legt die Lautstärke der Wiedergabe fest. Die Lautstärke wird als Absolutwert (0-255) übergeben.
E42	(DPT1)	Startet die Wiedergabe in Abhängigkeit von E32-34.
E43	(DPT1)	Startet die Wiedergabe.
E44	(DPT1)	Beendet die Wiedergabe in Abhängigkeit von E35-37.
E45 (DPT1)	Beendet die Wiedergabe.
E46	(DPT1)	Fragt den aktuellen Status des Chromecast ab (Lautstärke und Status).

A1	(DPT5)	Aktuelle Lautstärke (0-255).
A2	(DPT17)	Aktueller Status der Wiedergabe (1=Nicht Verfügbar, 2=Gestoppt, 3=Von Cloud, 4=Von Lokal).
A3	(DPT17)	Aktuell ausgewählter Sender (nicht unbedingt der aktuell abgespielte Sender).
###[/HELP]### 

###[LBS]###
<?
function LB_LBSID($id) {
    if ($inputs = logic_getInputs($id)) {
		LB_LBSID_writeLog($inputs, $id, 7, 'Started.');

		if (logic_getVar($id, 3) == 0) { //check init var. if it is zero, we've been triggered by the lbs init. Don't react to prevent unwanted effects.
			LB_LBSID_writeLog($inputs, $id, 6, 'Not initialized yet. No Processing.');
			logic_setVar($id, 3, 1);
			return;
		}
		
		if ($inputs[1]['value'] == 0) {
			LB_LBSID_writeLog($inputs, $id, 6, 'Disabled. Stopping.');
			return;
		}

		// check which input triggered the execution.
		// further checks regarding Chromecast status
        // and all the time-consuming http requests in the EXEC.
		
		if ($inputs[40]['refresh'] == 1) { // Channel
		    $channel = $inputs[40]['value'];
			LB_LBSID_writeLog($inputs, $id, 6, 'Setting Channel: ' . $channel);
            logic_setOutput($id, 3, $channel); // set channel to output a3

		    if ($inputs[31]['value'] == 1) { // AutoPlay
				LB_LBSID_writeLog($inputs, $id, 6, 'Trigger: Channel.');
                logic_setVar($id, 1, 4);
                logic_callExec(LBSID,$id);
		    }
		    return;
		}

        if ($inputs[41]['refresh'] == 1) { // Volume
			LB_LBSID_writeLog($inputs, $id, 6, 'Trigger: Volume.');
            logic_setVar($id, 1, 2);
            logic_callExec(LBSID,$id);
            return;
        }
		
		if ($inputs[42]['refresh'] == 1) { // CondPlay
		    if ($inputs[42]['value'] == 1) {
				LB_LBSID_writeLog($inputs, $id, 6, 'Trigger: CondPlay.');
				logic_setVar($id, 1, 3);
				logic_callExec(LBSID, $id);
            }
            return;
		}
		
		if ($inputs[43]['refresh'] == 1) { // Play
		    if ($inputs[43]['value'] == 1) {
				LB_LBSID_writeLog($inputs, $id, 6, 'Trigger: Play.');
				logic_setVar($id, 1, 4);
				logic_callExec(LBSID, $id);
            }
            return;
		}
		
		if ($inputs[44]['refresh'] == 1) { // CondStop
		    if ($inputs[44]['value'] == 1) {
				LB_LBSID_writeLog($inputs, $id, 6, 'Trigger: CondStop.');
                logic_setVar($id, 1, 5);
                logic_callExec(LBSID, $id);
            }
            return;
		}
		
		if ($inputs[45]['refresh'] == 1) { // Stop
            if ($inputs[45]['value'] == 1) {
				LB_LBSID_writeLog($inputs, $id, 6, 'Trigger: Stop.');
                logic_setVar($id, 1, 6);
                logic_callExec(LBSID, $id);
            }
            return;
		}
		
		if ($inputs[46]['refresh'] == 1) { // Status
            if ($inputs[46]['value'] == 1) {
				LB_LBSID_writeLog($inputs, $id, 6, 'Trigger: Status.');
                logic_setVar($id, 1, 7);
                logic_callExec(LBSID, $id);
            }
            return;
		}
	}
	else {
		LB_LBSID_writeLog($inputs, $id, 6, 'Could not retrieve inputs.');
	}
}

function LB_LBSID_writeLog($inputs, $id, $logLevel, $logMessage) {
	if ($inputs[5]['value'] == 1) {
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

$inputs = logic_getInputs($id); 
$vars = logic_getVars($id);

// Get status from device.
writeLog($inputs, $id, 6, 'Requesting Status.');
$status = getChromecastStatus($inputs);

// check which command triggered the execution, act accordingly.
if ($vars[1] == 2) { // Volume
    $volume = ($inputs[41]['value'] / 255);
	writeLog($inputs, $id, 6, 'Setting Volume: ' . $volume);
    $status[0] = setVolume($inputs, $volume);
}


if ($vars[1] == 3) { // CondPlay
	writeLog($inputs, $id, 7, 'CondPlay: Checking status. Current status: ' . $status[1]);

    // check if condition and state match, switch mode to play.
	if ($status[1] == 1 || $status[1] == 2) { // not available, idle
		if ($inputs[32]['value'] == 1) {
			writeLog($inputs, $id, 7, 'CondPlay matches: IDLE');
			$vars[1] = 4;
		}
	}
	else if ($status[1] == 3) { // cloud
		if ($inputs[33]['value'] == 1) {
			writeLog($inputs, $id, 7, 'CondPlay matches: CLOUD');
			$vars[1] = 4;
		}
	}
	else if ($status[1] == 4) { // local
		if ($inputs[34]['value'] == 1) {
			writeLog($inputs, $id, 7, 'CondPlay matches: LOCAL');
			$vars[1] = 4;
		}
	} else {
		writeLog($inputs, $id, 7, 'CondPlay: No match.');
    }
}


if ($vars[1] == 4) { // Play
	// get correct url and media type
    // beware, scene numbers are offset by one.
    // This is handled by the comparison.
	if ($inputs[40]['value'] == 0) {
		$url = $inputs[11]['value'];
		$type = $inputs[12]['value'];
        $volume = $inputs[13]['value'];
	}
	
	if ($inputs[40]['value'] == 1) {
		$url = $inputs[14]['value'];
		$type = $inputs[15]['value'];
        $volume = $inputs[16]['value'];
	}
	
	if ($inputs[40]['value'] == 2) {
		$url = $inputs[17]['value'];
		$type = $inputs[18]['value'];
        $volume = $inputs[19]['value'];
	}
	
	if ($inputs[40]['value'] == 3) {
		$url = $inputs[20]['value'];
		$type = $inputs[21]['value'];
        $volume = $inputs[22]['value'];
	}
	
	if ($inputs[40]['value'] == 4) {
		$url = $inputs[23]['value'];
		$type = $inputs[24]['value'];
        $volume = $inputs[25]['value'];
	}

	if (isset($volume) && isset($type) && isset($url)){
		writeLog($inputs, $id, 6, 'Play: Starting playback. Selected channel: ' . $inputs[40]['value']);
        $status[0] = setVolume($inputs, ($volume/100));
        $status[1] = startPlayback($inputs, $type, $url);
    }else {
		writeLog($inputs, $id, 3, 'Play: Vars not set -> channel out of range?');
    }
}


if ($vars[1] == 5) { // CondStop
	writeLog($inputs, $id, 7, 'CondStop: Checking status. Current status:' . $status[1]);

    // check if condition and state match, switch mode to stop.
	if ($status[1] == 1 || $status[1] == 2) { // not available, idle
		if ($inputs[35]['value'] == 1) {
			writeLog($inputs, $id, 7, 'CondStop matches: IDLE');
			$vars[1] = 6; // switch mode to stop
		}
	}
	else if ($status[1] == 3) { // cloud
		if ($inputs[36]['value'] == 1) {
			writeLog($inputs, $id, 7, 'CondStop matches: CLOUD');
			$vars[1] = 6; // switch mode to stop
		}
	}
	else if ($status[1] == 4) { // local
		if ($inputs[37]['value'] == 1) {
			writeLog($inputs, $id, 7, 'CondStop matches: LOCAL');
			$vars[1] = 6; // switch mode to stop
		}
	} else {
		writeLog($inputs, $id, 7, 'CondStop: No match.');
    }
}


if ($vars[1] == 6) { // Stop
	writeLog($inputs, $id, 6, 'Stop: Sending stop command.');
    if (isset($status[2])){
        $status[1] = stopPlayback($inputs, $status[2]);
    }else {
        $status[1] = startPlayback($inputs, 'null', 'null');
    }

    $status[2] = 0;
}


// update status

logic_setOutput($id, 1, $status[0]); // set volume to output a1
logic_setOutput($id, 2, $status[1]-1); // set status to output a2


// functions

function getChromecastStatus($inputs) {
    $status_raw = file_get_contents('http://' . $inputs[2]['value'] . '/getDeviceStatus?address=' . $inputs[3]['value']);
    $status_content = json_decode($status_raw, true);

    $volume = round($status_content["status"]["volume"]["level"] * 255, 0, PHP_ROUND_HALF_DOWN);

    if (empty($status_content)) {
        $status = 1; // not available
    }
    else if (empty($status_content["status"]["applications"])) {
        $status = 2; // idle
    }
    else if ($status_content["status"]["applications"]["0"]["launchedFromCloud"] == 1) {
        $status = 3; // cloud
        $session = $status_content["status"]["applications"]["0"]["sessionId"];
    }
    else {
        $status = 4; // local
        $session = $status_content["status"]["applications"]["0"]["sessionId"];
    }

    if (isset($session)){
        return array($volume, $status, $session);
    }

    return array($volume, $status, 0);
}

function setVolume($inputs, $volume) {
    $volume_raw = file_get_contents('http://' . $inputs[2]['value'] . '/setDeviceVolume?address=' . $inputs[3]['value'] . '&volume=' . $volume);
    $volume_content = json_decode($volume_raw, true);
    return round($volume_content["status"]["volume"]["level"] * 255, 0, PHP_ROUND_HALF_DOWN);
}

function startPlayback($inputs, $type, $url) {
    if (!isset($type) || !isset($url)){
        return 1;
    }

    $play_raw = file_get_contents('http://' . $inputs[2]['value'] . '/setMediaPlayback?address=' . $inputs[3]['value'] . '&mediaType=' . $type . '&mediaUrl=' . $url . '&mediaStreamType=' . $inputs[4]['value'] . '&mediaTitle=null&mediaSubtitle=null&mediaImageUrl=null');
    $play_content = json_decode($play_raw, true);

    if ($play_content["status"][0]["playerState"] == 'PLAYING'){
        return 4;
    }

    return 1;
}

function stopPlayback($inputs, $sessionId) {
    $stop_raw = file_get_contents('http://' . $inputs[2]['value'] . '/setDevicePlaybackStop?address=' . $inputs[3]['value'] . '&sessionId=' . $sessionId);
    //$stop_content = json_decode($stop_raw, true);

    return 2;
}

function writeLog($inputs, $id, $logLevel, $logMessage) {
	if ($inputs[5]['value'] == 1) {
		writeToCustomLog('ChromecastLBS', $logLevel, '[' . $id . '] ' . $logMessage);
	}
}

function warning_handler($errno, $errstr) {
	writeToCustomLog('ChromecastLBS', 1, '[' . $elementid . '] Error during processing: [' . $errno . '] [' . $errstr . ']');
}

sql_disconnect();
?>
###[/EXEC]###
