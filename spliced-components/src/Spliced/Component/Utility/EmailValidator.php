<?php
/*
 * This file is part of the Spliced Component package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Component\Utility;

/**
 * EmailValidator
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com> [Moved to PHP 5.3+, Moved to OOP, Altered Code]
 * @author http://www.tienhuis.nl/php-email-address-validation-with-verify-probe
 */
class EmailValidator{
    
    protected $smtpTimeout = 60;
    
    protected $tcpConnectTimeout = 20;
    
    private $emailRegexp = '/^([a-zA-Z0-9\'\._\+-]+)\@((\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,7}|[0-9]{1,3})(\]?))$/';
    
    protected $lastError = null;

    protected $debug = false;
    
    protected $debugMessages = array();
    
    public function __construct($debug = false, $smtpTimeout = 60, $tcpConnectTimeout = 30){
        #$this->debug = $debug !== false || $debug !== true ? false : $debug;
        $this->debug = $debug;
        $this->tcpConnectTimeout = $tcpConnectTimeout;
        $this->smtpTimeout = $smtpTimeout;
    }
    
    /**
     * validate
     * 
     * @param string $emailAddress
     * @return bool
     */
    public function validate($emailAddress, $heloAddress, $fromAddress){
        
        if(!function_exists('checkdnsrr')){
            throw new \Exception('Unix Systems Only; you appear to be on Windows');
        }
        
        if(! $this->isValidSyntax($emailAddress)){
            $this->setLastError('Email Address Did Not Pass Regexp Validation');
            return false;
        }
        
        $emailParts = explode('@',$emailAddress);
        
        # Construct array of available mailservers
        if(getmxrr($emailParts[1], $mxHosts, $mxWeight)) {
            for($i=0;$i<count($mxHosts);$i++){
                $mxs[$mxHosts[$i]] = $mxWeight[$i];
            }
            asort($mxs);
            $mailers = array_keys($mxs);
        } elseif(checkdnsrr($emailParts[1], 'A')) {
            $mailers[0] = gethostbyname($emailParts[1]);
        } else {
            $this->setLastError(sprintf('Unable to Obtain MX Information from Host %s',$emailParts[1]));
            return false;
        }
        
        foreach($mailers as $mailer){

            if($this->debug){
                $this->addDebugMessage(sprintf('Starting Mail Server %s',$mailer));
            }
            
            $errorNumber = null;
            $errorString = null;
            if($sock = @fsockopen($mailer, 25, $errorNumber , $errorString, $this->tcpConnectTimeout)) {
                $response = fread($sock,8192);
                
                if($this->debug){
                    $this->addDebugMessage(sprintf('Recieved Response: %s', $response));
                }
                
                stream_set_timeout($sock, $this->smtpTimeout);
                
                $meta = stream_get_meta_data($sock);

                if(!$meta['timed_out'] && !preg_match('/^2\d\d[ -]/', $response)) {
                    $this->setLastError(sprintf('Error: %s said: %s',$mailer,$response));
                    return false;
                }
                
                $cmds = array(
                    "HELO $heloAddress",
                    "MAIL FROM: <$fromAddress>",
                    "RCPT TO:<$emailAddress>",
                    "QUIT",
                );
                
                $validEmail = false;
                
                foreach($cmds as $cmd) {
                    if($this->debug){
                        $this->addDebugMessage(sprintf('Running Command %s',$cmd));
                    }
                    
                    fputs($sock, "$cmd\r\n");
                    $response = fread($sock, 4096);
                    
                    if($this->debug){
                        $this->addDebugMessage(sprintf('Recieved Response: %s From Command: %s', $response, $cmd));
                    }

                    if(!$meta['timed_out'] && preg_match('/^5\d\d[ -]/', $response)) {
                        $errorMsg = sprintf('Unverified address: %s said: %s',$mailer,$response);
                        $this->addDebugMessage($errorMsg)
                          ->setLastError($errorMsg);
                        if(preg_match('/^RCPT/',$cmd)){ // recieved invalid mailbox or other error
                            fputs($sock, "QUIT\r\n");
                            break;
                        }
                    } else if(preg_match('/^RCPT/',$cmd)){ // recieved a valid email response, we can quit now
                        fputs($sock, "QUIT\r\n"); 
                        $validEmail = true; 
                        break;
                    }
                }
                
                fclose($sock);
                
                return $validEmail;
            }
        }
        
        return false;
    }
    
    /**
     * isValidSyntax
     * Checks to see if the email address supplied is a valid email address via REGEXP
     * @return bool
     */
    private function isValidSyntax($email){
        return preg_match($this->emailRegexp, $email);
    }
    
    /**
     * setLastError
     * 
     * Sets the last error the operation encountered
     * 
     * @param string $message
     * @return EmailValidator
     */
    public function setLastError($message){
        $this->lastError = $message;
        return $this;
    }
    
    /**
     * getLastError
     * 
     * Returns the last error the operation encountered
     */
    public function getLastError(){
        return $this->lastError;
    }
    
    /**
     * addDebugMessage
     * Add a Debug Message to Stack
     * 
     * @param string $message
     * @return EmailValidator
     */
    public function addDebugMessage($message){
        array_push($this->debugMessages, $message);
        return $this;
    }
    
    /**
     * getDebugMessages
     * 
     * @return array
     */
    public function getDebugMessages(){
        return $this->debugMessages;
    }
}
    