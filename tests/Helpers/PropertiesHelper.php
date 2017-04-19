<?php

use CoRex\Support\Obj;

class PropertiesHelper
{
    /**
     * Get client properties.
     *
     * @param object $client
     * @param string $className
     * @return array
     */
    public static function getClientProperties($client, $className)
    {
        return Obj::getPropertiesFromObject(Obj::PROPERTY_PRIVATE, $client, $className);
    }

    /**
     * Get client connector.
     *
     * @param object $client
     * @param string $className
     * @return object
     */
    public static function getClientConnector($client, $className)
    {
        $properties = self::getClientProperties($client, $className);
        if (isset($properties['connector'])) {
            return $properties['connector'];
        }
        return null;
    }

    /**
     * Get connector properties.
     *
     * @param object $connector
     * @return array
     */
    public static function getConnectorProperties($connector)
    {
        return Obj::getPropertiesFromObject(Obj::PROPERTY_PROTECTED, $connector);
    }

    /**
     * Get connector request.
     *
     * @param object $connector
     * @return \CoRex\Client\Base\ConnectorRequest
     */
    public static function getConnectorRequest($connector)
    {
        $properties = self::getConnectorProperties($connector);
        if ($properties['request']) {
            return $properties['request'];
        }
        return null;
    }
}