<?php

/**
 * __     __        _         ____ ____    ____  ____  _  __
 * \ \   / /__  ___| |_ __ _ / ___|  _ \  / ___||  _ \| |/ /
 * \ \ / / _ \/ __| __/ _` | |   | |_) | \___ \| | | | ' / 
 *  \ V /  __/\__ \ || (_| | |___|  __/   ___) | |_| | . \ 
 *   \_/ \___||___/\__\__,_|\____|_|     |____/|____/|_|\_\                                                        
 * 
 * VestaCP SDK
 * @package VestaCP SDK
 * @version 1.0
 * @author Neto Melo <neto737@live.com>
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 */
class VestaCPSDK {

    private $returnCode = 'yes';
    private $vstCommand = null;
    private $format = 'json';
    private $engine = null;

    /**
     * Init the class
     * @param string $hostname
     * @param string $username
     * @param string $password
     */
    public function __construct($hostname, $username, $password) {
        $this->hostname = $hostname;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Create a new user account in your VestaCP server
     * @param string $username
     * @param string $password
     * @param string $email
     * @param string $package
     * @param string $firstName
     * @param string $lastName
     * @return string
     */
    public function createUserAccount($username, $password, $email, $package, $firstName, $lastName) {
        $this->vstCommand = 'v-add-user';

        $postvars = [
            'user' => $this->username,
            'password' => $this->password,
            'returncode' => $this->returnCode,
            'cmd' => $this->vstCommand,
            'arg1' => $username,
            'arg2' => $password,
            'arg3' => $email,
            'arg4' => $package,
            'arg5' => $firstName,
            'arg6' => $lastName
        ];

        $this->engine = $this->execute($postvars);

        if ($this->engine == 0) {
            return 'User account has been successfuly created\n';
        } else {
            return 'Query returned error code: ' . $this->engine . '\n';
        }
    }

    /**
     * Add a new domain in your VestaCP server
     * @param string $username
     * @param string $domain
     * @return string
     */
    public function addDomain($username, $domain) {
        $this->vstCommand = 'v-add-domain';

        $postvars = [
            'user' => $this->username,
            'password' => $this->password,
            'returncode' => $this->returnCode,
            'cmd' => $this->vstCommand,
            'arg1' => $username,
            'arg2' => $domain
        ];

        $this->engine = $this->execute($postvars);

        if ($this->engine == 0) {
            return 'Domain has been successfuly created\n';
        } else {
            return 'Query returned error code: ' . $this->engine . '\n';
        }
    }

    /**
     * Create a new database in your VestaCP server
     * @param string $username
     * @param string $db_name
     * @param string $db_user
     * @param string $db_pass
     * @return string
     */
    public function createDatabase($username, $db_name, $db_user, $db_pass) {
        $this->vstCommand = 'v-add-database';

        $postvars = [
            'user' => $this->username,
            'password' => $this->password,
            'returncode' => $this->returnCode,
            'cmd' => $this->vstCommand,
            'arg1' => $username,
            'arg2' => $db_name,
            'arg3' => $db_user,
            'arg4' => $db_pass
        ];

        $this->engine = $this->execute($postvars);

        if ($this->engine == 0) {
            return 'Database has been successfuly created\n';
        } else {
            return 'Query returned error code: ' . $this->engine . '\n';
        }
    }

    /**
     * List user account
     * @param string $username
     * @return mixed
     */
    public function listUserAccount($username) {
        $this->vstCommand = 'v-list-user';

        $postvars = [
            'user' => $this->username,
            'password' => $this->password,
            'cmd' => $this->vstCommand,
            'arg1' => $username,
            'arg2' => $this->format
        ];

        $this->engine = $this->execute($postvars);

        return json_decode($this->engine, true);
    }

    /**
     * List all the web domains for an user
     * @param string $username
     * @param string $domain
     * @return mixed
     */
    public function listWebDomains($username, $domain) {
        $this->vstCommand = 'v-list-web-domain';

        $postvars = [
            'user' => $this->username,
            'password' => $this->password,
            'cmd' => $this->vstCommand,
            'arg1' => $username,
            'arg2' => $domain,
            'arg3' => $this->format
        ];

        $this->engine = $this->execute($postvars);

        return json_decode($this->engine, true);
    }
    
    /**
     * Delete an user account from your VestaCP server
     * @param string $username
     * @return string
     */
    public function deleteUserAccount($username) {
        $this->vstCommand = 'v-delete-user';

        $postvars = [
            'user' => $this->username,
            'password' => $this->password,
            'returncode' => $this->returnCode,
            'cmd' => $this->vstCommand,
            'arg1' => $username
        ];

        $this->engine = $this->execute($postvars);

        if ($this->engine == 0) {
            return 'User account has been successfuly deleted\n';
        } else {
            return 'Query returned error code: ' . $this->engine . '\n';
        }
    }

    /**
     * Verify if username and password is correct or incorrect
     * @param string $username
     * @param string $password
     * @return string
     */
    public function checkUsernameAndPassword($username, $password) {
        $this->vstCommand = 'v-check-user-password';

        $postvars = [
            'user' => $this->username,
            'password' => $this->password,
            'returncode' => $this->returnCode,
            'cmd' => $this->vstCommand,
            'arg1' => $username,
            'arg2' => $password
        ];

        $this->engine = $this->execute($postvars);

        if ($this->engine == 0) {
            return 'OK: User can login\n';
        } else {
            return 'Error: Username or password is incorrect\n';
        }
    }

    /**
     * This function is responsible for performing all other functions
     * @param mixed array $postvars
     * @return mixed
     */
    private function execute($postvars) {
        $postdata = http_build_query($postvars);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://' . $this->hostname . ':8083/api/');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
        return curl_exec($curl);
    }

}
