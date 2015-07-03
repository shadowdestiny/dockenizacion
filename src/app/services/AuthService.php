<?php
//namespace EuroMillions\services;
//
//class AuthService
//{
//    const REMEMBER_ME_EXPIRATION = 691200; //(86400 * 8) = 8 days
//    const SESSION_VAR_NAME = 'auth-identity';
//    /** @var  \Phalcon\Security */
//    protected $security;
//    /** @var  \Phalcon\Session\Adapter */
//    protected $session;
//    /** @var  \Phalcon\Http\Request */
//    protected $request;
//    /** @var  \Phalcon\Http\Response\Cookies */
//    protected $cookies;
//    /** @var  \Phalcon\Http\Response */
//    protected $response;
//    public function __construct()
//    {
//        $di = DI::getDefault();
//        /** @var DaoFactory $dao_factory */
//        $dao_factory = $di->get('daoFactory');
//        $this->usersQueryDAO = $dao_factory->getUsersQuery();
//        $this->rememberTokensCommandDAO = $dao_factory->getRememberTokensCommand();
//        $this->rememberTokensQueryDAO = $dao_factory->getRememberTokensQuery();
//        $this->security = $di->get('security');
//        $this->session = $di->get('session');
//        $this->request = $di->get('request');
//        $this->cookies = $di->get('cookies');
//        $this->response = $di->get('response');
//    }
//    /**
//     * Checks the user credentials
//     * @param $credentials
//     * @return bool
//     */
//    public function check($credentials)
//    {
//        $user = $this->usersQueryDAO->getByUsername($credentials['username']);
//        if (!$user) {
//            return false;
//        }
//        if (!$this->security->checkHash($credentials['password'], $user->password)) {
//            return false;
//        }
//        if (isset($credentials['remember'])) {
//            $this->createRememberEnviroment($user);
//        }
//        $this->session->set(self::SESSION_VAR_NAME, array(
//            'id'   => $user->id,
//            'name' => $user->username,
//        ));
//        return true;
//    }
//    /**
//     * Creates the remember me environment settings the related cookies and generating tokens
//     * @param User $user
//     */
//    public function createRememberEnviroment(User $user)
//    {
//        $user_agent = $this->request->getUserAgent();
//        $token = $this->getToken($user, $user_agent);
//        $remember = new RememberToken();
//        $remember->user_id = $user->id;
//        $remember->token = $token;
//        $remember->user_agent = $user_agent;
//        if ($this->rememberTokensCommandDAO->save($remember)) {
//            $expire = time() + self::REMEMBER_ME_EXPIRATION;
//            $this->cookies->set('RMU', $user->id, $expire);
//            $this->cookies->set('RMT', $token, $expire);
//        }
//    }
//    /**
//     * Check if the session has a remember me cookie
//     *
//     * @return boolean
//     */
//    public function hasRememberMe()
//    {
//        return $this->cookies->has('RMU');
//    }
//    /**
//     * Logs on using the information in the coookies
//     */
//    public function loginWithRememberMe($today = null)
//    {
//        $now = $today ? strtotime($today) : time();
//        $user_id = $this->cookies->get('RMU')->getValue();
//        $cookieToken = $this->cookies->get('RMT')->getValue();
//        $user = $this->usersQueryDAO->getById($user_id);
//        if ($user) {
//            $user_agent = $this->request->getUserAgent();
//            $token = $this->getToken($user, $user_agent);
//            if ($cookieToken == $token) {
//                $remember = $this->rememberTokensQueryDAO->getByIdAndToken($user->id, $token);
//                if ($remember) {
//                    // Check if the cookie has not expired
//                    if (($now - self::REMEMBER_ME_EXPIRATION) < $remember->created) {
//                        $this->session->set(self::SESSION_VAR_NAME, array(
//                            'id'   => $user->id,
//                            'name' => $user->username,
//                        ));
//                        return true;
//                    }
//                }
//            }
//        }
//        $this->cookies->get('RMU')->delete();
//        $this->cookies->get('RMT')->delete();
//        return false;
//    }
//    /**
//     * Returns the current identity
//     *
//     * @return array
//     */
//    public function getIdentity()
//    {
//        return $this->session->get(self::SESSION_VAR_NAME);
//    }
//    /**
//     * Returns the current identity
//     *
//     * @return string
//     */
//    public function getName()
//    {
//        $identity = $this->session->get(self::SESSION_VAR_NAME);
//        return $identity['name'];
//    }
//    /**
//     * Removes the user identity information from session
//     */
//    public function remove()
//    {
//        if ($this->cookies->has('RMU')) {
//            $this->cookies->get('RMU')->delete();
//        }
//        if ($this->cookies->has('RMT')) {
//            $this->cookies->get('RMT')->delete();
//        }
//        $this->session->remove(self::SESSION_VAR_NAME);
//    }
//    /**
//     * Auths the user by his/her id
//     *
//     * @param int $id
//     */
//    public function authUserById($id)
//    {
//        $user = $this->usersQueryDAO->getById($id);
//        $this->session->set(self::SESSION_VAR_NAME, array(
//            'id'   => $user->id,
//            'name' => $user->name,
//        ));
//    }
//    /**
//     * Get the entity related to user in the active identity
//     * @return User
//     */
//    public function getUser()
//    {
//        $identity = $this->session->get(self::SESSION_VAR_NAME);
//        if (isset($identity['id'])) {
//            $user = $this->usersQueryDAO->getById($identity['id']);
//            return $user;
//        }
//        return false;
//    }
//    /**
//     * @param User $user
//     * @param $user_agent
//     * @return string
//     */
//    private function getToken(User $user, $user_agent)
//    {
//        $token = md5($user->username . $user->password . $user_agent);
//        return $token;
//    }
//}
//}