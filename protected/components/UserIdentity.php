<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		$user = User::model()->find('email = :e OR login= :e', array(':e' => $this->username));

		if ( !$user ) {
			$this->errorCode = self::ERROR_USERNAME_INVALID;
		} else {
			$p = Security::cryptPassword($this->password, '1');
			if ( $p != $user->password ) {
				$this->errorCode = self::ERROR_PASSWORD_INVALID;
			} else {
				//				if($user->active) {
				//
				//				} else {
				$this->errorCode = self::ERROR_NONE;
				//				}
			}
		}

		return !$this->errorCode;
	}
}