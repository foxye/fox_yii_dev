<?php

/**
 * This is the model class for table "tbl_users".
 *
 * The followings are the available columns in table 'tbl_users':
 * @property string $ID
 * @property string $user_login
 * @property string $user_pass
 * @property string $user_nicename
 * @property string $user_email
 * @property string $user_url
 * @property string $user_registered
 * @property string $user_activation_key
 * @property integer $user_status
 * @property string $display_name
 */
class User extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	
	var $itoa64;
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_status', 'numerical', 'integerOnly'=>true),
			array('user_login, user_activation_key', 'length', 'max'=>60),
			array('user_pass', 'length', 'max'=>64),
			array('user_nicename', 'length', 'max'=>50),
			array('user_email, user_url', 'length', 'max'=>100),
			array('display_name', 'length', 'max'=>250),
			array('user_registered', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, user_login, user_pass, user_nicename, user_email, user_url, user_registered, user_activation_key, user_status, display_name', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'user_login' => 'User Login',
			'user_pass' => 'User Pass',
			'user_nicename' => 'User Nicename',
			'user_email' => 'User Email',
			'user_url' => 'User Url',
			'user_registered' => 'User Registered',
			'user_activation_key' => 'User Activation Key',
			'user_status' => 'User Status',
			'display_name' => 'Display Name',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('ID',$this->ID,true);
		$criteria->compare('user_login',$this->user_login,true);
		$criteria->compare('user_pass',$this->user_pass,true);
		$criteria->compare('user_nicename',$this->user_nicename,true);
		$criteria->compare('user_email',$this->user_email,true);
		$criteria->compare('user_url',$this->user_url,true);
		$criteria->compare('user_registered',$this->user_registered,true);
		$criteria->compare('user_activation_key',$this->user_activation_key,true);
		$criteria->compare('user_status',$this->user_status);
		$criteria->compare('display_name',$this->display_name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	
	public function checkpassword($password, $stored_hash)
	{
		$hash = $this->crypt_private($password, $stored_hash);
		if ($hash[0] == '*')
			$hash = crypt($password, $stored_hash);
	
		return $hash == $stored_hash;
	}
	
	public function crypt_private($password, $setting)
	{	
		$this->itoa64 = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
		$output = '*0';
		if (substr($setting, 0, 2) == $output)
			$output = '*1';
	
		$id = substr($setting, 0, 3);
		# We use "$P$", phpBB3 uses "$H$" for the same thing
		if ($id != '$P$' && $id != '$H$')
			return $output;
	
		$count_log2 = strpos($this->itoa64, $setting[3]);
		if ($count_log2 < 7 || $count_log2 > 30)
			return $output;
	
		$count = 1 << $count_log2;
	
		$salt = substr($setting, 4, 8);
		if (strlen($salt) != 8)
			return $output;
	
		# We're kind of forced to use MD5 here since it's the only
		# cryptographic primitive available in all versions of PHP
		# currently in use.  To implement our own low-level crypto
		# in PHP would result in much worse performance and
		# consequently in lower iteration counts and hashes that are
		# quicker to crack (by non-PHP code).
		if (PHP_VERSION >= '5') {
			$hash = md5($salt . $password, TRUE);
			do {
				$hash = md5($hash . $password, TRUE);
			} while (--$count);
		} else {
			$hash = pack('H*', md5($salt . $password));
			do {
				$hash = pack('H*', md5($hash . $password));
			} while (--$count);
		}
	
		$output = substr($setting, 0, 12);
		$output .= $this->encode64($hash, 16);
	
		return $output;
	}
	
	function encode64($input, $count)
	{
		$output = '';
		$i = 0;
		do {
			$value = ord($input[$i++]);
			$output .= $this->itoa64[$value & 0x3f];
			if ($i < $count)
				$value |= ord($input[$i]) << 8;
			$output .= $this->itoa64[($value >> 6) & 0x3f];
			if ($i++ >= $count)
				break;
			if ($i < $count)
				$value |= ord($input[$i]) << 16;
			$output .= $this->itoa64[($value >> 12) & 0x3f];
			if ($i++ >= $count)
				break;
			$output .= $this->itoa64[($value >> 18) & 0x3f];
		} while ($i < $count);
	
		return $output;
	}
}