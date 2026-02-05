<?php

namespace App\Services\MessageQueue;

/**
 * Simple inter-service messenger for microservice-to-microservice communication
 *
 * This allows services to call other services while maintaining clear boundaries
 * and making it easy to migrate to RabbitMQ/HTTP in the future
 */
class ServiceMessenger
{
    private static array $services = [];

    /**
     * Register a service handler
     */
    public static function register(string $serviceName, callable $handler): void
    {
        self::$services[$serviceName] = $handler;
    }

    /**
     * Send a message to another service
     *
     * @param string $serviceName The target service name
     * @param string $action The action to perform
     * @param array $params Parameters for the action
     * @return mixed The result from the service
     * @throws \Exception If service not found or action fails
     */
    public static function send(string $serviceName, string $action, array $params = []): mixed
    {
        if (!isset(self::$services[$serviceName])) {
            throw new \Exception("Service '{$serviceName}' not found", 404);
        }

        $handler = self::$services[$serviceName];
        return $handler($action, $params);
    }

    /**
     * Get all registered services
     */
    public static function getServices(): array
    {
        return array_keys(self::$services);
    }

    /**
     * Check if a service is registered
     */
    public static function hasService(string $serviceName): bool
    {
        return isset(self::$services[$serviceName]);
    }
}
