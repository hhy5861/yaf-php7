<?php
namespace app\business\func;

class IdVerify implements IIdVerify
{
	private static $instance;

	/**
	 * 防止创建对象
	 */
	private function __construct()
    {
        return $this;
    }

	/**
	 * 单例构造方法
	 * @return IdVerify
	 */
	public static function getInstance()
	{
		if(!self::$instance)
		{
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * 阻止用户复制对象实例
	 */
	private function __clone()
	{
		trigger_error('Clone is not allow' ,E_USER_ERROR);
	}

	public function validationCardId(string $cardId) : bool
	{
		$strlen = strlen($cardId);

		switch($strlen)
		{
			case 15:
				$cardId = $this->idcard15tTo18($cardId);
				$status = $this->idcardChecksum18($cardId);
				break;

			case 18:
				$status = $this->idcardChecksum18($cardId);
				break;

			default:
				$status = false;
				break;
		}

		return $status;
	}

    /**
     * 计算身份证校验码，根据国家标准GB 11643-1999
     *
     * @param string|string $idCardBase
     * @return bool
     */
	private function idcardVerifyNumber(string $idCardBase): string
	{
		if(($len = strlen($idCardBase)) !== 17 || !is_numeric($idCardBase))
		{
			return false;
		}

		//加权因子
		$factor = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];

		//校验码对应值
		$verifyNumberList = ['1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'];
		$checksum = 0;
		for($i = 0; $i < $len; $i++)
		{
            $checksum += substr($idCardBase, $i, 1) * $factor[$i];
		}

		$mod = $checksum % 11;
		return $verifyNumberList[$mod];
	}

	/**
	 * 将15位身份证升级到18位
	 *
	 * @param $idcard
	 * @return bool|string
	 */
	private function idcard15tTo18(string $idcard): string
	{
		if(strlen($idcard) != 15)
		{
			return false;
		}
		else
		{
			/*如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码*/
			if(array_search(substr($idcard, 12, 3), ['996', '997', '998', '999']) !== false)
			{
				$idcard = substr($idcard, 0, 6) . '18' . substr($idcard, 6, 9);
			}
			else
			{
				$idcard = substr($idcard, 0, 6) . '19' . substr($idcard, 6, 9);
			}
		}

		return $idcard . $this->idcardVerifyNumber($idcard);
	}

	/**
	 * 18位身份证校验码有效性检查
	 *
	 * @param $idcard
	 * @return bool
	 */
	private function idcardChecksum18(string $idCard) : bool
	{
		if(strlen($idCard) !== 18)
		{
			return false;
		}

		$idCardBase = substr($idCard, 0, 17);
		if ($this->idcardVerifyNumber($idCardBase) !== strtoupper(substr($idCard, 17, 1)))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
}
