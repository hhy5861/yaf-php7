<?php
/**
 * @name IndexController
 * @author Mike
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */

class WeixinController extends \Yaf_Controller_Abstract {

	public function indexAction() {
		$files = '<br/>';
		$failFile = '<br/>';
		$extArr = ['php', 'phtml', 'js', 'css', 'png', 'jpg', 'csv'];
		$name = 'gzpt-' . date('Y-m-d-H-i', time()) . '.zip ./*';
		$dir = '/Volumes/web/weixin/';
		$filePath = '/Volumes/web/php/weixin/';
		$file = '/Volumes/web/php/yaf-php7/doc/weixin.txt';
		$commet = file_get_contents($file);
		$commetArr = array_unique(explode("\n", $commet));
		$shell = '';
		foreach ($commetArr as $v) {
			if (!trim($v)) {
				continue;
			}

			$f = explode('/', $v);
			$num = count($f);
			if ($num > 1) {
				unset($f[$num - 1]);
				$th = implode('/', $f);
				$proDir = $dir . $th . '/';
				$this->mkdir_r($proDir);
				$source = $filePath . $v;
				if (in_array($this->ext($v), $extArr) && file_exists($source)) {
					$files .= $v . '<br/>';
					$shell .= 'sudo cp -f ' . $source . ' ' . $proDir . ' && ';
				} else {
					$failFile .= $source . '<br>';
				}
			} else {
				if (in_array($this->ext($v), $extArr)) {
					$sour = $filePath . $v;
					if (file_exists($sour)) {
						$files .= $v . '<br/>';
						$shell .= 'sudo cp -f ' . $sour . ' ' . $dir . ' && ';
					} else {
						$failFile .= $sour . '<br>';
					}
				} else {
					$files .= $v . '<br/>';
					$shell .= 'sudo cp -rf ' . $filePath . $v . ' ' . $dir . ' && ';
				}
			}
		}

		echo '成功拷贝：' . $files . '<hr>';
		echo '失败文件：' . $failFile . '<hr>';
		$shell .= 'cd ' . $dir . '&& sudo zip -r ' . $name;
		print 'shell命令：<br>' . $shell;
		return false;
	}

	function mkdir_r($dirName, $rights = 0777) {
		$dirs = explode('/', $dirName);
		$dir = '';
		foreach ($dirs as $part) {
			$dir .= $part . '/';
			if (!is_dir($dir) && strlen($dir) > 0) {
				mkdir($dir, $rights, 1);
			}

		}
	}

	function ext($file) {
		return substr(strrchr($file, '.'), 1);
	}

}
