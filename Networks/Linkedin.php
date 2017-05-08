<?php

namespace SocialAuth\Networks;

use Happyr\LinkedIn\LinkedIn;

/**
 * This class is used to encapsulate the calls for each specific social network 
 */
class Linkedin extends Base{

	/**
	 *
	 * @var LinkedIn
	 */
	protected $linkedinApi;
	protected $accessToken;
	
	/**
	 *
	 * @param array $configs - ['key' => '', 'secret' => '']
	 * @throws \Exception
	 */
	public function __construct(array $configs){
		if(!isset($configs['key']) || !isset($configs['secret'])){
			throw new \Exception('The configuration array does not contain the element(s) "key" and/or "secret"');
		}
	
		$this->linkedinApi = new LinkedIn($configs['key'], $configs['secret']);
	}
	
	public function getSocialLoginUrl(string $redirectUrl){
		return $this->linkedinApi->getLoginUrl(['redirect_uri'=>$redirectUrl]);
	}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see \SocialAuth\Networks\Base::login()
	 */
	public function login(){
		$this->linkedinApi->isAuthenticated();
	}
	
	public function getProfile(){
		$user = $this->linkedinApi->get('v1/people/~:(id,first-name,last-name,picture-url,public-profile-url,email-address)');

		$data = ['social_id'=>$user['id']];
		
		if(isset($user['firstName'])){
			$data['full_name'] = $user['firstName'];
			if(isset($user['lastName'])){
				$data['full_name'] .= ' '.$user['lastName'];
			}
		}
		
		if(isset($user['emailAddress'])) $data['email'] = $user['emailAddress'];
		if(isset($user['pictureUrl']))	 $data['picture']= $user['pictureUrl'];
		
		return $data;
	}
	
}