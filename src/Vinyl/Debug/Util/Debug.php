<?php

namespace Vinyl\Debug\Util;

use Silex\Application;
use Monolog\Logger;
use Doctrine\DBAL\Connection;

class Debug
{
	public static $DUMP_MAX_WIDTH = 50;
	public static $DUMP_MAX_DEPTH = 15;

	public static function dump($var, $echo = true, $fontsize= '9pt', $showLocation = true) {
		$id = uniqid("dump-");
		$ret = '<div class="dump" id="' . $id . '" style="color:white; font-size: '.$fontsize.'; font-family: monospace;background: #222;
padding: 5px;
margin-top: 5px;
white-space:nowrap;
border-radius: 7px;">' .
			Debug::_dump($var) .
			"</div>";
		echo $ret;
	}

	private static function _dump($var, $depth = 1) {
		if (is_null($var)) {
			return '<b>null</b>';
		}
		elseif (is_object($var) || is_array($var)) {
			$ret = is_object($var) ? "<b>object</b>(<i>" . get_class($var) . "</i>)" : "<b>array</b>(" . (count($var) == 0 ? 'empty' : count($var)) . ")";
			$vars = is_object($var) ? get_object_vars($var) : $var;
			if (count($vars) > 0) {
				$ret .= "\n<ul style=\"list-style-type: none; padding-left: 20px; margin: 0px\">\n";
				$i = 0;
				foreach ($vars as $k => $v) {
					if ($i++ > Debug::$DUMP_MAX_WIDTH) {
						$ret .= "	<li>...</li>";
						break;
					}
					$kh = is_int($k) ? $k : '"' . htmlspecialchars($k) . '"';
					$ret .= "	<li>" . $kh . " <font color=\"#888a85\">=></font> " . ($depth > Debug::$DUMP_MAX_DEPTH ? '...' : '<span style="position: relative; left: 50px; margin-left: -50px;">' . Debug::_dump($v, $depth + 1) . '</span>') . "</li>\n";
				}
				$ret .= "</ul>\n";
			}
			return $ret;
		}
		elseif (is_float($var)) {
			return '<i>(float)</i> <font color="green">' . $var . "</font>";
		}
		elseif (is_int($var)) {
			return '<i>(int)</i> <font color="green">' . $var . "</font>";
		}
		elseif (is_string($var)) {
			if (strlen($var) > 1000) {
				$var = substr($var, 0, 1000) . "... (truncated)";
			}
			return '<span style="color:#cc0000; white-space:pre">"' . htmlspecialchars($var) . '"</span>';
		}
		elseif (is_bool($var)) {
			return '<font color="blue"><b>' . ($var ? 'true' : 'false') . '</b></font>';
		}
		else {
			return $var; //WTF??
		}
	}

    public static function log(Application $app, $message, $level = Logger::DEBUG) {
    	if (!$app instanceof Application || !$message) {
    		return false;
    	}

    	if (!in_array($level, Logger::getLevels())) {
    		self::error("Invalid level for logger ($level)", Logger::ERROR);
    	}
    	else {
    		$record['channel'] = 'db';
    		$record['level'] = $level;
    		$record['message'] = $message;
    		$record['time'] = time();

    		switch ($level) {
				case LOGGER::INFO:
    				$app['monolog']->addInfo($message);
				break;
				case LOGGER::NOTICE:
    				$app['monolog']->addNotice($message);
				break;
				case LOGGER::WARNING:
    				$app['monolog']->addWarning($message);
				break;
				case LOGGER::ERROR:
    				$app['monolog']->addError($message);
				break;
				case LOGGER::CRITICAL:
    				$app['monolog']->addCritical($message);
				break;
				case LOGGER::ALERT:
    				$app['monolog']->addAlert($message);
				break;
				case LOGGER::EMERGENY:
    				$app['monolog']->addEmergency($message);
				break;

				default:
					$app['monolog']->addDebug($message);
				break;
    		}

    		$app['db']->insert('logging', $record);
    	}
    }
}