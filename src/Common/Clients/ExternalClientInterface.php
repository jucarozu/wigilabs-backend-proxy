<?php
namespace Wigilabs\Common\Clients;

/**
 * Interface for external service clients (SOAP/REST).
 * Ensures that all implementations have a standard method for obtaining products.
 */
interface ExternalClientInterface {
    /**
     * Obtains a product from the external service.
     *
     * @param string $id Product ID
     * @return array Product data in associative format
     */
    public function getProduct(string $id): array;
}