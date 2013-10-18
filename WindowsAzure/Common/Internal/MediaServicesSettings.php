<?php

/**
 * LICENSE: Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * 
 * PHP version 5
 *
 * @category  Microsoft
 * @package   WindowsAzure\Common\Internal
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/windowsazure/azure-sdk-for-php
 */
 
namespace WindowsAzure\Common\Internal;
use WindowsAzure\Common\Internal\Resources;

/**
 * Represents the settings used to sign and access a request against the service 
 * management. For more information about service management connection strings check
 * this page: http://msdn.microsoft.com/en-us/library/windowsazure/gg466228.aspx
 *
 * @category  Microsoft
 * @package   WindowsAzure\Common\Internal
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @version   Release: 0.3.1_2011-08
 * @link      https://github.com/windowsazure/azure-sdk-for-php
 */
class ServiceManagementSettings extends ServiceSettings
{
    /**
     * @var string
     */
    private $_accountName;
    
    /**
     * @var string
     */
    private $_primaryAccessKey;
    
    /**
     * @var string
     */
    private $_secondaryAccessKey;
    
    /**
     * @var string
     */
    private $_endpointUri;
    
    /**
     * Validator for the MediaServicesAccountName setting. It has to be provided.
     * 
     * @var array
     */
    private static $_accountNameSetting;
    
    /**
     * Validator for the MediaServicesPrimaryAccessKey setting. It has to be provided.
     * 
     * @var array
     */
    private static $_primaryAccessKeySetting;
    
    /**
     * Validator for the MediaServicesSecondaryAccessKey setting. It has to be provided.
     * 
     * @var array
     */
    private static $_secondaryAccessKeySetting;
    
    /**
     * Validator for the MediaServicesEndpoint setting. Must be a valid Uri.
     * 
     * @var array
     */
    private static $_endpointUriSetting;
    
    /**
     * @var boolean
     */
    protected static $isInitialized = false;
    
    /**
     * Holds the expected setting keys.
     * 
     * @var array
     */
    protected static $validSettingKeys = array();
    
    /**
     * Initializes static members of the class.
     * 
     * @return none
     */
    protected static function init()
    {
        self::$_endpointUriSetting = self::settingWithFunc(
            Resources::MEDIA_SERVICES_ENDPOINT_URI_NAME,
            Validate::getIsValidUri()
        );
        
        self::$_accountNameSetting = self::setting(
            Resources::MEDIA_SERVICES_ACCOUNT_NAME
        );
        
        self::$_primaryAccessKeySetting = self::setting(
            Resources::MEDIA_SERVICES_PRIMARY_ACCESS_KEY
        );
        
        self::$_secondaryAccessKeySetting = self::setting(
            Resources::MEDIA_SERVICES_SECONDARY_ACCESS_KEY
        );
        
        self::$validSettingKeys[] = Resources::MEDIA_SERVICES_ENDPOINT_URI_NAME;
        self::$validSettingKeys[] = Resources::MEDIA_SERVICES_ACCOUNT_NAME;
        self::$validSettingKeys[] = Resources::MEDIA_SERVICES_PRIMARY_ACCESS_KEY;
        self::$validSettingKeys[] = Resources::MEDIA_SERVICES_SECONDARY_ACCESS_KEY;
    }
    
    /**
     * Creates new media services settings instance.
     * 
     * @param string $accountName         The user provided account name.
     * @param string $primaryAccessKey    The user provided primary access key
     * @param string $secondaryAccessKey  The user provided secondary access key 
     * @param string $endpointUri         The service management endpoint uri.
     */
    public function __construct($accountName, $primaryAccessKey, $secondaryAccessKey, $endpointUri)
    {
        $this->_accountName = $accountName;
        $this->_primaryAccessKey = $primaryAccessKey;
        $this->_secondaryAccessKey  = $secondaryAccessKey;
        $this->_endpointUri  = $endpointUri;
    }
    
    /**
     * Creates a MediaServicesSettings object from the given connection string.
     * 
     * @param string $connectionString The media services settings connection string.
     * 
     * @return MediaServicesSettings
     */
    public static function createFromConnectionString($connectionString)
    {
        $tokenizedSettings = self::parseAndValidateKeys($connectionString);
        
        $matchedSpecs = self::matchedSpecification(
            $tokenizedSettings,
            self::allRequired(
                self::$_accountNameSetting,
                self::$_primaryAccessKeySetting,
            	self::$_secondaryAccessKeySetting
            ),
            self::optional(
                self::$_endpointUriSetting
            )
        );
        if ($matchedSpecs) {
            $endpointUri     = Utilities::tryGetValueInsensitive(
                Resources::MEDIA_SERVICES_ENDPOINT_URI_NAME,
                $tokenizedSettings,
                Resources::MEDIA_SERVICES_URL
            );
            $accountName  = Utilities::tryGetValueInsensitive(
                Resources::MEDIA_SERVICES_ACCOUNT_NAME,
                $tokenizedSettings
            );
            $primaryAccessKey = Utilities::tryGetValueInsensitive(
                Resources::MEDIA_SERVICES_PRIMARY_ACCESS_KEY,
                $tokenizedSettings
            );
            $secondaryAccessKey = Utilities::tryGetValueInsensitive(
           		Resources::MEDIA_SERVICES_SECONDARY_ACCESS_KEY,
           		$tokenizedSettings
            );
            
            return new MediaServicesSettings(
                $accountName,
                $primaryAccessKey,
                $secondaryAccessKey,
            	$endpointUri
            );
        }
        
        self::noMatch($connectionString);
    }
    
    /**
     * Gets media services account name.
     * 
     * @return string
     */
    public function getAccountName()
    {
        return $this->_accountName;
    }
    
    /**
     * Gets media services primary access key.
     * 
     * @return string
     */
    public function getPrimaryAccessKey()
    {
        return $this->_primaryAccessKey;
    }
    
    /**
     * Gets media services secondary access key.
     * 
     * @return string
     */
    public function getSecondaryAccessKey()
    {
        return $this->_secondaryAccessKey;
    }
    
    /**
     * Gets media services endpoint uri.
     * 
     * @return string 
     */
    public function getEndpointUri()
    {
        return $this->_endpointUri;
    }
}


